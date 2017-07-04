<?php
/**
 * Created by PhpStorm.
 * User: hc
 * Date: 2016/12/5
 * Time: 13:28
 */

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Models\QrComments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

class QrCommentController extends Controller
{
    /**
     * @api 添加二维码评论
     * @module 树牌扫码
     * @url api/qr-comments/add
     * @method post
     * @in qr_tag_id int 二维码ID
     * @in? image file 图片文件
     * @in? describe string 文字描述,留言内容
     * @in? phone string 手机号码
     * @in? password string 提取密码
     * @in? status string 状态
     */
    public function postAdd(Request $request)
    {
        $this->validate($request, [
            'qr_tag_id' => 'required|integer',
        ], [
            'qr_tag_id.required' => '缺少参数二维码ID',
            'qr_tag_id.integer' => '参数二维码ID需为整数',
        ]);

        $qr_tag_id = (int)$request->get('qr_tag_id');
        $image = $request->file('image');
        $describe = $request->get('describe');
        $phone = $request->get('phone');
        $password = $request->get('password');
        $status = $request->get('status');

        if (!$status) $status = 0;

        $key = '';
        if ($image) {
            $filename = md5(uniqid(mt_rand())) . '.' . $image->getClientOriginalExtension();
            $auth = new Auth(env('QI_NIU_ACCESS_KEY'), env('QI_NIU_SECRET_KEY'));
            //要上传的空间
            $bucket = env('BUCKET');
            $token = $auth->uploadToken($bucket);
            //上傳到七牛後保存的文件名
            $key = 'qr-comments/' . $qr_tag_id . '/' . $filename;
            //初始化UploadManager對象並進行文件上傳
            $uploadMgr = new UploadManager();
            list($ret, $err) = $uploadMgr->putFile($token, $key, $image->getRealPath());//直接从文件临时地址读取
            if ($err != null) {
                Log::error('评论提交失败，' . (array)$err);
                return $this->error('评论提交失败！');
            }
        }
        QrComments::create(array(
            'qr_tag_id' => $qr_tag_id,
            'image' => $key,
            'describe' => $describe,
            'status' => $status,
            'phone' => $phone,
            'password' => $password,
        ));
        return $this->ok();
    }

    /**
     * @api 查询二维码评论
     * @module 树牌扫码
     * @url api/qr-comments
     * @method get
     * @in qr_tag_id int 二维码ID
     * @in? phone string 手机号码
     * @in? password string 提取密码
     * @out image string 图片url
     * @out describe string 文字描述
     * @out created_at string 创建时间
     */
    public function getIndex(Request $request)
    {
        $this->validate($request, [
            'qr_tag_id' => 'required|integer',
        ], [
            'qr_tag_id.required' => '缺少参数二维码ID',
            'qr_tag_id.integer' => '参数二维码ID需为整数',
        ]);
        $qr_tag_id = (int)$request->get('qr_tag_id');
        $phone = $request->get('phone');
        $password = $request->get('password');

        $data = array(
            'qr_tag_id' => $qr_tag_id,
            'status' => 1,
        );
        if ($phone) {
            $data['phone'] = $phone;
        }
        if ($password) {
            $data['password'] = $password;
        }

        if (empty($phone) && empty($password)) {
            $data['phone'] = '';
            $data['password'] = '';
        }
        $list = QrComments::select('image', 'describe', 'created_at')->where($data)
            ->get();
        foreach ($list as $vo) {
            $vo->image = env('QI_NIU_HTTPS_URL') . $vo->image;
        }
        return $this->ok($list);
    }

    /**
     * @api 查询所有二维码评论最新一张照片
     * @module 树牌扫码
     * @url api/qr-comments/latest-photo
     * @method get
     * @in qr_tag_id int 二维码ID
     * @out qr_tag_id int 二维码ID
     * @out image string 图片url
     * @out describe string 文字描述
     * @out longitude string 经度
     * @out latitude string 纬度
     */
    public function getLatestPhoto(Request $request)
    {
        $this->validate($request, [
            'qr_tag_id' => 'required|integer',
        ], [
            'qr_tag_id.required' => '缺少参数二维码ID',
            'qr_tag_id.integer' => '参数二维码ID需为整数',
        ]);
        $qr_tag_id = (int)$request->get('qr_tag_id');
        //根据二维码标签ID反向查询对应的客户ID
        $customer_id = DB::table('qr_tags')->where('id', $qr_tag_id)->pluck('customer_id');
        //根据客户ID查询该客户所有二维码的评论的第一张图片
        $query = DB::table('qr_tags as qt')
            ->join('qr_comments as qc', 'qt.id', '=', 'qc.qr_tag_id')
            ->select('qt.id', 'qc.image', 'qc.describe', 'qt.longitude', 'qt.latitude', 'qc.created_at')
            ->whereNotExists(function ($query) {
                $query->from('qr_comments as qc1')
                    ->whereRaw('qt.id = qc1.qr_tag_id and qc1.id>qc.id');
            })
            ->where('qt.customer_id', $customer_id)
            ->get();

        foreach ($query as $vo) {
            $vo->image = env('QI_NIU_HTTPS_URL') . $vo->image;
        }
        return $this->ok($query);
    }
}
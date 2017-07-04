<?php
/**
 * Created by PhpStorm.
 * User: hc
 * Date: 16/4/5
 * Time: 上午9:40
 */

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Models\QrScanning;
use App\Http\Models\QrTags;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Qiniu\Auth;
use Qiniu\Storage\BucketManager;

class QrTagController extends Controller
{
    /**
     * @api 植物详情
     * @module 树牌扫码
     * @url api/qr/qr-detail
     * @method get
     * @in? qr_tag_id int 二维码ID
     * @in? qr_code string 二维码编码
     * @out qr_tag_id string 二维码ID
     * @out plant_code string 植物编码
     * @out plant_name string 植物名称
     * @out latin_name string 植物拉丁名
     * @out family string 科
     * @out genus string 属
     * @out alias string 常用别名
     * @out cover string 封面
     * @out origin string 产地分布
     * @out introduct_profile string 观赏特性简介
     * @out introduct string 观赏特性
     * @out form string 形态简介
     * @out Longitude double 经度
     * @out latitude double 纬度
     * @out plantNeedTabooObj array 注意事项数组
     * @out plantTraitObj array 植物特性数组
     * @remark plantNeedTabooObj包括[content注意内容，sort排序]
     * @remark plantTraitObj包括[title标题，content内容，sort排序]
     * @remark qr_tag_id/qr_code两者不能同时为空
     */
    public function getQrDetail(Request $request)
    {
        $qr_tag_id = $request->get('qr_tag_id');
        $qr_code = $request->get('qr_code');

        if (empty($qr_tag_id) && empty($qr_code)) {
            Log::error('qr_tag_id和qr_code不能同时为空！');
            return $this->error('缺少参数！');
        }

        $param = [];
        if ($qr_tag_id)
            $param['id'] = $qr_tag_id;
        if ($qr_code)
            $param['qr_code'] = $qr_code;

        //根据得到的二维码ID查询植物编码
        $qr_tag = QrTags::where($param)->first();
        $query = '';
        if ($qr_tag) {
            //更新扫描记录
            QrScanning::create([
                'qr_tag_id' => $qr_tag->id,
            ]);
            //根据植物编码查询植物基础信息
            $query = DB::table('plant_basics')->select('plant_code', 'plant_name', 'latin_name', 'family', 'genus', 'alias', 'introduct_profile',
                'introduct', 'cover', 'origin', 'form')->where('plant_code', $qr_tag->plant_code)->first();
            if (empty($query)) {
                return $this->error('该二维码对应的植物不存在！');
            }
            //根据植物编码查询植物特征
            $plantTrait = DB::table('plant_traits')->select('title', 'content', 'sort')->where('plant_code', $qr_tag->plant_code)->get();
            //根据植物编码查询注意事项
            $plantNeedTaboo = DB::table('plant_need_taboos')->select('content', 'sort')->where('plant_code', $qr_tag->plant_code)->get();

            $query->cover = env('QI_NIU_HTTPS_URL') . $query->plant_code . '/cover.jpg';
            $query->qr_tag_id = $qr_tag->id;
            $query->longitude = $qr_tag->longitude;
            $query->latitude = $qr_tag->latitude;
            $query->plantTraitObj = $plantTrait;
            $query->plantNeedTabooObj = $plantNeedTaboo;
        }
        return $this->ok($query);
    }

    /**
     * @api 植物图片
     * @module 树牌扫码
     * @url api/qr/qr-image
     * @method get
     * @in plant_code string 植物编码
     * @out plant_img string 植物图片url
     * @out plant_name string 植物名称
     * @out sort int 排序
     */
    public function getQrImage(Request $request)
    {
        $this->validate($request, ['plant_code' => 'required'], [
            'plant_code.required' => '植物编码不能为空！'
        ]);
        $plant_code = $request->get('plant_code');

        //根据植物编码查出植物名称
        $plant_name = DB::table('plant_basics')->where('plant_code', $plant_code)->pluck('plant_name');
        $images = [];
        if ($plant_name) {
            //从七牛取出图片集
            $auth = new Auth(env('QI_NIU_ACCESS_KEY'), env('QI_NIU_SECRET_KEY'));
            $bucketMgr = new BucketManager($auth);

            list($iterms, $marker, $err) = $bucketMgr->listFiles($bucket = env('BUCKET'), $plant_code . '/');
            if ($err == null) {
                $i = 1;
                foreach ($iterms as $vo) {
                    if ($vo['key'] != $plant_code . '/cover.jpg') {
                        $images[] = array(
                            'plant_img' => env('QI_NIU_HTTPS_URL') . $vo['key'],
                            'sort' => $i,
                            'remark' => $i . '-' . $plant_name
                        );
                        $i++;
                    }
                }
            }
        }
        return $this->ok($images);
    }

    /**
     * @api 客户简介
     * @module 树牌扫码
     * @url api/qr/customer
     * @method get
     * @in qr_tag_id int 二维码ID
     * @out icon string 图标图片url
     * @out customer_name string 客户名称
     * @out dept string 客户简介
     */
    public function getCustomer(Request $request)
    {
        $this->validate($request, ['qr_tag_id' => 'required'], [
            'qr_tag_id.required' => '二维码参数为空'
        ]);
        $qr_tag_id = $request->get('qr_tag_id');

        //根据得到的二维码ID查询客户信息
        $customer_id = DB::table('qr_tags')->where('id', $qr_tag_id)->pluck('customer_id');
        if (empty($customer_id)) return $this->error('该二维码对应的客户不存在！');
        $query = DB::table('qr_customers')->select('icon', 'customer_name', 'dept')->where('id', $customer_id)->first();
        $query->icon = env('QI_NIU_HTTPS_URL') . 'customer/' . $query->icon;
        return $this->ok($query);
    }

    /**
     * @api 所有二维码位置
     * @module 树牌扫码
     * @url api/qr/qr-address
     * @method get
     * @out id int 二维码id
     * @out plant_code string 植物编码
     * @out plant_name string 植物名称
     * @out longitude double 经度
     * @out latitude double 纬度
     */
    public function getQrAddress()
    {
        $query = DB::table('qr_tags as qt')
            ->join('plant_basics as pb', 'qt.plant_code', '=', 'pb.plant_code')
            ->select('qt.id', 'qt.plant_code', 'pb.plant_name', 'qt.longitude', 'qt.latitude')
            ->get();
        return $this->ok($query);
    }
}
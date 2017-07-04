<?php
/**
 * Created by PhpStorm.
 * User: hc
 * Date: 16/3/31
 * Time: 上午10:12
 */

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Qiniu\Auth;
use Qiniu\Storage\BucketManager;

class PlantBasicController extends Controller
{
    /**
     * @api 植物列表
     * @module 植物百科
     * @url api/plant-basic
     * @method get
     * @in? category string 分类查询
     * @in? letter string 首字母
     * @in page int 页数
     * @in rows int 每页显示数
     * @in? difficulty int 养护难度 1非常容易、2容易、3一般，4难，5很难
     * @out id int 植物ID
     * @out plant_code string 植物编码
     * @out plant_name string 植物名称
     * @out latin_name string 植物拉丁名
     * @out family string 科
     * @out genus string 属
     * @out difficulty int 养护难度
     * @out cover string 封面图片url
     */
    public function getIndex(Request $request)
    {
        $category = $request->get('category');
        $letter = $request->get('letter');
        $difficulty = $request->get('difficulty');
        $page = $request->get('page');
        $rows = $request->get('rows');

        $query = DB::table('plant_basics')->select('id', 'plant_code', 'plant_name', 'latin_name', 'family', 'genus', 'difficulty', 'cover');

        if ($letter) {
            $query = $query->where('latin_name', 'like', $letter . '%');
        }
        if ($difficulty) {
            $query = $query->where('difficulty', $difficulty);
        }
        if ($category) {
            if ($difficulty) {
                $query = $query->where('type_one', $category)->orWhere(function ($query) use ($category, $difficulty) {
                    $query->where(['type_two' => $category, 'difficulty' => $difficulty]);
                });
            } else {
                $query = $query->where('type_one', $category)->orWhere(function ($query) use ($category) {
                    $query->where('type_two', $category);
                });
            }
        }

        $query = $query->orderBy('sort')->orderBy('latin_name')->skip(($page - 1) * $rows)->take($rows)->get();

        foreach ($query as $vo) {
            $vo->cover = env('QI_NIU_HTTPS_URL') . $vo->plant_code . '/cover.jpg';
        }
        return $this->ok($query);
    }

    /**
     * @api 名称搜索植物
     * @module 植物百科
     * @url api/plant-basic/list-by-name
     * @method get
     * @in plant_name string 植物名称
     * @out id int 植物ID
     * @out plant_code string 植物编码
     * @out plant_name string 植物名称
     * @out latin_name string 植物拉丁名
     * @out family string 科
     * @out genus string 属
     * @out difficulty int 养护难度
     * @out cover string 封面图片url
     */
    public function getListByName(Request $request)
    {
        $plant_name = $request->get('plant_name');
        $query = DB::table('plant_basics')->select('id', 'plant_code', 'plant_name', 'latin_name', 'family', 'genus', 'difficulty', 'cover');
        if ($plant_name) {
            $query = $query->where('plant_name', 'like', '%' . $plant_name . '%');
        } else {
            $query = $query->orderBy('hot', 'desc')->take(5);
        }
        $query = $query->get();

        foreach ($query as $vo) {
            $vo->cover = env('QI_NIU_HTTPS_URL') . $vo->plant_code . '/cover.jpg';
        }
        return $this->ok($query);
    }

    /**
     * @api 植物详情
     * @module 植物百科
     * @url api/plant-basic/plant-detail
     * @method get
     * @in plant_code string 植物编码
     * @out plant_code string 植物编码
     * @out plant_name string 植物名称
     * @out latin_name string 植物拉丁名
     * @out family string 科
     * @out genus string 属
     * @out alias string 植物别名
     * @out introduct_profile string 植物简介概要
     * @out introduct string 植物简介
     * @out application_profile string 生活应用价值概要
     * @out application string 生活应用价值
     * @out habit_profile string 植物习性概要
     * @out habit string 植物习性
     * @out difficulty int 养护难度
     * @out cover string 封面图片url
     */
    public function getPlantDetail(Request $request)
    {
        $this->validate($request, ['plant_code' => 'required'],
            ['plant_code.required' => '植物编码不能为空！']);
        $plant_code = $request->get('plant_code');

        $query = DB::table('plant_basics')->select('plant_code', 'plant_name', 'latin_name', 'family', 'genus',
            'alias', 'introduct_profile', 'introduct', 'application_profile', 'application', 'habit_profile', 'habit', 'difficulty', 'cover')
            ->where('plant_code', $plant_code)->first();

        if ($query) {
            $query->cover = env('QI_NIU_HTTPS_URL') . $query->plant_code . '/cover.jpg';
        }
        return $this->ok($query);
    }


    /**
     * @api 注意事项
     * @module 植物百科
     * @url api/plant-basic/need-taboo
     * @method get
     * @in plant_code string 植物编码
     * @out content string 注意内容
     * @out sort int 排序
     */
    public function getNeedTaboo(Request $request)
    {
        $this->validate($request, ['plant_code' => 'required'],
            ['plant_code.required' => '植物编码不能为空！']);
        $plant_code = $request->get('plant_code');

        $query = DB::table('plant_need_taboos')->select('content', 'sort')->where('plant_code', $plant_code)->orderBy('sort')->get();

        $knowledges = plantKnowledgeCache();

        foreach ($query as $vo) {
            $content = replaceKnowledge($knowledges, $vo->content);
            $vo->content = $content;
        }

        return $this->ok($query);
    }

    /**
     * @api 单个养护要点
     * @module 植物百科
     * @url api/plant-basic/points
     * @method get
     * @in plant_code string 植物编码
     * @in regional string 养护地域 暂时只有'华南',参数请传递对应的拼音,'例如huanan'
     * @out plant_code string 植物编码
     * @out maintenance_name string 养护名称
     * @out maintenance_title string 养护标题
     * @out content_profile string 养护内容概要
     * @out content string 养护内容
     * @out maintenance_time string 养护时间
     * @out sort int 排序
     * @remark 只显示当前季度的养护要点
     */
    public function getPoints(Request $request)
    {
        $this->validate($request,
            ['plant_code' => 'required', 'regional' => 'required'],
            ['plant_code.required' => '植物编码不能为空！',
                'regional.required' => '养护地域不能为空！']
        );

        $plant_code = $request->get('plant_code');
        $regional = $request->get('regional');

        //根据植物编码、季度和区域查询植物养护要点
        //得到当前月份,根据月份判断获取那一季度的养护要点
        $thismonth = date('m');
        $spring = array('03', '04', '05');
        $summer = array('06', '07', '08');
        $autumn = array('09', '10', '11');
        $winter = array('01', '02', '12');

        if (in_array($thismonth, $spring)) {
            $season = '春';
        }
        if (in_array($thismonth, $summer)) {
            $season = '夏';
        }
        if (in_array($thismonth, $autumn)) {
            $season = '秋';
        }
        if (in_array($thismonth, $winter)) {
            $season = '冬';
        }

        if ($regional == 'huanan') {
            $regional = '华南';
        }

        $query = DB::table('plant_points')->select('plant_code', 'maintenance_time', 'maintenance_name', 'maintenance_title', 'content_profile',
            'content', 'sort')->where(['plant_code' => $plant_code, 'regional' => $regional, 'season' => $season])->orderBy('sort')->get();

        $knowledge = plantKnowledgeCache();

        foreach ($query as $vo) {
            $content = replaceKnowledge($knowledge, $vo->content);
            $vo->content = $content;
        }
        return $this->ok($query);
    }

    /**
     * @api 全部养护要点
     * @module 植物百科
     * @url api/plant-basic/points-list
     * @method get
     * @in plant_code string 植物编码
     * @in regional string 养护地域 暂时只有'华南',参数请传递对应的拼音,例如'huanan'
     * @out plant_code string 植物编码
     * @out maintenance_name string 养护名称
     * @out maintenance_title string 养护标题
     * @out content_profile string 养护内容概要
     * @out content string 养护内容
     * @out maintenance_time string 养护时间
     * @out sort int 排序
     */
    public function getPointsList(Request $request)
    {
        $this->validate($request,
            ['plant_code' => 'required', 'regional' => 'required'],
            ['plant_code.required' => '植物编码不能为空！',
                'regional.required' => '养护地域不能为空！']
        );

        $plant_code = $request->get('plant_code');
        $regional = $request->get('regional');

        if ($regional == 'huanan') {
            $regional = '华南';
        }
        $query = DB::table('plant_points')->select('plant_code', 'maintenance_time', 'maintenance_name', 'maintenance_title', 'content_profile',
            'content', 'season', 'sort')->where(['plant_code' => $plant_code, 'regional' => $regional])->get();

        $knowledge = plantKnowledgeCache();

        $spring = [];
        $summer = [];
        $autumn = [];
        $winter = [];
        foreach ($query as $vo) {
            $content = replaceKnowledge($knowledge, $vo->content);
            $vo->content = $content;

            if ($vo->season == '春') {
                $spring[] = $vo;
            }
            if ($vo->season == '夏') {
                $summer[] = $vo;
            }
            if ($vo->season == '秋') {
                $autumn[] = $vo;
            }
            if ($vo->season == '冬') {
                $winter[] = $vo;
            }
        }
        $query = array_merge($spring, $summer, $autumn, $winter);


        return $this->ok($query);
    }

    /**
     * @api 植物图片
     * @module 植物百科
     * @url api/plant-basic/images
     * @method get
     * @in plant_code string 植物编码
     * @out plant_img string 植物图片url
     * @out plant_name string 植物名称
     * @out sort int 排序
     */
    public function getImages(Request $request)
    {
        $this->validate($request, [
            'plant_code' => 'required'
        ], [
            'plant_code.required' => '植物编码不能为空！'
        ]);
        $plant_code = $request->get('plant_code');

        //根据植物编码查出植物名称
        $plant_name = DB::table('plant_basics')->where('plant_code', $plant_code)->pluck('plant_name');

        //从七牛取出图片集
        $auth = new Auth(env('QI_NIU_ACCESS_KEY'), env('QI_NIU_SECRET_KEY'));
        //要上传的空间
        $bucket = env('BUCKET');
        $bucketMgr = new BucketManager($auth);

        list($iterms, $marker, $err) = $bucketMgr->listFiles($bucket, $plant_code . '/');
        $images = [];
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
        return $this->ok($images);
    }

    /**
     * @api 帖子
     * @module 植物百科
     * @url api/plant-basic/post
     * @method get
     * @in plant_code string 植物编码
     * @out title string 标题
     * @out href string 链接
     */
    public function getPost(Request $request)
    {
        $this->validate($request, ['plant_code' => 'required'], [
            'plant_code.required' => '植物编码不能为空！'
        ]);
        $plant_code = $request->get('plant_code');
        //根据植物编码查找植物对应帖子
        $query = DB::table('plant_posts')->select('title', 'href')->where('plant_code', $plant_code)->get();

        return $this->ok($query);
    }

    /**
     * @api 植物热度搜索
     * @module 植物百科
     * @url api/plant-basic/hot
     * @method get
     * @in? plant_name string 植物名称
     * @out id int 植物ID
     * @out plant_code string 植物编码
     * @out plant_name string 植物名称
     * @out latin_name string 植物拉丁名
     * @out family string 科
     * @out genus string 属
     * @out difficulty int 养护难度
     * @out cover string 封面图片url
     * @remark 不传参默认显示热度最高的五条数据
     */
    public function getHot(Request $request)
    {
        $plant_name = $request->get('plant_name');
        $query = DB::table('plant_basics')->select('id', 'plant_code', 'plant_name', 'latin_name', 'family', 'genus', 'difficulty', 'cover');
        if ($plant_name) {
            $query = $query->where('plant_name', $plant_name)->first();
        } else {
            $query = $query->orderBy('hot', 'desc')->take(5)->get();
        }
        if (count($query) > 1) {
            foreach ($query as $vo) {
                $vo->cover = env('QI_NIU_HTTPS_URL') . $vo->plant_code . '/cover.jpg';
            }
        } else if (count($query) > 0) {
            DB::table('plant_basics')->increment('hot');
            $query->cover = env('QI_NIU_HTTPS_URL') . $query->plant_code . '/cover.jpg';
        } else {
            $query = array();
        }
        return $this->ok($query);
    }
} 
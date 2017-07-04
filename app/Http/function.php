<?php
/**
 * 公共自定义函数类
 * Created by PhpStorm.
 * User: hc
 * Date: 2017/6/1
 * Time: 9:29
 */
use \Illuminate\Support\Facades\Cache;

/**
 * @param $id
 * @return string
 * 植物养护难度
 */
function getDifficulty($id = '')
{
    $difficulty = [
        ['id' => 1, 'text' => '非常容易'],
        ['id' => 2, 'text' => '容易'],
        ['id' => 3, 'text' => '一般'],
        ['id' => 4, 'text' => '难'],
        ['id' => 5, 'text' => '很难']
    ];
    if (!empty($id)) {
        switch ($id) {
            case 1:
                $difficulty = '非常容易';
                break;
            case 2:
                $difficulty = '容易';
                break;
            case 3:
                $difficulty = '一般';
                break;
            case 4:
                $difficulty = '难';
                break;
            case 5:
                $difficulty = '很难';
                break;
        }
    }
    return $difficulty;
}

/**
 * @param $id
 * @return string
 * 植物喜水习性
 */
function getWaterhabit($id = '')
{
    $waterhabit = [
        ['id' => 'H', 'text' => '保持湿润高'],
        ['id' => 'M', 'text' => '见干见湿中'],
        ['id' => 'L', 'text' => '常规绿植（低）'],
        ['id' => 'S', 'text' => '干透浇透多肉（低）'],
    ];
    if (!empty($id)) {
        switch ($id) {
            case 'H':
                $waterhabit = '保持湿润高';
                break;
            case 'M':
                $waterhabit = '见干见湿中';
                break;
            case 'L':
                $waterhabit = '常规绿植（低）';
                break;
            case 'S':
                $waterhabit = '干透浇透多肉（低）';
                break;
        }
    }
    return $waterhabit;
}

/**
 * @return \Illuminate\Database\Eloquent\Collection|static[]
 * 获取植物种类名称
 */
function getSpecies()
{
    return $species = \App\Http\Models\PlantSpecies::all();
}

/**
 * @param $id
 * @return string
 * 根据种类ID查询种类
 */
function getSpeciesById($id)
{
    $species = '';
    if (!empty($id)) {
        $species = \App\Http\Models\PlantSpecies::where('id', $id)->pluck('name');
    }
    return $species;
}

/**
 * @return \Illuminate\Database\Eloquent\Collection|static[]
 * 获取所有分类
 */
function getTypes()
{
    return \App\Http\Models\PlantTypes::all();
}

/**
 * @param string $id
 * @return array|string
 * 获取养护时间段
 */
function getMaintenanceTime($id = '')
{
    return [
        ['id' => '春季（3-5月）的养护', 'text' => '春季（3-5月）的养护'],
        ['id' => '夏季（6-8月）的养护', 'text' => '夏季（6-8月）的养护'],
        ['id' => '秋季（9-11月）的养护', 'text' => '秋季（9-11月）的养护'],
        ['id' => '冬季（12-2月）的养护', 'text' => '冬季（12-2月）的养护'],
    ];
}

/**
 * @param string $id
 * @return array|string
 * 获取养护名称
 */
function getMaintenanceName($id = '')
{
    return [
        ['id' => '光照', 'text' => '光照'],
        ['id' => '水分', 'text' => '水分'],
        ['id' => '温度', 'text' => '温度'],
        ['id' => '基质', 'text' => '基质'],
        ['id' => '肥料', 'text' => '肥料'],
        ['id' => '病虫害', 'text' => '病虫害'],
    ];
}

/**
 * @return array
 * 获取养护地域
 */
function getRegional()
{
    return [
        ['id' => '华南', 'text' => '华南'],
        ['id' => '香港', 'text' => '香港'],
    ];
}

/**
 * @return array
 * 获取植物特征名称
 */
function getTitle()
{
    return [
        ['id' => '最佳观赏期', 'text' => '最佳观赏期'],
        ['id' => '名字由来', 'text' => '名字由来'],
        ['id' => '花语', 'text' => '花语'],
        ['id' => '故事典籍及其他', 'text' => '故事典籍及其他'],
        ['id' => '其他应用', 'text' => '其他应用'],
        ['id' => '注意事项', 'text' => '注意事项'],
    ];
}

/**
 * @return mixed
 * 缓存植物养护知识/干货
 */
function plantKnowledgeCache()
{
    if (Cache::has('plant_knowledge')) {
        $knowledges = Cache::get('plant_knowledge');
    } else {
        $knowledges = \App\Http\Models\PlantKnowledges::all();
        $expiresAt = \Carbon\Carbon::now()->addMinutes(60);
        Cache::put('plant_knowledge', $knowledges, $expiresAt);
    }
    return $knowledges;
}


/**
 * @param $knowledges
 * @param $content
 * @return mixed
 *  养护知识关键字匹配
 */
function replaceKnowledge($knowledges, $content)
{
    foreach ($knowledges as $vo) {
        $content = str_replace($vo['name'], '<a href="' . $vo['link'] . '">' . $vo['name'] . '</a>', $content);//替换
    }
    return $content;
}
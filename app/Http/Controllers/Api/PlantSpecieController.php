<?php
/**
 * Created by PhpStorm.
 * User: hc
 * Date: 16/5/18
 * Time: 下午1:55
 */

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Models\PlantBasics;
use App\Http\Models\SpeciesRelationTools;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlantSpecieController extends Controller
{
    /**
     * @api 种类列表
     * @module 植物百科
     * @url api/species
     * @method get
     * @out id int ID
     * @out name string 种类名称
     */
    public function getIndex()
    {
        $query = DB::table('plant_species')->select('id', 'name')->get();
        return $this->ok($query);
    }

    /**
     * @api 养护难度列表
     * @module 植物百科
     * @url api/species/difficultys
     * @out id int ID
     * @out name string 难度名称
     */
    public function getDifficultys()
    {
        $difficulty = array(
            ['id' => 1, 'name' => '非常容易'],
            ['id' => 2, 'name' => '容易'],
            ['id' => 3, 'name' => '一般'],
            ['id' => 4, 'name' => '难'],
            ['id' => 5, 'name' => '很难']
        );
        return $this->ok($difficulty);
    }

    /**
     * @api 养护工具
     * @module 植物百科
     * @url api/species/tools
     * @in plant_code string 植物编码
     * @out id int ID
     * @out name string 工具名称
     * @out image string 工具图标
     * @out buy_link string 链接
     */
    public function getTools(Request $request)
    {
        $this->validate($request, ['plant_code' => 'required'], [
            'plant_code.required' => '植物编码不能为空！'
        ]);
        $plant_code = $request->query->get('plant_code');

        $plant_basic = PlantBasics::select('type_one', 'recommend_tools')->where('plant_code', $plant_code)->first();
        $tools_id = SpeciesRelationTools::where('species_id', $plant_basic->type_one)->pluck('tools_id');

        if (!empty($plant_basic->recommend_tools)) {
            $tools_id = $tools_id . ',' . $plant_basic->recommend_tools;
        }

        $tools_ids = explode(',', $tools_id);
        $query = DB::table('plant_tools')->select('id', 'name', 'image', 'buy_link')->whereIn('id', $tools_ids)->get();
        foreach ($query as $vo) {
            $vo->image = env('QI_NIU_HTTPS_URL') . 'tools/' . $vo->image;
        }
        return $this->ok($query);
    }
} 
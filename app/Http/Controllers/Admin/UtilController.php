<?php
/**
 * Created by PhpStorm.
 * User: hc
 * Date: 2017/5/12
 * Time: 13:50
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Models\PlantBasics;
use App\Http\Models\PlantTools;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Sentry;

class UtilController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * 获取重置密码的密钥
     */
    public function getResetPasswordCode(Request $request)
    {
        $user = Sentry::findUserById((int)$request->get('id'));
        $resetCode = $user->getResetPasswordCode();
        if ($resetCode) {
            return $this->ok(['code' => $resetCode]);
        } else {
            return $this->error('获取失败!');
        }
    }

    public function getEditor(Request $request)
    {
        return view("admin.plant-basic.autocompleter");
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * 根据关键字搜索植物养护工具
     */
    public function getPlantToolsData(Request $request)
    {
        $key = $request->get('term');
        $data = [];
        if ($key) {
            $data = PlantTools::where('name', 'like', '%' . $key . '%')->select('id as value', 'name as label')->get();
        }
        return response()->json($data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * 根据关键字获取植物
     */
    public function getPlantData(Request $request)
    {
        $key = $request->get('term');
        $data = [];
        if ($key) {
            $data = PlantBasics::where('plant_name', 'like', '%' . $key . '%')->select('plant_code as value', 'plant_name as label')->get();
        }
        return response()->json($data);
    }
}
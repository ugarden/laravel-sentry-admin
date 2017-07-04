<?php
/**
 * Created by PhpStorm.
 * User: hc
 * Date: 2017/5/11
 * Time: 14:24
 */

namespace App\Http\Controllers\Admin;


use App\Classes\OperationExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Sentry;
use DB;

/**
 * @module 3-权限管理
 */
class OperationController extends Controller
{

    /**
     * @operation 查看
     */
    public function getIndex(Request $request)
    {
        $group_id = (int)$request->get('id');

        $permissions = [];//定义该用户已有的权限
        $data = [];//定义所有可供操作权限
        if ($group_id) {
            $obj = Sentry::findGroupById($group_id);
            $groupPermissions = $obj->getPermissions();

            if ($groupPermissions) {
                foreach ($groupPermissions as $k => $v) {
                    if ($v == 0) {//当权限为不允许时
                        continue;
                    }
                    $permissions[] = $k;
                }
            }

            $rows = DB::table('operations')->orderBy('sort')->get();
            foreach ($rows as $row) {
                $data[$row->module_name][$row->operation_name][] = $row->action;
            }
        }
        return view('admin.operation.index', ['group_id' => $group_id, 'data' => $data, 'group_operation' => $permissions]);
    }

    /**
     * @operation 创建
     */
    public function postIndex(Request $request)
    {
        $this->validate($request, ['id' => 'required|integer', 'operation' => 'array']);
        $id = (int)$request->get('id');
        $operation = (array)$request->get('operation');

        //通过分组ID查找分组
        $group = Sentry::findGroupById($id);

        $permissions = [];
        foreach ($operation as $v) {
            $permissions[$v['key']] = $v['value'];
        }
        $group->permissions = $permissions;
        if ($group->save()) {
            return $this->ok();
        } else {
            return $this->error('授权失败！');
        }
    }

    /**
     * @operation 刷新
     */
    public function putIndex(Request $request)
    {
        $operationExport = new OperationExport(app_path("Http/Controllers/Admin"));
        $operationExport->setNamespace("App\\Http\\Controllers\\Admin");
        $operations = $operationExport->export();

        foreach ($operations as $v) {
            foreach ($v['operation'] as $v1) {
                $data = [
                    'module_name' => $v['module'],
                    'operation_name' => $v1['name'],
                    'action' => $v1['action']
                ];
                isset($v['sort']) and $data['sort'] = $v['sort'];

                $row = DB::table('operations')->where('action', $data['action'])->first();
                if ($row)
                    DB::table('operations')->where('id', $row->id)->update($data);
                else
                    DB::table('operations')->insert($data);
            }
        }
        return $this->ok();
    }
}
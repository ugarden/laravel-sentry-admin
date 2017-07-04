<?php
/**
 * Created by PhpStorm.
 * User: hc
 * Date: 2017/5/11
 * Time: 16:10
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper;
use Cartalyst\Sentry\Groups\GroupExistsException;
use Cartalyst\Sentry\Groups\GroupNotFoundException;
use Cartalyst\Sentry\Groups\NameRequiredException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Sentry;

/**
 * @module 2-角色管理
 */
class GroupController extends Controller
{
    use Helper;

    /**
     * @operation 查看
     */
    public function getShow()
    {
        return view('admin.group.index');
    }

    /**
     * @operation 查看
     */
    public function getIndex(Request $request)
    {
        $name = $request->get('name');

        list($skip, $limit) = $this->getSkipLimit($request);

        $recordsTotal = DB::table('groups')->count();
        $out['data'] = DB::table('groups');

        if ($name) {
            $out['data'] = $out['data']->where('name', $name);
            $out['recordsFiltered'] = $out['data']->where('name', $name)->count();
        } else {
            $out['recordsFiltered'] = $recordsTotal;
        }

        $out['recordsTotal'] = $recordsTotal;
        $out['data'] = $out['data']->orderBy('created_at', 'desc')->take($limit)->skip($skip)->get();
        $out['draw'] = $request->get('draw'); //获取Datatables发送的参数，必要，这个值直接返回给前端

        return response()->json($out);
    }

    /**
     * @operation 查看角色对应用户列表
     */
    public function getUserList(Request $request)
    {
        $id = (int)$request->get('id');
        if ($id) {
            $group = Sentry::findGroupById($id);
            // 查出分组下的所有用户
            $users = Sentry::findAllUsersInGroup($group);
        } else {
            Log::info("查找角色对应用户列表失败！");
            return false;
        }
        return view('admin.group.users', ['users' => $users]);
    }

    /**
     * @operation 创建
     */
    public function postIndex(Request $request)
    {
        try {
            // 创建分组即角色
            Sentry::createGroup(array(
                'name' => $request->get('name')
            ));
        } catch (NameRequiredException $e) {
            return $this->error('角色名称必须存在,请输入角色！');
        } catch (GroupExistsException $e) {
            return $this->error('角色已经存在，请重新输入！');
        }
        return $this->ok();
    }

    /**
     * @operation 编辑
     */
    public function putIndex(Request $request)
    {
        $id = (int)$request->get('id');
        $this->validate($request, [
            'id' => 'required|integer',
            'name' => 'required|unique:groups,name,' . $id,
        ], [
            'id.required' => '角色ID不存在！',
            'name.unique' => '该角色已存在，请重新输入！',
        ]);

        // 通过 分组ID 查找分组
        $group = Sentry::findGroupById($id);

        // 更新分组详情
        $group->name = $request->get('name');

        // 更新分组
        if ($group->save()) {
            return $this->ok();
        } else {
            return $this->error('更新失败！');
        }
    }

    /**
     * @operation 删除
     */
    public function deleteIndex(Request $request)
    {
        try {
            // 通过 分组ID 查找分组
            $group = Sentry::findGroupById((int)$request->get('id'));
            // 删除分组
            $group->delete();
        } catch (GroupNotFoundException $e) {
            return $this->error('角色不存在！');
        }
        return $this->ok();
    }
}
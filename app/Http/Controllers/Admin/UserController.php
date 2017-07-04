<?php
/**
 * Created by PhpStorm.
 * User: hc
 * Date: 2017/5/9
 * Time: 14:18
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper;
use App\Http\Models\Users;
use Cartalyst\Sentry\Groups\GroupNotFoundException;
use Cartalyst\Sentry\Users\UserExistsException;
use Cartalyst\Sentry\Users\UserNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Sentry;
use DB;

/**
 * @module 1-用户管理
 */
class UserController extends Controller
{
    use Helper;

    /**
     * @operation 查看
     */
    public function getShow()
    {
        return view('admin.user.index');
    }

    /**
     * @operation 查看
     */
    public function getIndex(Request $request)
    {
        $email = $request->get('email');

        list($skip, $limit) = $this->getSkipLimit($request);

        $recordsTotal = Users::count();
        $out['data'] = DB::table('users');

        if ($email) {
            $out['data'] = $out['data']->where('email', $email);
            $out['recordsFiltered'] = $out['data']->where('email', $email)->count();
        } else {
            $out['recordsFiltered'] = $recordsTotal;
        }

        $out['recordsTotal'] = $recordsTotal;
        $out['data'] = $out['data']->orderBy('created_at', 'desc')->take($limit)->skip($skip)->get();
        $out['draw'] = $request->get('draw'); //获取Datatables发送的参数，必要，这个值直接返回给前端

        return response()->json($out);
    }

    /**
     * @operation 创建
     */
    public function getCreate()
    {
        //获取所有分组
        $groups = Sentry::findAllGroups();
        return view('admin.user.create', ['groups' => $groups]);
    }

    /**
     * @operation 创建
     */
    public function postCreate(Request $request)
    {
        try {
            $user = Sentry::createUser(array(
                'email' => $request->get('email'),
                'password' => $request->get('password'),
                'first_name' => $request->get('first_name'),
                'last_name' => $request->get('last_name'),
                'activated' => false,
            ));
            // 查找用户组
            $adminGroup = Sentry::findGroupById((int)$request->get('group_id'));
            // 把用户加到用户组
            $user->addGroup($adminGroup);
        } catch (UserExistsException $e) {
            return $this->error('用户已存在，请重新输入！');
        } catch (GroupNotFoundException $e) {
            return $this->error('用户组不存在！');
        }
        return $this->ok();
    }

    /**
     * @operation 删除
     */
    public function deleteIndex(Request $request)
    {
        $ids = (array)$request->get('ids');

        try {
            foreach ($ids as $id) {
                // 根据 user id 查询用户信息
                $user = Sentry::findUserById($id);
                // 删除用户
                $user->delete();
            }
        } catch (UserNotFoundException $e) {
            return $this->error('用户不存在！');
        }
        return $this->ok();
    }

    /**
     * @operation 修改
     */
    public function getEdit(Request $request)
    {
        $id = (int)$request->get('id');

        try {
            // 查询用户
            $model = Sentry::findUserByID($id);
            // 获取该用户对应的分组
            $group = json_decode($model->getGroups(), true);
            //获取所有分组
            $groups = Sentry::findAllGroups();
        } catch (UserNotFoundException $e) {
            Log::info('用户不存在！' . $e);
        }
        return view('admin.user.create', ['groups' => $groups, 'model' => $model, 'group' => $group[0]['id']]);
    }

    /**
     * @operation 修改
     */
    public function putEdit(Request $request)
    {
        $id = (int)$request->get('id');
        $this->validate($request, [
            'email' => 'required|email|unique:users,email,' . $id,
        ], [
            'email.unique' => '该用户已存在，请重新输入！',
        ]);

        try {
            // 查询用户
            $user = Sentry::findUserById($id);
            // 查出 新的用户组
            $adminGroup = Sentry::findGroupById($request->get('group_id'));
            // 把用户与用户组绑定
            if ($user->updateGroups($adminGroup)) {
                // 更新用户信息
                $user->email = $request->get('email');
                $user->first_name = $request->get('first_name');
                $user->last_name = $request->get('last_name');
                $user->save();
            } else {
                return $this->error('更新失败！');
            }
        } catch (UserNotFoundException $e) {
            return $this->error('用户不存在！');
        } catch (GroupNotFoundException $e) {
            return $this->error('用户组不存在！');
        }
        return $this->ok();
    }

    /**
     * @operation 重置密码
     */
    public function getPassword(Request $request)
    {
        return view('admin.user.password', ['id' => (int)$request->get('id')]);
    }

    /**
     * @operation 重置密码
     */
    public function postPassword(Request $request)
    {
        $this->validate($request, ['reset_password_code' => 'required', 'password' => 'required']);
        $reset_password_code = $request->get('reset_password_code');
        $password = $request->get('password');
        $id = (int)$request->get('id');

        $user = Sentry::findUserById($id);

        if ($user->checkResetPasswordCode($reset_password_code)) {
            // 重置用户密码
            if ($user->attemptResetPassword($reset_password_code, $password)) {
                return $this->ok();
            } else {
                return $this->error('密码重置失败!');
            }
        }
    }

    /**
     * @operation 用户激活禁用
     */
    public function getStatus(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer'
        ]);
        $activated = $request->get('activated');

        $user = Sentry::findUserById($request->get('id'));

        if ($activated == 1) {
            $user->activated = false;
            $user->activated_at = null;
            $user->save();
        } else {
            // 获取此用户的 激活码
            $activationCode = $user->getActivationCode();
            // 使用激活码激活用户
            $user->attemptActivation($activationCode);
        }
        return $this->ok();
    }
}
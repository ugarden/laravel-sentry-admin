<?php

/**
 * Created by PhpStorm.
 * User: hc
 * Date: 2017/5/8
 * Time: 15:10
 */
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Cartalyst\Sentry\Throttling\UserBannedException;
use Cartalyst\Sentry\Throttling\UserSuspendedException;
use Cartalyst\Sentry\Users\UserNotActivatedException;
use Cartalyst\Sentry\Users\UserNotFoundException;
use Cartalyst\Sentry\Users\WrongPasswordException;
use Illuminate\Http\Request;
use Sentry;
use Validator;

class IndexController extends Controller
{

    private static $menu = [
        ['icon' => '&#xe62e;', 'text' => '系统管理', 'children' => [
            ['text' => '用户管理', 'action' => 'UserController@getShow'],
            ['text' => '角色管理', 'action' => 'GroupController@getShow'],
        ]],
    ];

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 进入登录界面
     */
    public function getLogin()
    {
        return view('admin.login');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * 执行登录操作
     */
    public function postLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ], [
            'email.required' => '账号不能为空！',
            'email.email' => '格式不正确，账号为邮箱格式！',
            'password.required' => '密码不能为空！'
        ]);
        $cred = [
            'email' => $request->get('email'),
            'password' => $request->get('password'),
        ];

        try {
            //验证验证码
            $validator = Validator::make($request->all(), ['verify_img' => 'required|captcha']);
            if ($validator->fails()) {
                return $this->error('验证码错误!');
            }
            $user = Sentry::authenticate($cred, false);
            if ($user) {
                if ($request->get('remember_me') == true) {//记住账号
                    setcookie('email', $request->get('email'), time() + 3600 * 24);
                    setcookie('password', $request->get('password'), time() + 3600 * 24);
                } else {
                    setcookie('email', '', 0);
                    setcookie('password', '', 0);
                }
                return $this->ok();
            }
        } catch (WrongPasswordException $e) {
            return $this->error('密码错误!');
        } catch (UserNotFoundException $e) {
            return $this->error('用户不存在!');
        } catch (UserBannedException $e) {
            return $this->error('用户被禁止登录!');
        } catch (UserSuspendedException $e) {
            try {
                $user = Sentry::findUserByCredentials($cred);
            } catch (WrongPasswordException $e) {
                return $this->error('密码错误!');
            }
            $throttle = Sentry::findThrottlerByUserId($user->id);
            $time = $throttle->getSuspensionTime();
            return $this->error('用户暂停登录' . $time . '分钟!');
        } catch (UserNotActivatedException $e) {
            return $this->error('用户未激活，请联系管理员！');
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 进入后台主界面
     */
    public function getIndex()
    {
        $user = Sentry::getUser();
        return view('admin.index', ['user' => $user, 'menus' => $this->formatMenu(self::$menu, $user)]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * 退出登录
     */
    public function getLogout()
    {
        Sentry::logout();
        return redirect(action('Admin\IndexController@getLogin'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 欢迎界面
     */
    public function getWelcome()
    {
        return view('admin.welcome');
    }

    /**
     * @param $menu
     * @param $user
     * @return array
     * 组装后台二级目录菜单
     */
    private function formatMenu($menu, $user)
    {
        $out = [];
        foreach ($menu as $v) {
            $children = [];
            foreach ($v['children'] as $v1) {
                //判断用户是否有访问该菜单的权限
                if ($user->hasAccess($v1['action'])) {
                    $v1['url'] = action('Admin\\' . $v1['action']);
                    unset($v1['action']);
                    $children[] = $v1;
                }
            }
            if ($children) {
                $out[] = ['icon' => $v['icon'], 'text' => $v['text'], 'children' => $children];
            }
        }
        return $out;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: hc
 * Date: 2017/5/7
 * Time: 9:18
 */

namespace App\Http\Middleware;


use Closure;
use Illuminate\Support\Facades\Log;
use Route;
use Sentry;

class AdminAuth
{
    /**
     * @var array
     * 无需验证权限的控制器
     */
    public static $pass = [
        'IndexController',
        'UtilController',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $route = Route::currentRouteAction();
        list($full_controller_name, $action) = explode('@', $route);
        $splits = explode('\\', $full_controller_name);
        $controller_name = end($splits);

        $routeAction = $controller_name . '@' . $action;
        $loginAction = 'IndexController@getLogin';

        if (!Sentry::check()) {
            if ($routeAction != $loginAction && $routeAction != 'IndexController@postLogin')
                return $request->ajax() ? response()->json(['errno' => 402, 'errmsg' => '请登录']) : view('admin.back');
        } else {
            if ($routeAction == $loginAction)
                return redirect(action('Admin\IndexController@getIndex'));
            $user = Sentry::getUser();
            if (!in_array($controller_name, self::$pass) && !$user->hasAccess($routeAction))
                return $request->ajax() ? response()->json(['errno' => 401, 'errmsg' => '没有权限']) : response('没有权限');
        }
        return $next($request);
    }
}
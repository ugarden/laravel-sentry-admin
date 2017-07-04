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

class ApiAuth
{
    /**
     * @var array
     * 无需验证权限的控制器
     */
    public static $pass = [

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

        return $next($request);
    }
}
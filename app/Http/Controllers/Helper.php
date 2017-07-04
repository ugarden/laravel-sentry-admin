<?php
/**
 * Created by PhpStorm.
 * User: hc
 * Date: 16/6/27
 * Time: 下午3:55
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;

trait Helper
{
    function getSkipLimit(Request $request)
    {
        $page = $request->get('start', 0);
        $rows = $request->get('length');
        return [$page, $rows];
    }
}
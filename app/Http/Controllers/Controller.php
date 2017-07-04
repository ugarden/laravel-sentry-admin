<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function ok($data = '')
    {
        $result = ['errno' => 0, 'errmsg' => '成功', 'data' => $data];
        return response()->json($result);
    }

    protected function error($errmsg, $errno = 1)
    {
        $result = ['errno' => $errno, 'errmsg' => $errmsg];
        return response()->json($result);
    }
}

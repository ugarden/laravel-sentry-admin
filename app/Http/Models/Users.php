<?php

/**
 * Created by PhpStorm.
 * User: hc
 * Date: 2017/5/10
 * Time: 10:17
 */
namespace App\Http\Models;

use Cartalyst\Sentry\Users\Eloquent\User;

class Users extends User
{
    public static function getForDataGrid($skip, $limit, $key)
    {
//        $out['data'] = Users::orderBy('created_at', 'desc');
//        if ($key) {
//            $out['data'] = $out['data']->where('email', $key);
//            $out['$recordsFiltered'] = $out['data']->where('email', $key)->count();
//        } else {
//            $out['$recordsFiltered'] = Users::count();
//        }
//        $out['$recordsTotal'] = Users::count();
//        $out['data'] = $out['data']->take($limit)->skip($skip)->get();
//        return $out;
    }
}
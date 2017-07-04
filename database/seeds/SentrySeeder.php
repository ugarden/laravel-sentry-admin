<?php

/**
 * Created by PhpStorm.
 * User: hc
 * Date: 2017/5/8
 * Time: 15:32
 */
use  \Illuminate\Database\Seeder;
use  \Sentry;
use  \Illuminate\Support\Facades\DB;

class SentrySeeder extends Seeder
{
    public function run()
    {
        // 清空数据
        DB::table('users')->delete();
        DB::table('groups')->delete();
        DB::table('users_groups')->delete();

        // 创建用户
        Sentry::getUserProvider()->create(array(
            'email' => 'admin@qq.com',
            'password' => "123456",
            'first_name' => '超级',
            'last_name' => '管理员',
            'activated' => 1,
        ));

        // 创建用户组
        Sentry::getGroupProvider()->create(array(
            'name' => 'Admin',
            'permissions' => [
                'UserController@getShow' => 1,
                'UserController@getIndex' => 1,
                'UserController@deleteIndex' => 1,
                'UserController@getEdit' => 1,
                'UserController@putEdit' => 1,
                'UserController@getPassword' => 1,
                'UserController@postPassword' => 1,
                'UserController@getStatus' => 1,
                'GroupController@getShow' => 1,
                'GroupController@getIndex' => 1,
                'GroupController@getUserList' => 1,
                'GroupController@postIndex'=>1,
                'GroupController@putIndex'=>1,
                'GroupController@deleteIndex'=>1,
                'OperationController@getIndex' => 1,
                'OperationController@postIndex' => 1,
                'OperationController@putIndex' => 1,
            ],
        ));

        // 将用户加入用户组
        $adminUser = Sentry::getUserProvider()->findByLogin('admin@qq.com');
        $adminGroup = Sentry::getGroupProvider()->findByName('admin');
        $adminUser->addGroup($adminGroup);
    }
}
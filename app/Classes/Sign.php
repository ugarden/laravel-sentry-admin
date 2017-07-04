<?php
/**
 * Created by PhpStorm.
 * User: hc
 * Date: 2016/8/2
 * Time: 13:01
 */

namespace App\Classes;


class Sign
{
    const ALGO = 'sha1';
    const SHA1_LENGTH = 40;

    private $key;

    function __construct($key)
    {
        $this->key = $key;
    }

    public function make($member_id)
    {
        $data = $member_id . mt_rand(1000, 9999);
        return hash_hmac(self::ALGO, $data, $this->key) . base64_encode($data);
    }

    public function decode($content, &$member_id)
    {
        $hash = substr($content, 0, self::SHA1_LENGTH);
        $data = base64_decode(substr($content, self::SHA1_LENGTH));

        if (hash_hmac(self::ALGO, $data, $this->key) === $hash) {
            $member_id = substr($data, 0, -4);
            return true;
        }
        return false;
    }
}
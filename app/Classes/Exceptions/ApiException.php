<?php

/**
 * Created by PhpStorm.
 * User: hc
 * Date: 2016/8/2
 * Time: 13:51
 */
namespace App\Classes\Exceptions;

class ApiException extends \Exception
{
    private $output;

    public function __construct($message, $output)
    {
        parent::__construct($message);
        $this->output = $output;
    }

    public function getOutput()
    {
        return $this->output;
    }

    public function __toString()
    {
        return $this->message . ', 接口返回: ' . to_string($this->output);
    }

}
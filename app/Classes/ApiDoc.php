<?php
/**
 * Created by PhpStorm.
 * User: lsq
 * Date: 2015/12/1
 * Time: 19:44
 */

namespace App\Classes;


class Parameter {

    /**
     * @var string 参数名
     */
    public $name;

    /**
     * @var string 参数类型
     */
    public $type;

    /**
     * @var bool 是否必须
     */
    public $require = true;

    /**
     * @var string 注释
     */
    public $comment;
}


/**
 * Class ApiDoc
 * @package App\Http\Classes
 */
class ApiDoc
{
    /**
     * @var string api注释
     */
    private $doc;

    /**
     * @var string api名称
     */
    public $api;

    /**
     * @var string api所属模块
     */
    public $module;

    /**
     * @var array api描述
     */
    public $desc = [];

    /**
     * @var string http方法
     */
    public $method;

    /**
     * @var string
     */
    public $url;

    /**
     * @var string
     */
    public $contentType;

    /**
     * @var array[Parameter] 输入参数
     */
    public $in = [];

    /**
     * @var string
     */
    public $inRepeat = '';

    /**
     * @var array[Parameter] 输出参数
     */
    public $out = [];

    /**
     * @var string
     */
    public $outRepeat = '';

    /**
     * @var array[string] 备注
     */
    public $remark = [];


    /**
     * @var string
     */
    public $hash;

    /**
     * ApiDocParser constructor.
     * @param string $doc
     * @throws \Exception
     */
    public function __construct($doc)
    {
        if (!$doc || strpos($doc, '@api') === false)
            throw new \Exception("wrong format: $doc");

        $this->doc = $doc;

        $this->parse();
    }

    public function hash() {
        if (!$this->hash)
            $this->hash = md5($this->api . $this->url);
        return $this->hash;
    }

    private function parse()
    {
        $splits = explode("\n", $this->doc);

        $last = null;

        foreach ($splits as $line)
        {
            $line = trim($line, "*/ \t\n\r\0\x0B");

            if (!$line) continue;

            if ($line == '@in+')
            {
                $this->inRepeat = '+';
                continue;
            }
            if ($line == '@out+')
            {
                $this->outRepeat = '+';
                continue;
            }

            if ($line[0] == '@')
            {
                $first_space = strpos($line, ' ');
                $tag = substr($line, 0, $first_space);
                $remain = substr($line, $first_space + 1);

                switch ($tag)
                {
                    case '@api':
                        $this->api = $remain;
                        break;
                    case '@module':
                        $this->module = $remain;
                        break;
                    case '@desc':
                        $this->desc[] = $remain;
                        $last = &$this->desc;
                        break;
                    case '@method':
                        $this->method = strtoupper($remain);
                        break;
                    case '@contentType':
                        $this->contentType = $remain;
                        break;
                    case '@url':
                        $this->url = $remain;
                        break;
                    case '@in_remark':
                        $this->inRemark = $remain;
                        break;
                    case '@in':
                    case '@in?':
                        $this->in[] = $this->parseIn($tag, $remain);
                        break;
                    case '@out':
                        $this->out[] = $this->parseParam($remain);
                        break;
                    case '@remark':
                        $this->remark[] = $remain;
                        $last = &$this->remark;
                        break;
                }
            }
            else
                $last[] = $line;
        }
    }

    /**
     * @param $tag
     * @param $content
     * @return Parameter
     */
    private function parseIn($tag, $content) {
        $in = $this->parseParam($content);
        $in->require = $tag[strlen($tag) - 1] != '?';
        return $in;
    }

    /**
     * @param $content
     * @return Parameter
     * @throws \Exception
     */
    private function parseParam($content) {
        $splits = preg_split('#\s+#', $content);
        if (count($splits) < 3)
            throw new \Exception("invalid format: $content");
        $param = new Parameter();
        $param->name = $splits[0];
        $param->type = $splits[1];
        $param->comment = implode(' ', array_slice($splits, 2));
        return $param;
    }

    /**
     * @return array
     */
    public function getDesc()
    {
        return implode("<br />", $this->desc);
    }
}
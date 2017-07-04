<?php
/**
 * Created by PhpStorm.
 * User: hc
 * Date: 2016/12/2
 * Time: 22:53
 */

namespace App\Http\Controllers;


use App\Classes\ApiDoc;
use Illuminate\Http\Request;
use Cache;

/**
 * 根据注释生成api文档
 * Class DocController
 * @package App\Http\Controllers
 */
class DocController extends Controller
{
    //private $user = 'doc';

    //private $passwd = 'lehuo1234';

    private $cacheKey = 'api_doc';

    public function index(Request $request)
    {
        if ($request->query->getInt('rebuild'))
        {
            $this->clear();
            return redirect()->action('DocController@index');
        }
        else
            return $this->fetch();

        /*$is_login = $request->session()->has('api_doc_login');

        $user = $request->server('PHP_AUTH_USER');
        $passwd = $request->server('PHP_AUTH_PW');

        if ($is_login || ($user === $this->user && $passwd === $this->passwd)) {
            $is_login or $request->session()->put('api_doc_login', true);
            if ($request->query->getInt('rebuild'))
            {
                $this->clear();
                return redirect()->action('DocController@index');
            }
            else
                return $this->fetch();
        }
        else
            return response('', 401, ['WWW-Authenticate' => 'Basic realm="lehuo"']);*/

    }

    private function clear() {
        Cache::forget($this->cacheKey);
    }

    private function fetch() {
        if (!Cache::has($this->cacheKey))
            Cache::forever($this->cacheKey, $this->build());
        return Cache::get($this->cacheKey);
    }

    private function build() {
        $api_dir = ['Api'];

        $data = [];
        foreach ($api_dir as $dir)
        {
            $path = app_path('Http/Controllers/' . $dir);
            $files = glob($path . "/*Controller.php");
            foreach ($files as $file)
            {
                $splits = explode('/', $file);
                $name = end($splits);
                $controller_name = substr($name, 0, -4);
                $rc = new \ReflectionClass("App\\Http\\Controllers\\{$dir}\\{$controller_name}");
                $methods = $rc->getMethods(\ReflectionMethod::IS_PUBLIC);
                foreach ($methods as $m)
                {
                    $doc = $m->getDocComment();
                    if (strpos($doc, '@api') !== false)
                    {
                        $doc = new ApiDoc($doc);
                        $data[$doc->module][] = $doc;
                    }
                }

            }
        }
        return view('doc', ['data' => $data])->render();
    }
}
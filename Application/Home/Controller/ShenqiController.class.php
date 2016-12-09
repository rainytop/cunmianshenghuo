<?php
/**
 * Created by PhpStorm.
 * User: xiedalie
 * Date: 2016/12/4
 * Time: 6:49
 */

namespace Home\Controller;


use Think\Controller;
use Vendor\Hiland\Utils\Data\ReflectionHelper;

class ShenqiController extends Controller
{
    public function more(){
        $this->display("more");
    }

    public function baoye()
    {
        $this->assign("title", "美女包夜");
        $this->detail("baoye");
    }

    private function detail($methodName)
    {
        if (IS_POST) {
            $name = I("targetName");
            if (empty($name)) {
                $name = "老青岛";
            }

            $methodArgs = array($name);
            $relativeFile = ReflectionHelper::executeMethod("Home\Model\ShenqiBiz", $methodName, null, $methodArgs);

            $webFile = __ROOT__ . $relativeFile;
            $this->assign("imgsrc", $webFile);
        }

        $this->display("index");
    }

    public function neiku()
    {
        $this->assign("title", "美女内裤");
        $this->detail("neiku");
    }

    public function chuanpiao()
    {
        $this->assign("title", "法院传票");
        $this->detail("chuanpiao");
    }

    public function jiejiu()
    {
        $this->assign("title", "戒酒宣言");
        $this->detail("jiejiu");
    }

    public function wurenji(){
        $this->assign("title", "无人机驾驶证");
        $this->detail("wurenji");
    }
}
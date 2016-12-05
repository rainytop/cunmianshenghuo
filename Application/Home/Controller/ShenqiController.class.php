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
    public function baoye()
    {
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
        $this->detail("neiku");
    }

    public function chuanpiao()
    {
        $this->detail("chuanpiao");
    }

    public function jiejiu()
    {
        $this->detail("jiejiu");
    }
}
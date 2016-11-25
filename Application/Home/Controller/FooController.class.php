<?php
/**
 * Created by PhpStorm.
 * User: xiedalie
 * Date: 2016/11/25
 * Time: 15:58
 */

namespace Home\Controller;


use Home\Model\WxBiz;
use Think\Controller;

class FooController extends Controller
{
    public function index(){
        dump(1111111111);
    }

    public function wxbiz(){
        WxBiz::createQrcode(3,"oinMwxGi-Ok20PEf5lUn6TtPaQXg");
    }
}
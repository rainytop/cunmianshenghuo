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
use Vendor\Hiland\Biz\Tencent\WechatHelper;
use Vendor\Hiland\Utils\IO\ImageHelper;
use Vendor\Hiland\Utils\Web\NetHelper;

class FooController extends Controller
{
    public function index()
    {
        dump(1111111111);
    }

    public function wxbiz()
    {
        WxBiz::createQrcode(3, "oinMwxGi-Ok20PEf5lUn6TtPaQXg");
    }

    public function wximg()
    {
        $headimgurl= "http://wx.qlogo.cn/mmopen/Ib5852jAybibhPd6DV1FzXCgLicqMreYh8LTWtFje4ePscFDPl8KMc2jAo65z5IjNluaQBBwkIVS2oxX67eqFBaoRnjoesVAWL/0";
//        $data = NetHelper::get($headimgurl);
//        dump($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_URL, $headimgurl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 2);
        $headimg = curl_exec($ch);
        curl_close($ch);
        dump($headimg);
    }

    public function uploadimg(){
        $wechat= WxBiz::getWechat();

        $file= PHYSICAL_ROOT_PATH . "\\QRcode\\promotion\\oinMwxGi-Ok20PEf5lUn6TtPaQXg.jpg";
        dump($file);
        $data = array('media' => '@' . $file);
        $result= $wechat->uploadMedia($data,'image');
        dump($result);

        $rt= WechatHelper::uploadMedia($file);
        dump($rt);
    }

    public function wxav(){
        $hostName= "http://wx.qlogo.cn";
        //$hostName= "http://182.254.18.178";
        $recommenduseravatar= "$hostName/mmopen/Ib5852jAybibhPd6DV1FzXCgLicqMreYh8LTWtFje4ePscFDPl8KMc2jAo65z5IjNluaQBBwkIVS2oxX67eqFBaoRnjoesVAWL/0";

        //$headimg = ImageHelper::loadImage($recommenduseravatar, 'non');

        //$headimg= NetHelper::request($recommenduseravatar,null,30);

        $headimg= $this-> ss($recommenduseravatar);
        $headimg= imagecreatefromstring($headimg);
        ImageHelper::display($headimg);
        //dump($headimg);
    }

    private function ss($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);

        return $output;
    }
}
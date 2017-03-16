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
use Vendor\Hiland\Utils\Data\Calendar;
use Vendor\Hiland\Utils\Data\CalendarHelper;
use Vendor\Hiland\Utils\Data\DateHelper;
use Vendor\Hiland\Utils\Data\StringHelper;
use Vendor\Hiland\Utils\IO\DirHelper;
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
        $headimgurl = "http://wx.qlogo.cn/mmopen/Ib5852jAybibhPd6DV1FzXCgLicqMreYh8LTWtFje4ePscFDPl8KMc2jAo65z5IjNluaQBBwkIVS2oxX67eqFBaoRnjoesVAWL/0";
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

    public function calendarop($y = 2017, $m = 3, $d = 15)
    {
        $cal = new Calendar();
        $data = $cal->Calc($y, $m, $d);
        dump($data);

        $bb = CalendarHelper::convertSolarToLunar(2017, 3, 15);
        dump($bb);

        $lunar= CalendarHelper::convertSolarToLunar(date('Y'),date('m'),date('d'));
        dump($lunar);
        dump($lunar[1]);
        //dump(substr($lunar[1],0,1)) ;
        dump(StringHelper::subString($lunar[1],0,1)) ;
        dump(StringHelper::subString($lunar[1],1,1)) ;
    }

    public function uploadimg()
    {
        $wechat = WxBiz::getWechat();

        $file = PHYSICAL_ROOT_PATH . "\\QRcode\\promotion\\oinMwxGi-Ok20PEf5lUn6TtPaQXg.jpg";
        dump($file);
        $data = array('media' => '@' . $file);
        $result = $wechat->uploadMedia($data, 'image');
        dump($result);

        $rt = WechatHelper::uploadMedia($file);
        dump($rt);
    }

    public function wxav()
    {
        $hostName = "http://wx.qlogo.cn";

        $ip = C('WX_AVATARSERVER_IP');
        $hostName = "http://$ip";
        $recommenduseravatar = "$hostName/mmopen/Ib5852jAybibhPd6DV1FzXCgLicqMreYh8LTWtFje4ePscFDPl8KMc2jAo65z5IjNluaQBBwkIVS2oxX67eqFBaoRnjoesVAWL/0";

        //$headimg = ImageHelper::loadImage($recommenduseravatar, 'non');

        $headimg = NetHelper::request($recommenduseravatar, null, 30);
        //$headimg= NetHelper::get($recommenduseravatar,true);
        //$headimg= $this-> ss($recommenduseravatar);

        $headimg = imagecreatefromstring($headimg);
        ImageHelper::display($headimg);
        //dump($headimg);
    }

    public function jsop()
    {
        $this->display();
    }

    public function dirop()
    {
        $path = "E:\\aa\\bb\\cc\\dd";
//        if(is_dir($path)==false){
//            mkdir($path);
//        }

        DirHelper::surePathExist($path);
    }

    public function aa()
    {
        dump('http://' . $_SERVER['HTTP_HOST'] . __ROOT__ . '/index.php/Home/Wxpay/nd/');
    }

    public function weeknameop()
    {
        $time = mktime(9, 1, 1, 3, 15, 2017);

        dump(DateHelper::getWeekName('c', $time));
    }

    private function ss($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);

        return $output;
    }
}
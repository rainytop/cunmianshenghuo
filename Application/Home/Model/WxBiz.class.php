<?php

namespace Home\Model;
use Vendor\Hiland\Utils\Web\NetHelper;

/**
 * Created by PhpStorm.
 * User: xiedalie
 * Date: 2016/11/25
 * Time: 15:14
 */
class WxBiz
{
    public static function getWechat()
    {
        $set = M('Set')->find();

        $token = $set['wxtoken'];
        $appId = $set['wxappid'];
        $appSecret = $set['wxappsecret'];

        $options['token'] = $token;
        $options['appid'] = $appId;
        $options['appsecret'] = $appSecret;

        $wechat = new \Util\Wx\Wechat($options);
        return $wechat;
    }

    /**
     * 获取二维码的背景图片资源
     * @return resource
     */
    public static function createQrcodeBg()
    {
        $autoset = M('Autoset')->find();
        if (!file_exists('./' . $autoset['qrcode_background'])) {
            $background = imagecreatefromstring(file_get_contents('./QRcode/background/default.jpg'));
        } else {
            $background = imagecreatefromstring(file_get_contents('./' . $autoset['qrcode_background']));
        }
        return $background;
    }

    public static function createQrcode($id, $openid)
    {
        if ($id == 0 || $openid == '') {
            return false;
        }
        if (!file_exists('./QRcode/qrcode/' . $openid . '.png')) {
            //二维码进入系统
            // $url = 'http://' . $_SERVER['HTTP_HOST'] . __ROOT__ . '/App/Shop/index/ppid/' . $id;
            // \Util\QRcode::png($url, './QRcode/qrcode/' . $openid . '.png', 'L', 6, 2);

            //二维码进入公众号
            self::getQRCode($id, $openid);
        }
        $qrcode = imagecreatefromstring(file_get_contents('./QRcode/qrcode/' . $openid . '.png'));
        return $qrcode;
    }

    public static function getQRCode($id, $openid)
    {
        $wechat= self::getWechat();
        $ticket = $wechat->getQRCode($id, 1);
        //CommonLoger::log("ticket",json_encode($ticket));

        $vipModel= M('Vip');
        $vipModel->where(array("id" => $id))->save(array("ticket" => $ticket["ticket"]));
        $qrUrl = $wechat->getQRUrl($ticket["ticket"]);

        $data = NetHelper::request($qrUrl);
        //CommonLoger::log('datalength',sizeof($data));
        file_put_contents('./QRcode/qrcode/' . $openid . '.png', $data);
    }
}
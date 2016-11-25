<?php
/**
 * Created by PhpStorm.
 * User: xiedalie
 * Date: 2016/11/25
 * Time: 9:37
 */

namespace Home\Controller;


use App\QRcode;
use Think\Controller;
use Vendor\Hiland\Biz\Loger\CommonLoger;
use Vendor\Hiland\Biz\Tencent\WechatHelper;

class WxNonValidController extends Controller
{
    public static $_wx; //缓存微信对象

    public function __construct($options)
    {
        $set = M('Set')->find();

        $token = $set['wxtoken'];
        $appId = $set['wxappid'];
        $appSecret = $set['wxappsecret'];

        $options['token'] = $token;
        $options['appid'] = $appId;
        $options['appsecret'] = $appSecret;

        self::$_wx = new \Util\Wx\Wechat($options);
    }


    public function reply4TuiGuangErWeiMa($openid)
    {
        //CommonLoger::log("aaaaaaaaaaaaaa00", "1111111111111111");
        // 获取用户信息
        $map['openid'] = $openid;//self::$_revdata['FromUserName'];

        //CommonLoger::log("erweima-openid", $map['openid']);
        $vipModel= M('Vip');
        $vip = $vipModel ->where($map)->find();

        CommonLoger::log("aaa", "22");
        // 用户校正
        if (!$vip) {
            $msg = "用户信息缺失，请重新关注公众号";
            self::$_wx->text($msg)->reply();
            exit();
        } else if ($vip['isfx'] == 0) {
            $msg = "您还未成为" . self::$_shop['fxname'] . "，请先购买成为" . self::$_shop['fxname'] . "！";
            self::$_wx->text($msg)->reply();
            exit();
        }

        CommonLoger::log("aaa", "33");

        // 过滤连续请求-打开
        if (F($vip['openid']) != null) {
            CommonLoger::log("aaa", "331");
            $msg = "推广二维码正在生成，请稍等！";
            //self::$_wx->text($msg)->reply();
            WechatHelper::responseCustomerServiceText($openid,$msg);
            CommonLoger::log("aaa", "332");
            exit();
        } else {
            CommonLoger::log("aaa", "333");
            F($vip['openid'], $vip['openid']);
        }

        CommonLoger::log("aaa", "44");

        // 生产二维码基本信息，存入本地文档，获取背景
        $background = $this->createQrcodeBg();
        $qrcode = $this->createQrcode($vip['id'], $vip['openid']);
        if (!$qrcode) {
            $msg = "专属二维码 生成失败";
            self::$_wx->text($msg)->reply();
            F($vip['openid'], null);
            exit();
        }

        CommonLoger::log("aaa", "55");
        // 生产二维码基本信息，存入本地文档，获取背景 结束

        // 获取头像信息
        $mark = false; // 是否需要写入将图片写入文件
        $headimg = $this->getRemoteHeadImage($vip['headimgurl']);
        if (!$headimg) {// 没有头像先从头像库查找，再没有就选择默认头像
            if (file_exists('./QRcode/headimg/' . $vip['openid'] . '.jpg')) { // 获取不到远程头像，但存在本地头像，需要更新
                $headimg = file_get_contents('./QRcode/headimg/' . $vip['openid'] . '.jpg');
            } else {
                $headimg = file_get_contents('./QRcode/headimg/' . 'default' . '.jpg');
            }
            $mark = true;
        }

        CommonLoger::log("aaa", "66");
        $headimg = imagecreatefromstring($headimg);
        // 获取头像信息 结束

        // 生成二维码推广图片=======================

        // Combine QRcode and background and HeadImg
        $b_width = imagesx($background);
        $b_height = imagesy($background);
        $q_width = imagesx($qrcode);
        $q_height = imagesy($qrcode);
        $h_width = imagesx($headimg);
        $h_height = imagesy($headimg);
        imagecopyresampled($background, $qrcode, $b_width * 0.24, $b_height * 0.5, 0, 0, 297, 297, $q_width, $q_height);
        imagecopyresampled($background, $headimg, $b_width * 0.10, 12, 0, 0, 120, 120, $h_width, $h_height);

        // Set Font Type And Color
        $fonttype = './Public/Common/fonts/wqy-microhei.ttc';
        $fontcolor = imagecolorallocate($background, 0x00, 0x00, 0x00);

        // Combine All And Text, Then store in local
        imagettftext($background, 18, 0, 280, 100, $fontcolor, $fonttype, $vip['nickname']);
        imagejpeg($background, './QRcode/promotion/' . $vip['openid'] . '.jpg');

        CommonLoger::log("aaa", "77");
        // 生成二维码推广图片 结束==================

        // 上传下载相应
        if (file_exists(getcwd() . "/QRcode/promotion/" . $vip['openid'] . '.jpg')) {
            $data = array('media' => '@' . getcwd() . "/QRcode/promotion/" . $vip['openid'] . '.jpg');
            $uploadresult = self::$_wx->uploadMedia($data, 'image');
            self::$_wx->image($uploadresult['media_id'])->reply();
        } else {
            $msg = "专属二维码生成失败";
            self::$_wx->text($msg)->reply();
        }
        // 上传下载相应 结束

        CommonLoger::log("aaa", "88");
        // 过滤连续请求-关闭
        F($vip['openid'], null);

        // 后续数据操作（写入头像到本地，更新个人信息）
        if ($mark) {
//            $tempvip = $this->apiClient(self::$_revdata['FromUserName']);
//            $vip['nickname'] = $tempvip['nickname'];
//            $vip['headimgurl'] = $tempvip['headimgurl'];
        } else {
            // 将头像文件写入
            imagejpeg($headimg, './QRcode/headimg/' . $vip['openid'] . '.jpg');
        }
    }

}
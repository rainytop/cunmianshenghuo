<?php
namespace Vendor\Hiland\Biz\Loger;

use Vendor\Hiland\Utils\Data\ReflectionHelper;

/**
 * Created by PhpStorm.
 * User: xiedalie
 * Date: 2016/6/30
 * Time: 7:31
 * 说明： 如果启用第三方的loger，请在配置文件中设置LogProviderName
 */
class CommonLoger
{
    /**
     * 进行日志记录
     *
     * @param string $title
     *            日志的标题
     * @param string $content
     *            日志的内容
     * @param string $categoryname
     *            日志的分类名称
     * @param string $other
     *            日志附加信息
     * @param int $misc1
     *            日志附加信息
     * @param string $status
     *            日志状态信息
     * @return boolean 日志记录的成功与失败
     */
    public static function log($title, $content = '', $status = '', $categoryname = 'develop', $other = '', $misc1 = 0)
    {
        self::getLoger()->log($title, $content, $status, $categoryname, $other, $misc1);
    }

    private static function getLoger()
    {
        $cacheKey = "system-provider-loger";
        if (S($cacheKey)) {
            return S($cacheKey);
        } else {
            $providerName = C("LogProviderName");
            if (empty($providerName)) {
                $providerName = "DBLoger";
            }

            $className = "Vendor\\Hiland\\Biz\\Loger\\$providerName\\Loger";
            $loger = ReflectionHelper::createInstance($className);

            S($cacheKey, $loger);
            return $loger;
        }
    }
}
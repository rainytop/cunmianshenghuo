<?php
namespace Vendor\Hiland\Biz\Loger;

use Vendor\Hiland\Utils\Data\ReflectionHelper;

/**
 * Created by PhpStorm.
 * User: xiedalie
 * Date: 2016/6/30
 * Time: 7:31
 * 说明：如果启用第三方的loger，请在配置文件中设置LogProviderName
 */
class CommonLoger
{
    const LOGLEVEL_BIZ = 0;
    const LOGLEVEL_DEBUG = 30;
    const LOGLEVEL_WARNING = 40;
    const LOGLEVEL_ERROR = 50;
    const LOGLEVEL_DIE = 60;

    /**
     * 进行日志记录
     * @param string $title 日志的标题
     * @param string $content 日志的内容
     * @param int $logLevel 记录的级别  LOGLEVEL_***,缺省为LOGLEVEL_DEBUG
     * @param array $option 日志的其他信息
     *      string $category 日志的分类名称
     *      string $other 日志附加信息
     *      int $misc 日志附加信息
     *      string $status 日志状态信息
     */
    public static function log($title, $content = '', $logLevel = CommonLoger::LOGLEVEL_DEBUG, $option = array(status => '', categoryname => 'develop', other => '', misc => 0))
    {
        $systemLogLevel = C("SYSTEM_LOG_LEVEL");
        if (empty($systemLogLevel)) {
            $systemLogLevel = CommonLoger::LOGLEVEL_DEBUG;
        }

        if ($logLevel > $systemLogLevel) {
            return;
        }

        $status = '';
        if ($option['status']) {
            $status = $option['status'];
        }

        $category = '';
        if ($option['category']) {
            $category = $option['category'];
        }

        $other = '';
        if ($option['other']) {
            $other = $option['other'];
        }

        $misc = 0;
        if ($option['misc']) {
            $misc = $option['misc'];
        }

        self::getLoger()->log($title, $content, $status, $category, $other, $misc);
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
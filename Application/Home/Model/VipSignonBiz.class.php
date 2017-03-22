<?php
/**
 * Created by PhpStorm.
 * User: xiedalie
 * Date: 2017/3/21
 * Time: 10:24
 */

namespace Home\Model;


use Vendor\Hiland\Utils\Data\DateHelper;
use Vendor\Hiland\Utils\DataModel\ModelMate;

class VipSignonBiz
{
    public static function signOn($vipid = 0)
    {
        $signMate = new ModelMate('vip_signon_detail');

        $today = DateHelper::format(null, 'Y-m-d');
        $nextDay = DateHelper::format(DateHelper::addInterval(null, 'd', 1), 'Y-m-d');

        $filter['signtime'] = array('between', array($today, $nextDay));
        $filter['vipid'] = $vipid;

        $recordExist = $signMate->find($filter);

        if (!$recordExist) {
            $continueDayCount = self::getContinuousDayCount($vipid) + 1;
            $basicScore = C('VIP_SIGNON_BASICSCORE');

            $record['vipid'] = $vipid;
            $record['signtime'] = date('Y-m-d H:i:s');
            $record['scoretype'] = 1;


            $scoreOfToday = $basicScore * $continueDayCount;
            //如果时间在8点-10点之间，那么折半积分
            $timestamp_5 = DateHelper::getTimestamp(date('Y-m-d') . ' 5:0:0');

            $timestamp_8 = DateHelper::getTimestamp(date('Y-m-d') . ' 8:0:0');
            $timestamp_10 = DateHelper::getTimestamp(date('Y-m-d') . ' 10:0:0');

            $currentTime = time();

            if ($currentTime < $timestamp_5) {
                //todo 提示签到尚未开始
            }

            if ($currentTime >= $timestamp_5 && $currentTime < $timestamp_8) {
                //本时间段内，完全积分。
            }

            if ($currentTime >= $timestamp_8 && $currentTime < $timestamp_10) {
                //本时间段内，0.5倍积分。
                $scoreOfToday = $scoreOfToday / 2;
            }

            if ($currentTime >= $timestamp_10) {
                //超过10点不进行积分。
                $scoreOfToday = 0;
            }

            $record['score'] = $scoreOfToday;

            $signMate->interact($record);

            $summaryMate = new ModelMate('vip_signon_summary');
            $summaryCondition['vipid'] = $vipid;
            $summary = $summaryMate->find($summaryCondition);
            if ($summary) {
                $summary['totalscore'] = $summary['totalscore'] + $scoreOfToday;
            } else {
                $summary['vipid'] = $vipid;
                $summary['totalscore'] = $record['score'];
            }

            $summaryMate->interact($summary);
        }
    }

    public static function getContinuousDayCount($vipid = 0)
    {
        $signMate = new ModelMate('vip_signon_detail');
        $filter['vipid'] = $vipid;
        $signArray = $signMate->select($filter, 'signtime desc', 0, 0, 22);

        //dump($signArray);

        //先计算不包括当前日的连续天数
        $result= self::calcContinuousDayCount($signArray);

        //再计算包括当前日的连续天数
        if($result==0){
            $nextDate=  DateHelper::addInterval(null,'d',1);
            $nextDate= DateHelper::format($nextDate,'Y-m-d 0:0:0');
            $result= self::calcContinuousDayCount($signArray,$nextDate);
        }

        return $result;
    }

    private static function calcContinuousDayCount($signArray, $comparingDate = null)
    {
        if ($comparingDate == null) {
            $comparingDate = DateHelper::format(null, 'Y-m-d 0:0:0');
        }

        $comparingDate = DateHelper::getTimestamp($comparingDate);

        $recordCount = count($signArray);
        $continuousDays = 0;
        for ($i = 0; $i < $recordCount; $i++) {
            $record = strtotime($signArray[$i]['signtime']);

            $beginTime = DateHelper::addInterval($comparingDate, 'd', -$i - 1);
            $endTime = DateHelper::addInterval($comparingDate, 'd', -$i);

            if ($record >= $beginTime && $record < $endTime) {
                $continuousDays++;
            } else {
                break;
            }
        }

        return $continuousDays;
    }
}
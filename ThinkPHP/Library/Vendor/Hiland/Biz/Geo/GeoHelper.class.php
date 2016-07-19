<?php
namespace Vendor\Hiland\Biz\Geo;
/**
 * Created by PhpStorm.
 * User: xiedalie
 * Date: 2016/7/12
 * Time: 7:48
 */
class GeoHelper
{
    /**
     * 地球半径（单位：KM）
     */
    const EARTH_RADIUS = 6378.137;

    /**
     * 根据给定的用户坐标，对包含坐标数据的数据集信息进行排序
     * @param float $userLat 用户纬度
     * @param float $userLng 用户经度
     * @param array $dataList 包含坐标数据的数据集信息
     * @param string $rankType 排序方式 asc升序 desc 降序
     * @param string $dataItemLatFormat 获取$dataList数据集内元素的lat坐标的格式（缺省为"lat"，如果此坐标嵌套在元素的子元素内，其格式为 "**"."lat"）
     * @param string $dataItemLngFormat 获取$dataList数据集内元素的lng坐标的格式（缺省为"lng"，如果此坐标嵌套在元素的子元素内，其格式为 "**"."lng"）
     * @return array|bool
     */
    public static function rankDistance($userLat, $userLng, $dataList, $rankType = 'asc', $dataItemLatFormat = "lat", $dataItemLngFormat = "lng")
    {
        if (!empty($userLat) && !empty($userLng)) {
            foreach ($dataList as $row) {
                $latArray = explode(".", $dataItemLatFormat);
                $itemLat = $row;
                foreach ($latArray as $item) {
                    $itemLat = $itemLat[$item];
                }

                $lngArray = explode(".", $dataItemLngFormat);
                $itemLng = $row;
                foreach ($lngArray as $item) {
                    $itemLng = $itemLng[$item];
                }

                $row['km'] = self::getDistance($userLat, $userLng, $itemLat, $itemLng);
                $row['km'] = round($row['km'], 1);

                $distance[] = $row['km'];
                $res[] = $row;
            }

            if (!empty($res)) {
                $rankType = strtoupper($rankType);
                if ($rankType == 'DESC') {
                    $rankType = SORT_DESC;
                } else {
                    $rankType = SORT_ASC;
                }
                array_multisort($distance, $rankType, $res);
                return $res;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    //计算经纬度两点之间的距离

    public static function getDistance($lat1, $lng1, $lat2, $lng2)
    {
        $radLat1 = self::getRadian($lat1);
        $radLat2 = self::getRadian($lat2);
        $a = $radLat1 - $radLat2;
        $b = self::getRadian($lng1) - self::getRadian($lng2);
        $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2)));
        $s1 = $s * self::EARTH_RADIUS;
        $s2 = round($s1 * 10000) / 10000;
        return $s2;
    }

    /**
     * 由角度计算弧度
     * @param float $angle 角度
     * @return float 弧度
     */
    public static function getRadian($angle)
    {
        return $angle * 3.1415926535898 / 180.0;
    }
}
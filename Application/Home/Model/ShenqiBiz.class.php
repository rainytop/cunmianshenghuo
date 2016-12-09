<?php
/**
 * Created by PhpStorm.
 * User: xiedalie
 * Date: 2016/12/5
 * Time: 15:48
 */

namespace Home\Model;

use Vendor\Hiland\Utils\Data\DateHelper;
use Vendor\Hiland\Utils\Data\GuidHelper;
use Vendor\Hiland\Utils\IO\DirHelper;
use Vendor\Hiland\Utils\IO\ImageHelper;

class ShenqiBiz
{
    public static function baoye($name = "解然")
    {
        $bgFileName = PHYSICAL_ROOT_PATH . "\\Upload\\shenqi\\baoye\\meinv.jpg";
        $fingerMarkFileName = PHYSICAL_ROOT_PATH . "\\Upload\\shenqi\\baoye\\zhiwen.png";
        $fontFileName = PHYSICAL_ROOT_PATH . "\\Upload\\fonts\\jiangangshouxie.ttf";

        $imagebg = ImageHelper::loadImage($bgFileName);;
        $imagemegered = imagecreatetruecolor(imagesx($imagebg), imagesy($imagebg));
        imagecopy($imagemegered, $imagebg, 0, 0, 0, 0, imagesx($imagebg), imagesy($imagebg));

        $imageFingerMarker = ImageHelper::loadImage($fingerMarkFileName, 'non');
        //$imageFingerMarker = ImageHelper::resizeImage($imageFingerMarker, 130, 130);
        imagecopy($imagemegered, $imageFingerMarker, 515, 622, 0, 0, imagesx($imageFingerMarker), imagesy($imageFingerMarker));

        $textcolor = imagecolorallocate($imagemegered, 85, 85, 85);
        imagefttext($imagemegered, 35, 0, 480, 670, $textcolor, $fontFileName, $name);
        $sinDate = DateHelper::format(null, "Y-m-d");
        imagefttext($imagemegered, 20, 0, 410, 690, $textcolor, $fontFileName, $sinDate);

        $relativeFile = self::saveImageAndGetRelativePath($imagemegered);
        return $relativeFile;
    }

    private static function saveImageAndGetRelativePath($imageResource)
    {
        $relativePath = "\\Upload\\shenqitupian\\" . DateHelper::format(null, 'Y-m-d') . "\\";
        $targetFilePath = PHYSICAL_ROOT_PATH . $relativePath;
        DirHelper::surePathExist($targetFilePath);

        $fileShortName = GuidHelper::newGuid() . ".jpg";
        $relativeFile = $relativePath . $fileShortName;

        $physicalFullName = PHYSICAL_ROOT_PATH . $relativeFile;
        $physicalFullName = str_replace('/', '\\', $physicalFullName);

        $physicalFullName = ImageHelper::save($imageResource, $physicalFullName);
        return $relativeFile;
    }

    public static function neiku($name = "解然")
    {
        $bgFileName = PHYSICAL_ROOT_PATH . "\\Upload\\shenqi\\neiku\\neiku.jpg";
        $fontFileName = PHYSICAL_ROOT_PATH . "\\Upload\\fonts\\jiangangshouxie.ttf";

        $imagebg = ImageHelper::loadImage($bgFileName);;
        $imagemegered = imagecreatetruecolor(imagesx($imagebg), imagesy($imagebg));
        imagecopy($imagemegered, $imagebg, 0, 0, 0, 0, imagesx($imagebg), imagesy($imagebg));

        $textcolor = imagecolorallocate($imagemegered, 85, 85, 85);
        imagefttext($imagemegered, 35, -15, 350, 435, $textcolor, $fontFileName, $name);
        $sinDate = DateHelper::format(null, "Y m d");
        imagefttext($imagemegered, 20, -10, 220, 445, $textcolor, $fontFileName, $sinDate);

        $relativeFile = self::saveImageAndGetRelativePath($imagemegered);
        return $relativeFile;
    }

    public static function chuanpiao($name = "解然")
    {
        $bgFileName = PHYSICAL_ROOT_PATH . "\\Upload\\shenqi\\chuanpiao\\chuanpiao.jpg";
        $fontFileName = PHYSICAL_ROOT_PATH . "\\Upload\\fonts\\jiangangshouxie.ttf";

        $imagebg = ImageHelper::loadImage($bgFileName);;
        $imagemegered = imagecreatetruecolor(imagesx($imagebg), imagesy($imagebg));
        imagecopy($imagemegered, $imagebg, 0, 0, 0, 0, imagesx($imagebg), imagesy($imagebg));

        $textcolor = imagecolorallocate($imagemegered, 85, 85, 85);
        imagefttext($imagemegered, 35, 0, 250, 315, $textcolor, $fontFileName, $name);
        $sinDate = DateHelper::format(DateHelper::addInterval(time(), "d", 5), "Y m d");
        imagefttext($imagemegered, 20, 0, 220, 435, $textcolor, $fontFileName, $sinDate);

        $sinDate2 = DateHelper::format(null, "Y m d");
        imagefttext($imagemegered, 20, 0, 65, 715, $textcolor, $fontFileName, $sinDate2);

        $relativeFile = self::saveImageAndGetRelativePath($imagemegered);
        return $relativeFile;
    }

    public static function jiejiu($name = "解然")
    {
        $bgFileName = PHYSICAL_ROOT_PATH . "\\Upload\\shenqi\\jiejiu\\jiejiu.jpg";
        $fontFileName = PHYSICAL_ROOT_PATH . "\\Upload\\fonts\\jiangangshouxie.ttf";

        $imagebg = ImageHelper::loadImage($bgFileName);;
        $imagemegered = imagecreatetruecolor(imagesx($imagebg), imagesy($imagebg));
        imagecopy($imagemegered, $imagebg, 0, 0, 0, 0, imagesx($imagebg), imagesy($imagebg));

        $textcolor = imagecolorallocate($imagemegered, 85, 85, 85);
        imagefttext($imagemegered, 40, 0, 500, 855, $textcolor, $fontFileName, $name);
        $sinDate = DateHelper::format(null, "Y年m月d日");
        imagefttext($imagemegered, 20, 0, 450, 875, $textcolor, $fontFileName, $sinDate);

        $relativeFile = self::saveImageAndGetRelativePath($imagemegered);
        return $relativeFile;
    }

    public static function wurenji($name = "解然")
    {
        $bgFileName = PHYSICAL_ROOT_PATH . "\\Upload\\shenqi\\wurenji\\wurenji.jpg";
        $fontFileName = PHYSICAL_ROOT_PATH . "\\Upload\\fonts\\songti.TTF";
        //$fontFileName = PHYSICAL_ROOT_PATH . "\\Upload\\fonts\\jiangangshouxie.ttf";

        $imagebg = ImageHelper::loadImage($bgFileName);;
        $imagemegered = imagecreatetruecolor(imagesx($imagebg), imagesy($imagebg));
        imagecopy($imagemegered, $imagebg, 0, 0, 0, 0, imagesx($imagebg), imagesy($imagebg));

        $textcolor = imagecolorallocate($imagemegered, 85, 85, 85);
        imagefttext($imagemegered, 15, -2, 175, 245, $textcolor, $fontFileName, $name);
        $sinDate = DateHelper::format(null, "Y年m月d日");
        imagefttext($imagemegered, 11, -2, 195, 365, $textcolor, $fontFileName, $sinDate);

        $relativeFile = self::saveImageAndGetRelativePath($imagemegered);
        return $relativeFile;
    }

}
<?php

/**
 * Created by PhpStorm.
 * User: lovermeng
 * Date: 2016/1/4
 * Time: 19:43
 * Email:464262101@qq.com
 */
class NewImgTool
{
    private $fromPic=array(
        "image/jpeg"=>"imagecreatefromjpeg",
        "image/png"=>"imagecreatefrompng",
        "image/gif"=>"imagecreatefromgif"
    );
    private $outPic=array(
        "image/jpeg"=>"imagejpeg",
        "image/png"=>"imagepng",
        "image/gif"=>"imagegif"
    );
    private $extName=array(
        "image/jpeg"=>".jpg",
        "image/png"=>".png",
        "image/gif"=>".gif"
    );
    //图片路径
    //图片大小
    //保存路径
    public function createImg($path,$size,$dir){
        $type=getimagesize($path);
        //获取原图
        $img =  $this->fromPic[$type['mime']]($path);
        //创建新的画布
        $newImg=imagecreatetruecolor($size[0], $size[1]);
        //图片的等比例缩放
        $imgWidth=$type[0];
        $imgHeight=$type[1];
        //求比例
        $bili=max($imgWidth/$size[0],$imgHeight/$size[1]);
        //得新图大小
        $imgWidth1=$imgWidth/$bili;
        $imgHeight1=$imgHeight/$bili;
        //拷贝图片
        imagecopyresampled($newImg, $img, ($size[0]-$imgWidth1)/2, ($size[1]-$imgHeight1)/2, 0, 0, $imgWidth1, $imgHeight1, $imgWidth, $imgHeight);
        //填充颜色
        $white=  imagecolorallocate($newImg, 255, 255, 255);
        imagefill($newImg, 0, 0, $white);
        //输出
        //名字唯一

        $name=uniqid($GLOBALS["config"]["upload"]["prefix"]).$this->extName[$type['mime']];
        $flag=$this->outPic[$type["mime"]]($newImg,$dir.DS.$name);
        //释放资源
        imagedestroy($img);
        imagedestroy($newImg);
        return $name;

    }
}
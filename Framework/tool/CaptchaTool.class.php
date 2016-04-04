<?php
class CaptchaTool {
    private static function makeCode($max=4){
        $str="0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $str=  str_shuffle($str);
        return substr($str,0,$max);
    }
    public static function Draw($max=4){
        //创建画布
        $img=  imagecreatefromjpeg(TOOL_PATH."Captcha/captcha_bg".mt_rand(1, 5).".jpg");
        //设定颜色
        $color=  imagecolorallocate($img, 255, 255, 255);
        //设定文字的颜色
        $arr=array(
            imagecolorallocate($img, 255, 255, 255),
            imagecolorallocate($img, 0, 0, 0)
        );
        //绘制矩形
        imagerectangle($img, 0, 0, 144, 19, $color);
        //绘制文字
        @session_start();
        $yzm=self::makeCode($max);
        $_SESSION["yzm"] =  $yzm;        
        //绘制验证码
        imagestring($img, 5, 50, 2, $yzm, $arr[mt_rand(0, 1)]);
        //绘制杂色
        for($i=0;$i<3;$i++){
                imageline($img, mt_rand(0, 145), mt_rand(0, 20), mt_rand(0, 145), mt_rand(0, 20), $color);
        }
         //绘制点
        for($i=0;$i<50;$i++){
            imagesetpixel($img, mt_rand(0, 145), mt_rand(0, 20), $color);
        }
        header("Content-type:image/jpeg");
        imagejpeg($img);
        imagedestroy($img);        
    }
}
           
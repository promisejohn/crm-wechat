<?php
class CaptchaController {
    //显示验证码控制器
    public function showAction(){
        CaptchaTool::Draw();
    }
}

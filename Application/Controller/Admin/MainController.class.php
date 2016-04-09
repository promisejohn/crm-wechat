<?php
class MainController extends PlatformController{
    public function showAction(){

        if($_COOKIE["is_admin"]=="是"){
            static::showView("main");
        }else{
            static::assign("id",$_COOKIE);
            static::showView("main_x");
        }
    }
    //安全退出方法
    public function quitAction(){
        //清除session
        //session_start();
        $_SESSION["isLogin"]=true;
        unset($_SESSION["isLogin"]);//清除session信息
        session_destroy();//清除session文件
        static::jump("index.php?p=Admin&c=AdminInfo&a=login");
    }


}

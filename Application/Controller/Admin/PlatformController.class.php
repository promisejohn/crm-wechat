<?php
class PlatformController extends Controller {
    public function __construct() {
        session_start();
        if(in_array(ACTION, Array("login","check"))||(isset($_SESSION["isLogin"])&&$_SESSION["isLogin"]==true)){
                return;
        }else{
            static::jump("index.php?p=Admin&c=AdminInfo&a=login");
        }
    }
}

<?php
class AdminInfoController extends PlatformController {
    //加入login
    public function loginAction(){
        static::showView("login");
    }


    //验证表单
    public function checkAction(){
        $post=$_POST;
        $model=new MembersModel();
        $row=$model->check($post);


        if($row!=null){
            //session_start();
            $_SESSION["isLogin"]=true;

            $member_id =$row[0]["member_id"];//获取ID用于判断
            $time = date("Y-m-d H:i:s");//获取系统当前时间
            $model->upNowTime($time,$member_id);//更新最后登录时间
            $ip = $_SERVER['REMOTE_ADDR'];//获取用户最后登录的ip
            $model->upNowIP($ip,$member_id);//更新最后登录的ip


            //存储coolkie
            setcookie("member_id",$row[0]["member_id"]);
            setcookie("username",$row[0]["username"]);
            setcookie("password",md5($row[0]["password"]));
            setcookie("is_admin",$row[0]["is_admin"]);
            setcookie("isLogin",true);
            static::jump("index.php?p=Admin&c=Main&a=show");
        }else{
            static::jump("index.php?p=Admin&c=AdminInfo&a=login","账号密码输入有误,2秒后请重新输入",2);
        }



    }
}

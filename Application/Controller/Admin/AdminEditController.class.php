<?php
header("content-type:text/html;charset=utf-8");
/**
 * Created by PhpStorm.
 * User: hwj
 * Date: 2016/1/6
 * Time: 10:43
 */

class AdminEditController extends PlatformController{
    //显示数据
    public function listAction(){
        $mid = $_COOKIE["member_id"];
        $category = new MembersModel();
        $rows=$category->getNowList($mid);
        self::assign("rows", $rows);
        self::showView("AdminEdit");
    }
    //修改个人信息
    public function editAction(){
        //获取所有员工账号
        $category = new MembersModel();
        $rows=$category->getUserName();
        $un=array();
        foreach($rows as $key=>$value){
            if($value["username"]!=$_COOKIE["username"]){
                $un[$key]=$value["username"];
            }
        }

        $post=$_POST;
        $post["member_id"]=$_COOKIE["member_id"];


        if(in_array("",$post)){
            static::jump("index.php?P=Admin&c=AdminEdit&a=list","输入内容不能为空",2);
        }elseif(in_array($post["username"],$un)){
            static::jump("index.php?P=Admin&c=AdminEdit&a=list","输入账号重复,请重新输入",2);
        }elseif($post["password"]!=$post["password2"]){
            static::jump("index.php?P=Admin&c=AdminEdit&a=list","两次输入的密码不相同,请重新输入",2);
        }else{
            $post["password"]=md5($post["password"]);
            $model=new MembersModel();
            $model->upNowdate($post);
            static::jump2("index.php?p=Admin&c=AdminInfo&a=login","修改成功,请重新登录!!");
            //清除session
            unset($_SESSION["isLogin"]);//清除session信息
            session_destroy();//清除session文件
        }
    }



}
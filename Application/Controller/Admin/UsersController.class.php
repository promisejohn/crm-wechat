<?php

/**
 * Created by PhpStorm.
 * User: lovermeng
 * Date: 2016/1/5
 * Time: 21:58
 * Email:464262101@qq.com
 */
class UsersController extends PlatformController
{
    private static $pageIndex=1;//当前页
    private static $pageSize=5;//每页显示条数
    //显示会员
    public function listAction(){
        $model=new UsersModel();
        self::$pageIndex=isset($_GET["page"])?$_GET["page"]:1;
        //上一页
        $lastPage=self::$pageIndex-1;
        if($lastPage<1){
            $lastPage=1;
        }
        //获取 数据的总条数
        $num= $model->count();
        //获取总页数
        $pageTotal=ceil($num/self::$pageSize);
        //下一页
        $nextPage=self::$pageIndex+1;
        if($nextPage>=$pageTotal){
            $nextPage=$pageTotal;
        }
        $rows=$model->getPage(self::$pageIndex,self::$pageSize);//计算当前的开始位置
        //分配数据
        static::assign("rows",$rows);
        static::assign("pageIndex",self::$pageIndex);
        static::assign("num",$num);
        static::assign("lastPage",$lastPage);
        static::assign("nextPage",$nextPage);
        static::assign("pageTotal",$pageTotal);
        static::showView("users");
    }
    //显示添加会员
    public function addAction(){
        static::showView("add");
    }
    //制作数据添加的方法
    public function insertAction(){
        $post=$_POST;
        //调用model层添加数据
        $Users=new UsersModel();
        if($Users->insert($post)){
            static::jump("index.php?p=Admin&c=Users&a=list");
        }else{
            static::jump("index.php?p=Admin&c=Users&a=add","用户名不能相同;3秒后跳转添加页面",3);
        }
    }
    //删除会员（有消费记录的会员不能删除）
    public function removeAction(){
        $user_id=$_GET["user_id"];
        $Users=new UsersModel;
//        删除数据
        if(!$Users->remove($user_id)){
            static::jump("index.php?p=Admin&c=Users&a=list","该会员有消费记录不能删除",3);
        }else{
            static::jump("index.php?p=Admin&c=Users&a=list");
        }
    }
    //载入修改会员信息视图
    public function editAction(){
        //按照id获取数据
        $user_id=$_GET["user_id"];
        $Users=new UsersModel();
        //取出一条数据
        $row=$Users->getRow($user_id);
        //分派数据
        static::assign("row", $row);
        //载入视图
        static::showView("edit");
    }
    //更新会员
    public function updateAction(){
        $post=$_POST;
        $user_id=$_POST['user_id'];
        $user=new UsersModel();
        if($user->update($post,$user_id)){
            static::jump("index.php?p=Admin&c=Users&a=list");
        }else{
            static::jump("index.php?p=Admin&c=Users&a=list","修改失败!3秒后跳转类型列表页面",3);
        }
    }
    //模糊查询会员信息
    public function selAction(){
        $sel=$_POST['sel'];
        $user= new UsersModel();
        $rows=$user->select($sel);
        //分派数据
        static::assign("rows", $rows);
        //载入视图
        static::showView("sel");
    }
}
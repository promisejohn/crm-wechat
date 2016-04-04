<?php
/**
 * Created by PhpStorm.
 * User: lovermeng
 * Date: 2016/1/5
 * Time: 15:57
 * Email:464262101@qq.com
 */
header("content-type:text/html;charset=utf-8");
class GroupController extends PlatformController{
    //显示部门
    public function listAction(){
        //实例化类
        $Group=new GroupModel();
        $rows=$Group->getList();
        //设置数据
        static::assign("rows", $rows);
        //载入视图分派数据
        static::showView("group");
    }
    //显示添加部门
    public function addAction(){
        static::showView("add");
    }
    //制作数据添加的方法
    public function insertAction(){
        $post=$_POST;
        //调用model层添加数据
        $group=new GroupModel();
        if($group->insert($post)){
            static::jump("index.php?p=Admin&c=Group&a=list");
        }else{
            static::jump("index.php?p=Admin&c=Group&a=list","添加失败;<br/>该组名字已经存在<br/>3秒后跳转组类页面",3);
        }
    }
    //删除部门（有员工的部门不能删除）
    public function removeAction(){
        $group_id=$_GET["group_id"];
        $group=new GroupModel();
//        删除数据
        if(!$group->remove($group_id)){
            static::jump("index.php?p=Admin&c=Group&a=list","该部门有员工，不能删除！",3);
        }else{
            static::jump("index.php?p=Admin&c=Group&a=list");
        }
    }
    //修改部门
    public function editAction(){
    //按照id获取数据
        $group_id=$_GET["group_id"];
        $group=new GroupModel();
        //取出一条数据
        $row=$group->getRow($group_id);
        //分派数据
        static::assign("row", $row);
        //载入视图
        static::showView("edit");
    }
    //更新组
    public function updateAction(){
        $post=$_POST;
        $group_id=$_POST["group_id"];
        $group=new GroupModel();
        if($group->update($post,$group_id)){
            static::jump("index.php?p=Admin&c=Group&a=list");
        }else{
            static::jump("index.php?p=Admin&c=Group&a=list","修改失败!3秒后跳转类型列表页面",3);
        }
    }
}

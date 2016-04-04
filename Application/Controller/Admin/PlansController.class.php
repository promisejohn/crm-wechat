<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/6 0006
 * Time: 14:01
 */
header("content-type:text/html;charset=utf-8");
class PlansController extends PlatformController{
    //显示当前美发套餐
    public function listAction(){
        $model=new PlansModel();
        $row=$model->getList();
        self::assign("row",$row);
        self::showView("list");
    }
    //显示美发套餐
    public function addAction(){
        new PlansModel();
        //显示html  视图
        self::showView("add");
    }
    //添加美发套餐
    public function insertAction(){
        $post=$_POST;
        //调用model层添加数据
        $model=new PlansModel();
        //调用 insert方法
        if($model->insert($post)){
            static::jump("index.php?p=Admin&c=Plans&a=list");
        }else{
            static::jump("index.php?p=Admin&c=Plans&a=list","添加失败;<br/>该套餐名字已经存在<br/>3秒后跳转页面",3);
        }
    }
    //删除美发套餐
    public function removeAction(){
        //通过get传值  获得当前要删除的套餐的id 并通过变量保存下来
        $id=$_GET['id'];
        $name=$_GET['name'];
        $Model=new HistoriesModel();
        $row=$Model->getList();
        $model=new PlansModel();
        $arr=array();
        foreach($row as $val){
            $arr[]=$val['content'];
        }
        if(in_array($name,$arr)>0){
            static::jump("index.php?p=Admin&c=Plans&a=list","删除失败;<br/>该套餐有服务记录不能删除<br/>3秒后跳转页面",3);
            return false;
        }else {
            //调用删除方法  并判断是否执行成功
            if ($model->remove($id)) {
                static::jump("index.php?p=Admin&c=Plans&a=list");
            } else {
                static::jump("index.php?p=Admin&c=Plans&a=list", "删除失败;<br/>该套餐有服务记录不能删除<br/>3秒后跳转页面", 3);
            }
        }
    }
    public function editAction(){
        //按照id获取数据
        $id=$_GET["plan_id"];
        $model=new PlansModel();
        //取出一条数据
        $row=$model->getRow($id);
        $rows=$model->getGroup();
        self::assign("rows",$rows);
//        //分派数据
        static::assign("row", $row);
//        //载入视图
        static::showView("edit");
    }
    //修改套餐信息
    public function updateAction(){
        $post=$_POST;
        $id=$post["plan_id"];
        $plan=new PlansModel();
        if($plan->update($post,$id)){
            static::jump("index.php?p=Admin&c=Plans&a=list");
        }else{
            static::jump("index.php?p=Admin&c=Plans&a=list","修改失败;<br/>该商品名字已经存在<br/>3秒后跳转类型列表页面",3);
        }
    }
    //搜索套餐的方式
    public function searchAction(){
        $post=$_POST;
        $post=$post['search'];
        $model=new PlansModel();
        if($model->search($post)) {
            $row = $model->search($post);
            self::assign("row", $row);
            self::showView("search");
        }else{
            self::jump("index.php?p=Admin&c=Plans&a=list","搜索失败;<br/>该套餐不存在<br/>3秒后跳转类型列表页面",3);
        }
    }
}
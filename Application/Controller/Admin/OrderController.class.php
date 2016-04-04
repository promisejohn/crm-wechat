<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/7 0007
 * Time: 16:54
 */
header("content-type:text/html;charset=utf-8");
class OrderController extends PlatformController{
    private static $pageIndex=1;//当前页
    private static $pageSize=3;//每页显示条数
    //添加预约美发
    public function addAction(){
        $model=new OrderModel();
        $Model=new MembersModel();
        $row=$Model->getAll();
        $MODEL=new PlansModel();
        $rows=$MODEL->getAll("*","status!='下线'");
        //分派数据
        self::assign("rows",$rows);
        self::assign("row",$row);
        $model->add();
        self::showView("add");
    }
    //添加方法
    public function insertAction(){
        $post=$_POST;
        $model=new OrderModel();
        if($model->insert($post)){
            self::jump2("index.php?p=Admin&c=Main&a=show");
        }else{
            self::jump("index.php?c=Order&p=Admin&a=add","添加预约失败<br/>请重新检查输入信息是否有误",3);
        }
    }
    //显示预约列表
    public function listAction(){
        $model=new OrderModel();
        self::$pageIndex=isset($_GET["page"])?$_GET["page"]:1;
        //上一页
        $lastPage=self::$pageIndex-1;
        if($lastPage<1){
            $lastPage=1;
        }
        //获取 数据的总条数
        $num= $model->Count();
        //获取总页数
        $pageTotal=ceil($num/self::$pageSize);
        //下一页
        $nextPage=self::$pageIndex+1;
        if($nextPage>=$pageTotal){
            $nextPage=$pageTotal;
        }$rows=$model->getPage(self::$pageIndex,self::$pageSize);//计算当前的开始位置
        //分配数据
        static::assign("rows",$rows);
        static::assign("pageIndex",self::$pageIndex);
        static::assign("num",$num);
        static::assign("lastPage",$lastPage);
        static::assign("nextPage",$nextPage);
        static::assign("pageTotal",$pageTotal);
		$row=$model->getList();
//        echo"<pre>";
//        print_r($row);
        if(!empty($row)){
            self::assign("row",$row);
            self::showView("list");
        }else{
            self::jump2("index.php?p=Admin&c=Main&a=show","没有待处理的预约信息");
        }
    }
    //处理预约消息
    public function updateAction(){
        $post=$_POST;
//        echo"<pre>";
//        print_r($post);
//        exit;
        $id=$post['order_id'];
        $model=new OrderModel();
        if($model->updateData($post,"`order_id`='$id'")){
            static::jump("index.php?p=Admin&c=Order&a=show");
        }else{
            static::jump("index.php?p=Admin&c=Main&a=show","处理预约套餐信息失败<br/>3秒后跳转类型列表页面",3);
        }
    }
    //显示所有处理了的预约消息
    public function showAction(){
        $model=new OrderModel();
        $row=$model->show();
        self::assign("row",$row);
        self::showView("show");
    }
    //删除预约进度
    public function removeAction(){
        $order_id = $_POST['order_id'];
        $model = new OrderModel();
        $result = $model->delete($order_id);
        if($result){
            $model=new OrderModel();
            $row=$model->show();
            self::assign("row",$row);
            self::showView("show");
        }else{
            echo "删除失败";
        }
    }
}
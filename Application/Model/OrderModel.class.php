<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/7 0007
 * Time: 16:53
 */
header("content-type:text/html;charset=utf-8");
class OrderModel extends Model{
    //显示数据
    public function add(){
        return $this->getAll();
    }
    //添加预约
    public function insert($post){
        return $this->addRow($post);
    }
	 //分页获取数据
    public function getPage($start,$num){
        return $this->page("order_id,phone,reallyName,realname,name,content,date,status,reply",$start,$num);
    }
    public function Count(){
        return $this->getCount();
    }
    //显示预约信息
    public function getList(){
        return $this->getAll("*","`status`='未处理'");
    }
    //处理预约消息
    public function update($post){
        return $this->updateData($post);
    }
    //显示处理了的预约消息
    public function show(){
        return $this->getAll("*","status!='未处理'");
    }
}
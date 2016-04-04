<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/6 0006
 * Time: 14:00
 */
header("content-type:text/html;charset=utf-8");
class PlansModel extends Model{
    //显示当前美发套餐
    public function getList(){
        return $this->getAll();
    }
    //添加美发套餐
    public function add(){
        return $row=$this->addRow();
    }
    //添加美发套餐
    public function insert($post){
        return $this->addRow($post);
    }
    //删除美发套餐
    public function remove($id){
        $sql="delete from plans where plan_id=$id";
        return $this->db->query($sql);
    }
    //更新美发套餐
    public function update($post){
        return  $this->updateData($post);
    }
    //获取一条数据
    public function getRow($id){
        return $this->getId($id);
    }
    //获取group中的数据
    public function getGroup(){
        $sql="select * from `plans`";
        $rows=$this->db->fetchAll($sql);
        return $rows;
    }
    //搜索套餐名
    public function search($post){
        return $this->check($post);
    }
    //获取套餐内容
    //搜索套餐名
    public function getsAll(){
        $sql="select * from `plans` where `status`='上线'";
        $Plans=$this->db->fetchAll($sql);
        return $Plans;
    }
}
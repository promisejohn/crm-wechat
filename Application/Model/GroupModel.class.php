<?php

/**
 * Created by PhpStorm.
 * User: lovermeng
 * Date: 2016/1/5
 * Time: 16:02
 * Email:464262101@qq.com
 */
class GroupModel extends Model{
    //获取列表
    public function getList(){
        return $row=$this->getAll("*","");
    }
    //添加组
    public function insert($post){
        //获取数据
        $name=$post["name"];
        //判定同层次不能有相同的名字
        $sql="select count(*) from {$this->table()} where `name`='$name'";
        $num=  $this->db->fetchColumn($sql);
        if($num>0){
            return false;
        }
        return  $this->addRow($post);
    }
    //删除组
    public  function remove($group_id){
        //在删除的时候需要判定  如果该部门有员工 则不能删除
        $sql="select count(*) from `members` where group_id=$group_id";
        $num=$this->db->fetchColumn($sql);
        if($num>0){
            return false;
        }
        return $this->delete($group_id);
    }
    //编辑组
    public function getRow($id){
        return $this->getId($id);
    }
    public function update($post){
        return  $this->updateData($post);
    }
}
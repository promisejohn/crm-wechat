<?php
header("content-type:text/html;charset=utf-8");
/**
 * Created by PhpStorm.
 * User: hwj
 * Date: 2016/1/6
 * Time: 16:56
 */

class ArticleModel extends Model{
    //读取所有数据
    public function getList(){
        return $this->getAll();
    }
    //按ID查询某条数据
    public function quireID($id){
        return $this->getId($id);
    }
    //添加数据
    public function insert($post){
        return $this->addRow($post);
    }
    //修改数据
    public function update($post){
        return $this->updateData($post);
    }
    //按ID删除数据
    public function deleteID($id){
        return $this->delete($id);
    }
    //获取总条数
    public function count(){
        return $this->getCount();
    }
    //分页获取数据
    public function getPage($start,$num){
        return $this->page("article_id,title,content,start,end,time",$start,$num);
    }
    //得到在办的活动
    public function getOnline(){
        $time = date("Y-m-d");
        return $this->getAll("*","`start`<='$time'&&`end`>='$time'");
    }
}
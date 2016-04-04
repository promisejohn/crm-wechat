<?php
header("content-type:text/html;charset=utf-8");
/**
 * Created by PhpStorm.
 * User: hwj
 * Date: 2016/1/5
 * Time: 19:26
 */

class AdminEditModel extends Model{
    //读取当前用户的数据
    
    //修改数据
    public function update($post){
        $this->updateData($post);
    }










}
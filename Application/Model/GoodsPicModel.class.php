<?php

/**
 * Created by PhpStorm.
 * User: lovermeng
 * Date: 2016/1/4
 * Time: 20:34
 * Email:464262101@qq.com
 */
class GoodsPicModel extends Model
{
    //插入数据
    public function insert($post){
        //调用model
        return $this->addRow($post);
    }
}
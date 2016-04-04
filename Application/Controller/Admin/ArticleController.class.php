<?php
header("content-type:text/html;charset=utf-8");
/**
 * Created by PhpStorm.
 * User: hwj
 * Date: 2016/1/6
 * Time: 10:43
 */

class ArticleController extends PlatformController{

    //显示数据
    public function listAction(){
        //调用分页显示的方法
        $pages = self::cutPage("Article");

        self::assign("rows", $pages["rows"]);
        static::assign("pageIndex",$pages["pageIndex"]);
        static::assign("pageTotal",$pages["pageTotal"]);
        static::assign("num",$pages["num"]);

        self::showView("article");
    }

    //跳转页面
    public function showInsertAction(){
        self::showView("insert");
    }


    //添加活动的方法
    public function insertAction(){
        $post=$_POST;
        if(in_array("",$post)){
            static::jump("index.php?p=Admin&c=Article&a=insert","输入内容不能为空",2);
        }else{
            $category = new ArticleModel();
            $rows = $category->insert($post);
            if($rows){
                static::jump("index.php?p=Admin&c=Article&a=list","添加成功!",2);
            }
        }
    }
    //跳转编辑活动页面
    public function showUpdateAction(){
        $id=$_GET["id"];
        $category = new ArticleModel();
        $rows=$category->getId($id);

        self::assign("rows", $rows);
        self::showView("update");
    }
    //编辑活动的方法
    public function updateAction(){
        $post=$_POST;
        if(in_array("",$post)){
            static::jump("index.php?p=Admin&c=Article&a=insert","输入内容不能为空",2);
        }else{
            $category = new ArticleModel();
            $rows = $category->update($post);
            if($rows){
                static::jump("index.php?p=Admin&c=Article&a=list","修改成功!",2);
            }
        }
    }
    //删除活动的方法
    public function deleteAction(){
        $id=$_GET["id"];
        $category = new ArticleModel();
        $rows = $category->deleteID($id);
        if($rows){
            static::jump("index.php?p=Admin&c=Article&a=list");
        }
    }






}
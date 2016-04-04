<?php
class CategoryController extends PlatformController{
    public function listAction(){
        $category=new CategoryModel();
        $rows=$category->getList();
        self::assign("rows", $rows);
        self::showView("category");
    }
    /**
     * 制作删除方法
     */
    public function removeAction(){
        $id=$_GET["id"];
        $category=new CategoryModel();
//        删除数据
        if(!$category->remove($id)){
            static::jump("index.php?p=Admin&c=Category&a=list","请先删除子元素",3);
        }else{
            static::jump("index.php?p=Admin&c=Category&a=list");
        }
    }
    
    /**
     * 编辑类型
     */
    public function editAction(){
//        按照id获取数据
        $id=$_GET["id"];
        $category=new CategoryModel();
        //取出一条数据
        $row=$category->getRow($id);
        //取出分类树
        $tree=$category->getList();
        //分派数据
        static::assign("row", $row);
        static::assign("tree",$tree);
        //载入视图
        static::showView("edit");
    }
    /**
     * 更新数据
     */
    public function updateAction(){
        $post=$_POST;
        $category=new CategoryModel();
        if($category->update($post)){
            static::jump("index.php?p=Admin&c=Category&a=list");
        }else{
            static::jump("index.php?p=Admin&c=Category&a=list","修改失败;<br/>该商品名字已经存在<br/>3秒后跳转类型列表页面",3);
        }
    }
    /**
     *  载入添加页面
     */
    public function addAction(){
//        绑定分类数据
        $category=new CategoryModel();
         $rows=$category->getList();
        self::assign("tree", $rows);
        //显示一个视图
        static::showView("add");
    }
    /**
     * 制作数据添加的方法
     */
    public function insertAction(){
        $post=$_POST;        
        //调用model层添加数据
        $category=new CategoryModel();
        if($category->insert($post)){
            static::jump("index.php?p=Admin&c=Category&a=list");
        }else{
            static::jump("index.php?p=Admin&c=Category&a=list","添加失败;<br/>该商品名字已经存在<br/>3秒后跳转类型列表页面",3);
        }
    }
}

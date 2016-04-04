<?php
class GoodsController extends PlatformController {
    private static $pageIndex=1;
    private static $pageSize=6;

    //显示列表
    public function listAction(){
        $model=new GoodsModel();
        self::$pageIndex=isset($_GET["page"])?$_GET["page"]:1;
        if(self::$pageIndex<1)   self::$pageIndex=1;
        //获取 数据的总条数
        $num= $model->count();
        //获取总页数
        $pageTotal=ceil($num/self::$pageSize);
        if(self::$pageIndex>$pageTotal) self::$pageIndex=$pageTotal;

        $rows=$model->getPage(self::$pageIndex,self::$pageSize);//计算当前的开始位置
        //分配数据
        static::assign("rows",$rows);
        static::assign("pageIndex",self::$pageIndex);
        static::assign("pageTotal",$pageTotal);
        static::showView("list");
    }
    //添加add方法
    public function addAction(){
        $category=new CategoryModel();
        $rows=$category->getList();
        static::assign("rows", $rows);
        //载入视图
        static::showView("add");
    }
    
    public function insertAction(){
        //获取所有表单数据
        $post=$_POST;
        //使用商品model类
        $model=new GoodsModel();
        //获取上传文件 原图  缩略图
        //???这里在页面上去掉勾选之后，再选上就会报错，说goods_thumb未定义
        $image_ori=$_FILES["image_ori"];
        $goods_thumb=empty($_FILES["goods_thumb"])?'':$_FILES["goods_thumb"];

        //使用上传控件；
        //上传图片
        $upload=new UploadTool();
        $post["image_ori"]=$upload->uploadFile($image_ori);
        //判定是否上传成功
        if($post["image_ori"]==false){
            static::jump("index.php?p=Admin&c=Goods&a=add",$upload->error_info,3);
        }else{
            //判断是否要 程序生成缩略图
            if(isset($post["auto_thumb"])){
                $newImg=new NewImgTool();
                $post["goods_thumb"]=$newImg->createImg($GLOBALS["config"]["upload"]["dir"].DS. $post["image_ori"], array(200,200), "./goods");
            }else{
                // 手动上传缩略图
                $post["goods_thumb"]=$upload->uploadFile($goods_thumb);
                if($post["goods_thumb"]==false){
                    static::jump("index.php?p=Admin&c=Goods&a=add",$upload->error_info,3);
                }
            }
        }
//取出精品 新品 热销
        $status=0;
        //得到商品状态
        if(isset($post["goods_status"])){
            foreach ($post["goods_status"] as $value){
                $status=$status|$value;
            }
        }
        $post["goods_status"]=$status;
        //写入数据库 写入到商品库
        $model->insert($post);
        $files=$_FILES["img_url"];
        //上传商品其他图片
        $upload->mulittype($files);//多文件上传
        //遍历属性 nameArr取得上传名字
        //将商品的其他图片名字入库
        foreach ($upload->nameArr as $value){
            //取得名字
            $row["pic_name"]=$value;
            //找到图片所属产品
            //找商品id
            $row["goods_id"]=$model->getRowId($post["goods_name"]);
            //录入数据
            $pic=new GoodsPicModel();
            $pic->insert($row);
        }
        //------没完
    }
    
}

<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/5 0005
 * Time: 15:31
 */
header("content-type:text/html;charset=utf-8");
class MembersController extends PlatformController{
    /**
     * 制作头像
     */
    public function picAction($pic){
        if(!empty($pic['name'])){
            //图片上传
            $upload=new UploadTool();
            $newname=$upload->uploadFile($pic);
            //制作头像缩略图
            $NewImg=new NewImgTool();
            $path='goods/img/'.$newname;//原图路径
            $size=array(30,30);//画布大小
            $dir='goods/img/';
            $name=$NewImg->createImg($path, $size, $dir);
            $post['path']=$name;
        }else {
            $post['path']='zhangheng.jpg';//如果没有上传图片 则给一个默认图片
        }
        return $post['path'];
    }
    //显示员工列表的所有数据
    public function listAction(){
        $model=new MembersModel();
        //调用list方法
        $rows=$model->getList();
        //分派数据到html中
        static::assign("rows",$rows);
        //显示html  视图
        self::showView("list");
    }
    //添加员工列表
    public function addAction(){
        $model=new MembersModel();
        $Model=new GroupModel();
        $row=$Model->getAll();
        //调用list方法
        $rows=$model->getList();
        //分派数据到html中
        static::assign("row",$row);
        static::assign("rows",$rows);
        //显示html  视图
        self::showView("add");
    }

    //添加员工
    public function insertAction(){
        $post=$_POST;
        if(empty($post['username'])||empty($post['username'])||empty($post['username'])){
            echo "带*号的项为必填项，请填写后再提交";
            return ;
        }else {
            $pic = $_FILES['head_pic'];
            $post['head_pic'] = $this->picAction($pic);
            $member = new MembersModel();
            $re = $member->insert($post);
            if ($re) {
                static::jump("index.php?p=Admin&c=Members&a=list", "添加成功三秒后跳转到列表页面", 3);
            } else {
                static::jump("index.php?p=Admin&c=Members&a=list", "添加失败三秒后回到到添加页面", 3);
            }
        }
    }
    //删除员工信息
    public function removeAction(){
        //通过get传值  获得当前要删除的员工的id 并通过变量保存下来
        $id=$_GET['member_id'];
        $model=new MembersModel();
        $Model=new HistoriesModel();
        $row=$Model->getAll();
        $arr=array();
        foreach($row as $val){
            $arr[]=$val["member_id"];
        }
        //调用删除方法  并判断是否执行成功
        if(in_array($id,$arr)){
            static::jump("index.php?p=Admin&c=Members&a=list","删除失败;<br/>该员工有服务记录不能删除<br/>3秒后跳转页面",3);
            return ;
        }else{
            $model->delete($id);
            static::jump("index.php?p=Admin&c=Members&a=list");
        }
    }
    public function editAction(){
        //按照id获取数据
        $id=$_GET["member_id"];
        $model=new MembersModel();
        //取出一条数据
        $row=$model->getRow($id);
        $rows=$model->getGroup();
        self::assign("rows",$rows);
        //分派数据
        static::assign("row", $row);
        //载入视图
        static::showView("edit");
    }
    //修改员工信息
    public function updateAction(){
        $post=$_POST;
        $id=$post["member_id"];
        if(empty($post['username'])||empty($post['username'])||empty($post['username'])){
            echo "带*号的项为必填项，请填写后再提交";
            return ;
        }else {
            $pic = $_FILES['head_pic'];
            $post['head_pic'] = $this->picAction($pic);
            $member = new MembersModel();
            if ($member->update($post, "member_id=$id")) {
                static::jump("index.php?p=Admin&c=Members&a=list");
            } else {
                static::jump("index.php?p=Admin&c=Members&a=list", "修改失败;<br/>该商品名字已经存在<br/>3秒后跳转类型列表页面", 3);
            }
        }
    }
    //查看员工服务信息
    public function scanAction(){
        $id=$_GET["member_id"];
        $model=new MembersModel();
        $row=$model->getHistories($id);

        if(empty($row)){
            static::jump2("index.php?p=Admin&c=Main&a=show", "该员工暂时没有服务记录");
            return ;
        }
        self::assign("row",$row);
        self::showView("histories");
    }
}
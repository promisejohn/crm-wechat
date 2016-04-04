<?php
class Controller {
    private $smarty;
    public function __construct(){
        $this->smarty = new SmartyTool();
        $this->smarty->setTemplateDir(array(VIEW_PATH."Test".DS));
        $this->smarty->setCompileDir(VIEW_PATH."smartyView");
    }
    protected function assigns($name,$value=null){
        $this->smarty->assign($name,$value);
    }
    protected function display($viewname){
        $this->smarty->display($viewname);
    }
    /**
     *  分页跳转
     * @param type $pageIndex 当前页
     * @param type $pageSize 每页显示条数
     */
    protected static function cutPage($tbName,$pageIndex=1,$pageSize=3){
        //实例化对应的model
        $model = $tbName."Model";
        $category = new $model();

        //获取当前页面
        $pageIndex=isset($_GET["page"])?$_GET["page"]:1;
        if($pageIndex<1){
            $pageIndex=1;
        }

        //获取 数据的总条数
        $num = $category->count();

        //获取总页数
        if($num==0)$num=1;//除数不能为0
        $pageTotal=ceil($num/$pageSize);
        if($pageIndex>$pageTotal){
            $pageIndex=$pageTotal;
        }
        //显示当前页面数据
        $rows=$category->getPage($pageIndex,$pageSize);

        //返回开始位置,当前页,总页数,总条数
        $pages = array(
            "rows"=>$rows,
            "pageIndex"=>$pageIndex,
            "pageTotal"=>$pageTotal,
            "num"=>$num);
        return $pages;
    }
    /**
     *  跳转函数
     * @param type $url 连接地址
     * @param type $time 等待时间
     * @param type $msg  等待时显示的消息
     */
    protected static function jump($url,$msg="",$time=0){
         //判定服务器是否发送文件头
        if(!headers_sent()){
            //是否有延迟时间
            if($time==0){
                header("Location:$url");
            }else{
                echo $msg;

                header("Refresh:$time;$url");
            }
        }else{
            if($time==0){
                echo "<meta http-equiv='refresh' content='0;url=$url'/>";
            }else{
                echo $msg;
                echo "<meta http-equiv='refresh' content='$time;url=$url'/>";
            }
        }
    }

    //js跳转
    protected static function jump2($url,$msg=""){
        if($msg!=""){
            echo "<script>alert('$msg')</script>";
        }
        echo "<script>window.parent.frames.location.href='$url'</script>";
    }


    //这个 变量用来保存前台数据
    private static $data=array();
    //设置方法 保存数据
    public static function assign($key,$value){
        self::$data["$key"]=$value;//获取前端数据 保存到data 数组中
    }
   //载入视图
    public static function showView($viewName){
        //将$data 进行分离  键作为变量 值作为变量值
        extract(self::$data);
        require CURRENT_VIEW_PATH.$viewName.'.html';
    }




}

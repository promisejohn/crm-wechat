<?php

/**
 * Created by PhpStorm.
 * User: lovermeng
 * Date: 2016/1/12
 * Time: 18:17
 * Email:464262101@qq.com
 */
require "./VipWechat/autoload.php";
use Overtrue\Wechat\Server;
use Overtrue\Wechat\Message;
use Overtrue\Wechat\Menu;
use Overtrue\Wechat\MenuItem;

class WechatController extends Controller
{
    //验证微信账号
    private $_appId = "wxa3cd73cc60df95c7";
    private $_token = "lovermeng";
    private $_appsecret = "d4624c36b6795d1d99dcf0547af5443d";


    public function yanzhengAction()
    {
        $server = new Server($this->_appId, $this->_token);//进行验证微信地址
        //监听关注事件
        $server->on('event', 'subscribe', function ($event) {
            return Message::make('text')->content("您好！欢迎关注！");
        });


        //文本


        //处理用户提交的文本信息
        $server->on('message','text',function($message){
            //拿到用户的openid
            $openid = $message['FromUserName'];
            //判定用户发送的是不是 绑定手机+手机号
            $str = $message['Content'];
            if(strpos($str,"绑定手机")!==false){
                $telephone = substr($str,-11,11);
                //查询手机号在数据库里边存在不，存在的话就给验证码，不存在的话就提示不是会员
                $model =new UsersModel;
                $num = $model->getCount("`telephone`='$telephone'","*");
                if($num>0){
                    //先查询数据这个手机号对应的openid是不是为空，为空的话就生成验证码保存到数据库，再发送给客户让客户验证
                    $is_openid = $model->getCount("`telephone`='$telephone'","openid");
                    if($is_openid>0){
                        return Message::make('text')->content("您已经绑定微信号，请先解除绑定");
                    }else{
                        //生成验证码
                        $str="0123456789";
                        $str=  str_shuffle($str);
                        $yzm = substr($str,0,4);
                        //首先查询这个验证码在数据里边是否存在，存在的话重新生成验证码，不存在的话直接入库
                        $is_code = $model->getCount("`code`='$yzm'","code");
                        if($is_code>0){
                            $yzm=$model->echoYzm();
                            $post=array("code"=>$yzm);
                            $model->updateData($post,"`telephone`='$telephone'");
                            //然后将该验证码显示给用户
                            return Message::make('text')->content("$yzm");
                        }else{
                            $post=array("code"=>$yzm);
                            $model->updateData($post,"`telephone`='$telephone'");
                            //然后将该验证码显示给用户
                            return Message::make('text')->content("$yzm");
                        }
                    }
                }else{
                    return Message::make('text')->content("您输入的手机号不正确或者暂未成为本店会员请到店充值");
                }
            }
            //判断用户发送的是验证码+验证码
            if(strpos($str,"验证码")!==false){
                $code = substr($str, -4, 4);
                //如果用户发送的验证码正确的话就绑定微信号，如果验证码错误就提示验证码错误请重新输入
                $model = new UsersModel();
                $num = $model->getCount("`code`='$code'","code");
                if($num>0){
                    //根据唯一的验证码绑定该微信号
                    $post=array("openid"=>$openid);
                    $model->updateData($post,"`code`='$code'");
                    return Message::make('text')->content("绑定成功");
                }else{
                    return Message::make('text')->content("验证码错误，请重新获取");
                }

            }
            switch ($message['Content']){
                case '帮助':
                    return Message::make('text')->Content("收到帮助信息");
                    break;
                case '最新活动':
                    $model = new ArticleModel();
                    $articles = $model ->getOnline();
                    $data = array();
                    foreach($articles as $article){
                        $data[] = Message::make('news_item')
                            ->title($article['title'])
                            ->url('http://www.idoiwill.cn/index.php?p=Admin&c=Wechat&a=viewArticle&article_id='.$article['article_id'])
                            ->picUrl('http://www.baidu.com/demo.jpg');
                    }
                    $news = Message::make('news')->items($data);
                    return $news;
                    break;
                case '我要预约':
                    return Message::make('text')->Content('点此预约http://www.idoiwill.cn/index.php?p=Admin&c=Wechat&a=viewOrder');
                    break;
                case '消费记录':
                    return Message::make('text')->Content('查看消费记录http://www.idoiwill.cn/index.php?p=Admin&c=Wechat&a=consCal&openid='.$message['FromUserName']);
                    break;
                case '解除绑定':
                    //根据openid拿到对应的手机号
                    $model= new UsersModel();
                    $result = $model->freeWechats($openid);
                    if($result===-1){
                        return Message::make('text')->Content('该手机号未绑定微信');
                    }elseif($result==true){
                        return Message::make('text')->Content('解除绑定成功');
                    }else{
                        return Message::make('text')->Content('解除绑定失败');
                    }
            }
        });

        //菜单


        //点击菜单事件
        $server->on('event', 'CLICK', function($event){
            switch ($event['EventKey']){
                case 'Latest_Activity'://最新活动
                    $model = new ArticleModel();
                    $articles = $model ->getOnline();
                    $data = array();

                    foreach($articles as $article) {
                        $data[]= Message::make('news_item')
                            ->title($article['title'])
                            ->url('http://www.idoiwill.cn/index.php?p=Admin&c=Wechat&a=viewArticle&article_id='.$article['article_id'])
                            ->picUrl('http://www.baidu.com/demo.jpg');
                    }
                    $news = Message::make('news')->items($data);
                    return $news;
                    break;
                case 'Binding'://绑定手机
                    return Message::make('text')->content('点此绑定手机http://www.idoiwill.cn/index.php?p=Admin&c=Wechat&a=viewBinding&openid='.$event['FromUserName']);
                    break;
                case 'ExpenseCalendar'://查看消费记录
                    return Message::make('text')->Content('查看消费记录http://www.idoiwill.cn/index.php?p=Admin&c=Wechat&a=consCal&openid='.$event['FromUserName']);
                    break;
            }

        });

        echo $server->serve();

    }
    //设置自定义菜单
    public function setMenuAction(){
        $menuService = new Menu($this->_appId,$this->_appsecret);

        $menu2 = new MenuItem("个人信息");
        $menus = array(
            $menu1 = new MenuItem("最新活动", 'click', 'Latest_Activity'),
            $menu2->buttons(array(
                new MenuItem('绑定手机', 'click', 'Binding'),
                new MenuItem('预约', 'view', 'http://www.idoiwill.cn/index.php?p=Admin&c=Wechat&a=viewOrder'),
                new MenuItem('消费记录', 'click', 'ExpenseCalendar')
            ))
        );
        try {
            $menuService->set($menus);// 请求微信服务器
            echo '设置成功！';
        } catch (\Exception $e) {
            echo '设置失败：' . $e->getMessage();
        }
    }

    //显示在办活动
    public function viewArticleAction()
    {
        $article_id = $_GET['article_id'];
        $model = new ArticleModel();

        $rows = $model->quireID($article_id);
        //分派数据
        static::assign("rows", $rows);
        //载入视图
        static::showView("article");
    }
    //显示预约界面
    public function viewOrderAction(){
        $model1=new MembersModel();
        $row=$model1->getAll();
        $model2=new PlansModel();
        $rows=$model2->getAll("*","status!='下线'");
        //分派数据
        self::assign("rows",$rows);
        self::assign("row",$row);
        //载入视图
        static::showView("orderadd");
    }
    //预约添加方法
    public function insertOrderAction(){
        $post=$_POST;
        $model=new OrderModel();
        if($model->insert($post)){
            echo "预约成功";
        }else{
            echo"预约失败";
        }
    }
    //载入获取验证码页面
    public function viewBindingAction(){
        $openid=$_GET['openid'];
        self::assign("openid",$openid);
        static::showView("viewbinding");
    }
    //得到验证码（如果手机号不存在不能绑定，存在的话给验证码）
    public function getYzmAction(){
        $phoneNum=$_POST['sjh'];
        $openid=$_POST['openid'];
        //查询该手机号在数据库里边是否为空
        $model = new UsersModel();
        $num = $model->getCount("`telephone`='$phoneNum'","*");
        if($num>0){
            //先查询数据这个手机号对应的openid是不是为空，为空的话就生成验证码保存到数据库，再发送给客户让客户验证
            $is_openid = $model->getCount("`telephone`='$phoneNum'","openid");
            if($is_openid>0){
                echo"您已经绑定微信号，请先解除绑定";
                static::assign("phoneNum",$phoneNum);
                static::assign("openid",$openid);
                static::showView("freebinding");
            }else{
                //生成验证码
                $str="0123456789";
                $str=  str_shuffle($str);
                $yzm = substr($str,0,4);
                //首先验证码入库
                $post=array("code"=>$yzm);
                $model->updateData($post,"`telephone`='$phoneNum'");
                //然后将该验证码显示给用户
                static::assign("phoneNum",$phoneNum);
                static::assign("openid",$openid);
                static::assign("yzm",$yzm);
                static::showView("dobinding");
            }
        }else{
            echo "您输入的手机号不正确或者暂未成为本店会员请到店充值";
        }
    }
    //处理绑定手机
    public function dobindingAction(){
        $post = $_POST;
        $code = $post['code'];
        $model = new UsersModel();
        //判断用户输入的验证码是不是正确的
        $num = $model->getCount("`code`='$code'","code");
        if($num>0){
            if($model->bindWecht($post)){
                echo "绑定成功";
            }else{
                echo "绑定失败";
            }
        }else{
            echo "验证码错误，请重新输入";
        }

    }
    //取消绑定
    public function freebindingAction(){
        $post = $_POST;
        $user = new UsersModel();
        if($user->freeWechat($post)){
            echo "取消绑定成功";
        }else{
            echo "取消绑定失败";
        }
    }
    //查看消费记录
    public function consCalAction(){
        $openid = $_GET['openid'];
        //判定是不是该微信号是不是会员，是会员的话就跳转到消费记录页面，如果不是会员的话就跳转到绑定手机页面
        $model1 = new UsersModel();
        $user = $model1->getByOpenid($openid);
        if($user){
            $model2 = new HistoriesModel();
            $rows=$model2->selectRecords($openid);
            static::assign("rows", $rows);
            //载入视图
            static::showView("sle");
        }else{
            echo "您未绑定手机，请先绑定http://www.idoiwill.cn/index.php?p=Admin&c=Wechat&a=viewBinding&openid='.$openid";
        }
    }
}
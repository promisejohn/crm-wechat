<?php

/**
 * Created by PhpStorm.
 * User: lovermeng
 * Date: 2016/1/6
 * Time: 16:01
 * Email:464262101@qq.com
 */
class HistoriesController extends Controller
{
    //显示充值和消费页面
    public function recandconAction(){
        //按照id获取数据
        $user_id=$_GET["user_id"];
        $Users=new UsersModel();
        //取出一条数据
        $row=$Users->getRow($user_id);
        //分派数据
        static::assign("row", $row);
//        载入视图
        static::showView("recandcon");
    }
    //载入充值页面
    public function recAction(){
        //按照id获取数据
        $user_id=$_GET["user_id"];
        $Users=new UsersModel();
        //取出一条数据
        $row=$Users->getRow($user_id);
        //分派数据
        static::assign("row", $row);
        //载入视图
        static::showView("rec");
    }
    //执行充值
    public function czAction(){
        $post=$_POST;
        //调用model层添加数据
        $Histories=new HistoriesModel();
        if($Histories->insert($post)){
            //按照id获取数据
            $user_id=$_POST["user_id"];
            $Users=new UsersModel();
            //取出一条数据
            $row=$Users->getRow($user_id);
            //充值送费规则
            if($post['amount']<500){
                $money=array("money"=>$row['money']+$post['amount']);
            }elseif($post['amount']>=500&&$post['amount']<1000){
                $money=array("money"=>$row['money']+$post['amount']+100);
            }elseif($post['amount']>=1000&&$post['amount']<5000){
                $money=array("money"=>$row['money']+$post['amount']+300);
            }else{
                $money=array("money"=>$row['money']+$post['amount']+2000);
            }
            //充值
            $Users->updateData($money,"`user_id`='$user_id'");
            $time=date("Y-m-d H:i:s");//获取系统当前时间
            $mid=$_COOKIE['member_id'];//当前管理员ID
            $maxId=$Histories->maxHid();//得到刚插入进数据库的记录号
            $res=$Histories->lastCon($maxId);//得到该会员消费的上一条记录
            $oldRemainder=$res['amount'];//得到上次消费的金额
            $remainder=array("remainder"=>$money["money"]+$oldRemainder,"member_id"=>$mid,"time"=>$time);//得到现在的余额,和交易的员工id
            $Histories->updateData($remainder,"`history_id`='$maxId'");
            //判断是否升级为vip；
            $is_vip=array("is_vip"=>1);
            if($post['amount']>=5000){
                $Users->updateData($is_vip,"`user_id`='$user_id'");
            }
            static::jump("index.php?c=Users&p=Admin&a=list");
        }else{
            static::jump("index.php?c=Users&p=Admin&a=list","充值失败;3秒后跳转组类页面",3);
        }
    }
    //载入消费页面
    public function consAction(){
        //按照id获取数据
        $user_id=$_GET["user_id"];
        $Users=new UsersModel();
        //取出一条数据
        $row=$Users->getRow($user_id);
        //分派数据
        static::assign("row", $row);

        $member= new MembersModel();
        $mem=$member->getsAll();
        //分派数据
        static::assign("mem", $mem);

        $plans= new PlansModel();
        $plan=$plans->getsAll();
        //分派数据
        static::assign("plan", $plan);

        //载入视图
        static::showView("cons");
    }
    //载入记录视图
    public function sleAction(){
        $user_id=$_GET['user_id'];
        $records=new HistoriesModel();
        $rows=$records->createViews($user_id);
        static::assign("rows", $rows);
        //载入视图
        static::showView("sle");
    }

    //执行消费功能
    public function xfAction(){

        $user_id=$_POST['user_id'];//当前会员号
        $member_id=$_POST['m_id'];//服务员工号
        $plan_id=$_POST['plan_id'];//得到消费套餐的编号
        $type=$_POST['type'];//交易类型为消费
        $time=date("Y-m-d H:i:s");//获取系统当前时间

        //获取当前员工的余额
        $User=new UsersModel();
        //取出一条数据
        $row=$User->getRow($user_id);
        $yu_e=$row['money'];//得到该用户的余额
        $is_vip=$row['is_vip'];//得到该用户是不是VIP

        //得到当前套餐的金额和内容
        $plans= new PlansModel();
        //取出一条数据
        $res=$plans->getRow($plan_id);
        $tcj=$res['money'];//得到该套餐的金额
        $tcm=$res['name'];//得到该套餐的名字

        //如果该客户是会员，消费打5折
        if($is_vip==1){
            $sjMoney=$tcj*0.5;//实际消费的钱
                if($yu_e<$sjMoney){
                    echo '<script>alert("您的余额不足，不能选择这个套餐消费")</script>';
                    exit;
                }else{
                    $sxMoney=$yu_e-$sjMoney;//客户剩下的钱
                }

        }else{
            if($yu_e<$tcj){
                echo '<script>alert("您的余额不足，不能选择这个套餐消费")</script>';
                exit;
            }else{
                $sxMoney=$yu_e-$tcj;//客户剩下的钱
            }
        }
        $Users=new UsersModel();
        $money=array("money"=>$sxMoney);
        $Users->updateData($money,"`user_id`='$user_id'");//更改该用户的余额

        $post=array("user_id"=>$user_id,"member_id"=>$member_id,"type"=>$type,"amount"=>0-$tcj,"content"=>$tcm,"time"=>$time,"remainder"=>$sxMoney);
        $Histories=new HistoriesModel();
        if($Histories->insert($post)){
            static::jump("index.php?c=Users&p=Admin&a=list");
        }else{
            static::jump("index.php?c=Users&p=Admin&a=list","消费失败;3秒后跳转组类页面",3);
        }
    }
}
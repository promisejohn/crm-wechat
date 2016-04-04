<?php

/**
 * Created by PhpStorm.
 * User: lovermeng
 * Date: 2016/1/5
 * Time: 21:59
 * Email:464262101@qq.com
 */
class UsersModel extends Model
{
    //获取列表
    public function getList(){
        return $row=$this->getAll("*","");
    }
    //分页获取数据
    public function getPage($start,$num){
        return $this->page("user_id,username,realname,sex,telephone,money",$start,$num);
    }
    public function count(){
        return $this->getCount();
    }
    //添加会员
    public function insert($post){
        //获取数据
        $name=$post["username"];
        //有相同的用户名则不能添加成功
        $sql="select count(*) from {$this->table()} where  `username`='$name'";
        $num=  $this->db->fetchColumn($sql);
        if($num>0){
            return false;
        }
        return  $this->addRow($post);
    }
    //删除会员
    public  function remove($user_id){
        //有消费记录的会员不能删除
        $sql="select count(*) from `histories` where user_id=$user_id";
        $num=$this->db->fetchColumn($sql);
        if($num>0){
            return false;
        }
        return $this->delete($user_id);
    }
    //编辑会员
    public function getRow($id){
        return $this->getId($id);
    }
    public function update($post){
        return  $this->updateData($post);
    }

    //模糊查询会员信息
    public function select($sel){
        $sql="select * from `users` where user_id like '%$sel%' or username like '%$sel%' or realname like '%$sel%' or sex like '%$sel%' or telephone like '%$sel%' or remark like '%$sel%' or is_vip like '%$sel%'";
        return $this->db->fetchAll($sql);
    }
    static $code=NULL;
    public function echoYzm(){
        //生成唯一的验证码
        $str="0123456789";
        $str=  str_shuffle($str);
        self::$code = substr($str,0,4);
        $code=self::$code;
        $is_code = $this->getCount("`code`='$code'","code");
        if($is_code>0){
            self::$code = substr($str,0,4);
        }else{
            self::$code =$code ;
        }
        return self::$code;
    }

    //根据code更新会员信息，绑定会员微信号
    public function bindWecht($post){
        $telephone = $post['phoneNum'];
        $openid = $post['openid'];
        $code = $post['code'];
        $sql = "update `users` set `openid`='$openid' where `code`=$code AND `telephone`=$telephone";
        return $this->db->query($sql);
    }
    //跳转取消绑定
    public function freeWechat($post){
        $telephone = $post['phoneNum'];
        $openid = $post['openid'];
        $sql = "update `users` set `openid`=NULL where `openid`='$openid' AND `telephone`=$telephone";
        return $this->db->query($sql);
    }
    //输入文字信息取消绑定
    public function freeWechats($openid){
        //判定该openid是否绑定，绑定了的话就取消绑定，未绑定的话就执行取消绑定
        $num = $this->getCount("`openid`='$openid'","openid");
        if($num>0){
            $sql = "update `users` set `openid`=NULL where `openid`='$openid'";
            return $this->db->query($sql);
        }else{
            return -1;
        }
    }
    //根据openid获取到用户的一条记录
    public function getByOpenid($openid){
        $sql = "select * from `users` where `openid`='$openid'";
        return $this->db->fetchRow($sql);
    }
    //根据telephone获取到用户的一条记录
    public function getByPhone($telephone){
        $sql = "select * from `users` where `telephone`='$telephone'";
        return $this->db->fetchRow($sql);
    }
    //根据code获取到用户的一条记录
    public function getByCode($code){
        $sql = "select * from `users` where `code`='$code'";
        return $this->db->fetchRow($sql);
    }


}
<?php

/**
 * Created by PhpStorm.
 * User: lovermeng
 * Date: 2016/1/6
 * Time: 16:02
 * Email:464262101@qq.com
 */
class HistoriesModel extends Model
{
    //获取列表
    public function getList(){
        return $row=$this->getAll("*","");
    }
    public function insert($post){
        return  $this->addRow($post);
    }
    public function getRow($id){
        return $this->getId($id);
    }
    //创建数据库视图
    public function createViews($user_id){
        $sqlView="select hi.history_id,me.username,hi.type,hi.amount,hi.content,hi.time,hi.remainder from histories as hi left join members as me on hi.member_id=me.member_id where hi.user_id=$user_id";
        return $this->db->fetchAll($sqlView);
    }
    //创建查询数据数据最新的消费记录
    public function maxHid(){
        $sql="select max(history_id) from histories";
        return $this->db->fetchColumn($sql);
    }
    //创建查询上一次消费记录
    public function lastCon($history_id){
        $sql="select * from histories where history_id<$history_id order by history_id desc limit 0,1";
        return $this->db->fetchAll($sql);
    }
    //按充值消费情况排名获取数据
    public function getRankList(){
        $sql = "select us.realname,hi.type,sum(hi.amount) as amountall from histories as hi left join users as us on hi.user_id=us.user_id group by hi.user_id,hi.type ORDER BY sum(hi.amount) DESC";
        return  $this->db->fetchAll($sql);
    }
    //按员工操作排序
    public function getRankMembersList(){
        $sql = "select me.realname from histories as hi left join members as me on hi.member_id=me.member_id where type='消费' group by hi.member_id ORDER BY count(hi.member_id) DESC";
        return  $this->db->fetchAll($sql);
    }

    //根据微信号查询消费记录
    public function selectRecords($openid){
        //根据openid得到该会员的user_id
        $sql1="select `user_id` from `users` where `openid`='$openid'";
        $user_id=$this->db->fetchColumn($sql1);
        $sql2="select hi.history_id,me.username,hi.type,hi.amount,hi.content,hi.time,hi.remainder from histories as hi left join members as me on hi.member_id=me.member_id where hi.user_id=$user_id";
        return $this->db->fetchAll($sql2);
    }
}
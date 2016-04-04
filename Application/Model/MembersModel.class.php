<?php
class MembersModel extends Model {
    //声明读取所有数据的方法
    public function getList(){
        return $this->getAll();
    }
    //制作数据
    public function check($post){
        $adminName=$post["username"];
        $adminPwd=md5($post["password"]);
        //创建sql语句
        $row = $this->getAll("*"," username='$adminName' and password='$adminPwd'");
        return $row;
    }


    //读取当前用户的方法
    public function getNowList($mid){
        $rows = $this->getAll("*"," member_id='$mid'");
        return $rows;
    }
    //修改当前用户数据
    public function upNowdate($post){
        $this->updateData($post);
    }

    //获取所有员工账号
    public function getUserName(){
        return $this->getAll("username");
    }
    //修改最后登录时间
    public function upNowTime($time,$member_id){
        $sql ="update `members` set last_login='$time' where member_id='$member_id'" ;
        $this->db->query($sql);
    }
    //修改最后登录时间
    public function upNowIp($ip,$member_id){
        $sql ="update `members` set `last_loginip`='$ip' where member_id='$member_id'" ;
        $this->db->query($sql);
    }




    //添加员工
    public function add(){
        return $row=$this->addRow();
    }
    //添加员工
    public function insert($post){
        $post["password"]=md5($post["password"]);
        return $this->addRow($post);
    }
    //删除员工
    public function remove($id){
        return $this->delete($id);
    }
    //更新员工资料
    public function update($post){
        $post["password"]=md5($post["password"]);
        //执行
        return  $this->updateData($post);
    }
    //获取一条数据
    public function getRow($id){
        return $this->getId($id);
    }
    //获取group中的数据
    public function getGroup(){
        $sql="select * from `group`";
        $rows=$this->db->fetchAll($sql);
        return $rows;
    }
    //获取histories的数据
    public function getHistories($id){
        $sql="select hi.history_id,us.realname,hi.type,hi.amount,hi.content,hi.time from histories as hi left join users as us on hi.user_id=us.user_id where hi.member_id=$id";
        $his=$this->db->fetchAll($sql);
        return $his;
    }



    //取得员工表所有数据
    public function getsAll(){
        $sql="select * from `members`";
        $rowsMem=$this->db->fetchAll($sql);
        return $rowsMem;
    }

}

<?php
class SessionDBTool {
    //声明属性
    private $db;
    //出重写session机制
    public function __construct() {
//        session_set_save_handler($open, $close, $read, $write, $destroy, $gc);
        session_set_save_handler(
               array($this,"open"),
                array($this,"close"),
                array($this,"read"),
                array($this,"write"),
                array($this,"destroy"),
                array($this,"gc")
                );
        @session_start();
    }
    //公有的
    public function open($path,$name){
        //创建数据库的连接
        $this->db=DB::getInstance(array("dbName"=>"myshop"));
        return true;
    }
    //重写close
    public function close(){
        return true;
    }
     //重写read
    public function read($session_id){
        //通过id查询
        $sql="select s_data from `session` where Id=$session_id";
        return $this->db->fetchColumn($sql);
    }
      //重写 write
    public function write($session_id,$data){
        $sql="insert into `session` values('$session_id','$data',UNIX_TIMESTAMP()) on duplicate key update s_time=UNIX_TIMESTAMP()";
        return $this->db->query($sql);
    }
     //重写 destroy
    public function destroy($session_id){
        //执行删除
         $sql="delete from 'session' where id=$session_id";
         return $this->db->query($sql);
    }
    //重写 gc
    public function gc($maxTime){
        $sql="delete from 'session' where UNIX_TIMESTAMP()>s_time+$maxTime";
         return $this->db->query($sql);
    }
}

<?php
class DB {
  // 找类的属性 host  user  password  port(端口)  charset 数据库名 dbname  
  private $host;
  private $user;
  private $password;
  private $port;
  private $charset;
  private $dbName;
  //设置连接资源的属性
  public $link;
  //在构造函数中初始化对象成员//私有化构造函数，不允许外部new出对象
  private function __construct($config=array()) {
      //给host 属性赋值
      //给属性初始化
      $this->host=  isset($config["host"])?$config["host"]:"localhost";
      $this->user=  isset($config["user"])?$config["user"]:"root";
      $this->password=  isset($config["password"])?$config["password"]:"123";
      $this->port=  isset($config["port"])?$config["port"]:"3306";
      $this->charset=  isset($config["charset"])?$config["charset"]:"utf8";
      $this->dbName=  isset($config["dbName"])?$config["dbName"]:"";
      //自动调用连接数据库
      $this->connect();
      //设置字符集
      $this->setCharset();
      //选定数据库
      $this->selectDB();
  }
    //私有静态变量 去控制创建对象的个数
    private static $obj=null;
    //创建公有静态方法，实现创建对象
    public static function getInstance($config=array()){
        //如果对象不存在，新建，如果存在就直接返回对象
        if(self::$obj==null){
            self::$obj=new DB($config);
        }
        return self::$obj;
    }
    //禁止克隆
    private function __clone(){

    }
    //序列化调用的函数 项目中使用时  返回空
    public function __sleep() {
        return array("host","user");
    }

    //反序列化调用
    public function __wakeup() {
        //自动调用连接数据库
        $this->connect();
        //设置字符集
        $this->setCharset();
        //选定数据库
        $this->selectDB();
    }
    //构造连接数据库的方法
  public function connect(){
//      mysql_connect()
      //判定连接是否成功 没有成功 输出连接错误；
      //保存连接资源     
      if(! $this->link=  mysql_connect($this->host.":".$this->port,  $this->user,  $this->password)){
          echo "错误信息是:".mysql_errno()."<br/>";
          echo mysql_error();
          exit();
      }
  } 
/**
 * 设置字符集编码
 */
  public function setCharset(){
      //用户录入了空的字符编码
      if($this->charset==""){
          echo "字符编码不能为空";
          exit();
      }
     if(!mysql_set_charset($this->charset,  $this->link)){
         echo "错误信息是:".mysql_errno()."<br/>";
         echo mysql_error();
         exit();
     }
  }
  /**
   * 选择数据库   * 
   */
  public function selectDB(){
      if($this->dbName==""){
           echo "数据库名字不能为空";
          exit();
      }
      if(!mysql_select_db($this->dbName,  $this->link)){
          echo "错误信息是:".mysql_errno()."<br/>";
          echo mysql_error();
          exit();
      }
  }
  
  /*
   * 执行sql语句方法
   */
   public function query($sql){
       if(!mysql_query($sql)){
           echo "错误信息是:".mysql_errno()."<br/>";
           echo mysql_error();
           exit();
       }else{
           return true;
       }
       
   }
   /**
    * 读取所有数据的方法  fetchAll 
    * 执行select语句
    * 将资源数据 保存到数组中 
    * 将数据送达给外部的页面 做方法返回值
    */
   public  function fetchAll($sql){
       //执行不成功返回错误
       //#sql 认为是查询语句
       if(!$resource=mysql_query($sql)){
           echo "错误信息是:".mysql_errno()."<br/>";
           echo mysql_error();
           return false;
       }else{
           //将资源数据 放入到数组中
           $rows=array();
           while($row=  mysql_fetch_assoc($resource)){
               $rows[]=$row;
           }
           return $rows;
       }
   }
   
   /**
    * 获取一条记录 fetchRow
    *  调用 读取所有数据的方法 去执行sql  select、
    * 取出数组中的第一个记录
    */
   public function fetchRow($sql){
       //调用 fetchAll 取得所有数据
       $rows=$this->fetchAll($sql);
       if(!$rows){
           return false;
       }else{
           return array_shift($rows);
       }
   }
    /**
     * 获取第一行 第一列的数据  fetchColumn
     * 执行 查询中的 聚合函数
     */
   public function fetchColumn($sql){
       //调用 fetchAll 取得所有数据
       $row=$this->fetchRow($sql);
       if(!$row){
           return false;
       }else{
           return array_shift($row);
       }
   }
   /*
    * 析构中释放连接
    */
   public function __destruct() {
//       mysql_close();
   }
}

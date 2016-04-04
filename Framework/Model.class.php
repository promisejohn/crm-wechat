<?php
class Model {
    protected $db;
    protected $tableName="";
    protected $fields=array();


    public function __construct(){
        $this->init();
    }
//初始化类的属性
    public function init(){
        $this->db=DB::getInstance($GLOBALS["config"]["db"]);
        $this->getFields();
    }
//取得表名
    public function table(){      
        if(empty($this->tableName))
           $this->tableName='`'.$GLOBALS["config"]["db"]["pre"].  strtolower(substr(get_class($this),0,-5)).'`';        
        return $this->tableName;
    }
//获取所有字段名； 对主键 进行了特殊记录
    public function getFields(){
        //执行sql语句 查询表结构
        $sql="desc {$this->table()}";
        $rows = $this->db->fetchAll($sql);
        //遍历rows 将表的结构 放置于 $this->fields; 要知道谁是主键
        foreach ($rows as $row){
            if($row["Key"]=="PRI"){
                $this->fields["pk"]=$row["Field"];
            }else{
                $this->fields[]=$row["Field"];
            }
        }
        return $rows;
    }
//按照主键删除一条数据
    public function delete($id){
        $sql="delete from {$this->table()} where `{$this->fields["pk"]}`=$id";
       return $this->db->query($sql);
    }
//根据主键查询一条数据
    public function getId($id){
        $sql="select * from {$this->table()} where `{$this->fields["pk"]}`=$id";
        return $this->db->fetchRow($sql);
    }
//查找所有记录
    public function getAll($fileds="*",$condition=""){
        $sql="select $fileds from {$this->table()}";

        if(!empty($condition)){
            $sql.=' where '.$condition;
        }
        return $this->db->fetchAll($sql);
    }
    /**
     * 分页查找
     * @param type $fileds
     * @param type $condition
     * @return type
     */
    public function page($fileds="*",$start,$num){
        $start1=($start-1)*$num;
        $sql="select $fileds from {$this->table()} limit $start1,$num";
        return $this->db->fetchAll($sql);
    }

    /**
     * 插入语句
     * @param type $post
     * @return type
     */
    public function addRow($post){
        $sql="insert into {$this->table()}";
        //insert into 表名
        //在录入数据时 开发者 为了方便 快速收录用户 实质上没有完全录入字段的值，在添加数据时，字段名 来源表单项的内容
        //过滤
        $this->filter($post);
        //取出所有的post中的key
        $keyArr=  array_keys($post);
        $keyArr=array_map(function($val){return '`'.$val.'`';}, $keyArr);
        //将数组转换为字符串
        $str=  implode(",", $keyArr);
        //拼接字段名
        $sql.=' ('.$str.')';
        //拼接values中的值
        $valueArr=  array_values($post);
         $valueArr=array_map(function($val){return "'".$val."'";}, $valueArr);
         $valStr=  implode(",", $valueArr);
//         拼接sql
         $sql.=" values(".$valStr.")";
         return $this->db->query($sql);
    }
//构建修改语句
    public function updateData($post,$condition=""){

        $sql = "update {$this->table()}";
        $this->filter($post);
        $str="";
        foreach ($post as $key=>$val){
            $str.="`$key`='$val',";
        }
        //去掉最右边的，
        $str = rtrim($str,",");
        $sql.=" set ".$str;
        //对拼写的条件进行判定
        if(!empty($condition)){
            $sql.=" where ".$condition;
        }elseif(isset($post[$this->fields['pk']])){
            $sql.=" where `{$this->fields['pk']}`='{$post[$this->fields['pk']]}'";
        }else{
            return false;
        }
        return $this->db->query($sql);
    }
/**
 * 统计查询记录的条数
 * @param type $field
 * @param type $condition
 * @return type
 */
    public function getCount($condition="",$field="*"){
        //构建sql
        if($field!="*")
           $sql="select count(`$field`) from {$this->table ()}";
        else
            $sql="select count($field) from {$this->table ()}";
        if(!empty($condition))
              $sql.=" where ".$condition;
      
         return $this->db->fetchColumn($sql);
    }
    public function check($post){
        $sql="select * from {$this->table()} WHERE `name` like '%$post%' or des like '%$post%' or `money` like '%$post%'";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * 过滤所有字段
     */
    public function filter(&$post){
        //过滤非法字段
        foreach ($post as $key=>$value){
            //$key不在字段中
            if(!in_array($key, $this->fields)){
                unset($post[$key]);
            }
        }
    }
}

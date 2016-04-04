<?php
class CategoryModel extends Model {
    //读取所有数据
    public function getList(){
        $rows=$this->getAll();
        //调用getTree
        $tree=$this->getTree($rows);
        return $tree;
    }
    
     /**
     * 将数据进行树形展示
     * @param type $rows
     */
    public function getTree($rows,$parentId=0,$deep=0){
        //声明一个数组 保存 树形组合出来的数组
        static $tree=array();
        //遍历数组 找父亲id=0；
        foreach ($rows as $row){
            if($row['parent_id']==$parentId){
                //找到数据列中父级id==传过来的父级id
                //给row单独加列 为txt 做名字的缩进
                $row["txt"]=  str_repeat("&nbsp;", $deep*5).$row["cateName"];
                $tree[]=$row;
                $this->getTree($rows,$row["id"],$deep+1);            
            }
        }        
        return $tree;
    }
    /**
     * 按id进行删除
     * @param type $id
     */
    public  function remove($id){
//        //在删除的时候需要判定  如果当前 数据 有子类型 不能删除
//        //获取所有数据
        $rows=  $this->getList();
        foreach($rows as $row){
            if($row["parent_id"]==$id){
                return false;
            }
        }
//        $sql="delete from {$this->table()} where id=$id";
        return $this->delete($id);
    }
    /**
     * 获取一行数据
     * @param type $id
     * @return type
     */
    public function getRow($id){
//        $sql="select * from {$this->table()} where id=$id";
        return $this->getId($id);
    }
    /**
     * 更新数据
     * @param type $post 传递的表单数据
     * @return boolean
     */
    public function update($post){
        $cateName=$post["cateName"];
        $intro=$post["intro"];
        $parent_id=$post["parent_id"];
        $id=$post["id"];
        //判定同级目录下 是否有相同名字
//        $sql="select count(*) from {$this->table()} where parent_id=$parent_id and cateName='$cateName'and id<>$id";
        $num =  $this->getCount("parent_id=$parent_id and cateName='$cateName'and id<>$id");
        if($num>0){
            return false;
        }     
       return  $this->updateData($post);
    }
    
    public function insert($post){
        //获取数据
        $cateName=$post["cateName"];
        $intro=$post["intro"];
        $parent_id=$post["parent_id"];
        //判定同层次不能有相同的名字
        $sql="select count(*) from {$this->table()} where parent_id=$parent_id and cateName='$cateName'";
        $num=  $this->db->fetchColumn($sql);
        if($num>0){
            return false;
        }
//        $sql="insert into {$this->table()} (cateName,intro,parent_id) values('$cateName','$intro',$parent_id)";
        return  $this->addRow($post);
    }
           
}

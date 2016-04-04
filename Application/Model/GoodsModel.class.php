<?php
class GoodsModel extends Model {
    //获取列表
    public function getList(){
        return $this->getAll("goods_id,goods_name,shop_price,market_price,goods_num","");
    }
    //分页获取数据
    public function getPage($start,$num){
        return $this->page("goods_id,goods_name,shop_price,market_price,goods_num",$start,$num);
    }
    public function count(){
        return $this->getCount();
    }
    //创建model
    //封装添加商品的方法
    public function insert($post){
        return $this->addRow($post);
    }
    /**
     * 获取id号
     * @param type $name
     * @return type
     */
    public function getRowId($name){
        $rows= $this->getAll("goods_id","goods_name='$name'");
        return $rows[0]["goods_id"];
    }
}

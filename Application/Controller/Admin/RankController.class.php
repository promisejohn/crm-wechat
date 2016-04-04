<?php
header("content-type:text/html;charset=utf-8");
/**
 * Created by PhpStorm.
 * User: hwj
 * Date: 2016/1/7
 * Time: 16:24
 */

class RankController extends PlatformController{
    //按充值消费情况排名的方法
    public static $i=0;
    public static $j=0;
    public function listAction(){
        $category = new HistoriesModel();
        $rows=$category->getRankList();
        $rowsMembers = $category->getRankMembersList();

        $cz=array();
        $xf=array();
        foreach($rows as $key=>$value){
            if($value["type"]=="充值"){
                $cz[self::$i]=$value;
                self::$i++;
            }elseif($value["type"]=="消费"){
                $xf[self::$j]=$value;
                self::$j++;
            }
        }


        self::assign("cz", $cz);
        self::assign("xf", $xf);
        self::assign("yg", $rowsMembers);
        self::showView("rank");
    }
}
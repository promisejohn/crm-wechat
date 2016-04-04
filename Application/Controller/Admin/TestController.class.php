<?php
//测试smarty集成进框架没有
class TestController extends Controller{
    function indexAction(){
        $name = '小';
        $this->assigns("name",$name);
        $this->display("test.html");
    }
}

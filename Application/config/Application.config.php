<?php
return array(
    "db"=>array(
        'host'=>"localhost",
        'user'=>"a0407210828",
        'password'=>"f0b1cb6e",
        'port'=>"3306",
        'dbName'=>"a0407210828",
        'charset'=>"utf8",
        'pre'=>""
    ),
    "app"=>array(
        'platform'=>"Admin",
        'controller'=>"AdminInfo",
        'action'=>"login"
    ),
    "upload"=>array(
        "dir"=>"./goods/img",
        "allow_types"=>array(
            "image/jpeg","image/png","image/gif"
        ),
        "max_size"=>2*1024*1024,
        "prefix"=>""
    )
);


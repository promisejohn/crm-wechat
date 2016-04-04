<?php
return array(
    "db"=>array(
        'host'=>"localhost",
        'user'=>"root",
        'password'=>"iean",
        'port'=>"3306",
        'dbName'=>"wechat",
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
        "prefix"=>"ft_"
    )
);


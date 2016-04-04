<?php
class Application {
    public static function run(){
        self::initPath();
        self::initConfig();
        self::initParam();        
        self::classMapping();
        self::initAutoload();
        self::dispacher();
    }
//    1、	配置使用的常量
    private static function initPath(){
        defined('DS') or define('DS',DIRECTORY_SEPARATOR);//定义目录分隔符常量
        //$_SERVER['SCRIPT_FILENAME'] 请求运行的文件绝对路径  dirname() 取出文件的所在目录
        defined('ROOT_PATH') or define('ROOT_PATH',dirname($_SERVER['SCRIPT_FILENAME']).DS);//定义网站的根目录
        defined('APP_PATH') or define('APP_PATH',ROOT_PATH.'Application'.DS);//定义Application的目录
        defined('FRAME_PATH') or define('FRAME_PATH',ROOT_PATH.'Framework'.DS);//定义Framework的目录
        defined('CONFIG_PATH') or define('CONFIG_PATH',APP_PATH.'config'.DS);//Config的目录
        defined('CONTROLLER_PATH') or define('CONTROLLER_PATH',APP_PATH.'Controller'.DS);//定义Controller的目录
        defined('MODEL_PATH') or define('MODEL_PATH',APP_PATH.'Model'.DS);//定义Controller的目录
        defined('VIEW_PATH') or define('VIEW_PATH',APP_PATH.'View'.DS);//定义View的目录
        defined('TOOL_PATH') or define('TOOL_PATH',FRAME_PATH.'tool'.DS);//定义framework中tool的目录
    }
//2、	引入配置文件
    private static function initConfig(){
        $GLOBALS["config"]=require CONFIG_PATH.'Application.config.php';
    }
//3、	配置平台 平台参数  控制器参数  方法参数
    private static function initParam(){
        defined("PLATFORM") or define("PLATFORM",isset($_GET["p"])?$_GET["p"]:$GLOBALS["config"]["app"]["platform"]);
        defined("CONTROLLER") or define("CONTROLLER",isset($_GET["c"])?$_GET["c"]:$GLOBALS["config"]["app"]["controller"]);
        defined("ACTION") or define("ACTION",isset($_GET["a"])?$_GET["a"]:$GLOBALS["config"]["app"]["action"]);
        //定义当前指向平台常量
        defined('CURRENT_CONTROLLER_PATH') or define('CURRENT_CONTROLLER_PATH',CONTROLLER_PATH.PLATFORM.DS);
        defined('CURRENT_VIEW_PATH') or define('CURRENT_VIEW_PATH',VIEW_PATH.PLATFORM.DS.CONTROLLER.DS);
    }
    
//4、	执行控制器的方法
    private static function dispacher(){
        //引入控制器        
        //new类
        $controllerName=CONTROLLER."Controller";
        $controller=new $controllerName();
        //执行action    
        $actionName=ACTION."Action";
        $controller->$actionName();
    }


    //制作 特殊文件包含
    private static function classMapping(){
        $GLOBALS['mapping']=array(
           'DB'=>TOOL_PATH."DB.class.php",
           'Model'=>FRAME_PATH.'Model.class.php',
           'Controller'=>FRAME_PATH."Controller.class.php"
         );
    }
    
    //制作自动加载方法
    private  static function userAutoLoad($className){
        //载入Model的类文件
        //Controller类文件
        if(isset($GLOBALS['mapping'][$className])){         
            require $GLOBALS['mapping'][$className];
        }elseif(substr($className,-10)=="Controller"){
            require CURRENT_CONTROLLER_PATH."{$className}.class.php";
        }elseif(substr($className,-5)=="Model"){
            require MODEL_PATH."$className.class.php";
        }elseif(substr($className,-4)=="Tool"){
            require TOOL_PATH."$className.class.php";
        }
    }
    
    //注册自动加载的方法
    private static function initAutoload(){
        spl_autoload_register("Application::userAutoLoad");
    }
    
}

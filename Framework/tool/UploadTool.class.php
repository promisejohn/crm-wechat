<?php
class UploadTool {
    //需要初始化的属性
    private $dir;//保存文件的目录
    private $allow_types;//文件类型
    private $max_size;  //文件的大小  
    public $error_info="";//错误提示
    private $prefix;//文件名的前缀
    public $nameArr=array();
    /**
     * 初始化上传成员
     * @param type $dir
     * @param type $allow_types
     * @param type $max_size
     * @param type $prefix
     */
    public function __construct() {
        $this->dir=$GLOBALS["config"]["upload"]["dir"];
        $this->allow_types=$GLOBALS["config"]["upload"]["allow_types"];
        $this->max_size=$GLOBALS["config"]["upload"]["max_size"];
        $this->prefix=$GLOBALS["config"]["upload"]["prefix"];
    }
    
    public function uploadFile($file){
        if($file["error"]!=0){
            $this->error_info=$file["error"];
            return false;
        }
        //验证类型
        if(!in_array($file["type"], $this->allow_types)){
            $this->error_info="文件类型错误";
            return false;
        }
        //验证文件大小
        if($file["size"]>$this->max_size){
            $this->error_info="文件超过定义的大小";
            return false;
        }
        //取出文件的扩展名
        $extName=substr($file["name"], strrpos($file["name"], '.'));
        $newName=  uniqid($this->prefix).$extName;
        //上传
        if(move_uploaded_file($file['tmp_name'], $this->dir.DS.$newName))
                return $newName;
        else
            return false;
        
    }
    //多文件上传
    public function mulittype($files){
        $file=array();
        for($i=0;$i<count($files["name"]);$i++){
            $file["name"]=$files["name"][$i];
            $file["type"]=$files["type"][$i];
            $file["tmp_name"]=$files["tmp_name"][$i];
            $file["error"]=$files["error"][$i];
            $file["size"]=$files["size"][$i];
            $this->nameArr[]=$this->uploadFile($file);
        }
    }
}

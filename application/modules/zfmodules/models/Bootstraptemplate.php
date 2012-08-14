<?php

class Zfmodules_Model_Bootstraptemplate
{
    protected $moddir;
    protected $arstructdir = array(
            'controllers','models','views','views/filters','views/helpers','views/scripts'
                );

    public function __construct($moddir)
    {
        $this->moddir = $moddir;
        //generate bootsrap file...                       
        foreach($this->releaseupdatedModule($moddir) as $key=>$row){
            if (!is_file($this->moddir."/".$row."/Bootstrap.php")){
                $filename = APPLICATION_PATH."/modules/zfmodules/views/helpers/bootstrap.tmpl";
                $handle = fopen($filename, "r");
                $contents = str_replace("Modz",ucfirst($row), str_replace("&lt;","<", fread($handle, filesize($filename))));
                
                //write file bootstrap...
                $fp = fopen($this->moddir."/".$row."/Bootstrap.php", 'w+');
                fwrite($fp, $contents);
            }
        }
    }    
        
    
    private function releaseupdatedModule($moddir)
    {
        $dirs = array();
        if ($handle = opendir($moddir)){
           while (false !== ($file = readdir($handle))) {
                if ($file!="." && $file!=".."){
                    $dirs[] = $file;                    
                }
           }
        }        
        return $dirs;
        
    }
    
    public function setDir($modname)
    {
        if (!is_dir($this->moddir."/".$modname))
            mkdir($this->moddir."/".$modname);
            
        foreach($this->arstructdir as $key=>$row)
        {
            if (!is_dir($this->moddir."/".$modname."/".$row))
                mkdir($this->moddir."/".$modname."/".$row);
        }
    }    
}
<?php 
    class Config
    {
        public static function get($path =null)
        {
            if($path)
            {
                //wish it be accessible form everywhere in this class
                $config=$GLOBALS['config'];
                $path=explode('/',$path);
                foreach($path as $address){
                    if(isset($config[$address])){
                        $config=$config[$address];
                    }
                }
                return $config;
            }
            return false;
        }
    }
?>
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
                foreach($path as $bit){
                    if(isset($config[$bit])){
                        $config=$config[$bit];
                    }
                }
                return $config;
            }
            return false;
        }
    }
?>
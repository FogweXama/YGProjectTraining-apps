<?php //crossside research forgery refutal ie avoid 
//adding data using the url box  and needs to be generated in our form
    class Token{
        public static function generate(){
            return Session::put(Config::get('session/token_name'), md5(uniqid()));
        }
        public static function check($token){
            $tokenName=Config::get('session/token_name');
            if(Session::exists($tokenName)&& $token===Session::get($tokenName)){
                Session::delete($tokenName);
                return true;
            }
            return false;
        }
    }
?>
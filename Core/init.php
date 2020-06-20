<?php  //files for all classes
session_start();
//put in our superglobal and array of arrays that carry the information about our db, session and cookies
$GLOBALS['config']=array(
        'mysql'=>array(
            'host'=>'localhost',
            'username'=>'root',
            'password'=>'',
            'db'=>'yglearndb'
        ),
        'remember'=>array(
            'cookie_name'=>'hash',
            'cookie_expiry'=>604800
        ),
        'session'=>array(
            'session_name'=>'txtUserName',
            'token_name'=>'token'
        )
    );
//loading the other classes needed...ie. require_once 'Classes/config.php..
spl_autoload_register(function($class){
    require_once 'Classes/'. $class .'.php';
});

require_once 'Functions/sanitize.php';

if(Cookie::exists(Config::get('remember/cookie_name'))&&!Session::exists(Config::get('session/session_name'))) {
    $hash=Cookie::get(Config::get('remember/cookie_name'));
    $hashCheck=DB::getInstance()->get('tblsession', array('Hash', '=', $hash));
    if($hashCheck->count()){
        $user=new User($hashCheck->first()->ID);
        $user->login();
    }
}
?>
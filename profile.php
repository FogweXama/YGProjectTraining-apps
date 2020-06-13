<?php
require_once 'Core/init.php';

if(!$username=Input::get('user')){
    Redirect::to('index.php');
}else{
    $user=new User($username);
    if(!$user->exists()){
        Redirect::to('Include/Error/404.php');
    }else{
        $data=$user->data();
    }
}
?>
    <h3><?php echo escape($data->username); ?> </h3>
    <p> Full name: <?php echo escape($data->name); ?></p>


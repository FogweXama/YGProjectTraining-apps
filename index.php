<?php
require_once 'Core/init.php';

if(session::exists('success')){
    echo '<p>'. Session::flash('success').'</p>';
}

$user = new User();

if($user->isLoggedIn()){
?>
    <p>Hello <a href="profile.php?user=<?php echo escape($user->data()->username); ?>"><?php echo escape($user->data()->username); ?></a></p>
    <ul>
        <li><a href="logout.php">Logout</a></li>
        <li><a href="update.php">Edit Profile</a></li>
        <li><a href="changepassword.php">Change Password</a></li>
    </ul>
<?php
    if($user->hasPermission('Administrator')){
        echo '<p>You are an administrator</p>';
    }
    else if($user->hasPermission('User')){
        echo '<p>You are an User</p>';
    }
}else{
    
    echo '<p align="center"> You need to <a href="routeLogin.php">Login</a> or <a href="routeRegister.php">Register</a> to enjoy our services</p>';
    require 'skeleton.php';
}
?>
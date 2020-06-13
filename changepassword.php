<?php
require_once 'Core/init.php';

$user=new User();

if(!$user->isLoggedIn()){
    Redirect::to('index.php');
}
if(Input::exists()){
    if(Token::check(Input::get('pwd_token'))){
        $validate=new Validate();
        $validation=$validate->check($_POST, array(
            'current_pwd'=>array(
                'required'=>true,
                'min'=>6
            ),
            'new_pwd'=>array(
                'required'=>true,
                'min'=>6
            ),
            'renew_pwd'=>array(
                'required'=>true,
                'min'=>6,
                'matches'=>'new_pwd'
            )
        ));
        if($validation->passed()){
            if(Hash::make(Input::get('current_pwd'), $user->data()->salt!==$user->data()->password)){
                    echo 'Your password is wrong';
            }
            else{
                echo 'Password matches';
                $salt=Hash::salt(32);
                $user->update(array(
                    'password'=> Hash::make(Input::get('new_pwd'), $salt),
                    'salt'=>$salt
                ));
                Session::flash('home', 'Your password has been changed');
                Redirect::to('index.php');
            }
        }
        else{
            foreach($validation->errors() as $error){
                echo $error, '<br>';
            }
        }
    }
}
?>

<form action="" method="post">
    <div class="">
        <label for="current_pwd">Old Password</label>
        <input type="password" name="txtCurrentPassword" id="current_pwd" autocomplete="off">
    </div>
    <div class="">
        <label for="new_pwd">New Password</label>
        <input type="password" name="txtNewPassword" id="new_pwd" autocomplete="off">
    </div>
    <div class="">
        <label for="renew_pwd">Re-enter New Password</label>
        <input type="password" name="txtReNewPassword" id="renew_pwd" autocomplete="off">
    </div>

    <input type="hidden" name="pwd_token" value="<?php echo Token::generate(); ?>">
    <input type="submit" value="Change Password"><br>
</form>
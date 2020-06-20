<?php 
require_once 'Core/init.php';
    if(Input::exists()){
        if(Token::check(Input::get('token'))){
            $validate=new Validate();
            $validation=$validate->check($_POST,array(
                'txtusername'=>array('required'=>true),
                'txtpassword'=> array('required'=>true)
            ));
        if($validation->passed()){
            $user=new User();
            $remember=(Input::get('remember')==='on')?true:false;
            $login =$user->login(Input::get('username'), Input::get('password'), $remember);
            if($login){
                Redirect::to(index.php);
            }
            else{
                echo 'login failed';
                Redirect::to(Includes/errors/failedlogin.php);
            }
        }else{
             foreach($validation->errors() as $error){
                 echo $error, '<br>';
             }
        }
        }
    }

?>
<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8 col-sm-12">
        <div id="box">
            <form action="" method="post">
                <div class="row m-0 p-2">
                    <div class="col-md-3 col-sm-4"><label for="username">Username or Email</label></div>
                    <div class="col-md-9 col-sm-8"><input type="text" name="txtUsername" id="username" autocomplete="off"></div>
                </div>
                <div class="row m-0 p-2">
                    <div class="col-md-3 col-sm-4"><label for="password">Password</label></div>
                    <div class="col-md-9 col-sm-8"><input type="password" name="txtPassword" id="password" autocomplete="off"></div>
                </div>

                <div class="row m-0 p-2">
                    <div class="col-md-3 col-sm-4"></div>
                    <div class="col-md-9 col-sm-8"><label for="chkRemember"><input type="checkbox" name="chkRemember" id="chkRemember">Remember Me</label></div>
                </div>
                <div class="row m-0 p-2">
                    <div class="col-md-3 col-sm-4"></div>
                    <div class="col-md-9 col-sm-8">
                        <input class="btn btn-success btn-lg col-6" type="submit" value="Log In">
                        <div><small>Don't yet own an account? <a href="routeRegister.php">Register</a></small></div>
                        <div><small><a href="forgotpass.php">Forgot Password?</a></small></div>
                    </div>
                </div>
                <div><input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
            </form>
        </div>
    </div>
    <div class="col-md-2"></div>
</div>


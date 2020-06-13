<?php 
    require_once 'Core/init.php';

    // if (isset($_POST["loadPicture"])){
    //     $dp=UploadPicture::display();
    // }
    
    // $dpi=UploadPicture::upload();

    //if post which is the input respond form the submit button waited upon
    //if the post is not sent by the submit button, nothing moves further...
    if(Input::exists())
    {
        //crosside research forgery is done here...
        //this stage ensures that a token must exist before any validation occurs
        //imagine a person submits a result just by editing the data in the url search bar
        //this next if ensures only a token must exist after the input is pressed for registration to occur
        $tokenpresent=var_dump(Token::check(Input::get('token')));
        echo $tokenpresent;
        if(print_r(Token::check(Input::get('token'))))
        {
            echo 'Checking for tokens...';
            $validate=new Validate();
            $validation=$validate->check($_POST, array(
                        //validation for the user inputs we've used...
                        'txtUserName'=>array(
                            'required'=>true,
                            'min'=>2,
                            'max'=>20,
                            'unique'=>'tblusers'
                        ),
                        'txtPassword'=>array(
                            'required'=>true,
                            'min'=>6
                        ),
                        'txtDOB'=>array(
                            'required'=>true,
                            'min'=>6
                        ),
                        'txtRePassword'=>array(
                            'required'=>true,
                            'matches'=>'txtPassword'
                        ),
                        'txtAddress'=>array(
                            'required'=>false,
                            'min'=>2,
                            'max'=>150
                        ),
                        'txtEmail'=>array(
                            'required'=>true,
                            'min'=>2,
                            'max'=>100
                        ),
                        'txtName'=>array(
                            'required'=>true,
                            'min'=>2,
                            'max'=>50
                        )
                    )
                );
            if($validation->passed())
            {
                $user=new User();
                $salt=Hash::salt(60);
                        $register=$user->create(
                            1,
                            1,
                            Input::get('txtEmail'),
                            Input::get('txtAddress'),
                            Hash::make(Input::get('txtPassword'), $salt), 
                            Input::get('txtUserName'),
                            date("Y-m-d",strtotime(Input::get('txtDOB'))), 
                            Input::get('txtName'),
                        );
                        if($register){
                            Redirect::to(index.php);
                        }
                        else{
                            echo 'Registration failed';
                            Redirect::to(Includes/errors/failedlogin.php);
                        }
            //the argument passed in the salt needs to be considered in the db aswell.else it wont match if one is longer than the other...
                $salt=Hash::salt(64);
                try 
                {
                    $user->create(
                                array(
                                    'ID'=>ID,
                                    'Name'=>Input::get('txtName'),
                                    'Email'=>Input::get('txtEmail'),
                                    'UserName'=>Input::get('txtUserName'),
                                    'Password'=>Hash::make(Input::get('txtPassword'), $salt),
                                    //'DOB'=>Input::get('txtDOB'),
                                    'Address'=>Input::get('txtAddress'),
                                    'DP'=>UploadPicture::display('imageUpload'),
                                    'Salt'=>$salt,
                                    'DOB'=>date('Y-m-d'),
                                    'GroupID'=>1
                                    )
                                );
                    Session::flash('home',"You registered successfully");
                } 
                catch (Exception $e)
                {
                    die($e->getMessage());
                }
            }
            else
            {
            
                foreach($validation->errors() as $error)
                {
                    echo '<br>', $error;
                }
            }
        }
    }
?>

<!-- 
    ctrl+k+c to comment and ctrl+k+u to uncomment 
-->
<div class="row">
    <div class="col-md-2  col-sm-1"><i class="fa fa-car"></i></div>
    <div class="col-md-8 col-sm-10">
        <div id="box">
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
                <div class="row m-0 p-2">
                    <div class="col-md-4"></div>
                    <div class="col-md-8">
                        <input type="file" class="form-control-file border upload" id="imageUpload"><br>
                        <!-- <button onclick="activateUploadBtn(event)">Upload image</button> -->
                    </div>
                </div>
                <div class="row m-0 p-2">
                    <div class="col-md-4"><label for="txtName"><small><span> Full Name</span></small></label></div>
                    <div class="col-md-8"><input type="text" class="input-cool" name="txtName" value="<?php echo escape(Input::get("txtName")) ?>" id ="txtName" autocomplete="off" ><br></div>
                </div>
                <div class="row m-0 p-2">
                    <div class="col-md-4"><label for="txtDOB"><small>Date of Birth</small></label></div>
                    <div class="col-md-8"><input type="date" class="input-cool" name="txtDOB" id ="txtDOB" autocomplete="off" ><br></div>
                </div>
                <div class="row m-0 p-2 ">
                    <div class="col-md-4"><label for="txtEmail"><small>Email</small></label></div>
                    <div class="col-md-8"><input type="email" class="input-cool" name="txtEmail" id="txtEmail" autocomplete="off"><br></div>
                </div>
                <div class="row m-0 p-2">
                    <div class="col-md-4"><label for="txtUserName"><small>Username</small></label></div>
                    <div class="col-md-8"><input type="text" class="input-cool" name="txtUserName" id ="txtUserName" autocomplete="off"><br></div>
                </div>
                <div class="row m-0 p-2">
                    <div class="col-md-4"><label for="txtAddress"><small>Address</small></label></div>
                    <div class="col-md-8"><input type="textarea" class="input-cool" name="txtAddress" id ="txtAddress" autocomplete="off"><br></div>
                </div>
                
                <div class="row m-0 p-2">
                    <div class="col-md-4"><label for="txtPassword"><small>Password</small></label></div>
                    <div class="col-md-8"><input type="password" class="input-cool" name="txtPassword" id="txtPassword" autocomplete="off"><br></div>
                </div>
                <div class="row m-0 p-2">
                    <div class="col-md-4"><label for="txtRePassword"><small>Confirm Password</small></label></div>
                    <div class="col-md-8"><input type="password" class="input-cool" id="txtRePassword" name="txtRePassword" autocomplete="off"><br></div>
                </div>
                <div class="row m-0 p-2">
                    <div class="col-md-4"></div>
                    <div class="col-md-8">
                        <input type="submit" id="submit" name="submit" value="Register" class="btn btn-success"><br>
                        <input type="hidden" name="token" id="token" value="<?php echo Token::generate(); ?>">
                    </div>
                </div><br>
			</form>
        </div>
    </div>
    <div class="col-md-2 col-sm-1"></div>
</div>
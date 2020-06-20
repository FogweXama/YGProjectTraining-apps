<?php
    class User{
        private $_db, 
                $_data, 
                $_sessionName, 
                $_isLoggedIn, 
                $_cookieName;

        public function __construct($user = null){
            $this->_db=DB::getInstance();
            $this->_sessionName=Config::get('session/session_name');
            $this->_cookieName=Config::get('remember/cookie_name');
            if(!$user){
                if(Session::exists($this->_sessionName)){
                    $user=Session::get($this->_sessionName);
                    if ($this->find($user)) {
                        $this->_isLoggedIn=true;
                    } else {
                        //logout
                    }
        
                }
            }else{
                $this->find($user);
            }
        }
        public function create($fields=array()){
            if(!$this->_db->insert('tblusers', $fields)){
                throw new Exception("There was a problem creating account!");
            }
        }

        public function find($user=null){
            $data = null;
            if($user){
                $field=(is_numeric($user)) ? 'ID': 'UserName';
                $data = $this->_db->get('tblusers', array($field, '=', $user));

                if($data->count()){
                    $this->_data=$data->first();
                    return true;
                }
            }
            return false;
        }

        public function login($username=null, $password=null, $remember=false){
            
            if (!$username && !$password && $this->exists()) {
                    Session::put($this->_sessionName, $this->data()->id);
                } else {
                    $user=$this->find($username);
                    if($user){
                    if($this->data()->password===Hash::make($password, $this->data()->salt)){
                            Session::put($this->_sessionName, $this->data()->id);
    
                            if($remember){
                                $hash=Hash::unique();
                                $hashCheck=$this->_db->get('users_session', array('user_id', '=', $this->data()->id));
                                if(!$hashCheck->count()){
                                    $this->_db->insert('users_session',array(
                                        'user_id'=>$this->data()->id,
                                        'hash'=>$hash
                                    ));
                                }else{
                                    $hash=$hashCheck->first()->hash;
                                }
                                Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));
                            }
                            return true;
                        }
                    }
                }
            return false; 
        }
        public function hasPermission($key){
            $group=$this->_db->get('tblgroup', array('ID', '=',$this->data()->groupID));
            if($group->count()){
                $permission=json_decode($group->first()->permissions, true);
                if($permissions[$key]==true){
                    return true;
                }
                else{
                    return false;
                }
            }
        }
        public function exists(){
            return (!empty($this->_data))?true:false;
        }
        public function logout(){
            $this->_db->delete('tblsession', array('UserID', '=', $this->data()->id));
            Session::delete($this->_sessionName);
            Cookie::delete($this->_cookieName);
        }
        public function data(){
            return $this->_data;
        }
        public function isLoggedIn(){
            return $this->_isLoggedIn; 
        }
        public function update($fields=array()){
            if(!$id && $this->isLoggedIn()){
                $id=$this->data()->id;
            }
            if(!$this->_db->update('tblusers', $id, $fields)){
                throw new Exception("There was a problem updating");
            }
        }
    }
?>
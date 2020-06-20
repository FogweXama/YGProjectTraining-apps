<?php
require_once './Core/init.php';
        class Validate{
            private $_passed=false,
                    $_errors=array(),
                    $_db=null;

            public function __construct(){
                 $this->_db=DB::getInstance();
            }
            //$source is where our data comes from...
            public function check($source, $items=array()){
                
                foreach($items as $item=>$rules){
                    foreach($rules as $rule=>$rule_value){
                        $value=trim($source[$item]);
                        //$item=escape($item);
                        if($rule==='required'&& empty($value)){
                            //$item will carry field name
                            $this->addError("{$item} is required");
                        }
                        else if(!empty($value)){
                            switch($rule){
                                case 'min':
                                    if(strlen($value) < $rule_value){
                                    $this->addError("{$item} must be a minimum of {$rule_value} characters");
                                }
                                break;
                                case 'max':
                                    if(strlen($value) > $rule_value){
                                    $this->addError("{$item} must be a maximum of {$rule_value} characters");
                                }
                                break;
                                case 'matches':
                                    if($value != $source[$rule_value]){
                                    $this->addError("{$rule_value} must match {$item}");
                                }
                                break;
                                case 'unique':
                                    //here is my watchtower and works perfectly well
                                    // echo $rule_value. '<br>';
                                    // echo $value. '<br>';
                                    // echo $item. '<br>';
                                    $vals="UserName";
                                    $check =$this->_db->get($rule_value, array($vals, '=', $value));
                                    
                                    //var_dump($check);
                                    if($check->count()){
                                        $this->addError("{$item} already exists.");
                                    }
                                break;
                            }
                        }
                    }
                }
                if(empty($this->_errors)){
                    $this->_passed=true;
                }
                return $this;
            }
            private function addError($error){
                $this->_errors[]=$error;
            }
            public function errors(){
                return $this->_errors;
            }
            public function passed(){
                return $this->_passed;
            }
            private function delay($delay){
                delay($delay);
                return $delay;
            }
        
    }
?>
<?php
//using a singleton pattern rather than the constructor inorder not to be connecting to the db again and again
class DB{
    private static $_instance =null;
    private $_pdo,//gonno represent the instanciated pdo object
            $_query,//last query executed
            $_error=false,//if there was an error or whether query failed or not
            $_result, //store our result set
            $_count=0;//if there has been any results returned
            //creating our constructor---to be used at instantiation
    private function __construct(){ 
        try{
            $this->_pdo=new PDO('mysql:host='. Config::get('mysql/host'). ';dbname='.Config::get('mysql/db'), Config::get('mysql/username'),Config::get('mysql/password'),array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
        }
        catch(PDOException $e){
            die($e->getMessage());
        }
    }
    public static function getInstance(){
        if(!isset(self::$_instance)){
            self::$_instance=new DB();
        }
        return self::$_instance;
    }
    public function query($sql=null, $params=array()){
        $this->_error=false;//reset error to false to eliminate errors from previous queries
        
            if($this->_query=$this->_pdo->prepare($sql)){
                $x=1;
                if(count($params)){
                    foreach($params as $param){
                        $this->_query->bindValue($x, $param);
                        $x++;
                    }
                    //echo var_dump( $this->_query->bindValue($x, $param));
                }

                if($this->_query->execute()){
                    $this->_result=$this->_query->fetchAll(PDO::FETCH_OBJ);
                    $this->_count=$this->_query->rowCount();
                }else if(!$this->_query->execute()){
                    $this->_error=true;
                    echo '<br>DB.php line 43, erreur query not executing<br>';
                }
            }
            return $this;
        
    }
    public function action($action,$table,$where=array()){
        //making it sure that the array is passed with three elements only!
        if(count($where)===3){
            $operators=array('=','>','<','>=','<=');
            $field=$where[0];
            $operator=$where[1];
            $value=$where[2];

            if(in_array($operator, $operators)){
                $sql="{$action} FROM `{$table}` WHERE `{$field}` {$operator} ?";
                if(!$this->query($sql, array($value))->error()){
                    return $this;
                }
            }
        } 
        return false;
    }
    public function get($table, $where){
        return $this->action('SELECT *', $table, $where);
    }
    public function delete($table, $where){
        return $this->action('DELETE', $table, $where);
    }
    public function insert($table, $fields=array()){
        if(count($fields)){
            $keys=array_keys($fields);
            $values="";
            $x=1;
            foreach($fields as $field){
                $values.= '?';
                //$values.="'".$field."'";
                if($x < count($fields)){
                    $values .= ',';
                }
                $x++;
            }
            $sql="INSERT INTO `{$table}` (`" . implode("`,`", $keys) . "`) VALUES ({$values})";
            //echo $sql;
            if(!$this->query($sql, $fields)->error()){
                echo 'no errors';
                return true;
            }
        }
        return false;
    }
    public function update($table,$id, $fields){
        $set='';
        $x=1;
        foreach($fields as $name=>$value){
            $set.="{$name}=?";
            if($x<count($fields)){
                $set .=', ';
            }
            $x++;
        }
        die($set);
        $sql="UPDATE `{$table}` SET `{$set}` WHERE ID={$id}";
        if(!$this->query($sql, $fields)->error()){
            return true;
        }
        return false;
    }
    public function result(){
        return $this->_result;
    } 
    public function first(){
        return $this->result()[0];
    }
    public function error(){
        return $this->_error;
    }
    public function count(){
        return $this->_count;
    }
}
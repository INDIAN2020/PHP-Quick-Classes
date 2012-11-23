<?php

require_once('initial.conf.php');

class mysqlq {
    
    private $mysql_username = DATABASE_USER;
    private $mysql_password = DATABASE_PASS;
    private $mysql_database = DATABASE_NAME;
    private $mysql_host     = DATABASE_HOST;
    private $aArrayedResults;
    
    function __construct() {
        $this->connect();
    }
    
    public function connect() {
        if(@mysql_connect($this->mysql_host, $this->mysql_username, $this->mysql_password)) {
            if(@mysql_select_db($this->mysql_database)) {
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function disconnect() {
        if($this->mysql_conn_state == true) {
            mysql_close( mysql_close( $this->mysql_host, $this->mysql_username, $this->mysql_password ) );
            $this->mysql_conn_state = false;
        }
    }
    
    public function execute($query) {
        $result = mysql_query($query) or die ( mysql_error() );
        $return = mysql_fetch_assoc($result) or die ( mysql_error() );
        return $return;
    }
    
    public function runInstructionSet($instructions) {
        foreach($instructions as $instruction) {
            if(!$this->run($instruction))
            {
                die(mysql_error());
            }
        }
    }
    
    public function run($query) {
        if(mysql_query($query) or die (mysql_error())) {
            return true;
        }else{
            return false;   
        }
    }
    
    public function cExecute($query) {
        if(mysql_query($query) or die( mysql_error() ) )
        {
            return true;    
        }else{
            return false;
        }
    }
    
    public function aExecute($query){
        $this->aArrayedResults = array();
        $r = mysql_query($query) or die( mysql_error() );
        while ($aData = mysql_fetch_assoc($r)){
            $this->aArrayedResults[] = $aData;
        }
        return $this->aArrayedResults;
    }
    
    public function getLastAIID() {
        return mysql_insert_id();   
    }
    
}

?>
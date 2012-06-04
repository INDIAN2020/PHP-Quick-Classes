<?php

require_once('init.inc.php');

class mysqlq {
    
    private $mysql_username = SQL_USER;
    private $mysql_password = SQL_PASS;
    private $mysql_database = SQL_DABA;
    private $mysql_host     = SQL_HOST;
	private $aArrayedResults;
	private $mysql_conn_state;
    
    function __construct() {
        $this->connect();
    }
    
    public function connect() {
        if( $this->mysql_conn_state == false ) {
            if( mysql_connect($this->mysql_host, $this->mysql_username, $this->mysql_password) /* or die ( mysql_error() ) */) {
                if(mysql_select_db($this->mysql_database) /* or die ( mysql_error() ) */) {
                    $this->mysql_conn_state = true;
                }else{
                    print_r(mysql_error());
                }
            }else{
                print_r(mysql_error());
            }
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
	
	public function run($query) {
	  	mysql_query($query) or die ( mysql_error() );
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
    
}

?>

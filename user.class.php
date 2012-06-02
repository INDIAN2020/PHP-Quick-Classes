<?php

require_once('init.inc.php');

class user {
    
    function __construct() {
        
    }
    
	public function is_logged_in()
	{
		if(!isset($_SESSION[SESSION_NAME]) || empty($_SESSION[SESSION_NAME]))
		{
			return false;
		}else{
			return true;
		}	
	}
	
    public function check_user_exists($username, $password, $password_hash_type = DEF_PASS_ENC) {
        $username = mysql_real_escape_string($username);
        if($password_hash_type != NULL) {
            $password = mysql_real_escape_string(hash($password_hash_type, $password));
        }else{
            $password = mysql_real_escape_string($password);
        }
        $mysql = new mysqlq();
        $request_result = $mysql->execute("SELECT * FROM `".SQL_DABA."`.`".SQL_USER_TABLE_NAME."` WHERE `username` = '{$username}' AND `password` = '{$password}'");
        if(empty($request_result))
        {
            return false;
            unset($mysql);
        }else{
            return true;
            unset($mysql);
        }
    }

    public function check_user_valid($user_hash) {
        $mysql = new mysqlq();
        $r = $mysql->execute("SELECT COUNT(*) FROM ".SQL_USER_TABLE_NAME." WHERE unique_uid = '$user_hash'");
        $r = intval($r['COUNT(*)']);
        if(is_int($r)) {
            if($r == 1) {
                return true;
            }else{
                return false;
            }
        }else{
            die('Result is not an integer, script died.<br />Value: <strong>'.$r.'</strong>');
        }
    }

    public function validate_user_session() {
        if(isset($_SESSION[SESSION_NAME]) && !empty($_SESSION[SESSION_NAME])) {
            if(user::check_user_valid($_SESSION[SESSION_NAME]) == true) {
                $_SESSION[VALID_SESSION_NAME] = 1;
            }else{
                $_SESSION[VALID_SESSION_NAME] = 0;
            }
        }
    }

    public function get_user_id_from_username_password($username, $password) {
        $username = mysql_real_escape_string($username);
        $password = hash(DEF_PASS_ENC, $password);
        $mysql = new mysqlq();
        $r = $mysql->execute("SELECT COUNT(*) FROM ".SQL_USER_TABLE_NAME." WHERE username = '$username' AND password = '$password'");
        $r = intval($r['COUNT(*)']);
        if(is_int($r)) {
            if($r == 1) {
                $r = $mysql->execute("SELECT id FROM ".SQL_USER_TABLE_NAME." WHERE username = '$username' AND password = '$password'");
                $r = intval($r['id']);
                return $r;
            }
        }else{
            die('Result is not an integer, script died.<br />Value: <strong>'.$r.'</strong>');
        }
    }

    public function get_user_id_from_hash($user_hash) {
        $user_id = mysql_real_escape_string($user_hash);
        $mysql = new mysqlq();
        $r = $mysql->execute("SELECT COUNT(*) FROM ".SQL_USER_TABLE_NAME." WHERE unique_uid = '{$user_hash}'");
        $r = intval($r['COUNT(*)']);
        if(is_int($r)) {
            if($r == 1) {
                $r = $mysql->execute("SELECT id FROM ".SQL_USER_TABLE_NAME." WHERE unique_uid = '{$user_hash}'");
                $r = $r['id'];
                return $r;
            }
        }else{
            die('Result is not an integer, script died.<br />Value: <strong>'.$r.'</strong>');
        }
    }
    
    public function get_uniqe_hash($user_id) {
        $user_id = mysql_real_escape_string($user_id);
        $mysql = new mysqlq();
        $r = $mysql->execute("SELECT COUNT(*) FROM ".SQL_USER_TABLE_NAME." WHERE id = $user_id");
        $r = intval($r['COUNT(*)']);
        if(is_int($r)) {
            if($r == 1) {
                $r = $mysql->execute("SELECT unique_uid FROM ".SQL_USER_TABLE_NAME." WHERE id = $user_id");
                $r = $r['unique_uid'];
                return $r;
            }
        }else{
            die('Result is not an integer, script died.<br />Value: <strong>'.$r.'</strong>');
        }
    }
	
	public function get_unique_hash_from_username($user) {
	    $user = mysql_real_escape_string($user);
       	if(!isset($mysql)) { $mysql = new mysqlq(); }
        $r = $mysql->execute("SELECT COUNT(*) FROM ".SQL_USER_TABLE_NAME." WHERE `username` = '$user'");
        $r = intval($r['COUNT(*)']);
		if($r == 1) {
			$r = $mysql->execute("SELECT unique_uid FROM ".SQL_USER_TABLE_NAME." WHERE `username` = '$user'");
			$r = $r['unique_uid'];
			return $r;
		}		
	}

    public function get_user_data($user_hash) {
        $mysql = new mysqlq();
        $data = array();
        $user_hash = trim(str_replace(' ', '', $user_hash));
        $func_num_args = func_num_args();
        $func_get_args = func_get_args();

        if($func_num_args > 1) {
            unset($func_get_args[0]);
            $fields = '`' . implode('`, `', $func_get_args) . '`';
            $data = $mysql->execute("SELECT $fields FROM `".SQL_USER_TABLE_NAME."` WHERE `unique_uid` = '$user_hash'");
            return $data;
        }
    }
}

?>

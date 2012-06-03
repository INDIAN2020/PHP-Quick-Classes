<?php

require_once('mysqlq.class.php');

class setting extends mysqlq {

    function __construct() {
		parent::__construct();
        $this->connect();
    }

    public function check_setting_exists($setting_name) {
		$setting_name = mysql_real_escape_string($setting_name);
		$count_set = mysqlq::execute("SELECT COUNT(*) FROM `settings` WHERE `setting_name` = '".$setting_name."'");
		$count_set = $count_set['COUNT(*)'];
		if($count_set == 1)
		{
			return true;
		}else{
			return false;
		}
	}


    public function get_setting($setting_name, $bool = false) {
		if($bool == false)
		{
			if(setting::check_setting_exists($setting_name))
			{
				$setting_name = mysql_escape_string($setting_name);
				$result = mysqlq::execute("SELECT `setting_value` FROM `settings` WHERE `setting_name` = '".$setting_name."'");
				return $result['setting_value'];
			}else{
				die("<strong>Halted:</strong> Setting $setting_name does not exist.");
			}
		}elseif($bool = true){
			if(setting::check_setting_exists($setting_name))
			{
				$setting_name = mysql_escape_string($setting_name);
				$result = mysqlq::execute("SELECT `setting_value` FROM `settings` WHERE `setting_name` = '".$setting_name."'");
				$result = intval($result['setting_value']);
				if($result == 1)
				{
					return true;
				}elseif($result == 0){
					return false;	
				}
			}else{
				die("<strong>Halted:</strong> Setting $setting_name does not exist.");
			}	
		}
	}	
}

?>

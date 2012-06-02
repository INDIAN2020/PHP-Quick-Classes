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

    public function set_setting($setting_name, $setting_value) {
        
    }

    public function get_setting($entity) {
		if(setting::check_setting_exists($entity))
		{
			$entity = mysql_escape_string($entity);
			$result = mysqlq::execute("SELECT `setting_value` FROM `settings` WHERE `setting_name` = '".$entity."'");
			return $result['setting_value'];
		}else{
			die("<strong>Halted:</strong> Setting $entity does not exist.");
		}
	}	
}

?>

<?php

class versionControl {
	
	function __construct() {
    }
		
	private function repository_exists($url) {
		$curl_session = curl_init($url);
		curl_setopt($curl_session, CURLOPT_NOBODY, true);
		curl_setopt($curl_session, CURLOPT_FOLLOWLOCATION, true);
		curl_exec($curl_session);
		$stat_code = curl_getinfo($curl_session, CURLINFO_HTTP_CODE);
		
		if($stat_code == 200) {
			if(@fopen($url.'/index.php', 'r'))
			{
				$file = file_get_contents($url.'/index.php');
				$file_status = substr($file, 0, 2);
				if($file_status == 'OK')
				{
					return true;
				}else{
					return false;	
				}
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
	private function get_version_from_repository($repository)
	{
		$file = file_get_contents($repository);
		$version = str_replace('OK', '', $file);
		return $version;		
	}
	
	public function check_for_update($app = NULL) {
		
			$mysql = new mysqlq();
			$setting = new setting();
			$reps = $mysql->aExecute("SELECT * FROM `repositories`");
			global $keySet;
			global $versionSet;
			$update_stack = array();
			
			if($app == NULL)
			{
				
				foreach($reps as $repository)
				{
					$addr = 'http://'.$repository['repository_addr'];
					if($this->repository_exists($addr))
					{
						foreach($keySet as $access_key)
						{
							$rep_access = $addr.'?k='.$access_key;
							$app_name = array_keys($keySet, $access_key);
							$app_name = $app_name[0];
							$app_name = $versionSet[$app_name];
							//echo $rep_access.'<br />';
							//echo $this->get_version_from_repository($rep_access).' - '.$repository['repository_name'].' - '.$app_name.'<br />';				
							$rep_version = $this->get_version_from_repository($rep_access);
							$runtime_version = $setting->get_setting($app_name);
							
							//echo '<br/>REP: '; print_r($rep_version);
							//echo '<br/>RUNTIME: '; print_r($runtime_version);
							
							$v_comp = version_compare($rep_version, $runtime_version);
							if($v_comp == 1)
							{
								$update_stack[$app_name] = array('repository_addr' => $addr, 'current_version' => $runtime_version, 'available_version' => $rep_version);
							}
							//echo $app_name . ' ' . $runtime_version . ' - ' . $rep_version . ' - ' . $v_comp . '<br />';
							
						}
					}
					
				}
				
			}else{
				
				if(in_array($app, $versionSet))
				{
					
					
					foreach($reps as $repository)
					{
					
						$addr = 'http://'.$repository['repository_addr'];
						if($this->repository_exists($addr))
						{
						
							$app_key = array_keys($versionSet, $app);
							$app_key = $keySet[$app_key[0]];
							
							$rep_access = $addr.'?k='.$app_key;
							
							$rep_version = $this->get_version_from_repository($rep_access);
							$runtime_version = $setting->get_setting($app);
							
							$v_comp = version_compare($rep_version, $runtime_version);
							if($v_comp == 1)
							{
								$update_stack[$app] = 	array('repository_addr' => $addr, 'current_version' => $runtime_version, 'available_version' => $rep_version);
							}
	
						}
						
					}
				}else{
					die("<strong>Halted:</strong> $app does not exist in the version set");	
				}
			}
			
		return $update_stack;
		
	}
	
}

?>
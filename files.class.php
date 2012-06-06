<?php

require_once('init.inc.php');

class files {

    function __construct() {
        
    }

    public function get_basename($file) {
        $basename = pathinfo($file);
        return $basename['basename'];
    }

    public function get_file_extension($file) {
        $file_ext = pathinfo($file);
        return $file_ext['extension'];
    }
    
    public function get_full_path($file) {
        $path = pathinfo($file);
        return $path['dirname'];
    }
    
    public function get_relative_path($file) {
        $file_path = pathinfo($file);
        $file_path = $file_path['dirname'];
        $file_path = explode(DIR_NAME, $file_path);
        $file_path = $file_path[1];
        $file_path = str_replace(DS, XS, $file_path);
        $file_path = substr($file_path, 1);
        $f_struct = explode(XS, $file_path);
        foreach($f_struct as $f) {
            $file_path = '../' . $file_path;
        }
        return $file_path.'/';
    }
	
	public function convert_bytes($bytes)
    {
        $unit = array('B', 'Kb', 'MB', 'GB', 'TB', 'PB');
        $index = floor(log($bytes)/log(1024));
		      
        return sprintf('%.2f '.$unit[$index], ($bytes/pow(1024, floor($index))));
    }
	
	public function file_size($uri, $array = true, $bytes = true)
	{
		$c_session = curl_init();  
		curl_setopt($c_session, CURLOPT_URL, $uri);  
		curl_setopt($c_session, CURLOPT_NOBODY, 1);  
		curl_setopt($c_session, CURLOPT_HEADER, 0);  
		curl_setopt($c_session, CURLOPT_FILETIME, 1);  
		curl_exec($uh);  
		$filesize = curl_getinfo($c_session, CURLINFO_CONTENT_LENGTH_DOWNLOAD);  
		$modified = curl_getinfo($c_session, CURLINFO_FILETIME);  
		curl_close($uh);  
	  
	  	if($array == true)
		{
			if($bytes == true)
			{
				return array("size" => $filesize, "modified" => $modified);  
			}elseif($bytes == false){
				return array("size" => $this->convert_bytes($filesize), "modified" => $modified);	
			}
		}elseif($array == false){
			if($bytes == true)
			{
				return $filesize;	
			}elseif($bytes == false){
				return $this->convert_bytes($filesize);
			}
		}
	}
}

?>

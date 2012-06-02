<?php

require_once('init.inc.php');

class file {

    function __construct() {
        
    }

    public function extract_basename($file) {
        $basename = pathinfo($file);
        return $basename['basename'];
    }

    public function extract_file_extension($file) {
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
}

?>

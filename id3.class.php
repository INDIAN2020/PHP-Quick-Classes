<?php

class id3 
{
	function __construct() {
		
	}
	
	public function getid3 ($file) { 
		if (file_exists($file)) { 
			$title = '';
			$artist = '';
			$album = '';
			$year = '';
			$comment = '';
			$genre = '';
			$id_start=filesize($file)-128; 
			$fp=fopen($file,"r"); 
			fseek($fp,$id_start); 
			$tag=fread($fp,3); 
			if ($tag == "TAG") {
				$title = fread($fp,30);
				$artist = fread($fp,30);
				$album = fread($fp,30);
				$year = fread($fp,4);
				$comment = fread($fp,30);
				$genre = fread($fp,1);
				$id3 = array(
							'title' => $title,
							'artist' => $artist, 
							'album' => $album, 
							'year' => $year, 
							'comment' => $comment,
							'genre' => $genre
							);
				fclose($fp); 
				return $id3; 
			} else { 
				fclose($fp); 
				return false; 
			} 
		} else { return false; } 
	}
}
?>
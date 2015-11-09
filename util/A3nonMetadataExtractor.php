<?php
/**
 * Extracts Metadata from wordpress plugin file
 * @author Andi Dittrich
 * @version 0.1
 * @license MIT Style X11 License
 */

class A3nonMetadataExtractor{
	
	public static function extract($filename){
		// try to get file content
		$content = file_get_contents($filename);
		
		// comment start position
		$start = strpos($content, "/**");
		$stop = strpos($content, "\n*/", $start);
		
		// extract comment block
		$commentBlock = trim(substr($content, ($start + 3), ($stop-$start-3)));
		
		// split it into ines
		$lines = explode("\n", $commentBlock);
		
		// metadata output buffer
		$metadata = array();
		
		// process lines
		foreach ($lines as $line){
			$line = trim($line);
			
			// param:value pair ?
			$divider = strpos($line, ':');
			if ($divider !== false){
				$param = substr($line, 0, $divider);
				$value = substr($line, $divider+1);
				$metadata[$param] = $value;
			}
		}
		
		// return ass array of metadata param=>value pairs
		return $metadata;
	}	
}
?>
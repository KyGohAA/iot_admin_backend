<?php

namespace App;


class FileIOHelper extends ExtendModel
{
	public static function get_directory_content($directory){
		
		  $listing    = array_slice(scandir($directory), 2);
		  return $listing;

	}

	public static function get_directory_html_files($directory)
	{	
		  $return = array();
		  $listing    = array_slice(scandir($directory), 2);
		  foreach ($listing as $row) {
		  	if (strpos($row, 'html') !== false) 
		  	{
		  		array_push($return, $row);
		  	}
		  	
		  }
		  return $return;

	}
	

	public static function get_all_sub_directory_content($directory,$end_extension=null){
		
		  $listing    = array_slice(scandir($directory), 2);
		  foreach ($listing as $row) {
		  	$temp = static::get_directory_content($directory.'//'.$row); 
		  	/*if{

		  	}else*/
		  	while (false !== ($entry = readdir($handle))) {

		    }
		}
	}

	/**
	 * Copy a file, or recursively copy a folder and its contents
	 * @author      Aidan Lister <aidan@php.net>
	 * @version     1.0.1
	 * @link        http://aidanlister.com/2004/04/recursively-copying-directories-in-php/
	 * @param       string   $source    Source path
	 * @param       string   $dest      Destination path
	 * @param       int      $permissions New folder creation permissions
	 * @return      bool     Returns true on success, false on failure
	 */
	public static function recursive_copy($source, $dest, $permissions = 0755)
	{
	    // Check for symlinks
	    if (is_link($source)) {
	        return symlink(readlink($source), $dest);
	    }

	    // Simple copy for a file
	    if (is_file($source)) {
	        return copy($source, $dest);
	    }

	    // Make destination directory
	    if (!is_dir($dest)) {
	        mkdir($dest, $permissions);
	    }

	    // Loop through the folder
	    $dir = dir($source);
	    while (false !== $entry = $dir->read()) {
	        // Skip pointers
	        if ($entry == '.' || $entry == '..') {
	            continue;
	        }

	        // Deep copy directories
	       static::recursive_copy("$source/$entry", "$dest/$entry", $permissions);
	    }

	    // Clean up
	    $dir->close();
	    return true;
	}

}

 	



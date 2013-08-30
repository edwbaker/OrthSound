#!/usr/bin/php
<?php

/**
 * FILE RENAMING SCRIPT FOR ORTHOPTERA SOUND ARCHIVE
 *
 * ED BAKER AUGUST 2013
 * 
 * Renames files ripped using iTunes using a CSV formatted index file to the collection
 */

//Generate an array from the CSV format index keyed by CD number then track number: $items[cd][track] = array(other data)
$items = array();
$file_handle = fopen('cd_track_data.csv', 'r');
while (($parts = fgetcsv($file_handle)) !== FALSE) {
	$insert = array();
	$count = sizeof($parts);
	for ($i = 2; $i < $count; $i++){
     	 $insert[] = $parts[$i];
	}
	$items[$parts[0]][$parts[1]] = $insert;
}
fclose($file_handle);

//Loop over subdirectories and files
$dirs = scandir('.');
foreach($dirs as $dir){
	//If it is a directory then look inside
	if (is_dir('./'.$dir)){
		//Identify files to change
		$dir_contents = scandir('./'.$dir);
		foreach ($dir_contents as $item) {
			//Check the file names are of the format "XX Track XX" (iTunes default)
  			if (substr($item, 3, 5) == "Track") {
				$track = (int)substr($item,0,2);
   				$data = $items[$dir][$track];
   				//Replacement name has format CD-Track_Species_name_tape_.wav
    			$new_name = str_replace(' ', '_', $dir.'-'.$track.'_'.$data[0].'_'.$data[1].'_.wav');
    			//Rename the file
    			rename('./'.$dir.'/'.$item, './'.$dir.'/'.$new_name);
  			}
		}			
	}
}
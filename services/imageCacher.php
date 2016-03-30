<?php
function getimg($url) {         
    $headers[] = 'Accept: image/gif, image/x-bitmap, image/jpeg, image/pjpeg';              
    $headers[] = 'Connection: Keep-Alive';         
    $headers[] = 'Content-type: application/x-www-form-urlencoded;charset=UTF-8';         
    $user_agent = 'php';         
    $process = curl_init($url);         
    curl_setopt($process, CURLOPT_HTTPHEADER, $headers);         
    curl_setopt($process, CURLOPT_HEADER, 0);         
    curl_setopt($process, CURLOPT_USERAGENT, $user_agent);         
    curl_setopt($process, CURLOPT_TIMEOUT, 30);         
    curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);         
    curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);         
    $return = curl_exec($process);         
    curl_close($process);         
    return $return;   
} 

function cacheImage($image_url, $data_source){
	$stripped_image_url = strtok($image_url, '?'); 
	$imagename= basename($stripped_image_url);
    $image_directory = $_SERVER['DOCUMENT_ROOT'] . '/src/images/' . $data_source . '/';
	if(!file_exists($image_directory . $imagename)){ 
	   $image = getimg($image_url); 
	   file_put_contents($image_directory . $imagename,$image); 
    }
}
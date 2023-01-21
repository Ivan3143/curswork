<?php 

const TOKEN = '5874147301:AAEuJ4kKowxarHWp3VltJDIkCZBsGMyZqxA';

$url ='https://api.telegram.org/bot'  . TOKEN . '/getUpdates';
$lastupdate = 67046660;
$params = [
	'offset' =>  $lastupdate+1
];
$response = json_decode(file_get_contents($url), JSON_OBJECT_AS_ARRAY);


var_dump($response);
?>
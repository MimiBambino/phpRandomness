<?php

$db = new mysqli('localhost', 'root', 'papermate', 'ITeBook');

if($db->connect_errno){

  die('Sorry, we are having some problems.');
}

//if ($db->errno){
//	printf("Unable to connect to the database: <br> %s", $db->errno);
//	exit();
//}

?>
<?php

function dbConn() {
	$host="localhost";
	$user="root";
	$password="root";
	mysql_connect($host,$user,$password);
	$database_name="landit";
	$db=mysql_select_db($database_name);
	if (!$db) {
		return false;
	} else {
		return true;
	}
}

?>
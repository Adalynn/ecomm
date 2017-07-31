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


function getUserDbId($fbid) {
	$conn = dbConn();
	$data = array();
	if ($conn) {
		$sql = "SELECT * FROM users where `fbid` = '" . $fbid . "'";
		$res = mysql_query($sql);
		$num = mysql_num_rows($res);
		if($num) {
			$data['user_exists'] = true;
			$data['response_code'] = 501;
			$data['message'] = "user fb id exists in db";
		} else {
			$data['user_exists'] = false;
			$data['response_code'] = 502;
			$data['message'] = "user fb id does not exists in db";
		}
	} else {
			$data['response_code'] = 500;
			$data['message'] = "unable to connect db";
	}
	return $data;
}


function getUserDataByFbId($fbid) {

	sleep(1);
	$conn = dbConn();
	$data = array();
	if ($conn) {
		$sql = "SELECT * FROM users where `fbid` = '" . $fbid . "'";
		$res = mysql_query($sql);
		$num = mysql_num_rows($res);
		if($num) {
			$row = mysql_fetch_assoc($res);
			$data['data'] = $row;
			$data['response_code'] = 503;
			$data['message'] = "user fb id exists in db";
		} else {
			$data['response_code'] = 502;
			$data['message'] = "user fb id does not exists in db";
		}
	} else {
			$data['response_code'] = 500;
			$data['message'] = "unable to connect db";
	}
	return $data;
}

function getUserDataByDbId($dbid) {

	sleep(1);
	$conn = dbConn();
	$data = array();
	if ($conn) {
		$sql = "SELECT * FROM users where `id` = '" . $dbid . "'";
		$res = mysql_query($sql);
		$num = mysql_num_rows($res);
		if($num) {
			$row = mysql_fetch_assoc($res);
			$data['data'] = $row;
			$data['response_code'] = 504;
			$data['message'] = "user db id exists in db";
		} else {
			$data['response_code'] = 505;
			$data['data'] = $dbid;
			$data['message'] = "user db id does not exists in db";
		}
	} else {
			$data['response_code'] = 500;
			$data['message'] = "unable to connect db";
	}
	return $data;
}

?>
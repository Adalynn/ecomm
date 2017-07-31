<?php
include "functions.php";

if(isset($_REQUEST['action'])) {

	/*
	 * Check if users fbid exists in db or not
	*/
	if($_REQUEST['action'] == "getuserdbid") {
		$user_data = getUserDbId($_REQUEST['fbid']);	
		echo json_encode($user_data);	
	}


	/*
	 * Check if users fbid exists in db or not
	*/
	if($_REQUEST['action'] == "getuserdatabyfbid") {
		$user_data = getUserDataByFbId($_REQUEST['fbid']);	
		echo json_encode($user_data);	
	}

	/*
	 * Get users data in db by dbid
	*/
	if($_REQUEST['action'] == "getuserdatabydbid") {
		$user_data = getUserDataByDbId($_REQUEST['dbid']);	
		echo json_encode($user_data);	
	}


	/*
	 * update user fbid in db on behalf of dbid and prev fbid
	*/
	if($_REQUEST['action'] == "updatefbid") {
		//$user_data = getUserDataByFbId($_REQUEST['fbid']);	
		echo json_encode($_REQUEST);	
	}
}
?>
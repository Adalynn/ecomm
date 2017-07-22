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

	
}
?>
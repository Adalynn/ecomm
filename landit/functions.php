<?php
include "env_functions.php";
function dbConnOld() {
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

function getUserData($by_col, $value) {

	$conn = dbConn();
	$data = array();
	if ($conn) {
		$sql = "SELECT * FROM users where `".$by_col."` = '" . $value . "'";
		$res = mysql_query($sql);
		$num = mysql_num_rows($res);
		if($num) {
			$data['data'] = mysql_fetch_array($res);
			$data['user_exists'] = true;
			$data['response_code'] = 501;
			$data['message'] = "user mobile already registered";
		} else {
			$data['data'] = array();
			$data['user_exists'] = false;
			$data['response_code'] = 502;
			$data['message'] = "user mobile not registered";
		}
	} else {
		$data['response_code'] = 500;
		$data['message'] = "unable to connect db";
	}

	return $data;

}

function addNewUser($r_data) {

	$conn = dbConn();
	$data = array();
	
	if ($conn) {
		$mobile = $r_data['mobile'];

		$user_data = getUserData("mobile", $mobile);
		if($user_data['user_exists']) {
			/*
				Don't enter just update the is_verified and add the verification code
			*/
			$userid = $user_data['data']['id'];
			$sql="UPDATE users set is_verified=0, verification_code='".getVerificationCode()."' where `id`='" . $userid . "'";
			$res = mysql_query($sql);
			if($res) {
				$data = getUserDataByDbId($userid);
				$data['user_updated'] = true;
				$data['user_exists'] = $user_data['user_exists'];
			} else {
				$data['user_id'] = $userid;
				$data['getUserDataByDbId'] = false;
				$data['response_code'] = 501;
				$data['user_exists'] = $user_data['user_exists'];
				$data['message'] = "user update in db query error";
			}
		} else {
			$sql = "INSERT INTO users (`fbid`,`email`,`verification_code`,`mobile`) VALUES ('','','".getVerificationCode()."','".$mobile."')";
			$res = mysql_query($sql);
			if ($res) {
				$data = getUserDataByDbId(mysql_insert_id());
				$data['user_inserted'] = true;
				$data['user_exists'] = $user_data['user_exists'];
			} else {
				$data['user_id'] = 0;
				$data['user_inserted'] = false;
				$data['response_code'] = 501;
				$data['user_exists'] = $user_data['user_exists'];
				$data['message'] = "user inserted in db query error";			
			}
		}

	} else {
		$data['response_code'] = 500;
		$data['message'] = "unable to connect db";
	}

	return $data;
}






function saveUserDataByFbId($fbid) {
	//#fec225
	$conn = dbConn();
	$data = array();
	if ($conn) {
		$sql = "INSERT INTO users (`fbid`,`email`,`mobile`) VALUES ('".$fbid."','','')";
		$res = mysql_query($sql);
		if ($res) {
			$dbid = mysql_insert_id();
			// $data['user_inserted'] = true;
			// $data['response_code'] = 501;
			// $data['message'] = "user inserted in db";
			$data = getUserDataByDbId($dbid);
			$data['user_inserted'] = true;
		} else {
			$data['user_id'] = 0;
			$data['user_inserted'] = false;
			$data['response_code'] = 501;
			$data['message'] = "user inserted in db query error";			
		}
	} else {
			$data['response_code'] = 500;
			$data['message'] = "unable to connect db";
	}
	return $data;
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



function updateUsersIsVerified($r_data) {
	sleep(1);
	$conn = dbConn();
	$data = array();
	if ($conn) {

		$sql="UPDATE users set is_verified=1 where `id`='" . $r_data['dbid'] . "' AND `mobile`='" . $r_data['mobile'] . "'";
		$res = mysql_query($sql);
		if($res) {
			$data['data'] = getUserDataByDbId($r_data['dbid'])['data'];
			$data['response_code'] = 512;
			$data['user_updated'] = true;
			$data['message'] = "verification code updated successfully!";
		} else {
			$data['data'] = $r_data;
			$data['response_code'] = 512;
			$data['user_updated'] = false;
			$data['message'] = "unable to update verification code!";
		}

	} else {
		$data['response_code'] = 500;
		$data['message'] = "unable to connect db";
	}

	return $data;
}

function updateIsVerified($r_data) {
	sleep(1);
	$conn = dbConn();
	$data = array();
	if ($conn) {

		$sql="UPDATE contacts set is_verified=1 where `id`='" . $r_data['cid'] . "' AND `parent_id`='" . $r_data['pid'] . "'";
		$res = mysql_query($sql);
		if($res) {
			$data['data'] = $r_data;
			$data['response_code'] = 512;
			$data['user_updated'] = true;
			$data['message'] = "verification code updated successfully!";
		} else {
			$data['data'] = $r_data;
			$data['response_code'] = 512;
			$data['user_updated'] = false;
			$data['message'] = "unable to update verification code!";
		}

	} else {
		$data['response_code'] = 500;
		$data['message'] = "unable to connect db";
	}

	return $data;
}

function saveUserDataByDbId($r_data) {

	sleep(1);
	$conn = dbConn();
	$data = array();
	if ($conn) {

		$sql="UPDATE users set email='".$r_data['email']."',mobile='".$r_data['mobile']."' where `id`='" . $r_data['dbid'] . "'";
		$res = mysql_query($sql);
		if($res) {
			$data['data'] = $r_data;
			$data['response_code'] = 506;
			$data['user_updated'] = true;
			$data['message'] = "user data updated successfully!";
		} else {
			$data['data'] = $r_data;
			$data['response_code'] = 507;
			$data['user_updated'] = false;
			$data['message'] = "unable to update user data!";
		}

	} else {
		$data['response_code'] = 500;
		$data['message'] = "unable to connect db";
	}

	return $data;
}

function getUserContactsByDbId($r_data) {

    sleep(1);
    $conn = dbConn();
    $data = array();
    if ($conn) {

        //$sql="UPDATE users set email='".$r_data['email']."',mobile='".$r_data['mobile']."' where `id`='" . $r_data['dbid'] . "'";
        //$sql="SELECT * FROM contacts where `parent_id`='" . $r_data['dbid'] . "'";
        $sql="SELECT * FROM contacts where `parent_id`='" . $r_data['dbid'] . "' order by id desc";
        $res = mysql_query($sql);
        $num = mysql_num_rows($res);
        if($num) {

            $user_contacts = array();
            while ($row=mysql_fetch_array($res)) {
                $user_contact = array();
                $user_contact['contact_id'] = $row['id'];
                $user_contact['parent_id'] = $row['parent_id'];
                $user_contact['contact_name'] = $row['contact_name'];
                $user_contact['contact_number'] = $row['contact_number'];
                $user_contact['is_verified'] = $row['is_verified'];
                $user_contact['added_on'] = $row['added_on'];
                $user_contact['latitude'] = $row['latitude'];
                $user_contact['longitude'] = $row['longitude'];
                $user_contact['other_info'] = $row['other_info'];
                $user_contact['verification_code'] = $row['verification_code'];
                array_push($user_contacts, $user_contact);
            }
            $data['data'] = $user_contacts;
            $data['response_code'] = 508;
            $data['total_contacts'] = $num;
            $data['message'] = "user contacts found";
        } else {
            $data['user_exists'] = false;
            $data['response_code'] = 509;
            $data['total_contacts'] = $num;
            $data['message'] = "user contacts not found";
        }

    } else {
        $data['response_code'] = 500;
        $data['message'] = "unable to connect db";
    }

    return $data;
}

function getVerificationCode() {

	$conn = dbConn();

	$unique_users_codes = getDistictVerificationCodes('users', 'verification_code');
	$unique_contacts_codes = getDistictVerificationCodes('contacts', 'verification_code');

	$reserved_codes = array_unique(array_merge($unique_users_codes,$unique_contacts_codes));

	return createVerificationCode($reserved_codes, 4);
	

	//return md5($unique_str);
}

function createVerificationCode($ignored_values, $digits) {

	$random_code = rand(pow(10, $digits-1), pow(10, $digits)-1);
	if(!in_array($random_code, $ignored_values)) {
		return $random_code;
	} else {
		createVerificationCode($ignored_values, $digits);
	}
}

function getDistictVerificationCodes($table , $field) {

	$conn = dbConn();
	$unique_codes = array();
	$sql="SELECT DISTINCT(`".$field."`) FROM `".$table."`";
    $res = mysql_query($sql);
    while($row = mysql_fetch_assoc($res)) {
		if(!empty($row['verification_code'])) {
		       	array_push($unique_codes, $row['verification_code']);        		        			
		}
    }
    return $unique_codes;
}


function addUserContacts($r_data) {

    sleep(1);
    $conn = dbConn();
    $data = array();
    if ($conn) {

        $sql="SELECT * FROM contacts where `parent_id`='".$r_data['dbid']."' AND `contact_number`='". $r_data['contact_number'] ."'";
        $res = mysql_query($sql);
        $num = mysql_num_rows($res);
        if($num) {
			$row = mysql_fetch_assoc($res);
			$data['contact_id'] = $row['id'];
        	$data['contact_added'] = false;
            $data['contact_number'] = $r_data['contact_number'];
            $data['response_code'] = 510;
            $data['message'] = "user contacts already exists";
            $data['verification_code'] = 0;
        } else {
        	// User does not exists we can insert now
        	$verification_code = getVerificationCode();
			$sql = "INSERT INTO contacts (`parent_id`,`contact_number`,`contact_name`,`other_info`,`verification_code`) VALUES ('".$r_data['dbid']."','".$r_data['contact_number']."','".$r_data['contact_name']."','','".$verification_code."')";
			$res = mysql_query($sql);
			if ($res) {
				$dbid = mysql_insert_id();
				$data['contact_id'] = $dbid;				
				$data['contact_added'] = true;
            	$data['contact_number'] = $r_data['contact_number'];
				$data['response_code'] = 511;
				$data['message'] = "user contacts added";
				$data['verification_code'] = $verification_code;
			} else {
				$data['contact_id'] = 0;
				$data['contact_added'] = false;
            	$data['contact_number'] = $r_data['contact_number'];
				$data['response_code'] = 501;
				$data['message'] = "user inserted in db query error";
				$data['verification_code'] = 0;		
			}
        }

    } else {
        $data['response_code'] = 500;
        $data['message'] = "unable to connect db";
    }

    return $data;
}

?>
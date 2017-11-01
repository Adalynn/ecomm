<?php
	$data = array();
	$data['email'] = 'adsadasdsadsadsa@gmail.com';
	$data['mobile'] = 'qweqweeqeq';
	$data['dbid'] = 'asdadasdasda';

	$sql= "UPDATE user_manual set email='".$data['email']."', mobile='".$data['mobile']."' where `id` = '" . $data['dbid'] . "'";
	echo $sql;
?>
<?php


function insertUsersBatch($data, $mysqli) {

	for($i = 0; $i < count($data); $i++) {
		if(empty($data[$i])) {
			continue;
		}
		$query = "INSERT INTO user(user_name, user_eid, user_password, user_salt, user_role, create_date,delete_flag)";
		$query .= " VALUES('".$data[$i]['user_name']."', '".$data[$i]['user_eid']."', '".$data[$i]['user_password']."', '".$data[$i]['user_salt']."', '".$data[$i]['user_role']."', '".$data[$i]['create_date']."', '".$data[$i]['delete_flag']."')";
		$stmt = $mysqli->prepare($query);
		if(!$stmt -> execute()) {
			return false;
		}
	}
	
	return true;
}
?>

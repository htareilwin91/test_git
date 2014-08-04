<?php
function getUserList($mysqli) {
	$query = "SELECT * FROM user";

	if ($stmt = $mysqli -> query($query)) {
		if ($stmt -> num_rows > 0) {
			while ($result = $stmt -> fetch_assoc()) {
				$data[] = $result;
			}
		} else {
			$data = "";
		}
	}
	return $data;
}

function insert_data($array, $mysqli) {
	$query = "INSERT INTO user(user_name,user_eid,user_password,user_salt,email,phone,position,department,address,user_role,create_date) ";
	$query .= " VALUES('" . $array['name'] . "', '" . $array['userid'] . "', '" . $array['new_pass'] . "', '" . $array['salt'] . "', ";
	$query .= "'" . $array['email'] . "', '" . $array['phoneno'] . "', '" . $array['position'] . "', ";
	$query .= "'" . $array['dep_name'] . "', '" . $array['address'] . "', '" . $array['role'] . "', NOW() ";
	$query .= ")";
	$insert_row = $mysqli -> query($query);

	if ($insert_row) {
		// print 'Success! ID of last inserted record is : ' .$mysqli->insert_id .'<br />';
		header('Location:user_list.php');
	} else {
		die('Error : (' . $mysqli -> errno . ') ' . $mysqli -> error);
	}
}

function getdetail($edit_id, $mysqli) {
	$query = "SELECT * FROM user WHERE user_id=$edit_id";

	if ($stmt = $mysqli -> query($query)) {
		if ($stmt -> num_rows > 0) {
			while ($result = $stmt -> fetch_assoc()) {
				$data[] = $result;
			}
		} else {
			$data = "";
		}
	}
	return $data;
}

function delete_user($delete_id, $mysqli) {
	$query = "DELETE FROM user WHERE user_id=$delete_id";
	$delete_row = $mysqli -> query($query);
	if ($delete_row) {
		header('Location:user_list.php');
	} else {
		die('Error : (' . $mysqli -> errno . ') ' . $mysqli -> error);
	}

}

function update_user($editarray, $mysqli) {

	$query = "UPDATE `user` SET user_name = '" . $editarray['username'] . "', department='" . $editarray['deptname'] . "', user_role='" . $editarray['role'] . "'";
	$query .= ", email='" . $editarray['email'] . "', position='" . $editarray['position'] . "', phone='" . $editarray['phoneno'] . "', address='" . $editarray['address'] . "'";
	$query .= " WHERE user_id = " . $editarray['u_id'];

	$update_row = $mysqli -> query($query);

	if ($update_row) {
		header('Location:user_list.php');
	} else {
		die('Error : (' . $mysqli -> errno . ') ' . $mysqli -> error);
	}
}

function getprofile($userid, $mysqli) {
	$query = "SELECT * FROM user WHERE user_eid='" . $userid . "'";
	if ($stmt = $mysqli -> query($query)) {
		if ($stmt -> num_rows > 0) {
			while ($result = $stmt -> fetch_assoc()) {
				$data[] = $result;
			}
		} else {
			$data = "";
		}
	}
	return $data;
}

function updateprofile($proarray, $mysqli) {
	$id = $_SESSION['sess_user_eid'];
	if ($proarray['password'] == 0) {
		$query = "UPDATE `user` SET user_name = '" . $proarray['username'] . "', department='" . $proarray['deptname'] . "'";
		$query .= ", position='" . $proarray['position'] . "', phone='" . $proarray['phoneno'] . "', address='" . $proarray['address'] . "'";
		$query .= " WHERE user_eid ='" . $id . "'";
		//echo $query;
		$update_row = $mysqli -> query($query);

		if ($update_row) {
			header('Location:home.php?msg=2');
		} else {
			die('Error : (' . $mysqli -> errno . ') ' . $mysqli -> error);
		}
	} else {
		$query = "UPDATE `user` SET user_name = '" . $proarray['username'] . "', department='" . $proarray['deptname'] . "', user_password='" . $proarray['password'] . "'";
		$query .= ", user_salt='" . $proarray['salt'] . "', position='" . $proarray['position'] . "', phone='" . $proarray['phoneno'] . "', address='" . $proarray['address'] . "'";
		$query .= " WHERE user_eid ='" . $id . "'";
		echo $query;
		$update_row = $mysqli -> query($query);

		if ($update_row) {
			header('Location:home.php?msg=2');
		} else {
			die('Error : (' . $mysqli -> errno . ') ' . $mysqli -> error);
		}
	}

}
function getusermail($usermail,$mysqli) {
	//echo $usermail;
	$query = "SELECT * FROM user WHERE email='".$usermail."'";

	if ($stmt = $mysqli -> query($query)) {
		if ($stmt -> num_rows > 0) {
			while ($result = $stmt -> fetch_assoc()) {
				$data[] = $result;
			}
		} else {
			$data = "";
		}
	}
	return $data;
}


function update_psw($newpsw,$salt,$hemail,$mysqli) {
	//echo $newpsw."<br>".$salt;
	//echo $hemail;
	
		$query = "UPDATE `user` SET user_password = '" . $newpsw . "', user_salt='" . $salt . "'";
		$query .= " WHERE email ='" . $hemail . "'";
		echo $query;
		$update_row = $mysqli -> query($query);

		if ($update_row) {
			header('Location:home.php');
		} else {
			die('Error : (' . $mysqli -> errno . ') ' . $mysqli -> error);
		}
	
}
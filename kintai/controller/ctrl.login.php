<?php
include_once ("../lib/ini.setting.php");
include_once ("ini.config.php");
include_once ("ini.dbstring.php");
include_once ("ini.functions.php");
include_once ("mod.login.php");

sec_session_start();

if (isset($_GET['cmd']) && $_GET['cmd'] == logout) {
	logout();
	header("location: ../index.php");
	exit ;
} else {
	$usereid = $_POST['username'];
	$password = $_POST['password'];
	$password = hash("sha256", $password);
	$stmt = login($db, $usereid);
	var_dump($stmt);
	if ($stmt -> num_rows == 0) {
		header('Location: ../index.php?err=1');
	} else {
		while ($result = $stmt -> fetch_assoc()) {
			define("MAX_LENGTH", 6);
			$intermediateSalt = md5(uniqid(rand(), true));
			$salt = substr($intermediateSalt, 0, MAX_LENGTH);
			$hash = hash("sha256", $password . $result['user_salt']);
			if ($hash != $result['user_password'])// Incorrect password. So, redirect to login_form again.
			{
				header('Location: ../index.php?err=1');
			} else {
				// Redirect to home page after successful login.
				session_regenerate_id();
				$_SESSION['sess_user_id'] = $result['user_id'];
				$_SESSION['sess_username'] = $result['user_name'];
				$_SESSION['sess_user_eid'] = $result['user_eid'];
				if ($result['user_role'] == "0") {
			
					$_SESSION['sess_user_role'] = $result['user_role'];
					$_SESSION['sess_user_eid'] = $result['user_eid'];
					header('Location: ../admin/user_list.php');
				} else {
				
					$_SESSION['sess_user_role'] = $result['user_role'];
					$_SESSION['sess_user_eid'] = $result['user_eid'];
					header('Location: ../home.php');
				}
				session_write_close();
			}
		}
	}
}

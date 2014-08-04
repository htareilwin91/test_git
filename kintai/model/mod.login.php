<?php
function logout() {
	$_SESSION = array();
	$params = session_get_cookie_params();
	setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
	session_destroy();
	return true;
}

function login($mysqli, $username) {
	$query = "SELECT user_id, user_eid, user_name, user_password, user_salt, user_role FROM user WHERE user_eid = '$username'";
	if ($stmt = $mysqli -> query($query)) {
		return $stmt;
	}
}
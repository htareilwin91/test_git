<?php
include_once ("mod.admin.php");
if (isset($_POST['submit'])) {
	$password = $_POST['password'];
	$password = hash("sha256", $password);
	//echo $password;
	define("MAX_LENGTH", 6);
	$intermediateSalt = md5(uniqid(rand(), true));
	$salt = substr($intermediateSalt, 0, MAX_LENGTH);
	$new_pass = hash("sha256", $password . $salt);
	//echo $new_pass;

	$name = $_POST['username'];
	$dep_name = $_POST['deptname'];
	$email = $_POST['email'];
	$position = $_POST['position'];
	$phoneno = $_POST['phoneno'];
	$address = $_POST['address'];
	$role = $_POST['role'];
	$branch = $_POST['branch'];
	echo $name.$dep_name.$email.$position.$phoneno.$address.$role.$branch;
	if ($branch != '-1') {
		$generate_id = uniqid(rand());
		if ($branch == 0) {
			$id_generate = "JP" . substr($generate_id, -6);
			$id_generate=strtoupper($id_generate);
		} elseif ($branch == 1) {
			$id_generate = "CH" . substr($generate_id, -6);
			$id_generate=strtoupper($id_generate);
		} elseif ($branch == 2) {
			$id_generate = "VN" . substr($generate_id, -6);
			$id_generate=strtoupper($id_generate);
		} elseif ($branch == 3) {
			$id_generate = "MM" . substr($generate_id, -6);
			$id_generate=strtoupper($id_generate);
		}

		$array['name'] = $name;
		$array['userid'] = $id_generate;
		$array['dep_name'] = $dep_name;
		$array['email'] = $email;
		$array['position'] = $position;
		$array['phoneno'] = $phoneno;
		$array['phoneno'] = $phoneno;
		$array['address'] = $address;
		$array['role'] = $role;
		$array['new_pass'] = $new_pass;
		$array['salt'] = $salt;

		//insert_data($array, $db);
	}
}

$showuser = getuserlist($db);

if ($_SERVER['REQUEST_URI'] == "/kintai/user_list.php") {

} else if (isset($_GET['id'])) {
	$edit_id = $_GET['id']; ;
	$user_detail = getdetail($edit_id, $db);

} else if (isset($_GET['del_id'])) {
	$delete_id = $_GET['del_id'];
	delete_user($delete_id, $db);
}

if (isset($_POST['edit'])) {
	$username = $_POST['username'];
	$deptname = $_POST['deptname'];
	$role = $_POST['role'];
	$email = $_POST['email'];
	$position = $_POST['position'];
	$phoneno = $_POST['phoneno'];
	$address = $_POST['address'];
	$u_id = $_POST['u_id'];
	$array['username'] = $username;
	$array['deptname'] = $deptname;
	$array['role'] = $role;
	$array['email'] = $email;
	$array['position'] = $position;
	$array['phoneno'] = $phoneno;
	$array['address'] = $address;
	$array['u_id'] = $u_id;
	update_user($array, $db);
}

$profile_data = getprofile($_SESSION['sess_user_eid'], $db);

if (isset($_POST['profile_edit'])) {
	$username = $_POST['username'];
	$deptname = $_POST['deptname'];
	$password = $_POST['new_pass'];
	$password = hash("sha256", $password);
	define("MAX_LENGTH", 6);
	$intermediateSalt = md5(uniqid(rand(), true));
	$salt = substr($intermediateSalt, 0, MAX_LENGTH);
	$new_pass = hash("sha256", $password . $salt);

	$email = $_POST['email'];
	$position = $_POST['position'];
	$phoneno = $_POST['phoneno'];
	$address = $_POST['address'];

	$array['username'] = $username;
	$array['deptname'] = $deptname;
	$array['password'] = $new_pass;
	$array['salt'] = $salt;
	$array['email'] = $email;
	$array['position'] = $position;
	$array['phoneno'] = $phoneno;
	$array['address'] = $address;
	updateprofile($array, $db);
}

if(isset($_POST['add']))
{
	$user_email=$_POST['email'];
	$_SESSION['sess_user_email']=$_POST['email'];

	$result=getusermail($user_email,$db);
	foreach ($result as $row) {
		 $psw=$row['user_password'];
		 $seed = str_split($psw);
			shuffle($seed);
			$rand = '';
			foreach (array_rand($seed, 8) as $k)
			$rand .= $seed[$k];

			}
			$to=$user_email;
					$subject="Security Code";
					$message= "You can access our site by using the following code:"."<br>";
					$message.=$rand."<br>";
					$message.='<a href="http://atu-japan.co.jp/kintai/password_reset.php?e='.$_SESSION['sess_user_email'].'">Click here to change your password</a>';

					ini_set("SMTP","localhost");
					ini_set("sendmail_from","info@saj.ir");
					$headers= 'MIME-Version: 1.0' . "\r\n";
					$headers.= 'Content-type: text/html; charset=utf8' . "\r\n";
					$headers .= 'From: Rubbersoul' . "\r\n";
					if(mail($to,"password reset",$message,$headers))
					{
						echo '<script type="text/javascript">
						alert("Your security code has been sent to your email!");
					window.location.href= "index.php";
							</script>';
					}
}
//echo $_SERVER['REQUEST_URI'];
/*if(isset($_GET['e']))
{
$result=getusermail($_GET['e'],$db);
foreach ($result as $row) {
	$pass=$row['user_password'];
	}
}*/

if(isset($_POST['savepsw']))
{
	$hidden= $_POST['hidden'];
	$newpsw=$_POST['newpsw'];
	$password = hash("sha256", $newpsw);
	define("MAX_LENGTH", 6);
	$intermediateSalt = md5(uniqid(rand(), true));
	$salt = substr($intermediateSalt, 0, MAX_LENGTH);
	$new_pass = hash("sha256", $password . $salt);
	update_psw($new_pass,$salt,$hidden,$db);
}
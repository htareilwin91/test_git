<?php
include_once ("lib/ini.setting.php");
include_once ("ini.config.php");
include_once ("ini.dbstring.php");
include_once ("ini.functions.php");

sec_session_start();

if(!$_SESSION['sess_user_id'] || $_SESSION['sess_user_role']=="0"){
header('Location: index.php');
}

include_once ("mod.calendar.php");
include_once ("mod.attendance.php");
include_once ("mod.admin.php");
include_once ("ctrl.checklogin.php");
include_once ("ctrl.calendar.php");
include_once ("ctrl.attendance.php");
include_once ("ctrl.admin.php");

// If the form was submitted, scrub the input (server-side validation)
// see below in the html for the client-side validation using jQuery
$name = '';
$userid = '';
$deptname = '';
$role = '';
$address = '';
$email = '';
$position = '';
$password = '';
$phoneno = '';
$output = '';

if ($_POST) {
	// collect all input and trim to remove leading and trailing whitespaces
	$name = trim($_POST['username']);
	$deptname = trim($_POST['deptname']);
	$address = trim($_POST['address']);
	$email = trim($_POST['email']);
	$position = trim($_POST['position']);
	$phoneno = trim($_POST['phoneno']);
	$errors = array();

	// Validate the input
	if (strlen($name) == 0)
		array_push($errors, "Please enter your name");

	if (strlen($address) == 0)
		array_push($errors, "Please specify your address");

	if (!filter_var($email, FILTER_VALIDATE_EMAIL))
		array_push($errors, "Please specify a valid email address");

	if (strlen($position) == 0)
		array_push($errors, "Please enter your position");

	if (strlen($phoneno) == 0)
		array_push($errors, "Please enter your phone");

	if (count($errors) == 0) {
		array_push($errors, "No Errors! Form Sumbitted Successfully!.. Thanks!");
	}
	//Prepare errors for output
	$output = '';
	foreach ($errors as $val) {
		$output .= "<div class='output'>$val</div>";
	}
}
?>

<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title>Kinntai system</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="Form Validation Using jQuery and PHP" />
		<meta name="keywords" content="jQuery, HTML, PHP, Form Validation, Ajax Form Validation" />
		<meta name="author" content="DesignHuntR" />
		<link href="<?php echo CSS; ?>/import.css" rel="stylesheet" type="text/css"/>
		<script src="<?php echo JS; ?>/jquery.min.js"></script>
		<script src="<?php echo JS; ?>/jquery.validate.min.js"></script>
	</head>
	<body>
		<!--  The form that will be parsed by jQuery before submit  -->
		<?php
		include ('header.php');
		?>
		<div class="bd_content">
			<?php
			include ('left_menu.php');
			?>
			<div class="dat_content">
				<div class="container">
					<form action="#" method="post" id="register-form" novalidate="novalidate" >
						<?php foreach ($profile_data as $query_data) { ?>
						<table cellpadding="5px" width="900px">
							<tr>
								<td width="200px">User Name</td>
								<td>
								<input type="text" id='username' name="username" value="<?php echo $query_data['user_name']; ?>">
								</td>
							</tr>
							<tr>
								<td>Dept_Name</td>
								<td>
								<input type="text" id="deptname" name="deptname" value="<?php echo $query_data['department']; ?>">
								</td>
							</tr>

							<tr>
								<td>Email</td>
								<td>
								<input type="text" id="email" name="email" value="<?php echo $query_data['email']; ?>">
								</td>
							</tr>
							<tr>
								<td>Change Password</td>
								<td>
								<input type="password" id="old_pass" name="old_pass" width='' value="<?php $psw = $query_data['user_password'];
									$seed = str_split($psw);
									shuffle($seed);
									$rand = '';
									foreach (array_rand($seed, 8) as $k)
										$rand .= $seed[$k];

									echo $rand;
								?>" disabled>
								<br>
								<input type="password" name='new_pass' placeholder=' Tye new password' id="new_pass">
								</td>
							</tr>
							<tr>
								<td>Position</td>
								<td>
								<input type="text" id="position" name="position" value="<?php echo $query_data['position']; ?>">
								</td>
							</tr>
							<tr>
								<td>Phone No</td>
								<td>
								<input type="text" id="phoneno" name="phoneno" value="<?php echo $query_data['phone']; ?>">
								</td>
							</tr>
							<tr>
								<td>Address</td>
								<td><textarea rows="5" cols="29" name="address"><?php echo $query_data['address']; ?></textarea></td>
							</tr>
							<tr>
								<td></td>
								<td>
								<input type="submit" value="Update" name='profile_edit' class="button">
								</td>
							</tr>
						</table>
						<?php } ?>
					</form>
				</div>

			</div>
			<?php
			include_once ('right_menu.php');
			include_once ("scripts.php");
			?>
		</div>
		<!-- jQuery Form Validation code -->
	</body>
</html>
<?php
include_once ("../lib/ini.setting.php");
include_once ("ini.config.php");
include_once ("ini.dbstring.php");
include_once ("ini.functions.php");

sec_session_start();
if(!$_SESSION['sess_user_id'] || $_SESSION['sess_user_role']=="1"){
header('Location: ../index.php');
}
$todaydate = explode("-", date("Y-n-j-D"));
$userid = $_SESSION['sess_user_id'];
$show = true;

include_once ("mod.admin.php");
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
	//  $userid = trim($_POST['userid']);
	$deptname = trim($_POST['deptname']);
	$role = trim($_POST['role']);
	//echo $role;
	$address = trim($_POST['address']);
	$email = trim($_POST['email']);
	$position = trim($_POST['position']);

	$phoneno = trim($_POST['phoneno']);

	$errors = array();

	// Validate the input
	if (strlen($name) == 0)
		array_push($errors, "Please enter your name");

	if (strlen($deptname) == 0)
		array_push($errors, "Please enter your ID");
	if (strlen($role) == 0)
		array_push($errors, "Please enter your ID");

	if (strlen($address) == 0)
		array_push($errors, "Please specify your address");

	if (!filter_var($email, FILTER_VALIDATE_EMAIL))
		array_push($errors, "Please specify a valid email address");

	if (strlen($position) == 0)
		array_push($errors, "Please enter your position");

	if (strlen($phoneno) == 0)
		array_push($errors, "Please enter your phone");

	// If no errors were found, proceed with storing the user input
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
	<meta charset="UTF-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Form Validation Using jQuery and PHP"/>
	<meta name="keywords" content="jQuery, HTML, PHP, Form Validation, Ajax Form Validation"/>
	<meta name="author" content="DesignHuntR"/>
	<link rel="stylesheet" type="text/css" href="style.css"/>
	<link href="<?php echo CSS; ?>/import.css" rel="stylesheet" type="text/css"/>
	<title>Kinntai system</title>
	<script src="<?php echo JS; ?>/jquery.min.js"></script>
	<script src="<?php echo JS; ?>/jquery.validate.min.js"></script>
</head>
<body>
<?php include('header_admin.php'); ?>
<div class="bd_content">
	<?php include_once ('left_menu_admin.php'); ?>
	<div class="dat_content">
		<div class="container">
			<form action="#" method="post" id="edit-form" novalidate="novalidate">
				<?php foreach ($user_detail as $query_data) { ?>
					<table cellpadding="5px" width="900px">
						<tr>
							<td width="200px">User Name</td>
							<td>
								<input type="text" id='username' name="username"
								       value="<?php echo $query_data['user_name']; ?>">
							</td>
						</tr>
						<tr>
							<td>Dept_Name</td>
							<td>
								<input type="text" id="deptname" name="deptname"
								       value="<?php echo $query_data['department']; ?>">
							</td>
						</tr>
						<tr>
							<td>User role</td>
							<td>
								<select style="width:250px;" id='role' name="role">
									<?php $role = $query_data['user_name'];
									if ($role == 0) {
										echo '<option value="0">admin</option>';
										echo '<option value="1">user</option>';
									} else {
										echo '<option value="1">user</option>';
										echo '<option value="0">admin</option>';
									}
									?>
								</select>
							</td>
						</tr>
						<tr>
							<td>Email</td>
							<td>
								<input type="text" id="email" name="email" value="<?php echo $query_data['email']; ?>">
							</td>
						</tr>
						<tr>
							<td>Position</td>
							<td>
								<input type="text" id="position" name="position"
								       value="<?php echo $query_data['position']; ?>">
							</td>
						</tr>
						<tr>
							<td>Phone No</td>
							<td>
								<input type="text" id="phoneno" name="phoneno"
								       value="<?php echo $query_data['phone']; ?>">
							</td>
						</tr>
						<tr>
							<td>Address</td>
							<td><textarea rows="5" cols="28"
							              name="address"><?php echo $query_data['address']; ?></textarea></td>
						</tr>
						<tr>
							<td>
								<input type="hidden" id="u_id" name="u_id"
								       value="<?php echo $query_data['user_id']; ?>">
							</td>
							<td>
								<input type="submit" value="Save" id="edit" name="edit" class="btn_type">
							</td>
						</tr>
					</table>
				<?php } ?>
			</form>
		</div>
	</div>
	<?php include_once("right_menu_admin.php"); ?>
</div>
<?php include_once("scripts.php"); ?>
<script>
	$(function() {
		// Setup form validation on the #register-form element
		$("#register-form").validate({
			// Specify the validation rules
			rules : {
				username : "required",

				deptname : "required",
				address : "required",

				email : {
					required : true,
					email : true
				},

				position : "required",
				phoneno : {
					required : true,

					number : true
				},
			},
			// Specify the validation error messages
			messages : {
				username : "Please enter your name",

				deptname : "Please specify your department name",
				address : "Please enter your address",

				email : "Please enter a valid email address",

				position : "Please enter position",
				phoneno : {
					required : "Please enter phone number",
				},
			},
			submitHandler : function(form) {
				form.submit();
			}
		});
	});
</script>
</body>
</html>

<?php
include_once("../lib/ini.setting.php");
include_once("ini.config.php");
include_once("ini.dbstring.php");
include_once("ini.functions.php");

sec_session_start();
$todaydate = explode("-", date("Y-n-j"));
$userid = $_SESSION['sess_user_id'];
$show = true;

include_once("mod.admin.php");
include_once("ctrl.admin.php");

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
	$role = trim($_POST['role']);
	$branch = trim($_POST['branch']);
	$address = trim($_POST['address']);
	$email = trim($_POST['email']);
	$position = trim($_POST['position']);
	$password = trim($_POST['password']);
	$phoneno = trim($_POST['phoneno']);

	$errors = array();

	// Validate the input
	if (strlen($name) == 0)
		array_push($errors, "Please enter your name");

	if ($branch == '')
		array_push($errors, "Please enter the branch");
	if (strlen($deptname) == 0)
		array_push($errors, "Please enter your ID");
	if ($role == '')
		array_push($errors, "Please enter your ID");

	if (strlen($address) == 0)
		array_push($errors, "Please specify your address");

	if (!filter_var($email, FILTER_VALIDATE_EMAIL))
		array_push($errors, "Please specify a valid email address");

	if (strlen($position) == 0)
		array_push($errors, "Please enter your position");

	if (strlen($phoneno) == 0)
		array_push($errors, "Please enter your phone");
	if (strlen($password) < 5)
		array_push($errors, "Please enter a password. Passwords must contain at least 5 characters.");

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
	<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title>Kinntai system</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="Form Validation Using jQuery and PHP" />
		<meta name="keywords" content="jQuery, HTML, PHP, Form Validation, Ajax Form Validation" />
		<meta name="author" content="DesignHuntR" />
		<link rel="stylesheet"  type="text/css" href="style.css" />
		<link href="<?php echo CSS; ?>/import.css" rel="stylesheet" type="text/css"/>
		<link href="<?php echo CSS; ?>/screen.css" rel="stylesheet" type="text/css"/>
		<script src="<?php echo JS; ?>/jquery.min.js"></script>
		<script src="<?php echo JS; ?>/jquery.validate.min.js"></script>
	<style type="text/css">
			#register-form label.error {
				color: #FF5959;
				font-weight: bold;
				padding-left: 10px;
				font-size: 15px;
			}
		</style>
</head>
<body>
<?php
include('header_admin.php');
?>
<div class="bd_content">
	<?php include_once('left_menu_admin.php'); ?>
	<div class="dat_content">
		<div class="container">
			<?php echo $output; ?>
			<form action="#" method="post" id="register-form" novalidate="novalidate">
				<table cellpadding="5" width="900">
					<tr>
						<td width="200">ユーザー名</td>
						<td>
							<input type="text" id='username' name="username">
						</td>
					</tr>
					<tr>
						<td>部門</td>
						<td>
							<input type="text" id="deptname" name="deptname">
						</td>
					</tr>
					<tr>
						<td>レベル</td>
						<td>
							<select id='role' name="role" class="required">
								<option value="0">admin</option>
								<option value="1">user</option>
							</select></td>
					</tr>
					<tr>
						<td>会社ブランチ</td>
						<td>
							<select id='branch' name="branch" class="required">
								<option value="0">日本</option>
								<option value="1">中国</option>
								<option value="2">ベトナム</option>
								<option value="3">ミャンマー</option>
							</select></td>
					</tr>
					<tr>
						<td>メール</td>
						<td>
							<input type="text" id="email" name="email">
						</td>
					</tr>
					<tr>
						<td>パスワード</td>
						<td>
							<input type="password" id="password" name="password">
						</td>
					</tr>
					<tr>
						<td>役職</td>
						<td>
							<input type="text" id="position" name="position">
						</td>
					</tr>
					<tr>
						<td>形態番号</td>
						<td>
							<input type="text" id="phoneno" name="phoneno">
						</td>
					</tr>
					<tr>
						<td>住所</td>
						<td><textarea rows="5" cols="29" name="address"></textarea></td>
					</tr>
					<tr>
						<td colspan="2">
							<input type="submit" value="Create" name='submit' class="button" style="margin-left:210px;">
						</td>
					</tr>
				</table>
			</form>
		</div>
	</div>
	<?php include_once("right_menu_admin.php"); ?>
</div>
<!-- jQuery Form Validation code -->
<?php include_once ('scripts.php'); ?>
<script>
	$(function() {
		// Setup form validation on the #register-form element
		$("#register-form").validate({
			// Specify the validation rules
			
			rules : {
				username : "required",
				deptname : "required",
				address : "required",
				role: {
            	required:true
				},
				branch: {
					required:true
				},
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
				role: {
                required: "Please select the role"
				},
				 branch: {
					required: "Please select the branch"
				},
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
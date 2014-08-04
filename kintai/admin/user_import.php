<?php
include_once ("../lib/ini.setting.php");
include_once ("ini.config.php");
include_once ("ini.dbstring.php");
include_once ("ini.functions.php");

sec_session_start();
if(!$_SESSION['sess_user_id'] || $_SESSION['sess_user_role']=="1"){
header('Location: index.php');
}
$todaydate = explode("-", date("Y-n-j-D"));
$userid = $_SESSION['sess_user_id'];
$show = true;

include_once ("mod.admin.php");
include_once ("ctrl.admin.php");

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
	
	<script src="<?php echo JS; ?>/jquery.js"></script>
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
					<p class="hdr">取り込み</p>
				<form action="<?php echo CTRL; ?>ctrl.user.php" method="post" enctype="multipart/form-data">
					<input type="file" name="uploadFile" />
					<input type="submit" value="Import" class="btn-mod" />
                    <input type="hidden" name="cmd" value="import" />
			
				</form>
				<?php 
			
				if($_SESSION['cmd']['err']=="success"){
				$_SESSION['cmd']['err']="";
				echo "<span style='color:red;'>Successfully imported</span>";
				echo $_SESSION['cmd']['err']="";
				}elseif($_SESSION['cmd']['err']=="error"){
				$_SESSION['cmd']['err']="";
				echo "<span style='color:red;'>Please check your file</span>";
	
			}
			
				?>
				
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

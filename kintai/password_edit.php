<?php
include_once ("lib/ini.setting.php");
include_once ("ini.config.php");
include_once ("lib/ini.dbstring.php");
include_once ("ctrl.admin.php");
error_reporting(0);

//Report runtime errors
error_reporting(E_ERROR | E_WARNING | E_PARSE);

//Report all errors
error_reporting(E_ALL);
?>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
		<link href="<?php echo CSS; ?>/base.css" rel="stylesheet" type="text/css"/>
		<link href="<?php echo CSS; ?>/content.css" rel="stylesheet" type="text/css"/>
		<link href="<?php echo CSS; ?>/login.css" rel="stylesheet" type="text/css"/>
		<title>Password edit</title>
	</head>
	<body>
		<div id="psw_edit">
			<form action="#" method="post" id="form1">
			<h2 class="p_edit">Get a New Password</h2>
			Enter the information below and we'll send you an email with the next steps to get a new password.<br><br>
				Type your email address :
				<br>
				<input type="text" id="email" name="email" class="login_field">
				<br><br>
				
				<input type="submit" name="add" id="add" value="Submit"  class="button"/>
				
			</form>
		</div>
	</body>
</html>
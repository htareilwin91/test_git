<?php
include_once ("lib/ini.setting.php");
include_once ("ini.config.php");
include_once("ini.functions.php");
include_once ("ctrl.checklogin.php");
include_once ("ini.dbstring.php");
//sec_session_start();

include_once ("mod.select.php");
include_once ("ctrl.checklogin.php");
include_once ("mod.attendance.php");
include_once ("ctrl.attendance.php");

sec_session_start();
if(!$_SESSION['sess_user_id'] || $_SESSION['sess_user_role']=="0"){
header('Location: index.php');
}

$userid = (!isset($_GET['userid']) || $_GET['userid'] == "")?1:$_GET['userid'];
$todaydate = explode("-", date("Y-n-j"));

$currenttime = date("H:i:s", time());
$currentdate = date("Y-n-j");
?>

<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Kinntai system</title>
		<link href="<?php echo CSS; ?>/import.css" rel="stylesheet" type="text/css"/>
		<script src="<?php echo JS; ?>/jquery.js"></script>
		<?php echo $rfc; ?>
	</head>
	<body>

		<?php
		include ('header.php');
		?>
		<div class="bd_content">
			<?php include ('left_menu.php'); ?>
			<div class="dat_content">
				<div class="container" style="text-align:center;">
					<span style='font-size:1200%;line-height:100%;'><?php echo $currenttime; ?></span><br/>
					<h2>コメント</h2>
					<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
						<textarea name="attd_comment" style="width:100%;height:200px;"></textarea>
						
						<input type="hidden" name="attd_out_time" value="<?php echo $currenttime; ?>" />
						<input type="hidden" name="attd_date" value="<?php echo $currentdate; ?>" />
						<input type="hidden" name="userid" value="<?php echo $_SESSION['sess_user_id']; ?>">
						
						<input type="hidden" name="form_submit_out" value="true" />
						<input class="button" style="margin-top:10px;" type="submit" value="退勤する">
					</form>
				</div>
			</div>
			<?php
			include_once ('right_menu.php');
			include_once ("scripts.php");
			?>
		</div>
	</body>
</html>
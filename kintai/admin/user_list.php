<?php
include_once("../lib/ini.setting.php");
include_once("ini.config.php");
include_once("ini.dbstring.php");
include_once("ini.functions.php");

sec_session_start();
if(!$_SESSION['sess_user_id'] || $_SESSION['sess_user_role']=="1"){
header('Location: ../index.php');
}
$todaydate = explode("-", date("Y-n-j-D"));
$userid = $_SESSION['sess_user_id'];
$show = true;

include_once("mod.admin.php");
include_once("ctrl.admin.php");

?>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Kinntai system</title>
	<link href="<?php echo CSS; ?>/import.css" rel="stylesheet" type="text/css"/>
	<script src="<?php echo JS; ?>/jquery.min.js"></script>
</head>
<body>
<?php include_once('header_admin.php'); ?>
<div class="bd_content">
	<?php include('left_menu_admin.php'); ?>
	<div class="dat_content">
		<div class="container">
			<div class="data_tbl">
				<div class="tblCalendarCtn">
					<table class="tbl_str">
						<tr>
							<th>Id</th>
							<th>Employer ID</th>
							<th>Name</th>
							<th>Email</th>
							<th>address</th>
							<th>position</th>
							<th>department</th>
							<th></th>
						</tr>
						<?php for ($r = 0; $r < count($showuser); $r++) { ?>
							<tr>
								<td><?php echo $r + 1; ?></td>
								<td><?php echo $showuser[$r]["user_eid"]; ?></td>
								<td><?php echo $showuser[$r]["user_name"]; ?></td>
								<td><?php echo $showuser[$r]["email"]; ?></td>
								<td><?php echo $showuser[$r]["address"]; ?></td>
								<td><?php echo $showuser[$r]["position"]; ?></td>
								<td><?php echo $showuser[$r]["department"]; ?></td>
								<td>
									<?php $id = $showuser[$r]["user_id"]; ?>
									<a href="user_edit.php?id=<?php echo $id; ?>">Edit </a>|
									<a href="user_list.php?del_id=<?php echo $id; ?>">Delete</a>
								</td>
							</tr>
						<?php } ?>
						<tr>
							<th colspan="8" height="20"></th>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
include_once("right_menu_admin.php");
include_once("scripts.php");
?>
</body>
</html>
<?php
$todaydate = explode("-", date("Y-n-j-D"));
$userid = $_SESSION['sess_user_id'];
//echo $userid;
$late = getLateCheckIn($userid, $todaydate, $db);
$earlyleave = getEarlyCheckOut($userid, $todaydate, $db);
$absent = getAbsent($userid, $todaydate, $db);

$checkInTime = getCheckInTime($userid, $todaydate, $db);
$checkOutTime = getCheckOutTime($userid, $todaydate, $db);
?>
<link href="<?php echo CSS; ?>/import.css" rel="stylesheet" type="text/css"/>
<script src="<?php echo JS; ?>/jquery.min.js"></script>
<div id='top_banner'>	
	<div class="logoutCtn">
		<a href="controller/ctrl.login.php?cmd=logout">Logout</a>
	</div>
	<div class="userinfoCtn">
		<a href="<?php echo ROOT . "profile_edit.php"; ?>"> <?php echo strtoupper($_SESSION['sess_username']); ?> [ IDBB977 ]</a>
	</div>
	<div class="dateinfoCtn">
		<?php echo $todaydate[0] . "年 " . $todaydate[1] . "月 " . $todaydate[2] . "日 " . "（" . $todaydate[3] . "）"; ?>
	</div>
</div>


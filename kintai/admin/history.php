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
include_once ("mod.attendance.php");
include_once ("ctrl.checklogin.php");
include_once ("ctrl.admin.php");
include_once ("ctrl.attendance.php");

$late = getLateCheckIn($userid, $todaydate, $db);
$earlyleave = getEarlyCheckOut($userid, $todaydate, $db);
$absent = getAbsent($userid, $todaydate, $db);
?>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Kinntai system</title>
		<link href="<?php echo CSS; ?>/import.css" rel="stylesheet" type="text/css"/>
		<script src="<?php echo JS; ?>/jquery.min.js"></script>
	</head>
	<body>
		<?php include ('header_admin.php'); ?>
		<div class="bd_content">
			<?php include ('left_menu_admin.php'); ?>
			<div class="dat_content">
				<div class="container">
					<div class="search_bar">
						<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
							<span id='filter'>
								<select class="button2" name="year">
									<option value="2014">2014</option>
									<option value="2015">2015</option>
									<option value="2015">2016</option>
								</select> 年
								<select class="button2" name="month">
									<?php
										for($i = 1; $i <= count($ymd); $i++) {
											echo "<option value='" . $ymd[$i-1]['mindex'] . "'>" . strtoupper($ymd[$i-1]['mnameshort']) . "</option>";
										}
									?>
								</select> 月
								<input type="hidden" name="filterAdmin" value="true">
								<input class="button" type="submit" value="検索" name="btn_search" id="btn_search">
							</span>
						</form>
					</div>

					<div class="data_tbl">
						<?php
						if ($show) {
							if (isset($my) && $my != "" && !empty($my)) {
								echo '<div class="tblCalendarCtn">';
								echo '<table class="tbl_str">';
								echo '<tr>';
								echo '<th align="left">ユーザー名</th>';
								echo '<th align="left">Position</th>';
								echo '<th align="left">メール</th>';
								echo '<th align="left">詳細</th>';
								echo '</tr>';

								$userlist = getUserList($db);
								$d = $my[0] . "-" . $my[1] . "-" . $my[2];

								for ($u = 0; $u < count($userlist); $u++) {
									echo "<tr>";
									echo "<td>" . $userlist[$u]['user_name'] . "</td>";
									echo "<td>" . $userlist[$u]['position'] . "</td>";
									echo "<td>" . $userlist[$u]['email'] . "</td>";
									echo "<td><a href='#' url='detail.php?uid=" . $userlist[$u]['user_id'] . "&cdate=" . $d . "' class='popup'>詳細</a></td>";
									echo "</tr>";
								}
								echo '<tr>';
								echo '<th colspan="9" height="20"></th>';
								echo '</tr>';
								echo '</table>';
								echo '</div>';
							}
						} else {
							echo "<div class='info'><span class='nodata'>No data</span></div>";;
						}
						?>
					</div>
				</div>
			</div>
			<?php
			include_once ("right_menu_admin.php");
			include_once ("scripts.php");
			?>
		</div>
	</body>
</html>
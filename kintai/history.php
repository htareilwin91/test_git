<?php
include_once ("lib/ini.setting.php");
include_once ("ini.config.php");
include_once ("ini.dbstring.php");
include_once ("ini.functions.php");

sec_session_start();

if(!$_SESSION['sess_user_id'] || $_SESSION['sess_user_role']=="0"){
header('Location: index.php');
}
$userid = $_SESSION['sess_user_id'];
$todaydate = explode("-", date("Y-n-j"));
$show = false;

include_once ("mod.calendar.php");
include_once ("mod.attendance.php");
include_once ("ctrl.checklogin.php");
include_once ("ctrl.calendar.php");
include_once ("ctrl.attendance.php");


if (!isset($_POST['filter']) && $_POST['filter'] != "true" && $_POST["filter"] == "") {
	$getCurrentMonth = getCurrentMonth($todaydate, $userid, $db);
	$filter_late = getLateCheckIn($userid, $todaydate, $db);
	$filter_earlyleave = getEarlyCheckOut($userid, $todaydate, $db);
	$filter_absent = getAbsent($userid, $todaydate, $db);
}
?>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Kinntai system</title>
		<link href="<?php echo CSS; ?>/import.css" rel="stylesheet" type="text/css"/>
		<script src="<?php echo JS; ?>/jquery.js"></script>
		<script type="text/javascript">
			function PrintDiv() {
				var divToPrint = document.getElementById('divToPrint');
				var popupWin = window.open('', '_blank', 'width=300,height=300');
				popupWin.document.open();
				popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
				popupWin.document.close();
			}
		</script>
	</head>
	<body>
		<?php
		include ('header.php');
		?>
		<div class="bd_content">
			<?php
			include_once ('left_menu.php');
			?>
			<div class="dat_content">
				<div class="container">
					<div class="search_bar">
						<div class="search_form">
							<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
								<select class="button2" name="year" id="name">
									<option value="2014">2014</option>
									<option value="2015">2015</option>
									<option value="2015">2016</option>
								</select>
								年
								<select class="button2" name="month" id="month">
									<option value="1">JAN</option>
									<option value="2">FEB</option>
									<option value="3">MAR</option>
									<option value="4">APR</option>
									<option value="5">MAY</option>
									<option value="6">JUN</option>
									<option value="7">JUL</option>
									<option value="8">AUG</option>
									<option value="9">SEP</option>
									<option value="10">OCT</option>
									<option value="11">NOV</option>
									<option value="12">DEC</option>
								</select>
								月
								<input type="hidden" name="filter" value="true">
								<input class="button" type="submit" value="検索" name="btn_search" id="btn_search">
							</form>
						</div>
						<?php if($show) {
						?>
						<ul class="dasu">
							<li>
								<a href="" class="button" onclick="PrintDiv();">印刷</a>
							</li>
							<li>
								<a href="search_mail.php" class="button">メール</a>
							</li>
						</ul>
						<?php } ?>
					</div>

					<div class="data_tbl" id="divToPrint">
					<?php if($show) { ?>
						<div class="info">
							<table>
								<tr>
									<td><span class="if"><?php echo $filter_late; ?></span><span class="it">遅刻</span></td>
									<td><span class="if"><?php echo $filter_earlyleave; ?></span><span class="it">早退</span></td>
									<td><span class="if"><?php echo $filter_absent; ?></span><span class="it">欠勤</span></td>
								</tr>
							</table>
						</div>
						<div class="tblCalendarCtn">
							<table id="attd" class="tbl_str">
								<tr>
									<th class="left">日付</th>
									<th class="left">曜日</th>
									<th class="right">出社時間</th>
									<th class="right">遅刻</th>
									<th class="right">退社時間</th>
									<th class="right">早退</th>
									<th class="right">作業時間</th>
									<th class="right">残業時間</th>
									<th class="right">統計時間</th>
								</tr>
								<?php
								for ($r = 0; $r < count($getCurrentMonth); $r++) {
									// extract only current logged in user's data
									$overtime = "";
									$worktime = "";
									$totaltime = "";
									$intime = "";
									$outtime = "";
									$latetime = "";
									$in = date("H:i", strtotime($getCurrentMonth[$r]["attd_in_time"]));
									$out = date("H:i", strtotime($getCurrentMonth[$r]["attd_out_time"]));
									if ($getCurrentMonth[$r]["attd_in_time"] != "") {
										$intime = date("H:i", strtotime($getCurrentMonth[$r]["attd_in_time"]));
									} else {
										$intime = "-";
									}
					
									if ($getCurrentMonth[$r]["attd_out_time"] != "") {
										$outtime = date("H:i", strtotime($getCurrentMonth[$r]["attd_out_time"]));
									} else {
										$outtime = "-";
									}
					
									if ($getCurrentMonth[$r]["calendar_status"] == 0 && $getCurrentMonth[$r]["calendar_day"] == "sun") {
										$class = "offSun";
									} else if ($getCurrentMonth[$r]["calendar_status"] == 0 && $getCurrentMonth[$r]["calendar_day"] == "sat") {
										$class = "offSat";
									} else if ($getCurrentMonth[$r]["calendar_status"] == 0) {
										$class = "offHoliday";
									} else {
										$class = "";
									}
					
									// calculate worktime
									if ($getCurrentMonth[$r]["attd_out_time"] != "" && $getCurrentMonth[$r]["attd_in_time"] != "") {
										list($hours, $minutes) = explode(':', $in);
										$startTimestamp = mktime($hours, $minutes);
					
										list($hours, $minutes) = explode(':', $out);
										$endTimestamp = mktime($hours, $minutes);
					
										$seconds = $endTimestamp - $startTimestamp;
										$minutes = ($seconds / 60) % 60;
										$hours = round($seconds / (60 * 60));
										$worktime = $hours . ":" . $minutes;
									} else {
										$worktime = "-";
									}
					
									// calculate overtime
									if ($getCurrentMonth[$r]["attd_out_time"] != "" && strtotime($getCurrentMonth[$r]["attd_out_time"]) > strtotime("18:30")) {
										$limit_ot = "18:30";
										list($hours, $minutes) = explode(':', $out);
										$startTimestamp = mktime($hours, $minutes);
					
										list($hours, $minutes) = explode(':', $limit_ot);
										$endTimestamp = mktime($hours, $minutes);
					
										$seconds = $startTimestamp - $endTimestamp;
										$minutes = ($seconds / 60) % 60;
										$hours = round($seconds / (60 * 60));
										$overtime = $hours . ":" . $minutes;
									} else {
										$overtime = "-";
									}
					
									// calculate late time
									if ($getCurrentMonth[$r]["attd_in_time"] != "" && strtotime($getCurrentMonth[$r]["attd_in_time"]) >= strtotime("09:30")) {
										$limit = "09:30";
										list($hours, $minutes) = explode(':', $in);
										$startTimestamp = mktime($hours, $minutes);
					
										list($hours, $minutes) = explode(':', $limit);
										$endTimestamp = mktime($hours, $minutes);
					
										$seconds = $startTimestamp - $endTimestamp;
										$minutes = ($seconds / 60) % 60;
										$hours = round($seconds / (60 * 60));
										$latetime = $hours . ":" . $minutes;
									} else {
										$latetime = "-";
									}

									// calculate totaltime
									if ($worktime != "-" && $overtime != "-") {
										$worktimecal = strtotime($worktime);
										$overtimecal = strtotime($overtime);
										$min = date("i", $overtimecal);
										$sec = date("s", $overtimecal);
										$hr = date("H", $overtimecal);

										$convert = strtotime("+$min minutes", $worktimecal);
										$convert = strtotime("+$hr hours", $convert);

										$totaltime = date("H:i", $convert);
									} else {
										$totaltime = "-";
									}

									// calculate early time
									if ($getCurrentMonth[$r]["attd_out_time"] != "" && strtotime($getCurrentMonth[$r]["attd_out_time"]) < strtotime("18:30")) {
										$limit="18:30:00";
										list($hours, $minutes,$sec) = explode(':', $out);
										$startTimestamp = mktime($hours, $minutes,$sec);

										list($hours, $minutes,$sec) = explode(':', $limit);
										$endTimestamp = mktime($hours, $minutes,$sec);

										$seconds = $endTimestamp - $startTimestamp;
										$sec=$seconds % 60;
										$minutes = ($seconds / 60) % 60;
										$hours = round($seconds / (60 * 60));
										//echo "Time passed: <b>$hours</b> hours and <b>$minutes</b> minutes<br>";
										//end test
										$earlytime=$hours.":".$minutes.":".$sec;
									} else {
										$earlytime = "-";
									}

									echo "<tr class='" . $class . "'>";
									echo "<td class='left'>" . substr($getCurrentMonth[$r]["calendar_date"], -2) . "</td>";
									echo "<td class='left'>" . strtoupper($getCurrentMonth[$r]["calendar_day"]) . "</td>";
									echo "<td class='right'>" . $intime . "</td>";
									echo "<td class='right'><span class='late'>" . $latetime . "</span></td>";
									echo "<td class='right'>" . $outtime . "</td>";
									echo "<td class='right'><span class='early'>" . $earlytime . "</span></td>";
									echo "<td class='right'>" . $worktime . "</td>";
									echo "<td class='right'>" . $overtime . "</td>";
									echo "<td class='right'>" . $totaltime . "</td>";
									echo "</tr>";
								}
								?>
								<tr>
									<th colspan="9" height="20"></th>
								</tr>
							</table>
						</div>
						<?php }else { echo "<div class='info'><span class='nodata'>No data</span></div>"; } ?>
					</div>
				</div>
			</div>
			<?php
			include_once ("right_menu.php");
			include_once ("scripts.php");
			?>
		</div>
	</body>
</html>

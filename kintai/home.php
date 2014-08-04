<?php
include_once ("lib/ini.setting.php");
include_once ("ini.config.php");
include_once ("lib/ini.functions.php");
include_once ("ini.dbstring.php");

include_once ("mod.select.php");
include_once ("mod.calendar.php");
include_once ("mod.attendance.php");
include_once ("ctrl.checklogin.php");
include_once ("ctrl.calendar.php");
include_once ("ctrl.attendance.php");

sec_session_start();
if(!$_SESSION['sess_user_id'] || $_SESSION['sess_user_role']=="0"){
header('Location: index.php');
}
//$userid = (!isset($_GET['userid']) || $_GET['userid'] == "")?1:$_GET['userid'];
$userid=$_SESSION['sess_user_id'];

$todaydate = explode("-", date("Y-n-j"));
$getCurrentMonth = getCurrentMonth($todaydate, $userid, $db);
?>

<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Kintai system</title>
		<link href="<?php echo CSS; ?>/import.css" rel="stylesheet" type="text/css" media="screen"/>
		<link href="css/print.css" rel="stylesheet" type="text/css" media="screen"/>
		
		<script src="<?php echo JS; ?>/jquery.js"></script>
		   
	 	<style type="text/css" media="print">
	.noprint
        {
		display:none;
	}
</style>
<style type="text/css" media="screen">
	.header
        {
		width:800px;
        height:200px;
	
	}
</style>

	</head>
	<body>
	<?php
		if($_GET['msg']=="1")
		{
			echo "<script>alert('Mail has been sent');</script>";
		}
		elseif ($_GET['msg']=="2") {
			echo "<script>alert('Your profile has been changed.');</script>";
		}
	?>
		<?php include ('header.php'); ?>
		<div class="bd_content">
			<?php
			include ('left_menu.php');
			?>
			<div class="dat_content">
				<div class="container">
					<div class="search_bar">
						<ul class="dasu noprint">
							<li>
						
								<a class="button noprint" href="javascript:window.print();" onclick="PrintDiv();">印刷</a>
							
							</li>
							<li>
								<a class="button" href="mail_test.php">メール</a>
							</li>
						</ul>
					</div>
					<div class="info noprint">
						<table class="noprint">
							<tr>
								<td><span class="if"><?php echo $late; ?></span><span class="it">遅刻</span></td>
								<td><span class="if"><?php echo $earlyleave; ?></span><span class="it">早退</span></td>
								<td><span class="if"><?php echo $absent; ?></span><span class="it">欠勤</span></td>
							</tr>
						</table>
					</div>
					<div class="tblCalendarCtn" id="divToPrint">
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
								$in=date("H:i:s", strtotime($getCurrentMonth[$r]["attd_in_time"]));
								$out=date("H:i:s", strtotime($getCurrentMonth[$r]["attd_out_time"]));
								if ($getCurrentMonth[$r]["attd_in_time"] != "") {
									$intime = date("H:i:s", strtotime($getCurrentMonth[$r]["attd_in_time"]));
								} else {
									$intime = "-";
								}
								
								if ($getCurrentMonth[$r]["attd_out_time"] != "") {
									$outtime = date("H:i:s", strtotime($getCurrentMonth[$r]["attd_out_time"]));
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
									list($hours, $minutes,$sec) = explode(':', $in); 
									$startTimestamp = mktime($hours, $minutes,$sec); 
									 
									list($hours, $minutes,$sec) = explode(':', $out); 
									$endTimestamp = mktime($hours, $minutes,$sec); 
									
									$seconds = $endTimestamp - $startTimestamp;
									$sec = $seconds % 60; 
									$minutes = ($seconds / 60) % 60; 
									$hours = round($seconds / (60 * 60)); 

									$worktime=$hours.":".$minutes.":".$sec;
								} else {
									$worktime = "-";
								}

								// calculate overtime
								if ($getCurrentMonth[$r]["attd_out_time"] != "" && strtotime($getCurrentMonth[$r]["attd_out_time"]) > strtotime("18:30")) {
									$limit_ot="18:30";
									list($hours, $minutes, $sec) = explode(':', $out); 
									$startTimestamp = mktime($hours, $minutes,$sec); 
									 
									list($hours, $minutes,$sec) = explode(':', $limit_ot); 
									$endTimestamp = mktime($hours, $minutes,$sec); 
									
									$seconds = $startTimestamp - $endTimestamp; 
									$sec=$seconds % 60; 
									$minutes = ($seconds / 60) % 60; 
									$hours = round($seconds / (60 * 60)); 

									$overtime=$hours.":".$minutes;
								} else {
									$overtime = "-";
								}
								
								// calculate late time
								if ($getCurrentMonth[$r]["attd_in_time"] != "" && strtotime($getCurrentMonth[$r]["attd_in_time"]) >= strtotime("09:30")) {
									$limit="09:30:00";
									list($hours, $minutes,$sec) = explode(':', $in); 
									$startTimestamp = mktime($hours, $minutes,$sec); 
									 
									list($hours, $minutes,$sec) = explode(':', $limit); 
									$endTimestamp = mktime($hours, $minutes,$sec); 
									
									$seconds = $startTimestamp - $endTimestamp; 
									$sec = $seconds% 60;
									$minutes = ($seconds / 60) % 60; 
									$hours = round($seconds / (60 * 60)); 
									$latetime=$hours.":".$minutes.":".$sec;
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
						</table>
					</div>
				</div>
			</div>
            <?php
            include_once ('right_menu.php');
            include_once ("scripts.php");
            ?>
		</div>

	</body>
</html>

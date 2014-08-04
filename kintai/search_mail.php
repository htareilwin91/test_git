<?php
include_once ("lib/ini.setting.php");
include_once ("ini.config.php");
include_once ("lib/ini.functions.php");
include_once ("ini.dbstring.php");

include_once ("mod.select.php");
include_once ("mod.calendar.php");
include_once ("mod.attendance.php");
include_once ("ctrl.calendar.php");
include_once ("ctrl.attendance.php");
sec_session_start();
$yr['0']=$_SESSION['year'];
$yr['1']=$_SESSION['month'];
//echo $yr['1'];

$to=$_SESSION['sess_user_id'];
$rsesult=getmail($to,$db);
foreach ($rsesult as $row) {
	$tosend=$row['email'];
	//echo $tosend;

}
$getname=getusername($_SESSION['sess_user_id'],$db);
foreach ($getname as $result) {
						$fname=$result['user_name'];
						$eid=$result['user_eid'];
						//echo $eid;
					}
					$getCurrentMonth = getCurrentMonth($yr,$_SESSION['sess_user_id'], $db);	
					$body='<html>
							<head><title></title>
							<style>
								td{text-align:center; border-bottom: 1px dotted #514F4F;}
							</style>
							</head>
								<body>';
					$body.='User Name: '.$fname.'<br>';
					$body.='User Id: '.$eid.'<br>';
					$body.='<table style="width:800px">
									<tr>
										<th style="border-top: 1px solid #514F4F;border-bottom: 1px solid #514F4F;background: #514F4F;color: #E1E1E1;">日付</th>
										<th style="border-top: 1px solid #514F4F;border-bottom: 1px solid #514F4F;background: #514F4F;color: #E1E1E1;">曜日</th>
										<th style="border-top: 1px solid #514F4F;border-bottom: 1px solid #514F4F;background: #514F4F;color: #E1E1E1;">出社時間</th>
										<th style="border-top: 1px solid #514F4F;border-bottom: 1px solid #514F4F;background: #514F4F;color: #E1E1E1;">遅刻</th>
										<th style="border-top: 1px solid #514F4F;border-bottom: 1px solid #514F4F;background: #514F4F;color: #E1E1E1;">退社時間</th>
										<th style="border-top: 1px solid #514F4F;border-bottom: 1px solid #514F4F;background: #514F4F;color: #E1E1E1;">作業時間</th>
										<th style="border-top: 1px solid #514F4F;border-bottom: 1px solid #514F4F;background: #514F4F;color: #E1E1E1;">残業時間</th>
										<th style="border-top: 1px solid #514F4F;border-bottom: 1px solid #514F4F;background: #514F4F;color: #E1E1E1;">統計時間</th>
									</tr>';
								
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
									//$worktimediff = date_diff(date_create($getCurrentMonth[$r]["attd_out_time"]), date_create($getCurrentMonth[$r]["attd_in_time"]));
									//$worktime = $worktimediff -> format("%H:%I");
									
									//$worktime=$out-$in;
									//$worktime=date('h:i',$worktime);
									//test
									list($hours, $minutes,$sec) = split(':', $in); 
									$startTimestamp = mktime($hours, $minutes,$sec); 
									 
									list($hours, $minutes,$sec) = split(':', $out); 
									$endTimestamp = mktime($hours, $minutes,$sec); 
									
									$seconds = $endTimestamp - $startTimestamp; 
									$sec=$seconds % 60; 
									$minutes = ($seconds / 60) % 60; 
									$hours = round($seconds / (60 * 60)); 
									//echo "Time passed: <b>$hours</b> hours and <b>$minutes</b> minutes<br>"; 
									//end test
									$worktime=$hours.":".$minutes.":".$sec;
								} else {
									$worktime = "-";
								}

								// calculate overtime
								if ($getCurrentMonth[$r]["attd_out_time"] != "" && strtotime($getCurrentMonth[$r]["attd_out_time"]) > strtotime("18:30")) {
									//$overtimediff = date_diff(date_create($getCurrentMonth[$r]["attd_out_time"]), date_create("18:30"));
									//$overtime = $overtimediff -> format("%H:%I");
									//$overtime=$out-strtotime("18:30");
									//$overtime=date('h:i',$overtime);
									$limit_ot="18:30:00";
									list($hours, $minutes,$sec) = split(':', $out); 
									$startTimestamp = mktime($hours, $minutes,$sec); 
									 
									list($hours, $minutes,$sec) = split(':', $limit_ot); 
									$endTimestamp = mktime($hours, $minutes,$sec); 
									
									$seconds = $startTimestamp - $endTimestamp;
									$sec=$seconds % 60;
									$minutes = ($seconds / 60) % 60; 
									$hours = round($seconds / (60 * 60)); 
									//echo "Time passed: <b>$hours</b> hours and <b>$minutes</b> minutes<br>"; 
									//end test
									$overtime=$hours.":".$minutes.":".$sec;
								} else {
									$overtime = "-";
								}
								
								// calculate late time
								if ($getCurrentMonth[$r]["attd_in_time"] != "" && strtotime($getCurrentMonth[$r]["attd_in_time"]) >= strtotime("09:30")) {
									//$latetimediff = date_diff(date_create($getCurrentMonth[$r]["attd_in_time"]), date_create("09:30"));
									//$latetime = $latetimediff -> format("%H:%I");
									$limit="09:30";
									//$latetime=$in-$limit;
									//$latetime=date('h:i',$latetime);
									list($hours, $minutes,$sec) = split(':', $in); 
									$startTimestamp = mktime($hours, $minutes,$sec); 
									 
									list($hours, $minutes,$sec) = split(':', $limit); 
									$endTimestamp = mktime($hours, $minutes,$sec); 
									
									$seconds = $startTimestamp - $endTimestamp;
									$sec=$seconds % 60;
									$minutes = ($seconds / 60) % 60; 
									$hours = round($seconds / (60 * 60)); 
									//echo "Time passed: <b>$hours</b> hours and <b>$minutes</b> minutes<br>"; 
									//end test
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
									//$convert = strtotime("+$sec seconds", $convert);
									$convert = strtotime("+$hr hours", $convert);

									$totaltime = date("H:i", $convert);
								} else {
									$totaltime = "-";
								}
								$body.="<tr>";
								$body.="<td>".substr($getCurrentMonth[$r]["calendar_date"], -2)."</td>";
								$body.="<td>".strtoupper($getCurrentMonth[$r]["calendar_day"])."</td>";
								$body.="<td>".$intime."</td>";
								$body.="<td><span style='color:red;'>".$latetime."</span></td>";
								//$body.="<td style='border-bottom: 1px dotted #514F4F; text-align:center;'><span style='color:red;'>".$latetime."</span></td>";
								$body.="<td>".$outtime."</td>";
								$body.="<td>".$worktime."</td>";
								$body.="<td>".$overtime."</td>";
								$body.="<td>".$totaltime."</td>";
								$body.="</tr>";
							
							}
							
								$body.='</table>
								</body>
							</html>';

$from_name=$tosend;

$headers  = "MIME-Version: 1.0 \n" ;
/*$headers .= "From: " .
       "".mb_encode_mimeheader (mb_convert_encoding($from_name,"ISO-2022-JP","AUTO")) ."" .
       "\n";*/
$headers .= 'From:'.$from_name. "\r\n";


    
$headers .= "Content-type: text/html;charset=ISO-2022-JP \n";

    
/* Convert body to same encoding as stated 
in Content-Type header above */
    
$body = mb_convert_encoding($body, "ISO-2022-JP","AUTO");
    
/* Mail, optional paramiters. */
$subject="Attendance of ".$fname;
    
mb_language("ja");
$subject = mb_convert_encoding($subject, "ISO-2022-JP","AUTO");
$subject = mb_encode_mimeheader($subject);

if(mail('j.sumitomo@rubbersoul.co.jp', $subject, $body, $headers))
	{
		// echo '<script type="text/javascript">
		// 				alert("Mail has been sent......");
		// 			window.location.href= "home.php";
		// 					</script>';
		header("Location:history.php?msg=1");
	}
					
?>
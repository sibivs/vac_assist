<script src="../../Scripts/jquery-1.6.3.js" type="text/javascript"></script>
<!--  FOR STATISTICS     -->
<script src="library/RGraph.common.core.js"></script>
<script src="library/RGraph.common.dynamic.js"></script>
<script src="library/RGraph.common.key.js"></script>
<script src="library/RGraph.common.tooltips.js"></script>
<script src="library/RGraph.pie.js"></script>
<!--  FOR STATISTICS     -->
<link rel="stylesheet" href="../../styles/style.css">
<?php
include_once("../../db_connect.php");
$mission = $_REQUEST['m'];
$get_submission_status = mysql_query("SELECT application_status, COUNT(*) as total FROM `appointment_schedule` WHERE scheduled_date_app = CURDATE() and mission_id = '".$mission."' GROUP BY application_status DESC");
if(mysql_num_rows($get_submission_status)>0)
{
	$data = "";
	while($todays_submission = mysql_fetch_array($get_submission_status))
	{
		$data = $data.$todays_submission['application_status']."=".$todays_submission['total']."#";
	}
?>
<input type="hidden" id="data_sub" value="<?php echo rtrim($data,"#"); ?>" />
<div style="width: 340px; font-size: 13px; font-weight:600; padding-bottom: 10px; text-align:center;" class="text-danger">
   <u> Today's Submission Status</u>
   <br>
</div>
<canvas id="today_submission" width="340" height="250">[No canvas support]</canvas>
<script>
    var labels_subm = $("#data_sub").val().split("#");
	var data_subm = [];
	for(var i=0; i<labels_subm.length; i++)
	{
		sub_splitm =  labels_subm[i].split("=");
		data_subm[i]=parseInt(sub_splitm[1],10);
	} 
	
  	var pie3 = new RGraph.Pie({
            id: 'today_submission',
            data: data_subm,
            options: {
				tooltips: labels_subm,
				colors: ['#ff8080','#0080ff','#53c653','#b36b00','#00ff00','#0000ff','#4dffa6'],
				strokestyle: 'white',
				linewidth: 1,
				shadow: true,
				shadowOffsetx: 2,
				shadowOffsety: 2,
				shadowBlur: 3,
				exploded: 3,
				textSize: 9,
				radius: 75,
				key: labels_subm,
				keyPositionGraphBoxed: true,
				keyPositionX: 170,
				keyPositionY: 20,
				centerx:80,
				centery: 100
            }
          }).roundRobin(null, function () {pie3.explodeSegment(0,6);});
</script>
                                
<?php
}
else
{
	echo '<div style="width: 360px; height: 200px;" class="text-info h4">No Appointment Today!</div>';
}

$get_submission_monthly = mysql_query("SELECT application_status, COUNT(*) as total FROM `appointment_schedule` WHERE YEAR(scheduled_date_app) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(scheduled_date_app) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH) and mission_id = '".$mission."' GROUP BY application_status ASC");
if(mysql_num_rows($get_submission_monthly)>0)
{
	$data_monthly = "";
	while($monthly_submission = mysql_fetch_array($get_submission_monthly))
	{
		$data_monthly = $data_monthly.$monthly_submission['application_status']."=".$monthly_submission['total']."#";
	}
?>

<input type="hidden" id="data_sub_monthly" value="<?php echo rtrim($data_monthly,"#"); ?>" />
<div style="width: 340px; font-size: 13px; font-weight:600; padding-bottom: 10px; text-align:center;" class="text-danger">
   <u> Monthly Submission Status</u>
   <br>
</div>
<canvas id="month_submission" width="3400" height="250">[No canvas support]</canvas>
<script type="text/javascript">
 	// executes when complete page is fully loaded, including all frames, objects and images
 	var labels_sub = $("#data_sub_monthly").val().split("#");
	var data_sub = [];
	for(var i=0; i<labels_sub.length; i++)
	{
		sub_split =  labels_sub[i].split("=");
		data_sub[i]=parseInt(sub_split[1],10);
	} 
	
  	var pie4 = new RGraph.Pie({
            id: 'month_submission',
            data: data_sub,
            options: {
				tooltips: labels_sub,
				colors: ['#ff8080','#0080ff','#53c653','#b36b00','#00ff00','#0000ff','#4dffa6'],
				strokestyle: 'white',
				linewidth: 1,
				shadow: true,
				shadowOffsetx: 2,
				shadowOffsety: 2,
				shadowBlur: 3,
				exploded: 3,
				textSize: 9,
				radius: 75,
				key: labels_sub,
				keyPositionGraphBoxed: true,
				keyPositionX: 170,
				keyPositionY: 20,
				centerx:80,
				centery: 100
            }
          }).roundRobin(null, function () {pie4.explodeSegment(0,6);});
    
</script>
                                
<?php
}
?>

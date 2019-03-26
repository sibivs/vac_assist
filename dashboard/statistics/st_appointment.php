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
$get_appointment_status = mysql_query("SELECT appointment_type, COUNT(*) as total FROM `appointment_schedule` WHERE date_of_appointment = CURDATE() and mission_id = '".$mission."' GROUP BY appointment_type DESC");
if(mysql_num_rows($get_appointment_status)>0)
{
	$data = "";
	while($todays_appointment = mysql_fetch_array($get_appointment_status))
	{
		$data = $data.$todays_appointment['appointment_type']."=".$todays_appointment['total']."#";
	}
?>
<input type="hidden" id="data" value="<?php echo rtrim($data,"#"); ?>" />
<div style="width: 450px; font-size: 13px; padding-bottom: 10px; font-weight:600; text-align:center;" class="text-danger">
   <u> Today's Appointment Status</u>
   <br>
</div>
<canvas id="today_appointment" width="450" height="250">[No canvas support]</canvas>
<script>
    var labels = $("#data").val().split("#");
	var data = [];
	for(var i=0; i<labels.length; i++)
	{
		sub_split =  labels[i].split("=");
		data[i]=parseInt(sub_split[1],10);
	} 
	
  	var pie1 = new RGraph.Pie({
            id: 'today_appointment',
            data: data,
            options: {
				tooltips: labels,
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
				key: labels,
				keyPositionGraphBoxed: true,
				keyPositionX: 170,
				keyPositionY: 20,
				centerx:80,
				centery: 100
            }
          }).roundRobin(null, function () {pie1.explodeSegment(0,6);});
</script>
                                
<?php
}
else
{
	echo '<div style="width: 360px; height: 200px;" class="text-info h4">No Appointment Today!</div>';
}

$get_appointment_monthly = mysql_query("SELECT appointment_type, COUNT(*) as total FROM `appointment_schedule` WHERE YEAR(date_of_appointment) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(date_of_appointment) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH) and mission_id = '".$mission."' GROUP BY appointment_type ASC");
if(mysql_num_rows($get_appointment_monthly)>0)
{
	$data_monthly = "";
	while($monthly_appointment = mysql_fetch_array($get_appointment_monthly))
	{
		$data_monthly = $data_monthly.$monthly_appointment['appointment_type']."=".$monthly_appointment['total']."#";
	}
?>

<input type="hidden" id="data_monthly" value="<?php echo rtrim($data_monthly,"#"); ?>" />
<div style="width: 450px; font-size: 13px; padding-bottom: 10px; font-weight:600; text-align:center;" class="text-danger">
   <u> Monthly Appointment Status</u>
   <br>
</div>
<canvas id="month_appointment" width="450" height="250">[No canvas support]</canvas>
<script type="text/javascript">
 	// executes when complete page is fully loaded, including all frames, objects and images
 	var labels_monthly = $("#data_monthly").val().split("#");
	var data_monthly = [];
	for(var i=0; i<labels_monthly.length; i++)
	{
		sub_split =  labels_monthly[i].split("=");
		data_monthly[i]=parseInt(sub_split[1],10);
	} 
	
  	var pie2 = new RGraph.Pie({
            id: 'month_appointment',
            data: data_monthly,
            options: {
				tooltips: labels_monthly,
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
				key: labels_monthly,
				keyPositionGraphBoxed: true,
				keyPositionX: 170,
				keyPositionY: 20,
				centerx:80,
				centery: 100
            }
          }).roundRobin(null, function () {pie2.explodeSegment(0,6);});
    
</script>
                                
<?php
}
?>

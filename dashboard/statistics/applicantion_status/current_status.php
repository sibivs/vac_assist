<?php
$status_vs_sess =session_status();
if($status_vs_sess == PHP_SESSION_NONE){
    //There is no active session
    session_start();
}
if(isset($_SESSION['role_ukvac']))
{
	include_once("../../../db_connect.php");
	$mission = $_REQUEST['m'];
?>
    <!DOCTYPE html>
    <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <link rel="stylesheet" href="../../../styles/style.css">
        </head>
        <body style="height:360px;">
            <div style="height: 40px; display:none; border: 0px; vertical-align: top; padding-bottom: 30px;" class="form-control" align="center" id="response1"></div>
            <?php 
            $date_today = date("Y-m-d");
            $get_total_app = mysql_query("select count(*) as total_appointment from appointment_schedule where scheduled_date_app='$date_today' and mission_id='".$mission."'");
            $result_total_appointment= mysql_fetch_array($get_total_app);
            if($result_total_appointment['total_appointment'] >0)
            {
                $total_app = $result_total_appointment['total_appointment'];
            }
            else
            {
                $total_app=0;
            }
            
            
            $get_total_shown_up = mysql_query("select count(*) as total_shown_up from appointment_schedule where application_status NOT IN ('scheduled','applicant_not_present')  and scheduled_date_app='$date_today' and mission_id='".$mission."'");
            $result_total_shown_up= mysql_fetch_array($get_total_shown_up);
            if($result_total_shown_up['total_shown_up'] >0)
            {
                $total_shown_up = $result_total_shown_up['total_shown_up'];
            }
            else
            {
                $total_shown_up=0;
            }
            
            
            
            $get_total_submissions = mysql_query("select count(*) as total_submitted from appointment_schedule where application_status IN ('outscan')  and date_of_appointment ='$date_today' and scheduled_date_app = '$date_today' and mission_id='".$mission."'");
            $result_total_submissions= mysql_fetch_array($get_total_submissions);
            if($result_total_submissions['total_submitted'] >0)
            {
                $total_submission = $result_total_submissions['total_submitted'];
            }
            else
            {
                $total_submission=0;
            }
        
        
            $get_total_submissions_prev = mysql_query("select count(*) as total_submitted from appointment_schedule where application_status IN ('outscan')  and scheduled_date_app = '$date_today' and date_of_appointment NOT IN ('$date_today') and mission_id='".$mission."'");
            $result_total_submissions_prev= mysql_fetch_array($get_total_submissions_prev);
            if($result_total_submissions_prev['total_submitted'] >0)
            {
                $total_submission_prev = $result_total_submissions_prev['total_submitted'];
            }
            else
            {
                $total_submission_prev=0;
            }
            
            
            $get_total_returned = mysql_query("select count(*) as total_returned from appointment_schedule where application_status NOT IN ('scheduled','outscan','shown_up','ro_complete')  and scheduled_date_app='$date_today' and mission_id='".$mission."'");
            $result_total_returned= mysql_fetch_array($get_total_returned);
            if($result_total_returned['total_returned'] >0)
            {
                $total_returned = $result_total_returned['total_returned'];
            }
            else
            {
                $total_returned=0;
            }
        
            $walkins = mysql_query("select count(*) as walkin FROM appointment_schedule WHERE date_of_appointment='$date_today' AND `scheduled_date_app` NOT IN ('$date_today') and application_status NOT IN('rejected','pending') and mission_id='".$mission."'");
            $result_walkins= mysql_fetch_array($walkins);
            if($result_walkins['walkin'] >0)
            {
                $total_walkin = $result_walkins['walkin'];
            }
            else
            {
                $total_walkin =0;
            }
        
            $get_total_submissions_waiting = mysql_query("select count(*) as total_submitted_waiting from appointment_schedule where application_status IN ('shown_up','ro_complete')  and scheduled_date_app='$date_today' and mission_id='".$mission."'");
            $result_total_submissions_waiting= mysql_fetch_array($get_total_submissions_waiting);
            if($result_total_submissions_waiting['total_submitted_waiting'] >0)
            {
                $total_submission_waiting = $result_total_submissions_waiting['total_submitted_waiting'];
            }
            else
            {
                $total_submission_waiting=0;
            }
            
            
            $get_total_noshow = mysql_query("select count(*) as total_noshow from appointment_schedule where application_status IN ('scheduled')  and scheduled_date_app='$date_today' and mission_id='".$mission."'");
            $result_total_noshow= mysql_fetch_array($get_total_noshow);
            if($result_total_noshow['total_noshow'] >0)
            {
                $total_noshow = $result_total_noshow['total_noshow'];
            }
            else
            {
                $total_noshow=0;
            }
            
            ?>
            <table class="table">
                <tr>
                    <td class="text-info" style="text-align: left; font-weight: bold; font-size: 14px;">Date : <?php echo date("d-m-Y"); ?></td>
                    <td class="text-info" style="text-align: left; font-weight: bold; font-size: 14px;">Total Appoinmtnents : <?php echo $total_app;  ?></td>
                </tr>
                <tr>
                    <td colspan="2" class="text-info" style="text-align: left;font-size: 14px;">Total shown up applicants  :  <span style="font-weight:normal;"><?php echo ($total_submission+$total_returned+$total_submission_waiting); //echo $total_shown_up; ?></span></td>
                </tr>
                <tr>
                    <td colspan="2" class="text-danger" style="border: 0px; border-color: none; text-align: left; font-size: 14px; padding-left: 50px;">- Total submitted applicants :  <span style="font-weight:normal;"><?php echo $total_submission; ?></span></td>
                </tr>
                <tr>
                    <td colspan="2" class="text-danger" style="border: 0px; border-color: none; text-align: left; font-size: 14px; padding-left: 50px;">- Total not submitted applicants :  <span style="font-weight:normal;"><?php echo $total_returned; ?></span></td>
                </tr>
                <tr>
                    <td colspan="2" class="text-danger" style="border: 0px; border-color: none; text-align: left; font-size: 14px; padding-left: 50px;">- Total applicants waiting for submission :  <span style="font-weight:normal;"><?php echo $total_submission_waiting; ?></span></td>
                </tr>
                <tr>
                    <td colspan="2" class="text-info" style="font-size: 14px;border: 0px; border-color: none; text-align: left;">Total no shows  :  <span style="font-weight:normal;"><?php echo ($total_noshow+$total_submission_prev); ?></span></td>
                </tr>
                <tr>
                    <td colspan="2" class="text-danger" style="border: 0px; border-color: none; text-align: left; font-size: 14px; padding-left: 50px;">- Previous Day Submissions :  <span style="font-weight:normal;"><?php echo $total_submission_prev; ?></span></td>
                </tr>
                <tr>
                    <td colspan="2" class="text-danger" style="border: 0px; border-color: none; text-align: left; font-size: 14px; padding-left: 50px;">- Today's No Shows :  <span style="font-weight:normal;"><?php echo $total_noshow; ?></span></td>
                </tr>
                <tr>
                    <td colspan="2" class="text-info" style="font-size: 14px;text-align: left;">Total Walk-In applicantions Submitted  :  <span style="font-weight:normal;"><?php echo $total_walkin; ?></span></td>
                </tr>    
            </table>
        </body>
    </html>
<?php
}
?>    
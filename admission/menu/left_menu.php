<?php
$status_vs_sess =session_status();
if($status_vs_sess == PHP_SESSION_NONE){
    //There is no active session
    session_start();
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- Main stylesheet and javascripts for the page -->
<link rel="stylesheet" type="text/css" href="styles/style.css">
<link rel="stylesheet" type="text/css" href="css/style_menu.css">
<!-- link href="styles/main_style.css" rel="stylesheet" type="text/css" /-->
<script src="../Scripts/jquery-1.6.3.js" type="text/javascript"></script>
<!--script src="Scripts/jquery-1.4.4.js" type="text/javascript"></script-->
<?php
if($_SESSION['role_ukvac']=="staff" || $_SESSION['role_ukvac']=="passback" || $_SESSION['role_ukvac']=="administrator")
{
	//include_once("../../db_connect.php");
	/*if($_SESSION['disabled']!=0)
	{
		$page_disabled = explode("_", $_SESSION['disabled']);
	}*/
	$disabled = array();
	$disabled_string = "";
	$i=0;
	$get_disabled_pages = mysql_query("select pa.file_name as filename from mission_page_disabled pd, pages_associated pa where pd.mission_id='".$_SESSION['mission_id']."' and pd.page_id=pa.id and pd.status='pg_disabled'");
	while($disabled_pgs = mysql_fetch_array($get_disabled_pages))
	{
		$disabled[$i] = $disabled_pgs['filename'];
		$i++;
	}
}

if($_SESSION['role_ukvac']=="administrator")//Supervisor user
{
	?>
    <ul class="menu">
        	<li><a href="../index.php"><span>Home</span></a></li>
            <li><a href=""><span>Admission Zone</span></a>
                <ul>
                	<?php
					if(!in_array('admission_officer.php',$disabled))
					{
					?>
                    <li><a href='../appointment_details.php'><span>Appointment Status</span></a></li>
                    <?php
					}
					else
					{
						
					?>
                    <!--li><a href='admission.php'><span>New Admission</span></a></li-->
                    <li><a href='status.php'><span>Appointment Status</span></a></li>
                    <?php
					}
					?>
                    
                </ul>
            </li>
            <?php
			if(!in_array('rectification.php',$disabled))
			{
			?>
            <li><a href="#"><span>Submission Zone</span></a>
                <ul>
                    <li><a href='../rectification.php'><span>Manage Rectifications</span></a></li>
                </ul>
            </li>
            <?php
			}
			?>
            <li><a href='../passport_delivery_reports.php'><span>Reports</span></a></li>
            <?php
            if(!in_array('manage_tees.php',$disabled) || !in_array('oyster_stock.php',$disabled))
			{
			?>
            <li><a href='#'><span>Inventory</span></a>
                <ul>
                	<?php
					if(!in_array('manage_tees.php',$disabled))
					{
					?>
                    <li><a href="../manage_tees.php" ><span>Manage TEE Envelop Stock</span></a></li>
                    <?php
					}
					if(!in_array('oyster_stock.php',$disabled))
					{
					?>
                    <li><a href="../oyster_stock.php" ><span>Manage Oyster Card Stock</span></a></li>
                    <?php
					}
					?>
                </ul>
            </li>
            <?php
			}
			if(!in_array('passport_deliver.php',$disabled) || !in_array('admission_officer.php',$disabled))
			{
			?>
            <li><a href='#'><span>Supervisor Tasks</span></a>
                <ul>
                	<?php
					if(!in_array('passport_deliver.php',$disabled))
					{
					?>
                    <li class='last'><a href="../upload_inscan_report.php" ><span>Inscan to VAC</span></a></li>
                    <?php
					}
					if(!in_array('admission_officer.php',$disabled))
					{
					?>
                    <li class='last'><a href="../appointments.php" ><span>Upload Appointment List</span></a></li>
                    <?php
					}
					?>
                    <li class='last'><a href="../approvals.php" ><span>Manage Approvals</span></a></li>
                </ul>
            </li>
            <?php
			}
			?>
            <li><a href='#'><span>System Setup</span></a>
                <ul>
                    <li><a target="_blank" href="chat/chat.php"><span>Chat</span></a></li>
                    <li><a href="" ><span>Manage Delivery Methods</span></a></li>
                    <li><a href="../mail_receivers.php" ><span>Manage Email Reciepients</span></a></li>
                    <li><a href="../users.php" ><span>Manage Users</span></a></li>
                    <li><a href="../walk_in_limits.php" ><span>Walk-In Limits</span></a></li>
                    <li class='last'><a href="../add_staff.php" ><span>Add New User</span></a></li>
                </ul>
            </li>
            <li><a href='#'><span>Chat</span></a>
                <ul>
                	<li><a target="_blank" href="chat/chat.php"><span>Chat</span></a></li>
            	</ul>
            </li>
        </ul>
    <?php

}
else if($_SESSION['role_ukvac']=="staff")//Staff user
{
	?>
    <ul class="menu">
            <li><a href='../index.php'><span>Home</span></a></li>
            <li><a href=""><span>Admission Zone</span></a>
                <ul>
                	<?php
					if(!in_array('admission_officer.php',$disabled))
					{
					?>
                	
                    <li><a href='../admission_officer.php'><span>New Admission</span></a></li>
                    <li><a href='../appointment_details.php'><span>Appointment Status</span></a></li>
                    <?php
					}
					else
					{
						
					?>
                    <li><a href='admission.php'><span>New Admission</span></a></li>
                    <li><a href='status.php'><span>Appointment Status</span></a></li>
                    <?php
					}
					?>
                    
                </ul>
            </li>
            <li><a href=""><span>Submission Zone</span></a>
                <ul>
                	<?php
					if(!in_array('admission_officer.php',$disabled))
					{
					?>
                    <li><a href='../registration.php'><span>Application Submission</span></a></li>
                    <?php
					}
					else
					{
					?>
                    <li><a href='submission.php'><span>Applicaton Submission</span></a></li>
                    <?php
					}
					?>
                    <li><a href='../biometric.php'><span>Biometrics</span></a></li>
                    <li><a href='../edit_outscanned.php'><span>Update My Submissions</span></a></li>
                    <li><a href='../resubmission_lists.php'><span>Passport Resubmissions</span></a></li>
                    <li><a href='../rectification.php'><span>Manage Rectifications</span></a></li>
                </ul>
            </li>
            <?php
            if(!in_array('passport_deliver.php',$disabled))
			{
			?>
            <li><a href=""><span>Passback Zone</span></a>
                <ul>
                	<li><a href='../search.php'><span>Search Current Status</span></a></li>
                    <li><a href='../passport_deliver.php'><span>Deliver Passport</span></a></li>
                </ul>
            </li>
            <?php
			}
			if(!in_array('bts.php',$disabled))
			{
			?>
            <li><a href=""><span>Travel Products</span></a>
                <ul>
                    <li><a href='../bts.php'><span>Travel Product Sales</span></a></li>
                </ul>
            </li>
            <?php
			}
			?>
            <li><a href=""><span>SO Reconciliation</span></a>
                <ul>
                    <li><a href='../cash_tally_sheet.php'><span>Cash Tally Sheet</span></a></li>
                    <?php
					if(!in_array('passport_deliver.php',$disabled))
					{
					?>
                    <li class='last'><a href='../reconciliation.php'><span>Reconciliation</span></a></li>
                    <?php
					}
					?>
                </ul>
            </li>
            <li><a href='#'><span>Reports</span></a>
                <ul>
                    <li><a href="../passport_delivery_reports.php" ><span>General Reports</span></a></li>
                </ul>
     		</li>
            <li><a href='#'><span>Chat</span></a>
                <ul>
                	<li><a target="_blank" href="chat/chat.php"><span>Chat</span></a></li>
            	</ul>
            </li>
    </ul>
    <?php
}
else if($_SESSION['role_ukvac']=="passback")//Staff user
{
	?>
    <ul class="menu">
            <li><a href='../index.php'><span>Home</span></a></li>
            <?php
            if(!in_array('passport_deliver.php',$disabled))
			{
			?>
            <li><a href=""><span>Passback Zone</span></a>
                <ul>
                	<li><a href='../search.php'><span>Search Current Status</span></a></li>
                    <li><a href='../passport_deliver.php'><span>Deliver Passport</span></a></li>
                </ul>
            </li>
            <?php
			}
			if(!in_array('bts.php',$disabled))
			{
			?>
            <li><a href=""><span>Travel Products</span></a>
                <ul>
                    <li><a href='../bts.php'><span>Travel Product Sales</span></a></li>
                </ul>
            </li>
            <?php
			}
			?>
            <li><a href=""><span>SO Reconciliation</span></a>
                <ul>
                    <li><a href='../cash_tally_sheet.php'><span>Cash Tally Sheet</span></a></li>
                </ul>
            </li>
            <li><a href='#'><span>Reports</span></a>
                <ul>
                    <li><a href="../passport_delivery_reports.php" ><span>General Reports</span></a></li>
                </ul>
     		</li>
            <li><a href='#'><span>Chat</span></a>
                <ul>
                	<li><a target="_blank" href="chat/chat.php"><span>Chat</span></a></li>
            	</ul>
            </li>
        </ul>
    <?php
}
else if($_SESSION['role_ukvac']=="management")//Management user
{
	?>
        <ul class="menu">
        	<li><a href='#'><span>Reports</span></a>
                <ul>
                    <li><a href="../passport_delivery_reports.php" ><span>General Reports</span></a></li>
                    <li><a href="../reports_chart.php" ><span>Management Reports</span></a></li>
                </ul>
     		</li>
            <li><a href='#'><span>Chat</span></a>
                <ul>
                	<li><a target="_blank" href="chat/chat.php"><span>Chat</span></a></li>
            	</ul>
            </li>
        </ul>
   	<?php
}
else if($_SESSION['role_ukvac']=="accounts")//Accounts users
{
	?>
        <ul class="menu">
        	<li><a href='#'><span>Reports</span></a>
                <ul>
                    <li><a href="../reports_chart.php" ><span>Management Reports</span></a></li>
                </ul>
     		</li>
            <li><a href='#'><span>Chat</span></a>
                <ul>
                	<li><a target="_blank" href="chat/chat.php"><span>Chat</span></a></li>
            	</ul>
            </li>
        </ul>
   	<?php
}
else if($_SESSION['role_ukvac']=="power_admin")//Super Admin
{
	?>
        <ul class="menu">
        	<li><a href='../index.php'><span>Administration</span></a></li>
        	<li><a href='#'><span>Missions</span></a>
                <ul>
                    <li><a href="../missions.php" ><span>Manage Missions</span></a></li>
                    <li><a href="../exchange_rates.php" ><span>Currency Conversion Rates</span></a></li>
                </ul>
     		</li>
            <li><a href='#'><span>Mission Modules</span></a>
                <ul>
                    <li><a href="../modules.php" ><span>Manage Modules</span></a></li>
                </ul>
     		</li>
            <li><a href='#'><span>Users</span></a>
                <ul>
                    <li><a href="../users.php" ><span>Manage Users</span></a></li>
                    <li><a href="../add_staff.php" ><span>Add New User</span></a></li>
                </ul>
            </li>
            <li><a href='#'><span>Chat</span></a>
                <ul>
                	<li><a target="_blank" href="../chat/chat.php"><span>Chat</span></a></li>
            	</ul>
            </li>
        </ul>
    <?php
}
else
{
	
}
	
?>

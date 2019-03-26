<?php
$status_vs_sess =session_status();
if($status_vs_sess == PHP_SESSION_NONE){
    //There is no active session
    session_start();
}
$expire_time = 60*60; //expire time
if((isset($_SESSION['last_activity_ukvac'] )) && ($_SESSION['last_activity_ukvac'] < (time()-$expire_time))) 
{
	echo "<script type='text/javascript'>alert('Session Expired, Please login')</script>";
	session_destroy();
	header("Location:login.php");
}
else 
{
    $_SESSION['last_activity_ukvac'] = time(); // you have to add this line when logged in also;
include_once("db_connect.php");
if(isset($_SESSION['role_ukvac']) && ($_SESSION['role_ukvac']=="administrator" ||$_SESSION['role_ukvac']=="staff" )&&  isset($_SESSION['vac']))
{
	$disabled = explode(",",$_SESSION['disabled_string']);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Consolidated Report</title>
<!-- Main stylesheet and javascripts for the page -->
<link href="styles/templatemo_style.css" rel="stylesheet" type="text/css" />
<!-- link href="styles/main_style.css" rel="stylesheet" type="text/css" /-->
<script src="Scripts/jquery-1.6.3.js" type="text/javascript"></script>
<script src="Scripts/jquery-1.4.4.js" type="text/javascript"></script>
<script src="Scripts/script.js"></script> 


<!-- for new Calandar control -->

<link rel="stylesheet" type="text/css" media="all" href="styles/jsDatePick_ltr.min.css" />
<script src="Scripts/jquery-1.6.3.js" type="text/javascript"></script>
<script type="text/javascript" src="Scripts/jsDatePick.min.1.3.js"></script>

<!-- End for new Calandar control -->
<script type="text/javascript">

function gethtml()
{
	var htmldata = document.getElementById('messagecontnt').innerHTML;
	//var oldhtml = document.getElementById('hiddenhtmlformail').value;
	//var new_html = oldhtml+htmldata;
	document.getElementById('hiddenhtmlformail').value=htmldata;
}

</script>

</head>

<body>
<form name="form1" id="form1" action="php_func.php?cho=30" method="post">
<textarea id="hiddenhtmlformail" name="hiddenhtmlformail" style="visibility:hidden;"></textarea>
<div id="templatemo_container">
	       
	<div id="templatemo_content">
		<div style="width: 16%;" id="templatemo_left_content">
		</div>

		<div id="templatemo_right_content" style="height: auto !important; width:950px;">
			<div id="templatemo_content_area">

                <div style="padding: 5px; width: 950px; display:block;" align="left" id="messagecontnt">
                <?php
				$cu = $_REQUEST['cu'];
				if (preg_match('/,/',$cu))
         		{
                	$currency = explode(",",$cu);
               	}
              	else
           		{
           			$currency[0]=$cu;
            	}
				foreach($currency as $key=> $value)
      			{
					$get_currenct_notes = mysql_fetch_array(mysql_query("select notes from note_values where currency='".$value."'"));
					$notes = preg_replace('/\s+/', '', $get_currenct_notes['notes']);
					if (preg_match('/,/',$notes))
					{
						$notes_each = explode(",",$notes);
					}
					else
					{
						$notes_each[0]=$notes;
					}
                    
					if(in_array("1000",$notes_each))
					{
						$cnt1000 = "select sum(1000s) as sum1000 from daily_consolidated_submissions where date='".date('Y-m-d')."' and mission_id='".$_SESSION['mission_id']."' and currency='".$value."'";
						$cnt1000_res=mysql_fetch_array(mysql_query($cnt1000));
						$display_1000 = "yes";
					}
					else
					{
						$cnt1000_res=0;
						$display_1000 = "no";
					}
					if(in_array("500",$notes_each))
					{
						$cnt500 = "select sum(500s) as sum500 from daily_consolidated_submissions where date='".date('Y-m-d')."' and mission_id='".$_SESSION['mission_id']."' and currency='".$value."'";
						$cnt500_res=mysql_fetch_array(mysql_query($cnt500));
						$display_500 = "yes";
					}
					else
					{
						$cnt500_res=0;
						$display_500 = "no";
					}
					if(in_array("100",$notes_each))
					{
						$cnt100 = "select sum(100s) as sum100 from daily_consolidated_submissions where date='".date('Y-m-d')."' and mission_id='".$_SESSION['mission_id']."' and currency='".$value."'";
						$cnt100_res=mysql_fetch_array(mysql_query($cnt100));
						$display_100 = "yes";
					}
					else
					{
						$cnt100_res=0;
						$display_100 = "no";
					}
					if(in_array("50",$notes_each))
					{
						$cnt50 = "select sum(50s) as sum50 from daily_consolidated_submissions where date='".date('Y-m-d')."' and mission_id='".$_SESSION['mission_id']."' and currency='".$value."'";
						$cnt50_res=mysql_fetch_array(mysql_query($cnt50));
						$display_50 = "yes";
					}
					else
					{
						$cnt50_res=0;
						$display_50 = "no";
					}
					if(in_array("20",$notes_each))
					{
						$cnt20 = "select sum(20s) as sum20 from daily_consolidated_submissions where date='".date('Y-m-d')."' and mission_id='".$_SESSION['mission_id']."' and currency='".$value."'";
						$cnt20_res=mysql_fetch_array(mysql_query($cnt20));
						$display_20 = "yes";
					}
					else
					{
						$cnt20_res=0;
						$display_20 = "no";
					}
					if(in_array("10",$notes_each))
					{
						$cnt10 = "select sum(10s) as sum10 from daily_consolidated_submissions where date='".date('Y-m-d')."' and mission_id='".$_SESSION['mission_id']."' and currency='".$value."'";
						$cnt10_res=mysql_fetch_array(mysql_query($cnt10));
						$display_10 = "yes";
					}
					else
					{
						$cnt10_res=0;
						$display_10 = "no";
					}
					if(in_array("5",$notes_each))
					{
						$cnt5 = "select sum(5s) as sum5 from daily_consolidated_submissions where date='".date('Y-m-d')."' and mission_id='".$_SESSION['mission_id']."' and currency='".$value."'";
						$cnt5_res=mysql_fetch_array(mysql_query($cnt5));
						$display_5 = "yes";
					}
					else
					{
						$cnt5_res=0;
						$display_5 = "no";
					}
					if(in_array("1",$notes_each))
					{
						$cnt1 = "select sum(1s) as sum1 from daily_consolidated_submissions where date='".date('Y-m-d')."' and mission_id='".$_SESSION['mission_id']."' and currency='".$value."'";
						$cnt1_res=mysql_fetch_array(mysql_query($cnt1));
						$display_1 = "yes";
					}
					else
					{
						$cnt1_res=0;
						$display_1 = "no";
					}
					if(in_array("0.500",$notes_each))
					{
						$cntp500 = "select sum(p500s) as sump500s from daily_consolidated_submissions where date='".date('Y-m-d')."' and mission_id='".$_SESSION['mission_id']."' and currency='".$value."'";
						$cntp500_res=mysql_fetch_array(mysql_query($cntp500));
						$display_p500 = "yes";
					}
					else
					{
						$cntp500_res=0;
						$display_p500 = "no";
					}
					if(in_array("0.100",$notes_each))
					{
						$cntp100 = "select sum(p100s) as sump100s from daily_consolidated_submissions where date='".date('Y-m-d')."' and mission_id='".$_SESSION['mission_id']."' and currency='".$value."'";
						$cntp100_res=mysql_fetch_array(mysql_query($cntp100));
						$display_p100 = "yes";
					}
					else
					{
						$cntp100_res=0;
						$display_p100 = "no";
					}
					if(in_array("0.50",$notes_each))
					{
						$cntp50 = "select sum(p50s) as sump50s from daily_consolidated_submissions where date='".date('Y-m-d')."' and mission_id='".$_SESSION['mission_id']."' and currency='".$value."'";
						$cntp50_res=mysql_fetch_array(mysql_query($cntp50));
						$display_p50 = "yes";
					}
					else
					{
						$cntp50_res=0;
						$display_p50 = "no";
					}
					$totalsum = 0;
				
				$qs="select count(*) as totalcount from passport_inscan_record where date_outscan='".date('Y-m-d')."' and current_status='outscan' and mission_id='".$_SESSION['mission_id']."'";
				$qry_total_taken = mysql_query($qs);
				$totalmyapplnts = mysql_fetch_array($qry_total_taken);
				if( count( array_filter( $totalmyapplnts)) == 0)
				{
					$totalmyapplnts[0]='0';
				}
				?>
                <div align='center' style='font-weight: bold; color: blue; font-weight: 16px;'>
                	<p><u>
                    	CONSOLIDATED Cash Tally Sheet</u>
                        <br>
                  	</p>
                    <p></p>
                    <p></p>
              	</div>
                <?php echo "<b>VAC Name:</b> ".$_SESSION['vac']."VAC -".$_SESSION['vac_city'].", ".$_SESSION['vac_country']; ?>
                        <br>
                        <?php echo "<b>Date:</b> ".date('d-m-Y h:m:s'); ?>
                        <br>
                        <?php echo "<b>Currency:</b> ".$value ;?>
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr>
            	<td align="center" width="45%" valign="top" style="padding: 5px;">
                	<table width="100%" border="1" cellpadding="0" cellspacing="0" id="t1">
		  				<tr>
		    				<td width="40%" valign="middle"><strong>&nbsp;&nbsp;&nbsp;Date :  </strong><?php echo date('d-F-Y'); ?></td>
            				<td width="60%" valign="middle" colspan="2"><p><strong>&nbsp;&nbsp;&nbsp;Applications Taken :</strong><label id='total_appln_taken' name='total_appln_taken' style="width: 30px;"><?php echo "  ".$totalmyapplnts[0]; ?></label></p></td>
            			</tr>
            <tr>
		    	<td valign="middle" align="center"><p><strong>Notes</strong></p></td>
            	<td valign="middle" align="center"><p><strong>Total Count</strong></p></td>
            	<td valign="middle" align="center"><p><strong>Total Amount</strong></p></td>
            </tr>
            <?php
			if($display_1000=="yes")
			{
			?>
            <tr>
		    	<td valign="middle" ><p align="right"><strong>1000 X&nbsp;&nbsp;&nbsp;</strong></p></td>
		    	<td valign="middle"><p>&nbsp;<?php echo $cnt1000_res[0]; ?></p></td>
		    	<td valign="middle"><p>&nbsp;<?php echo $cnt1000_res[0]*1000; $totalsum= $totalsum+($cnt1000_res[0]*1000); ?></p></td>
	      	</tr>
            <?php
			}
			if($display_500=="yes")
			{
			?>
            <tr>
		    	<td valign="middle" ><p align="right"><strong>500 X&nbsp;&nbsp;&nbsp;</strong></p></td>
		    	<td valign="middle"><p>&nbsp;<?php echo $cnt500_res[0]; ?></p></td>
		    	<td valign="middle"><p>&nbsp;<?php echo $cnt500_res[0]*500; $totalsum= $totalsum+($cnt500_res[0]*500); ?></p></td>
	      	</tr>
            <?php
			}
			if($display_100=="yes")
			{
			?>
            <tr>
		    	<td valign="middle" ><p align="right"><strong>100 X&nbsp;&nbsp;&nbsp;</strong></p></td>
		    	<td valign="middle"><p>&nbsp;<?php echo $cnt100_res[0]; ?></p></td>
		    	<td valign="middle"><p>&nbsp;<?php echo $cnt100_res[0]*100; $totalsum= $totalsum+($cnt100_res[0]*100); ?></p></td>
	      	</tr>
            <?php
			}
			if($display_50=="yes")
			{
			?>
            <tr>
		    	<td valign="middle" ><p align="right"><strong>50 X&nbsp;&nbsp;&nbsp;</strong></p></td>
		    	<td valign="middle"><p>&nbsp;<?php echo $cnt50_res[0]; ?></p></td>
		    	<td valign="middle"><p>&nbsp;<?php echo $cnt50_res[0]*50; $totalsum= $totalsum+($cnt50_res[0]*50); ?></p></td>
	      	</tr>
            <?php
			}
			if($display_20=="yes")
			{
			?>
		  	<tr>
		    	<td valign="middle" ><p align="right"><strong>20 X&nbsp;&nbsp;&nbsp;</strong></p></td>
		    	<td valign="middle"><p>&nbsp;<?php echo $cnt20_res[0]; ?></p></td>
		    	<td valign="middle"><p>&nbsp;<?php echo $cnt20_res[0]*20; $totalsum= $totalsum+($cnt20_res[0]*20); ?></p></td>
	      	</tr>
            <?php
			}
			if($display_10=="yes")
			{
			?>
		  	<tr>
		    	<td valign="middle"><p align="right"><strong>10 X&nbsp;&nbsp;&nbsp;</strong></p></td>
		    	<td valign="middle"><p>&nbsp;<?php echo $cnt10_res[0]; ?></p></td>
		    	<td valign="middle"><p>&nbsp;<?php echo $cnt10_res[0]*10; $totalsum= $totalsum+($cnt10_res[0]*10); ?></p></td>
	      	</tr>
            <?php
			}
			if($display_5=="yes")
			{
			?>
		  	<tr>
		    	<td valign="middle"><p align="right"><strong>5 X&nbsp;&nbsp;&nbsp;</strong></p></td>
		    	<td valign="middle"><p>&nbsp;<?php echo $cnt5_res[0]; ?></p></td>
		    	<td valign="middle"><p>&nbsp;<?php echo $cnt5_res[0]*5; $totalsum= $totalsum+($cnt5_res[0]*5); ?></p></td>
	      	</tr>
            <?php
			}
			if($display_1=="yes")
			{
			?>
		  	<tr>
		    	<td align="middle"><p align="right"><strong>1 X&nbsp;&nbsp;&nbsp;</strong></p></td>
		    	<td valign="middle"><p>&nbsp;<?php echo $cnt1_res[0]; ?></p></td>
		    	<td valign="middle"><p>&nbsp;<?php echo $cnt1_res[0]*1; $totalsum= $totalsum+($cnt1_res[0]*1);?></p></td>
	      	</tr>
            <?php
			}
			if($display_p500=="yes")
			{
			?>
		  	<tr>
		    	<td valign="middle"><p align="right"><strong>0.500 X&nbsp;&nbsp;&nbsp;</strong></p></td>
		    	<td valign="middle"><p>&nbsp;<?php echo $cntp500_res[0]; ?></p></td>
		    	<td valign="middle"><p>&nbsp;<?php echo $cntp500_res[0]*0.500; $totalsum= $totalsum+($cntp500_res[0]*0.500); ?></p></td>
	      	</tr>
            <?php
			}
			if($display_p100=="yes")
			{
			?>
		  	<tr>
		    	<td valign="middle"><p align="right"><strong>0.100 X&nbsp;&nbsp;&nbsp;</strong></p></td>
		    	<td valign="middle"><p>&nbsp;<?php echo $cntp100_res[0]; ?></p></td>
		    	<td valign="middle"><p>&nbsp;<?php echo $cntp100_res[0]*0.100; $totalsum= $totalsum+($cntp100_res[0]*0.100);?></p></td>
	      	</tr>
            <?php
			}
			if($display_p50=="yes")
			{
			?>
            <tr>
		    	<td valign="middle"><p align="right"><strong>0.050 X&nbsp;&nbsp;&nbsp;</strong></p></td>
		    	<td valign="middle"><p>&nbsp;<?php echo $cntp50_res[0]; ?></p></td>
		    	<td valign="middle"><p>&nbsp;<?php echo $cntp50_res[0]*0.050; $totalsum= $totalsum+($cntp50_res[0]*0.050);?></p></td>
	      	</tr>
            <?php
			}
			?>
		  	<tr>
		    	<td colspan="2" valign="top">&nbsp;</td>
		    	<td valign="top"><p>&nbsp;</p></td>
	      	</tr>
		  	<tr >
		    	<td colspan="2" valign="top"><p align="right" style="color:red;"><strong>Total Amount (<?php echo $value; ?>) :&nbsp;&nbsp;&nbsp;</strong></p></td>
		    	<td valign="middle" style="color:red;"><p style="color:red;">&nbsp;<strong><?php echo $totalsum; ?></strong></p></td>
	      	</tr>
	  	</table>
   		</td>
                	<td align="center" width="55%" style="padding: 5px;" valign="top">
                    
                    <table width="100%" border="1" cellpadding="0" cellspacing="0" id="t2">
                  <tr style="height: 40px;">
                    <td width="40%" valign="top"><p align="center"><strong>Category</strong></p></td>
                    <td width="30%" valign="top"><p align="center"><strong>Count</strong></p></td>
                    <td width="30%" valign="top"><p align="center"><strong>Total Amount(<?php echo $value; ?>)</strong></p></td>
                  </tr>
                  <?php
				  $myid = $_SESSION['user_id_ukvac'];
				  $grant_total_amount_VAS=0;
				  $r = "select dve.id as id ,sum(dve.total) as totals, dve.vas_id as vas_id from daily_vas_entries_total dve , price_list pl where dve.date='".date('Y-m-d')."' and dve.mission_id='".$_SESSION['mission_id']."' and dve.vas_id=pl.id and pl.currency='".$value."' group by dve.vas_id ";
				  $get_total_each = mysql_query($r);
				  while($get_vas_total_for_day=mysql_fetch_array($get_total_each))
				  {
					  $vas_name_and_price=mysql_fetch_array(mysql_query("select * from price_list where id='".$get_vas_total_for_day['vas_id']."' and mission_id='".$_SESSION['mission_id']."'"));
					  $vas_id=$vas_name_and_price['id'];
					  $vas_name = $vas_name_and_price['service_name'];
					  $vas_price = $vas_name_and_price['amount'];
					  $total_vas_count = $get_vas_total_for_day['totals'];
					  $total_vas_amount = $vas_price*$total_vas_count;
					  $grant_total_amount_VAS = $grant_total_amount_VAS+$total_vas_amount;			  
              ?>
              
              <tr>
                    <td width="219" valign="top" style="padding-right: 10px;"><p align="right"><strong><?php echo $vas_name; ?>&nbsp; <label style="color:#017196;">(<?php echo $vas_price; ?>&nbsp;<?php echo $value; ?>)</label></strong></p></td>
                    <td width="186" valign="top" style="padding-left: 10px;">
                    	<p>
                    	<?php echo $total_vas_count; ?>	</p>
                    </td>
                    <td width="130" valign="top"><p>&nbsp;<label style="font-weight:bold; padding-left: 10px;" ><?php echo $total_vas_amount; ?></label>&nbsp;</p></td>
                  </tr>
              
              
              <?php
				  }
				if(!in_array("bts.php",$disabled))
				{
					$get_exchange_rate = mysql_fetch_array(mysql_query("select exchng_rate_wt_gbp from exchange_rates where currency ='".$value."'"));
					$exchange_rate = $get_exchange_rate['exchng_rate_wt_gbp'];
					
					$datetoday = date('Y-m-d');
					$user_id = $_SESSION['user_id_ukvac'];
					$grand_total_bts=(float)0;
					$getbts_sales_today = "select quantity_sold*gbp_price as total_amount,date_sold,user_id from bts_sales where date_sold='$datetoday' and mission_id='".$_SESSION['mission_id']."' and currency='".$value."'";
					$q_ftch = mysql_query($getbts_sales_today);
					while($res_getbts_sales_today= mysql_fetch_array($q_ftch))
					{
						$ttl_roundamount = round($res_getbts_sales_today['total_amount']*$exchange_rate);
						$grand_total_bts=$grand_total_bts+ $ttl_roundamount;
					}
				?>
				<tr>
                    <td width="219" valign="top" style="padding-right: 10px;"><p align="right"><strong>Britain Travel Shop Products</strong></p></td>
                    <td width="186" valign="top" style="padding-left: 10px;"><p></p></td>
                    <td width="130" valign="top" style="padding-left: 10px;"><p><b><label id='totalbtssales' name='totalbtssales' style="font-weight:bold;" ><?php echo $grand_total_bts; ?></label></b></p></td>
                  </tr>
                  <?php
				}
				else
				{
					$grand_total_bts=0;
				}
				?>
                  <tr>
                    <td colspan="2" valign="top" style="padding-right: 10px;"><p align="right" style="color:red;"><strong>Total Amount (<?php echo $value; ?>) :&nbsp;&nbsp;&nbsp;</strong></p></td>
                    <td width="130" valign="top"><p style="color:red; font-weight: bold;">&nbsp;<label id='itemwisegtttl' name='itemwisegtttl' style="font-weight:bold; padding-left: 10px; color:red;" ><?php echo $grant_total_amount_VAS+$grand_total_bts;  ?></label>&nbsp;</p></td>
                  </tr>
                </table>
                
                    
                    </td>
                </tr>
                </table>
                
                <p>&nbsp;</p>
                
                
                
<p>&nbsp;</p>
                  
                  
                  <p style="text-align: center; font-size: 14px; color: blue; font-weight: bold; padding: 10px;">&nbsp; Cash Sheet Submission Details &nbsp;</p>
            
     	<table width="950px" border="1" cellpadding="0" cellspacing="0" id="t3">
        	<tr>
            	<td width="5%" valign="top"><p align="center"><strong>Sl. No</strong></p></td>
                <td width="15%" valign="top"><p align="center"><strong>Staff Name</strong></p></td>
              	<td width="15%" valign="top"><p align="center"><strong>Status</strong></p></td>
                <?php
                if($display_1000=="yes")
				{
				?>
                <td width="8%" valign="top"><p align="center"><strong>1000s</strong></p></td>
                <?php
				}
				if($display_500=="yes")
				{
                ?>
                <td width="8%" valign="top"><p align="center"><strong>500s</strong></p></td>
                <?php
				}
				if($display_100=="yes")
				{
                ?>
                <td width="8%" valign="top"><p align="center"><strong>100s</strong></p></td>
                <?php
				}
				if($display_50=="yes")
				{
                ?>
                <td width="8%" valign="top"><p align="center"><strong>50s</strong></p></td>
                <?php
				}
				if($display_20=="yes")
				{
                ?>
                <td width="8%" valign="top"><p align="center"><strong>20s</strong></p></td>
                <?php
				}
				if($display_10=="yes")
				{
                ?>
                <td width="8%" valign="top"><p align="center"><strong>10s</strong></p></td>
                <?php
				}
				if($display_5=="yes")
				{
                ?>
                <td width="8%" valign="top"><p align="center"><strong>5s</strong></p></td>
                <?php
				}
				if($display_1=="yes")
				{
                ?>
                <td width="8%" valign="top"><p align="center"><strong>1s</strong></p></td>
               	<?php
				}
				if($display_p500=="yes")
				{
                ?>
                <td width="10%" valign="top"><p align="center"><strong>0.500s</strong></p></td>
                <?php
				}
				if($display_p100=="yes")
				{
                ?>
                <td width="10%" valign="top"><p align="center"><strong>0.100s</strong></p></td>
                <?php
				}
				if($display_p50=="yes")
				{
                ?>
                <td width="10%" valign="top"><p align="center"><strong>0.050s</strong></p></td>
                <?php
				}
				?>
                <td width="10%" valign="top"><p align="center"><strong>Total Amount</strong></p></td>
                <td width="10%" valign="top"><p align="center"><strong>Difference</strong></p></td>
          	</tr>
            <?php
			$allusers = "select user_id, display_name from users where role='staff' and mission_id='".$_SESSION['mission_id']."'";
			$slno=0;
			$differtotal=0;
			$allusers1 = mysql_query($allusers);
			$cnt_notsubmitted=0;
			while($allusers2 = mysql_fetch_array($allusers1))
			{
				$get_total_each = mysql_num_rows(mysql_query("select * from daily_vas_entries_total where date='".date('Y-m-d')."' and user_id='".$allusers2['user_id']."' and mission_id='".$_SESSION['mission_id']."' "));
				
				$get_bts_entry=mysql_fetch_array(mysql_query("select * from bts_sales where date_sold='".date('Y-m-d')."' and user_id='".$allusers2['user_id']."' and mission_id='".$_SESSION['mission_id']."'"));
				
				if($get_total_each >0 || $get_bts_entry >0)
				{
					$chkwxist = mysql_query("select count(*) from daily_consolidated_submissions where date='".date('Y-m-d')."' and employee_id='".$allusers2['user_id']."' and mission_id='".$_SESSION['mission_id']."' and currency = '".$value."'");
					$res_chkexist = mysql_fetch_array($chkwxist);
					$slno++;
					$gettt = "select * from daily_consolidated_submissions where date='".date('Y-m-d')."' and employee_id='".$allusers2['user_id']."' and mission_id='".$_SESSION['mission_id']."' and currency = '".$value."'";
					$getttl = mysql_query($gettt);
					$rows = mysql_num_rows($getttl);
					$total = mysql_fetch_array($getttl);
			?>
                <tr>
                    <td valign="top" style="text-align:left; padding-left: 5px;"><?php echo $slno; ?></td>
                    <td valign="top" style="text-align:left; padding-left: 5px;"><?php echo $allusers2['display_name'];?>&nbsp;&nbsp;</td>
                    <td valign="top" style="text-align:left; padding-left: 5px;"><?php if($rows > 0) { echo "<label style='color:green;'>Submitted&nbsp;&nbsp;</label>"; } else {echo "<label style='color:red;'>Not Submitted&nbsp;&nbsp;</label>"; $cnt_notsubmitted++;}?></td>
                   
                   	<?php
                if($display_1000=="yes")
				{
				?>
                <td valign="top" align="center"><?php echo $total['1000s'];?></td>
                <?php
				}
				if($display_500=="yes")
				{
                ?>
                <td valign="top" align="center"><?php echo $total['500s'];?></td>
                <?php
				}
				if($display_100=="yes")
				{
                ?>
                <td valign="top" align="center"><?php echo $total['100s'];?></td>
                <?php
				}
				if($display_50=="yes")
				{
                ?>
                <td valign="top" align="center"><?php echo $total['50s'];?></td>
                <?php
				}
				if($display_20=="yes")
				{
                ?>
                <td valign="top" align="center"><?php echo $total['20s'];?></td>
                <?php
				}
				if($display_10=="yes")
				{
                ?>
                <td valign="top" align="center"><?php echo $total['10s'];?></td>
                <?php
				}
				if($display_5=="yes")
				{
                ?>
                <td valign="top" align="center"><?php echo $total['5s'];?></td>
                <?php
				}
				if($display_1=="yes")
				{
                ?>
                <td valign="top" align="center"><?php echo $total['1s'];?></td>
               	<?php
				}
				if($display_p500=="yes")
				{
                ?>
                <td valign="top" align="center"><?php echo $total['p500s'];?></td>
                <?php
				}
				if($display_p100=="yes")
				{
                ?>
                <td valign="top" align="center"><?php echo $total['p100s'];?></td>
                <?php
				}
				if($display_p50=="yes")
				{
                ?>
                <td valign="top" align="center"><?php echo $total['p50s'];?></td>
                <?php
				}
					$differ = $total['total_amount']-$total['total_per_submission'];
					$differtotal=$differtotal+$differ;
				?>
               	<td valign="top" align="center">
				<?php 
				if($differ>0) 
				{ 
					echo "<label style='font-weight: bold; color:red;'>+".round(($total['total_amount']-$total['total_per_submission']),3,PHP_ROUND_HALF_UP)."</label>"; 
				} 
				else if($differ <0) 
				{ 
					echo "<label style='font-weight: bold; color:red;'>".round(($total['total_amount']-$total['total_per_submission']),3,PHP_ROUND_HALF_UP)."</label>"; 
				}
				?>
              	</td>
          	</tr>
            <?php
				}
			}
			?>
            <tr>
            <td colspan="9" style="border-bottom:0px; !important;"></td>
            <td valign="top" align="center"><strong><?php echo $totalsum; ?></strong></td>
            <td valign="top" align="center" id="diffttl"><?php echo round($totalsum-($grant_total_amount_VAS+$grand_total_bts),2); ?></td>
            </tr>
            </table>
           	<p>&nbsp;</p>
            
            <?php
			$total_cash_diff = round($totalsum-($grant_total_amount_VAS+$grand_total_bts),2);
			if($total_cash_diff > 0)
			{
				?>
				<div align="center" style="width:100%; text-align:center; color:red; font-size: 14px; font-weight:bold;"><label >There is an Excess Amount Of <?php echo $total_cash_diff." ".$value;  ?> </label></div>
               <?php
			}
			else if($total_cash_diff < 0)
			{
				?>
				<div align="center" style="width:100%; text-align:center; color:red; font-size: 14px; font-weight:bold;"><label >There is a Shortage Amount Of <?php echo $total_cash_diff." ".$value;  ?></label></div>
               <?php
			}
			?>	
            
            <?php
			if(!in_array("bts.php",$disabled))
			{
				$allbts_sales_today = "select bts.quantity_sold as quantity_sold, bts.gbp_price as gbp_price, bts.quantity_sold*bts.gbp_price as total_amount,bts.product_name as product_name, u.display_name as display_name from bts_sales bts, users u where u.user_id=bts.user_id and bts.date_sold='$datetoday' and bts.mission_id='".$_SESSION['mission_id']."' and bts.currency='".$value."'";
				if(mysql_num_rows(mysql_query($allbts_sales_today)) >0)
				{
			?>      
                  <p style="text-align: center; font-size: 14px; color: blue; font-weight: bold; padding: 10px;">&nbsp; Travel Products List &nbsp;</p>
            
     	<table width="950px" border="1" cellpadding="0" cellspacing="0" id="t3">
        	<tr>
            	<td width="5%" valign="top"><p align="center"><strong>Sl. No</strong></p></td>
                <td width="20%" valign="top"><p align="center"><strong>Staff Name</strong></p></td>
              	<td width="35%" valign="top"><p align="center"><strong>Product Name</strong></p></td>
               	<td width="10%" valign="top"><p align="center"><strong>Quantity Sold</strong></p></td>
               	<td width="10%" valign="top"><p align="center"><strong>Unit Price (<?php echo $value; ?>)</strong></p></td>
              	<td width="10%" valign="top"><p align="center"><strong>Total Amount(<?php echo $value; ?>)</strong></p></td>
          	</tr>
            <?php
			
			$slno=0;
			$res_allsales = mysql_query($allbts_sales_today);
			while($res1_allsales = mysql_fetch_array($res_allsales))
			{
				$slno++;
			?>
            <tr>
            	<td valign="top" style="text-align:left; padding-left: 5px;"><?php echo $slno; ?></td>
                <td valign="top" style="text-align:left; padding-left: 5px;"><?php echo $res1_allsales['display_name'];?></td>
              	<td valign="top" style="text-align:left; padding-left: 5px;"><?php echo $res1_allsales['product_name'];?></td>
               	<td valign="top" style="text-align:left; padding-left: 5px;"><?php echo $res1_allsales['quantity_sold'];?></td>
               	<td valign="top" style="text-align:left; padding-left: 5px;"><?php echo ($res1_allsales['gbp_price']*$exchange_rate);?></td>
              	<td valign="top" style="text-align:left; padding-left: 5px;"><?php echo round($res1_allsales['total_amount']*$exchange_rate);?></td>
          	</tr>
            <?php
			}
			?>
            </table>
           <?php
				}
			}
			?>
            <hr>
			<?php
			}//Close Loop For Currency Iteration	
			?>
            
           	<p>&nbsp;</p>	
            
            </div>
		  </div><!-- Closing DIV  display_consolidated -->
          </div>
    
		</div>
<?php

?>
        <script language="javascript" type="text/javascript">
		gethtml();
		document.form1.action="php_func.php?cho=30";   //somename is whatever you want to send in there as name
		document.form1.submit();
		
		</script>
	</div>
</div>

</form>
</body>
</html>
<?php
}
else
	{
        header("Location:login.php");
	}
}
?>
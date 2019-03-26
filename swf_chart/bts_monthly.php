<?php
include_once("../db_connect.php");
$year = $_REQUEST['year'];
$month =$_REQUEST['month'];
$product = $_REQUEST['pname'];
if($year == "all")
{
	if($month=="all")
	{ 
		$yr = "All Months In ".$year; 
	}
	else
	{
		$mn_name =array(1 => "January", 2 => "February", 3 => "March", 4 => "April", 5 => "May", 6 => "June", 7 => "July", 8 => "August", 9 => "September", 10 => "October", 11 => "November", 12 => "December");
		if(substr( $month, 0,1) === '0')
		{
			$mname1 = ltrim($month,"0");
				
		}
		else
		{
			$mname1 = $month;
		}
		$mname = $mn_name[$mname1];
		$yr = $mname;
	}
	
	$chart_initial = "<chart><license>LTA9-KHS7V.O.945CWK-2XOI1X0-7L</license><axis_category  size='12' color='000000' />
		<axis_ticks value_ticks='true' category_ticks='true' major_thickness='2' minor_thickness='1' minor_count='1' major_color='222222' minor_color='222222' position='centered' />
		<axis_value  size='12' color='000000' alpha='100' steps='10' prefix='' suffix='' decimals='0' separator='' show_min='false'/>
		<chart_label  color='000000' alpha='100' size='10' position='center' prefix='BD ' suffix='' decimals='0' separator='' as_percentage='false' />
		<chart_border color='000000' top_thickness='0' bottom_thickness='1' left_thickness='1' right_thickness='0' />";
	
	
	$hedings_1 = "<chart_data><row><null/>";
	$hedings_2 = "";
	for($z= 2014; $z <= date('Y'); $z++)
	{
		$hedings_2=$hedings_2."<string>".$month."-".$z."</string>";
	}
	$hedings_3= "</row>";
	
	$full_heading =$hedings_1.$hedings_2.$hedings_3;
		
	$row_data = "";
	$a2 = "";
	$a3 = "";
	$search    ="";
		
	$a1 = "<row><string>Region A</string>";
	for($s= 2014; $s<= date('Y'); $s++)
	{
		$get_totals =mysql_query("SELECT '".$s."' AS YEAR,  IFNULL(ROUND(SUM((quantity_sold*price_per_item)),1),0) AS totals FROM bts_sales WHERE product_name='".$product."' and YEAR(date_sold)='".$s."' and MONTH(date_sold) = '".$month."' ORDER BY MONTH(date_sold)");
		$totals_result = mysql_fetch_array($get_totals);
		$a2 = $a2."<number bevel='data' tooltip='".$s." ".$product."  Total - ".$totals_result['totals']."' shadow='low'>".$totals_result['totals']."</number>";
	}
	$row_data = $a1.$a2."</row>";
	$chart_data_last = "</chart_data>";
	$chart_foorter = "<chart_grid_h alpha='10' thickness='1' type='dashed' />
		<chart_note type='arrow' size='13' color='004444' alpha='75' x='-10' y='-30' background_color_1='FF4400' background_alpha='75' shadow='low' />
		<chart_rect bevel='bg' shadow='high' x='225' y='90' width='340' height='200' positive_color='eeeeff' negative_color='dddddd' positive_alpha='100' negative_alpha='100'  corner_tl='0' corner_tr='0' corner_br='0' corner_bl='0' />
		<chart_guide horizontal='true' vertical='true' thickness='1' alpha='25' type='dashed' text_h_alpha='0' text_v_alpha='0' />
		<chart_transition type='scale' delay='.5' duration='1' order='series' />
	<chart_type>bar</chart_type>
	<draw>
		<text  color='FA051D'  x='920' y='40' size='14'>Toggle Full Screen</text>
		<text  transition='dissolve' delay='.5' duration='0.5' color='000033'  size='12' x='220' y='-123' width='700' height='180' h_align='center' v_align='bottom'>(** Data availability from April 2014 onwards)</text>
		<text  color='000000' alpha='100' rotation='-90' size='16' x='135' y='330' width='300' height='50' h_align='center' v_align='middle'>Year</text>
		<text  transition='dissolve' delay='.5' duration='0.5' color='000033'  size='16' x='220' y='-143' width='700' height='180' h_align='center' v_align='bottom'>Value Added Services Month On Month Comparison -".$yr." For ".$product."</text>
		<text  transition='dissolve' delay='.5' duration='0.5' color='000033'  size='16' x='190' y='213' width='300' height='130' h_align='center' v_align='bottom'>Total sale (BD)</text>
	</draw>
	<filter>
		<bevel id='data' angle='90' blurX='10' blurY='10' distance='5' highlightAlpha='10' shadowAlpha='20' type='full' />
		<bevel id='small' angle='45' blurX='2' blurY='2' distance='1' highlightAlpha='35' highlightColor='ffffff' shadowColor='000000' shadowAlpha='35' type='inner' />   
		<shadow id='high' distance='5' angle='45' alpha='35' blurX='15' blurY='15' />
		<shadow id='low' distance='2' angle='45' alpha='100' blurX='5' blurY='5' />
	</filter>

	<legend layout='hide' />
	<tooltip color='000000'
            alpha='75'
            size='11'
            background_color_1='ff4400' 
            background_color_2='8888ff' 
            background_alpha='90'
            shadow='low'
            />
	<link>
			 <area x='910' 
				y='35' 
				width='136'  
				height='35' 
				target='toggle_fullscreen'
				/>
	
	   </link>
		<series_color>
		<color>C9C7C8</color>
		<color>D4040F</color>
		<color>E30071</color>
		<color>CF009B</color>
		<color>9A04B8</color>
		<color>6302AD</color>
		<color>1404C4</color>
		<color>0274A1</color>
		<color>018C75</color>
		<color>038007</color>
		<color>6C7504</color>
		<color>A66A03</color>
		<color>D46402</color>
	</series_color>

</chart>";
	$final_data = $chart_initial.$full_heading.$row_data.$chart_data_last.$chart_foorter;
	$fp = fopen("bts_monthly_bar.xml","w");
	fwrite($fp,$final_data);
	fclose($fp);

}
else
{
	$yr = "All Months In ".$year; 
		
	$chart_initial = "<chart><license>LTA9-KHS7V.O.945CWK-2XOI1X0-7L</license><axis_category  size='12' color='000000' />
		<axis_ticks value_ticks='true' category_ticks='true' major_thickness='2' minor_thickness='1' minor_count='1' major_color='222222' minor_color='222222' position='centered' />
		<axis_value  size='12' color='000000' alpha='100' steps='10' prefix='' suffix='' decimals='0' separator='' show_min='false'/>
		<chart_label  color='000000' alpha='99' size='10' position='center' prefix='BD ' suffix='' decimals='0' separator='' as_percentage='false' />
		<chart_border color='000000' top_thickness='0' bottom_thickness='1' left_thickness='1' right_thickness='0' />";
	
	
	$hedings_1 = "<chart_data><row><null/>";
	$hedings_2 = "";
	$mn_name =array(1 => "January", 2 => "February", 3 => "March", 4 => "April", 5 => "May", 6 => "June", 7 => "July", 8 => "August", 9 => "September", 10 => "October", 11 => "November", 12 => "December");
	for($z= 1; $z <= 12; $z++)
	{
		$hedings_2=$hedings_2."<string>".$mn_name[$z]."</string>";
	}
	$hedings_3= "</row>";
		
	$full_heading =$hedings_1.$hedings_2.$hedings_3;
		
	$row_data = "";
	$a2 = "";
	$a3 = "";
	$search    ="";
		
	$a1 = "<row><string>Region A</string>";
	for($s= 1; $s<= 12; $s++)
	{
		if($s <=9)
		{
			$mont = "0".$s;
		}
		else
		{
			$mont = $s;
		}
		$get_totals =mysql_query("SELECT '".$year."' AS YEAR,  IFNULL(ROUND(SUM((quantity_sold*price_per_item)),1),0) AS totals FROM bts_sales WHERE product_name='".$product."' and YEAR(date_sold)='".$year."' and MONTH(date_sold) = '".$mont."' ORDER BY MONTH(date_sold)");
			
		$totals_result = mysql_fetch_array($get_totals);
		$a2 = $a2."<number bevel='data' tooltip='".$year." ".$product."  Total - ".$totals_result['totals']."' shadow='low'>".$totals_result['totals']."</number>";
	}
	$row_data = $a1.$a2."</row>";
	$chart_data_last = "</chart_data>";
	$chart_foorter = "<chart_grid_h alpha='10' thickness='1' type='dashed' />
		<chart_note type='arrow' size='13' color='004444' alpha='75' x='-10' y='-30' background_color_1='FF4400' background_alpha='75' shadow='low' />
		<chart_rect bevel='bg' shadow='high' x='225' y='90' width='400' height='240' positive_color='eeeeff' negative_color='dddddd' positive_alpha='100' negative_alpha='100'  corner_tl='0' corner_tr='0' corner_br='0' corner_bl='0' />
		<chart_guide horizontal='true' vertical='true' thickness='1' alpha='25' type='dashed' text_h_alpha='0' text_v_alpha='0' />
		<chart_transition type='scale' delay='.5' duration='1' order='series' />
	<chart_type>bar</chart_type>
	<draw>
		<text  color='FA051D'  x='920' y='40' size='14'>Toggle Full Screen</text>
		<text  transition='dissolve' delay='.5' duration='0.5' color='000033'  size='12' x='220' y='-123' width='700' height='180' h_align='center' v_align='bottom'>(** Data availability from April 2014 onwards)</text>
		<text  color='000000' alpha='100' rotation='-90' size='16' x='135' y='330' width='300' height='50' h_align='center' v_align='middle'>Months</text>
		<text  transition='dissolve' delay='.5' duration='0.5' color='000033'  size='16' x='220' y='-143' width='700' height='180' h_align='center' v_align='bottom'>Value Added Services Month On Month Comparison -".$yr." For ".$product."</text>
		<text  transition='dissolve' delay='.5' duration='0.5' color='000033'  size='16' x='190' y='253' width='300' height='130' h_align='center' v_align='bottom'>Total sale (BD)</text>
	</draw>
	<filter>
		<bevel id='data' angle='90' blurX='10' blurY='10' distance='5' highlightAlpha='10' shadowAlpha='20' type='full' />
		<bevel id='small' angle='45' blurX='2' blurY='2' distance='1' highlightAlpha='35' highlightColor='ffffff' shadowColor='000000' shadowAlpha='35' type='inner' />   
		<shadow id='high' distance='5' angle='45' alpha='35' blurX='15' blurY='15' />
		<shadow id='low' distance='2' angle='45' alpha='100' blurX='5' blurY='5' />
	</filter>

	<legend layout='hide' />
	<tooltip color='000000'
            alpha='75'
            size='11'
            background_color_1='ff4400' 
            background_color_2='8888ff' 
            background_alpha='90'
            shadow='low'
            />
	<link>
			 <area x='910' 
				y='35' 
				width='136'  
				height='35' 
				target='toggle_fullscreen'
				/>
	
	   </link>
		<series_color>
		<color>CBD0D1</color>
	</series_color>

</chart>";
	$final_data = $chart_initial.$full_heading.$row_data.$chart_data_last.$chart_foorter;
	$fp = fopen("bts_monthly_bar.xml","w");
	fwrite($fp,$final_data);
	fclose($fp);
}


	




?>
<?php
include_once("../db_connect.php");
$prname = $_REQUEST['pname'];
$mission_id =$_REQUEST['mid'];
if($prname == "all")
{
	$get_vas_items = mysql_query("select id, service_name from price_list where current_status ='active' and mission_id='".$mission_id."' ORDER BY service_name");
	$chart_initial = "<chart><license>LTA9-KHS7V.O.945CWK-2XOI1X0-7L</license>
		<axis_category  size='12' color='000000' />
		<axis_ticks value_ticks='true' category_ticks='true' major_thickness='2' minor_thickness='1' minor_count='1' major_color='222222' minor_color='222222' position='centered' />
		<axis_value  size='10' color='000000' alpha='100' steps='10' prefix='' suffix='' decimals='0' separator='' show_min='false'/>
		<chart_label  color='000000' alpha='99' size='10' position='outside' prefix='' suffix='' decimals='0' separator='' as_percentage='false' />
		<chart_border color='000000' top_thickness='0' bottom_thickness='1' left_thickness='3' right_thickness='0' />";
	
	
	$hedings_1 = "<chart_data><row><null/>";
	$hedings_2 = "";
	$x=1;
	$xvalue = "";
	$data_val = "";
	while($res_vas_names= mysql_fetch_array($get_vas_items))
	{
		$hedings_2=$hedings_2."<string>S ".($x)."</string>";
		$xvalue = $xvalue."S".($x)." - ".$res_vas_names['service_name'].",   ";
		if($x%5==0)
		{
			$xvalue = $xvalue."\r";
		}
		$x++;
	}
	
	$hedings_3= "</row>";
	
	$full_heading =$hedings_1.$hedings_2.$hedings_3;
	
	$row_data = "";
	$a2 = "";
	$a3 = "";
	$search    ="";
	$get_vas_items1 = mysql_query("select distinct(YEAR(date)) as years from daily_vas_entries_total where  mission_id='".$mission_id."' order by YEAR(date) ASC");
	$m=0;
	$ax="";
	while($get_vas_loop = mysql_fetch_array($get_vas_items1))
	{
		$get_totals =mysql_query("SELECT p.service_name AS service_name ,'".$get_vas_loop['years']."' as period,  IFNULL(SUM(d.total),0) AS totals FROM price_list p LEFT JOIN daily_vas_entries_total d on p.id=d.vas_id and YEAR(d.date)='".$get_vas_loop['years']."' where p.mission_id='".$mission_id."' GROUP BY p.service_name ORDER BY p.service_name ASC");
		$row_cnt = mysql_num_rows($get_totals);
		$a1 ="<row><string>".($get_vas_loop['years'])."</string>";
		$m=0;
		while($totals_result = mysql_fetch_array($get_totals))
		{		
			if($m==0)
			{
				$a2 = "<row><string>".($totals_result['period'])."</string><number tooltip='".$get_vas_loop['years']." ".$totals_result['service_name']."  Total - ".$totals_result['totals']."' shadow='low'>".$totals_result['totals']."</number>";
			}
			else if ($m==($row_cnt-1))
			{
				$a2 = "<number tooltip='".$get_vas_loop['years']." ".$totals_result['service_name']."  Total - ".$totals_result['totals']."' shadow='low'>".$totals_result['totals']."</number></row>";
			}
			else
			{
				$a2 = "<number tooltip='".$get_vas_loop['years']." ".$totals_result['service_name']."  Total - ".$totals_result['totals']."' shadow='low'>".$totals_result['totals']."</number>";
			}
			$ax = $ax.$a2;
			$m++;
		}
			
			
		$row_data = $ax;	
	}
	$chart_data_last = "</chart_data>";
	$chart_foorter = "<chart_grid_h alpha='10' thickness='1' type='dashed' />
		<chart_note type='arrow' size='13' color='004444' alpha='75' x='-10' y='-30' background_color_1='FF4400' background_alpha='75' shadow='low' />
		<chart_rect bevel='bg' shadow='high' x='95' y='90' width='840' height='200' positive_color='eeeeff' negative_color='dddddd' positive_alpha='100' negative_alpha='100'  corner_tl='0' corner_tr='0' corner_br='0' corner_bl='0' />
		<chart_guide horizontal='true' vertical='true' thickness='1' alpha='25' type='dashed' text_h_alpha='0' text_v_alpha='0' />
		<chart_transition type='scale' delay='.5' duration='1' order='series' />
	
		<draw>
			<text  color='FA051D'  x='920' y='40' size='14'>Toggle Full Screen</text>
			<text  transition='dissolve' delay='.5' duration='0.5' color='000033'  size='12' x='220' y='-123' width='700' height='180' h_align='center' v_align='bottom'>(** Data availability from April 2014 onwards)</text>
			
			<text  transition='dissolve' delay='.5' duration='0.5' color='000033'  size='12' x='100' y='223' width='850' height='180' h_align='left' v_align='bottom'>
			".$xvalue."
			</text>
			
			<text  color='000000' alpha='100' rotation='-90' size='16' x='7' y='330' width='300' height='50' h_align='center' v_align='middle'>Total</text>
			<text  transition='dissolve' delay='.5' duration='0.5' color='000033'  size='16' x='190' y='150' width='300' height='180' h_align='center' v_align='bottom'>Value Added Services</text>
			<text  transition='dissolve' delay='.5' duration='0.5' color='000033'  size='16' x='220' y='-143' width='700' height='180' h_align='center' v_align='bottom'>Value Added Services Year On Year Comparison- All Services</text>
		</draw>
		<filter>
			<shadow id='high' distance='5' angle='45' alpha='35' blurX='10' blurY='10' />
			<shadow id='low' distance='2' angle='45' alpha='35' blurX='5' blurY='5' />
			<bevel id='bg' angle='45' blurX='50' blurY='50' distance='10' highlightalpha='100' highlightColor='ffffff' shadowAlpha='10' inner='true' />
			<bevel id='blue' angle='-80' blurX='0' blurY='30' distance='20' highlightColor='ffffff' highlightalpha='100' shadowColor='000088' shadowAlpha='25' inner='true' />
			<bevel id='gray' angle='-80' blurX='0' blurY='30' distance='20' highlightColor='ffffff' highlightAlpha='25' shadowColor='000000' shadowAlpha='20' inner='true' />
		</filter>
		
		<legend shadow='low' x='75' y='57' width='360' height='20' margin='5' fill_color='000066' fill_alpha='8' line_alpha='0' line_thickness='0' size='12' color='333355' alpha='90' />
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
			<color>862d59</color>
			<color>C9C7C8</color>
			<color>73b82e</color>
			<color>666666</color>
			<color>768bb3</color>
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
		<series set_gap='-100' bar_gap='15' />
	
	</chart>";
	$final_data = $chart_initial.$full_heading.$row_data.$chart_data_last.$chart_foorter;
	if($fp = fopen("vas_yearly_bar.xml","w"))
	{
		fwrite($fp,$final_data);
		fclose($fp);
		print json_encode("correct");
	}
}
else
{
	
	$get_vas_items = mysql_query("select id, service_name from price_list where id='".$prname."' where mission_id='".$mission_id."'  ORDER BY service_name");
	$chart_initial = "<chart><license>LTA9-KHS7V.O.945CWK-2XOI1X0-7L</license><axis_category  size='12' color='000000' />
		<axis_ticks value_ticks='true' category_ticks='true' major_thickness='2' minor_thickness='1' minor_count='1' major_color='222222' minor_color='222222' position='centered' />
		<axis_value  size='12' color='000000' alpha='100' steps='10' prefix='' suffix='' decimals='0' separator='' show_min='false'/>
		<chart_label  color='000000' alpha='99' size='10' position='center' prefix='' suffix='' decimals='0' separator='' as_percentage='false' />
		<chart_border color='000000' top_thickness='0' bottom_thickness='1' left_thickness='1' right_thickness='0' />";
	
	
	$hedings_1 = "<chart_data><row><null/>";
	$hedings_2 = "";
	for($z= 2014; $z <= date('Y'); $z++)
	{
		$hedings_2=$hedings_2."<string>".$z."</string>";
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
		$get_totals =mysql_query("SELECT p.service_name AS service_name,  IFNULL(SUM(d.total),0) AS totals FROM price_list p LEFT JOIN daily_vas_entries_total d on p.id=d.vas_id AND YEAR(d.date)='".$s."' WHERE p.id=".$prname." and p.mission_id='".$mission_id."'  GROUP BY YEAR(d.date) ORDER BY YEAR(d.date) ASC");
		$totals_result = mysql_fetch_array($get_totals);
		$a2 = $a2."<number bevel='data' tooltip='".$s." ".$totals_result['service_name']."  Total - ".$totals_result['totals']."' shadow='low'>".$totals_result['totals']."</number>";
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
		<text  transition='dissolve' delay='.5' duration='0.5' color='000033'  size='16' x='220' y='-143' width='700' height='180' h_align='center' v_align='bottom'>Value Added Services Year On Year Comparison - ".$totals_result['service_name']."</text>
		<text  transition='dissolve' delay='.5' duration='0.5' color='000033'  size='16' x='190' y='213' width='300' height='130' h_align='center' v_align='bottom'>Total sale</text>
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
		<color>C9C7C8</color>
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
	if($fp = fopen("vas_yearly_bar.xml","w"))
	{
		fwrite($fp,$final_data);
		fclose($fp);
		print json_encode("correct");
	}
}



?>
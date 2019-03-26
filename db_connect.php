<?php
/************************ YOUR DATABASE CONNECTION START HERE   ****************************/
date_default_timezone_set("Asia/Bahrain");
define ("DB_HOST", "localhost"); // set database host
define ("DB_USER", "admin_pp_issue"); // set database user
define ("DB_PASS",'Global$vfs'); // set database password
define ("DB_NAME","vac_assist_19122016"); // set database name
								 
$link = @mysql_connect(DB_HOST, DB_USER, DB_PASS) or die("Couldn't make connection.");
$db = @mysql_select_db(DB_NAME, $link) or die("Couldn't select database");
								
								 
/************************ YOUR DATABASE CONNECTION END HERE  ****************************/

?>
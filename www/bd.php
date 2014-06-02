<?php
	$db = mysql_connect ("localhost","root","") or die("Не могу соединиться с MySQL.");
	mysql_query('SET NAMES cp1251');
	mysql_select_db ("bd",$db) or die("Не могу подключиться к базе.");;
?>
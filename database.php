<?php

	// set some variables w/ database login information

	$hostname = "localhost";

	$database_name = "";

	$username = "";

	$password = "";

	$eventtable = "Event";

	

	// open and connect to the database

	$ugather = mysql_pconnect($hostname, $username, $password) or trigger_error(mysql_error(),E_USER_ERROR);

	mysql_select_db($database_name)or die("Error: cannot select the chosen database!");

?>

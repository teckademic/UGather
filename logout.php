<?php
	require_once('database.php');
	require_once('validateData.php');	
	require_once('objUser.php');
	
	session_start();
	$old_userID = $_SESSION['valid_user'];;
	
	unset($_SESSION['valid_user']);
	$result_dest = session_destroy();
	
	if(!empty($old_userID))
	{
		if($result_dest)
		{
			//if they were logged in and are now logged out
			header('location: index.php');
		} else {
			echo 'Could not log you out. <br />';
		}
	} else {
		echo 'You were not logged in, and so have not been logged out.<br />';
		header('location: login.php');
	}
?>
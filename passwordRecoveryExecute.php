<?php
  	require_once('database.php');
	require_once('validateData.php');	
	require_once('objUser.php');
  
  	// creating short variable name
  	$userID = $_POST['userID'];

  	try 
	{
    	$password = resetPassword($userID);
    	notifyPassword($userID, $password);
    	header('location: passwordRecoveryConfirmation.php');
  	}
  	catch (Exception $e) 
	{
        echo  $e->getMessage();
  	}
?>
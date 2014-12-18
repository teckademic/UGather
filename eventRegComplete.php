<?php
	require_once('objRegistration.php');
	require_once('objUser.php');
		
	session_start();
	$userID = $_SESSION['valid_user'];
	
	checkValidUser();
	
	$eventID = $_POST['eventID'];
	$eventTitle = $_POST['eventTitle'];
	$userID = $_SESSION['valid_user'];
	$status = $_POST['status'];
	$userFirstName = $_POST['userFirstName'];
	$userLastName = $_POST['userLastName'];
	$plannerFirstName = $_POST['plannerFirstName'];
	$plannerLastName = $_POST['plannerLastName'];
	$eventPlanner = $_POST['eventPlanner'];
	
	try
	{
		//call add status function
		addAttendee($eventID, $userID, $status);
		// echo "You are $status to this event";
		eventPlannerRegistrationNotification($eventID, $userID, $status, $eventTitle, $userFirstName, $userLastName, $plannerFirstName, $plannerLastName);
		attendeeRegistrationNotification($eventID, $eventPlanner, $userID, $status, $eventTitle, $userFirstName, $userLastName);
		//do_html_url('dashboard.php', 'Dashboard');
	}
	catch(Exception $e)
	{
	   echo  $e->getMessage();
	}
?>
	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>

<title>uGather</title>

<link rel="stylesheet" href="images/frontCSS.css" type="text/css" />
</head>

<body>
<!-- wrap starts here -->
<div id="wrap">

	<!--header -->
	<div id="header">					

	<!--header ends-->					
	</div>
		
	<!-- navigation starts-->	
	<div  id="nav">
		<? 
			displayNavigation("Events");
		?>
	<!-- navigation ends-->	
	</div>						
			
	<!-- content starts -->
	<div id="content">
	
	  <div id="main">
      <h2>Registration</h2>
      	<p>Your registration status for <a href="viewEvent.php?eventID=<? echo $_POST['eventID']; ?>">this event</a> is: <? echo $_POST['status']; ?>.</p>
        
		</div>
        <!-- end main -->
				
        <!-- sidebar begins -->
        <div id="sidebar">
        <?php
            // display sidebar
			displaySidebar();
        ?>          
        <!-- sidebar ends -->		
        </div>			
		
	<!-- content ends-->	
	</div>
		
	<!-- footer starts -->		
	<div id="footer">
        <p>Copyright &copy; 2009 uGather team: CS 4850 group #3 @ Kennesaw State University (Delgado, Haney, Spera, Voss, and Wilder)</p>				
	<!-- footer ends-->
	</div>

<!-- wrap ends here -->
</div>

</body>
</html>

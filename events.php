<?php
	require_once('objEvent.php');
	require_once('objUser.php');
	session_start();
	$userID = $_SESSION['valid_user'];
	
	// set defaults, just in case
	$eventSortBy = "eventID";
	$eventSortHow = "descending";
	
	if ($_GET['sortBy'] && $_GET['sortHow'])
	{
		$eventSortBy = $_GET['sortBy'];
		$eventSortHow = $_GET['sortHow'];
	}
	if ($_POST['sortBy'] && $_POST['sortHow']) 
	{
		$eventSortBy = $_POST['sortBy'];
		$eventSortHow = $_POST['sortHow'];
	}
	
	$fieldsNeeded = "eventID, eventName, locationVenue, userID, date";
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

		<!-- main starts -->
		<div id="main">
				
			<h2>Events</h2>
            <h3>Current Events</h3>
            <p><a href="eventsCurrent.php"><b>view current events</b></a><br />
            (Current events may be registered for.)</p>

			<h3>Archived Events</h3>
            <p><a href="eventsArchived.php"><b>view archived events</b></a><br />
            (Archived events may NOT be registered for.)</p>
            
            <h3>Search Events</h3>
            <p><a href="search.php"><b>search for events</b></a></p>
            
            <? if (isLoggedIn())
            { ?>
				<h3>Other Options</h3>
            	<p><a href="newEvent.php"><b>create a new event</b></a><br />
                <a href="dashboardOwnedEvents.php"><b>view events you've created</b></a><br />
                <a href="dashboardRegisteredEvents.php"><b>view events you've registered for</b></a></p>
            <? } ?>

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


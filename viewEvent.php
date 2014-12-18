<?php
	include("database.php");
	require_once('objEvent.php');
	require_once('objRegistration.php');
	require_once('objUser.php');
	
	session_start();
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

		<?php
			$eventID = $_GET['eventID'];
			$event = getAllEventDetails($eventID);

			if (is_array($event)) 
			{
	    		foreach ($event as $rows)  
				{
					$date = $rows['date'];
					?> <h2>Event: <? echo $rows['eventName']; ?></h2>
                    <h3>Event Details</h3>
					 <table width="520" border="1">
                          <tr>
                            <td width="111"><strong><font color="332616">Event Name:</font></strong></td>
                            <td width="393"><font color="332616"><? echo $rows['eventName']; ?></font></td>
                          </tr>
                          <tr>
                            <td><strong><font color="332616">Host:</font></strong></td>
                            <td><font color="332616"><a href="viewPublicProfile.php?profileID=<? echo $rows['userID']; ?>"><b><? echo $rows['userID']; ?></b></a></font></td>
                          </tr>
                          <tr>
                            <td><strong><font color="332616">Location:</font></strong></td>
                            <td><font color="332616"><? echo $rows['locationVenue']; ?></font></td>
                          </tr>
                          <tr>
                            <td><strong><font color="332616">Date:</font></strong></td>
                            <td><font color="332616"><? echo $rows['date']; ?></font></td>
                          </tr>
                          <tr>
                            <td><strong><font color="332616">Start Time:</font></strong></td>
                            <td><font color="332616"><? echo $rows['startTime']; ?></font></td>
                          </tr>
                          <tr>
                            <td><strong><font color="332616">End Time:</font></strong></td>
                            <td><font color="332616"><? echo $rows['endTime']; ?></font></td>
                          </tr>
                          <tr>
                            <td><strong><font color="332616">Address:</font></strong></td>
                            <td><font color="332616"><a href="http://mapof.it/<? echo $rows['address']; ?> <? echo $rows['city']; ?>, <? echo $rows['state']; ?> <? echo $rows['zipCode']; ?>" target="_blank"><b><? echo $rows['address']; ?></b></a></font></td>
                          </tr>
                          <tr>
                            <td><strong><font color="332616">City:</font></strong></td>
                            <td><font color="332616"><? echo $rows['city']; ?></font></td>
                          </tr>
                          <tr>
                            <td><strong><font color="332616">State:</font></strong></td>
                            <td><font color="332616"><? echo $rows['state']; ?></font></td>
                          </tr>
                          <tr>
                            <td><strong><font color="332616">Zip:</font></strong></td>
                            <td><font color="332616"><? echo $rows['zipCode']; ?></font></td>
                          </tr>
                          <tr>
                            <td><strong><font color="332616">Phone Number:</font></strong></td>
                            <td><font color="332616"><? echo $rows['phoneNumber']; ?></font></td>
                          </tr>
                          <tr>
                            <td><strong><font color="332616">Description:</font></strong></td>
                            <td><font color="332616"><? echo $rows['eventDescription']; ?></font></td>
                          </tr>
                        </table>
                        
                        <!-- Options and Statistics Display --> 
                        <h3>Options & Statistics</h3>
                        <?php
							$userID 	= $_SESSION['valid_user'];
							$eventID 	= $_GET['eventID'];
							if(checkUserOwnsEvent($userID, $eventID))
							{ ?>
								<p><b>
                                <a href="editEvent.php?eventID=<? echo $rows['eventID']; ?>">edit event <? echo $rows['eventName']; ?></a> <br />
                                <a href="attendeesMessage.php?eventID=<? echo $rows['eventID']; ?>">send message to all attendees</a><br />
								<a href="viewAttendees.php?eventID=<? echo $rows['eventID']; ?>">view attendees to <? echo $rows['eventName']; ?></a><br />
                                <a href="eventsByUser.php?ownerID=<? echo $rows['userID']; ?>">view other events by <? echo $rows['userID']; ?></a>
                                </b></p>
							<? }
							else
							{ ?>
								<p><b><a href="viewAttendees.php?eventID=<? echo $rows['eventID']; ?>">view attendees to <? echo $rows['eventName']; ?></a><br />
                                <a href="eventsByUser.php?ownerID=<? echo $rows['userID']; ?>">view other events by <? echo $rows['userID']; ?></a><br />
                                <? if(isLoggedIn()) { ?>
                                <a href="sendMessage.php?eventID=<? echo $rows['eventID']; ?>&amp;recipientID=<? echo $rows['userID']; ?>">send message to host, <? echo $rows['userID']; ?></a>
                                <? } ?>
                                </b></p>
							<? }
						?>
				<? }
			}
			else 
			{
				echo "Error: could not obtain information for event with id = $eventID.";
			}
       	?>

		<!-- Registration Display -->
        <?php
			$userID 	= $_SESSION['valid_user'];
			$eventID	= $_GET['eventID'];
			$event 		= getAllEventDetails($eventID);
			
			if (!isDatePassed($eventID))
			{
				if(isLoggedIn() && !isRegistered($eventID, $userID))
				{ ?>
					<h3>Registration</h3>
					<form name="form1" method="post" action="eventRegComplete.php">
					<select name="status" size="3" id="status">
						<option value="Attending" selected>Attending</option>
						<option value="Maybe Attending">Maybe Attending</option>
						<option value="Not Attending">Not Attending</option>
					</select>
					<br />
					<input name="event" type="hidden" id="event" value="<?php echo $event = getAllEventDetails($eventID); ?>">
					<input name="eventID" type="hidden" id="eventID" value="<?php echo $eventID = $_GET['eventID']; ?>">
	
					<?php
						$eventID = $_GET['eventID'];
						$event = getEventNotificationDetails($eventID);
						
						if (is_array($event)) 
						{
							foreach ($event as $rows)  
							{
								$eventTitleTemp = $rows['eventName'];
								$eventPlannedIDTemp = $rows['userID'];
								?> 
								<input name="eventTitle" type="hidden" id="eventTitle" value="<?php echo $eventTitle = $eventTitleTemp; ?>" />
								<input name="eventPlanner" type="hidden" id="eventPlanner" value="<?php echo $eventPlanner = $eventPlannedIDTemp; ?>" />
							<? }
						}
						else 
						{
							echo "Error: could not obtain event name and event planner id for the event with ID = $eventID.";
						}
					?>
	
					<?php
						$userID 	= $_SESSION['valid_user'];
						$event 		= getUserName($userID);
					
						if (is_array($event)) 
						{
							foreach ($event as $rows)  
							{
								?> 
								<input name="userFirstName" type="hidden" id="userFirstName" value="<? echo $userFirstName = $rows['firstName']; ?>">
								<input name="userLastName" type="hidden" id="userLastName" value="<? echo $userLastName = $rows['lastName']; ?>">
							<? }
						}
						else 
						{
							echo "Error: could not obtain first and last name for the user with userID = $userID.";
						}
					?>
	
					<?php
						$eventID = $_GET['eventID'];
						$event = getEventPlannerName($eventID);
					
						if (is_array($event)) 
						{
							foreach ($event as $rows)  
							{ ?> 
								<input name="plannerFirstName" type="hidden" id="plannerFirstName" value="<? echo $firstName = $rows['firstName']; ?>">
								<input name="plannerLastName" type="hidden" id="plannerLastName" value="<? echo $lastName = $rows['lastName']; ?>">
							<? }
						}
						else 
						{
							echo "Error: could not retrieve the event planner's name for the event with id = $eventID.";
						}
					?>
	
					<input name="Register" type="submit" id="Register" value="Register">
					</form>
					</p>
				<? }
				else if(isLoggedIn() && isRegistered($eventID, $userID))
				{ 
					$userID = $_SESSION['valid_user'];
					$eventID = $_GET['eventID'];
					$attendeeStatus = getAttendeeRegistrationStatus($eventID, $userID);
					
				?>
					<h3>Registration</h3>
					<p>You are already registered for this event! Your current status is: <? echo $attendeeStatus; ?>. <br/>
					Use the below form to change your registration status!
					<form name="form2" method="post" action="eventRegEditComplete.php">
					<select name="status" size="3" id="status">
						<option value="Attending" selected>Attending</option>
						<option value="Maybe Attending">Maybe Attending</option>
						<option value="Not Attending">Not Attending</option>
					</select>
					<br />
					<input name="event" type="hidden" id="event" value="<?php echo $event = getAllEventDetails($eventID); ?>">
					<input name="eventID" type="hidden" id="eventID" value="<?php echo $eventID = $_GET['eventID']; ?>">
	
					<?php
						$eventID = $_GET['eventID'];
						$event = getEventNotificationDetails($eventID);
						
						if (is_array($event)) 
						{
							foreach ($event as $rows)  
							{
								$eventTitleTemp = $rows['eventName'];
								$eventPlannedIDTemp = $rows['userID'];
								?> 
								<input name="eventTitle" type="hidden" id="eventTitle" value="<?php echo $eventTitle = $eventTitleTemp; ?>" />
								<input name="eventPlanner" type="hidden" id="eventPlanner" value="<?php echo $eventPlanner = $eventPlannedIDTemp; ?>" />
							<? }
						}
						else 
						{
							echo "Error: could not obtain event name and event planner id for the event with ID = $eventID.";
						}
					?>
	
					<?php
						$userID 	= $_SESSION['valid_user'];
						$event 		= getUserName($userID);
					
						if (is_array($event)) 
						{
							foreach ($event as $rows)  
							{
								?> 
								<input name="userFirstName" type="hidden" id="userFirstName" value="<? echo $userFirstName = $rows['firstName']; ?>">
								<input name="userLastName" type="hidden" id="userLastName" value="<? echo $userLastName = $rows['lastName']; ?>">
							<? }
						}
						else 
						{
							echo "Error: could not obtain first and last name for the user with userID = $userID.";
						}
					?>
	
					<?php
						$eventID = $_GET['eventID'];
						$event = getEventPlannerName($eventID);
					
						if (is_array($event)) 
						{
							foreach ($event as $rows)  
							{ ?> 
								<input name="plannerFirstName" type="hidden" id="plannerFirstName" value="<? echo $firstName = $rows['firstName']; ?>">
								<input name="plannerLastName" type="hidden" id="plannerLastName" value="<? echo $lastName = $rows['lastName']; ?>">
							<? }
						}
						else 
						{
							echo "Error: could not retrieve the event planner's name for the event with id = $eventID.";
						}
					?>
	
					<input name="Register" type="submit" id="Register" value="Change Status">
					</form>
					</p>
				<? }
				else
				{ ?>
					<h3>Registration</h3>
					<p>If you'd like to register to attend this event, please <a href="login.php">login</a> to uGather.</p>
				<? }
			}
			else
			{ ?>
				<h3>Registration</h3>
                <p>This event has already come to pass, and you may not register for it nor change your registration status, if already registered.</p>
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

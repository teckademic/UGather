<?php
	// add necessary php files
	require_once("database.php");
	require_once('objEvent.php');
	require_once('objRegistration.php');
	require_once('objUser.php');
	require_once('validateData.php');
	
	session_start();
	
	// cannot see page if not logged in!
	checkValidUser();
	
	// get from URL
	$eventID = $_GET['eventID'];
	$recipientID = $_GET['recipientID'];
	$messageReceived = $_GET['messageReceived'];
	// get userID from session
	$userID = $_SESSION['valid_user'];
	
	$eventName = $_POST['eventName'];
	$messageText = $_POST['messageText'];
	
	$messageURL = str_replace('"', "&#34;", $messageReceived);
	
	try
	{
		// is form filled out?
		if (filled_out($_POST))
		{
			// yes, filled out, send message
			responseMessageForEvent($eventID, $eventName, $recipientID, $userID, $messageText, $messageReceived);
			// redirect to confirmation of message sent
			header('location: sendMessageConfirmation.php');
		} // end if form filled out
		else
		{
			// form not filled out, throw exception
			throw new Exception("<br /><span style=\"color:red;\">Please type in a response message!</span>");
		} // end else (form not filled out)
	} // end try
	catch (Exception $exception)
	{
		// code detailed below
	} // end catch
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
		
            <!-- send message header -->
            <h2>Send Response to: <? echo $recipientID; ?></h2> 
        	<!-- start send message form -->
			<h3>Message Content</h3>
			<?php 
				if ($exception)
					echo "<p>",  $exception->getMessage(), "\n";
            ?>
			<form name="sendMessage" method="post" action="messageResponseExecute.php?eventID=<? echo $eventID; ?>&amp;recipientID=<? echo $recipientID; ?>&amp;messageReceived=<? echo $messageURL; ?>">	
				<!-- hidden form fields-->
				<input name="eventID" type="hidden" id="eventID" value="<?php echo $eventID; ?>">
				<input name="eventName" type="hidden" id="eventName" value="<? echo $eventName; ?>">
				<input name="recipientID" type="hidden" id="recipientID" value="<?php echo $recipientID; ?>">
                <input name="userID" type="hidden" id="userID" value="<?php echo $userID; ?>">
				<table width="460" border="1">
					<tr>
						<td><strong><font color="332616">Message about: </font></strong></td>
						<td><font color="332616"><a href="http://www.ugather.info/viewEvent.php?eventID=<? echo $eventID; ?>"><b><? echo $eventName; ?></b></a></font></td>
					</tr>
					<tr>
						<td><strong><font color="332616">Message to: </font></strong></td>
						<td><font color="332616"><a href="viewPublicProfile.php?profileID=<? echo $recipientID; ?>"><b><? echo $recipientID; ?></b></a></font></td>
					</tr>
					<tr>
						<td><strong><font color="332616">Message from: </font></strong></td>
						<td><font color="332616"><a href="viewPublicProfile.php?profileID=<? echo $userID; ?>"><b><? echo $userID; ?></b></a></font></td>
					</tr>
                    <tr>
						<td><strong><font color="332616">Message Received: </font></strong></td>
						<td><font color="332616"><? echo $messageReceived; ?></font></td>
                 	</tr>
					<tr>
						<td><strong><font color="332616">Message: </font></strong></td>
						<td><font color="332616"><textarea name="messageText" cols="37" rows="6" id="messageText" maxlength="1000" /><? echo $messageText; ?></textarea><br />
                        <small>(Max length: 1,000 characters.)</small></font></td>
                 	</tr>
				</table>
                <input class="button" type="submit" value="Send Message" />
         		<input class="button" type="reset" value="Reset" />	
			</form>
            <!-- end send message form -->
				
			<!-- Other Options Display --> 
			<h3>Other Options (For Event <? echo $eventName; ?>)</h3>
			<?php
				if(checkUserOwnsEvent($userID, $eventID))
				{ ?>
					<p><b>
					<a href="editEvent.php?eventID=<? echo $eventID; ?>">edit event <? echo $eventName; ?></a> <br />
					<a href="viewAttendees.php?eventID=<? echo $eventID; ?>">view attendees to <? echo $eventName; ?></a><br />
					<a href="eventsByUser.php?recipientID=<? echo $recipientID; ?>">view other events by <? echo $recipientID; ?></a>
                    </b></p>
				<? }
				else
				{ ?>
					<p><b><a href="viewAttendees.php?eventID=<? echo $eventID; ?>">view attendees to <? echo $eventName; ?></a></b><br />
					<a href="eventsByUser.php?recipientID=<? echo $recipientID; ?>"><b>view other events by <? echo $recipientID; ?></b></a>
					</b></p>
				<? }
			?>
            <!-- end Other Options -->

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

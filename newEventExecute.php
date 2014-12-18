<?php	
	require_once("database.php");
	require_once('validateData.php');
	require_once('objEvent.php');
	require_once('objUser.php');
	
	//start session to pass user ID
	session_start();
	$userID = $_SESSION['valid_user'];
	
	// cannot see page if not logged in!
	checkValidUser();
	
	//create variables
	$eventName = $_POST['eventName'];
	$locationVenue = $_POST['locationVenue'];
	$date = $_POST['date'];
	$address = $_POST['address'];
	$city = $_POST['city'];
	$state = $_POST['state'];
	$zipCode = $_POST['zipCode'];
	$phoneNumber = $_POST['phoneNumber'];
	$eventDescription = $_POST['eventDescription'];
	$startTime = $_POST['startTime'];
	$endTime = $_POST['endTime'];
	
	// fix apostrophe issue for database
	$apostrophe = array("'"); 
	$quotation = array('"'); 
	$eventName2 = $eventName;
	$eventName = str_replace($apostrophe, "\'", $eventName);
	$eventName = str_replace($quotation, "&#34;", $eventName);
	$locationVenue2 = $locationVenue;
	$locationVenue = str_replace($apostrophe, "\'", $locationVenue);
	$locationVenue = str_replace($quotation, "&#34;", $locationVenue);
	$address2 = $address;
	$address = str_replace($apostrophe, "\'", $address);
	$address = str_replace($quotation, "&#34;", $address);
	$city2 = $city;
	$city = str_replace($apostrophe, "\'", $city);
	$city = str_replace($quotation, "&#34;", $city);
	$zipCode2 = $zipCode;
	$zipCode = str_replace($apostrophe, "\'", $zipCode);
	$zipCode = str_replace($quotation, "&#34;", $zipCode);
	$phoneNumber2 = $phoneNumber;
	$phoneNumber = str_replace($apostrophe, "\'", $phoneNumber);
	$phoneNumber = str_replace($quotation, "&#34;", $phoneNumber);
	$eventDescription2 = $eventDescription;
	$eventDescription = str_replace($apostrophe, "\'", $eventDescription);
	$eventDescription = str_replace($quotation, "&#34;", $eventDescription);
	
	try 
	{
		// check street address has no special characters
		if (hasSpecialCharacters2($address2))
		{
			throw new Exception('<span style="color: #FF0000;">The event address may only contain letters A-Z (upper or lowercase: a-z and A-Z), numbers (0-9), and some special characters (- \' . ,). Please try again.</span>');
		} // end if
		// check city has no special characters
		if (hasSpecialCharacters2($city2))
		{
			throw new Exception('<span style="color: #FF0000;">The city may only contain letters A-Z (upper or lowercase: a-z and A-Z), numbers (0-9), and some special characters (- \' . ,). Please try again.</span>');
		} // end if
		// check  zip code has only numbers and dashes
		if (preg_match( "@[^0-9-]+@i", $zipCode2))
		{
			throw new Exception('<span style="color: #FF0000;">The zip code may contain only numbers (0-9) and dashes (-). No spaces allowed. Please try again.</span>');
		} // end if
		// check phone number has only numbers
		if (preg_match( "@[^0-9]+@i", $phoneNumber2))
		{
			throw new Exception('<span style="color: #FF0000;">The phone number may contain only numbers (0-9), in form 1112223333. No spaces allowed. Please try again.</span>');
		} // end if
		
		//checks that forms are filled in
		if (!filled_out($_POST))
		{
			throw new Exception("<br /><span style=\"color:red;\">You have not filled out the form completely. Event not created. Try again!</span>");
		} // end if
	
		addEvent($userID, $eventName, $locationVenue, $date, $address, $city, $state, $zipCode, $phoneNumber, $eventDescription, $startTime, $endTime);
		// eventPlannerEventNotification($eventID, $userID, $eventName);
		header("location: dashboard.php");
	
	}
	catch(Exception $exception)
   	{
		// code detailed below   	
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title>uGather</title>
    <!-- main stylesheet -->
    <link rel="stylesheet" href="images/frontCSS.css" type="text/css" />
    <!-- JavaScript calendar -->
    <SCRIPT LANGUAGE="JavaScript" SRC="JavaScript/CalendarPopup.js"></SCRIPT>
    <!-- This prints out the default stylehseets used by the DIV style calendar.
         Only needed if you are using the DIV style popup -->
    <SCRIPT LANGUAGE="JavaScript">document.write(getCalendarStyles());</SCRIPT>
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
			displayNavigation("New Event");
		?>
	<!-- navigation ends-->	
	</div>						

	<!-- content starts -->
	<div id="content">

		<!-- main starts -->
		<div id="main">
        

		  <h2>Create a New Event</h2>
          	<?php 
				if ($exception)
					echo "<p>Error found: ",  $exception->getMessage(), "\n";
			?>
            <!-- start new event form -->
			<form name="newEventForm" method="post" action="newEventExecute.php">	
				<p>
				<label>Event Name</label>
				  <br />
				  <input name="eventName" type="text" id="eventName" size="70" maxlength="150" value="<? echo $eventName2; ?>">
				</p>
               <p>
				<label>Location</label>
					<br />
				    <input name="locationVenue" type="text" id="locationVenue" size="70" maxlength="150" value="<? echo $locationVenue2; ?>">
			  </p>
              <p>
				<label>Date</label>
					<br />
						<script language="JavaScript" id="js17">
						var now = new Date();
						var cal = new CalendarPopup();
						cal.showNavigationDropdowns();
						cal.addDisabledDates(null,formatDate(now,"yyyy-MM-dd"));
						</script>
						<input type="text" name="date" value="<? echo $date; ?>" maxlength="10" size="25" id="date" readonly />
						&nbsp;<a href="#" onClick="cal.select(document.forms[0].date,'anchor','yyyy/MM/dd'); return false;"
						title="cal.select(document.forms[0].date,'anchor','MM/dd/yyyy'); return false;" name="anchor" id="anchor"><b>pick date</b></A><br />
						<small>(Must be after today's date.)</small>
                    </script>
			  </p>	
              <p>
              	<label>Start Time</label>
                <br />
                <select name="startTime">
                <?
                	displaySelectedTimeSelect($startTime);
				?>
                </select>
              </p>
              <p>
              	<label>End Time</label>
                <br />
                <select name="endTime">
                <?
                	displaySelectedTimeSelect($endTime);
				?>
                </select>
              </p>
			   <p>
				<label>Venue Street Address</label>
					<br />
				    <input name="address" type="text" id="address" size="70" maxlength="175" value="<? echo $address2; ?>">
			  </p>	
			  <p>
					<label>City</label>
					<br />
				    <input name="city" type="text" id="city" maxlength="25" value="<? echo $city2; ?>">
			  </p>
              <p>
					<label>State</label>
					<br />
                    <select name="state">
                    <? 
                        displaySelectedStateSelect($state);
					?>
                    </select>
			  </p>
              <p>
				<label>Zip</label>
					<br />
				    <input name="zipCode" type="text" id="zipCode" maxlength="10" value="<? echo $zipCode2; ?>">
			  </p>
              <p>
				<label>Event Planner's Contact Number</label>
					<br />
				    <input name="phoneNumber" type="text" id="phoneNumber" maxlength="15" value="<? echo $phoneNumber2; ?>">
			  </p>
              <p>
				<label>Description</label>
					<br />
				    <textarea name="eventDescription" cols="55" rows="7" id="eventDescription"><? echo $eventDescription2; ?></textarea>
			  </p>
				<p class="no-border">
				  <input class="button" type="submit" value="Create New Event" />
         		<input class="button" type="reset" value="Reset"/>	
				</p>
			</form>
            <!-- end new event form -->				 

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

<?php	
	require_once('database.php');
	require_once('objCommon.php');
	require_once('objEvent.php');
	require_once('objUser.php');
	
	session_start();
	$userID = $_SESSION['valid_user'];
	
	// cannot see page if not logged in!
	checkValidUser();
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
          <!-- start new event form -->
			<form name="newEventForm" method="post" action="newEventExecute.php">	
				<p>
				<label>Event Name</label>
				  <br />
				  <input name="eventName" type="text" id="eventName" size="70" maxlength="150">
				</p>
               <p>
				<label>Location</label>
					<br />
				    <input name="locationVenue" type="text" id="locationVenue" size="70" maxlength="150">
			  </p>
              <p>
				<label>Date (hit link to right of box to select!)</label>
					<br />
					<script language="JavaScript" ID="js17">
					var now = new Date();
					var cal = new CalendarPopup();
					cal.showNavigationDropdowns();
					cal.addDisabledDates(null,formatDate(now,"yyyy-MM-dd"));
					</script>
					<input type="text" name="date" value="" size="25" maxlength="10" id="date" readonly />
					&nbsp;<a href="#" onClick="cal.select(document.forms[0].date,'anchor','yyyy/MM/dd'); return false;"
                    title="cal.select(document.forms[0].date,'anchor','MM/dd/yyyy'); return false;" Nname="anchor" id="anchor"><b>pick date</b></A><br />
                    <small>(Must be after today's date.)</small>
			  </p>
              <p>
              	<label>Start Time</label>
                <br />
                <select name="startTime">
                <?
                	displayTimeSelect();
				?>
                </select>
              </p>
              <p>
              	<label>End Time</label>
                <br />
                <select name="endTime">
                <?
                	displayTimeSelect();
                ?>
				</select>
              </p>
			   <p>
				<label>Venue Street Address</label>
					<br />
				    <input name="address" type="text" id="address" size="70" maxlength="175">
			  </p>	
			  <p>
				<label>City</label>
					<br />
				    <input name="city" type="text" id="city" maxlength="25">
			  </p>
              <p>
				<label>State</label>
					<br />
				<select name="state"> 
                <?
                    displayStateSelect(); 
				?>
				</select>
			  </p>
              <p>
				<label>Zip</label>
					<br />
				    <input name="zipCode" type="text" id="zipCode" maxlength="10">
			  </p>
              <p>
				<label>Event Planner's Contact Number</label>
					<br />
				    <input name="phoneNumber" type="text" id="phoneNumber" maxlength="15">
			  </p>
              <p>
				<label>Description</label>
					<br />
				    <textarea name="eventDescription" cols="55" rows="7" id="eventDescription"></textarea>
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

<DIV ID="testdiv1" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></DIV>


</body>
</html>

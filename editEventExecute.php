<?php
	require_once('objEvent.php');
	require_once("objUser.php");
	include("database.php");
	
	mysql_select_db($database_name) or die("Error: Cannot select the database.");
	
	require_once('validateData.php');
	
	// start session
	session_start();
	$userID = $_SESSION['valid_user'];
	$eventID = $_POST['eventID'];
	
	// cannot see if not logged in!
	checkValidUser();
	
	// cannot see this page if does not own event!
	if(!checkUserOwnsEvent($userID, $eventID))
		header('location: dashboard.php');
	
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
	
	// data validation
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
		
		if (!filled_out($_POST))
		{
			throw new Exception("<br /><span style=\"color:red;\">You have not filled out the form completely. Event not created. Try again!</span>");
		}
		
		updateExistingEvent($userID, $eventID, $eventName, $locationVenue, $date, $address, $city, $state, $zipCode, $phoneNumber, $eventDescription, $startTime, $endTime);
		
		// send notification of edit to event planner and attendees
		$result = getPlannerDetails($eventID);
		if (is_array($result))
		{
			foreach ($result as $rows)
			{
				$plannerFirstName = $rows['firstName'];;
				$plannerLastName = $rows['lastName'];;
			}
	 	}
		eventPlannerEditEventNotification($eventID, $userID, $eventName, $plannerFirstName, $plannerLastName);
		attendeeEditEventNotification($eventID, $eventName); 
		
		header("location: viewEvent.php?eventID=$eventID");
	}
	catch (Exception $exception)
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
					?> <h2>Edit Event: <? echo $rows['eventName']; ?></h2>
					<?php 
                    if ($exception)
                        echo "<p>Error found: ",  $exception->getMessage(), "\n";
                    ?>
                    <!-- start edit event form -->
                    <form name="editEventForm" method="post" action="editEventExecute.php?eventID=<? echo $eventID; ?>">
                   		<input name="event" type="hidden" id="event" value="<?php echo $event = getAllEventDetails($eventID); ?>">
                		<input name="eventID" type="hidden" id="eventID" value="<?php echo $eventID; ?>">
					 <table width="460" border="1">                    
                          	<tr>
                            	<td>
                                	<strong>Current Event Name: </strong>
                                </td>
								<td>
                                	<font color="332616"><? echo $rows['eventName']; ?></font>
                                </td>
                            </tr>
                            <tr>
                            	<td>
                                	<strong>New Event Name:</strong>
                                </td>
								<td>
                                	<input name="eventName" type="text" id="eventName" size="30" maxlength="150"  value="<? echo $eventName2; ?>">
                              	</td>
                            </tr>
                            
                            <tr>
                            	<td>
                                	<strong>Current Location: </strong>
                                </td>
								<td>
                                	<font color="332616"><? echo $rows['locationVenue']; ?></font>
                                </td>
                            </tr>
                            <tr>
                            	<td>
                                	<strong>New Location: </strong>
                                </td>
								<td>
                                	<input name="locationVenue" type="text" id="locationVenue" value="<? echo $locationVenue2; ?>" size="30" maxlength="150" >
                              	</td>
                            </tr>
                            
                            <tr>
                            	<td>
                                	<strong>Current Date: </strong>
                                </td>
								<td>
                                	<font color="332616"><? echo $rows['date']; ?></font>
                                </td>
                            </tr>
                            <tr>
                            	<td>
                                	<strong>New Date: </strong>
                                </td>
								<td>
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
                              	</td>
                            </tr>
                            
                            <tr>
                            	<td>
                                	<strong>Current Start Time: </strong>
                                </td>
								<td>
                                	<font color="332616"><? echo $rows['startTime']; ?></font>
                                </td>
                            </tr>
                             <tr>
                            	<td>
                                	<strong>New Start Time: </strong>
                                </td>
								<td>
                                	<font color="332616">
									<select name="startTime">
                                    	<?
                							displaySelectedTimeSelect($startTime);
										?>	
                                   	</select>
               					 </font>
                                </td>
                            </tr>
                            
                            <tr>
                            	<td>
                                	<strong>Current End Time: </strong>
                                </td>
								<td>
                                	<font color="332616"><? echo $rows['endTime']; ?></font>
                                </td>
                            </tr>
                             <tr>
                            	<td>
                                	<strong>New End Time: </strong>
                                </td>
								<td>
                                	<font color="332616">
                                    <select name="endTime">
										<?
                							displaySelectedTimeSelect($endTime);
										?>	
                                   	</select>
               					 	</font>
                                </td>
                            </tr>
                            
                            <tr>
                            	<td>
                                	<strong>Current Address: </strong>
                                </td>
								<td>
                                	<font color="332616"><a href="http://mapof.it/<? echo $rows['address']; ?> <? echo $rows['city']; ?>, <? echo $rows['state']; ?> <? echo $rows['zipCode']; ?>" target="_blank"><? echo $rows['address']; ?></a></font>
                                </td>
                            </tr>
                            <tr>
                            	<td>
                                	<strong>New Address: </strong>
                                </td>
								<td>
                                	<input name="address" type="text" id="address" value="<? echo $address2; ?>" size="30" maxlength="175" >
                              	</td>
                            </tr>
                            
                            <tr>
                            	<td>
                                	<strong>Current City: </strong>
                                </td>
								<td>
                                	<font color="332616"><? echo $rows['city']; ?></font>
                                </td>
                            </tr>
                            <tr>
                            	<td>
                                	<strong>New City: </strong>
                                </td>
								<td>
                                	<input name="city" type="text" id="city" value="<? echo $city2; ?>" size="30"  maxlength="25" >
                              	</td>
                            </tr>
                            
                            <tr>
                            	<td>
                                	<strong>Current State: </strong>
                                </td>
								<td>
                                	<font color="332616"><? echo $state; ?></font>
                                </td>
                            </tr>
                            <tr>
                            	<td>
                                	<strong>New State: </strong>
                                </td>
								<td>
                                    <select name="state"> 
                                    	<? if(isEqual($state,"AL")) { ?>
                                        	<option value="AL" selected>Alabama</option> 
                                        <? } else { ?>
                                        	<option value="AL">Alabama</option> 
                                        <? } if(isEqual($state,"AK")) { ?>
                                        	<option value="AK" selected>Alaska</option> 
                                        <? } else { ?>
                                        	<option value="AK">Alaska</option> 
										<? } if(isEqual($state,"AZ")) { ?>
                                        	<option value="AZ" selected>Arizona</option> 
                                        <? } else { ?>
                                        	<option value="AZ">Arizona</option>
										<? } if(isEqual($state,"AR")) { ?>
                                        	<option value="AR" selected>Arkansas</option> 
                                        <? } else { ?>
                                        	<option value="AR">Arkansas</option>
										<? } if(isEqual($state,"CA")) { ?>
                                        	<option value="CA" selected>California</option> 
                                        <? } else { ?>
                                        	<option value="CA">California</option>
										<? } if(isEqual($state,"CO")) { ?>
                                        	<option value="CO" selected>Colorado</option> 
                                        <? } else { ?>
                                        	<option value="CO">Colorado</option> 
										<? } if(isEqual($state,"CT")) { ?>
                                        	<option value="CT" selected>Connecticut</option> 
                                        <? } else { ?>
                                        	<option value="CT">Connecticut</option>
										<? } if(isEqual($state,"DE")) { ?>
                                        	<option value="DE" selected>Delaware</option> 
                                        <? } else { ?>
                                        	<option value="DE">Delaware</option> 
										<? } if(isEqual($state,"DC")) { ?>
                                        	<option value="DC" selected>District of Columbia</option> 
                                        <? } else { ?>
                                        	<option value="DC">District of Columbia</option>
										<? } if(isEqual($state,"FL")) { ?>
                                        	<option value="FL" selected>Florida</option> 
                                        <? } else { ?>
                                        	<option value="FL">Florida</option> 
										<? } if(isEqual($state,"GA")) { ?>
                                        	<option value="GA" selected>Georgia</option> 
                                        <? } else { ?>
                                        	<option value="GA">Georgia</option> 
										<? } if(isEqual($state,"HI")) { ?>
                                        	<option value="HI" selected>Hawaii</option> 
                                        <? } else { ?>
                                        	<option value="HI">Hawaii</option>
										<? } if(isEqual($state,"ID")) { ?>
                                        	<option value="ID" selected>Idaho</option> 
                                        <? } else { ?>
                                        	<option value="ID">Idaho</option> 
										<? } if(isEqual($state,"IL")) { ?>
                                        	<option value="IL" selected>Illinois</option> 
                                        <? } else { ?>
                                         	<option value="IL">Illinois</option>
										<? } if(isEqual($state,"IN")) { ?>
                                        	<option value="IN" selected>Indiana</option> 
                                        <? } else { ?>
                                        	<option value="IN">Indiana</option>
										<? } if(isEqual($state,"IA")) { ?>
                                        	<option value="IA" selected>Iowa</option> 
                                        <? } else { ?>
                                        	<option value="IA">Iowa</option> 
										<? } if(isEqual($state,"KS")) { ?>
                                        	<option value="KS" selected>Kansas</option> 
                                        <? } else { ?>
                                        	<option value="KS">Kansas</option> 
										<? } if(isEqual($state,"KY")) { ?>
                                        	<option value="KY" selected>Kentucky</option> 
                                        <? } else { ?>
                                        	<option value="KY">Kentucky</option> 
										<? } if(isEqual($state,"LA")) { ?>
                                        	<option value="LA" selected>Louisiana</option> 
                                        <? } else { ?>
                                        	<option value="LA">Louisiana</option>
										<? } if(isEqual($state,"ME")) { ?>
                                        	<option value="ME" selected>Maine</option> 
                                        <? } else { ?>
                                        	<option value="ME">Maine</option> 
										<? } if(isEqual($state,"MD")) { ?>
                                        	<option value="MD" selected>Maryland</option> 
                                        <? } else { ?>
                                        	<option value="MD">Maryland</option> 
										<? } if(isEqual($state,"MA")) { ?>
                                        	<option value="MA" selected>Massachusetts</option> 
                                        <? } else { ?>
                                        	<option value="MA">Massachusetts</option> 
										<? } if(isEqual($state,"MI")) { ?>
                                        	<option value="MI" selected>Michigan</option> 
                                        <? } else { ?>
                                        	<option value="MI">Michigan</option> 
										<? } if(isEqual($state,"MN")) { ?>
                                        	<option value="MN" selected>Minnesota</option> 
                                        <? } else { ?>
                                        	<option value="MN">Minnesota</option> 
										<? } if(isEqual($state,"MS")) { ?>
                                        	<option value="MS" selected>Mississippi</option> 
                                        <? } else { ?>
                                        	<option value="MS">Mississippi</option>
										<? } if(isEqual($state,"MO")) { ?>
                                        	<option value="MO" selected>Missouri</option> 
                                        <? } else { ?>
                                        	<option value="MO">Missouri</option>
										<? } if(isEqual($state,"MT")) { ?>
                                        	<option value="MT" selected>Montana</option> 
                                        <? } else { ?>
                                        	<option value="MT">Montana</option>
										<? } if(isEqual($state,"NE")) { ?>
                                        	<option value="NE" selected>Nebraska</option> 
                                        <? } else { ?>
                                        	<option value="NE">Nebraska</option>
										<? } if(isEqual($state,"NV")) { ?>
                                        	<option value="NV" selected>Nevada</option> 
                                        <? } else { ?>
                                        	<option value="NV">Nevada</option> 
										<? } if(isEqual($state,"NH")) { ?>
                                        	<option value="NH" selected>New Hampshire</option> 
                                        <? } else { ?>
                                        	<option value="NH">New Hampshire</option>
										<? } if(isEqual($state,"NJ")) { ?>
                                        	<option value="NJ" selected>New Jersey</option> 
                                        <? } else { ?>
                                        	<option value="NJ">New Jersey</option> 
										<? } if(isEqual($state,"NM")) { ?>
                                        	<option value="NM" selected>New Mexico</option> 
                                        <? } else { ?>
                                        	<option value="NM">New Mexico</option>
										<? } if(isEqual($state,"NY")) { ?>
                                        	<option value="NY" selected>New York</option> 
                                        <? } else { ?>
                                        	<option value="NY">New York</option> 
										<? } if(isEqual($state,"NC")) { ?>
                                        	<option value="NC" selected>North Carolina</option> 
                                        <? } else { ?>
                                        	<option value="NC">North Carolina</option>
										<? } if(isEqual($state,"ND")) { ?>
                                        	<option value="ND" selected>North Dakota</option> 
                                        <? } else { ?>
                                        	<option value="ND">North Dakota</option> 
										<? } if(isEqual($state,"OH")) { ?>
                                        	<option value="OH" selected>Ohio</option> 
                                        <? } else { ?>
                                        	<option value="OH">Ohio</option>
										<? } if(isEqual($state,"OK")) { ?>
                                        	<option value="OK" selected>Oklahoma</option> 
                                        <? } else { ?>
                                        	<option value="OK">Oklahoma</option>
										<? } if(isEqual($state,"OR")) { ?>
                                        	<option value="OR" selected>Oregon</option> 
                                        <? } else { ?>
                                        	<option value="OR">Oregon</option>
										<? } if(isEqual($state,"PA")) { ?>
                                        	<option value="PA" selected>Pennsylvania</option> 
                                        <? } else { ?>
                                        	<option value="PA">Pennsylvania</option>
										<? } if(isEqual($state,"RI")) { ?>
                                        	<option value="RI" selected>Rhode Island</option> 
                                        <? } else { ?>
                                        	<option value="RI">Rhode Island</option>
										<? } if(isEqual($state,"SC")) { ?>
                                        	<option value="SC" selected>South Carolina</option> 
                                        <? } else { ?>
                                        	<option value="SC">South Carolina</option>
										<? } if(isEqual($state,"SD")) { ?>
                                        	<option value="SD" selected>South Dakota</option> 
                                        <? } else { ?>
                                        	<option value="SD">South Dakota</option>
										<? } if(isEqual($state,"TN")) { ?>
                                        	<option value="TN" selected>Tennessee</option> 
                                        <? } else { ?>
                                        	<option value="TN">Tennessee</option>
										<? } if(isEqual($state,"TX")) { ?>
                                        	<option value="TX" selected>Texas</option> 
                                        <? } else { ?>
                                        	<option value="TX">Texas</option>
										<? } if(isEqual($state,"UT")) { ?>
                                        	<option value="UT" selected>Utah</option> 
                                        <? } else { ?>
                                        	<option value="UT">Utah</option> 
										<? } if(isEqual($state,"VT")) { ?>
                                        	<option value="VT" selected>Vermont</option> 
                                        <? } else { ?>
                                        	<option value="VT">Vermont</option>
										<? } if(isEqual($state,"VA")) { ?>
                                        	<option value="VA" selected>Virginia</option> 
                                        <? } else { ?>
                                        	<option value="VA">Virginia</option>
										<? } if(isEqual($state,"WA")) { ?>
                                        	<option value="WA" selected>Washington</option> 
                                        <? } else { ?>
                                        	<option value="WA">Washington</option>
										<? } if(isEqual($state,"WV")) { ?>
                                        	<option value="WV" selected>West Virginia</option> 
                                        <? } else { ?>
                                        	<option value="WV">West Virginia</option>
										<? } if(isEqual($state,"WI")) { ?>
                                        	<option value="WI" selected>Wisconsin</option> 
                                        <? } else { ?>
                                        	<option value="WI">Wisconsin</option> 
										<? } if(isEqual($state,"WY")) { ?>
                                        	<option value="WY" selected>Wyoming</option> 
                                        <? } else { ?>
                                        	<option value="WY">Wyoming</option> 	
                                        <? } ?>
                                    </select>
                              	</td>
                            </tr>
                            
                             <tr>
                            	<td>
                                	<strong>Current Zip Code: </strong>
                                </td>
								<td>
                                	<font color="332616"><? echo $rows['zipCode']; ?></font>
                                </td>
                            </tr>
                            <tr>
                            	<td>
                                	<strong>New Zip Code: </strong>
                                </td>
								<td>
                                	<input name="zipCode" type="text" id="zipCode" value="<? echo $zipCode2; ?>" size="30" maxlength="10" >
                              	</td>
                            </tr>
                            
                            <tr>
                            	<td>
                                	<strong>Current Phone Number: </strong>
                                </td>
								<td>
                                	<font color="332616"><? echo $rows['phoneNumber']; ?></font>
                                </td>
                            </tr>
                            <tr>
                            	<td>
                                	<strong>New Phone Number: </strong>
                                </td>
								<td>
                                	<input name="phoneNumber" type="text" id="phoneNumber" value="<? echo $phoneNumber2; ?>" size="30" maxlength="15" >
                              	</td>
                            </tr>
                            
                            <tr>
                            	<td>
                                	<strong>Current Description: </strong>
                                </td>
								<td>
                                	<font color="332616"><? echo $rows['eventDescription']; ?></font>
                                </td>
                            </tr>
                            <tr>
                            	<td>
                                	<strong>New Description: </strong>
                                </td>
								<td>
                                    <textarea name="eventDescription" cols="35" rows="7" id="eventDescription"><? echo $eventDescription2; ?></textarea>
                              	</td>
                            </tr>
                        </table>
                    <input name="Edit Event" type="submit" id="Edit Event" value="Edit Event">
                    </form>
                    <!-- end edit event form -->
				<? }
			}
			else 
			{
				echo "Error";
			}
       	?>

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

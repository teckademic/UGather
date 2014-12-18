<?php
	require_once('database.php');
	require_once('objCommon.php');
	
	// function getEventDetailsRegisteredAsc
	function getEventDetailsRegisteredAsc($fieldsNeeded, $orderBy, $attendeeID)
	{
		$sql = "SELECT ".$fieldsNeeded." FROM Event WHERE date(date) >= now() AND eventID IN (SELECT eventID FROM eventAttendees WHERE userID = '".$attendeeID."') ORDER BY ".$orderBy." ASC";
		$result = mysql_query($sql);
		return $result;
	} // end function getEventDetailsRegisteredAsc
	
	// function getEventDetailsRegisteredDesc
	function getEventDetailsRegisteredDesc($fieldsNeeded, $orderBy, $attendeeID)
	{
		$sql = "SELECT ".$fieldsNeeded." FROM Event WHERE date(date) >= now() AND eventID IN (SELECT eventID FROM eventAttendees WHERE userID = '".$attendeeID."') ORDER BY ".$orderBy." DESC";
		$result = mysql_query($sql);
		return $result;
	} // end function getEventDetailsRegisteredDesc
	
	// function getEventDetailsRegisteredAscLimit
	function getEventDetailsRegisteredAscLimit($fieldsNeeded, $orderBy, $attendeeID, $set_limit, $limit)
	{
		$sql = "SELECT ".$fieldsNeeded." FROM Event WHERE date(date) >= now() AND eventID IN (SELECT eventID FROM eventAttendees WHERE userID = '".$attendeeID."') ORDER BY ".$orderBy." ASC LIMIT $set_limit, $limit";
		$result = mysql_query($sql);
		return $result;
	} // end function getEventDetailsRegisteredAscLimit
	
	// function getEventDetailsRegisteredDescLimit
	function getEventDetailsRegisteredDescLimit($fieldsNeeded, $orderBy, $attendeeID, $set_limit, $limit)
	{
		$sql = "SELECT ".$fieldsNeeded." FROM Event WHERE date(date) >= now() AND eventID IN (SELECT eventID FROM eventAttendees WHERE userID = '".$attendeeID."') ORDER BY ".$orderBy." DESC LIMIT $set_limit, $limit";
		$result = mysql_query($sql);
		return $result;
	} // end function getEventDetailsRegisteredDescLimit
	
	// get all fields from event planner's user row
	function getAllEventPlannerDetails($eventID)
	{
  		$result = mysql_query("SELECT * FROM Event WHERE eventID = '".$eventID."'");
		if (!$result) 
		{
			throw new Exception('No records found.');
		}
		if(!$result)
		{
			return false;
			}
		$num_rows = mysql_num_rows($result);
		if($num_rows == 0) 
		{
			return false;
		}
		$result = resultToArray($result);
		return $result;
	} // end function getAllEventPlannerDetails
	
	// add a new Attendee into the eventAttendees table
	function addAttendee($eventID, $userID, $status)
	{
		$result = mysql_query("select * from eventAttendees where (eventID = '".$eventID."' AND userID = '".$userID."')");
		if (!$result) 
		{
			throw new Exception('Could not execute query');
		}
		$num_rows = mysql_num_rows($result);
		if ($num_rows > 0) 
		{
			throw new Exception('You are already register for this event.');
		}
			
		$result = mysql_query("INSERT INTO eventAttendees(eventID, userID, status) VALUES ('".$eventID."', '".$userID."', '".$status."')");
		if (!$result) 
		{
			echo mysql_error();
			//throw new Exception('Could not register you in database - please try again later.');
		}
		return true;	
	} // end function addAttendee
	
	// send attendee confirmation of their registration to an event
	function attendeeRegistrationNotification($eventID, $userID, $attenID, $status, $eventName, $attenFirstName, $attenLastName) 
	{
		$result = mysql_query("select emailAddress from User where userID = '".$attenID."'");
		$num_rows = mysql_num_rows($result);
		
		if (!$result) 
		{
		 	throw new Exception('Could not find email address.');
		} 
		else if ($num_rows == 0) 
		{
			throw new Exception('Could not find the user!');
		} 
		else 
		{
			$apostrophe 		= array("\'"); // for fixing apostrophe issue
			$attenFirstName 	= str_replace($apostrophe, "'", $attenFirstName);
			$attenLastName 		= str_replace($apostrophe, "'", $attenLastName);
			$eventName 			= str_replace($apostrophe, "'", $eventName);
			
		 	$row = mysql_fetch_object($result);
			$email = $row -> emailAddress;

			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: uGather <ugather@ugather.info>' . "\r\n";
						
			$sub = "uGather: ".$attenID.": Event Registration for ".$eventName."";
			$mesg = "<html>
						<head>
							<title>You have registered for an event!</title>
						</head>
						<body>
						<h2 style=\"font-family: 'Trebuchet MS', 'Helvetica Neue', Arial, Sans-serif; font-weight: bold; padding: 10px 0 5px 5px; color: #444; font-size: 2.5em; color: #88AC0B; border-bottom: 1px solid #E4F2C8; letter-spacing: -2px; margin-left: 5px; \">
uGather Event Registration</h2>
						<p style=\"font: 12px/170% 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; color: #6B6B6B; padding: 12px 10px; \">Hello, ".$attenFirstName." ".$attenLastName."! </p>
						
						<p style=\"font: 12px/170% 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; color: #6B6B6B; padding: 12px 10px; \">You have changed your registration status for the event <a href=\"http://www.ugather.info/viewEvent.php?eventID=".$eventID."\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">".$eventName."</a> at  <a href=\"http://www.ugather.info\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">uGather</a>. <br />
						Your registration status for ".$eventName." is: ".$status.".  </p>
						
						<p style=\"font: 12px/170% 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; color: #6B6B6B; padding: 12px 10px; \">You can find the event page for ".$eventName." here: <a href=\"http://www.ugather.info/viewEvent.php?eventID=".$eventID."\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">view event ".$eventName."</a>. You can view all of the attendees to that event here: <a href=\"http://www.ugather.info/viewAttendees.php?eventID=".$eventID."\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">view attendees to ".$eventName."</a>. </p>
						
						<p style=\"font: 12px/170% 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; color: #6B6B6B; padding: 12px 10px; \">This is an automatically generated email from <a href=\"http://ugather.info\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">uGather</a>. Please do not respond. Thank you.</p> 
						</body>
						</html>";
	
			if (mail($email, $sub, $mesg, $headers)) 
			{
				return true;
			} 
			else 
			{
				throw new Exception('Could not send email.');
			}
    	}
	} // end function attendeeRegistrationEditNotification
	
	// send attendee confirmation of their registration to an event
	function attendeeRegistrationEditNotification($eventID, $userID, $attenID, $status, $eventName, $attenFirstName, $attenLastName) 
	{
		$result = mysql_query("select emailAddress from User where userID = '".$attenID."'");
		$num_rows = mysql_num_rows($result);
		
		if (!$result) 
		{
		 	throw new Exception('Could not find email address.');
		} 
		else if ($num_rows == 0) 
		{
			throw new Exception('Could not find the user!');
		} 
		else 
		{
			$apostrophe 		= array("\'"); // for fixing apostrophe issue
			$attenFirstName 	= str_replace($apostrophe, "'", $attenFirstName);
			$attenLastName 		= str_replace($apostrophe, "'", $attenLastName);
			$eventName 			= str_replace($apostrophe, "'", $eventName);
			
		 	$row = mysql_fetch_object($result);
			$email = $row -> emailAddress;

			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: uGather <ugather@ugather.info>' . "\r\n";
						
			$sub = "uGather: ".$attenID.": Event Registration for ".$eventName."";
			$mesg = "<html>
						<head>
							<title>You have registered for an event!</title>
						</head>
						<body>
						<h2 style=\"font-family: 'Trebuchet MS', 'Helvetica Neue', Arial, Sans-serif; font-weight: bold; padding: 10px 0 5px 5px; color: #444; font-size: 2.5em; color: #88AC0B; border-bottom: 1px solid #E4F2C8; letter-spacing: -2px; margin-left: 5px; \">uGather Event Registration Change</h2>
						<p style=\"font: 12px/170% 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; color: #6B6B6B; padding: 12px 10px; \">Hello, ".$attenFirstName." ".$attenLastName."! </p>
						
						<p style=\"font: 12px/170% 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; color: #6B6B6B; padding: 12px 10px; \">You have changed your registration status for the event <a href=\"http://www.ugather.info/viewEvent.php?eventID=".$eventID."\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">".$eventName."</a> at  <a href=\"http://www.ugather.info\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">uGather</a>. <br />
						Your registration status for ".$eventName." is: ".$status.".  </p>
						
						<p style=\"font: 12px/170% 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; color: #6B6B6B; padding: 12px 10px; \">You can find the event page for ".$eventName." here: <a href=\"http://www.ugather.info/viewEvent.php?eventID=".$eventID."\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">view event ".$eventName."</a>. You can view all of the attendees to that event here: <a href=\"http://www.ugather.info/viewAttendees.php?eventID=".$eventID."\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">view attendees to ".$eventName."</a>. </p>
						
						<p style=\"font: 12px/170% 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; color: #6B6B6B; padding: 12px 10px; \">This is an automatically generated email from <a href=\"http://ugather.info\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">uGather</a>. Please do not respond. Thank you.</p> 
						</body>
						</html>";
	
			if (mail($email, $sub, $mesg, $headers)) 
			{
				return true;
			} 
			else 
			{
				throw new Exception('Could not send email.');
			}
    	}
	}
	
	// notify the event planner of a new attendee to their event
	function eventPlannerRegistrationEditNotification($eventID, $userID, $status, $eventName, $userFirstName, $userLastName, $plannerFirstName, $plannerLastName) 
	{
		$result = mysql_query("select emailAddress from User where userID in (select userID from Event where eventID = '".$eventID."')");
		$num_rows = mysql_num_rows($result);
		if (!$result) 
		{
		 	throw new Exception('Could not find email address for that event.');
		} 
		else if ($num_rows == 0) 
		{
			throw new Exception('Could not find event planner for event.');
		} 
		else 
		{
			$apostrophe 		= array("\'"); // for fixing apostrophe issue
			$userFirstName 		= str_replace($apostrophe, "'", $userFirstName);
			$userLastName 		= str_replace($apostrophe, "'", $userLastName);
			$plannerFirstName 		= str_replace($apostrophe, "'", $plannerFirstName);
			$plannerLastName 		= str_replace($apostrophe, "'", $plannerLastName);
			$eventName 			= str_replace($apostrophe, "'", $eventName);
			
		 	$row = mysql_fetch_object($result);
	
			$email = $row -> emailAddress;
			
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: uGather <ugather@ugather.info>' . "\r\n";
						
			$sub = "uGather: ".$userID.": Event Registration for ".$eventName."";
			$mesg = "<html>
						<head>
							<title>Someone has registered for your event!</title>
						</head>
						<body>
						<h2 style=\"font-family: 'Trebuchet MS', 'Helvetica Neue', Arial, Sans-serif; font-weight: bold; padding: 10px 0 5px 5px; color: #444; font-size: 2.5em; color: #88AC0B; border-bottom: 1px solid #E4F2C8; letter-spacing: -2px; margin-left: 5px; \">uGather Event Registration Change</h2>
						<p style=\"font: 12px/170% 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; color: #6B6B6B; padding: 12px 10px; \">Hello, ".$plannerFirstName." ".$plannerLastName."! </p>
						
						<p style=\"font: 12px/170% 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; color: #6B6B6B; padding: 12px 10px; \">The user ".$userID." (".$userFirstName." ".$userLastName.") has changed their registration status for your event, <a href=\"http://www.ugather.info/viewEvent.php?eventID=".$eventID."\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">".$eventName."</a>, at  <a href=\"http://www.ugather.info\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">uGather</a>. <br />
						".$userID."'s registration status for ".$eventName." is: ".$status.".  </p>
						
						<p style=\"font: 12px/170% 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; color: #6B6B6B; padding: 12px 10px; \">You can find the event page for ".$eventName." here: <a href=\"http://www.ugather.info/viewEvent.php?eventID=".$eventID."\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">view event ".$eventName."</a>. You can view all of the attendees to your event here: <a href=\"http://www.ugather.info/viewAttendees.php?eventID=".$eventID."\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">view attendees to ".$eventName."</a>. </p>
						
						<p style=\"font: 12px/170% 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; color: #6B6B6B; padding: 12px 10px; \">This is an automatically generated email from <a href=\"http://ugather.info\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">uGather</a>. Please do not respond. Thank you.</p> 
						</body>
						</html>";
	
			if (mail($email, $sub, $mesg, $headers)) 
			{
				return true;
			} 
			else 
			{
				throw new Exception('Could not send email.');
			}
    	}
	} // end function eventPlannerRegistrationEditNotification
	
	// notify the event planner of a new attendee to their event
	function eventPlannerRegistrationNotification($eventID, $userID, $status, $eventName, $userFirstName, $userLastName, $plannerFirstName, $plannerLastName) 
	{
		$result = mysql_query("select emailAddress from User where userID in (select userID from Event where eventID = '".$eventID."')");
		$num_rows = mysql_num_rows($result);
		if (!$result) 
		{
		 	throw new Exception('Could not find email address for that event.');
		} 
		else if ($num_rows == 0) 
		{
			throw new Exception('Could not find event planner for event.');
		} 
		else 
		{
			$apostrophe 		= array("\'"); // for fixing apostrophe issue
			$userFirstName 		= str_replace($apostrophe, "'", $userFirstName);
			$userLastName 		= str_replace($apostrophe, "'", $userLastName);
			$plannerFirstName 		= str_replace($apostrophe, "'", $plannerFirstName);
			$plannerLastName 		= str_replace($apostrophe, "'", $plannerLastName);
			$eventName 			= str_replace($apostrophe, "'", $eventName);
			
		 	$row = mysql_fetch_object($result);
	
			$email = $row -> emailAddress;
			
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: uGather <ugather@ugather.info>' . "\r\n";
						
			$sub = "uGather: ".$userID.": Event Registration for ".$eventName."";
			$mesg = "<html>
						<head>
							<title>Someone has registered for your event!</title>
						</head>
						<body>
						<h2 style=\"font-family: 'Trebuchet MS', 'Helvetica Neue', Arial, Sans-serif; font-weight: bold; padding: 10px 0 5px 5px; color: #444; font-size: 2.5em; color: #88AC0B; border-bottom: 1px solid #E4F2C8; letter-spacing: -2px; margin-left: 5px; \">uGather Event Registration</h2>
						<p style=\"font: 12px/170% 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; color: #6B6B6B; padding: 12px 10px; \">Hello, ".$plannerFirstName." ".$plannerLastName."! </p>
						
						<p style=\"font: 12px/170% 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; color: #6B6B6B; padding: 12px 10px; \">The user ".$userID." (".$userFirstName." ".$userLastName.") has registered for your event, <a href=\"http://www.ugather.info/viewEvent.php?eventID=".$eventID."\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">".$eventName."</a>, at  <a href=\"http://www.ugather.info\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">uGather</a>. <br />
						".$userID."'s registration status for ".$eventName." is: ".$status.".  </p>
						
						<p style=\"font: 12px/170% 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; color: #6B6B6B; padding: 12px 10px; \">You can find the event page for ".$eventName." here: <a href=\"http://www.ugather.info/viewEvent.php?eventID=".$eventID."\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">view event ".$eventName."</a>. You can view all of the attendees to your event here: <a href=\"http://www.ugather.info/viewAttendees.php?eventID=".$eventID."\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">view attendees to ".$eventName."</a>. </p>
						
						<p style=\"font: 12px/170% 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; color: #6B6B6B; padding: 12px 10px; \">This is an automatically generated email from <a href=\"http://ugather.info\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">uGather</a>. Please do not respond. Thank you.</p> 
						</body>
						</html>";
	
			if (mail($email, $sub, $mesg, $headers)) 
			{
				return true;
			} 
			else 
			{
				throw new Exception('Could not send email.');
			}
    	}
	}
	
	// get status of attendee's registration
	function getAttendeeRegistrationStatus($eventID, $userID)
	{
		$result = mysql_query("SELECT * FROM eventAttendees WHERE userID = '".$userID."' AND eventID = '".$eventID."'");
		
		$num_rows = mysql_num_rows($result);
		
		if ($num_rows == 0)
		{
			throw new Exception('There was an error obtaining your registration status for that event!');	
		}
		else
		{
			$result = resultToArray($result);
			
			if (is_array($result)) 
			{
	    		foreach ($result as $rows)  
				{
					return $rows['status'];
               	}
            }
		}
	}
	
	// return event planner first and last names
	function getEventPlannerName($eventID)
	{
		// return true or error message
		//get the name of the Event	
	  	$result = mysql_query("select firstName, lastName from User where userID in (select userID from Event where eventID = '".$eventID."')");
		if (!$result) 
		{
			throw new Exception('No records found.');
		}
		if(!$result)
		{
			return false;
		}
		$num_rows = mysql_num_rows($result);
		if($num_rows == 0) 
		{
			return false;
		}
		$result = resultToArray($result);
		return $result;
	}
		
	// isRegistered function returns true if user is registered for the event
	function isRegistered($eventID, $userID)
	{
		$result = mysql_query("SELECT * FROM eventAttendees WHERE userID = '".$userID."' AND eventID = '".$eventID."'");
		
		$num_rows = mysql_num_rows($result);
		
		if ($num_rows > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	} // end function isRegistered
	
	// function to update the event details based on given parameters and eventID
	function updateAttendee($eventID, $userID, $status)
	{
		// update the following fields in the database:
		$result = mysql_query("UPDATE eventAttendees SET status = '".$status."' WHERE userID = '".$userID."' AND eventID = '".$eventID."'");
		
		if (!$result) 
		{
			throw new Exception('Could not update event with eventID = $eventID.');
		}		
		return true;
	}
?>
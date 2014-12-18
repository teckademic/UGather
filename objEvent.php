<?php
	include('database.php');
	require_once('objCommon.php');
	
	// add a new event to the database!
	function addEvent($userID, $eventName, $locationVenue, $date, $address, $city, $state, $zipCode, $phoneNumber, $eventDescription, $startTime, $endTime)
	{
  		// write information into the database in Event	
  		$result = mysql_query("INSERT INTO Event(userID, eventName, locationVenue, date, address, city, state, zipCode, phoneNumber, eventDescription, startTime, endTime) VALUES ('".$userID."', '".$eventName."', '".$locationVenue."', '".$date."', '".$address."', '".$city."', '".$state."', '".$zipCode."', '".$phoneNumber."', '".$eventDescription."', '".$startTime."', '".$endTime."')");
		
		if (!$result) 
		{
    		throw new Exception('Could not register your event in database - please try again later.');
  		}
		
		return true;
	} // end function addEvent
	
	// send attendee a notice that the event planner has edited the event
	function attendeeEditEventNotification($eventID, $eventName) 
	{
		$result = mysql_query("SELECT userID, firstName, lastName, emailAddress FROM User WHERE userID IN (SELECT userID FROM eventAttendees WHERE eventID = '".$eventID."')");
		$num_rows = mysql_num_rows($result);
		
		if (!$result) 
		{
		 	throw new Exception('Could not find registrant information!');
		} 
		else
		{
			$result = resultToArray($result);
			if (is_array($result))
			{
				foreach ($result as $rows)
				{
					$apostrophe 		= array("\'"); // for fixing apostrophe issue
					$attenID 			= $rows['userID'];
					$attenFirstName 	= $rows['firstName'];
					$attenFirstName 	= str_replace($apostrophe, "'", $attenFirstName);
					$attenLastName 		= $rows['lastName'];
					$attenLastName 		= str_replace($apostrophe, "'", $attenLastName);
					$emailAddress		= $rows['emailAddress'];
					$email 				= $emailAddress;
					$eventName 			= str_replace($apostrophe, "'", $eventName);
		
					$headers  = 'MIME-Version: 1.0' . "\r\n";
					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
					$headers .= 'From: uGather <ugather@ugather.info>' . "\r\n";
								
					$sub = "uGather: ".$attenID.": Event Edit for ".$eventName."";
					$mesg = "<html>
								<head>
									<title>An event you're registered for has been edited!</title>
								</head>
								<body>
								<h2 style=\"font-family: 'Trebuchet MS', 'Helvetica Neue', Arial, Sans-serif; font-weight: bold; padding: 10px 0 5px 5px; color: #444; font-size: 2.5em; color: #88AC0B; border-bottom: 1px solid #E4F2C8; letter-spacing: -2px; margin-left: 5px; \">uGather Event Edit</h2>
								<p style=\"font: 12px/170% 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; color: #6B6B6B; padding: 12px 10px; \">Hello, ".$attenFirstName." ".$attenLastName."! </p>
								
								<p style=\"font: 12px/170% 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; color: #6B6B6B; padding: 12px 10px; \">An event you've registered for, <a href=\"http://www.ugather.info/viewEvent.php?eventID=".$eventID."\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">".$eventName."</a>, at  <a href=\"http://www.ugather.info\">uGather</a> has been edited by the owner! <br />
								Please check the event page to see any changes.</p>
								
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
		}
	} // end function attendeeEditEventNotification
	
	// event planner sends message en masse to attendees of their event
	function attendeeMassEmail($eventID, $ownerID, $ownerMessage) 
	{
		$result = mysql_query("SELECT userID, firstName, lastName, emailAddress FROM User WHERE userID IN (SELECT userID FROM eventAttendees WHERE eventID = '".$eventID."')");
		$num_rows = mysql_num_rows($result);
		
		$resultOwner = mysql_query("SELECT * FROM User WHERE userID = '".$ownerID."'");
		$resultOwner = resultToArray($resultOwner);
		if (is_array($resultOwner))
		{
			foreach($resultOwner as $row)
			{
					$ownerEmailAddress		= $row['emailAddress'];
			}
		}
		
		$resultName = mysql_query("SELECT eventName FROM Event WHERE eventID = '".$eventID."'");
		$resultName = resultToArray($resultName);
		if (is_array($resultName))
		{
			foreach($resultName as $row)
			{
					$eventName		= $row['eventName'];
			}
		}
		
		if (!$result) 
		{
		 	throw new Exception('Could not find registrant information!');
		} 
		else
		{
			$result = resultToArray($result);
			if (is_array($result))
			{
				foreach ($result as $rows)
				{
					$apostrophe 		= array("\'"); // for fixing apostrophe issue
					$attenID 			= $rows['userID'];
					$attenFirstName 	= $rows['firstName'];
					$attenFirstName 	= str_replace($apostrophe, "'", $attenFirstName);
					$attenLastName 		= $rows['lastName'];
					$attenLastName 		= str_replace($apostrophe, "'", $attenLastName);
					$emailAddress		= $rows['emailAddress'];
					$email 				= $emailAddress;
					$eventName 			= str_replace($apostrophe, "'", $eventName);
		
					$headers  = "MIME-Version: 1.0" . "\r\n";
					$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
					$headers .= "From: uGather <".$ownerEmailAddress.">" . "\r\n";
								
					$sub = "uGather: ".$attenID.": Message Regarding ".$eventName."";
					$mesg = "<html>
								<head>
									<title>The owner of an event you've registered for has a message for you!</title>
								</head>
								<body>
								<h2 style=\"font-family: 'Trebuchet MS', 'Helvetica Neue', Arial, Sans-serif; font-weight: bold; padding: 10px 0 5px 5px; color: #444; font-size: 2.5em; color: #88AC0B; border-bottom: 1px solid #E4F2C8; letter-spacing: -2px; margin-left: 5px; \">uGather Message</h2>
								<p style=\"font: 12px/170% 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; color: #6B6B6B; padding: 12px 10px; \">Hello, ".$attenFirstName." ".$attenLastName."! </p>
								
								<p style=\"font: 12px/170% 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; color: #6B6B6B; padding: 12px 10px; \">The owner of an event you've registered for, <a href=\"http://www.ugather.info/viewEvent.php?eventID=".$eventID."\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">".$eventName."</a>, at  <a href=\"http://www.ugather.info\">uGather</a> has sent out a message!</p>
								
								<p style=\"font: 12px/170% 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; color: #6B6B6B; padding: 12px 10px; \">The message is as follows:<br />
								<blockquote><b>".$ownerMessage."</b></blockquote></p>
								
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
		}
	} // end function attendeeMassMessage
	
	function checkUserOwnsEvent($userID, $eventID)
	{
		include('database.php');
		$result = mysql_query("SELECT userID FROM Event WHERE userID = '".$userID."' AND eventID = '".$eventID."'");
		
		$num_rows = mysql_num_rows($result);
		
		if ($num_rows > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	} // end function checkUserOwnsEvent
	
	// function getArchivedEventDetailsOrderByAsc
	function getArchivedEventDetailsOrderByAsc($fieldsNeeded, $orderBy)
	{
		$sql = "SELECT ".$fieldsNeeded." FROM Event WHERE date(date) < now() ORDER BY ".$orderBy." ASC";
		$result = mysql_query($sql);
		return $result;
	} // end function getArchivedEventDetailsOrderByAsc
	
	// function getArchivedEventDetailsOrderByAscLimit
	function getArchivedEventDetailsOrderByAscLimit($fieldsNeeded, $orderBy, $set_limit, $limit)
	{
		$sql = "SELECT ".$fieldsNeeded." FROM Event WHERE date(date) < now() ORDER BY ".$orderBy." ASC LIMIT $set_limit, $limit";
		$result = mysql_query($sql);
		return $result;
	} // end function getArchivedEventDetailsOrderByAscLimit
	
	// function getArchivedEventDetailsOrderByDesc
	function getArchivedEventDetailsOrderByDesc($fieldsNeeded, $orderBy)
	{
		$sql = "SELECT ".$fieldsNeeded." FROM Event WHERE date(date) < now() ORDER BY ".$orderBy." DESC";
		$result = mysql_query($sql);
		return $result;
	} // end function getArchivedEventDetailsOrderByDesc
	
	// function getArchivedEventDetailsOrderByDescLimit
	function getArchivedEventDetailsOrderByDescLimit($fieldsNeeded, $orderBy, $set_limit, $limit)
	{
		$sql = "SELECT ".$fieldsNeeded." FROM Event WHERE date(date) < now() ORDER BY ".$orderBy." DESC LIMIT $set_limit, $limit";
		$result = mysql_query($sql);
		return $result;
	} // end function getArchivedEventDetailsOrderByDescLimit
	
	// function getCurrentEventDetailsOrderByAsc
	function getCurrentEventDetailsOrderByAsc($fieldsNeeded, $orderBy)
	{
		$sql = "SELECT ".$fieldsNeeded." FROM Event WHERE date(date) >= now() ORDER BY ".$orderBy." ASC";
		$result = mysql_query($sql);
		return $result;
	} // end function getCurrentEventDetailsOrderByAsc
	
	// function getCurrentEventDetailsOrderByAscLimit
	function getCurrentEventDetailsOrderByAscLimit($fieldsNeeded, $orderBy, $set_limit, $limit)
	{
		$sql = "SELECT ".$fieldsNeeded." FROM Event WHERE date(date) >= now() ORDER BY ".$orderBy." ASC LIMIT $set_limit, $limit";
		$result = mysql_query($sql);
		return $result;
	} // end function getCurrentEventDetailsOrderByAscLimit
	
	// function getCurrentEventDetailsOrderByDesc
	function getCurrentEventDetailsOrderByDesc($fieldsNeeded, $orderBy)
	{
		$sql = "SELECT ".$fieldsNeeded." FROM Event WHERE date(date) >= now() ORDER BY ".$orderBy." DESC";
		$result = mysql_query($sql);
		return $result;
	} // end function getCurrentEventDetailsOrderByDesc
	
	// function getCurrentEventDetailsOrderByDescLimit
	function getCurrentEventDetailsOrderByDescLimit($fieldsNeeded, $orderBy, $set_limit, $limit)
	{
		$sql = "SELECT ".$fieldsNeeded." FROM Event WHERE date(date) >= now() ORDER BY ".$orderBy." DESC LIMIT $set_limit, $limit";
		$result = mysql_query($sql);
		return $result;
	} // end function getCurrentEventDetailsOrderByDescLimit
	
	// function getCurrentEventDetailsOrderByAscWhere
	function getCurrentEventDetailsOrderByAscWhere($fieldsNeeded, $orderBy, $ownerID)
	{
		$sql = "SELECT ".$fieldsNeeded." FROM Event WHERE userID = '".$ownerID."' AND date(date) >= now() ORDER BY ".$orderBy." DESC";
		$result = mysql_query($sql);
		return $result;
	} // end function getCurrentEventDetailsOrderByAscWhere
	
	// function getCurrentEventDetailsOrderByAscLimitWhere
	function getCurrentEventDetailsOrderByAscLimitWhere($fieldsNeeded, $orderBy, $set_limit, $limit, $ownerID)
	{
		$sql = "SELECT ".$fieldsNeeded." FROM Event WHERE userID = '".$ownerID."' AND date(date) >= now() ORDER BY ".$orderBy." DESC LIMIT $set_limit, $limit";
		$result = mysql_query($sql);
		return $result;
	} // end function getCurrentEventDetailsOrderByAscLimitWhere
	
	// function getCurrentEventDetailsOrderByDescWhere
	function getCurrentEventDetailsOrderByDescWhere($fieldsNeeded, $orderBy, $ownerID)
	{
		$sql = "SELECT ".$fieldsNeeded." FROM Event WHERE userID = '".$ownerID."' AND date(date) >= now() ORDER BY ".$orderBy." DESC";
		$result = mysql_query($sql);
		return $result;
	} // end function getCurrentEventDetailsOrderByDescWhere
	
	// function getCurrentEventDetailsOrderByDescLimitWhere
	function getCurrentEventDetailsOrderByDescLimitWhere($fieldsNeeded, $orderBy, $set_limit, $limit, $ownerID)
	{
		$sql = "SELECT ".$fieldsNeeded." FROM Event WHERE userID = '".$ownerID."' AND date(date) >= now() ORDER BY ".$orderBy." DESC LIMIT $set_limit, $limit";
		$result = mysql_query($sql);
		return $result;
	} // end function getCurrentEventDetailsOrderByDescLimitWhere
	
	// retrieve all info in Event table
	function getAllEventDetails($eventID)
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
	} /// end function getAllEventDetails
	
	// function getEventName - returns name of an event based on eventID
	function getEventName($eventID)
	{
  		$result = mysql_query("SELECT eventName FROM Event WHERE eventID = '".$eventID."'");
		
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
		
		$event = resultToArray($result);
		if (is_array($event)) 
		{
			foreach ($event as $rows)  
			{
				$eventName = $rows['eventName'];
			}
		}
		
		return $eventName;
	} // end function getEventName
	
	// function getEventSortFormDisplay
	function getEventSortFormDisplay($sortBy, $sortHow)
	{ ?>
    	<select name="sortBy"> 
			<? if ($sortBy == "eventID" || $sortBy == "event ID") { ?>
                <option value="eventID" selected>event ID</option>
            <? } else { ?>
                <option value="eventID">event ID</option> 
            <? } if ($sortBy == "event name" || $sortBy == "eventName") { ?>
                <option value="eventName" selected>event name</option>
            <? } else { ?>
                <option value="eventName">event name</option>
            <? } if ($sortBy == "locationVenue" || $sortBy == "location venue") { ?>
                <option value="locationVenue" selected>location venue</option>
			<? } else { ?>
                <option value="locationVenue">location venue</option>
            <? } if ($sortBy == "host" || $sortBy == "userID") { ?>
                <option value="userID" selected>host</option>
			<? } else { ?>
                <option value="userID">host</option>
            <? } if ($sortBy == "date") { ?>
                <option value="date" selected>date</option>
			<? } else { ?>
                <option value="date">date</option>  
            <? } ?>
   		</select>
        <select name="sortHow">
            <!-- sort: ascending or descending? -->
           	<? if ($sortHow == "descending") { ?>
                <option value="descending" selected>descending</option>
            <? } else { ?>
                <option value="descending">descending</option>
            <? } ?>
            <? if ($sortHow == "ascending") { ?>
                <option value="ascending" selected>ascending</option>
            <? } else { ?>
                <option value="ascending">ascending</option>
            <? } ?>
        </select>
	<? }
	
	function getPlannerDetails($eventID)
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
	
	function getUserDetails($userID)
	{
		// return true or error message
		//get the name of the Event	
	  	$result = mysql_query("select firstName, lastName from User where userID = '".$userID."'");
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
	
	function getEventNotificationDetails($eventID)
	{
		// return true or error message
		// get the name and owner id of the Event	
  		$result = mysql_query("select eventName, userID  from Event where eventID = '".$eventID."'");
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
	
		
	// notify the event planner of a new attendee to their event
	function eventPlannerEditEventNotification($eventID, $userID, $eventName, $plannerFirstName, $plannerLastName) 
	{
		$result = mysql_query("select emailAddress from User where userID in (select userID from Event where eventID = '".$eventID."')");
		$num_rows = mysql_num_rows($result);
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
			$plannerFirstName 		= str_replace($apostrophe, "'", $plannerFirstName);
			$plannerLastName 		= str_replace($apostrophe, "'", $plannerLastName);
			$eventName 			= str_replace($apostrophe, "'", $eventName);
			
		 	$row = mysql_fetch_object($result);
	
			$email = $row -> emailAddress;
			
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: uGather <ugather@ugather.info>' . "\r\n";
						
			$sub = "uGather: ".$userID.": Event Edit for ".$eventName."";
			$mesg = "<html>
						<head>
							<title>You have edited your event!</title>
						</head>
						<body>
						<h2 style=\"font-family: 'Trebuchet MS', 'Helvetica Neue', Arial, Sans-serif; font-weight: bold; padding: 10px 0 5px 5px; color: #444; font-size: 2.5em; color: #88AC0B; border-bottom: 1px solid #E4F2C8; letter-spacing: -2px; margin-left: 5px; \">uGather Event Edit</h2>
						<p style=\"font: 12px/170% 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; color: #6B6B6B; padding: 12px 10px; \">Hello, ".$plannerFirstName." ".$plannerLastName."! </p>
						
						<p style=\"font: 12px/170% 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; color: #6B6B6B; padding: 12px 10px; \">You have edited your event, <a href=\"http://www.ugather.info/viewEvent.php?eventID=".$eventID."\">".$eventName."</a>, at  <a href=\"http://www.ugather.info\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">uGather</a>. </p>
						
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
	} // end function eventPlannerEditEventNotification
	
	// notify the event planner of a new attendee to their event
	function eventPlannerEventNotification($eventID, $userID, $eventName, $plannerFirstName, $plannerLastName) 
	{
		$result = mysql_query("select emailAddress from User where userID in (select userID from Event where eventID = '".$eventID."')");
		$num_rows = mysql_num_rows($result);
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
			$plannerFirstName 		= str_replace($apostrophe, "'", $plannerFirstName);
			$plannerLastName 		= str_replace($apostrophe, "'", $plannerLastName);
			$eventName 			= str_replace($apostrophe, "'", $eventName);
			
		 	$row = mysql_fetch_object($result);
	
			$email = $row -> emailAddress;
			
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: uGather <ugather@ugather.info>' . "\r\n";
						
			$sub = "uGather: ".$userID.": Event Edit for ".$eventName."";
			$mesg = "<html>
						<head>
							<title>You have edited your event!</title>
						</head>
						<body>
						<h2 style=\"font-family: 'Trebuchet MS', 'Helvetica Neue', Arial, Sans-serif; font-weight: bold; padding: 10px 0 5px 5px; color: #444; font-size: 2.5em; color: #88AC0B; border-bottom: 1px solid #E4F2C8; letter-spacing: -2px; margin-left: 5px; \">uGather Event Edit</h2>
						<p style=\"font: 12px/170% 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; color: #6B6B6B; padding: 12px 10px; \">Hello, ".$plannerFirstName." ".$plannerLastName."! </p>
						
						<p style=\"font: 12px/170% 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; color: #6B6B6B; padding: 12px 10px; \">You have edited your event, <a href=\"http://www.ugather.info/viewEvent.php?eventID=".$eventID."\">".$eventName."</a>, at  <a href=\"http://www.ugather.info\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">uGather</a>. </p>
						
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
	} // end function eventPlannerEventNotification
	
	// function isDatePassed($eventID)
	function isDatePassed($eventID)
	{
		$result = mysql_query("SELECT date FROM Event WHERE eventID = '".$eventID."' AND date(date) < now()");
		
		if (!$result) 
		{
			throw new Exception('No records found.');
		}

		$num_rows = mysql_num_rows($result);
		if($num_rows == 0) 
		{
			return false;
		}
		if($num_rows > 0)
		{
			return true;
		}
	} // end function isDatePassed($eventID)
	
	// function responseMessageForEvent
	// called when a user responses to a message regarding an event
	function responseMessageForEvent($eventID, $eventName, $recipientID, $senderID, $messageText, $previousMessage)
	{
		// get recipient email address & name
		$recipientEmailAddress = getUserEmailAddress($recipientID);
		$recipientName = getUserFullName($recipientID);
		
		// get user full name
		$senderName = getUserFullName($senderID);
		
		// handle unfound data
		if (!$recipientEmailAddress)
		{
			throw new Exception('Could not find email address for the attendee.');
		}
		if (!$recipientName)
		{
			throw new Exception('Could not find a name for the attendee.');
		}
		if (!$senderName)
		{
			throw new Exception('Could not find a name for the message sender.');
		}
		
		$messageURL = $messageText;
		$messageURL = str_replace('"', "&#34;", $messageURL);
		
		// fix apostrophees to look normal in email
		$apostrophe = array("\'"); // for fixing apostrophe issue
		$eventName = str_replace($apostrophe, "'", $eventName);
		$messageText = str_replace($apostrophe, "'", $messageText);
		$recipientName = str_replace($apostrophe, "'", $recipientName);
		$senderName = str_replace($apostrophe, "'", $senderName);
		
		// email headers
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: uGather <ugather@ugather.info>' . "\r\n";
		
		// email subject line
		$subject = "uGather: ".$recipientID.": Message from ".$senderID." regarding ".$eventName."";
		
		$message = "<html>
			<head>
				<title>Someone has sent you a message!</title>
			</head>
			<body>
			<h2 style=\"font-family: 'Trebuchet MS', 'Helvetica Neue', Arial, Sans-serif; font-weight: bold; padding: 10px 0 5px 5px; color: #444; font-size: 2.5em; color: #88AC0B; border-bottom: 1px solid #E4F2C8; letter-spacing: -2px; margin-left: 5px; \">uGather Event Message Response</h2>
			<p style=\"font: 12px/170% 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; color: #6B6B6B; padding: 12px 10px; \">Hello, ".$recipientName."! 
			<br /><br />
			
			The user ".$senderID." (".$senderName.") has responded to a message you sent regarding the event, <a href=\"http://www.ugather.info/viewEvent.php?eventID=".$eventID."\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">".$eventName."</a>, at  <a href=\"http://www.ugather.info\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">uGather</a>. <br /><br />
			
			The message is as follows: 
			<blockquote style=\"background-color: #F8F8F8  ; border: 2px solid #E4F2C8; width: 75%; margin: 1px; padding: 2px; \"><p style=\"font: 12px/170% 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; color: #6B6B6B; padding: 12px 10px; \"><b>".$messageText."</b><br /><br /><i>Message They Responded To:<br />".$previousMessage."</i></blockquote>
			<p style=\"font: 12px/170% 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; color: #6B6B6B; padding: 12px 10px; \">Respond to this message here: <a href=\"http://www.ugather.info/messageResponse.php?eventID=".$eventID."&amp;recipientID=".$senderID."&amp;messageReceived=".$messageURL."\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">message response!</a><br /><br />
			
			<i>Additional Options</i><br />
			<a href=\"http://www.ugather.info/viewEvent.php?eventID=".$eventID."\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">view event ".$eventName."</a> 
			<br />
			<a href=\"http://www.ugather.info/viewAttendees.php?eventID=".$eventID."\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">view attendees to ".$eventName."</a> 
			<br /><br />
			
			This is an automatically generated email from <a href=\"http://ugather.info\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">uGather</a>. Please do not respond. Thank you.</p> 
			</body>
			</html>";
		if (mail($recipientEmailAddress, $subject, $message, $headers)) 
		{
			return true;
		} // end if
		else 
		{
			throw new Exception('Could not send email.');
		} // end else
	} // end function responseMessageForEvent
	
	// function sendMessageToAllAttendees
	// called when a user requests to send a message to all attendees
	// of one of their events
	function sendMessageToAllAttendees($eventID, $eventName, $senderID, $messageText)
	{
		$sql = "SELECT userID FROM User WHERE userID IN (SELECT userID FROM eventAttendees WHERE eventID = '".$eventID."')";
		$result = mysql_query($sql);
		$result = resultToArray($result);
		
		if(is_Array($result))
		{
			foreach ($result as $rows)
			{
				// get user full name
				$senderName = getUserFullName($senderID);
				
				// get recipient email address and full name
				$recipientID = $rows['userID'];
				$recipientEmailAddress = getUserEmailAddress($recipientID);
				$recipientName = getUserFullName($recipientID);
				
				// handle unfound data
				if (!$recipientEmailAddress)
				{
					throw new Exception('Could not find email address for the recipient.');
				}
				if (!$recipientName)
				{
					throw new Exception('Could not find a name for the recipient.');
				}
				if (!$senderName)
				{
					throw new Exception('Could not find a name for the message sender.');
				}
				
				$messageURL = $messageText;
				$messageURL = str_replace('"', "&#34;", $messageURL);
				$messageURL = str_replace('#', "&#35;", $messageURL);
				
				// fix apostrophees to look normal in email
				$apostrophe = array("\'"); // for fixing apostrophe issue
				$eventName = str_replace($apostrophe, "'", $eventName);
				$messageText = str_replace($apostrophe, "'", $messageText);
				$recipientName = str_replace($apostrophe, "'", $recipientName);
				$senderName = str_replace($apostrophe, "'", $senderName);
				
				// email headers
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= 'From: uGather <ugather@ugather.info>' . "\r\n";
				
				// email subject line
				$subject = "uGather: ".$recipientID.": Message from ".$senderID." regarding ".$eventName."";
				
				$message = "<html>
					<head>
						<title>Someone has sent you a message!</title>
					</head>
					<body>
					<h2 style=\"font-family: 'Trebuchet MS', 'Helvetica Neue', Arial, Sans-serif; font-weight: bold; padding: 10px 0 5px 5px; color: #444; font-size: 2.5em; color: #88AC0B; border-bottom: 1px solid #E4F2C8; letter-spacing: -2px; margin-left: 5px; \">uGather Event Message</h2>
					<p style=\"font: 12px/170% 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; color: #6B6B6B; padding: 12px 10px; \">Hello, ".$recipientName."! 
					<br /><br />
					
					The user ".$senderID." (".$senderName.") has sent you a message regarding their event, <a href=\"http://www.ugather.info/viewEvent.php?eventID=".$eventID."\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">".$eventName."</a>, at  <a href=\"http://www.ugather.info\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">uGather</a>. <br /><br />
					
					The message is as follows: 
					<blockquote style=\"background-color: #F8F8F8  ; border: 2px solid #E4F2C8; width: 75%; margin: 1px; padding: 2px; \"><p style=\"font: 12px/170% 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; color: #6B6B6B; padding: 12px 10px; \"><b>".$messageText."</b></blockquote>
					<p style=\"font: 12px/170% 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; color: #6B6B6B; padding: 12px 10px; \">Respond to this message here: <a href=\"http://www.ugather.info/messageResponse.php?eventID=".$eventID."&amp;recipientID=".$senderID."&amp;messageReceived=".$messageURL."\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">message response!</a><br /><br />
					
					<i>Additional Options</i><br />
					<a href=\"http://www.ugather.info/viewEvent.php?eventID=".$eventID."\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">view event ".$eventName."</a> 
					<br />
					<a href=\"http://www.ugather.info/viewAttendees.php?eventID=".$eventID."\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">view attendees to ".$eventName."</a> 
					<br /><br />
					
					This is an automatically generated email from <a href=\"http://ugather.info\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">uGather</a>. Please do not respond. Thank you.</p> 
					</body>
					</html>";
				if (mail($recipientEmailAddress, $subject, $message, $headers)) 
				{
					// do nothing
				} // end if
				else 
				{
					throw new Exception('Could not send email.');
				} // end else
			}
		}
	} // end function sendMessageToAllAttendees
	
	// function sendMessageToEventPlanner
	// called when a user requests to send a message to the planner
	// of a specific event, regarding that event
	function sendMessageToEventPlanner($eventID, $eventName, $recipientID, $senderID, $messageText)
	{
		// get owner email address & name
		$recipientEmailAddress = getUserEmailAddress($recipientID);
		$recipientName = getUserFullName($recipientID);
		
		// get user full name
		$senderName = getUserFullName($senderID);
		
		// handle unfound data
		if (!$recipientEmailAddress)
		{
			throw new Exception('Could not find email address for the event owner.');
		}
		if (!$recipientName)
		{
			throw new Exception('Could not find a name for the event owner.');
		}
		if (!$senderName)
		{
			throw new Exception('Could not find a name for the message sender.');
		}
		
		$messageURL = $messageText;
		$messageURL = str_replace('"', "&#34;", $messageURL);
		
		// fix apostrophees to look normal in email
		$apostrophe = array("\'"); // for fixing apostrophe issue
		$eventName = str_replace($apostrophe, "'", $eventName);
		$messageText = str_replace($apostrophe, "'", $messageText);
		$recipientName = str_replace($apostrophe, "'", $recipientName);
		$senderName = str_replace($apostrophe, "'", $senderName);
		
		// email headers
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: uGather <ugather@ugather.info>' . "\r\n";
		
		// email subject line
		$subject = "uGather: ".$recipientID.": Message from ".$senderID." regarding ".$eventName."";
		
		$message = "<html>
			<head>
				<title>Someone has sent you a message!</title>
			</head>
			<body>
			<h2 style=\"font-family: 'Trebuchet MS', 'Helvetica Neue', Arial, Sans-serif; font-weight: bold; padding: 10px 0 5px 5px; color: #444; font-size: 2.5em; color: #88AC0B; border-bottom: 1px solid #E4F2C8; letter-spacing: -2px; margin-left: 5px; \">uGather Event Message</h2>
			<p style=\"font: 12px/170% 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; color: #6B6B6B; padding: 12px 10px; \">Hello, ".$recipientName."! 
			<br /><br />
			
			The user ".$senderID." (".$senderName.") has sent you a message regarding your event, <a href=\"http://www.ugather.info/viewEvent.php?eventID=".$eventID."\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">".$eventName."</a>, at  <a href=\"http://www.ugather.info\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">uGather</a>. <br /><br />
			
			The message is as follows: 
			<blockquote style=\"background-color: #F8F8F8  ; border: 2px solid #E4F2C8; width: 75%; margin: 1px; padding: 2px; \"><p style=\"font: 12px/170% 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; color: #6B6B6B; padding: 12px 10px; \"><b>".$messageText."</b></blockquote>
			<p style=\"font: 12px/170% 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; color: #6B6B6B; padding: 12px 10px; \">Respond to this message here: <a href=\"http://www.ugather.info/messageResponse.php?eventID=".$eventID."&amp;recipientID=".$senderID."&amp;messageReceived=".$messageURL."\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">message response!</a><br /><br />
			
			<i>Additional Options</i><br />
			<a href=\"http://www.ugather.info/viewEvent.php?eventID=".$eventID."\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">view event ".$eventName."</a> 
			<br />
			<a href=\"http://www.ugather.info/viewAttendees.php?eventID=".$eventID."\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">view attendees to ".$eventName."</a> 
			<br /><br />
			
			This is an automatically generated email from <a href=\"http://ugather.info\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">uGather</a>. Please do not respond. Thank you.</p> 
			</body>
			</html>";
		if (mail($recipientEmailAddress, $subject, $message, $headers)) 
		{
			return true;
		} // end if
		else 
		{
			throw new Exception('Could not send email.');
		} // end else
	} // end function sendMessageToEventPlanner
	
	// show all events user has created
	function showUserEvents($userID)
	{
		// return true or error message
		//Show the events by user	
		$result = mysql_query("Select eventID, eventName from Event where userID = '".$userID."'");
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
	
	// return the events the user has registered for 
	function showRegisteredEvents($userID)
	{
		// return true or error message
		//Show the events by user
		$result = mysql_query("select eventID, eventName from Event where eventID in (select eventID from eventAttendees where userID = '".$userID."')");	
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
	} // end function showRegisteredEvents
	
	// function to update the event details based on given parameters and eventID
	function updateExistingEvent($userID, $eventID, $eventName, $locationVenue, $date, $address, $city, $state, $zipCode, $phoneNumber, $eventDescription, $startTime, $endTime)
	{
		// update the following fields in the database:
		$result = mysql_query("update Event set eventName = '".$eventName."' where eventID = '".$eventID."'");
		$result = mysql_query("update Event set locationVenue = '".$locationVenue."' where eventID = '".$eventID."'");
		$result = mysql_query("update Event set date = '".$date."' where eventID = '".$eventID."'");
		$result = mysql_query("update Event set address = '".$address."' where eventID = '".$eventID."'");
		$result = mysql_query("update Event set city = '".$city."' where eventID = '".$eventID."'");
		$result = mysql_query("update Event set state = '".$state."' where eventID = '".$eventID."'");
		$result = mysql_query("update Event set zipCode = '".$zipCode."' where eventID = '".$eventID."'");
		$result = mysql_query("update Event set phoneNumber = '".$phoneNumber."' where eventID = '".$eventID."'");
		$result = mysql_query("update Event set eventDescription = '".$eventDescription."' where eventID = '".$eventID."'");
		$result = mysql_query("update Event set startTime = '".$startTime."' where eventID = '".$eventID."'");
		$result = mysql_query("update Event set endTime = '".$endTime."' where eventID = '".$eventID."'");
		
		if (!$result) 
		{
			throw new Exception('Could not update event with eventID = $eventID.');
		}		
		  return true;
	} // end function eventPlannerEditEventNotification
?>
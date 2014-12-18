<?php
	require_once('database.php');
	require_once('objCommon.php');
	
	// check if the user on the current session is an action system user
	function checkValidUser() 
	{
		// see if somebody is logged in and notify them if not
  		if (isset($_SESSION['valid_user']))  
		{
			return true;
  		} 
		else 
		{
     		// they are not logged in	
			header('location: login.php');
			exit;
		}
	}
	
	function getAllUserDetails($userID)
	{
		// return true or error message
		// gets all stored information for the current User	
  		$result = mysql_query("select * from User where userID = '".$userID."'");
		if (!$result) 
		{
			throw new Exception('No records found for user id = $userID.');
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
	} // end function getAllUserDetails
	
	function getPublicUserDetails($userID)
	{
		// return true or error message
		// gets all publically-allowed info for the current User	
  		$result = mysql_query("select userID, firstName from User where userID = '".$userID."'");
		if (!$result) 
		{
			throw new Exception('No records found for user id = $userID.');
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
	} // end function getPublicUserDetails
	
	function getRandomWord($min_length, $max_length) 
	{
		// grab a random word from dictionary between the two lengths
		// and return it
		   // generate a random word
		  $word = '';
		  // remember to change this path to suit your system
		  $dictionary = 'words.txt';  // the ispell dictionary
		  $fp = @fopen($dictionary, 'r');
		  if(!$fp) 
		  {
			return false;
		  }
		  $size = filesize($dictionary);
		  // go to a random location in dictionary
		  $rand_location = rand(0, $size);
		  fseek($fp, $rand_location);
		  // get the next whole word of the right length in the file
		  while ((strlen($word) < $min_length) || (strlen($word)>$max_length) || (strstr($word, "'"))) 
		  {
			 if (feof($fp)) 
			 {
				fseek($fp, 0);        // if at end, go to start
			 }
			 $word = fgets($fp, 80);  // skip first word as it could be partial
			 $word = fgets($fp, 80);  // the potential password
		  }
		  $word = trim($word); // trim the trailing \n from fgets
		  return $word;
	} // end function getRandomWord
	
	// function getUserFullName
	// returns first and last name on one string for user
	function getUserFullName($userID)
	{
  		$result = mysql_query("SELECT firstName, lastName FROM User WHERE userID = '".$userID."'");
		
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
				$userFirstName = $rows['firstName'];
				$userLastName = $rows['lastName'];
			}
		}
		
		$userFullName = "".$userFirstName." ".$userLastName."";
		
		return $userFullName;
	} // end function getUserFullName
	
	// function getUserEmailAddress
	// returns email address of the user with that userID
	function getUserEmailAddress($userID)
	{
  		$result = mysql_query("SELECT emailAddress FROM User WHERE userID = '".$userID."'");
		
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
				$userEmailAddress = $rows['emailAddress'];
			}
		}
		
		return $userEmailAddress;
	} // end function getUserEmailAddress
	
	// function getUserName - get the user's name!
	function getUserName($userID)
	{
		// return true or error message
		// gets all stored information for the current User	
  		$result = mysql_query("select firstName, lastName from User where userID = '".$userID."'");
		if (!$result) 
		{
			throw new Exception('No records found for user id = $userID.');
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
	
	// new function: isLoggedIn()

	// function: check if the session user is logged in!
	function isLoggedIn() 
	{
  		if (isset($_SESSION['valid_user']))  
		{
			// user has an active session/is logged in,
			// return true
			return true;
		} 
		else 
		{
     		// visitor not logged in, return false
     		return false;
  		}
	} // end function isLoggedIn()
	
	function login($userID, $password) 
	{
		// check username and password with db
		// if yes, return true
		// else return false
		$result = mysql_query("SELECT * from User WHERE userID = '".$userID."' AND password = md5('".$password."')");
		if ($result) 
		{
			$num_rows = mysql_num_rows($result);
			if ($num_rows > 0) 
			{
		 		return true;
			}	 
			else 
			{
		 		return false;
			}
		}
		else
		{
			return false;	
		}
	} // end function login

	
	function notifyPassword($userID, $password) 
	{
		// notify the user that their password has been changed
		$result = mysql_query("SELECT emailAddress, firstName, lastName FROM User WHERE userID = '".$userID."'");
		$num_rows = mysql_num_rows($result);
		
		if (!$result) 
		{
		  throw new Exception('Could not find email address.');
		} 
		else if ($num_rows == 0) 
		{
		  	throw new Exception('Could not find email address.');
		  	// username not in db
		} 
		else 
		{
			$row = mysql_fetch_object($result);
			$email = $row -> emailAddress;
			$firstName = $row -> firstName;
			$lastName = $row -> lastName;
			$apostrophe = array("\'"); // for fixing apostrophe issue
			$firstName = str_replace($apostrophe, "'", $firstName);
			$lastName = str_replace($apostrophe, "'", $lastName);
			
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: uGather <ugather@ugather.info>' . "\r\n";
			
			$subject = "uGather: ".$userID.": Password Change";
			
			$mesg = "<html>
						<head>
							<title>You have changed your password!</title>
						</head>
						<body>
						<h2 style=\"font-family: 'Trebuchet MS', 'Helvetica Neue', Arial, Sans-serif; font-weight: bold; padding: 10px 0 5px 5px; color: #444; font-size: 2.5em; color: #88AC0B; border-bottom: 1px solid #E4F2C8; letter-spacing: -2px; margin-left: 5px; \">uGather Password Change</h2>
						<p style=\"font: 12px/170% 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; color: #6B6B6B; padding: 12px 10px; \">Hello, ".$firstName." ".$lastName."! </p>
						
						<p style=\"font: 12px/170% 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; color: #6B6B6B; padding: 12px 10px; \">Your password for your account (".$userID.") at <a href=\"http://ugather.info\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">uGather</a> has been changed to ".$password."<br />
						Please change your password next time you log in using the <a href=\"http://www.ugather.info/editPassword.php\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">change password form</a>. </p>
						
						<p style=\"font: 12px/170% 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; color: #6B6B6B; padding: 12px 10px; \">This is an automatically generated email from <a href=\"http://ugather.info\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">uGather</a>. Please do not respond. Thank you.</p> 
						</body>
						</html>";
			
			if (mail($email, $subject, $mesg, $headers)) 
			{
				return true;
			} 
			else 
			{
				throw new Exception('Could not send email.');
			}
		}
	} // end function notifyPassword
	
	// notify the user that their password has been changed
	function notifyPasswordChange($userID) 
	{
		$result = mysql_query("SELECT emailAddress, firstName, lastName FROM User WHERE userID = '".$userID."'");
		$num_rows = mysql_num_rows($result);
		
		if (!$result) 
		{
		  	throw new Exception('Could not find email address.');
		} 
		else if ($num_rows == 0) 
		{
		  	throw new Exception('Could not find email address.');
		  // username not in db
		} 
		else 
		{
			$row = mysql_fetch_object($result);
			$email = $row -> emailAddress;
			$firstName = $row -> firstName;
			$lastName = $row -> lastName;
			$apostrophe = array("\'"); // for fixing apostrophe issue
			$firstName = str_replace($apostrophe, "'", $firstName);
			$lastName = str_replace($apostrophe, "'", $lastName);
			
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: uGather <ugather@ugather.info>' . "\r\n";
			
			$subject = "uGather: ".$userID.": Password Change";
			
			$mesg = "<html>
						<head>
							<title>You have changed your password!</title>
						</head>
						<body>
						<h2 style=\"font-family: 'Trebuchet MS', 'Helvetica Neue', Arial, Sans-serif; font-weight: bold; padding: 10px 0 5px 5px; color: #444; font-size: 2.5em; color: #88AC0B; border-bottom: 1px solid #E4F2C8; letter-spacing: -2px; margin-left: 5px; \">uGather Password Change</h2>
						<p style=\"font: 12px/170% 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; color: #6B6B6B; padding: 12px 10px; \">Hello, ".$firstName." ".$lastName."! </p>
						
						<p style=\"font: 12px/170% 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; color: #6B6B6B; padding: 12px 10px; \">You have changed your the password associated with your account at <a href=\"http://ugather.info\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">uGather</a>, ".$userID.". For your account's security, your new password is not displayed in this email. If you have forgotten your password, please see our <a href=\"http://www.ugather.info/passwordRecovery.php\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">password recovery form</a>. If you'd like to change your password again, please go to the <a href=\"http://www.ugather.info/editPassword.php\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">change password form</a>. </p>
						
						<p style=\"font: 12px/170% 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; color: #6B6B6B; padding: 12px 10px; \">This is an automatically generated email from <a href=\"http://ugather.info\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">uGather</a>. Please do not respond. Thank you.</p> 
						</body>
						</html>";
			
			if (mail($email, $subject, $mesg, $headers)) 
			{
				return true;
			} 
			else 
			{
				throw new Exception('Could not send email.');
			}
		}
	} // end function notifyPasswordChange
	
	// notify the user that their profile has been changed
	function notifyProfileChange($userID) 
	{
		$result = mysql_query("SELECT emailAddress, firstName, lastName FROM User WHERE userID = '".$userID."'");
		$num_rows = mysql_num_rows($result);
		
		if (!$result) 
		{
		  	throw new Exception('Could not find email address.');
		} 
		else if ($num_rows == 0) 
		{
		  	throw new Exception('Could not find email address.');
		  // username not in db
		} 
		else 
		{
			$row = mysql_fetch_object($result);
			$email = $row -> emailAddress;
			$firstName = $row -> firstName;
			$lastName = $row -> lastName;
			$apostrophe = array("\'"); // for fixing apostrophe issue
			$firstName = str_replace($apostrophe, "'", $firstName);
			$lastName = str_replace($apostrophe, "'", $lastName);
			
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: uGather <ugather@ugather.info>' . "\r\n";
			
			$subject = "uGather: ".$userID.": Profile Change";
			
			$mesg = "<html>
						<head>
							<title>You have changed your profile!</title>
						</head>
						<body>
						<h2 style=\"font-family: 'Trebuchet MS', 'Helvetica Neue', Arial, Sans-serif; font-weight: bold; padding: 10px 0 5px 5px; color: #444; font-size: 2.5em; color: #88AC0B; border-bottom: 1px solid #E4F2C8; letter-spacing: -2px; margin-left: 5px; \">uGather Profile Change</h2>
						<p style=\"font: 12px/170% 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; color: #6B6B6B; padding: 12px 10px; \">Hello, ".$firstName." ".$lastName."! </p>
						
						<p style=\"font: 12px/170% 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; color: #6B6B6B; padding: 12px 10px; \">You have changed your user profile at <a href=\"http://ugather.info\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">uGather</a> for the account ".$userID.". If you'd like to view your profile, please go to the <a href=\"http://www.ugather.info/viewUserProfile.php\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">view profile page</a>. If you'd like to edit your profile, please go to the <a href=\"http://www.ugather.info/editUserProfile.php\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">edit profile form</a>.</p>
						
						<p style=\"font: 12px/170% 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; color: #6B6B6B; padding: 12px 10px; \">This is an automatically generated email from <a href=\"http://ugather.info\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">uGather</a>. Please do not respond. Thank you.</p> 
						</body>
						</html>";
			
			if (mail($email, $subject, $mesg, $headers)) 
			{
				return true;
			} 
			else 
			{
				throw new Exception('Could not send email.');
			}
		}
	} // end function notifyProfileChanged
	
	
	// function to place new user in the database
	function registerUser($userID, $password, $userType, $firstName, $lastName, $emailAddress, $streetAddress, $city, $state, $zipCode, $phoneNumber)
	{
		// register new person with db
		// return true or error message
		// check if username is unique
		$result = mysql_query("select * from User where userID='".$userID."'");
		if (!$result) 
		{
			throw new Exception('Could not execute query');
		}
		$num_rows = mysql_num_rows($result);
		if ($num_rows > 0) 
		{
			throw new Exception('That username is taken - go back and choose another one.');
		}
		// if ok, put in db
		$result = mysql_query("INSERT INTO User VALUES('".$userID."', md5('".$password."'), '".$userType."', '".$firstName."', '".$lastName."', '".$emailAddress."', '".$streetAddress."', '".$city."', '".$state."', '".$zipCode."', '".$phoneNumber."')");
		if (!$result) 
		{
			throw new Exception('Could not register you in database - please try again later.');
		}
	  	return true;
	} // end function registerUser
	
	// function to notify new user of successful registration
	function registerUserNotification($userID) 
	{
		$result = mysql_query("SELECT emailAddress, firstName, lastName FROM User WHERE userID = '".$userID."'");
		$num_rows = mysql_num_rows($result);
		
		if (!$result) 
		{
		  	throw new Exception('Could not find email address.');
		} 
		else if ($num_rows == 0) 
		{
		  	throw new Exception('Could not find email address.');
		  // username not in db
		} 
		else 
		{
			$row = mysql_fetch_object($result);
			$email = $row -> emailAddress;
			$firstName = $row -> firstName;
			$lastName = $row -> lastName;
			$apostrophe = array("\'"); // for fixing apostrophe issue
			$firstName = str_replace($apostrophe, "'", $firstName);
			$lastName = str_replace($apostrophe, "'", $lastName);
			
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: uGather <ugather@ugather.info>' . "\r\n";
			
			$subject = "uGather: ".$userID.": New User Registration";
		
			$mesg = "<html>
						<head>
							<title>You have registered for an account!!</title>
						</head>
						<body>
						<h2 style=\"font-family: 'Trebuchet MS', 'Helvetica Neue', Arial, Sans-serif; font-weight: bold; padding: 10px 0 5px 5px; color: #444; font-size: 2.5em; color: #88AC0B; border-bottom: 1px solid #E4F2C8; letter-spacing: -2px; margin-left: 5px; \">uGather Account Registration</h2>
						<p style=\"font: 12px/170% 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; color: #6B6B6B; padding: 12px 10px; \">Hello, ".$firstName." ".$lastName."! </p>
						
						<p style=\"font: 12px/170% 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; color: #6B6B6B; padding: 12px 10px; \">Thank you for signing up for an account at <a href=\"http://ugather.info\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">uGather</a>!</p>
						
						<p style=\"font: 12px/170% 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; color: #6B6B6B; padding: 12px 10px; \">We highly encourage you to take advantage of any of the number of services we offer:<br />
						<ul style=\" margin: 10px 20px; padding: 0 20px;\">
							<li>Events</li>
								<ul style=\" margin: 10px 20px; padding: 0 20px;\">
									<li><a href=\"http://www.ugather.info/newEvent.php\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">Create a New Event!</a></li>
									<li><a href=\"http://www.ugather.info/events.php\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">View Existing Events</a></li>
									<li><a href=\"http://www.ugather.info/dashboardOwnedEvents.php\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">View Events You've Created</a></li>
									<li><a href=\"http://www.ugather.info/dashboardRegisteredEvents.php\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">View Events You've Registered For</a></li>
								</ul>
							<li>User Information</li>
								<ul style=\" margin: 10px 20px; padding: 0 20px;\">
									<li><a href=\"http://www.ugather.info/viewUserProfile.php\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">View Your User Profile</a></li>
									<li><a href=\"http://www.ugather.info/editUserProfile.php\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">Edit Your User Profile</a></li>
									<li><a href=\"http://www.ugather.info/editPassword.php\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">Change Your Password</a></li>
								</ul>
							<li>Other Pages</li>
								<ul style=\" margin: 10px 20px; padding: 0 20px;\">
									<li><a href=\"http://www.ugather.info/dashboard.php\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">View Your Dashboard</a></li>
									<li><a href=\"http://www.ugather.info/passwordRecovery.php\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">Forgot Password</a></li>
									<li><a href=\"http://www.ugather.info/help.php\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">Help</a></li>
								</ul>
						</ul></p>
						
						<p style=\"font: 12px/170% 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; color: #6B6B6B; padding: 12px 10px; \">This is an automatically generated email from <a href=\"http://ugather.info\" style=\"color: #332616; text-decoration: underline; font-weight: bold; \">uGather</a>. Please do not respond. Thank you.</p> 
						</body>
						</html>";
			
			if (mail($email, $subject, $mesg, $headers)) 
			{
				return true;
			} 
			else 
			{
				throw new Exception('Could not send email.');
			}
		}
		
		$email = $emailAddress;
		$sub = "uGather: ".$userID.": New User Registration confirmation";
		$from = "From: ugather@ugather.info \r\n";
		$mesg = "Thank you, ".$userID." (".$userFirstName." ".$userLastName.") "
			."for registering for at uGather. \r\n\n"
			."This is an automatic email generated by http://ugather.info. \r\n"
			."Please do not respond.";
		if (mail($email, $sub, $mesg, $from))
		{
			return true;
		} else 
		{
			throw new Exception('Could not send email.');
		}
	} // end function registerUserNotification
	
	// reset password to random password
	function resetPassword($userID) 
	{
		// set password for username to a random value
		// return the new password or false on failure
		// get a random dictionary word b/w 6 and 13 chars in length
		$new_password = getRandomWord(6, 13);
		if($new_password == false) 
		{
			throw new Exception('Could not generate new password.');
		}
		// add a number  between 0 and 999 to it
		// to make it a slightly better password
		$rand_number = rand(0, 999);
		$new_password .= $rand_number;
		// set user's password to this in database or return false
		$result = mysql_query("update User
							  set password = md5('".$new_password."')
							  where userID = '".$userID."'");
		if (!$result) 
		{
			echo mysql_error();
		//throw new Exception('Could not change password.');  // not changed
		} 
		else 
		{	
			return $new_password;  // changed successfully
		}
	} // end function resetPassword
		
	// function to update password in database
	function updatePassword($userID, $old_pass, $new_pass) 
	{
		// change password for username/old_password to new_password
		// return true or false
		// if the old password is right
		// change their password to new_password and return true
		// else throw an exception
		login($userID, $old_pass); 
		$result = mysql_query("update User
		set password = md5('".$new_pass."')
		where userID = '".$userID."'");
		if (!$result) 
		{
			throw new Exception('Password could not be changed.');
		} 
		else 
		{
			return true;  // changed successfully
		}
	} // end function updatePassword
	
	
	// function to update user fields in database
	function updateUser($userID, $firstName, $lastName, $emailAddress, $streetAddress, $city, $state, $zipCode, $phoneNumber)
	{
		// register new person with db
		// return true or error message
		$result = mysql_query("update User set firstName = '".$firstName."' where userID = '".$userID."'");
		$result = mysql_query("update User set lastName = '".$lastName."' where userID = '".$userID."'");
		$result = mysql_query("update User set emailAddress = '".$emailAddress."' where userID = '".$userID."'");
		$result = mysql_query("update User set streetAddress = '".$streetAddress."' where userID = '".$userID."'");
		$result = mysql_query("update User set city = '".$city."' where userID = '".$userID."'");
		$result = mysql_query("update User set state = '".$state."' where userID = '".$userID."'");
		$result = mysql_query("update User set zipCode = '".$zipCode."' where userID = '".$userID."'");
		$result = mysql_query("update User set phoneNumber = '".$phoneNumber."' where userID = '".$userID."'");
		if (!$result) 
		{
			throw new Exception('Could not update profile.');
		}
	
	  	return true;
	} // end funciton updateUser
?>
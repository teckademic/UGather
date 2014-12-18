<?php
	require_once('database.php');
	require_once('objUser.php');
	require_once('validateData.php');
	
	//start session
	session_start();
	
	// if logged in, please redirect to dashboard
	if (isLoggedIn())
	{
		header('location: dashboard.php');	
	} // end if logged in

	//create variables
	$userID = $_POST['userId'];
	$password = $_POST['userPass'];
	$userType = "user";
	$rePass = $_POST['rePass'];
	$firstName = $_POST['firstName'];
	$lastName = $_POST['lastName'];
	$emailAddress = $_POST['emailAddress'];
	$streetAddress = $_POST['address'];
	$city = $_POST['city'];
	$state = $_POST['state'];
	$zipCode = $_POST['zipCode'];
	$phoneNumber = $_POST['phoneNumber'];
	
	// fix apostrophe issue for database
	$apostrophe = array("'"); 
	$quotation = array('"'); 
	$firstName2 = $firstName;
	$firstName = str_replace($apostrophe, "\'", $firstName);
	$firstName = str_replace($quotation, "&#34;", $firstName);
	$lastName2 = $lastName;
	$lastName = str_replace($apostrophe, "\'", $lastName);
	$lastName = str_replace($quotation, "&#34;", $lastName);
	$streetAddress2 = $streetAddress;
	$streetAddress = str_replace($apostrophe, "\'", $streetAddress);
	$streetAddress = str_replace($quotation, "&#34;", $streetAddress);
	$city2 = $city;
	$city = str_replace($apostrophe, "\'", $city);
	$city = str_replace($quotation, "&#34;", $city);
	$zipCode2 = $zipCode;
	$zipCode = str_replace($apostrophe, "\'", $zipCode);
	$zipCode = str_replace($quotation, "&#34;", $zipCode);
	$phoneNumber2 = $phoneNumber;
	$phoneNumber = str_replace($apostrophe, "\'", $phoneNumber);
	$phoneNumber = str_replace($quotation, "&#34;", $phoneNumber);

	try 
	{
		// check for unallowed characters
		// check username has no special characters
		if (hasSpecialCharacters($userID2))
		{
			throw new Exception('<span style="color: #FF0000;">Your username may only contain letters A-Z (upper or lowercase: a-z and A-Z), numbers (0-9), and underscores (_). No spaces allowed. Please try again.</span>');
		} // end if
		// check first name has no special characters
		if (hasSpecialCharacters2($firstName2))
		{
			throw new Exception('<span style="color: #FF0000;">Your first name may only contain letters A-Z (upper or lowercase: a-z and A-Z), numbers (0-9), and some special characters (- \' . ,). Please try again.</span>');
		} // end if
		// check last name has no special characters
		if (hasSpecialCharacters2($lastName2))
		{
			throw new Exception('<span style="color: #FF0000;">Your last name may only contain letters A-Z (upper or lowercase: a-z and A-Z), numbers (0-9), and some special characters (- \' . ,). Please try again.</span>');
		} // end if
		// check street address has no special characters
		if (hasSpecialCharacters2($streetAddress2))
		{
			throw new Exception('<span style="color: #FF0000;">Your street address may only contain letters A-Z (upper or lowercase: a-z and A-Z), numbers (0-9), and some special characters (- \' . ,). Please try again.</span>');
		} // end if
		// check city has no special characters
		if (hasSpecialCharacters2($city2))
		{
			throw new Exception('<span style="color: #FF0000;">Your city may only contain letters A-Z (upper or lowercase: a-z and A-Z), numbers (0-9), and some special characters (- \' . ,). Please try again.</span>');
		} // end if
		// check  zip code has only numbers and dashes
		if (preg_match( "@[^0-9-]+@i", $zipCode2))
		{
			throw new Exception('<span style="color: #FF0000;">Your zip code may contain only numbers (0-9) and dashes (-). No spaces allowed. Please try again.</span>');
		} // end if
		// check phone number has only numbers
		if (preg_match( "@[^0-9]+@i", $phoneNumber2))
		{
			throw new Exception('<span style="color: #FF0000;">Your phone number may contain only numbers (0-9), in form 1112223333. No spaces allowed. Please try again.</span>');
		} // end if
		// check password has only letters, numbers, and some special characters
		if (preg_match( "@[^a-z0-9&.-]+@i", $password))
		{
			throw new Exception('<span style="color: #FF0000;">Your password may contain only numbers (0-9), letters A-Z (upper or lowercase: a-z and A-Z), and a few special characters (& . -). No spaces allowed. Please try again.</span>');
		} // end if
		
		// checks that forms are filled in
		if (!filled_out($_POST))
		{
			throw new Exception('<span style="color: #FF0000;">You have not filled the form out completely. Please try again.</span>');
		} // end if
		
		// check username length is okay
		if((strlen($userID) < 5)) 
		{
			throw new Exception('<span style="color: #FF0000;">Your username must be at least 5 characters. Please try again.</span>');
		} // end if
		// checks if passwords are the same
		if ($userPass != $rePass) 
		{
			throw new Exception('<span style="color: #FF0000;">The passwords you entered do not match. Please try again.</span>');
		} // end if
		// checks that email is correct
		if (!valid_email($emailAddress))
		{
			throw new Exception('<span style="color: #FF0000;">You have entered an invalid email address. Please try again.</span>');
		} // end if
		// check password length is ok
		if((strlen($userPass) < 6) || (strlen($userPass) > 16)) 
		{
			throw new Exception('<span style="color: #FF0000;">Your password must be between 6 and 16 characters. Please try again.</span>');
		} // end if

		// attempt to register user in database
		registerUser($userID, $password, $userType, $firstName, $lastName, $emailAddress, $streetAddress, $city, $state, $zipCode, $phoneNumber);
		// notify user of their new registration
		registerUserNotification($userID);
	
		// register session variable
		$_SESSION['valid_user'] = $userID;
		header('location: registerConfirmation.php');

  	} // end try
  	//catch exception
  	catch(Exception $exception)
   	{
    	// code detailed below
   	} // cend catch
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
			displayNavigation("Home");
		?>
	<!-- navigation ends-->	
	</div>					

	<!-- content starts -->
	<div id="content">

		<!-- main starts -->
		<div id="main">

		  <h2>Register</h2>
          	<? if ($exception)
					echo "<p>Error found: ",  $exception->getMessage(), "\n";
			?>
            <!-- start new user registration form -->
            <form name="registerExecuteForm" method="post" action="registerExecute.php">	
                <p>
                    <label>Username (will be used to log you into uGather!)</label>
                    <br />
                    <input name="userId" type="text" id="userId" size="30" maxlength="25" value="<? echo $userID; ?>" />
                </p>
                <p>
                    <label>Password (Must be 6 - 16 characters long) </label>
                	<br />
                    <input id="userPass" name="userPass" type="password" maxlength="16" value="<? echo $password; ?>" />
                </p>
                <p>
                    <label>Re-enter Password</label>
                    <br />
                    <input id="rePass" name="rePass" type="password" maxlength="16" value="<? echo $rePass; ?>" />
                </p>
                <p>
                    <label>Email</label>
                    <br />
                    <input name="emailAddress" type="text" id="emailAddress" size="25" maxlength="75" value="<? echo $emailAddress; ?>" />
                </p>	
                <p>
                    <label>First Name</label>
                    <br />
                    <input name="firstName" type="text" id="firstName" size="25" maxlength="30" value="<? echo $firstName2; ?>" />
                </p>	
                <p>
                    <label>Last Name</label>
                    <br />
                    <input name="lastName" type="text" id="lastName" size="25" maxlength="30" value="<? echo $lastName2; ?>" />
                </p>
                <p>
                    <label>Address Line</label>
                    <br />
                    <input name="address" type="text" id="address" size="55" maxlength="175" value="<? echo $streetAddress2; ?>" />
                </p>
                <p>
                    <label>City</label>
                    <br />
                    <input name="city" type="text" id="city" size="15" maxlength="25" value="<? echo $city2; ?>" />
                </p>
                <p>
                    <label>State</label>
                    <br />
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
                </p>
                <p>
                    <label>Zip</label>
                    <br />
                    <input name="zipCode" type="text" id="zipCode" size="6" maxlength="10" value="<? echo $zipCode2; ?>"  />
                </p>
                <p>
                    <label>Phone Number</label>
                    <br />
                    <input name="phoneNumber" type="text" id="phoneNumber" size="15" maxlength="15" value="<? echo $phoneNumber2; ?>"  />
                </p>
                <p class="no-border">
                    Please note: your username and first name will be displayed publically on this site. We will NOT share any additional information (email, full address, last name, or phone number) with anyone. Please do not share your password with anyone.<br />
                    <input class="button" type="submit" value="Create New Account" />
                    <input class="button" type="reset" value="Reset"/>	
                </p>
			</form>
            <!-- end new user registration form -->				 
		  	<p>This site is in no way affiliated with uGather.com or it's image library software.</p>
            
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

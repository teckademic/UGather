<?php
	require_once('validateData.php');
	require_once('objEvent.php');
	require_once('objUser.php');
	//start session to pass user ID
	session_start();
	
	//create variables
	$apostrophe 		= array("'"); // for fixing apostrophe issue
	$userID = 'jlh95';
	$eventID = '77';
	$ownerMessage = 'Test message send!';
	$ownerMessage = str_replace($apostrophe, "\'", $ownerMessage);
	
	/*$firstName = $_POST['firstName'];
	$firstName			= str_replace($apostrophe, "\'", $firstName);
	$lastName = $_POST['lastName'];
	$lastName			= str_replace($apostrophe, "\'", $lastName);
	$emailAddress = $_POST['emailAddress'];
	$streetAddress = $_POST['streetAddress'];
	$streetAddress		= str_replace($apostrophe, "\'", $streetAddress);
	$city = $_POST['city'];
	$city 				= str_replace($apostrophe, "\'", $city);
	$state = $_POST['state'];
	$zipCode = $_POST['zipCode'];
	$zipCode			= str_replace($apostrophe, "\'", $zipCode);
	$phoneNumber = $_POST['phoneNumber'];
	$phoneNumber		= str_replace($apostrophe, "\'", $phoneNumber);*/
	
	try 
	{
		checkValidUser();
		
		// form filled in?
	//	if (!filled_out($_POST)) 
	//	{ 
	//		throw new Exception("<br /><span style=\"color:red;\">You have not filled out the form completely. Profile not changed. Try again!</span>");
	//	}
		
		//checks that email is correct
//		if (!valid_email($emailAddress))
//		{
//			throw new Exception("<br /><span style=\"color:red;\">You have entered an invalid email address. Profile not changed. Please try again!</span>");
//		}
		
		// call update function for User table
		attendeeMassEmail($eventID, $ownerID, $ownerMessage);
		header('location: dashboard.php');
	}
	catch (Exception $exception) 
	{
		// detailed in code
	}
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
			displayNavigation("Dashboard");
		?>
	<!-- navigation ends-->	
	</div>						

	<!-- content starts -->
	<div id="content">

		<!-- main starts -->
		<div id="main">
        
			<h2>	
			<?php
            echo "".$_SESSION['valid_user']."'s Dashboard";
            ?>     
            </h2>   
            <h3>Edit Your User Profile</h3>
            
			<?php
				
				if ($exception)
					echo "<p>Error found: ",  $exception->getMessage(), "\n";
				
				// display change profile form if logged in!
				if(isLoggedIn())
				{ 
                  	$userID = $_SESSION['valid_user'];
                    $event = getAllUserDetails($userID);
        
                    if (is_array($event)) 
                    {
                        foreach ($event as $rows)  
                        {
                            ?> 
                            <form name="form1" method="post" action="editUserProfileExecute.php">
                             <table width="454" border="1">                          
                                <tr>
                                    <td>
                                        <strong>Current First Name: </strong>
                                    </td>
                                    <td>
                                        <font color="332616"><? echo $rows['firstName']; ?></font><br />
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>New First Name:</strong>
                                    </td>
                                    <td>
                                        <input name="firstName" type="text" id="firstName" value="<? echo $firstName; ?>" size="30" maxlength="30" >
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Current Last Name: </strong>
                                    </td>
                                    <td>
                                        <font color="332616"><? echo $rows['lastName']; ?></font>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>New Last Name:</strong>
                                    </td>
                                    <td>
                                        <input name="lastName" type="text" id="lastName" value="<? echo $lastName; ?>" size="30"  maxlength="30" >
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Current Address: </strong>
                                    </td>
                                    <td>
                                        <font color="332616"><? echo $rows['streetAddress']; ?></font>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>New Address:</strong>
                                    </td>
                                    <td><font color="332616">
                                        <input name="streetAddress" type="text" id="streetAddress" value="<? echo $streetAddress; ?>" size="30" maxlength="175" >
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
                                        <strong>New City:</strong>
                                    </td>
                                    <td>
                                        <input name="city" type="text" id="city" value="<? echo $city; ?>" size="30" maxlength="25" >
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Current State: </strong>
                                    </td>
                                    <td>
                                        <font color="332616"><? echo $rows['state']; ?></font>
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
                                        <input name="zipCode" type="text" id="zipCode" value="<? echo $zipCode; ?>" size="30" maxlength="10" >
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
                                        <input name="phoneNumber" type="text" id="phoneNumber" value="<? echo $phoneNumber; ?>" size="30" maxlength="15" >
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Current Email Address: :</strong>
                                    </td>
                                    <td>
                                        <font color="332616"><? echo $rows['emailAddress']; ?></font>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>New Email Address: </strong>
                                    </td>
                                    <td>
                                        <input name="emailAddress" type="text" id="emailAddress" value="<? echo $emailAddress; ?>" size="30" maxlength="75" >
                                    </td>
                                </tr>
                        </table>
                        <input name="Edit Profile" type="submit" id="Edit Profile" value="Submit Profile Changes">
                        </form>
						<? } // close foreach ($event as $rows)
					} // close if (is_array($event))
					else 
					{ ?>
						<p>Error: Could not display form!</p>
					<? }
				} // close if (isLoggedIn())
				// not logged in, don't display form
				else
				{ ?> 
                	<p>You cannot change your profile if not logged in. Please <a href="login.php">login to uGather</a>a>.</p>
                <? }
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

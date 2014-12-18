<?php	
	require_once('objUser.php');
	
	session_start();
	
	// if logged in, please redirect to dashboard
	if (isLoggedIn())
	{
		header('location: dashboard.php');	
	} // end if logged in
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
          	<!-- display new user registration form -->
            <form name="registerForm" method="post" action="registerExecute.php">	
                <p>
                    <label>Username (will be used to log you into uGather!)</label>
                    <br />
                    <input name="userId" type="text" id="userId" value="" size="30" maxlength="25" />
                </p>
                <p>
                    <label>Password (Must be 6 - 16 characters long) </label>
                    <br />
                    <input id="userPass" name="userPass" type="password" maxlength="16" />
                </p>
                <p>
                    <label>Re-enter Password</label>
                    <br />
                    <input id="rePass" name="rePass" value="" type="password" maxlength="16" />
                </p>
                <p>
                    <label>Email</label>
                    <br />
                    <input name="emailAddress" type="text" id="emailAddress" value="" size="25" maxlength="75" />
                </p>	
                <p>
                    <label>First Name</label>
                    <br />
                    <input name="firstName" type="text" id="firstName" value="" size="25" maxlength="30" />
                </p>	
                <p>
                    <label>Last Name</label>
                    <br />
                    <input name="lastName" type="text" id="lastName" value="" size="25" maxlength="30" />
                </p>
                <p>
                    <label>Address Line</label>
                    <br />
                    <input name="address" type="text" id="address" value="" size="55" maxlength="175" />
                </p>
                <p>
                    <label>City</label>
                    <br />
                    <input name="city" type="text" id="city" value="" size="15" maxlength="25" />
                </p>
                <p>
                    <label>State</label>
                    <br />
                    <select name="state"> 
                        <option value="AL" selected="selected">Alabama</option> 
                        <option value="AK">Alaska</option> 
                        <option value="AZ">Arizona</option> 
                        <option value="AR">Arkansas</option> 
                        <option value="CA">California</option> 
                        <option value="CO">Colorado</option> 
                        <option value="CT">Connecticut</option> 
                        <option value="DE">Delaware</option> 
                        <option value="DC">District of Columbia</option> 
                        <option value="FL">Florida</option> 
                        <option value="GA">Georgia</option> 
                        <option value="HI">Hawaii</option> 
                        <option value="ID">Idaho</option> 
                        <option value="IL">Illinois</option> 
                        <option value="IN">Indiana</option> 
                        <option value="IA">Iowa</option> 
                        <option value="KS">Kansas</option> 
                        <option value="KY">Kentucky</option> 
                        <option value="LA">Louisiana</option> 
                        <option value="ME">Maine</option> 
                        <option value="MD">Maryland</option> 
                        <option value="MA">Massachusetts</option> 
                        <option value="MI">Michigan</option> 
                        <option value="MN">Minnesota</option> 
                        <option value="MS">Mississippi</option> 
                        <option value="MO">Missouri</option> 
                        <option value="MT">Montana</option> 
                        <option value="NE">Nebraska</option> 
                        <option value="NV">Nevada</option> 
                        <option value="NH">New Hampshire</option> 
                        <option value="NJ">New Jersey</option> 
                        <option value="NM">New Mexico</option> 
                        <option value="NY">New York</option> 
                        <option value="NC">North Carolina</option> 
                        <option value="ND">North Dakota</option> 
                        <option value="OH">Ohio</option> 
                        <option value="OK">Oklahoma</option> 
                        <option value="OR">Oregon</option> 
                        <option value="PA">Pennsylvania</option> 
                        <option value="RI">Rhode Island</option> 
                        <option value="SC">South Carolina</option> 
                        <option value="SD">South Dakota</option> 
                        <option value="TN">Tennessee</option> 
                        <option value="TX">Texas</option> 
                        <option value="UT">Utah</option> 
                        <option value="VT">Vermont</option> 
                        <option value="VA">Virginia</option> 
                        <option value="WA">Washington</option> 
                        <option value="WV">West Virginia</option> 
                        <option value="WI">Wisconsin</option> 
                        <option value="WY">Wyoming</option> 
                    </select>
                </p>
                <p>
                    <label>Zip</label>
                    <br />
                    <input name="zipCode" type="text" id="zipCode" value="" size="6" maxlength="10" />
                </p>
                <p>
                    <label>Phone Number</label>
                    <br />
                    <input name="phoneNumber" type="text" id="phoneNumber" value="" size="15" maxlength="15" />
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

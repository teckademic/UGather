<?php
	require_once('database.php');
	require_once('objUser.php');
		
	function resultToArray($result) 
	{
	   	$res_array = array();
	   	for ($count = 0; $row = mysql_fetch_assoc($result); $count++) 
	   	{
		 	$res_array[$count] = $row;
   		}
		return $res_array;
	} // end function resultToArray
	
	// determine whether two values are equal!
	function isEqual($param1, $param2)
	{
			if ($param1 == $param2)
			{
				return true;
			}
			else
			{
				return false;
			}
	} // end function isEqual
	
	// display navigation bar
	function displayNavigation($current)
	{ ?>
		<ul>
        	<? if ($current == "Home") { ?>
				<li id="current"><a href="index.php">Home</a></li>
            <? } if ($current != "Home") { ?>
            	<li><a href="index.php">Home</a></li>
			<? } if ($current == "Events") { ?>
            	<li id="current"><a href="events.php">Events</a></li>
            <? } if ($current != "Events") { ?>
            	<li><a href="events.php">Events</a></li>
			<?php }
				if(isLoggedIn())
				{ ?>
					<? if ($current == "New Event") { ?>
                    	<li id="current"><a href="newEvent.php">Create Event</a></li>
					 <? } if ($current != "New Event") { ?>
                     	<li><a href="newEvent.php">Create Event</a></li>
				<? } }
			?> 
			<? if ($current == "Help") { ?>
            	<li id="current"><a href="help.php">Help</a></li>
			 <? } if ($current != "Help") { ?>
             	<li><a href="help.php">Help</a></li>
			<?php }
				if(isLoggedIn())
				{ ?>
					<? if ($current == "Dashboard") { ?>
                    	<li id="current"><a href="dashboard.php">Dashboard</a></li>
					 <? } if ($current != "Dashboard") { ?>
                     	<li><a href="dashboard.php">Dashboard</a></li>
				<? } }
			?>		
     	</ul>
	<? } // end function displayNavigation
	
	// display sidebar
	function displaySidebar()
	{
		if(!isLoggedIn())
		{ ?>
			<h2>Log In</h2>
			<form name="form1" method="post" action="checklogin.php">		
				<p>
				<label for="subject"></label>
				<label for="username">Username</label>
				<br />
				<input name="userID" type="text" id="userID" maxlength="20">
				</p>

				<p>
				<label for="password">Password</label>
				<br />
				<input name="password" type="password" id="password" maxlength="20">
				</p>
	
				<p class="no-border">
				<input class="button" type="submit" value="Submit" tabindex="3" />
				<input class="button" type="reset" value="Reset" tabindex="4" />	
				</p>
				<p><a class="main" href="register.php">Register</a>	| <a class="main" href="passwordRecovery.php">Forgot your password?</a>
			</form>	
		<? }
		else
		{ ?>
			<h2><? echo "".$_SESSION['valid_user'].""; ?></h2>
			<p><a href="dashboardOwnedEvents.php">view owned events</a><br />
            <a href="dashboardRegisteredEvents.php">view registered events</a><br /><br />
            
            <a href="viewUserProfile.php">view profile</a><br />
            <a href="editUserProfile.php">edit profile</a><br />
			<a href="editPassword.php">change password?</a><br /><br />
            
			<a href="logout.php">log out</a></p>
		<? }
	} // end function displaySidebar
	
	// function: displays a simple selecft
	// body for a time
	function displayTimeSelect()
	{
		?>
        	<option>12:00 AM</option>
            <option>12:30 AM</option>
            <option>01:00 AM</option>
            <option>01:30 AM</option>
            <option>02:00 AM</option>
            <option>02:30 AM</option>
            <option>03:00 AM</option>
            <option>03:30 AM</option>
            <option>04:00 AM</option>
            <option>04:30 AM</option>
            <option>05:00 AM</option>
            <option>05:30 AM</option>
            <option>06:00 AM</option>
            <option>06:30 AM</option>
            <option>07:00 AM</option>
            <option>07:30 AM</option>
            <option>08:00 AM</option>
            <option>08:30 AM</option>
            <option>09:00 AM</option>
            <option>09:30 AM</option>
            <option>10:00 AM</option>
            <option>10:30 AM</option>
            <option>11:00 AM</option>
            <option>11:30 AM</option>
            <option selected>12:00 PM</option>
            <option>12:30 PM</option>
            <option>01:00 PM</option>
            <option>01:30 PM</option>
            <option>02:00 PM</option>
            <option>02:30 PM</option>
            <option>03:00 PM</option>
            <option>03:30 PM</option>
            <option>04:00 PM</option>
            <option>04:30 PM</option>
            <option>05:00 PM</option>
            <option>05:30 PM</option>
            <option>06:00 PM</option>
            <option>06:30 PM</option>
            <option>07:00 PM</option>
            <option>07:30 PM</option>
            <option>08:00 PM</option>
            <option>08:30 PM</option>
            <option>09:00 PM</option>
            <option>09:30 PM</option>
            <option>10:00 PM</option>
            <option>10:30 PM</option>
            <option>11:00 PM</option>
            <option>11:30 PM</option>
        <?
	} // end function displayTimeSelect
	
	// function: displays a simple selecft
	// body for a state, with GA selected
	function displayStateSelect()
	{
		?>
        	<option value="AL">Alabama</option> 
            <option value="AK">Alaska</option> 
            <option value="AZ">Arizona</option> 
            <option value="AR">Arkansas</option> 
            <option value="CA">California</option> 
            <option value="CO">Colorado</option> 
            <option value="CT">Connecticut</option> 
            <option value="DE">Delaware</option> 
            <option value="DC">District of Columbia</option> 
            <option value="FL">Florida</option> 
            <option value="GA" selected="selected">Georgia</option> 
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
        <?
	} // end function displayStateSelect
	
	// function: displays a simple selecft
	// body for a time, given a certain 
	// selected time
	function displaySelectedTimeSelect($time)
	{
		?>
        	<? if(isEqual($time,"12:00 AM")) { ?>
                <option selected>12:00 AM</option> 
            <? } else { ?>
                <option>12:00 AM</option> 
            <? } if(isEqual($time,"12:30 AM")) { ?>
                <option selected>12:30 AM</option> 
            <? } else { ?>
                <option>12:30 AM</option> 
            <? } if(isEqual($time,"01:00 AM")) { ?>
                <option selected>01:00 AM</option> 
            <? } else { ?>
                <option>01:00 AM</option> 
            <? } if(isEqual($time,"01:30 AM")) { ?>
                <option selected>01:30 AM</option> 
            <? } else { ?>
                <option>01:30 AM</option> 
            <? } if(isEqual($time,"02:00 AM")) { ?>
                <option selected>02:00 AM</option> 
            <? } else { ?>
                <option>02:00 AM</option> 
            <? } if(isEqual($time,"02:30 AM")) { ?>
                <option selected>02:30 AM</option> 
            <? } else { ?>
                <option>02:30 AM</option> 
            <? } if(isEqual($time,"03:00 AM")) { ?>
                <option selected>03:00 AM</option> 
            <? } else { ?>
                <option>03:00 AM</option> 
            <? } if(isEqual($time,"03:30 AM")) { ?>
                <option selected>03:30 AM</option> 
            <? } else { ?>
                <option>03:30 AM</option> 
            <? } if(isEqual($time,"04:00 AM")) { ?>
                <option selected>04:00 AM</option> 
            <? } else { ?>
                <option>04:00 AM</option> 
            <? } if(isEqual($time,"04:30 AM")) { ?>
                <option selected>04:30 AM</option> 
            <? } else { ?>
                <option>04:30 AM</option> 
            <? } if(isEqual($time,"05:00 AM")) { ?>
                <option selected>05:00 AM</option> 
            <? } else { ?>
                <option>05:00 AM</option> 
            <? } if(isEqual($time,"05:30 AM")) { ?>
                <option selected>05:30 AM</option> 
            <? } else { ?>
                <option>05:30 AM</option> 
            <? } if(isEqual($time,"06:00 AM")) { ?>
                <option selected>06:00 AM</option> 
            <? } else { ?>
                <option>06:00 AM</option> 
            <? } if(isEqual($time,"06:30 AM")) { ?>
                <option selected>06:30 AM</option> 
            <? } else { ?>
                <option>06:30 AM</option> 
            <? } if(isEqual($time,"07:00 AM")) { ?>
                <option selected>07:00 AM</option> 
            <? } else { ?>
                <option>07:00 AM</option> 
            <? } if(isEqual($time,"07:30 AM")) { ?>
                <option selected>07:30 AM</option> 
            <? } else { ?>
                <option>07:30 AM</option> 
            <? } if(isEqual($time,"08:00 AM")) { ?>
                <option selected>08:00 AM</option> 
            <? } else { ?>
                <option>08:00 AM</option> 
            <? } if(isEqual($time,"08:30 AM")) { ?>
                <option selected>08:30 AM</option> 
            <? } else { ?>
                <option>08:30 AM</option> 
            <? } if(isEqual($time,"09:00 AM")) { ?>
                <option selected>09:00 AM</option> 
            <? } else { ?>
                <option>09:00 AM</option> 
            <? } if(isEqual($time,"09:30 AM")) { ?>
                <option selected>09:30 AM</option> 
            <? } else { ?>
                <option>09:30 AM</option> 
            <? } if(isEqual($time,"10:00 AM")) { ?>
                <option selected>10:00 AM</option> 
            <? } else { ?>
                <option>10:00 AM</option> 
            <? } if(isEqual($time,"10:30 AM")) { ?>
                <option selected>10:30 AM</option> 
            <? } else { ?>
                <option>10:30 AM</option> 
            <? } if(isEqual($time,"11:00 AM")) { ?>
                <option selected>11:00 AM</option> 
            <? } else { ?>
                <option>11:00 AM</option> 
            <? } if(isEqual($time,"11:30 AM")) { ?>
                <option selected>11:30 AM</option> 
            <? } else { ?>
                <option>11:30 AM</option> 
            <? } if(isEqual($time,"12:00 PM")) { ?>
                <option selected>12:00 PM</option> 
            <? } else { ?>
                <option>12:00 PM</option> 
            <? } if(isEqual($time,"12:30 PM")) { ?>
                <option selected>12:30 PM</option> 
            <? } else { ?>
                <option>12:30 PM</option> 
            <? } if(isEqual($time,"01:00 PM")) { ?>
                <option selected>01:00 PM</option> 
            <? } else { ?>
                <option>01:00 PM</option> 
            <? } if(isEqual($time,"01:30 PM")) { ?>
                <option selected>01:30 PM</option> 
            <? } else { ?>
                <option>01:30 PM</option> 
            <? } if(isEqual($time,"02:00 PM")) { ?>
                <option selected>02:00 PM</option> 
            <? } else { ?>
                <option>02:00 PM</option> 
            <? } if(isEqual($time,"02:30 PM")) { ?>
                <option selected>02:30 PM</option> 
            <? } else { ?>
                <option>02:30 PM</option> 
            <? } if(isEqual($time,"03:00 PM")) { ?>
                <option selected>03:00 PM</option> 
            <? } else { ?>
                <option>03:00 PM</option> 
            <? } if(isEqual($time,"03:30 PM")) { ?>
                <option selected>03:30 PM</option> 
            <? } else { ?>
                <option>03:30 PM</option> 
            <? } if(isEqual($time,"04:00 PM")) { ?>
                <option selected>04:00 PM</option> 
            <? } else { ?>
                <option>04:00 PM</option> 
            <? } if(isEqual($time,"04:30 PM")) { ?>
                <option selected>04:30 PM</option> 
            <? } else { ?>
                <option>04:30 PM</option> 
            <? } if(isEqual($time,"05:00 PM")) { ?>
                <option selected>05:00 PM</option> 
            <? } else { ?>
                <option>05:00 PM</option> 
            <? } if(isEqual($time,"05:30 PM")) { ?>
                <option selected>05:30 PM</option> 
            <? } else { ?>
                <option>05:30 PM</option> 
            <? } if(isEqual($time,"06:00 PM")) { ?>
                <option selected>06:00 PM</option> 
            <? } else { ?>
                <option>06:00 PM</option> 
            <? } if(isEqual($time,"06:30 PM")) { ?>
                <option selected>06:30 PM</option> 
            <? } else { ?>
                <option>06:30 PM</option> 
            <? } if(isEqual($time,"07:00 PM")) { ?>
                <option selected>07:00 PM</option> 
            <? } else { ?>
                <option>07:00 PM</option> 
            <? } if(isEqual($time,"07:30 PM")) { ?>
                <option selected>07:30 PM</option> 
            <? } else { ?>
                <option>07:30 PM</option> 
            <? } if(isEqual($time,"08:00 PM")) { ?>
                <option selected>08:00 PM</option> 
            <? } else { ?>
                <option>08:00 PM</option> 
            <? } if(isEqual($time,"08:30 PM")) { ?>
                <option selected>08:30 PM</option> 
            <? } else { ?>
                <option>08:30 PM</option> 
            <? } if(isEqual($time,"09:00 PM")) { ?>
                <option selected>09:00 PM</option> 
            <? } else { ?>
                <option>09:00 PM</option> 
            <? } if(isEqual($time,"09:30 PM")) { ?>
                <option selected>09:30 PM</option> 
            <? } else { ?>
                <option>09:30 PM</option> 
            <? } if(isEqual($time,"10:00 PM")) { ?>
                <option selected>10:00 PM</option> 
            <? } else { ?>
                <option>10:00 PM</option> 
            <? } if(isEqual($time,"10:30 PM")) { ?>
                <option selected>10:30 PM</option> 
            <? } else { ?>
                <option>10:30 PM</option> 
            <? } if(isEqual($time,"11:00 PM")) { ?>
                <option selected>11:00 PM</option> 
            <? } else { ?>
                <option>11:00 PM</option> 
            <? } if(isEqual($time,"11:30 PM")) { ?>
                <option selected>11:30 PM</option> 
            <? } else { ?>
                <option>11:30 PM</option> 
            <? } ?>
        <?
	} // end function displaySelectedTimeSelect
	
	// function to return a state select
	// body with a specific selected state
	function displaySelectedStateSelect($state)
	{
		?>
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
        <?
	} // end function displaySelectedStateSelect
?>
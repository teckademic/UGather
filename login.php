<?php	
	require_once('objUser.php');
	
	session_start();
	
	if (isLoggedIn())
		header('location: dashboard.php');
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
			<?php
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
					<p><br />
					<a href="viewUserProfile.php">view profile</a>
					<br />
					<a href="changePassword.php">change password?</a>
					<br />
					<a href="logout.php">log out</a>
					</p>
				<? }
			?>			 

		</div>
        <!-- end main -->
				
        <!-- sidebar begins -->
        <div id="sidebar">  
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

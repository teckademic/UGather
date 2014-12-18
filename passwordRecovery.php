<?php	
	require_once('objUser.php');
	
	session_start();
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
			displayNavigation("Help");
		?>
	<!-- navigation ends-->	
	</div>						
			
	<!-- content starts -->
	<div id="content">
	
        <!-- main starts -->
        <div id="main">
			
		  <h2>Reset Password</h2>
					
			   <form name="form1" method="post" action="passwordRecoveryExecute.php">			
				<p>
				  <label for="subject"></label>
				  <label for="username">Username</label>
					<br />
				  <input name="userID" type="text" id="userID" maxlength="30">
				</p>

				<p class="no-border">
				  <input class="button" name="Change Password" type="submit" id="Change Password" value="Submit">
				</p>
				<p><a class="main" href="register.php">Register</a>				
	      </form>				 

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

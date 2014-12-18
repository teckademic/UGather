<?php
	require_once('validateData.php');	
	require_once('objCommon.php');
	require_once('objUser.php');
	
	//start session to pass user ID
	session_start();
	// cannot see if not logged in!
	checkValidUser();
	
	//create variables
	$oldPass = $_POST['old_pass'];
	$newPass = $_POST['new_pass'];
	$newPass2 = $_POST['new_pass2'];
	
	try 
	{	
		// check password has only letters, numbers, and some special characters
		if (preg_match( "@[^a-z0-9&.-]+@i", $newPass))
		{
			throw new Exception('<span style="color: #FF0000;">Your password may contain only numbers (0-9), letters A-Z (upper or lowercase: a-z and A-Z), and a few special characters (& . -). No spaces allowed. Please try again.</span>');
		} // end if
		
		// form filled in?
		if (!filled_out($_POST)) 
		{ 
			throw new Exception("<br /><span style=\"color:red;\">You have not filled out the form completely. Password not changed. Try again!</span>");
		}

		// both new passwords the same?
		if ($newPass != $newPass2) 
		{
			 throw new Exception("<br /><span style=\"color:red;\">Passwords entered were not the same. Password not changed. Try again!</span>");
		}

		// first new password 6-16 characters?
		if ((strlen($newPass) > 16) || (strlen($newPass) < 6)) 
		{
			throw new Exception("<br /><span style=\"color:red;\">New password must be between 6 and 16 characters. Password not changed. Try again!</span>");
		}
		
		// second new password 6-16 characters?
		if ((strlen($newPass2) > 16) || (strlen($newPass2) < 6)) 
		{
			throw new Exception("<br /><span style=\"color:red;\">New password must be between 6 and 16 characters. Password not changed. Try again!</span>");
		}
	
		// attempt update
		updatePassword($_SESSION['valid_user'], $oldPass, $newPass);
		notifyPasswordChange($_SESSION['valid_user']);
		header('location: editPasswordConfirmation.php');
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
            <h3>Edit Password</h3>
            
            <?php
				
				if ($exception)
					echo "<p>Error found: ",  $exception->getMessage(), "\n";
				
				// display change password form if logged in!
				if(isLoggedIn())
				{ ?>
                <!-- start edit password form -->
					<form name="editPasswordForm" method="post" action="editPasswordExecute.php">
                      <table width="460" border="1" cellspacing="0" cellpadding="0">
                        <tr>
                          <td>Old Password: </td>
                          <td><input name="old_pass" type="password" id="old_pass" maxlength="16" ></td>
                        </tr>
                        <tr>
                          <td>New Password: </td>
                          <td><input name="new_pass" type="password" id="new_pass" maxlength="16" ></td>
                        </tr>
                        <tr>
                          <td>Repeat New Password: </td>
                          <td><input name="new_pass2" type="password" id="new_pass2" maxlength="16" ></td>
                        </tr>
                      </table>
                      <input name="Change Password" type="submit" id="Change Password" value="Change Password">
                    </form>
                    <!-- end edit password form -->
				<? }
				// not logged in, don't display form
				else
				{ ?> 
                	<p>You cannot change your password if not logged in. Please <a href="login.php">login to uGather</a>a>.</p>
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


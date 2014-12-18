<?php	
	require_once('objUser.php');
	
	session_start();
	// cannot see page if not logged in!
	checkValidUser();
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
            <h3>Edit Your Password</h3>

			<?php
				if(isLoggedIn())
				{ ?>
                	<p>Complete the following form to change your password:</p>
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
                      <input name="Change Password" type="submit" id="Change Password" value="Change Password" maxlength="16" >
                    </form>
                    <!-- end edit password form -->
				<? }
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

<?php	
	require_once('objUser.php');
	
	session_start();
	
	$userID = $_SESSION['valid_user'];
	
	// if not logged in, can't see this page!
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
            <?php
                $userID = $_SESSION['valid_user'];
                $event = getAllUserDetails($userID);
                
                if (is_array($event)) 
                {
                    foreach ($event as $rows)  
                    {
                        ?> <h2>	
						<?php
                        echo "".$_SESSION['valid_user']."'s Dashboard";
                        ?>     
                        </h2>   
                        <h3>Your User Profile</h3>
                         <table width="520" border="1">
                            <tr>
                                <td width="111"><strong><font color="332616">User ID:</font></strong></td>
                                <td width="393"><font color="332616"><? echo $rows['userID']; ?></font></td>
                            </tr>
                            <tr>
                                <td><strong><font color="332616">First Name:</font></strong></td>
                                <td><font color="332616"><? echo $rows['firstName']; ?></font></td>
                            </tr>
                            <tr>
                                <td><strong><font color="332616">Last Name:</font></strong></td>
                                <td><font color="332616"><? echo $rows['lastName']; ?></font></td>
                            </tr>
                            <tr>
                                <td><strong><font color="332616">Address:</font></strong></td>
                                <td><font color="332616"><? echo $rows['streetAddress']; ?></font></td>
                            </tr>
                            <tr>
                                <td><strong><font color="332616">City:</font></strong></td>
                                <td><font color="332616"><? echo $rows['city']; ?></font></td>
                            </tr>
                            <tr>
                                <td><strong><font color="332616">State:</font></strong></td>
                                <td><font color="332616"><? echo $rows['state']; ?></font></td>
                            </tr>
                            <tr>
                                <td><strong><font color="332616">Zip:</font></strong></td>
                                <td><font color="332616"><? echo $rows['zipCode']; ?></font></td>
                            </tr>
                            <tr>
                                <td><strong><font color="332616">Phone Number:</font></strong></td>
                                <td><font color="332616"><? echo $rows['phoneNumber']; ?></font></td>
                            </tr>
                            <tr>
                                <td><strong><font color="332616">Email Address:</font></strong></td>
                                <td><font color="332616"><? echo $rows['emailAddress']; ?></font></td>
                            </tr>
                        </table>
                        <h3>Other Options</h3>
                        <p><a href="editUserProfile.php"><b>edit your profile</b></a><br />
                        <a href="viewPublicProfile.php?profileID=<? echo $userID; ?>"><b>view your public profile</b></a></p>
                    <? }
                }
                else 
                {
                    echo "Error";
                }
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

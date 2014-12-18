<?php	
	require_once('objUser.php');
	
	session_start();
	
	$userID = $_SESSION['valid_user'];
	// get ID for the profile
	$profileID = $_GET['profileID'];
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
			displayNavigation("Events");
		?>
	<!-- navigation ends-->	
	</div>					
			
	<!-- content starts -->
	<div id="content">
	
        <!-- main starts -->
        <div id="main">
            <?php
                $userID = $_SESSION['valid_user'];
                $event = getPublicUserDetails($profileID);
                
                if (is_array($event)) 
                {
                    foreach ($event as $rows)  
                    {
                        ?> <h2>Public Profile</h2>   
                        <h3><? echo $profileID; ?></h3>
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
                                <td><strong><font color="332616">Events:</font></strong></td>
                                <td><font color="332616"><a href="eventsByUser.php?ownerID=<? echo $profileID; ?>"><b>views events created by <? echo $profileID; ?></b></a><br />
                                <a href="registrationsByUser.php?attendeeID=<? echo $profileID; ?>"><b>views events <? echo $profileID; ?> has registered for</b></a></font></td>
                            </tr>           
                        </table>
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

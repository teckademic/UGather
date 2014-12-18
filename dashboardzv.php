<?php

	require_once('objCommon.php');

	require_once('objEvent.php');

	require_once('objUser.php');

	

	session_start();

	checkValidUser();

	$userID = $_SESSION['valid_user'];

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
			<div  id="nav">
		<ul>
			<li><a href="index.html">Home</a></li>
			<li><a href="events.html">Events</a></li>
			<li id="current"><a href="help.html">Help</a></li>
		</ul>
		  <h3>Events</h3>

            <ul>

            	<li><a href="dashboardOwnedEvents.php"><b>Owned Events</b></a> --> view a list of events you've created</li>

                <li><a href="dashboardRegisteredEvents.php"><b>Registered Events</b></a> --> view a list of events you've registered for</li>

            </ul>  

			<h3>Account Options</h3>

            <ul>

            	<li><a href="viewUserProfile.php"><b>View User Profile</b></a> --> view your account details</li>

                <li><a href="editUserProfile.php"><b>Edit User Profile</b></a> --> edit your account details</li>

                <li><a href="editPassword.php"><b>Edit Password</b></a> --> edit your account password</li>

            </ul>

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




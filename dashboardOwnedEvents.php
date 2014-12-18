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

            <h3>Owned Events</h3>

		  	<p>Below is a list of all the events that you have created: <br />

		  	<?php

				$events_array = showUserEvents($userID);

				if (!is_array($events_array)) 

				{ ?>

		  			<p>You have not created any events. You can create a new event on <a href="/newEvent.php">this page</a>.</p>

				<? }

				else

				{

					echo "<ul>";



					foreach ($events_array as $row)  

					{

						?>

                    	<li><b><a href="viewEvent.php?eventID=<? echo $row['eventID']; ?>"><? echo $row['eventName']; ?></a></b> [ <a href="editEvent.php?eventID=<? echo $row['eventID']; ?>">edit event</a> | <a href="viewAttendees.php?eventID=<? echo $row['eventID']; ?>">view attendees</a> ]</li>

                    	<?

					}



					echo "</ul>";

				}



				echo "<hr />";

			?>

            </p>  

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




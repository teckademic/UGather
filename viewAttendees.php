<?php	
	require_once('objEvent.php');
	require_once('objUser.php');
	session_start();
	$userID = $_SESSION['valid_user'];

	include("database.php");
	
	$eventID = $_GET['eventID'];
	$event = getAllEventDetails($eventID);
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
                $eventID = $_GET['eventID'];
                $event = getAllEventDetails($eventID);
                
                if (is_array($event)) 
                {
                    foreach ($event as $rows)  
                    { $eventName = $rows['eventName'];
						?> 
                        <h2>Attendees: <? echo $eventName; ?></h2>
                     <? }
                }
            ?>
            <h3>All Registered Attendees</h3>
           	<table width="520" border="1">
              <tr>
                <td><strong>UserID</strong></td>
                <td><strong>First Name</strong></td>
                <td><strong>Status</strong></td>
                <td><strong>Profile</strong></td>
              </tr>
			<?php
				$eventID = $_GET['eventID'];
				$sql = "select User.userID, User.firstName, eventAttendees.status from User inner join eventAttendees on User.userID = eventAttendees.userID and eventAttendees.eventID = '".$eventID."' ORDER BY User.userID DESC";
				$result=mysql_query($sql);
				$i = 0;
				while($rows=mysql_fetch_array($result))
				{ 
					// Start looping table row
					if ($i % 2 != "0") # An odd row
					  $bgcolor = "A28C6A";
					else # An even row
					  $bgcolor = "8BB201"; 
					$i++;
				?>
				<tr>  
					<td align="left" bgcolor="#<? echo $bgcolor ?>"><strong><font color="#332616"><? echo $rows['userID']; ?></font></strong></td>
					<td align="left" bgcolor="#<? echo $bgcolor ?>"><strong><font color="#332616"><? echo $rows['firstName']; ?></font></strong></td> 
					<td align="left" bgcolor="#<? echo $bgcolor ?>"><strong><font color="#332616"><? echo $rows['status']; ?></font></strong></td>
                    <td align="left" bgcolor="#<? echo $bgcolor ?>"><strong><font color="#332616"><a href="viewPublicProfile.php?profileID=<? echo $rows['userID']; ?>"><b>public profile</b></a></font></strong></td>
				</tr>
			<?php
			// Exit looping and close connection
			}
			mysql_close();
			?>
			</table>
            
            <h3>Options & Statistics</h3>
            <p><b>
            <?php
			$userID 	= $_SESSION['valid_user'];
			$eventID 	= $_GET['eventID'];
			if(checkUserOwnsEvent($userID, $eventID))
			{ ?>
            <a href="attendeesMessage.php?eventID=<? echo $eventID; ?>">send message to all attendees</a><br />
            <? }
            ?>
            <a href="viewEvent.php?eventID=<? echo $eventID; ?>">view event page for <? echo $eventName; ?></a></b></p>

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

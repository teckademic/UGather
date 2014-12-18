<?php	
	require_once('objEvent.php');
	require_once('objUser.php');
	session_start();
	$userID = $_SESSION['valid_user'];
	
	$ownerID = $_GET['ownerID'];
	
	// set defaults, just in case
	$eventSortBy = "eventID";
	$eventSortHow = "descending";
	
	if ($_GET['sortBy'] && $_GET['sortHow'])
	{
		$eventSortBy = $_GET['sortBy'];
		$eventSortHow = $_GET['sortHow'];
	}
	if ($_POST['sortBy'] && $_POST['sortHow']) 
	{
		$eventSortBy = $_POST['sortBy'];
		$eventSortHow = $_POST['sortHow'];
	}
	
	$fieldsNeeded = "eventID, eventName, locationVenue, userID, date";
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
				
			<h2>Events by <? echo $ownerID; ?></h2>
            
            <p>Below is a list of all <b>current</b> events created by <? echo $ownerID; ?>. Click on an event name for more details, or to register. 
            <!-- sort form -->
            <form name="form1" method="post" action="eventsByUser.php?ownerID=<? echo $ownerID; ?>" style="margin: 2px 2px; padding: 2px 2px 2px 2px; border: 1px solid #EEE8E1; background: #FAF7F5;">
            	&nbsp;&nbsp;Sort By: &nbsp;&nbsp;
                <? echo getEventSortFormDisplay($eventSortBy, $eventSortHow); ?>
                &nbsp;&nbsp;<input class="button" type="submit" value="Change Sort!" />
          	</form>
 
			<?php
				include("database.php");
				if ($eventSortHow == "descending")
				{
					$result = getCurrentEventDetailsOrderByDescWhere($fieldsNeeded, $eventSortBy, $ownerID);
				}
				if ($eventSortHow == "ascending")
				{
					$result = getCurrentEventDetailsOrderByAscWhere($fieldsNeeded, $eventSortBy, $ownerID);	
				}
				
				if (!$result)
					throw new Exception("Error! Cannot find any events created by this user!");
					
				$total_items = mysql_num_rows($result); 
				$limit = $_GET['limit']; 
				$type = $_GET['type']; 
				$page = $_GET['page']; 
				$i = 0;
				
				// set default if: $limit is empty, non numerical, less than 10, greater than 50 
				if((!$limit)  || (is_numeric($limit) == false) || ($limit < 10) || ($limit > 50)) 
				{ 
					 $limit = 5; //default 
				} 
				// set default if: $page is empty, non numerical, less than zero, greater than total available 
				if((!$page) || (is_numeric($page) == false) || ($page < 0) || ($page > $total_items)) 
				{ 
					  $page = 1; //default 
				} 
				
				// get number of pages and set limit
				$total_pages = ceil($total_items / $limit); 
				$set_limit = $page * $limit - ($limit); 
				
				// get sql query
				if ($eventSortHow == "descending")
				{
					$q = getCurrentEventDetailsOrderByDescLimitWhere($fieldsNeeded, $eventSortBy, $set_limit, $limit, $ownerID);
				}
				if ($eventSortHow == "ascending")
				{
					$q = getCurrentEventDetailsOrderByAscLimitWhere($fieldsNeeded, $eventSortBy, $set_limit, $limit, $ownerID);	
				}
				
  				if(!$q)
				{
					throw new Exception ("Error! Cannot limit events created by this user.");	
				}
				
				$err = mysql_num_rows($q); 
       			if($err == 0) 
				{
					echo "<p><span style=\"color: #FF0000;\">This user has created no current events on uGather!</span></p>"; 
				} // end if
				else
				{ 
					?>   
						<p><a href="eventsByUser.php?ownerID=<? echo $ownerID; ?>&amp;sortBy=<? echo $eventSortBy; ?>&amp;sortHow=<? echo $eventSortHow; ?>&amp;limit=5&amp;page=1">5 per page</a> | 
						<a href="eventsByUser.php?ownerID=<? echo $ownerID; ?>&amp;sortBy=<? echo $eventSortBy; ?>&amp;sortHow=<? echo $eventSortHow; ?>&amp;limit=10&amp;page=1">10 per page</a> | 
						<a href="eventsByUser.php?ownerID=<? echo $ownerID; ?>&amp;sortBy=<? echo $eventSortBy; ?>&amp;sortHow=<? echo $eventSortHow; ?>&amp;limit=20&amp;page=1">20 per page</a>
						</p>
					<?php
					
					// print table header
					?>
					<table width="520" border="1">
					  <tr>
						<td><strong>ID</strong></td>
						<td><strong>Event</strong></td>
						<td><strong>Location</strong></td>
						<td><strong>Host</strong></td>
						<td><strong>Date</strong></td>
					  </tr>
					<?php
					
					//show data matching query: 
					while($code = mysql_fetch_object($q)) 
					{ 
						// Start looping table row
						if ($i % 2 != "0") # An odd row
							$bgcolor = "A28C6A";
						else # An even row
							$bgcolor = "8BB201"; 
						$i++;
					
					?> 
						<tr>  
							<td align="left" bgcolor="#<? echo $bgcolor ?>"><strong><font color="#332616"><? echo $code->eventID; ?></font></strong></td>
							<td align="left" bgcolor="#<? echo $bgcolor ?>"><strong><font color="#332616"><a href="viewEvent.php?eventID=<? echo $code->eventID; ?>" class="event" /><? echo $code->eventName; ?></a></font></strong></td>
							<td align="left" bgcolor="#<? echo $bgcolor ?>"><strong><font color="#332616"><? echo $code->locationVenue; ?></font></strong></td> 
							<td align="left" bgcolor="#<? echo $bgcolor ?>"><strong><font color="#332616"><a href="viewPublicProfile.php?profileID=<? echo $code->userID; ?>" class="event" /><? echo $code->userID; ?></a></font></strong></td>
							<td align="left" bgcolor="#<? echo $bgcolor ?>"><strong><font color="#332616"><? echo $code->date; ?></font></strong></td>
						</tr>
						 <!--echo("item: ".$code->eventID."<BR>"); -->
					<?php } 
					
					// end table
					?>
						</table>
					<?php
					
					$cat = urlencode($cat); //makes browser friendly 
	
					$prev_page = $page - 1; 
					
					if($prev_page >= 1) 
					{ 
						?>
							<b>&lt;&lt;</b> <a href="eventsByUser.php?ownerID=<? echo $ownerID; ?>&amp;sortBy=<? echo $eventSortBy; ?>&amp;sortHow=<? echo $eventSortHow; ?>&amp;limit=<? echo $limit; ?>&amp;page=<? echo $prev_page; ?>"><b>previous</b></a>
						<?php 
					} 
					
					for($a = 1; $a <= $total_pages; $a++) 
					{ 
						if($a == $page) 
						{ 
							echo("<b> $a</b> | "); //no link 
						} 
						else 
						{ 
							?>
								<a href="eventsByUser.php?ownerID=<? echo $ownerID; ?>&amp;sortBy=<? echo $eventSortBy; ?>&amp;sortHow=<? echo $eventSortHow; ?>&amp;limit=<? echo $limit; ?>&amp;page=<? echo $a; ?>"> <? echo $a; ?> </a> | 
							<?php
						} 
					} 
					
					$next_page = $page + 1; 
					if($next_page <= $total_pages) 
					{ 
						?>
							<a href="eventsByUser.php?ownerID=<? echo $ownerID; ?>&amp;sortBy=<? echo $eventSortBy; ?>&amp;sortHow=<? echo $eventSortHow; ?>&amp;limit=<? echo $limit; ?>&amp;page=<? echo $next_page; ?>"><b>Next</b></a> &gt; &gt;
						<?php
					} 
				} // end else for finding events in the database ?> 
            
			<!-- options listing -->
            <h3>Options & Statistics</h3>
            <p>
            <a href="viewPublicProfile.php?profileID=<? echo $ownerID; ?>"><b>view <? echo $ownerID; ?>'s public profile</b></a><br />
            <a href="registrationsByUser.php?attendeeID=<? echo $ownerID; ?>"><b>view events <? echo $ownerID; ?> has registered for</b></a></p>

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


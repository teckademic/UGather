<?php	
	require_once('objEvent.php');
	require_once('objUser.php');
	
	session_start();
	
	if ($_GET['find'])
	{
		$field = $_GET['field'];
		$find = $_GET['find'];
		$searching = "yes";
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
			displayNavigation("Events");
		?>
	<!-- navigation ends-->	
	</div>					

	<!-- content starts -->
	<div id="content">

		<!-- main starts -->
		<div id="main">
        
			<h2>Search For Events     
            </h2>   
            <h3>Search</h3>

                    <!-- start search form -->
					<form name="searchForm" method="post" action="search.php">
                      <table width="460" border="1" cellspacing="0" cellpadding="0">
                        <tr>
                          <td>Search For: </td>
                          <td><input type="text" name="find" id="find" /></td>
                        </tr>
                        <tr>
                          <td>Search By: </td>
                          <td>
                          	<Select NAME="field">
                            	<option value="allFields">all fields</option>
                            	<option VALUE="eventName">event name</option>
                                <option VALUE="userID">event host ID</option>
                                <option VALUE="locationVenue">location name</option>
                                <option VALUE="address">address</option>
                                <option VALUE="city">city</option>
                                <option VALUE="state">state</option>
                                <option VALUE="phoneNumber">phone number</option>
                          	</Select>
                          </td>
                        </tr>
                      </table>
                      <input type="hidden" name="searching" value="yes" />
                        <input type="submit" name="search" value="Search" />
                    </form>
                    <!-- end search form -->
                    
                    <?php

                    //This is only displayed if they have submitted the form 
                    if ($searching =="yes") 
                    { 
                    	echo "<h2>Results</h2><p>"; 
					
                    
                    //If they did not enter a search term we give them an error 
                    if ($find == "") 
                    { 
                    echo "<p>You forgot to enter a search term"; 
                    exit; 
                    } 
                    
                    // Otherwise we connect to our Database 
                    include('database.php');
                    
                    // We preform a bit of filtering 
                    $find = strtoupper($find); 
                    $find = strip_tags($find); 
                    $find = trim ($find); 
                    
                    //Now we search for our search term, in the field the user specified 
					if ($field == "allFields")
					{
						$data = mysql_query("SELECT * FROM Event WHERE date(date) >= now() LIKE'%$find%'");
					}
					else
					{
						$data = mysql_query("SELECT * FROM Event WHERE date(date) >= now() AND upper($field) LIKE'%$find%'");
                    }
					
					$total_items = mysql_num_rows($data); 
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
					
					$sql = "SELECT * FROM Event WHERE date(date) >= now() AND upper($field) LIKE'%$find%' ORDER BY eventName DESC LIMIT $set_limit, $limit";
					$q = mysql_query($sql);
					if(!$q) die(mysql_error()); 
					 $err = mysql_num_rows($q); 
					 
					 ?>   
						<p><a href="search.php?limit=5&amp;page=1&amp;field=<? echo $field; ?>&amp;find=<? echo $find; ?>">5 per page</a> | 
						<a href="search.php?limit=10&amp;page=1&amp;field=<? echo $field; ?>&amp;find=<? echo $find; ?>">10 per page</a> | 
						<a href="search.php?limit=20&amp;page=1&amp;field=<? echo $field; ?>&amp;find=<? echo $find; ?>">20 per page</a>
						</p>
					<?php
					
                    //And we display the results 
                    while($result = mysql_fetch_array($q)) 
                    { 
					
						echo "Event: ";
						echo "<a href=viewEvent.php?eventID=";
						echo $result['eventID'];
						echo ">";
						echo $result['eventName'];
						echo "</a> [ <a href=viewAttendees.php?eventID=";
						echo $result['eventID'];
						echo ">view attendees to ";
						echo $result['eventName'];
						echo "</a> ]";
						echo "<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date: ";
						echo $result['date'];
						echo "<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Start Time: ";
						echo $result['startTime'];
						echo "<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;End Time: ";
						echo $result['endTime'];
						echo "<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Host: ";
						echo "<a href=viewPublicProfile.php?profileID=";
						echo $result['userID'];
						echo ">";
						echo $result['userID']; 
						echo "</a>";
						echo "<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Location: ";
						echo $result['locationVenue'];
						echo "<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Address: ";
						echo $result['address'];
						echo ", ";
						echo $result['city'];
						echo ", ";
						echo $result['state'];
						echo " ";
						echo $result['zipCode'];
						echo "<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Contact Phone Number: ";
						echo $result['phoneNumber'];
						echo "<br /><br />";
                    } 
					
					$cat = urlencode($cat); //makes browser friendly 
	
					$prev_page = $page - 1; 
					
					if($prev_page >= 1) 
					{ 
						?>
							<b>&lt;&lt;</b> <a href="search.php?limit=<? echo $limit; ?>&amp;page=<? echo $prev_page; ?>&amp;field=<? echo $field; ?>&amp;find=<? echo $find; ?>"><b>previous</b></a>
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
								<a href="search.php?limit=<? echo $limit; ?>&amp;page=<? echo $a; ?>&amp;field=<? echo $field; ?>&amp;find=<? echo $find; ?>"> <? echo $a; ?> </a> | 
							<?php
						} 
					} 
					
					$next_page = $page + 1; 
					if($next_page <= $total_pages) 
					{ 
						?>
							<a href="search.php?limit=<? echo $limit; ?>&amp;page=<? echo $next_page; ?>&amp;field=<? echo $field; ?>&amp;find=<? echo $find; ?>"><b>Next</b></a> &gt; &gt;
						<?php
					} 
                    
                    //This counts the number or results - and if there wasn't any it gives them a little message explaining that 
                    $anymatches=mysql_num_rows($data); 
                    if ($anymatches == 0) 
                    { 
                    echo "Sorry, but we can not find an entry to match your query<br><br>"; 
                    } 
                    
                    //And we remind them what they searched for 
                    echo "<b>Searched For:</b> " .$find; 
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

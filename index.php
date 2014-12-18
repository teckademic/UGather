<?php	
	require_once('objUser.php');
	
	session_start();
	$userID = $_SESSION['valid_user'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>

<title>uGather</title>

<link rel="stylesheet" href="images/frontCSS.css" type="text/css" />

<style type="text/css">
<!--
.style2 {font-size: 14px}
.style3 {
	font-size: 18px;
	color: #333366;
}
.style5 {color: #333366}
.style6 {font-size: 14px; color: #333366; }
-->
</style>
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
			displayNavigation("Home");
		?>
	<!-- navigation ends-->	
	</div>					
			
	<!-- content starts -->
	<div id="content">
	
		<div id="main">
				
			
		  	 <?php
				if(!isLoggedIn())
				{ ?>
                	<h2>Welcome</h2>
               	<? }
				else
				{ ?>
                	<h2>Welcome, <? echo "".$_SESSION['valid_user'].""; ?></h2>
                <? }
			?>

			<h3>uGather is all about your event!</h3>
            <p>Use uGather to create an event or sign up for an event that has already been created. Before you do anything you must <a class="main" href="register.php">register</a>. It is free and easy.</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p><strong>uGather.info is a site that has been created to meet the requirements of CSIS 4850 at Kennesaw State University. No real events should be planned from this site.</strong></p>  

        <!-- main ends -->	
        <p>This site is in no way affiliated with uGather.com or it's image library software.</p>		
        
        <!-- end main -->
        </div>
				
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

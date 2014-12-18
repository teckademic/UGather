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
			displayNavigation("Help");
		?>
	<!-- navigation ends-->	
	</div>					

	<!-- content starts -->
	<div id="content">

		<!-- main starts -->
		<div id="main">
        
			<h2>Help</h2>
            <p>You log in or register and create or join an event. What else do you need?</p>
            
            <p><b>Frequently Asked Questions</b><br />
            <ol>
            	<li>I forgot my password. Is there a way to recover it?</li>
                	<ul>
                    	<li>Yes, you can reset your password using <a href="passwordRecovery.php"><b>this form</b></a>.</li>
                    </ul>
                <li>I forgot my username. Is there a way to recover it?</li>
                	<ul>
                    	<li>No, not at this time.</li>
                    </ul>
                <li>I'd like to change my password. Is there a way to do this?</li>
                	<ul>
                    	<li>Yes, you can change your password using <a href="editPassword.php"><b>this form</b></a>.</li>
                    </ul>
            	<li>Can I actually use this website?</li>
                	<ul>
                    	<li>Sure. <strong>But we wouldn't recommend actually planning events on it at this time.</strong> uGather.info is a site that has been created to meet the requirements of CSIS 4850 at Kennesaw State University. The system is still in development and testing phases, and may be prone to errors. We can offer <strong>no technical support</strong>.</li>
                    </ul>
               	<li>Is this site affiliated with uGather.com?</li>
                	<ul>
                    	<li>No. This site is in no way affiliated with uGather.com or it's image library software.</li>
                    </ul>
            </ol>
      
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

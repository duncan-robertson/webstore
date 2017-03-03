<!DOCTYPE html>

<html>
<head>
    <link rel="stylesheet" type="text/css" href="style.css"></link>
    <title>E-Utilities</title>

    <script
    src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js">
    </script>

    <script 
    src="actions.js">
    </script>

    <script>
    //Script toggles extra contact information on click
    $(document).ready(function(){
    $("#show").click(function () {
	$("#contact").toggle();
	});
    });
    </script>
    
    <?php
    //This php will auto logout someone whose last request was greater than 15 minutes ago
    session_start();
    if (isset($_SESSION['time']) && ((time() - $_SESSION['time']) > 15*60)) {
    	echo "<script type='text/javascript'>alert('Session Expired. You have been logged out.');</script>";
    	session_unset();
    	session_destroy();
    }
    else if (isset($_SESSION['username'])){ //refresh timestamp
    	$_SESSION['time'] = time();
    }
    ?>

</head>

<body>
    <div class="navbar">
	<img src="logo.png" id="logo"/>
	<?php
		if (isset($_SESSION['username'])) {
			echo "<span class='right'> <a href='account.php'>" . $_SESSION['username'] . "</a> | <a href='#' onclick='Logout();'>Logout</a></span>";
		}
		else {
			echo "<span class='right'>Not logged in | <a href='login.php'>Login</a></span>";
		}
	?>
	<h1>E-Utilities</h1>
	<ul>
	    <li><a href="index.php">Home</a></li>
	    <li><a href="register.php">Register</a></li>
	    <li><a href="catalog.php">Catalog</a></li>
        <li><a href="review.php">Review</a></li>
	    <li><a href="sitemap.php">Sitemap</a></li>
	</ul>
    </div>

    <div>
	<h2>Contact Information</h2>
	
	<p style="display: inline-block; margin-bottom: 2px;"><b>Duncan Robertson</b> (Project Lead) </p>

    <!-- This div will be toggled visible -->
	<div id="show">toggle info</div>
	    <p id="contact" style="display:none; margin-top: 0;">
	    Duncan Robertson is the primary developer and maintainer of this website.
	    He should be contacted for all matters concerning this website.<br>
	    Student Number: 1149681<br>
	    Email: <a href="mailto:robertda@mcmaster.ca">robertda@mcmaster.ca</a></p>
    </div>
    
    <div>
	<div class="footer" align="center">
	    <p>This website is best experienced in the latest Chrome or Firefox browsers. Mobile browsers 
	    are unsupported, and to retain a good experience the browser window should be at least 500x500 
	    pixels. This website is a product of Duncan Robertson<p>
	</div>
    </div>
</body>

</html>

<!DOCTYPE html>

<html>
<head>
    <link rel="stylesheet" type="text/css" href="style.css"/>
    <title>E-Utilities</title>
    
    <script
    src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js">
    </script>

    <script 
    src="actions.js">
    </script>

    <script>
    //This script will slowly fade in the logo bar
    $(document).ready(function(){
    $(".navbar").fadeIn("slow");
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
    <div class="container">
    <div class="navbar" style="display:none;">
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
	    <li><a href="register.php">Register</a></li>
	    <li><a href="contact.php">Contact Us</a></li>
	    <li><a href="catalog.php">Catalog</a></li>
        <li><a href="review.php">Review</a></li>
        <li><a href="sitemap.php">Sitemap</a></li>
	</ul>
    </div>
    </div>

    <div class="welcome">
	    <h2>Welcome to E-Utilities</h2>
	    
	    <p>Our store provides reliable access to quality factory direct electronics. This includes
	    useful accessories, addons, cabling, and generic replacement equipment. The price
	    is low, but the quality is high. Here at E-Utilities our equipment might not look pretty,
	    but we value durability and performance over aesthetic. If any of the above interests you, please visit</p>

	    <div align="center" style="box-shadow: none;">
	    <a href="catalog.php">Our Catalog</a>
	    </div>
	
	<p>We hope that you enjoy your stay</p>
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

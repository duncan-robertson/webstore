<!DOCTYPE html>

<html>
<head>
    <link rel="stylesheet" type="text/css" href="style.css"/>
    <title>E-Utilities</title>
    <meta charset="UTF-8">

    <script 
    src="actions.js">
    </script>

    <script
    src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js">
    </script>

    <script>
    //Script reads html5 local storage to compare the entered username and password
    function auth() {
        $("#message").html('');
        
        $.ajax({
            type: 'POST',
            url: 'secureLogin.php',
            dataType: 'json',
            data: {username: document.forms["main"]["uname"].value,
                   password: document.forms["main"]["password"].value},
            success: function (obj, textstatus) {
                if (!('error' in obj)) {
                    $("#message").css("color", "green");
                    $("#message").append(obj.result);
                    location.reload();
                    return true;
                }
                else {
                    $("#message").css("color", "red");
                    $("#message").append(obj.error);
                    return false;
                }
            }           
        });
    }

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
	    <li><a href="contact.php">Contact Us</a></li>
	    <li><a href="catalog.php">Catalog</a></li>
        <li><a href="review.php">Review</a></li>
	    <li><a href="sitemap.php">Sitemap</a></li>
	</ul>
    </div>
    

    <div>
    
    <div id="message" style="text-align:center;">
    </div>
	
    <h2>User Login</h2>
	<form action="javascript:void(0);" name="main" onsubmit="return auth();">
	    <label>User Name:</label><input type="text" name="uname">
	    <br>
	    <label>Password:</label><input type="password" name="password">
	    <br>
	    <br>
	    <input type = "reset" value="Clear Form"/>
	    <input type = "submit" value="Submit"/>
	</form>
    </div>

    <div>
	<div class="footer">
	    <p>This website is best experienced in the latest Chrome or Firefox browsers. Mobile browsers 
	    are unsupported, and to retain a good experience the browser window should be at least 500x500 
	    pixels. This website is a product of Duncan Robertson<p>
	</div>
    </div>
</body>

</html>

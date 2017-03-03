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

    <script>
    //Validate information update
    function update() {
        $("#errorbox").html('');
       
        var y = true;

        //First Name
        x = document.forms["info"]["fname"].value;

        if (x==null || x=="") {
            $("#errorbox").append('<p>First name is required</p>');
            y = false;
        }
        else if (badName(x) == true) {
            $("#errorbox").append('<p>First name may only contain alphabetic characters</p>');
            y = false;
        }

        //Last Name
        x = document.forms["info"]["lname"].value;

        if (x==null || x=="") {
            $("#errorbox").append('<p>Last name is required</p>');
            y = false;
        }
        else if (badName(x) == true) {
            $("#errorbox").append('<p>Last name may only contain alphabetic characters</p>');
            y = false;
        }

        x = document.forms["info"]["pnum"].value;
        //Remove unneccessary characters like - and ()
        x = x.replace(/[^0-9]/g, '');

        //Check phone number
        if (x!=null && x!="" && x.toString().length!=10) {
            $("#errorbox").append('<p>Phone number is invalid</p>');
            y = false;
        }
        else {
            document.forms["info"]["pnum"].value = x;
        }

        //Check email address
        x = document.forms["info"]["email"].value;
        if (x==null || x=="") {
            $("#errorbox").append('<p>Email address is required</p>');
            y = false;
        }
        else {
            x = x.match(/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/g);
            if (x==null || x=="") {
                $("#errorbox").append('<p>Invalid email address</p>');
                y = false;
            }
            else {
                document.forms["info"]["email"].value = x;
            }
        }

        //Check address
        x = document.forms["info"]["addr"].value;
        if (x==null || x=="") {
            $("#errorbox").append('<p>Address is required</p>');
            y = false;
        }
        else if(badAddress(x)==true) {
            $("#errorbox").append('<p>Invalid address</p>');
            y = false;
        }

        //Check second address
        x = document.forms["info"]["addr2"].value;
        if(badAddress(x)==true) {
            $("#errorbox").append('<p>Invalid secondary address</p>');
            y = false;
        }

        //Check city name
        x = document.forms["info"]["city"].value;
        if (x==null || x=="") {
            $("#errorbox").append('<p>City is required</p>');
            y = false;
        }
        else if(badName(x)==true) {
            $("#errorbox").append('<p>City may only contain alphanumeric characters</p>');
            y = false;
        }

        //Check province
        if (document.forms["info"]["province"].value=="non") {
            $("#errorbox").append('<p>You must select a province</p>');
            y = false;
        }
        
        //Check postalcode
        x = document.forms["info"]["postal"].value;
        if (x==null || x=="") {
            $("#errorbox").append('<p>Postal code is required</p>');
            y = false;
        }
        else if (x.match(/[a-zA-Z][0-9][a-zA-Z][0-9][a-zA-Z][0-9]/) == null) {
            $("#errorbox").append('<p>Postal code is invalid</p>');
            y = false;
        }

        if (y) {
            $.ajax({
                type: 'POST',
                url: 'updateAccount.php',
                dataType: 'json',
                data: {type: 'update',
                    first_name: document.forms["info"]["fname"].value,
                    last_name: document.forms["info"]["lname"].value,
                    phone: document.forms["info"]["pnum"].value,
                    email: document.forms["info"]["email"].value,
                    address: document.forms["info"]["addr"].value,
                    address2: document.forms["info"]["addr2"].value,
                    city: document.forms["info"]["city"].value,
                    province: document.forms["info"]["province"].value,
                    postal: document.forms["info"]["postal"].value
                },
                success: function (obj, textstatus) {
                    if ( !('error' in obj)) {
                        $("#errorbox").css("color", "green");
                        $("#errorbox").append(obj.result);
                        $("#errorbox").css("color", "red");
                    }
                    else {
                        $("#errorbox").append(obj.error);
                        y=false;
                    }
                }
            });
        }

        return y;
    }
    </script>
    <script>
    function pchange() {
        $("#errorbox").html('');
        y = true;

        //Check password
        x = document.forms["pass"]["password"].value; 
        if (x==null || x=="") {
            $("#errorbox").append('<p>Password is required</p>');
            y = false;
        }
        else if(x.toString().length > 15 || x.toString().length < 3) {
            $("#errorbox").append('<p>Password must be between 3 and 15 characters</p>');
            y = false;
        }
        else if(badSpecial(x)==true) {
            $("#errorbox").append('<p>Password may only contain alpha-numeric symbols as well as - _ \' .<p>');
            y = false;
        }

        //Check passwords match
        if (x != document.forms["pass"]["verify"].value) {
            $("#errorbox").append('<p>Passwords do not match<p>');
            y = false;
        }

        if (y) {
            $.ajax({
                type: 'POST',
                url: 'updateAccount.php',
                dataType: 'json',
                data: {type: 'password',
                    password: document.forms["pass"]["password"].value
                },
                success: function (obj, textstatus) {
                    if ( !('error' in obj)) {
                        $("#errorbox").css("color", "green");
                        $("#errorbox").append(obj.result);
                        $("#errorbox").css("color", "red");
                    }
                    else {
                        $("#errorbox").append(obj.error);
                        y=false;
                    }
                }
            });
        }
        return y;
    }
    </script>
    <script>
    function remAccount() {
        y = true;

        $.ajax({
            type: 'POST',
            url: 'updateAccount.php',
            dataType: 'json',
            data: {type: 'delete'},
            success: function (obj, textstatus) {
                if ( !('error' in obj)) {
                    $("#errorbox").css("color", "green");
                    $("#errorbox").append(obj.result);
                    $("#errorbox").css("color", "red");
                    alert('Account Locked for removal. Logging out');
                    window.location.href="login.php";
                }
                else {
                    $("#errorbox").append(obj.error);
                    y=false;
                }
            }
        });

        return y;
    }
    </script>

    <script>
    function adminRemove(x) {
        y = true;

        $.ajax({
            type: 'POST',
            url: 'updateAccount.php',
            dataType: 'json',
            data: {type: 'adminRemove', targetname: x},
            success: function (obj, textstatus) {
                if ( !('error' in obj)) {
                    $("#errorbox").css("color", "green");
                    $("#errorbox").append(obj.result);
                    $("#errorbox").css("color", "red");
                    alert('Account Removed');
                    location.reload();
                }
                else {
                    $("#errorbox").append(obj.error);
                    y=false;
                }
            }
        });

        return y;
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
        <li><a href="index.php">Home</a></li>
	    <li><a href="register.php">Register</a></li>
	    <li><a href="contact.php">Contact Us</a></li>
	    <li><a href="catalog.php">Catalog</a></li>
        <li><a href="review.php">Review</a></li>
        <li><a href="sitemap.php">Sitemap</a></li>
	</ul>
    </div>
    </div>

    <div>
        <!-- This div becomes filled with form errors -->
        <div id="errorbox" style="margin-left: 0px; padding-left: 0px;">
        </div>

        <?php
        if (!isset($_SESSION['username'])) {
            echo "<p>You are not logged in. Please log in to see account information.</p>";
        }
        else if (isset($_SESSION['user']) && $_SESSION['user'] === 'standard') {
                       
            $conn = new mysqli("localhost", "php", "phppass", "web");
            if ($conn->connect_error) {
                echo '<p>There was an error retreiving your information</p>';
                die ("Connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT * FROM users WHERE username = '" . $_SESSION['username'] . "'";
            $result = $conn->query($sql);

            if ($result->num_rows == 0) {
                echo '<p>There was an error retreiving your information</p>';
            }
            else {
                echo "<p>Update User Information</p>";
                $row = $result->fetch_assoc();
                $conn->close();

                $full = '';
                switch ($row['province']) {
                    case 'on':
                        $full = 'Ontario';
                        break;
                    case 'qc':
                        $full = 'Quebec';
                    case 'bc':
                        $full = 'British Columnbia';
                    case 'al':
                        $full = 'Alberta';
                        break;
                    case 'mn':
                        $full = 'Manitoba';
                        break;
                    case 'sk':
                        $full = 'Saskatchewan';
                        break;
                    case 'ns':
                        $full = 'Nova Scotia';
                        break;
                    case 'nb':
                        $full = 'New Brunswick';
                        break;
                    case 'nf':
                        $full = 'Newfoundland and Labrador';
                        break;
                    case 'pe':
                        $full = 'Prince Edward Island';
                        break;
                    case 'nw':
                        $full = 'Northwest Territories';
                        break;
                    case 'yk':
                        $full = 'Yukon';
                        break;
                    case 'nv':
                        $full = 'Nunavut';
                        break;
                }

                echo "<form action='javascript:void(0);' name='info' onsubmit='return update();' method='post'> " ;
                echo "<label>First Name</label><input type='text' name='fname' size= 10 value='" . $row['first_name'] . "'> <br> " ;
                echo "<label>Last Name</label><input type='text' name='lname' size= 15 value='" . $row['last_name'] . "'> <br> " ;
                echo "<label>Phone Number</label><input type='text' name='pnum' size= 12 value='" . $row['phone'] . "'> <br> " ;
                echo "<label>E-mail Address</label><input type='text' name='email' value='" . $row['email'] . "'> <br> " ;
                echo "<label>Address</label><input type='text' name='addr' size= 20 value='" . $row['address'] . "'> <br> " ;
                echo "<label>Address 2</label><input type='text' name='addr2' size= 20 value='" . $row['address2'] . "'> <br> " ;
                echo "<label>City</label><input type='text' name='city' size= 20 value='" . $row['city'] . "'> <br> " ;
                echo "<label>Province</label><select name='province'>";
                echo "<option value='" . $row['province'] . "' selected>" . $full . "</option>" ;
                echo "<option value='on'>Ontario</option> " ;
                echo "<option value='qc'>Quebec</option> " ;
                echo "<option value='bc'>British Columbia</option> " ;
                echo "<option value='al'>Alberta</option> " ;
                echo "<option value='mn'>Manitoba</option> " ;
                echo "<option value='sk'>Saskatchewan</option> " ;
                echo "<option value='ns'>Nova Scotia</option> " ;
                echo "<option value='nb'>New Brunswick</option> " ;
                echo "<option value='nf'>Newfoundland and Labrador</option> " ;
                echo "<option value='pe'>Prince Edward Island</option> " ;
                echo "<option value='nw'>Northwest Territories</option> " ;
                echo "<option value='yk'>Yukon</option> " ;
                echo "<option value='nv'>Nunavut</option> </select> <br> " ;
                echo "<label>Postal Code</label><input type='text' name='postal' size= 6 maxlength= 6 value='" . $row['postal'] . "'> <br><br> " ;
                echo "<input type='submit' value='Update'/> </form>" ;

                echo "<p>Change Password</p>" ;
                echo "<form action='javascript:void(0);' name='pass' onsubmit='return pchange();' method='post'>" ;
                echo "<label>New Password</label><input type='password' name='password'> <br>" ;
                echo "<label>Verify</label><input type='password' name='verify'> <br> <br>" ;
                echo "<input type='submit' value='Change Password'/> </form>" ;
                
                echo "<p>Request Account Deletion</p>" ;
                echo "<button onclick='return remAccount();'>DELETE MY ACCOUNT</button>";
                echo "<br>";

            }
        }
        else if (isset($_SESSION['user']) && $_SESSION['user'] === 'administrator') {
            echo "<a href='user_info.php'>All user information</a>";
            echo "<br><br><a href='product_info.php'>All product information</a>";

            $conn = new mysqli("localhost", "php", "phppass", "web");
            if ($conn->connect_error) {
                echo '<p>There was an error retreiving your information</p>';
                die ("Connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT username FROM users WHERE remove='1'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<p>Accounts marked for deletion</p>";
                echo "<table border='1'> <tr> <th>Username</th> <th>Delete</th> </tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>" . $row['username'] . "</td><td>";
                    echo "<button onclick='return adminRemove(\"" . $row['username'] . "\");'>Remove</button>";
                    echo "</td></tr>";
                }
                echo "</table>";
            }
            else {
                echo "<p>No user accounts to be removed</p>";
            }

            $conn->close();

        }
        else {
            echo "<p>We ecountered a problem</p>";
        }

        ?>

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
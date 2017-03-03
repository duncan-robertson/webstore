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
 
    //Validates all form data
    function validate() {
        //Reset the errorbox
        $("#errorbox").html('');
        
        var x = document.forms["main"]["fname"].value;
        //If any error is found y is set to false and the user must fix their errors
        var y = true;
        
        //Check first name
        if (x==null || x=="") {
            $("#errorbox").append('<p>First name is required</p>');
            y = false;
        }
        else if (badName(x) == true) {
            $("#errorbox").append('<p>First name may only contain alphabetic characters</p>');
            y = false;
        }
        
        //Check last name
        x = document.forms["main"]["lname"].value;

        if (x==null || x=="") {
            $("#errorbox").append('<p>Last name is required</p>');
            y = false;
        }
        else if (badName(x) == true) {
            $("#errorbox").append('<p>Last name may only contain alphabetic characters</p>');
            y = false;
        }

        x = document.forms["main"]["pnum"].value;
        //Remove unneccessary characters like - and ()
        x = x.replace(/[^0-9]/g, '');

        //Check phone number
        if (x!=null && x!="" && x.toString().length!=10) {
            $("#errorbox").append('<p>Phone number is invalid</p>');
            y = false;
        }
        else {
            document.forms["main"]["pnum"].value = x;
        }
        
        //Check email address
        x = document.forms["main"]["email"].value;
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
                document.forms["main"]["email"].value = x;
            }
        }
        
        //Check username
        x = document.forms["main"]["uname"].value;
        if (x==null || x=="") {
            $("#errorbox").append('<p>Username is required</p>');
            y = false;
        }
        else if(x.toString().length > 15) {
            $("#errorbox").append('<p>Username must be fewer than 15 characters</p>');
            y = false;
        }
        else if(badSpecial(x)==true) {
            $("#errorbox").append('<p>Username may only contain alpha-numeric symbols as well as - _ \' .<p>');
            y = false;
        }

        //Check password
        x = document.forms["main"]["password"].value; 
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
        if (x != document.forms["main"]["verify"].value) {
            $("#errorbox").append('<p>Passwords do not match<p>');
            y = false;
        }

        //Check address
        x = document.forms["main"]["addr"].value;
        if (x==null || x=="") {
            $("#errorbox").append('<p>Address is required</p>');
            y = false;
        }
        else if(badAddress(x)==true) {
            $("#errorbox").append('<p>Invalid address</p>');
            y = false;
        }

        //Check second address
        x = document.forms["main"]["addr2"].value;
        if(badAddress(x)==true) {
            $("#errorbox").append('<p>Invalid secondary address</p>');
            y = false;
        }

        //Check city name
        x = document.forms["main"]["city"].value;
        if (x==null || x=="") {
            $("#errorbox").append('<p>City is required</p>');
            y = false;
        }
        else if(badName(x)==true) {
            $("#errorbox").append('<p>City may only contain alphanumeric characters</p>');
            y = false;
        }

        //Check province
        if (document.forms["main"]["province"].value=="non") {
            $("#errorbox").append('<p>You must select a province</p>');
            y = false;
        }
        
        //Check postalcode
        x = document.forms["main"]["postal"].value;
        if (x==null || x=="") {
            $("#errorbox").append('<p>Postal code is required</p>');
            y = false;
        }
        else if (x.match(/[a-zA-Z][0-9][a-zA-Z][0-9][a-zA-Z][0-9]/) == null) {
            $("#errorbox").append('<p>Postal code is invalid</p>');
            y = false;
        }   
        
        //If form is valid run the php code to create the user account
        if (y==true) {
            $.ajax({
                type: "POST",
                url: 'createAccount.php',
                dataType: 'json',
                data: {username: document.forms["main"]["uname"].value,
                       first_name: document.forms["main"]["fname"].value,
                       last_name: document.forms["main"]["lname"].value,
                       phone: document.forms["main"]["pnum"].value,
                       email: document.forms["main"]["email"].value,
                       password: document.forms["main"]["password"].value,
                       address: document.forms["main"]["addr"].value,
                       address2: document.forms["main"]["addr2"].value,
                       city: document.forms["main"]["city"].value,
                       province: document.forms["main"]["province"].value,
                       postal: document.forms["main"]["postal"].value
                      },
                success: function (obj, textstatus) {
                    if ( !('error' in obj)) {
                        $("#errorbox").css("color", "green");
                        $("#errorbox").append(obj.result);
                        $("#errorbox").css("color", "red");
                    }
                    else {
                        $("#errorbox").append(obj.error);
                        y = false;
                    }
                }
            });
        }

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
	    <li><a href="contact.php">Contact Us</a></li>
	    <li><a href="catalog.php">Catalog</a></li>
        <li><a href="review.php">Review</a></li>
	    <li><a href="sitemap.php">Sitemap</a></li>
	</ul>
    </div>

    <div class="content">
	<h2>Create an Account</h2>
	
	<p>To purchase items from our store we require that you have an account. Your account, along with 
	purchasing, allows you to view your orders and shipment information. This keeps you safe, and allows 
	us to be a responsible store front.</p>
	<p>Please understand that we cater only to Canadians, so unfortunately
	those from other countries cannot register an account.</p>

    <!-- This div becomes filled with form errors -->
    <div id="errorbox">
    </div>

    <span class="imprtnt" style="margin-left: 20px">*&nbsp</span>Denotes a mandatory field
    
    <!-- Main entry form -->
	<form action="javascript:void(0);" name="main" onsubmit="return validate();" method="post">
        <!-- The form is split into 2 divs so they can appear side by side -->
        <div style="float:left;">
        <h3>Account Information</h3>
	    <label>First Name:</label><input type = "text" name = "fname" size = 10>
	    <label class="imprtnt">&nbsp*</label>
	    <br>
	    <label>Last Name:</label><input type = "text" name = "lname" size = 15>
	    <label class="imprtnt">&nbsp*</label>
	    <br>
	    <label>Phone Number:</label><input type = "text" name = "pnum" size = 12>
	    <br>
	    <label>E-mail Address:</label><input type = "text" name = "email">
	    <label class="imprtnt">&nbsp*</label>
	    <br> 
	    <label>User Name:</label><input type = "text" name = "uname">
	    <label class="imprtnt">&nbsp*</label>
	    <br>
	    <label>Password:</label><input type = "password" name = "password">
	    <label class="imprtnt">&nbsp*</label>
	    <br>
	    <label>Verify Password: </label><input type = "password" name = "verify">
	    <label class="imprtnt">&nbsp*</label>
	    <br>
        </div>

        <div style="float:left;">
	    <h3>Shipping Information</h3>
	    <label>Address:</label><input type = "text" name = "addr">
	    <label class="imprtnt">&nbsp*</label>
	    <br>
	    <label>Address 2:</label><input type = "text" name = "addr2">
	    <p id="small">(unit, suite, etc)</p>
	    <label>City:</label><input type = "text" name = "city" size = 20>
	    <label class="imprtnt">&nbsp*</label>
	    <br>
	    <label>Province:</label><select name = "province">
            <option value="non"></option>
            <option value="on">Ontario</option>
		    <option value="qc">Quebec</option>
		    <option value="bc">British Columbia</option>
		    <option value="al">Alberta</option>
		    <option value="mn">Manitoba</option>
		    <option value="sk">Saskatchewan</option>
		    <option value="ns">Nova Scotia</option>
		    <option value="nb">New Brunswick</option>
		    <option value="nf">Newfoundland and Labrador</option>
		    <option value="pe">Prince Edward Island</option>
		    <option value="nw">Northwest Territories</option>
		    <option value="yk">Yukon</option>
		    <option value="nv">Nunavut</option>
	    </select>
	    <label class="imprtnt">&nbsp*</label>
	    <br>
	    <label>Postal Code:</label><input type = "text" name = "postal" size = 6 maxlength=6>
	    <label class="imprtnt">&nbsp*</label>
	    <br>
	    <br>
	    <input type = "reset" value="Clear Form"/>
	    <input type = "submit" value="Submit"/>	    
        </div>
	</form>
	
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

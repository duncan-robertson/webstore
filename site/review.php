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
    //When a review is posted a new div is created containing the review
    function postr() {
        $("#errorbox").html('');
        y = true;

        $.ajax({
            type: "POST",
            url: 'addReview.php',
            dataType: 'json',
            data: {type: 'site',
                rating: document.forms["review"]["rating"].value,
                text: document.forms["review"]["text"].value
            },
            success: function (obj, textstatus) {
                if ( !('error' in obj)) {
                    $("#errorbox").css("color", "green");
                    $("#errorbox").append(obj.result);
                    $("#errorbox").css("color", "red");
                    location.reload();
                }
                else {
                    $("#errorbox").append(obj.error);
                    y = false;
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
<head>

<body>
    <div class="container">
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
                <li><a href="sitemap.php">Sitemap</a></li>
            </ul>
        </div>
    </div>

    <div>
        <h2>Review Us</h2>

        <div id="errorbox">
        </div>
        <!-- Primary review form -->
        <?php
            if (isset($_SESSION['user']) && $_SESSION['user'] === 'standard') {
                echo "<form action='javascript:void(0);' name='review' onsubmit='return postr()'>";
                    echo "<label style='width: auto;'>Name:&nbsp</label>";
                    echo "<label style='width: auto;'>Rating out of 5:&nbsp</label>";
                    echo "<select name='rating'>";
                        echo "<option value='5'>5</option>";
                        echo "<option value='4'>4</option>";
                        echo "<option value='3'>3</option>";
                        echo "<option value='2'>2</option>";
                        echo "<option value='1'>1</option>";
                    echo "</select>";
                    echo "<br>";
                    echo "<br>";
                    echo "<span>Write your review here</span>";
                    echo "<br>";
                    echo "<textarea rows='10' cols='50' name='text' maxlength='512'></textarea>";
                    echo "<br>";
                    echo "<input type='submit' value='Submit'/>";
                echo "</form>";

            }
            else {
                echo "<p>You must be logged in to review our site</p>";
            }
        ?>
    </div>

    <div id="reviews">
        <?php
        $conn = new mysqli("localhost", "php", "phppass", "web");
        
        if ($conn->connect_error) {
            $aResult['error'] = 'Error connecting to database';
            die("Error connecting to database: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM site_reviews";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<div><p><b>" . $row['user'] . "</b> gave us a " . $row['rating'] . "/5</p>";
                echo "<p>" . $row['review'] . "</p><p>" . $row['date'] . "</p></div>";
            }
        }

        $conn->close();

        ?>

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

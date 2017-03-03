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
    //This function is called by the search form and searchs the webpage
    function s() {
        //Get search text and convert to lower case
        var y = document.forms["search"]["text"].value;
        y = y.toLowerCase();

        $(".desc").each(function(index){
            if(index != 0){
                //Hide the current container
                $(this).parent().parent().hide();
                
                //if search text is blank reveal the container
                if (y == "" || y == null){
                    $(this).parent().parent().show();
                }
                //if text from the description matches search reveal the container
                else if ($(this).text().toLowerCase().indexOf(y) >= 0){
                    $(this).parent().parent().show();
                }

            }
        });
        
        //Perform the same search action on the price field
        $(".price").each(function(index){
            if(index != 0){
                if($(this).text().toLowerCase().indexOf(y) >= 0){
                    $(this).parent().parent().show();
                }
            }
        });
        
        window.alert("Search Complete");

        return false;
    }
    </script>

    <script>
    //This script will open a popup window with an image
    //Main script concept is courtesy of Will Master
    var PopupImageContainer = new Image();
    var PopupImageSRC = new String();
    
    function PopImage(imagesrc) {
        //imagesrc must contain a string
        if( length.imagesrc < 1 ) { return; }

        var loadDelay = PopupImageSRC.length ? 1 : 750;
        PopupImageSRC = imagesrc;
        PopupImageContainer.src = PopupImageSRC;
        setTimeout("PopupImageDisplay()",loadDelay);
    }
    
    function PopupImageDisplay() {
        //set window to size of image
        var iw = parseInt(PopupImageContainer.width);
        var ih = parseInt(PopupImageContainer.height);
        var ww = iw + 50;
        var hh = ih + 100;
        var properties = 'height=' + hh + ',width=' + ww + ',resizable=yes,location=no';
        
        //open window and inject the image html
        var picture = window.open('','',properties);
        picture.document.writeln('<html><head><title>Image</title>');
        picture.document.writeln('<script language="JavaScript"> function CloseMe() { self.close(); } <'+'/script>');
        picture.document.write('<'+'/head><body onBlur="CloseMe()"><center>');
        picture.document.write('<img src="' + PopupImageSRC + '" width="' + iw + '" height="' + ih + '" border="0">');
        picture.document.write('<input type="button" onClick="window.close()" value="Close Window">');
        picture.document.writeln('<'+'/center><'+'/body><'+'/html>');
    }

    function buy(product) {
        alert('Adding '+ document.forms[product]["quantity"].value+ ' '+product+' to cart');

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
                <li><a href="review.php">Review</a></li>
                <li><a href="sitemap.php">Sitemap</a></li>
            </ul>
        </div>
    </div>

    <div>
        <h2>E-Utilities Product Catalog</h2>
        
        <!-- Search Form -->
        <div>
            <form action="javascript:void(0);" name="search" onsubmit="return s()">
                <input type="text" name="text"><input type="submit" value="Search"/>
            </form>
        </div>

        <div class="product">
            <div class="twrapper">
            <div class="image table">
                <p><b>Image</b></p>
            </div>
            
            <div class="desc table">
                <p><b>Description</b></p>
            </div>

            <div class="buy table">
                <p><b>Buy</b></p>
            </div>
            
            <div class="price table">
                <p><b>Price</b></p>
            </div>
            </div>
        </div>

        <?php
        $conn = new mysqli("localhost", "php", "phppass", "web");

        if ($conn->connect_error) {
            echo '<p>There was an error attempting to display our catalog</p>';
            die ("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM catalog";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo   '<div class="product">
                        <div class="twrapper">
                        
                        <div class="image table">
                            <img src="' . $row["image_path"] . '" onclick="PopImage(\'' . $row["image_path"] . '\')"/>
                        </div>

                        <div class="desc table">
                            <p><b>'. $row["name"] . '</b></p>
                            <p>' . $row["description"] . '</p>
                        </div>

                        <div class="buy table">
                            <p>Quantity: ' . $row["quantity"] . '</p>
                            <form action="javascript:void(0);" name="' . $row["name"] . '" onsubmit="return buy(\'' . $row["name"] . '\');" method="post">
                            <input type="text" name="quantity" size=2 value=1> x
                            <input type="submit" value="Cart"/>
                            </form>
                        </div>

                        <div class="price table">
                            <p>$' . money_format("%i", $row["price"]) . '</p>
                        </div>
                        
                        </div>
                        </div>';   
            }

        } else {
            echo '<p>There was an error attempting to display our catalog</p>';
        }

        $conn->close();
        ?> 

    </div>


    <div>
        <div class="footer" align="center">
            <p>This website is best experienced in the latest Chrome or Firefox browsers. Mobile browsers
            are unsupported, and to retain a good experience the browser window should be at least 500x500
            pixels. This website is a product of Duncan Robertson</p>
        </div>
    </div>

</body>

</html>

</html>

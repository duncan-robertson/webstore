<?php

session_start();
if (isset($_SESSION['time']) && ((time() - $_SESSION['time']) > 15*60)) {
    echo "<script type='text/javascript'>alert('Session Expired. You have been logged out.');</script>";
    session_unset();
    session_destroy();
}
else if (isset($_SESSION['username'])){ //refresh timestamp
    $_SESSION['time'] = time();
}

echo "<html><body>";

if (isset($_SESSION['user']) && $_SESSION['user'] === 'administrator') {
	$conn = new mysqli("localhost", "php", "phppass", "web");
    if ($conn->connect_error) {
        echo '<p>There was an error retreiving your information</p>';
        die ("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM catalog";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
    	echo "<table border='1'> <tr>";
    	echo "<th>Name</th>";
    	echo "<th>Image Path</th>";
    	echo "<th>Description</th>";
    	echo "<th>Price</th>";
    	echo "<th>Quantity</th>";

    	while ($row = $result->fetch_assoc()) {
    		echo "<tr> <td>" . $row['name'] . "</td>";
    		echo "<td>" . $row['image_path'] . "</td>";
    		echo "<td>" . $row['description'] . "</td>";
    		echo "<td>" . $row['price'] . "</td>";
    		echo "<td>" . $row['quantity'] . "</td> </tr>";
    	}
    	echo "</table>";
    }
    else {
    	echo "<p>There are no user accounts</p>";
    }
    $conn->close();
}
else {
	echo "<p>You are not an administrator</p>";
}

echo "</body></html>";

?>
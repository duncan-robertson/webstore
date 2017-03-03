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

    $sql = "SELECT * FROM users";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
    	echo "<table border='1'> <tr>";
    	echo "<th>Username</th>";
    	echo "<th>E-mail</th>";
    	echo "<th>First Name</th>";
    	echo "<th>Last Name</th>";
    	echo "<th>Phone</th>";
    	echo "<th>Address</th>";
    	echo "<th>Address2</th>";
    	echo "<th>City</th>";
    	echo "<th>Province</th>";
    	echo "<th>Postal Code</th>";
    	echo "<th>Locked</th>";
    	echo "<th>Remove</th> </tr>";

    	while ($row = $result->fetch_assoc()) {
    		echo "<tr> <td>" . $row['username'] . "</td>";
    		echo "<td>" . $row['email'] . "</td>";
    		echo "<td>" . $row['first_name'] . "</td>";
    		echo "<td>" . $row['last_name'] . "</td>";
    		echo "<td>" . $row['phone'] . "</td>";
    		echo "<td>" . $row['address'] . "</td>";
    		echo "<td>" . $row['address2'] . "</td>";
    		echo "<td>" . $row['city'] . "</td>";
    		echo "<td>" . $row['province'] . "</td>";
    		echo "<td>" . $row['postal'] . "</td>";
    		echo "<td>" . $row['locked'] . "</td>";
    		echo "<td>" . $row['remove'] . "</td> </tr>";
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
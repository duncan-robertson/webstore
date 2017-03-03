<?php
session_start();

if (isset($_SESSION['username'])){ //refresh timestamp
    $_SESSION['time'] = time();
}

$aResult = array();

if (isset($_POST['type'])) {
	if($_POST['type'] === 'site') {
		if (!isset($_POST['rating'])) {$aResult['error'] = 'Missing argument';}
		if (!isset($_POST['text'])) {$aResult['error'] = 'Missing argument';}
		if (!isset($_SESSION['username'])) {$aResult['error'] = 'Not logged int';}
	
		if (!isset($aResult['error'])) {
			$conn = new mysqli("localhost", "php", "phppass", "web");
			if ($conn->connect_error) {
				$aResult['error'] = 'Error connecting to database';
				die("Error connecting to database: " . $conn->connect_error);
			}
			
			$sql = "INSERT INTO site_reviews (user, rating, review) VALUES ('"
				. $_SESSION['username'] . "',"
				. "'" . $_POST['rating'] . "',"
				. "'" . $_POST['text'] . "')";
	
			if ($conn->query($sql) === TRUE) {
				$aResult['result'] = 'Review successfully added';
			} else {
				$aResult['error'] = 'There was a problem attempting to add your review';
			}
	
			$conn->close();
			echo json_encode($aResult);
			return;
		}else {
			$aResult['error'] = 'There was an error adding your review';
			echo json_encode($aResult);
			return;
		}
	}
	else {
		$aResult['error'] = 'Illegal type specified';
	}
}
else {
	$aResult['error'] = 'Illegal script initialization';
}

?>
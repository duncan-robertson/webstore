<?php
header('Content-Type: application/json');

session_start();
if (isset($_SESSION['time'])){ //refresh timestamp
	$_SESSION['time'] = time();
}

$aResult = array();

if ( !isset($_POST['username'])) {$aResult['error'] = 'Missing arguments';}
if ( !isset($_POST['email'])) {$aResult['error'] = 'Missing arguments';}
if ( !isset($_POST['password'])) {$aResult['error'] = 'Missing arguments';}
if ( !isset($_POST['first_name'])) {$aResult['error'] = 'Missing arguments';}
if ( !isset($_POST['last_name'])) {$aResult['error'] = 'Missing arguments';}
if ( !isset($_POST['address'])) {$aResult['error'] = 'Missing arguments';}
if ( !isset($_POST['city'])) {$aResult['error'] = 'Missing arguments';}
if ( !isset($_POST['province'])) {$aResult['error'] = 'Missing arguments';}
if ( !isset($_POST['postal'])) {$aResult['error'] = 'Missing arguments';}

if ( !isset($aResult['error'])) {
	$address2;
	$phone;

	if ( !isset($_POST['address2'])) {
		$address2 = "";
	}
	else {
		$address2 = $_POST['address2'];
	}

	if ( !isset($_POST['phone'])) {
		$phone = "";
	}
	else {
		$phone = $_POST['phone'];
	}

	$conn = new mysqli("localhost", "php", "phppass", "web");
	if ($conn->connect_error) {
		$aResult['error'] = 'Error connecting to database';
		die("Error connecting to database: " . $conn->connect_error);
	}

	$sql = "SELECT * FROM users WHERE username = '" . $_POST['username'] . "'";
	$result = $conn->query($sql);
	$sql = "SELECT * FROM users WHERE email = '" . $_POST['email'] . "'";
	$result2 = $conn->query($sql);
	$sql = "SELECT * FROM admins WHERE username = '" . $_POST['username'] . "'";
	$result3 = $conn->query($sql);

	if($result->num_rows > 0) {
		$aResult['error'] = 'Username already in use';
	}
	else if ($result2->num_rows > 0) {
		$aResult['error'] = 'E-mail address already in use';
	}
	else if($result3->num_rows > 0) {
		$aResult['error'] = 'Username reserved';
	}
	else {
		$record = "INSERT INTO users VALUES (" 
			. "'" . $_POST['username'] . "',"
			. "'" . $_POST['email'] . "',"
			. "'" . $_POST['password'] . "',"
			. "'" . $_POST['first_name'] . "',"
			. "'" . $_POST['last_name'] . "',"
			. "'" . $phone . "',"
			. "'" . $_POST['address'] . "',"
			. "'" . $address2 . "',"
			. "'" . $_POST['city'] . "',"
			. "'" . $_POST['province'] . "',"
			. "'" . $_POST['postal'] . "',
			0, 0 )";
	
		if ($conn->query($record) === TRUE) {
			$aResult['result'] = 'New account has been created successfully';
		} else {
			$aResult['error'] = 'There was a problem attempting to create your account';
		}

	}

	$conn->close();
}

echo json_encode($aResult);

?>
<?php
header('Content-Type: application/json');

session_start();

$aResult = array();

if ( !isset($_POST['username'])) {$aResult['error'] = 'Invalid Login Credentials';}
if ( !isset($_POST['password'])) {$aResult['error'] = 'Invalid Login Credentials';}

if (isset($_SESSION['login'])) {
	if($_SESSION['login']) {$aResult['error'] = 'You are already logged in please logout first';}

}

if ( !isset($aResult['error'])) {
	$conn = new mysqli("localhost", "php", "phppass", "web");

	if ($conn->connect_error) {
    	echo '<p>There was an error attempting to authenticate your login</p>';
    	die ("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT password FROM admins WHERE username = '" . $_POST['username'] . "'";
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
    	$sql = "SELECT password,locked FROM users WHERE username = '" . $_POST['username'] . "'";
    	$result = $conn->query($sql);
    	
    	if ($result->num_rows == 0) {
    		$aResult['error'] = 'Invalid Login Credentials';
    	}
    	else {
    		$row = $result->fetch_assoc();
    		if ($row['locked'] == 1) {
    			$aResult['error'] = 'Account is locked';
    		}
    		else if ($row['password'] == $_POST['password']) {
    			$_SESSION['login'] = TRUE;
    			$_SESSION['username'] = $_POST['username'];
    			$_SESSION['user'] = 'standard';
    			$_SESSION['time'] = time();
    			$aResult['result'] = 'Successful Login. Welcome ' . $_POST['username'];
    		}
    		else {
    			$aResult['error'] = 'Invalid Login Credentials';
    		}
    	}
    }
    else {
    	$row = $result->fetch_assoc();
    	if ($row['password'] == $_POST['password']) {
    		$_SESSION['login'] = TRUE;
    		$_SESSION['username'] = $_POST['username'];
    		$_SESSION['user'] = 'administrator';
    		$_SESSION['time'] = time();
    		$aResult['result'] = 'Successful Administrator Login';

    	}
    	else {
    		$aResult['error'] = 'Invalid Login Credentials';
    	}

    }

    $conn->close();

}

echo json_encode($aResult);

?>
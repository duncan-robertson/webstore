<?php
header('Content-Type: application/json');

session_start();
if (isset($_SESSION['time'])){ //refresh timestamp
	$_SESSION['time'] = time();
}

$aResult = array();

if (!isset($_POST['type'])) {$aResult['error'] = 'Invalid call';}

if (!isset($aResult['error'])) {
	if ($_POST['type'] === 'update') {
		update();
	}
	else if ($_POST['type'] === 'password') {
		password();
	}
	else if ($_POST['type'] === 'delete') {
		delete();
	}
	else if ($_POST['type'] === 'adminRemove') {
		adminRemove();
	}
	else {
		$aResult['error'] = 'Invlid type given';
		echo json_encode($aResult);
	}
}

function update() {
	if (! isset($_POST['first_name'])) {$aResult['error'] = 'Missing argument';}
	if (! isset($_POST['last_name'])) {$aResult['error'] = 'Missing argument';}
	if (! isset($_POST['email'])) {$aResult['error'] = 'Missing argument';}
	if (! isset($_POST['address'])) {$aResult['error'] = 'Missing argument';}
	if (! isset($_POST['city'])) {$aResult['error'] = 'Missing argument';}
	if (! isset($_POST['province'])) {$aResult['error'] = 'Missing argument';}
	if (! isset($_POST['postal'])) {$aResult['error'] = 'Missing argument';}
	if (! isset($_SESSION['username'])) {$aResult['error'] = 'You are not logged in';}

	
	if (! isset($aResult['error'])) {
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
		
		$sql = "SELECT * FROM users WHERE email = '" . $_POST['email'] . "' 
		        AND username <>'" . $_SESSION['username'] . "'";
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
			$aResult['error'] = 'E-mail address already in use';
		}
		else {
			$sql = "UPDATE users SET email='" . $_POST['email'] . "', 
			   	first_name='" . $_POST['first_name'] . "',
		       	last_name='" . $_POST['last_name'] . "',
		       	address='" . $_POST['address'] . "',
		       	address2='" . $address2 . "',
		       	city='" . $_POST['city'] . "',
		       	province='" . $_POST['province'] . "',
		       	postal='" . $_POST['postal'] . "',
		       	phone='" . $phone . "' 
		       	WHERE username='" . $_SESSION['username'] . "'";

			if ($conn->query($sql) === TRUE) {
				$aResult['result'] = "Information updated successfully";
			} else {
		    	$aResult['error'] = "There was a problem updating your account";
			}
		}

		echo json_encode($aResult);

		$conn->close();
		return;
	}
}

function password() {
	if ( !isset($_POST['password'])) {$aResult['error'] = 'Missing argument';}
	if ( !isset($_SESSION['username'])) {$aResult['error'] = 'You are not logged in';}

	if ( !isset($aResult['error'])) {
		$conn = new mysqli("localhost", "php", "phppass", "web");
		if ($conn->connect_error) {
			$aResult['error'] = 'Error connecting to database';
			die("Error connecting to database: " . $conn->connect_error);
		}
	
		$sql = "UPDATE users SET password='" . $_POST['password'] . "'
		        WHERE username='" . $_SESSION['username'] . "'";
		
		if ($conn->query($sql) === TRUE) {
			$aResult['result'] = "Information updated successfully";
		} else {
			$aResult['error'] = "There was a problem updating your account";
		}

		$conn->close();
	}

	echo json_encode($aResult);
	return;
}

function delete(){
	if (! isset($_SESSION['username'])) {$aResult['error'] = 'You are not logged in';}

	if (! isset($aResult['error'])) {
		$conn = new mysqli("localhost", "php", "phppass", "web");
		if ($conn->connect_error) {
			$aResult['error'] = 'Error connecting to database';
			die("Error connecting to database: " . $conn->connect_error);
		}
	
		$sql = "UPDATE users SET locked='1', remove='1' WHERE username='" . $_SESSION['username'] . "'";
		
		if ($conn->query($sql) === TRUE) {
			$aResult['result'] = "Account marked for deletion, you will be logged out";
		} else {
			$aResult['error'] = "There was a problem updating your account";
		}

		$conn->close();
	}

	session_unset();
	session_destroy();

	echo json_encode($aResult);
	return;
}

function adminRemove() {
	if (!(isset($_SESSION['user']) && $_SESSION['user'] === 'administrator')) {
		$aResult['error'] = 'Insufficient privileges';
	}
	if ( !isset($_POST['targetname'])) {$aResult['error'] = 'No target specified';}

	if ( !isset($aResult['error'])) {
		
		$conn = new mysqli("localhost", "php", "phppass", "web");
		if ($conn->connect_error) {
			$aResult['error'] = 'Error connecting to database';
			die("Error connecting to database: " . $conn->connect_error);
		}
	
		$sql = "DELETE FROM users WHERE username='" . $_POST['targetname'] ."'";

		if ($conn->query($sql) === TRUE) {
			$aResult['result'] = "Account Deleted";
		} else {
			$aResult['error'] = "Encountered an error deleting the account";
		}

		$conn->close();

		echo json_encode($aResult);
		return;
	}
}

?>
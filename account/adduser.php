<?php

function connect_to_server(){
	$mysqli = new mysqli(
		"oniddb.cws.oregonstate.edu",
		"cecilma-db","WWRm6SQCx2MUU1cl",
		"cecilma-db"
	);

	if(!$mysqli || $msqli->connect_errno){
	    send_error($mysqli, $mysqli->connect_errno . ": " . $mysqli->connect_error);
	}

	return $mysqli;
}

function send_error($mysqli, $error){
	$returnval = array(
		"success" => false
	);
	if ($error){
		$returnval["error"] = $error;
	}
	echo json_encode($returnval);
	$mysqli->close();
	exit();
}

function insert_user($mysqli, $user, $pwd, $email){
	$slt = base64_encode(openssl_random_pseudo_bytes(32));
	$hash = base64_encode(hash("sha256", $pwd) + $slt);

	$statement = $mysqli->prepare(
		"INSERT INTO accounts(user, pwd, slt, email) VALUES (?, ?, ?, ?);"
	);

	$statement->bind_param("ssss", $user, $hash, $slt, $email);
	$statement->execute();
	$statement->close();
}

function validate_user($user){
	return 1;
}

function validate_email($email){
	return 1;
}

//main
$mysqli = connect_to_server();
if ($_POST){
	if (!array_key_exists("user", $_POST) || !array_key_exists("pwd", $_POST) || !array_key_exists("email", $_POST)){
		send_error($mysqli, "Missing information.");
	}
	$user = $_POST["user"];
	$pwd = $_POST["pwd"];
	$email = $_POST["email"];

	if (!validate_user($user)){
		send_error($mysqli, "Invalid username: can only contain alphanumeric characters and underscores.");
	}
	if (!validate_email($email)){
		send_error($mysqli, "Invalid email.");
	}

	insert_user($mysqli, $user, $pwd, $email);
	$returnval = array(
		"success" => true
	);
	echo json_encode($returnval);
}

mysqli_close($mysqli);

?>
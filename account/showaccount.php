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
	mysqli_close($mysqli);
	exit();
}

//main
$mysqli = connect_to_server();
session_start();

if(array_key_exists("sid", $_SESSION)){
	$res = $mysqli->query("SELECT(user) FROM sessions WHERE sid = \"" . $_SESSION["sid"] . "\";");
	if ($res && $session = $res->fetch_object()){
		$user = $session->user;
		echo json_encode(array("success" => true, "user" => $user));
	}else{
		echo json_encode(array("success" => false));
	}
}else{
	echo json_encode(array("success" => false));
}

mysqli_close($mysqli);

?>
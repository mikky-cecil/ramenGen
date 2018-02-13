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

$mysqli->query("DELETE FROM sessions WHERE sid = \"" . $_SESSION["sid"] . "\";");

session_unset();
session_destroy();

echo json_encode(array("success" => true));

mysqli_close($mysqli);

?>
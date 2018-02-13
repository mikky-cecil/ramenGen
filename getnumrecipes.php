<?php

function connect_to_server(){

	$mysqli = new mysqli(
		"oniddb.cws.oregonstate.edu",
		"cecilma-db","WWRm6SQCx2MUU1cl",
		"cecilma-db"
	);

	if(!$mysqli || $msqli->connect_errno){
	    echo "Connection error: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
	    $returnval = array(
	    	"success" => false,
	    	"error" => $mysqli->connect_error
	    );
	}else{
		$returnval = array("success" => true);
	}

	return $mysqli;

}

function get_length($mysqli){
	$res = $mysqli->query("SELECT MAX(id) FROM ramen_recipes;");
	if ($res){
		$max = $res->fetch_row();
		return $max;
	}else{
		return -1;
	}
}

//main
$mysqli = connect_to_server();

$length = get_length($mysqli);
if ($length != -1){
	$returnval = array(
		"num_recipes" => $length
	);
}else{
	$returnval["success"] = false;
}

echo json_encode($returnval);

mysqli_close($mysqli);

?>
<?php

function connect_to_server(){
	$mysqli = new mysqli(
		"oniddb.cws.oregonstate.edu",
		"cecilma-db","WWRm6SQCx2MUU1cl",
		"cecilma-db"
	);

	if(!$mysqli || $msqli->connect_errno){
	    send_error($mysqli->connect_errno . ": " . $mysqli->connect_error);
	}

	return $mysqli;
}

function send_error($error){
	$returnval = array(
		"data" => false,
		"success" => false
	);
	if ($error){
		$returnval["error"] = $error;
	}
	echo json_encode($returnval);
	exit();
}

$mysqli = connect_to_server();
	
if ($_GET){
	$returnval = array(
		"success" => true
	);

	if(array_key_exists("id", $_GET)){
		$res = $mysqli->query("
			SELECT flavor, ingredients
			FROM ramen_recipes
			WHERE (id = " . $_GET["id"] . ");
		");

		if($res){
			$recipe = $res->fetch_object();
			if ($recipe->flavor and $recipe->ingredients){
				$returnval["data"] = array(
					"flavor" => $recipe->flavor,
					"ingredients" => $recipe->ingredients
				);
			}else{
				send_error("Recipe not found.");
			}
		}else{
			send_error("Recipe not found.");
		}

		echo json_encode($returnval);
	}
	else{
		send_error("No recipe id given.");
	}
}else{
	send_error("No recipe id given, or id was given through POST.");
}

mysqli_close($mysqli);

?>
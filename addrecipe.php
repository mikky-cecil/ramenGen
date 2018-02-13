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

function get_ingredients(){
	$iarray = $_POST["ingredients"];
	
	//combine ingredients into string
	if (count($iarray) == 1){
		$istring = $iarray[0];
	}else if(count($iarray) == 2){
		$istring = $iarray[0] . " and " . $iarray[1];
	}else{
		$istring = $iarray[0];
		for ($i = 1; $i < count($iarray); $i++){
			if ($i == count($iarray) - 1){
				$istring = $istring . ", and " . $iarray[$i];
			}else{
				$istring = $istring . ", " . $iarray[$i];
			}
		}
	}

	return $istring;
}

function get_recipe_items(){
	//flavor
	if (array_key_exists("flavor", $_POST)){
		if ($_POST["flavor"] == "other"){
			if (array_key_exists("otherflavor", $_POST) && $_POST["otherflavor"] != ""){
				$flavor = $_POST["otherflavor"];
			}else{
				send_error("wrong.");
			}
		}else{
			$flavor = $_POST["flavor"];
		}
	}
	//nutrition
	if (array_key_exists("nut", $_POST)){
		if ($_POST["nut"] == "true"){
			$nut = 1;
		}else{
			$nut = 0;
		}
	}
	//price
	if (array_key_exists("cheap", $_POST)){
		if ($_POST["cheap"] == "true"){
			$cheap = 1;
		}else{
			$cheap = 0;
		}
	}
	//ingredients
	if (array_key_exists("ingredients", $_POST)){
		$istring = get_ingredients();
	}

	$rec_items = array(
		"flavor" => $flavor,
		"nut" => $nut,
		"cheap" => $cheap,
		"ingredients" => $istring
	);

	return $rec_items;
}

function check_values($x){
	if (!array_key_exists("flavor", $x) || !$x["flavor"]){
		return 0;
	}else if ($x["flavor"] == "other" && !$x["otherflavor"]){
		//they said "other" but did not specify
		return 0;
	}
	if (!array_key_exists("nut", $x) || !$x["nut"]){
		return 0;
	}
	if (!array_key_exists("cheap", $x) || !$x["flavor"]){
		return 0;
	}
	if (!array_key_exists("ingredients", $x) || !$x["ingredients"]){
		return 0;
	}
	return 1;
}

function enter_recipe($mysqli, $x){
	$statement = $mysqli->prepare(
		"INSERT INTO ramen_recipes(flavor, ingredients, nut, cheap)VALUES (?, ?, ?, ?)"
	);

	$statement->bind_param("ssss", $x["flavor"], $x["ingredients"], $x["nut"], $x["cheap"]);
	$statement->execute();
	$statement->close();
}

//main
$mysqli = connect_to_server();
if ($_POST){
	$rec_items = get_recipe_items();
	
	//check to make sure everything is there
	if (check_values($rec_items)){
		enter_recipe($mysqli, $rec_items);
		$returnval = array(
			"success" => true
		);
		echo json_encode($returnval);
	}else{
		send_error("Invalid values. Please fill in the form correctly.");
	}

	// foreach ($rec_items as $key => $value) {
	// 	echo $key . ": " . $value . "<br>";
	// }
}

mysqli_close($mysqli);

?>
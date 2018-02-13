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

function validate_user($mysqli, $user, $pwd){
	$res = $mysqli->query(
		"SELECT user, pwd FROM accounts WHERE (user = \"" . $user . "\");"
	);

	if(!$res){
		send_error($mysqli, "Query failed.");
	}
	$account = $res->fetch_object();
	if (!$account){
		send_error($mysqli, "Account not found.");
	}

	$slt = $account->slt;
	$hash = base64_encode(hash("sha256", $pwd) + $slt);
	
	if($account->pwd == $hash){
		return 1;
	}else{
		return 0;
	}
}

function start_a_session($mysqli, $user){
	$sid = base64_encode(openssl_random_pseudo_bytes(16));

	$res = $mysqli->query(
		"INSERT INTO sessions(user, sid) VALUES(\"" . $user ."\", \"" . $sid . "\")"
	);

	session_start();
	$_SESSION["sid"] = $sid;
}

//main
$mysqli = connect_to_server();
if ($_POST){
	if (array_key_exists("user", $_POST) && array_key_exists("pwd", $_POST)){
		$user = $_POST["user"];
		$pwd = $_POST["pwd"];

		if (validate_user($mysqli, $user, $pwd)){
			start_a_session($mysqli, $user);

			$returnval = array(
				"success" => true
			);
			echo json_encode($returnval);
		}else{
			send_error($mysqli, "Incorrect password.");
		}
	}else{
		send_error($mysqli, "Missing login info.");
	}
}

mysqli_close($mysqli);

?>
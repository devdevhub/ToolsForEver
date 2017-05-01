<?php

	spl_autoload_register(function ($class) {
		include "mvc\\model\\".$class.".php";
	});

	if ($_SERVER["SERVER_NAME"] == "localhost") {
		$host = "localhost";
		$port = 3306;
		$database = "ToolsForEver";
		$username = "root";
		$password = "root";
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
	}
	else {
		$host = "localhost";
		$port = 3306;
		$database = "deb7255_daniel";
		$username = "deb7255_daniel";
		$password = "fheds";
	}

	$GLOBALS["db"] = new PDO(
		"mysql:host=".$host.";port=".$port.";dbname=".$database,
		$username,
		$password
	);

	define("NOT_SET", -1);
	
?>
<?php
	$host = "localhost";
	$dbname ="eshop";
	$dbuser ="root";
	$dbpass ="";
	$location = "index.php";
	//csatlakozás

	$mysqli= mysqli_connect ($host,$dbuser,$dbpass);
	mysqli_select_db($mysqli, $dbname);
	?>
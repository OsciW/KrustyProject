<?php

	session_start();

	$userId = $_SESSION['userId'];
	echo 'welcome: '.$userId."</br>";
	

	$date=$_REQUEST['deliveryDate'];
	$time=$_REQUEST['deliveryTime'];


	echo 'Delivery date: ';
	echo $date."</br>";

	echo 'Delivery time: ';
	echo $time."</br></br>Cookies ordered: </br>";

	$recipes = $_SESSION['allRecipes'];

	unset($_POST['deliveryDate']);
	unset($_POST['deliveryTime']);


	$specs=array();
	$i=0;
	foreach ($_POST as $key => $value) {
		echo $recipes[$i].": {$value}<br />";
		$specs[$i]=array($recipes[$i], $value);
  		#print "{$key}: {$value}<br />";
  		$i++;
	}



	require_once('database.inc.php');
	require_once("mysql_connect_data.inc.php");

	$db = new Database($host, $userName, $password, $database);
	$db->openConnection();
	if (!$db->isConnected()) {
		header("Location: cannotConnect.html");
		exit();
	}
	$db->placeOrder($userId, $time, $date, $specs);


?>
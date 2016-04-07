<?php
	require_once('database.inc.php');
	require_once("mysql_connect_data.inc.php");
	
	$db = new Database($host, $userName, $password, $database);
	$db->openConnection();
	if (!$db->isConnected()) {
		header("Location: cannotConnect.html");
		exit();
	}

	
	$userId = $_REQUEST['userId'];
	if (!$db->userExists($userId)) {
		$db->closeConnection();
		header("Location: noSuchUser.html");
		exit();
	}

	$type = $db->getUserType($userId);
	$userType = $type[0];

	$db->closeConnection();

	

	session_start();
	$_SESSION['db'] = $db;
	$_SESSION['userId'] = $userId;
	$_SESSION['userType'] = $userType;

	if ($userType == "superUser") {
		header("Location: menu.php");
	}

	else if ($userType == "Customer"){
		header("Location: Customer.php");
	}
	else if ($userType == "Production" ){
		header("Location: production.php");
	} 
	else if ($userType == "OrdersDelivers" ){
		header("Location: ordersDeliver.php");
	} 
	else if ($userType == "StockManager" ){
		header("Location: stocks.php");
	}
?>

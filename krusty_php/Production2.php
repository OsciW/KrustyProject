<?php
	require_once('database.inc.php');
	
	session_start();
	$db = $_SESSION['db'];
	$userId = $_SESSION['userId'];
	$userType = $_SESSION['userType'];
	$recipe = $_REQUEST['recipe'];
	$date = $_REQUEST['date'];
	$quantity = $_REQUEST['quantity'];
	$barcodeId = $_REQUEST['barcodeId'];
	$status = $_REQUEST['status'];



	$db->openConnection();
	$Recipies = $db->getRecipe();
	$db->closeConnection();

?>


<html>
<head>
<title>Krusty Kookies <?php print $userType?> </title>
</head>
<body>

<h1 align="center">Production Testing page 2 </h1>
<p> 
Current user: <?php print $userId ?>
	<p>
	Barcode Id: <?php print $barcodeId ?>
	<p>
	Time Created: <?php print $time ?>
	<p>
	Date Created: <?php print $date ?>
	<p>
	Free Seats: <?php print $date ?>
	<p>



</body>
</html>
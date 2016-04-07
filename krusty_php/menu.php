<?php
	require_once('database.inc.php');
	
	session_start();
	$db = $_SESSION['db'];
	$userId = $_SESSION['userId'];
	$userType = $_SESSION['userType'];

	$db->openConnection();
  	$db->closeConnection();
?>


<html>
	<head>
		<title>Krusty Kookies - <?php print $userType ?> </title>
	</head>
	<body>

		<h1 align="center"> SuperMenu</h1>

    <p class="text">
        <a href="newUser.php">Create new Account</a>
    </p>
    <p class="text">
        <a href="production.php">Production site</a>
    </p>
    <p class="text">
        <a href="ordersDeliver.php">Orders and deliveries</a>
    </p>
    <p class="text">
        <a href="stocks.php">Warehouse stocks</a>
    </p>
    <p class="text">
        <a href="Customer.php">Customer site</a>
    </p>




		<p>

		<form method=get action="index.html">
    		<input type=submit value="log out" >
  		</form>


	</body>
</html>
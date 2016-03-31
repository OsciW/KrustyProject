<?php
	require_once('database.inc.php');
	
	session_start();
	$db = $_SESSION['db'];
	$userId = $_SESSION['userId'];
	$userType = $_SESSION['userType'];

	$db->openConnection();
  	$Recipies = $db->getRecipe();
  	$Ingredients = $db->getRawmaterials();
  	$db->closeConnection();
?>


<html>
	<head>
		<title>Krusty Kookies - <?php print $userType ?> </title>
	</head>
	<body>

		<h1 align="center"> Production</h1>

		<h3>Actions</h3>
		<p class="text">
  			<a href="production1.php">Create Pallet</a>
		</p>

		<p class="text">
  			<a href="searchPallet.php">Search Pallet</a>
		</p>


		<form method=get action="index.html">
    		<input type=submit value="log out" >
  		</form>


	</body>
</html>
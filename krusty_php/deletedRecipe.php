<?php
	require_once('database.inc.php');
	
	session_start();
	$db = $_SESSION['db'];
	$userId = $_SESSION['userId'];
	$userType = $_SESSION['userType'];
  $recipe = $_SESSION['recipe'];

	  $db->openConnection();
  $db->deleteRecipe($recipe);
  $db->closeConnection();
?>


<html>
<head>
<title>Krusty Kookies - <?php print $userType ?> </title>
</head>
<body>

<h1 align="center"> Recipe - <?php print $recipe ?> </h1>

Recipe "<?php print $recipe?>" deleted. 
<p>


<form method=get action="stocks.php">
    <input type=submit value="back" >
  </form>

<form method=get action="index.html">
    <input type=submit value="Logout" >
  </form>

<p>


</body>
</html>
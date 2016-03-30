<?php
	require_once('database.inc.php');
	
	session_start();
	$db = $_SESSION['db'];
	$userId = $_SESSION['userId'];
	$userType = $_SESSION['userType'];
?>


<html>
<head>
<title>Krusty Kookies - <?php print $userType ?> </title>
</head>
<body>

<h1 align="center"><?php print $userId ?>  <?php print $userType ?></h1>


<p>
<form method=get action="createRecipe.php">
    <input type=submit value="Manage recipies" >
  </form>

<p>

<form method=get action="index.html">
    <input type=submit value="log out" >
  </form>

</body>
</html>
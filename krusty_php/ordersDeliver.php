<?php
	require_once('database.inc.php');
	
	session_start();
	$db = $_SESSION['db'];
	$userId = $_SESSION['userId'];
	$userType = $_SESSION['userType'];
?>


<html>
<head>
<title>Krusty Kookies</title>
</head>
<body>

<h1 align="center"> Welcome <?php print $userId ?>  </h1>

</body>
</html>
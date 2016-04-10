<?php
	require_once('database.inc.php');
	
	session_start();
	$db = $_SESSION['db'];
	$userId = $_REQUEST['userId'];
	$userType = $_REQUEST['userType'];
	$userName = $_REQUEST['username'];
	$customer = $_REQUEST['customer'];

	$db->openConnection();
	$created = $db->createUser($userName, $userType, $userId);
	if($userType == 'Customer') {
		$db->createCustUse($userId,$customer);
	} 
	
	$db->closeConnection();

?>


<html>
<head>
<title>Krusty Kookies <?php print $userType?> </title>
</head>
<body>

<h1 align="center">Created user </h1>
<p> 

<?php if ($created) {

		print "SSN: $userId <br/>\n";
		print "Name: $userName <br/>\n";
		print "type: $userType <br/>\n";
		if($userType == 'Customer') {
			print "customer: $customer<br/>\n";
		}
} else {

	print "Sorry, wrongly filled information, or user already exists";
}
 ?>
 <form method=get action="newUser.php">
    <input type=submit value="back" >
  </form>
 
<form method=get action="index.html">
    <input type=submit value="log out" >
  </form>



</body>
</html>
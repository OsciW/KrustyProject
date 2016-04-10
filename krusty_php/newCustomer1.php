<?php
	require_once('database.inc.php');
	
	session_start();
	$db = $_SESSION['db'];
	$customerName = $_REQUEST['customerName'];
	$address = $_REQUEST['address'];
	$tele = $_REQUEST['tele'];

	$db->openConnection();
	$created = $db->createCustomer($customerName, $address, $tele);
	$db->closeConnection();

?>


<html>
<head>
<title>Krusty Kookies <?php print $userType?> </title>
</head>
<body>

<h1 align="center">Created Customer </h1>
<p> 

<?php if ($created) {

		print "Customer: $customerName <br/>\n";
		print "Address: $address <br/>\n";
		print "Phone nbr: $tele <br/>\n";
		
} else {

	print "Sorry, wrongly filled information, or customer already exists";
}
 ?>
<form method=get action="index.html">
    <input type=submit value="back" >
  </form>



</body>
</html>
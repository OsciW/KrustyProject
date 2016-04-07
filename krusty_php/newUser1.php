<?php
	require_once('database.inc.php');
	
	session_start();
	$db = $_SESSION['db'];
	$userId = $_REQUEST['userId'];
	$userType = $_REQUEST['userType'];
	$userName = $_REQUEST['username'];
	$customer = $_REQUEST['customer'];

	$db->openConnection();
	if($customer != null) {
		$db->createCustUse($userId,$customer);
	} 
	$db->createUser($userName, $userType, $userId);
	$db->closeConnection();

?>


<html>
<head>
<title>Krusty Kookies <?php print $userType?> </title>
</head>
<body>

<h1 align="center">Production Testing page 2 </h1>
<p> 

<?php if ($resNbr != 0) {


	print "Current user:   $userId <br/>\n";
	print "Barcode Id: $barcodeId <br/>\n";
	print "Time Created:  $time <br/>\n";
	
	print "Date Created: $date <br/>\n";
	
	print "Blocked:  $status <br/>\n";
	
	print "Recipe:   $recipe <br/>\n";
	
	print "Pallet Id:   $resNbr ";
} else {

	print "Not enough rawmaterials, fill stocks first";
}
 ?>
 <form method=get action="production1.php">
    <input type=submit value="back" >
  </form>
 
<form method=get action="index.html">
    <input type=submit value="log out" >
  </form>



</body>
</html>
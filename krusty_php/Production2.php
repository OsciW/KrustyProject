<?php
	require_once('database.inc.php');
	
	session_start();
	$db = $_SESSION['db'];
	$userId = $_SESSION['userId'];
	$userType = $_SESSION['userType'];
	$recipe = $_REQUEST['recipe'];
	$date = $_REQUEST['date'];
	$time = $_REQUEST['time'];
	$barcodeId = $_REQUEST['barcodeId'];
	$status = $_REQUEST['status'];



	$db->openConnection();
	$resNbr = $db->createPallet($barcodeId, $time, $date, $status, $recipe);
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
	
	print "Status:  $status <br/>\n";
	
	print "Recipe:   $recipe <br/>\n";
	
	print "Pallet Id:   $resNbr ";
} else {

	print "Not enough rawmaterials, fill stocks first";
}
 ?>
 
<form method=get action="index.html">
    <input type=submit value="log out" >
  </form>



</body>
</html>
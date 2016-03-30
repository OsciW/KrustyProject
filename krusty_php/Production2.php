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


print "Current user:   $userId";
	print "Barcode Id: $barcodeId \r\n";
	print "Time Created:  $time \r\n";
	
	print "Date Created: $date \r\n";
	
	print "Status:  $status\r\n";
	
	print "Recipe:   $recipe\r\n";
	
	print "Pallet Id:   $resNbr \r\n";
} else {

	print "Not enough rawmaterials, fill stocks first";
}
 ?>
 
<form method=get action="index.html">
    <input type=submit value="log out" >
  </form>



</body>
</html>
<?php
	require_once('database.inc.php');
	
	session_start();
	$db = $_SESSION['db'];
	$userId = $_SESSION['userId'];
	$userType = $_SESSION['userType'];
	$recipe = $_REQUEST['recipeName'];

	$db->openConnection();
	$rawMaterials = $db->getRawName();
	$db->closeConnection();

?>



<html>
<head>
<title>Krusty Kookies <?php print $userType?> </title>
</head>
<body>


<h1 align="center">Production Testing page 2 </h1>
<p> 
Current user: <?php print $userId ?>
<p>

<table id="rawMaterialTable">  
    <tr>
      <b>Raw Materials</b>
      <p>


<table id="Raw Material Table">
  <tr>
    <td style="background-color: #FFF"><b>Raw Material</b></td>
    <td style="background-color: #FFF"><b>Quantity</b></td>

  </tr>

<?php

  foreach($rawMaterials as $rawMaterial){
	 $checkWord = str_replace(" ", "","$rawMaterial");
  	if(isset($_GET[$checkWord])) { 
  		$quantity = $_GET[$checkWord]; 
			if($quantity > 0) {
				$db->openConnection();
				$nbr = $db->insertRawMaterial($rawMaterial, $quantity);
				$db->closeConnection();
				
  	?>
  <tr> 
    <td><?php echo $rawMaterial; ?></td>  
    <td><?php echo $quantity;?></td>
  </tr>
 <?php }
}

		}
	?>
</table>


<form method=get action="orderMoreRaw.php">
    <input type=submit value="back" >
  </form>


<form method=get action="index.html">
    <input type=submit value="log out" >
  </form>



</body>
</html>
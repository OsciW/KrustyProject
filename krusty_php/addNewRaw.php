<?php
	require_once('database.inc.php');
	
	session_start();
	$db = $_SESSION['db'];
	$userId = $_SESSION['userId'];
	$userType = $_SESSION['userType'];

	$db->openConnection();
  $Ingredients = $db->getAllIngredients();
  $db->closeConnection();
?>
<html>
<head>
<title>Krusty Kookies - <?php print $userType ?> </title>
</head>
<body>

<h1 align="center">Add New Raw Materials</h1>

<form method=get action="addedNewRaw.php">
  <p>
  Name: <input type="text" name="name" placeholder="Flour" value="<?php echo $name;?>">
  <p>
  Order Quantity: <input type="text" name="quantity" placeholder="100000 " value="<?php echo $quantity;?>">

  <p>
  Unit of measure : <input type="text" name="unit" placeholder="g/dl" value="<?php echo $unit;?>">
  <p>


    <input type=submit value="Order" >
   </form>



<form method=get action="stockReview.php">
    <input type=submit value="back" >
  </form>


<p>
<form method=get action="index.html">
    <input type=submit value="logout" >
  </form>

<p>


</body>
</html>
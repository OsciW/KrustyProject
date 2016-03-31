<?php
	session_start();


	$db = $_SESSION['db'];
	$userId = $_SESSION['userId'];
	$userType = $_SESSION['userType'];


	require_once('database.inc.php');
	require_once("mysql_connect_data.inc.php");

	$db = new Database($host, $userName, $password, $database);
	$db->openConnection();
	if (!$db->isConnected()) {
		header("Location: cannotConnect.html");
		exit();
	}

	$recipe = $db->getRecipe();



	$_SESSION['allRecipes']=$recipe;
	$_SESSION['userId']=$userId;
	$_SESSION['db']=$db;

?>


<html>
<head>
<title>Krusty Kookies - <?php print $userType ?> </title>
</head>
<body>


<h1 align="center"> Welcome <?php print $userId ?></h1>

<p class="breadtext">
		Please place an order:
</p>


<form action="orderprocessing.php" method="post">

<table border="0">

<tr>
	<td>Delivery date</td>
	<td align="center"><input type="date" name="deliveryDate" size="30" placeholder="YYYY-MM-DD"/></td>
</tr>
	
<tr>
	<td>Delivery time</td>
	<td align="center"><input type="date" name="deliveryTime" size="30" placeholder="HH:MM:SS"/></td>
</tr>

<tr>
	<td>Cookie type</td>
	<td align="center">Quantity</td>
</tr>


<?php
	foreach ($recipe as $row ) {
?>
	<tr>
		<td> <?php echo $row ?> </td>
		<td align="center"><select name="<?php echo $row; ?>">
    	<option value=0>0</option>
    	<option value=1>1</option>
    	<option value="2">2</option>
    	<option value="3">3</option>
    	<option value="4">4</option>
    	<option value="5">5</option>
    	<option value="6">6</option>
    	<option value="7">7</option>
    	<option value="8">8</option>
    	<option value="9">9</option>
 		 </select></td>
	</tr>
<?php
	}
?>


<tr>
<td colspan="2" align="right"><input type="submit" value="Submit Order"/></td>
</tr>

</table>
</form>

</body>
</html>
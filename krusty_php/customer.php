<?php
	require_once('database.inc.php');
	session_start();
	$db = $_SESSION['db'];
	$userId = $_SESSION['userId'];
	$userType = $_SESSION['userType'];

	$db->openConnection();
  	$recipe = $db->getRecipe();
  	$customer = $db->getCompanies();
  	if($userType == 'Customer') {
  		$customerName = $db->getUserCustomer($userId);
  		$customerName = $customerName[0][0];
  		$_SESSION['customerName'] = $customerName;
  	}
  	$db->closeConnection();

  	$_SESSION['recipes'] = $recipe;
  	
?>


<html>
<head>
<title>Krusty Kookies - <?php echo $userType ?> </title>
</head>
<body>


<h1 align="center"> Welcome <?php print $userId ?></h1>

<p class="breadtext">
		Please place an order:
</p>


<form action="orderprocessing.php" method="post">


<?php if($userType != 'Customer') { ?>
<select name="customerName" size=10>
    <?php
      $first = true;
      foreach ($customer as $name) {
        if ($first) {
          print "<option selected>";
          $first = false;
        } else {
          print "<option>";
        }
        print $name;
      }
      $_SESSION['customerName'] = $customerName;
    ?>

    </select>
 <?php } else { ?>

 Customer name : <?php print $customerName; ?> 



 <?php } ?> 


<table border="0">

<tr>
	<td>Delivery date</td>
	<td align="center"><input type="date" name="deliveryDate" size="30" placeholder="YYYY-MM-DD"/></td>
</tr>
	
<tr>
	<td>Delivery time</td>
	<td align="center"><input type="text" name="deliveryTime" size="30" placeholder="HH:MM:SS"/></td>
</tr>

<tr>
	<td>Cookie type</td>
	<td align="center">Quantity</td>
</tr>


<?php
	foreach ($recipe as $name ) {
?>
	<tr>
		<td> <?php echo $name ?> </td>
		<td align="center"><select name="<?php echo $name; ?>">
    	<option value=0>0</option>
    	<option value=1>1</option>
    	<option value=2>2</option>
    	<option value=3>3</option>
    	<option value=4>4</option>
    	<option value=5>5</option>
    	<option value=6>6</option>
    	<option value=7>7</option>
    	<option value=8>8</option>
    	<option value=9>9</option>
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
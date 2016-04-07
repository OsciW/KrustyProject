<?php
	require_once('database.inc.php');
	
	session_start();
	$db = $_SESSION['db'];
	$userId = $_SESSION['userId'];
	$userType = $_SESSION['userType'];

	$db->openConnection();
  	$Recipies = $db->getRecipe();
  	$Ingredients = $db->getRawmaterials();
  	$db->closeConnection();
?>


<html>
	<head>
		<title>Krusty Kookies - <?php print $userType ?> </title>
	</head>
	<body>

		<h1 align="center"> Stocks</h1>

		<h3>Recipes</h3>

		<table id="recipetable">  
  		<?php foreach($Recipies as $recipe){ ?>
  			<tr> 
 
    		<td> <a href="checkRecipe.php?recipe=<?php print $recipe?>">  <?php print $recipe?> </a></td>

 			</tr>
  		<?php } ?>

		</table>


		<h3>Actions</h3>
		<p class="text">
  			<a href="createRecipe.php">Create Recipes</a>
		</p>


		 <p class="text">
        <a href="addNewRaw.php">Add new raw material</a>
    </p>

    <p class="text">
        <a href="orderMoreRaw.php">Order more raw materials</a>
    </p>

    <p class="text">
        <a href="stockHistory.php">Check Stock History</a>
    </p>

    <table id="rawMaterialTable">
      <tr>
        <td style="background-color: #FFF"><b>Raw material</b></td>
        <td style="background-color: #FFF"><b>Amount</b></td>
        <td style="background-color: #FFF"><b>unit</b></td>


		<h3>In Stock</h3>
      </tr>
      <?php foreach($Ingredients as $ingredient){ ?>
        <tr> 
          <td><?php echo $ingredient['name']; ?></td>  
          <td><?php echo $ingredient['quantityStock']; ?></td>
          <td><?php echo $ingredient['unit']; ?> </td>
        </tr>
      <?php } ?>
    </table>

		<p>

		<form method=get action="index.html">
    		<input type=submit value="log out" >
  		</form>


	</body>
</html>
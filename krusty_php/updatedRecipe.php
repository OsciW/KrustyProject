<?php
	require_once('database.inc.php');
	
	session_start();
	$db = $_SESSION['db'];
	$userId = $_SESSION['userId'];
	$userType = $_SESSION['userType'];
	$recipe = $_SESSION['recipe'];

	$db->openConnection();
  $deleted = $db->deleteOldIngredients($recipe);
	$ingredients = $db->getAllIngredients();
	$db->closeConnection();

?>



<html>
<head>
<title>Krusty Kookies <?php print $userType?> </title>
</head>
<body>


  <?php

    if ($deleted) {
 ?>
  	<h1 align="center">Updated Recipe </h1>
<p> 
Current user: <?php print $userId ?>
<p>

Recipe: <?php print $recipe ?>
<p>

<table id="ingredientTable">  
    <tr>
      <b>Ingredients</b>
      <p>


<table id="ingredientTable">
  <tr>
    <td style="background-color: #FFF"><b>Ingredient</b></td>
    <td style="background-color: #FFF"><b>Quantity</b></td>

  </tr>

<?php

  foreach($ingredients as $ingredient){
	 $checkWord = str_replace(" ", "","$ingredient");
  	if(isset($_GET[$checkWord])) { 
  		$quantity = $_GET[$checkWord]; 
			if($quantity > 0) {
				$db->openConnection();
				$nbr = $db->insertRecipeIngredient($ingredient, $quantity, $recipe);
				$db->closeConnection();
				
  	?>
  <tr> 
    <td><?php echo $ingredient; ?></td>  
    <td><?php echo $quantity;?></td>
  </tr>
 <?php }
}

		}
  
	} else { ?>

Don't forget to write recipe name and an ingredient
<?php

}

	

 ?>
</table>


<form method=get action="stocks.php">
    <input type=submit value="back" >
  </form>


<form method=get action="index.html">
    <input type=submit value="log out" >
  </form>



</body>
</html>
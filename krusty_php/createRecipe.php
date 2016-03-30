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

<h1 align="center">Create Recipe</h1>

Current user: <?php print $userId ?>
<p>

<form name="createRecipe "id="createRecipe" action="createdRecipe.php">
  <input type="text" name="recipeName" placeholder="Recipe Name"/><br /><br />
  <table id="ingredientTable">  
    <tr>
      <b>Ingredients</b>
    </tr>
    <?php
    foreach($Ingredients as $ingredient){ ?>
      <tr> 
        <td><?php print $ingredient; ?></td>  
        <td><input type="text" name="<?php print str_replace(' ', '', $ingredient); ?>" placeholder="quantity"/></td>
      </tr>
    <?php } ?>


  </table>
<p>  
<input type=submit value="Create recipe" >

</form>



<form method=get action="stocks.php">
    <input type=submit value="back" >
  </form>


<p>
<form method=get action="index.html">
    <input type=submit value="logout" >
  </form>

<p>


</body>
</html>
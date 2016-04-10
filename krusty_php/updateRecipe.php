<?php
	require_once('database.inc.php');
	
	session_start();
	$db = $_SESSION['db'];
	$userId = $_SESSION['userId'];
	$userType = $_SESSION['userType'];
  $recipe = $_SESSION['recipe'];
  $oldIngredients = $_SESSION['Ingredients'];

	$db->openConnection();
  $Ingredients = $db->getAllIngredients();
  $db->closeConnection();
?>
<html>
<head>
<title>Krusty Kookies - <?php print $userType ?> </title>
</head>
<body>

<h1 align="center">Recipe update - <?php echo $recipe ?></h1>

Current user: <?php print $userId ?>
<p>

<form name="updateRecipe "id="updateRecipe" action="updatedRecipe.php">
 
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
<input type=submit value="Update recipe" >

</form>

<h3>Old recipe</h3>
 <table id="ingredientTable">
      <tr>
        <td style="background-color: #FFF"><b>Ingredient</b></td>
        <td style="background-color: #FFF"><b>Amount</b></td>
        <td style="background-color: #FFF"><b>unit</b></td>

      </tr>
      <?php foreach($oldIngredients as $ingredient){ ?>
        <tr> 
          <td><?php echo $ingredient['rawMaterialName']; ?></td>  
          <td><?php echo $ingredient['quantity']; ?></td>
          <td><?php echo $ingredient['unit']; ?> </td>
        </tr>
      <?php } ?>
    </table>

    <p>



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
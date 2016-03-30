<?php
	require_once('database.inc.php');
	
	session_start();
	$db = $_SESSION['db'];
	$userId = $_SESSION['userId'];
	$userType = $_SESSION['userType'];
  $recipe = $_GET['recipe'];
  $_SESSION['recipe'] = $recipe;

	$db->openConnection();
  $Ingredients = $db->getIngredients($recipe);
  $db->closeConnection();
?>


<html>
  <head>
    <title>Krusty Kookies - <?php print $userType ?> </title>
  </head>
  <body>

    <h1 align="center"> Recipe - <?php print $recipe ?> </h1>

    Current user: <?php print $userId?>
    <p>

    <table id="materialtable">
      <tr>
        <td style="background-color: #FFF"><b>Ingredient</b></td>
        <td style="background-color: #FFF"><b>Amount</b></td>
        <td style="background-color: #FFF"><b>unit</b></td>

      </tr>
      <?php foreach($Ingredients as $ingredient){ ?>
        <tr> 
          <td><?php echo $ingredient['rawMaterialName']; ?></td>  
          <td><?php echo $ingredient['quantity']; ?></td>
          <td><?php echo $ingredient['unit']; ?> </td>
        </tr>
      <?php } ?>
    </table>

    <p>
    <p>

    <form method=get action="deletedRecipe.php">
      <input type=submit value="Delete" >
    </form>
    <form method=get action="stocks.php">
      <input type=submit value="back" >
    </form>
    <p>
    <form method=get action="index.html">
      <input type=submit value="Logout" >
    </form>

    <p>
  </body>
</html>
<?php
	require_once('database.inc.php');
	
	session_start();
	$db = $_SESSION['db'];
	$userId = $_SESSION['userId'];
	$userType = $_SESSION['userType'];

	  $db->openConnection();
  $Recipies = $db->getRecipe();
  $db->closeConnection();
?>


<html>
	<head>
		<title>Krusty Kookies - <?php print $userType ?> </title>
	</head>
	<body>

		<h1 align="center"> Stocks</h1>

		<h1>Recipes</h1>


		<table id="materialtable">  
  		<?php foreach($Recipies as $recipe){ ?>
  			<tr> 
 
    		<td> <a href="checkRecipe.php?recipe=<?php print $recipe?>">  <?php print $recipe?> </a></td>

 			</tr>
  		<?php } ?>

		</table>

		<p class="text">
  			<a href="createRecipe.php">Create Recipes</a>
		</p>


		<p>

		<form method=get action="index.html">
    		<input type=submit value="log out" >
  		</form>
	</body>
</html>
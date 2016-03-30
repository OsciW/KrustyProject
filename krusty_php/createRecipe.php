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

<h1 align="center"><?php print $userId ?>  <?php print $userType ?></h1>
<form method=get action="stocks.php">
    <input type=submit value="back" >
  </form>
<form method=get action="production2.php">
 <select name="recipe" size=10>
    <?php
      $first = true;
      foreach ($Recipies as $name) {
        if ($first) {
          print "<option selected>";
          $first = false;
        } else {
          print "<option>";
        }
        print $name;
      }
      ?>

</select>
</form>

<h1 align= "center">
herrow 
</h1>


<p>
<form method=get action="index.html">
    <input type=submit value="Create recipe" >
  </form>

<p>


</body>
</html>
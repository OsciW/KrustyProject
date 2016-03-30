<?php
	require_once('database.inc.php');
	
	session_start();
	$db = $_SESSION['db'];
	$userId = $_SESSION['userId'];
	$userType = $_SESSION['userType'];

	$db->openConnection();
  $Ingredients = $db->getRawmaterials();
  $db->closeConnection();
?>


<html>
  <head>
    <title>Krusty Kookies - <?php print $userType ?> </title>
  </head>
  <body>

    <h1 align="center"> Rawmaterials </h1>

    Current user: <?php print $userId?>
    <p>

    <p class="text">
        <a href="addNewRaw.php">Add new raw material</a>
    </p>

    <p class="text">
        <a href="orderMoreRaw.php">Order more raw materials</a>
    </p>

    <table id="rawMaterialTable">
      <tr>
        <td style="background-color: #FFF"><b>Raw material</b></td>
        <td style="background-color: #FFF"><b>Amount</b></td>
        <td style="background-color: #FFF"><b>unit</b></td>

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
    <p>

  
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
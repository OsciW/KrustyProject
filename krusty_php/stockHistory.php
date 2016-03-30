<?php
	require_once('database.inc.php');
	
	session_start();
	$db = $_SESSION['db'];
	$userId = $_SESSION['userId'];
	$userType = $_SESSION['userType'];

	$db->openConnection();
  $Ingredients = $db->getAllStockEvents();
  $db->closeConnection();
?>


<html>
  <head>
    <title>Krusty Kookies - <?php print $userType ?> </title>
  </head>
  <body>

    <h1 align="center"> Stock history </h1>

    Current user: <?php print $userId?>
    <p>


    <table id="rawMaterialTable" style="border-spacing: 20px">
      <tr>
        <td style="background-color: #FFF"><b>Raw material</b></td>
        <td style="background-color: #FFF"><b>Amount</b></td>
        <td style="background-color: #FFF"><b>Time</b></td>
        <td style="background-color: #FFF"><b>Date</b></td>

      </tr>
      <?php foreach($Ingredients as $ingredient){ ?>
        <tr> 
          <td><?php echo $ingredient['rawMaterialName']; ?></td>  
          <td><?php echo $ingredient['quantity']; ?></td>
          <td><?php echo $ingredient['createdTime']; ?> </td>
          <td><?php echo $ingredient['createdDate']; ?> </td>
        </tr>
      <?php } ?>
    </table>

    <p>
    <p>

  
    <form method=get action="stockReview.php">
      <input type=submit value="back" >
    </form>
    <p>
    <form method=get action="index.html">
      <input type=submit value="Logout" >
    </form>

    <p>
  </body>
</html>
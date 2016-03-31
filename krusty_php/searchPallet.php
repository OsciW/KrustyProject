<?php
	require_once('database.inc.php');
	
	session_start();
	$db = $_SESSION['db'];
	$userId = $_SESSION['userId'];
	$userType = $_SESSION['userType'];
  	$start = $_GET['startDate'];
  	$end = $_GET['endDate'];
  	$recipe = $_GET['recipe'];
  	$barcode = $_GET['barcode'];


	$db->openConnection();
	if ($barcode != "") {
  		$Ingredients = $db->getBarcodePallet($barcode);

	} else if ($start < $end && $recipe != null || $start != null && $start == $end && $recipe != null) {
		$Ingredients = $db->getPalletBetweenRecipe($recipe, $start, $end);
	} else if($start < $end || $start == $end && $start != null) {
		$Ingredients = $db->getPalletBetween($start, $end);	 
  	} else if($recipe !=  null) {
		$Ingredients = $db->getRecipePallet($recipe);
  	} else  {
  		$Ingredients = $db->getAllPallets();
	}
  	$db->closeConnection();
?>


<html>
  <head>
    <title>Krusty Kookies - <?php print $userType ?> </title>
  </head>
  <body>

    <h1 align="center">Search Pallet </h1>

    Current user: <?php print $userId?>
    <p>
<?php if ($start < $end) { ?>
<h3> Time intervall between <?php print "$start" ?> - <?php print "$end"?><h>

<?php } ?>

<form method get action "searchPallet.php">
<input placeholder="Recipe Name" name="recipe" type="text"  value ="<?php echo $recipe; ?>">
<input placeholder="Barcode ID" name="barcode" type="text"  value ="<?php echo $barcode; ?>">
<input placeholder="Start date" name="startDate" type="text" onfocus="(this.type='date')"  value ="<?php echo $startDate; ?>">
<input placeholder="End date" name="endDate" type="text" onfocus="(this.type='date')"  value ="<?php echo $endDate; ?>">

<input type=submit value="check" >

</form>
<h3> Pallets <h3>
    <table id="rawMaterialTable" style="border-spacing: 20px">
      <tr>
        <td style="background-color: #FFF"><b>Recipe Name</b></td>
        <td style="background-color: #FFF"><b>Barcode ID</b></td>
        <td style="background-color: #FFF"><b>Time</b></td>
        <td style="background-color: #FFF"><b>Date</b></td>

      </tr>
      <?php foreach($Ingredients as $ingredient){ ?>
        <tr> 
          <td><?php echo $ingredient['recipeName']; ?></td>  
          <td><?php echo $ingredient['barcodeId']; ?></td>
          <td><?php echo $ingredient['createdTime']; ?> </td>
          <td><?php echo $ingredient['createdDate']; ?> </td>
        </tr>
      <?php 
      } ?>
    </table>


  
    <form method=get action="production.php">
      <input type=submit value="back" >
    </form>
    <p>
    <form method=get action="index.html">
      <input type=submit value="Logout" >
    </form>

    <p>
  </body>
</html>
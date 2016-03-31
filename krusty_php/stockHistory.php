<?php
	require_once('database.inc.php');
	
	session_start();
	$db = $_SESSION['db'];
	$userId = $_SESSION['userId'];
	$userType = $_SESSION['userType'];
  $start = $_GET['startDate'];
  $end = $_GET['endDate'];


	$db->openConnection();
  if($start < $end || $start == $end && $start != null) {
   $Ingredients = $db->getSelectedStockEvents($start, $end);
    
  } else {

  $Ingredients = $db->getAllStockEvents();
}
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
<?php if ($start < $end) { ?>
<h3> Time intervall between <?php print "$start" ?> - <?php print "$end"?><h>

<?php } ?>

<form method get action "stockHistory.php">
<input placeholder="Start date" name="startDate" type="text" onfocus="(this.type='date')"  value ="<?php echo $startDate; ?>">
<input placeholder="End date" name="endDate" type="text" onfocus="(this.type='date')"  value ="<?php echo $endDate; ?>">
<input type=submit value="check" >
</form>
<h3> Delivers <h3>
    <table id="rawMaterialTable" style="border-spacing: 20px">
      <tr>
        <td style="background-color: #FFF"><b>Raw material</b></td>
        <td style="background-color: #FFF"><b>Amount</b></td>
        <td style="background-color: #FFF"><b>Time</b></td>
        <td style="background-color: #FFF"><b>Date</b></td>

      </tr>
      <?php foreach($Ingredients as $ingredient){ 
        if($ingredient['quantity'] > 0) {?>
        <tr> 
          <td><?php echo $ingredient['rawMaterialName']; ?></td>  
          <td><?php echo $ingredient['quantity']; ?></td>
          <td><?php echo $ingredient['createdTime']; ?> </td>
          <td><?php echo $ingredient['createdDate']; ?> </td>
        </tr>
      <?php }
      } ?>
    </table>

    <p>
    <p>

    <h3> Orders <h3>
    <table id="rawMaterialTable" style="border-spacing: 20px">
      <tr>
        <td style="background-color: #FFF"><b>Raw material</b></td>
        <td style="background-color: #FFF"><b>Amount</b></td>
        <td style="background-color: #FFF"><b>Time</b></td>
        <td style="background-color: #FFF"><b>Date</b></td>

      </tr>
      <?php foreach($Ingredients as $ingredient){ 
        if($ingredient['quantity'] < 0) {?>
        <tr> 
          <td><?php echo $ingredient['rawMaterialName']; ?></td>  
          <td><?php echo $ingredient['quantity']; ?></td>
          <td><?php echo $ingredient['createdTime']; ?> </td>
          <td><?php echo $ingredient['createdDate']; ?> </td>
        </tr>
      <?php }
      } ?>
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
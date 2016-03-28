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
<title>Krusty Kookies <?php print $userType?> </title>
</head>
<body>

<h1 align="center">Production Testing page </h1>
<p> 
Current user: <?php print $userId ?>
<p>
<p>


<form method=get action="production2.php">
  Create pallets:
  <p>
  barcodeId: <input type="text" name="barcodeId" placeholder="666" value="<?php echo $barcodeId;?>">
  <p>
  Time Created <input type="text" name="time" placeholder="14:30:00" value="<?php echo $time;?>">
  <p>
  date: <input type="text" name="date" placeholder="2013-01-01" value="<?php echo $date;?>">
  <p>
  Status:
  <input type="radio" name="status"
  <?php if (isset($status) && $status=="Blocked") echo "checked";?>
  value="Blocked">Blocked
  <input type="radio" name="status"
  <?php if (isset($status) && $status=="Ok") echo "checked";?>
  value="Ok">Ok
  <input type="radio" name="status"
  <?php if (isset($status) && $status=="Other") echo "checked";?>
  value="Other">Other

    <p>
    
      Recipe:
      <p>
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
  <p>

  	<input type=submit value="Create" >
  	<?php 
  	$_SESSION['barcodeId'] = $barcodeId;
  	$_SESSION['time'] = $time;
  	$_SESSION['date'] = $date; 
  	$_SESSION['status'] = $status;
  	$_SESSION['recipe'] = $recipe;
  	
  	?>
   </form>


<p>
  <form method=get action="index.html">
    <input type=submit value="log out" >
  </form>

</body>
</html>
<?php
  require_once('database.inc.php');
  
  session_start();
  $db = $_SESSION['db'];
  $userId = $_SESSION['userId'];
  $userType = $_SESSION['userType'];

  $db->openConnection();
  $Recipies = $db->getRecipe();
  $db->closeConnection();

  date_default_timezone_set('Europe/Stockholm');
    $dt = new DateTime();
    $date = $dt->format('Y-m-d');
    $time = $dt->format('H:i:s');

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
  Time Created <input type="text" name="time"  value="<?php echo $time;?>">

  <!-- <input placeholder="Date" class="textbox-n" type="text" onfocus="(this.type='date')"  value ="<?php echo $date; ?>"> --> 
  
  <p>
  date: <input type="text" name="date" value="<?php echo $date;?>">
  <p>
  Status:
  <input type="radio" name="status"
  <?php if (isset($status) && $status=="Blocked") echo "checked";?>
  value="true">Blocked
  <input type="radio" name="status"
  <?php if (isset($status) && $status=="Ok") echo "checked";?>
  value="false" checked= "checked">Ok
  <input type="radio" name="status"
  <?php if (isset($status) && $status=="Other") echo "checked";?>
  value="false">Other

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
   </form>
<form method=get action="production.php">
    <input type=submit value="back" >
  </form>


<p>
  <form method=get action="index.html">
    <input type=submit value="log out" >
  </form>

</body>
</html>
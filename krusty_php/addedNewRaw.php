<?php
  require_once('database.inc.php');
  
  session_start();
  $db = $_SESSION['db'];
  $userId = $_SESSION['userId'];
  $userType = $_SESSION['userType'];
  $name = $_REQUEST['name'];
  $quantity = $_REQUEST['quantity'];
  $unit = $_REQUEST['unit'];


  $db->openConnection();
  $added = $db->newRawmaterial($name, $quantity, $unit);
  $db->closeConnection();

?>
<html>
<head>
<title>Krusty Kookies - <?php print $userType ?> </title>
</head>
<body>

<h1 align="center">New Raw Materials Added</h1>
<?php if($added ==true) { ?>

  
  <p>
<h2>
  <?php print "$quantity $unit of $name has been added to our stock";?> </h2> 
  <?php 
  
  } else { 


  print "An error occured";

  } ?>

<form method=get action="addNewRaw.php">
    <input type=submit value="back" >
  </form>


<p>
<form method=get action="index.html">
    <input type=submit value="logout" >
  </form>

<p>


</body>
</html>
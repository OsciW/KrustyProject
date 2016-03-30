<?php
	require_once('database.inc.php');
	
	session_start();
	$db = $_SESSION['db'];
	$userId = $_SESSION['userId'];
	$userType = $_SESSION['userType'];

	$db->openConnection();
  $rawMaterials = $db->getRawmaterials();
  $db->closeConnection();
?>
<html>
<head>
<title>Krusty Kookies - <?php print $userType ?> </title>
</head>
<body>

<h1 align="center">Order more raw materials</h1>

Current user: <?php print $userId ?>
<p>

<form name="orderMoreRaw "id="orderMoreRaw" action="orderedRaw.php">
  <table id="rawMaterialTable">  
    <tr>
      <b>Raw materials</b>
    </tr>
    <?php
    foreach($rawMaterials as $rawmaterial){ ?>
      <tr> 
        <td><?php print $rawmaterial['name']; ?></td>  
        <td><input type="text" name="<?php print str_replace(' ', '', $rawmaterial['name']); ?>" placeholder="quantity (<?php print $rawmaterial['unit']?>)" /></td>
      </tr>
    <?php } ?>


  </table>
<p>  
<input type=submit value="Order" >

</form>



<form method=get action="stockReview.php">
    <input type=submit value="back" >
  </form>


<p>
<form method=get action="index.html">
    <input type=submit value="logout" >
  </form>

<p>


</body>
</html>
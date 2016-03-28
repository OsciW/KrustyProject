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
	Create pallets:
	<p>
	barcodeId: <input type="text" name="name" value="<?php echo $barcodeId;?>">
	<p>
	Time Created <input type="text" name="time" value="<?php echo $time;?>">
	<p>
	date: <input type="text" name="date" value="<?php echo $date;?>">
	<p>
	Status:
	<input type="radio" name="Status"
	<?php if (isset($status) && $status=="Blocked") echo "checked";?>
	value="Blocked">Blocked
	<input type="radio" name="Status"
	<?php if (isset($Status) && $Status=="ok") echo "checked";?>
	value="ok">ok
	<input type="radio" name="Status"
	<?php if (isset($Status) && $Status=="Other") echo "checked";?>
	value="Other">Other

		<p>
		
			Recipe:
	<form method=post action="production2.php">
		<select name="Recipies" size=10>
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


<form method=get action="production2.php">
		<input type=submit value="log out" >

	</form>


</body>
</html>
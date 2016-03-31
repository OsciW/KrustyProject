<?php
	require_once('database.inc.php');
	session_start();

	$db = $_SESSION['db'];
	$userId = $_SESSION['userId'];
	$userType = $_SESSION['userType'];

	$recipes = $_SESSION['recipes'];
	$date=$_POST['deliveryDate'];
	$time=$_POST['deliveryTime'];

	unset($_POST['deliveryDate']);
	unset($_POST['deliveryTime']);

	$specs=array();
	$i=0;
	foreach ($_POST as $key => $value) {
		$specs[$i]=array($recipes[$i], $value);
  		$i++;
	}

	$db->openConnection();
	$customerAddress= $db->getCustomerAddress($userId);
	$orderSpec=$db->placeOrder($userId, $time, $date, $specs);
  	$db->closeConnection();


  	echo 'Order confirmation </br></br>';
	echo 'User: '.$userId."</br>";
	echo 'Delivery address: '.$customerAddress."</br>";

	echo 'Delivery date: ';
	echo $date."</br>";

	echo 'Delivery time: ';
	echo $time."</br></br>Cookies ordered: </br>";

	$i=0;
	foreach ($_POST as $key => $value) {
		echo $recipes[$i].": {$value}<br />";
  		$i++;
	}


?>

<body>
<html>
<p>


    <form method=get action="index.html">
      <input type=submit value="Logout" >
    </form>

      </body>
</html>
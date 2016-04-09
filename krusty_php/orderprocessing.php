<?php
	require_once('database.inc.php');
	session_start();

	$db = $_SESSION['db'];
	$userId = $_SESSION['userId'];
	$userType = $_SESSION['userType'];

	if($userType == 'Customer') {
		$customerName = $_SESSION['customerName'];
	} else {
		$customerName = $_REQUEST['customerName'];
	}

	$recipes = $_SESSION['recipes'];
	$date=$_POST['deliveryDate'];
	$time=$_POST['deliveryTime'];



	unset($_POST['deliveryDate']);
	unset($_POST['deliveryTime']);

	$no_cookies=0;

	$specs=array();
	$i=0;
	foreach ($_POST as $key => $value) {
		$no_cookies=$no_cookies+$value;

		$specs[$i]=array($recipes[$i], $value);
  		$i++;
	}


	if($no_cookies==0) {
		header("Location: errorOrder.php");
	}

	$db->openConnection();

	$orderSpec=$db->placeOrder($customerName, $time, $date, $specs);
  	$db->closeConnection();

  	if($orderSpec[0] == NULL){
  	
  		header("Location: errorOrder.php");
  	}


  	echo 'Order confirmation </br></br>';
	echo 'User: '.$userId."</br>";
	echo 'Customer: '.$customerName."</br>";

	echo 'Delivery date: ';
	echo $date."</br>";

	echo 'Delivery time: ';
	echo $time."</br>";

	echo 'OrderId: ';
	echo $orderSpec[0]."</br></br>";

	echo "Cookies ordered: </br>";

	for($i=1; $i<count($orderSpec); $i++) {
		echo $orderSpec[$i][0].": ".$orderSpec[$i][1]."<br />";
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
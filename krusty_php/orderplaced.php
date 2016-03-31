<?php

	session_start();

	

?>

<html>
<head>
<title>Order confirmation</title>
</head>
<body>

<h1 align="center">Thank you for your order</h1>


<p class="breadtext">
		Order details:
	</p>

<?php

	

	$date=$_REQUEST['deliveryDate'];
	$time=$_REQUEST['deliveryTime'];


	echo 'Delivery date: ';
	echo $date."</br>";

	echo 'Delivery time: ';
	echo $time."</br></br>Cookies ordered: </br>";


	#$recipes = $_SESSION['allRecipes'];

	unset($_POST['deliveryDate']);
	unset($_POST['deliveryTime']);

	foreach ($_POST as $key => $value) {
  		print "{$key}: {$value}<br />";
	}

?>

</body>
</html>
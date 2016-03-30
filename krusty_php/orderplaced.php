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
		We are happy to extend our partnership
	</p>

<?php

	

	$date=$_REQUEST['deliveryDate'];
	$time=$_REQUEST['deliveryTime'];

	$=$_REQUEST['deliveryTime'];

	echo 'Delivery date: ';
	echo $date."</br>";

	echo 'Delivery time: ';
	echo $time."</br>";
?>

<?php

	session_start();

	$recipes = $_SESSION['allRecipes'];

	foreach($recipes as $r){
		echo $r . ', ';
	}

?>

</body>
</html>
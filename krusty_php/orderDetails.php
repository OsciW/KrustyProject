<?php
	require_once('database.inc.php');
	
	session_start();
	$db = $_SESSION['db'];
	$userId = $_SESSION['userId'];
	$userType = $_SESSION['userType'];
	$orderId = $_REQUEST['orderId'];

	$db->openConnection();
    $orders = $db->getSpec($orderId);
	$db->closeConnection();
?>


<html>
<head>
<title>Krusty Kookies</title>
</head>
<body>

<h1 align="center"> Orders details  </h1>

    <h3> Orders to be loaded </h3>


    <table id="orderTable" style="border-spacing: 20px">
      <tr>
        <td style="background-color: #FFF"><b>Customer Name</b></td>
        <td style="background-color: #FFF"><b>RecipeName</b></td> 
        <td style="background-color: #FFF"><b>Quantity</b></td>


      </tr>
      <?php foreach($orders as $order){ ?>
        <tr> 
          <td><?php echo $order['customerName']; ?></td>
          <td><?php echo $order['recipeName']; ?></td> 
          <td><?php echo $order['quantity']; ?></td>
        </tr>
      <?php 
      } ?>
    </table>

<form method=get action="ordersDeliver.php">
        <input type=submit value="back" >
      </form>
      
      <form method=get action="index.html">
        <input type=submit value="log out" >
      </form>

</body>
</html>
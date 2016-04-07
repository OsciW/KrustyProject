<?php
	require_once('database.inc.php');
	
	session_start();
	$db = $_SESSION['db'];
	$userId = $_SESSION['userId'];
	$userType = $_SESSION['userType'];

	$db->openConnection();

	$orders = $db->getOrders();
  $pallets = $db->getAllOrders();
	$db->closeConnection();
?>


<html>
<head>
<title>Krusty Kookies</title>
</head>
<body>



<h1 align="center"> All orders  </h1>



<h3>Pallets in stock (not blocked)</h3>

    <table id="palletTable" style="border-spacing: 20px">
      <tr>
        <td style="background-color: #FFF"><b>Order Id</b></td>
        <td style="background-color: #FFF"><b>Customer name</b></td>
        <td style="background-color: #FFF"><b>Delivery Date</b></td>
        <td style="background-color: #FFF"><b>Delivery Time</b></td>
  

      </tr>
      <?php foreach($pallets as $pallet){ ?>
        <tr> 
          <td><?php echo $pallet['id']; ?></td> 
          <td><?php echo $pallet['customerName']; ?></td> 
          <td><?php echo $pallet['deliveryDate']; ?></td>
          <td><?php echo $pallet['deliveryTime']; ?></td>
          <td><?php echo $pallet['statusName']; ?></td> 
    
         
        </tr>
      <?php 
      } ?>
    </table>







     <form method=get action="index.html">
        <input type=submit value="log out" >
      </form>








</body>
</html>
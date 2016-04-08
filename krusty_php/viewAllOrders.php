<?php
	require_once('database.inc.php');
	
	session_start();
	$db = $_SESSION['db'];
	$userId = $_SESSION['userId'];
	$userType = $_SESSION['userType'];
  $action = $_GET['action'];

	$db->openConnection();

	$orders = $db->getOrders();
  
  if($action == "Orders") {
    $pallets = $db->getAllOrders();
  } else {
    
    $pallets = $db->getPalletOrders();
  }

	$db->closeConnection();

  header("viewAllOrders.php");
?>


<html>
<head>
<title>Krusty Kookies</title>
</head>
<body>



<h1 align="center"> All orders  </h1>

  <form method=get action="viewAllOrders.php">
        <input name = "action" type=submit value="Orders" >
      </form>
      <form method=get action="viewAllOrders.php">
        <input name = "action" type=submit value="Pallets used" >
      </form>



<h3>Orders</h3>
    <table id="palletTable" style="border-spacing: 20px">
      <tr>
        <td style="background-color: #FFF"><b>Order Id</b></td>
        <td style="background-color: #FFF"><b>Customer name</b></td>
        <td style="background-color: #FFF"><b>Created Date</b></td>
        <td style="background-color: #FFF"><b>Delivery date</b></td>
        <td style="background-color: #FFF"><b></b></td>
        <td style="background-color: #FFF"><b></b></td>
        <td style="background-color: #FFF"><b></b></td>
  

      </tr>
      <?php
      if($action =='Orders') {
       foreach($pallets as $pallet){ ?>
        <tr> 
          <td><?php echo $pallet['id']; ?></td> 
          <td><?php echo $pallet['customerName']; ?></td> 
          <td><?php echo $pallet['createdDate']; ?></td>
          <td><?php echo $pallet['deliveryDate']; ?></td>
          <td><?php echo $pallet['statusName']; ?></td> 
    
         
        </tr>
      <?php 
      } 
    } else {
      foreach($pallets as $pallet){ ?>
        <tr> 
          <td><?php echo $pallet['palletId']; ?></td> 
          <td><?php echo $pallet['createdDate']; ?></td> 
          <td><?php echo $pallet['recipeName']; ?></td>
          <td><?php echo $pallet['orderId']; ?></td>
          <td><?php echo $pallet['customerName']; ?></td>
          <td><?php echo $pallet['deliveryDate']; ?></td> 
    
         
        </tr>
      <?php 
      } 


    }

      ?>
    </table>





    <form method=get action="ordersDeliver.php">
        <input type=submit value="Back" >
      </form>


     <form method=get action="index.html">
        <input type=submit value="log out" >
      </form>








</body>
</html>
<?php
	require_once('database.inc.php');
	
	session_start();
	$db = $_SESSION['db'];
	$userId = $_SESSION['userId'];
	$userType = $_SESSION['userType'];
	$orderId = $_GET['orderId'];
  $action = $_GET['action'];
	$db->openConnection();
  if($action == 'Delivered') {
    $Delivered = $db->initiateLoad($orderId, $action);

  } else if($action == 'Canceled') {
    $db->changeStat($orderId, $action);
  } else if($action == 'Remove') {
    $db->removeOrder($orderId);

  }

	$pallets = $db->getPalletsNotBlocked();

	$orders = $db->getOrders();

	$db->closeConnection();
  header( "ordersDeliver.php");
?>


<html>
<head>
<title>Krusty Kookies</title>
</head>
<body>





<h1 align="center"> Orders and Deliveries  </h1>

  <?php if($Delivered) {
    ?>
    Order <?php echo $orderId ?> was successfully Delivered
    <?php
  }
  ?> 


<h3>Actions</h3>
    <p class="text">
        <a href="viewAllOrders.php">Check all orders</a>
    </p>




<h3>Pallets in stock (not blocked)</h3>

    <table id="palletTable" style="border-spacing: 20px">
      <tr>
        <td style="background-color: #FFF"><b>Recipe Name</b></td>
        <td style="background-color: #FFF"><b>Pallets in stock</b></td>
  

      </tr>
      <?php foreach($pallets as $pallet){ ?>
        <tr> 
          <td><?php echo $pallet['recipeName']; ?></td>  
          <td><?php echo $pallet['number']; ?></td>
         
        </tr>
      <?php 
      } ?>
    </table>


    <h3> Orders to be Delivered </h3>

    <table id="orderTable" style="border-spacing: 20px">
      <tr>
        <td style="background-color: #FFF"><b>Order Id</b></td>
        <td style="background-color: #FFF"><b>Customer Name</b></td> 
        <td style="background-color: #FFF"><b>Created Date</b></td>
  		  <td style="background-color: #FFF"><b>Delivery Date</b></td>
        <td style="background-color: #FFF"><b>Status</b></td>

        <td style="background-color: #FFF"><b>Details</b></td>
        

  		<td style="background-color: #FFF"><b>Change status</b></td> 
      <td style="background-color: #FFF"><b></b></td> 
      <td style="background-color: #FFF"><b></b></td>

      </tr>
      <?php foreach($orders as $order){ ?>
        <tr> 
          <td><?php echo $order['id']; ?></td> 
          <td><?php echo $order['customerName']; ?></td> 
          <td><?php echo $order['createdDate']; ?></td>
          <td><?php echo $order['deliveryDate']; ?></td>
          <td><?php echo $order['statusName']; ?></td>
          <td><form method=get action="orderDetails.php">
            <input type="hidden" name="orderId" value="<?php echo $order['id']; ?>" >
      <input type=submit name= "random" value="Check Details" >
    </form></td>
            
            
            <td><form method=get action="ordersDeliver.php">
            <input type="hidden" name="orderId" value="<?php echo $order['id']; ?>" >
      <input type=submit name= "action" value="Delivered" >
    </form></td>
    <td><form method=get action="ordersDeliver.php">
            <input type="hidden" name="orderId" value="<?php echo $order['id']; ?>" >

      <input type=submit name= "action" value="Canceled" >
    </form></td>
    <td><form method=get action="ordersDeliver.php">
            <input type="hidden" name="orderId" value="<?php echo $order['id']; ?>" >
      <input type=submit name= "action" value="Remove" >
    </form></td>

         
        </tr>
      <?php 
      } ?>
    </table>





     <form method=get action="index.html">
        <input type=submit value="log out" >
      </form>








</body>
</html>
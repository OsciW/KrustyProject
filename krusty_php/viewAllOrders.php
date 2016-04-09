<?php
	require_once('database.inc.php');
	
	session_start();
	$db = $_SESSION['db'];
	$userId = $_SESSION['userId'];
	$userType = $_SESSION['userType'];
  $action = $_GET['action'];
  $customerName = $_GET['customerName'];

	$db->openConnection();
  $customer = $db->getCompanies();
	$orders = $db->getOrders();
  if(!$customerName) {
    if($action == "Orders") {
      $pallets = $db->getAllOrders();
    } else {
      $pallets = $db->getPalletOrders();
    }
  } else {
    $pallets = $db->getPalletOrdersForC($customerName);
  }
  
	$db->closeConnection();
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
        <form method get action "viewAllOrders.php">
<tr>
    <td align="center"><select name="customerName">
     <option name="sumthing" value="">All companies</option>
    <?php
  foreach ($customer as $name ) {
?>
      <option name="customerName" value="<?php print $name ?>"><?php print $name?></option>
    
<?php
  }
?>
 </select></td>
 <input type=submit value="check" >


<?php if($action =='Orders') { ?>
<h3>Orders</h3>
<?php } else { ?>

  <h3>Delivered Pallets </h3>
<?php } ?>
    <table id="palletTable" style="border-spacing: 20px">
      <tr>
      <?php  if($action =='Orders') { ?>
        <td style="background-color: #FFF"><b>Order Id</b></td>
        <td style="background-color: #FFF"><b>Customer name</b></td>
        <td style="background-color: #FFF"><b>Created Date</b></td>
        <td style="background-color: #FFF"><b>Delivery date</b></td>
        <td style="background-color: #FFF"><b>Status</b></td>
        <td style="background-color: #FFF"><b></b></td>
        <td style="background-color: #FFF"><b></b></td>
  <? } else { ?>

       <td style="background-color: #FFF"><b>Pallet Id</b></td>
        <td style="background-color: #FFF"><b>Created Date</b></td>
        <td style="background-color: #FFF"><b>Recipe name</b></td>
        <td style="background-color: #FFF"><b>Order Id</b></td>
        <td style="background-color: #FFF"><b>Customer name</b></td>
        <td style="background-color: #FFF"><b>Delivery date</b></td>

 <?php  } ?>

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

 </tr>
 </form>

 <form method=get action="ordersDeliver.php">
        <input type=submit value="back" >
      </form>
      
     <form method=get action="index.html">
        <input type=submit value="log out" >
      </form>

        







</body>
</html>
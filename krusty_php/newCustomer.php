<?php
  require_once('database.inc.php');
  
  session_start();
  $db = $_SESSION['db'];
  

  $db->openConnection();
  $customer = $db->getCompanies();
  $db->closeConnection();

?>


<html>
<head>
<title>Krusty Kookies - New Customer </title>
</head>
<body>

<h1 align="center">New Customer</h1>

<p>


<form method=get action="newCustomer1.php">
  <p>
  Customer: <input type="text" name="customerName" placeholder="SkaneKakor AB" value="<?php echo $customerName;?>">
  <p>
  Address: <input type="text" name="address" placeholder="Lund" value="<?php echo $address;?>">
  <p>
  Telephone nbr: <input type="text" name="tele" placeholder="Optional" value="<?php echo $tele;?>">
  <p>
   

  	<input type=submit value="Create" >
   </form>


<p>
  <form method=get action="index.html">
    <input type=submit value="Back" >
  </form>

</body>
</html>
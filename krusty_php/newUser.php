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
<title>Krusty Kookies <?php print $userType?> </title>
</head>
<body>

<h1 align="center">New User</h1>
<p> 
Current user: <?php print $userId ?>
<p>
<p>


<form method=get action="newUser1.php">
  <p>
  Username: <input type="text" name="username" placeholder="per" value="<?php echo $username;?>">
  <p>
  SSN <input type="text" name="userId" placeholder="9110080111" value="<?php echo $userId;?>">
  <p>

  Type:
  <input type="radio" name="userType"
  <?php if (isset($status) && $status=="Production") echo "checked";?>
  value="Production" checked= "checked">Production
  <input type="radio" name="userType"
  <?php if (isset($status) && $status=="Customer") echo "checked";?>
  value="Customer" >Customer
  <input type="radio" name="userType"
  <?php if (isset($status) && $status=="StockManager") echo "checked";?>
  value="StockManager">StockManager
  <input type="radio" name="userType"
  <?php if (isset($status) && $status=="OrdersDelivers") echo "checked";?>
  value="OrdersDelivers">OrdersDelivers
  <input type="radio" name="userType"
  <?php if (isset($status) && $status=="superUser") echo "checked";?>
  value="superUser">superUser

    <p>

If new Customer user is selected please select company for this user
<p>    


   Company name:
      <p>
    <select name="customer" size=10>
    <?php
      $first = true;
      foreach ($customer as $name) {
        if ($first) {
          print "<option selected>";
          $first = false;
        } else {
          print "<option>";
        }
        print $name;
      }
    
    ?>
    </select>
  <p>

   

  	<input type=submit value="Create" >
   </form>


<p>
  <form method=get action="index.html">
    <input type=submit value="log out" >
  </form>

</body>
</html>
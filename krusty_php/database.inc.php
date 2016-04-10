<?php
/*
 * Class Database: interface to the movie database from PHP.
 *
 * You must:
 *
 * 1) Change the function userExists so the SQL query is appropriate for your tables.
 * 2) Write more functions.
 *
 */
class Database {
	private $host;
	private $userName;
	private $password;
	private $database;
	private $conn;
	
	/**
	 * Constructs a database object for the specified user.
	 */
	public function __construct($host, $userName, $password, $database) {
		$this->host = $host;
		$this->userName = $userName;
		$this->password = $password;
		$this->database = $database;
	}
	
	/** 
	 * Opens a connection to the database, using the earlier specified user
	 * name and password.
	 *
	 * @return true if the connection succeeded, false if the connection 
	 * couldn't be opened or the supplied user name and password were not 
	 * recognized.
	 */
	public function openConnection() {
		try {
			$this->conn = new PDO("mysql:host=$this->host;dbname=$this->database", 
					$this->userName,  $this->password);
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
			$error = "Connection error: " . $e->getMessage();
			print $error . "<p>";
			unset($this->conn);
			return false;
		}
		return true;
	}
	
	/**
	 * Closes the connection to the database.
	 */
	public function closeConnection() {
		$this->conn = null;
		unset($this->conn);
	}

	/**
	 * Checks if the connection to the database has been established.
	 *
	 * @return true if the connection has been established
	 */
	public function isConnected() {
		return isset($this->conn);
	}
	
	/**
	 * Execute a database query (select).
	 *
	 * @param $query The query string (SQL), with ? placeholders for parameters
	 * @param $param Array with parameters 
	 * @return The result set
	 */
	private function executeQuery($query, $param = null) {
		try {
			$stmt = $this->conn->prepare($query);
			$stmt->execute($param);
			$result = $stmt->fetchAll();
		} catch (PDOException $e) {
			$error = "*** Internal error: " . $e->getMessage() . "<p>" . $query;
			die($error);
		}
		return $result;
	}
	
	/**
	 * Execute a database update (insert/delete/update).
	 *
	 * @param $query The query string (SQL), with ? placeholders for parameters
	 * @param $param Array with parameters 
	 * @return The number of affected rows
	 */
	private function executeUpdate($query, $param = null) {
		try {
			$stmt = $this->conn->prepare($query);
			$stmt->execute($param);
			$count = $stmt->rowCount();
		} catch (PDOException $e) {
			return 0;
		}
		return $count;
	}
	
	/**
	 * Check if a user with the specified user id exists in the database.
	 * Queries the Users database table.
	 *
	 * @param userId The user id 
	 * @return true if the user exists, false otherwise.
	 */
	public function userExists($userId) {
		$sql = "select name from users where name = '$userId'";
		
		try {
			$result = $this->executeQuery($sql, array($userId));
		} 
		catch (PDOException $e) {
			$error = "*** Internal error: " . $e->getMessage() . "<p>" . $query;
			die($error);
		}
		return count($result) == 1; 
	}

	public function getUserType($userId) {
		$sql = "select type from users where name = '$userId'";

		try {
			$result = $this->executeQuery($sql, array($userId));

			foreach ($result as $row) {
   					$res[] = $row['type'];
   				}
   				
		} 
		catch (PDOException $e) {
			$error = "*** Internal error: " . $e->getMessage() . "<p>" . $query;
			die($error);
		}
		return $res; 
	}


	public function getRecipe() {
		$sql = "select name from recipe";

		try {

			$result = $this->executeQuery($sql);
			$res = array();
			if($result) {
   				foreach ($result as $row) {
   					$res[] = $row['name'];
   				}
			}
		} catch(PDOException $e) {
		$error = "*** Internal error: " . $e->getMessage() . "<p>" . $query;
			die($error);
		}
		return $res;
	}

	public function getUserCustomer($userName) {

		$sql = "select c.name from custUser c where c.pNbr in (select pNbr from users where name ='$userName')";
		try {

			$result = $this->executeQuery($sql);
			$res = array();
			if($result) {
   				foreach ($result as $row) {
   					$res[] = $row;
   				}
			}
		} catch(PDOException $e) {
		$error = "*** Internal error: " . $e->getMessage() . "<p>" . $query;
			die($error);
		}
		return $res;


	}




	public function placeOrder($userId, $deliveryTime, $deliveryDate, $specs) {
		$sql  = "insert into orders (customerName, createdTime, createdDate, deliveryDate, deliveryTime)".
			"VALUES ('$userId', curtime(), curdate(), '$deliveryDate', '$deliveryTime')";

		try {	
			$rowChange = $this->executeUpdate($sql);
			if ($rowChange == 1) {
				$orderId = $this->conn->lastInsertId();

				$confirmation=array($orderId);
				$this->createStatusEvent($orderId, 'Recieved');
				$i=0;
				foreach($specs as $orderSpec) {
					if ($orderSpec[1]>0) {
						$ref=$this->placeOrderSpec($orderId, $orderSpec[0], $orderSpec[1]);
						$confirmation[$i+1]=$ref;
						$i++;
					}
					
				} 
				
			}
		}
		catch (PDOException $e) {
			$error = "*** Internal error: " . $e->getMessage() . "<p>" . $query;
			die($error);
		}	
		return $confirmation;

	}

	public function createStatusEvent($orderId, $status) {
		$sql = "INSERT INTO orderStatusEvent(orderId, statusName) VALUES ('$orderId', '$status')";

		try {	
			$rowChange = $this->executeUpdate($sql);
			if ($rowChange == 1) {
				return true;
			}
		}
		catch (PDOException $e) {
			$error = "*** Internal error: " . $e->getMessage() . "<p>" . $query;
			die($error);
		}

		return false;

	}

	public function placeOrderSpec($orderId, $recipeName, $quantity) {
		$sql= "insert into orderspec(orderId, recipeName, quantity) VALUES".
		"('$orderId', '$recipeName', '$quantity')";

		try {	
			$rowChange = $this->executeUpdate($sql);
			if ($rowChange == 1) {
				return array($recipeName, $quantity);
			}
		}
		catch (PDOException $e) {
			$error = "*** Internal error: " . $e->getMessage() . "<p>" . $query;
			die($error);
		}

		return array($recipeName, $quantity);
	}

	public function getCustomerAddress($customerName) {
		$sql = "select address from customer where name='$customerName'";

		try {
			$result = $this->executeQuery($sql);
		} 
		catch(PDOException $e) {
			$error = "*** Internal error: " . $e->getMessage() . "<p>" . $query;
			die($error);
		}
		return $result[0][0];
	}


	public function createPallet($barcodeId, $time, $date, $status, $recipe) {
	
		if($this->subtractRawmaterial($recipe) == true) {
			$sql = "insert into Pallet (barcodeId, createdTime, createdDate,". 
			 		"blocked, recipeName) values ($barcodeId, '$time','$date'".
			 		", $status, '$recipe')";
			try {	

				$rowChange = $this->executeUpdate($sql);
				if ($rowChange == 1) {

					$resNbr = $this->conn->lastInsertId();
					$this->palletRawStock($recipe, $time, $date);
					return $resNbr;
					}
				}
				catch (PDOException $e) {
					$error = "*** Internal error: " . $e->getMessage() . "<p>" . $query;
					die($error);
				}	
			return $resNbr;
		} 
	return 0;

	}


	public function checkIngredients($recipe) {

		$sql = "select quantitystock - 54*quantity as q from rawmaterial r, ingredient i where r.name = i.rawmaterialname and i.recipename = '$recipe'";
		try {
			$result = $this->executeQuery($sql);
			$res = array();
			if($result) {
   				foreach ($result as $row) {
   					$s = $row['q'];
   					if($s < 0) {
   						return false;
   					}

   				}
			}
		} catch(PDOException $e) {
		$error = "*** Internal error: " . $e->getMessage() . "<p>" . $query;
			die($error);
		}

		return true;
	}

	public function palletRawStock($recipe, $time, $date) {


		$sql = "select r.name, -54*i.quantity as q from rawmaterial r, ingredient i where r.name = i.rawmaterialname and i.recipeName = '$recipe'";
		try {
			$result = $this->executeQuery($sql);
			if($result) {
				foreach($result as $row) {
					$n = $row['name'];
					$quantity = $row['q'];
					$this->palletStockEvent($time, $n, $quantity, $date);
				}
			}
		} catch (PDOException $e) {
			$error = "*** Internal error: " .$e->getMessage() . "<p>" . $query;
			die($error);
		}

	}



	public function subtractRawmaterial($recipe) {
		if ($this->checkIngredients($recipe) == true) {


			$sql = "update rawmaterial r, ingredient i set quantitystock".
			" = r.quantitystock - 54*i.quantity where r.name = i.rawmaterialname".
			" and i.recipename = '$recipe'";

			$this->conn->query("BEGIN");

			try {	
				$rowChange = $this->executeUpdate($sql);
				if ($rowChange > 0) {
					$this->conn->query("COMMIT");
					return true;
				}
				else {
					$this->conn->query("ROLLBACK");
					return false;		
				}
			}
			catch (PDOException $e) {
				$error = "*** Internal error: " . $e->getMessage() . "<p>" . $query;
				die($error);
			}	
		}
		return false;
	}

	public function getIngredients($recipe) {

		$sql = "select i.rawMaterialName, i.quantity, r.unit from ingredient i,rawmaterial r where i.recipeName = '$recipe' AND r.name = i.rawmaterialname ;";

		try {
			$res = array();
			$result = $this->executeQuery($sql);
			if($result) {
				foreach($result as $row) {
					$res[] = $row;
				}
			}

		} catch(PDOException $e) {
			$error = "*** Internal error: " . $e->getMessage() . "<p>" . $query;
			die($error);
		}
		return $res;

	}

	public function getAllIngredients() {
		$sql = "select distinct name from rawmaterial;";

		try {
			$res = array();
			$result = $this->executeQuery($sql);
			if($result) {
				foreach($result as $row) {
					$res[] = $row['name'];
				}
			}

		} catch(PDOException $e) {
			$error = "*** Internal error: " . $e->getMessage() . "<p>" . $query;
			die($error);
		}
		return $res;
	}

	public function getAllIngredientsRecipe($recipeName) {
		$sql = "select distinct name, quantity from rawmaterial r ;";

		try {
			$res = array();
			$result = $this->executeQuery($sql);
			if($result) {
				foreach($result as $row) {
					$res[] = $row['name'];
				}
			}

		} catch(PDOException $e) {
			$error = "*** Internal error: " . $e->getMessage() . "<p>" . $query;
			die($error);
		}
		return $res;
	}




	public function addRecipe($name) {
		$sql = "insert into Recipe(name) VALUES ('$name');";
		try {	
			$rowChange = $this->executeUpdate($sql);
			if ($rowChange == 1) {
				$idNbr = $this->conn->lastInsertId();
			}
		} catch (PDOException $e) {			
			$error = "*** Internal error: " . $e->getMessage() . "<p>" . $query;
			die($error);
		}
		return $idNbr;
	}


	public function insertRecipeIngredient($ingredient, $quantity, $recipe) {
		if($recipe == "") {
			return 0;
		}

		$sql = "insert into ingredient(rawmaterialname, quantity, recipename) VALUES ('$ingredient',$quantity,'$recipe');";
		try {	
			$rowChange = $this->executeUpdate($sql);
			if ($rowChange == 1) {
				$idNbr = $this->conn->lastInsertId();
			}
		} catch (PDOException $e) {			
			$error = "*** Internal error: " . $e->getMessage() . "<p>" . $query;
			die($error);
		}
		return $idNbr;
	}



	public function deleteOldIngredients($recipe) {
		$sql = "delete from ingredient where recipeName ='$recipe'; ";
		try {	
			$rowChange = $this->executeUpdate($sql);
			if ($rowChange > 0) {
				return true;
			}
		} catch (PDOException $e) {			
			$error = "*** Internal error: " . $e->getMessage() . "<p>" . $query;
			die($error);
		}
		return false;
	}

	public function deleteRecipe($recipe) {
		$sql = "delete from recipe where name = '$recipe';";
		try {	
			$rowChange = $this->executeUpdate($sql);
			if ($rowChange == 1) {
				$idNbr = $this->conn->lastInsertId();
			}
		} catch (PDOException $e) {			
			$error = "*** Internal error: " . $e->getMessage() . "<p>" . $query;
			die($error);
		}
		return $idNbr;
	}

	public function getRawmaterials() {
		$sql = "select * from rawmaterial;";

		try {
			$result = $this->executeQuery($sql);
			$res = array();
			if($result) {
   				foreach ($result as $row) {
   					$res[] = $row;
   				}
			}
		} catch(PDOException $e) {
		$error = "*** Internal error: " . $e->getMessage() . "<p>" . $query;
			die($error);
		}

		return $res;
	}

	public function getRawName() {
		$sql = "select * from rawmaterial;";
		
		try {
			$result = $this->executeQuery($sql);
			$res = array();
			if($result) {
   				foreach ($result as $row) {
   					$res[] = $row['name'];
   				}
			}
		} catch(PDOException $e) {
		$error = "*** Internal error: " . $e->getMessage() . "<p>" . $query;
			die($error);
		}

		return $res;
	}
	public function insertRawMaterial($name, $quantity) {
		if($this->createStockEvent($name, $quantity) == true) {
			$sql ="update rawmaterial set quantityStock = quantityStock + $quantity where name = '$name';";
			try {	
				$rowChange = $this->executeUpdate($sql);
				if ($rowChange == 1) {
					return true;
				}
			} catch (PDOException $e) {			
				$error = "*** Internal error: " . $e->getMessage() . "<p>" . $query;
				die($error);
			}
		}
		return false;
	}

	public function newRawmaterial($name, $quantity, $unit) {
			$sql = "insert into rawMaterial(name, quantityStock, unit) VALUES ('$name',$quantity,'$unit')";

			try {	
				$rowChange = $this->executeUpdate($sql);
				if ($rowChange == 1) {
					$this->createStockEvent($name, $quantity);
					return true;
				} else {
					return false;
				}
			} catch (PDOException $e) {			
				$error = "*** Internal error: " . $e->getMessage() . "<p>" . $query;
				die($error);
			}

		return false;
	}

	public function createStockEvent($name, $quantity) {
		date_default_timezone_set('Europe/Stockholm');
  		$dt = new DateTime();
		$date = $dt->format('Y-m-d');
		$time = $dt->format('H:i:s');
		$sql = "Insert into StockEvent(quantity, createdTime, createdDate, rawMaterialName)".
		"values ('$quantity', '$time', '$date', '$name')";
		try {	
			$rowChange = $this->executeUpdate($sql);
			if ($rowChange == 1) {
				return true;
			} else {
				return false;
			}
		} catch (PDOException $e) {			
			$error = "*** Internal error: " . $e->getMessage() . "<p>" . $query;
			die($error);
		}
		return false;
	}

	public function palletStockEvent($time, $name, $quantity, $date) {
		$sql = "Insert into StockEvent(quantity, createdTime, createdDate, rawMaterialName)".
		"values ('$quantity', '$time', '$date', '$name')";
		try {	
			$rowChange = $this->executeUpdate($sql);
			if ($rowChange == 1) {
				return true;
			} else {
				return false;
			}
		} catch (PDOException $e) {			
			$error = "*** Internal error: " . $e->getMessage() . "<p>" . $query;
			die($error);
		}
		return false;
	}




	public function getAllStockEvents() {
		$sql = "select * from stockevent;";
		try {
			$result = $this->executeQuery($sql);
			$res = array();
			if($result) {
   				foreach ($result as $row) {
   					$res[] = $row;
   				}
			}
		} catch(PDOException $e) {
		$error = "*** Internal error: " . $e->getMessage() . "<p>" . $query;
			die($error);
		}

		return $res;
	}
	public function getSelectedStockEvents($start, $end) {

		$sql = "select * from stockevent where createdDate between '$start' and '$end';";
		try {
			$result = $this->executeQuery($sql);
			$res = array();
			if($result) {
   				foreach ($result as $row) {
   					$res[] = $row;
   				}
			}
		} catch(PDOException $e) {
		$error = "*** Internal error: " . $e->getMessage() . "<p>" . $query;
			die($error);
		}

		return $res;

	}

	public function getAllPallets() {
		$sql = "select * from pallet where id not in (select palletId from orderPallet join orderStatusEvent where statusName = 'Delivered')";
		try {
			$result = $this->executeQuery($sql);
			$res = array();
			if($result) {
   				foreach ($result as $row) {
   					$res[] = $row;
   				}
			}
		} catch(PDOException $e) {
		$error = "*** Internal error: " . $e->getMessage() . "<p>" . $query;
			die($error);
		}

		return $res;
	}

	public function getAllpRep() {
		$sql = "select recipeName from pallet where". 
		" id not in (select palletId from orderPallet join orderStatusEvent where".
		" statusName = 'Delivered') group by recipeName";
		try {
			$result = $this->executeQuery($sql);
			$res = array();
			if($result) {
   				foreach ($result as $row) {
   					$res[] = $row;
   				}
			}
		} catch(PDOException $e) {
		$error = "*** Internal error: " . $e->getMessage() . "<p>" . $query;
			die($error);
		}
		return $res;

	}


	public function getRecipePallet($recipe) {
		$sql = "select * from pallet where recipeName = '$recipe' and". 
		" id not in (select palletId from orderPallet join orderStatusEvent where statusName = 'Delivered')";
		try {
			$result = $this->executeQuery($sql);
			$res = array();
			if($result) {
   				foreach ($result as $row) {
   					$res[] = $row;
   				}
			}
		} catch(PDOException $e) {
		$error = "*** Internal error: " . $e->getMessage() . "<p>" . $query;
			die($error);
		}
		return $res;
	}
		public function getBarcodePallet($barcodeId) {
		$sql = "select * from pallet where barcodeId = '$barcodeId' and id not in (select".
		" palletId from orderPallet join orderStatusEvent where statusName = 'Delivered')";
		try {
			$result = $this->executeQuery($sql);
			$res = array();
			if($result) {
   				foreach ($result as $row) {
   					$res[] = $row;
   				}
			}
		} catch(PDOException $e) {
		$error = "*** Internal error: " . $e->getMessage() . "<p>" . $query;
			die($error);
		}
		return $res;
	}

	public function getPalletBetween($start, $end) {
		$sql = "select * from pallet where createdDate between '$start' and '$end' and".
		" id not in (select palletId from orderPallet join orderStatusEvent where statusName = 'Delivered')";
		try {
			$result = $this->executeQuery($sql);
			$res = array();
			if($result) {
   				foreach ($result as $row) {
   					$res[] = $row;
   				}
			}
		} catch(PDOException $e) {
		$error = "*** Internal error: " . $e->getMessage() . "<p>" . $query;
			die($error);
		}
		return $res;
	}

		public function getPalletBetweenRecipe($recipe, $start, $end) {
		$sql = "select * from pallet where createdDate between '$start' and '$end' and".
		" recipeName = '$recipe' and id not in (select palletId from orderPallet join".
		" orderStatusEvent where statusName = 'Delivered')";
		try {
			$result = $this->executeQuery($sql);
			$res = array();
			if($result) {
   				foreach ($result as $row) {
   					$res[] = $row;
   				}
			}
		} catch(PDOException $e) {
		$error = "*** Internal error: " . $e->getMessage() . "<p>" . $query;
			die($error);
		}
		return $res;
	}

	public function getCompanies() {
		$sql = "select name from Customer";
		try {
			$result = $this->executeQuery($sql);
			$res = array();
			if($result) {
   				foreach ($result as $row) {
   					$res[] = $row['name'];
   				}
			}
		} catch(PDOException $e) {
		$error = "*** Internal error: " . $e->getMessage() . "<p>" . $query;
			die($error);
		}
		return $res;
	}
	public function createUser($name, $type, $pNbr) {
		$sql = "Insert into users(pNbr, name , type)".
		"values ('$pNbr', '$name', '$type')";
		try {	
			$rowChange = $this->executeUpdate($sql);
			if ($rowChange == 1) {
				return true;
			} else {
				return false;
			}
		} catch (PDOException $e) {			
			$error = "*** Internal error: " . $e->getMessage() . "<p>" . $query;
			die($error);
		}
		return false;
	}

	public function createCustUse($pNbr, $name) {
		$sql = "Insert into custUser(pNbr, name)".
		"values ('$pNbr', '$name')";
		try {	
			$rowChange = $this->executeUpdate($sql);
			if ($rowChange == 1) {
				return true;
			} else {
				return false;
			}
		} catch (PDOException $e) {			
			$error = "*** Internal error: " . $e->getMessage() . "<p>" . $query;
			die($error);
		}
		return false;
	}
	

	public function getPalletsNotBlocked() {

		$sql = "select recipeName, count(*) as number from pallet where blocked = false and id not in (select palletId from orderPallet) group by recipeName";
		try {
			$result = $this->executeQuery($sql);
			$res = array();
			if($result) {
   				foreach ($result as $row) {
   					$res[] = $row;
   				}
			}
		} catch(PDOException $e) {
		$error = "*** Internal error: " . $e->getMessage() . "<p>" . $query;
			die($error);
		}
		return $res;
	}

	public function getOrders() {
		$sql = "select o.id, customerName, deliveryDate, createdDate, statusName from orders o".
		", orderStatusEvent e where o.id = e.orderId and e.statusName != 'Delivered';";
		try {
			$result = $this->executeQuery($sql);
			$res = array();
			if($result) {
   				foreach ($result as $row) {
   				$res[] = $row;
   				}
			}
		} catch(PDOException $e) {
		$error = "*** Internal error: " . $e->getMessage() . "<p>" . $query;
			die($error);
		}
		return $res;
	}

	public function getSpec($orderId) {

		$sql = "select customerName, recipeName, quantity from".
		 " orderSpec s, orders o where s.orderId = o.id and o.id ='$orderId';";
		try {
			$result = $this->executeQuery($sql);
			$res = array();
			if($result) {
   				foreach ($result as $row) {
   					$res[] = $row;
   				}
			}
		} catch(PDOException $e) {
		$error = "*** Internal error: " . $e->getMessage() . "<p>" . $query;
			die($error);
		}
		return $res;
	}


	public function loadTruck($orderId, $recipeName) {
		$left = $this->checkNbrPallets($orderId);
		if($left){
			$sql = "insert into orderPallet(palletId, orderId) (select id as palletId".
			", '$orderId' as orderId from pallet p where recipeName = '$recipeName'".
			" and p.id not in (select palletId from orderPallet) and blocked = false limit 1);";

			try {	
				$rowChange = $this->executeUpdate($sql);
				if ($rowChange > 0) {
					return true;
				} 
			} catch (PDOException $e) {			
				$error = "*** Internal error: " . $e->getMessage() . "<p>" . $query;
				die($error);
			}
		}
		return false;	
	}


	public function initiateLoad($orderId, $stat) {
		if($this->checkNbrPallets($orderId)) {
			$sql = "select recipeName, quantity from orderSpec where orderId = '$orderId'";
			try {
			$result = $this->executeQuery($sql);
			$res = array();
			if($result) {
   				foreach ($result as $row) {
   					for($i = 0; $i < $row['quantity']; $i++) {
   						if($this->loadTruck($orderId, $row['recipeName']) == false) {
   							return false;
   						}
   					}
   					
   				}
			} else {
				return false;
			}

		} catch(PDOException $e) {
		$error = "*** Internal error: " . $e->getMessage() . "<p>" . $query;
			die($error);
		}

	$this->changeStat($orderId, $stat);
		return true;
	}
		return false;
	}

	public function changeStat($orderId, $stat) {
		$sql = "update orderStatusEvent set statusName = '$stat' where orderId = '$orderId'";
		try {	
				$rowChange = $this->executeUpdate($sql);
				if ($rowChange > 0) {

					return true;
				} 
			} catch (PDOException $e) {			
				$error = "*** Internal error: " . $e->getMessage() . "<p>" . $query;
				die($error);
			}
		return false;	
	}


	public function checkNbrPallets($orderId) {
		$sql = "select p.recipeName, count(*) - quantity as remaining from".
		" pallet p, orderspec o where o.orderId = '$orderId'".
		" and p.recipeName = o.recipeName and p.id not in (select palletId from orderPallet)".
		" and p.blocked = false group by p.recipeName;";
			try {

			$result = $this->executeQuery($sql);
			$res = array();
			if(!$result) {
   				return false;
			}

		} catch(PDOException $e) {
		$error = "*** Internal error: " . $e->getMessage() . "<p>" . $query;
			die($error);
		}

		return $this->checkStatus($orderId);
	}

	public function checkStatus($orderId) {
		$sql = "select statusName from orderStatusEvent where orderId = '$orderId'";

		try {
			$result = $this->executeQuery($sql);
			$res = array();
			if($result) {
   				foreach ($result as $row) {
   					if($row['statusName'] == 'Delivered') {
   						return false;
   					}
   				}
			}
		} catch(PDOException $e) {
		$error = "*** Internal error: " . $e->getMessage() . "<p>" . $query;
			die($error);
		}

		return true;


	}

	public function getAllOrders() {
		$sql = "select o.id as id, customerName, deliveryDate, createdDate, statusName from orders o".
		", orderStatusEvent e where o.id = e.orderId;";
		try {
			$result = $this->executeQuery($sql);
			$res = array();
			if($result) {
   				foreach ($result as $row) {
   				$res[] = $row;
   				}
			}
		} catch(PDOException $e) {
		$error = "*** Internal error: " . $e->getMessage() . "<p>" . $query;
			die($error);
		}
		return $res;
	}

	public function removeOrder($orderId) {

		$sql = "Delete from orders where id = '$orderId' ";

		try {	
				$rowChange = $this->executeUpdate($sql);
				if ($rowChange == 1) {
					return true;
				} 
			} catch (PDOException $e) {			
				$error = "*** Internal error: " . $e->getMessage() . "<p>" . $query;
				die($error);
			}
		return false;	
	}

	public function palletAction($id, $action) {
		if($action == 'Block') {
			$actions = 1;
		} else {
			$actions = 0;
		}
		
		$sql = "update pallet set blocked = $actions where id = '$id'";
		try {	
				$rowChange = $this->executeUpdate($sql);
				if ($rowChange == 1) {
					return true;
				} 
			} catch (PDOException $e) {			
				$error = "*** Internal error: " . $e->getMessage() . "<p>" . $query;
				die($error);
			}
		return false;	


	}

	public function getPalletOrders() {
		$sql = "select p.id as palletId, p.createdDate, p.recipeName, o.id as orderId, o.customerName, o.deliveryDate".
		" from pallet p, orders o, orderPallet op where p.id in (select palletId from orderPallet) and".
		" op.orderId = o.id and op.palletId = p.id;";

		try {
			$result = $this->executeQuery($sql);
			$res = array();
			if($result) {
   				foreach ($result as $row) {
   				$res[] = $row;
   				}
			}
		} catch(PDOException $e) {
		$error = "*** Internal error: " . $e->getMessage() . "<p>" . $query;
			die($error);
		}
		return $res;



	}

	public function getPalletOrdersForC($customerName) {
		$sql = "select p.id as palletId, p.createdDate, p.recipeName, o.id as orderId, o.customerName, o.deliveryDate".
		" from pallet p, orders o, orderPallet op where p.id in (select palletId from orderPallet) and".
		" op.orderId = o.id and o.customerName = '$customerName' and op.palletId = p.id;";

		try {
			$result = $this->executeQuery($sql);
			$res = array();
			if($result) {
   				foreach ($result as $row) {
   				$res[] = $row;
   				}
			}
		} catch(PDOException $e) {
		$error = "*** Internal error: " . $e->getMessage() . "<p>" . $query;
			die($error);
		}
		return $res;



	}



}
?>

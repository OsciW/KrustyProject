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


	public function placeOrder($userId, $deliveryTime, $deliveryDate, $specs) {
		$sql  = "insert into orders (customerName, createdTime, createdDate, deliveryDate, deliveryTime)".
			"VALUES ('$userId', curtime(), curdate(), '$deliveryDate', '$deliveryTime')";

		try {	
			$rowChange = $this->executeUpdate($sql);
			if ($rowChange == 1) {
				$orderId = $this->conn->lastInsertId();

				$confirmation=array($orderId);

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
		$sql = "select * from pallet";
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
		$sql = "select * from pallet where recipeName = '$recipe'";
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
		$sql = "select * from pallet where barcodeId = '$barcodeId'";
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
		$sql = "select * from pallet where createdDate between '$start' and '$end'";
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
		$sql = "select * from pallet where createdDate between '$start' and '$end' and recipeName = '$recipe'";
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
	public function createUser($name, $pNbr, $type) {
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





}
?>

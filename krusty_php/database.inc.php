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

	public function createPallet($barcodeId, $time, $date, $status, $recipe) {
		if($this->subtractRawmaterial($recipe) == true) {
			if($status == "Blocked") {
			$status = true;
			} else {
				$status = false;
			}
			$sql = "insert into Pallet (barcodeId, createdTime, createdDate,". 
			 		"blocked, recipeName) values ('$barcodeId', '$time','$date'".
			 		", '$status', '$recipe')";
			try {	
				$rowChange = $this->executeUpdate($sql);
				if ($rowChange == 1) {
					$resNbr = $this->conn->lastInsertId();
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
   					$res[] = $row['q'];
   				}
			}
		} catch(PDOException $e) {
		$error = "*** Internal error: " . $e->getMessage() . "<p>" . $query;
			die($error);
		}
		for( $i = 0; $i < 4; $i++) {
			if ($res[$i] < 0) {
				return false;
			}
		}

		return true;
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
		$sql = "select distinct rawMaterialName from ingredient;";

		try {
			$res = array();
			$result = $this->executeQuery($sql);
			if($result) {
				foreach($result as $row) {
					$res[] = $row['rawMaterialName'];
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





	/*
	 * *** Add functions ***
	 */

	public function getMovieNames() {
		$sql = "select name FROM movies";
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



public function getPerformancesForMovie($movieName) {
		$sql = "select * FROM performances where performances.movieName = '$movieName'";
		try {
			$result = $this->executeQuery($sql);
			$res = array();
			if($result) {
   				foreach ($result as $row) {
   					$res[] = $row['per_date'];
   				}
			}
		}
		catch (PDOException $e) {
			$error = "*** Internal error: " . $e->getMessage() . "<p>" . $query;
			die($error);
		}
		return $res;
	}

public function theatreName($movieName, $date) {

	$sql ="select theatreName from performances where performances.movieName = ".
			"'$movieName' and performances.per_date = '$date' limit 1";
	try {
		$result = $this->executeQuery($sql);

		if($result) {
			foreach ($result as $res) {
				$res[] = $row['theatreName'];
				return $res;
			}
		}
	}
	catch (PDOException $e) {
		$error = "*** Internal error: " . $e->getMessage() . "<p>" . $query;
				die($error);
	}
}
public function freeSeats($movieName, $date) {

$sql =  "select " .
				"(Select seats " .
				"from theatres, performances,movies " .
				"where theatres.name = performances.theatreName and performances.movieName = movies.name and performances.per_date = '$date' and movies.name = '$movieName')" .
				"-" .
				"(Select count(resNbr) " .
				"from Movies,Performances,Reservation " .
				"where Reservation.performancesId = Performances.id and Performances.movieName = Movies.name and Performances.per_date = '$date' and Movies.name = '$movieName')";

		try {		
		$result = $this->executeQuery($sql);
		$freeSeats = 0;
		if($result) {
			foreach($result as $res) {

				$freeSeats = (int) $res[0];
			}
		}
	}
		catch (PDOException $e) {
			$error = "*** Internal error: " . $e->getMessage() . "<p>" . $query;
			die($error);
		}
		return $freeSeats;
	}

	public function perId($date, $name) {
		$sql = "Select id from performances where performances.movieName = '$name' and ".
				"performances.per_date = '$date' ";
		try {
			$result = $this->executeQuery($sql);
			$id = 0;
			if($result) {
				foreach($result as $res) {
					$id = (int) $res[0];

				}
			}
		}
		catch (PDOException $e) {
			$error = "*** Internal error: " . $e->getMessage() . "<p>" . $query;
			die($error);
		}
		return $id;
	}

	public function reser($user, $date, $name) {
		$this->conn->query("BEGIN");
		$nbrSeats = $this->freeSeats($name, $date);
		$pId = $this->perId($date, $name);
		if($nbrSeats > 0) {
			$sql = "insert into reservation (username, performancesId) ".
					"values ('$user', '$pId')";
			try {
					$rowChange = $this->executeUpdate($sql);
				if ($rowChange == 1) {
					$resNbr = $this->conn->lastInsertId();
					$this->conn->query("COMMIT");
				}
			}
			catch (PDOException $e) {
				$error = "*** Internal error: " . $e->getMessage() . "<p>" . $query;
				die($error);
			}	
		} else {
			$this->conn->query("ROLLBACK");
		}
		return $resNbr;
	}
}
?>

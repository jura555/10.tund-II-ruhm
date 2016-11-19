<?php 
class huvi {
	
	private $connection;
	
	function __construct($mysqli){
		
		$this->connection = $mysqli;
		
	}

	/*TEISED FUNKTSIOONID */
	function delete($id){

		$stmt = $this->connection->prepare("UPDATE huvi_data SET deleted=NOW() WHERE id=? AND deleted IS NULL");
		$stmt->bind_param("i",$id);
		
		// kas õnnestus salvestada
		if($stmt->execute()){
			// õnnestus
			echo "kustutamine õnnestus!";
		}
		
		$stmt->close();
		
		
	}
		
	function get($q, $sort, $direction) {
		
		//mis sort ja järjekord
		$allowedSortOptions = ["activity", "time", "day", "id"];
		//kas sort on lubatud valikute sees
		if(!in_array($sort, $allowedSortOptions)){
			$sort = "activity";
		}
		echo "Sorteerin: ".$sort." ";
		
		//turvaliselt luban ainult 2 valikut
		$orderBy= "ASC";
		if($direction == "descending"){
			$orderBy= "DESC";
		}
		echo "Järjekord: ".$orderBy." ";
		
		if($q == ""){
		
			echo "ei otsi";
			
			$stmt = $this->connection->prepare("
				SELECT id, activity, time, day
				FROM  huvi_data
				WHERE deleted IS NULL 
				ORDER BY $sort $orderBy
			");
			echo $this->connection->error;
		}else{
			
			echo "Otsib: ".$q;
			
			//teen otsisõna
			// lisan mõlemale poole %
			$searchword = "%".$q."%";
			
			$stmt = $this->connection->prepare("
				SELECT id, activity, time, day
				FROM huvi_data
				WHERE deleted IS NULL AND
				(activity LIKE ? OR time LIKE ? OR day LIKE ?)
				ORDER BY $sort $orderBy
			");
			$stmt->bind_param("ss", $searchword, $searchword);
		
		}
				

		
		echo $this->connection->error;
		
		$stmt->bind_result($id, $activity, $time, $day);
		$stmt->execute();
		
		
		//tekitan massiivi
		$result = array();
		
		// tee seda seni, kuni on rida andmeid
		// mis vastab select lausele
		while ($stmt->fetch()) {
			
			//tekitan objekti
			$huvi = new StdClass();
			
			$huvi->activity = $activity;
			$huvi->time = $time;
			$huvi->day = $day;
			$huvi->id = $id;
			//echo $plate."<br>";
			// iga kord massiivi lisan juurde nr märgi
			array_push($result, $huvi);
		}
		
		$stmt->close();
		
		
		return $result;
	}
	
	function getSingle($edit_id){

		$stmt = $this->connection->prepare("SELECT activity, time, day FROM huvi_data WHERE id=? AND deleted IS NULL");

		$stmt->bind_param("i", $edit_id);
		$stmt->bind_result($activity, $day, $time);
		$stmt->execute();
		
		//tekitan objekti
		$car = new Stdclass();
		
		//saime ühe rea andmeid
		if($stmt->fetch()){
			// saan siin alles kasutada bind_result muutujaid
			$huvi->activity = $activity;
			$huvi->day = $day;
			$huvi->time = $time;
			
		}else{
			// ei saanud rida andmeid kätte
			// sellist id'd ei ole olemas
			// see rida võib olla kustutatud
			header("Location: data.php");
			exit();
		}
		
		$stmt->close();
		
		
		return $huvi;
		
	}

	function save ($activity, $day, $time) {
		
		$stmt = $this->connection->prepare("INSERT INTO huvi_data (activity, day, time) VALUES (?, ?, ?)");
	
		echo $this->connection->error;
		
		$stmt->bind_param("sss", $activity, $day, $time);
		
		if($stmt->execute()) {
			echo "salvestamine õnnestus";
		} else {
		 	echo "ERROR ".$stmt->error;
		}
		
		$stmt->close();
		
		
	}
	
	function update($activity, $day, $time, $id){
    	
		$stmt = $this->connection->prepare("UPDATE huvi_data SET activity=?, day=?, time=? 
		WHERE id=? AND deleted IS NULL");
		$stmt->bind_param("sssi",$activity, $day, $time, $id);
		
		// kas õnnestus salvestada
		if($stmt->execute()){
			// õnnestus
			echo "salvestus õnnestus!";
		}
		
		$stmt->close();
		
		
	}
	
}
?>
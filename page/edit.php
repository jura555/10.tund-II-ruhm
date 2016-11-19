<?php
	//edit.php
	require("../functions.php");
	
	require("../class/Huvi.class.php");
	$huvi = new huvi($mysqli);
	
	//var_dump($_POST);
	
	//kas kasutaja uuendab andmeid
	if(isset($_POST["update"])){
		
		$huvi->update($Helper->cleanInput($_POST["id"]), $Helper->cleanInput($_POST["activity"]), $Helper->cleanInput($_POST["day"]), $Helper->cleanInput($_POST["time"]));
		
		header("Location: edit.php?id=".$_POST["id"]."&success=true");
        exit();	
		
	}
	
	//kustutan
	if(isset($_GET["delete"])){
		
		$huvi->delete($_GET["id"]);
		
		header("Location: data.php");
		exit();
	}
	
	
	
	// kui ei ole id'd aadressireal siis suunan
	if(!isset($_GET["id"])){
		header("Location: data.php");
		exit();
	}
	
	//saadan kaasa id
	$c = $huvi->getSingle($_GET["id"]);
	//var_dump($c);
	
	if(isset($_GET["success"])){
		echo "salvestamine õnnestus";
	}

	
?>
<?php require("../header.php"); ?>


<br><br>
<a href="data.php"> TAGASI </a>

<h2>Muuda kirjet</h2>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" >
	<input type="hidden" name="id" value="<?=$_GET["id"];?>" > 
  	<label for="number_activity" >Tegevus</label><br>
	<input id="number_activity" name="activity" type="text" value="<?php echo $c->activity;?>" ><br><br>
  	<label for="number_day" >Paev</label><br>
	<input id="number_day" name="day" type="text" value="<?=$c->day;?>"><br><br>
  	<label for="number_time" >Aeg</label><br>
	<input id="number_time" name="time" type="text" value="<?=$c->time;?>"><br><br>
	
	
  </form>
  
  
 <br>
 <br>
 <br>
 <a href="?id=<?=$_GET["id"];?>&delete=true">   DELETE</a>
 <?php require("../footer.php"); ?>
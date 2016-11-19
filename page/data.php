<?php 
	
	require("../functions.php");
	
	require("../class/Huvi.class.php");
	$huvi = new huvi($mysqli);
	
	//kui ei ole kasutaja id'd
	if (!isset($_SESSION["userId"])){
		
		//suunan sisselogimise lehele
		header("Location: login.php");
		exit();
	}
	
	
	//kui on ?logout aadressireal siis login välja
	if (isset($_GET["logout"])) {
		
		session_destroy();
		header("Location: login.php");
		exit();
	}
	
	$msg = "";
	if(isset($_SESSION["message"])){
		$msg = $_SESSION["message"];
		
		//kui ühe näitame siis kustuta ära, et pärast refreshi ei näitaks
		unset($_SESSION["message"]);
	}
	
	
	if ( isset($_POST["activity"]) && 
		isset($_POST["activity"]) && 
		isset($_POST["time"]) &&
		!empty($_POST["day"]) && 
		!empty($_POST["day"]) &&
		!empty($_POST["time"])
	  ) {
		  
		$huvi->save($Helper->cleanInput($_POST["activity"]), $Helper->cleanInput($_POST["day"]), $Helper->cleanInput($_POST["time"]));
		
	}
	
	// sorteerib
	if(isset($_GET["sort"]) && isset($_GET["direction"])){
		$sort = $_GET["sort"];
		$direction = $_GET["direction"];
	}else{
		// kui ei ole määratud siis vaikimis id ja ASC
		$sort = "id";
		$direction = "ascending";
	}
	
	//kas otsib
	if(isset($_GET["q"])){
		
		$q = $Helper->cleanInput($_GET["q"]);
		
		$huviData = $huvi->get($q, $sort, $direction);
	
	} else {
		$q = "";
		$huviData = $huvi->get($q, $sort, $direction);
	
	}
	
	
	
	
?>
<?php require("../header.php"); ?>
<div class="container">



<h1>Treningute soovid</h1>
<?=$msg;?>
<p>
	Tere tulemast <a href="user.php"><?=$_SESSION["userEmail"];?>!</a>
	<a href="?logout=1">Logi välja</a>
</p>


<h2>Pane oma soov</h2>
<form method="POST">
<p>Vali tegevus</p>
<div class="styled-select">
   <select name="activity">
      <option>Korvpall</option>
      <option>Jalgpall</option>
	  <option>Jousaal</option>
   </select>
</div>
<p>Vali aeg</p>
<div class="styled-select">
   <select name ="time">
      <option>19:00-21:00</option>
      <option>17:00-19:00</option>
	  <option>18:00-20:00</option>
   </select>
</div>
<p>Vali paev</p>
<div class="styled-select">
   <select name="day">
      <option>Esmaspaev</option>
      <option>Teisipaev</option>
	  <option>Kolmapaev</option>
	  <option>Neljapaev</option>
	  <option>Reede</option>
	  <option>Laupaev</option>
	  <option>Puhapaev</option>
   </select>
</div>
<br>
<input type="submit" value="Submit"><br>
</form>

<h2>Soovid</h2>

<form>
	<input type="search" name="q" value="<?=$q;?>">
	<input type="submit" value="Otsi">
</form>

<?php 
	
	$direction = "ascending";
	if (isset($_GET["direction"])){
		if ($_GET["direction"] == "ascending"){
			$direction = "descending";
		}
	}
	
	//TABLE FORM HERE!!
	$html = "<table class='table table-hover'>";
	
	$html .= "<tr>";
		$html .= "<th>
					<a href='?q=".$q."&sort=id&direction=".$direction."'>
						id
					</a>
				</th>";
		$html .= "<th>
					<a href='?q=".$q."&sort=activity&direction=".$direction."'>
						activity
					</a>
				</th>";
		$html .= "<th>
					<a href='?q=".$q."&sort=time&direction=".$direction."'>
						time
					</a>
				</th>";
		$html .= "<th>
					<a href='?q=".$q."&sort=day&direction=".$direction."'>
						day
					</a>
				</th>";
	$html .= "</tr>";
	
	//iga liikme kohta massiivis
	foreach($huviData as $c){
		// iga auto on $c
		//echo $c->plate."<br>";
		
		$html .= "<tr>";
			$html .= "<td>".$c->id."</td>";
			$html .= "<td>".$c->activity."</td>";
			$html .= "<td>".$c->day."</td>";
			$html .= "<td>".$c->time."</td>";
			
			$html .= "<td><a  href='edit.php?id=".$c->id."glyphicon glyphicon-pencil'>
			<span class='glyphicon glyphicon-pencil'></span> Delete
			</a></td>";
			
		$html .= "</tr>";
	}
	
	$html .= "</table>";
	
	echo $html;
	
	
	$listHtml = "<br><br>";
	
	
	
	
	
	
	

?>
</div>
<?php require("../footer.php"); ?>


<?php
if (isset($_SESSION['user_id']))
{

	switch ($show) {
		case 0:
			$pagetitle = "Online players";
			break;
		case 1:
			$query = "select profile.name, survivor.* from profile, survivor as survivor where profile.unique_id = survivor.unique_id and survivor.is_dead = '0'"; 
			$pagetitle = "Alive players";		
			break;
		case 2:
			$query = "select profile.name, survivor.* from profile, survivor as survivor where profile.unique_id = survivor.unique_id and survivor.is_dead = '1'"; 
			$pagetitle = "Dead players";	
			break;
		case 3:
			$query = "select profile.name, survivor.* from profile, survivor as survivor where profile.unique_id = survivor.unique_id"; 
			$pagetitle = "All players";	
			break;
		case 4:
			$query = "SELECT * FROM objects WHERE damage < 0.95";
			$pagetitle = "Ingame vehicles";	
			break;
		case 5:
			$query = "SELECT * FROM spawns";
			$pagetitle = "Vehicle spawn locations";	
			break;
		case 6:
			$query = "SELECT * from objects where damage < 0.95";
			$pagetitle = "Online Players and Vehicles";
			break;
		default:
			$pagetitle = "Online players";
		};

?>
<div id="page-heading">
<?
	echo "<title>".$pagetitle." - ".$sitename."</title>";
	echo "<h1>".$pagetitle."</h1>";
?>
</div>
<?
	include ('/maps/'.$show.'.php');
}
else
{
	header('Location: index.php');
}
?> 

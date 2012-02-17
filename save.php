<?php

require('mysql.php');

$classes = explode("-",isset($_GET['classes']) ? $_GET['classes'] : $_POST['classes']);

$date = date("Y-m-d");

$query = "INSERT INTO `saves` (`date`) VALUES ('$date')";
mysql_query($query);
$recent = mysql_fetch_assoc(mysql_query("SELECT `id` FROM `saves` ORDER BY `id` DESC LIMIT 1"));
$id = $recent['id'];

$query = "INSERT INTO `saveCRN` (`save_id`,`crn`) VALUES ";
foreach($classes as $class)
{
	if(is_numeric($class))
		$queries[] = "('$id','$class')";
}
$query .= implode($queries, ", ") . ";";

mysql_query($query);



?><input type="text" style="width: 200px;" value="http://pittscheduler.com/<?=$id ?>" /><br /><br />
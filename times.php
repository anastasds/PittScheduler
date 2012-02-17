{"lectures": {
<?php

require('mysql.php');

$id = intval((isset($_GET['id']) ? $_GET['id'] : $_POST['id']));

$query = "SELECT * FROM `times` WHERE `section_id`='$id'";
$results = @mysql_query($query);
$result = mysql_fetch_assoc($results);

$section = mysql_fetch_assoc(mysql_query("SELECT `class_id`,`crn` FROM `sections` WHERE `id`='$id' LIMIT 1"));
$cid = $section['class_id'];

$class = mysql_fetch_assoc(mysql_query("SELECT `subject`,`classnum` FROM `classes` WHERE `id`='$cid' LIMIT 1"));

?>

	"id" : "<?=$id ?>" ,
	"subj" : "<?=$class['subject'] ?>" ,
	"classnum" : "<?=$class['classnum'] ?>" ,
	"crn" : "<?=$section['crn'] ?>" ,
	"lecture" : [
		{ "cell" : "<?=$result['cell'] ?>" , "offset" : <?=$result['offset'] ?>, "classlength" : <?=$result['length'] ?> }<?php

while($result = mysql_fetch_assoc($results))
{
?>,
		{ "cell" : "<?=$result['cell'] ?>" , "offset" : <?=$result['offset'] ?>, "classlength" : <?=$result['length'] ?> }<?php
}
?>

	]
}}
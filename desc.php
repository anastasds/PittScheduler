<?php

$id = intval(isset($_GET['id']) ? $_GET['id'] : $_POST['id']);

require('mysql.php');

$results = mysql_query("SELECT `desc`,`class_id` FROM `sections` WHERE `id`='$id' LIMIT 1");
$result = mysql_fetch_assoc($results);

$cid = $result['class_id'];
$class = @mysql_fetch_assoc(mysql_query("SELECT `subject`,`classnum`,`classname` FROM `classes` WHERE `id`='$cid' LIMIT 1"));

echo $class['subject'] . " " . $class['classnum'] . " - " . stripslashes($class['classname']) . nl2br(stripslashes($result['desc']));

?>
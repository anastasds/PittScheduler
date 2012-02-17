<select name="section" id="section" style="width: 200px;" <?=(empty($_POST) ? 'onChange="showClass(this.value)"' : 'onChange="showClass(this.value,-1,-1)"') ?>>
<option value="">Choose section</option>
<?php

require('mysql.php');

$id = addslashes((isset($_GET['id']) ? $_GET['id'] : $_POST['id']));
$term = addslashes((isset($_GET['term']) ? $_GET['term'] : $_POST['term']));
$query = "SELECT * FROM `sections` WHERE `class_id`='$id' AND `term`='$term' ORDER BY `crn` ASC";
$results = mysql_query($query);
while($result = mysql_fetch_assoc($results))
{
    echo '<option value="' . $result['id'] . '">' . stripslashes($result['times']) . "</option>\n";
}
?>
</select><br /><br />
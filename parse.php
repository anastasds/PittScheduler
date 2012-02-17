<?php

error_reporting(1);
$debug = false;
function mdebug($msg,$newline=true) { global $debug; if($debug) echo $msg."<br />"; }
//********************
// 1 for drop, 0 for don't
$drop = 0;
//********************

require('mysql.php');

if($drop == 1) {
    $query = "TRUNCATE `classes`";
    mysql_query($query);
    $query = "TRUNCATE `sections`";
    mysql_query($query);
    $query = "TRUNCATE `saves`";
    mysql_query($query);
    $query = "TRUNCATE `saveCRN`";
    mysql_query($query);
    $query = "TRUNCATE `times`";
    mysql_query($query);
}

$subj = strtoupper((isset($_GET['subj']) ? $_GET['subj'] : $_POST['subj']));
$subj2 = addslashes($subj);

$term = addslashes((isset($_GET['term']) ? $_GET['term'] : $_POST['term']));
if(strlen($term) == 0)
{

    $year = intval(date("Y"));
    $month = intval(date("n"));
    if($month <= 12 && $month >= 9)
        ++$year;

    $term = substr($year,0,1) . substr($year,2,1) . substr($year,3,1);
    if($month >= 1 && $month <= 4)
        $term .= "4";
    if($month > 4 && $month < 9)
        $term .= "7";
    if($month >= 9 && $month <= 12)
        $term .= "1";

}

$query = "SELECT `id` FROM `sections` WHERE `subject`='$subj2' AND `term`='$term' LIMIT 1";
$result = @mysql_fetch_assoc(mysql_query($query));
if(intval($result['id']) == 0)
{

$url = 'http://www.courses.as.pitt.edu/show-subj.asp?TERM=' . $term . '&SUBJ=' . $subj;
$file = 'classes.txt';

//file_put_contents($file,file_get_contents($url));
//$fh = fopen($file,"wt");
//fwrite($fh,file_get_contents($url));
//fclose($fh);

//$fh = fopen($file,"r");
//$fdata = fread($fh,filesize($file));
//@fclose($fh);

$fdata = file_get_contents($url);

$pos = strpos($fdata,'</h3>');
$fdata =  substr($fdata, $pos+5);

$classes = explode("<table",$fdata);

foreach($classes as $class)
{
mdebug("string to process:<pre>$class</pre>");
preg_match('/<td><strong>(\d+)<\/strong><\/td>/',$class,$matches);
$classnum = $matches[1];
$classnum2 = addslashes($classnum);
mdebug("class number: $classnum");

unset($matches);

preg_match('/<td colspan=[^>]*><strong>(.*)<\/strong><\/td>/',$class,$matches);
$classname = $matches[1];
$classname2 = addslashes($classname);
mdebug("class name: $classname");

preg_match('/<td align=[^>]*><strong>(.*)<\/strong><\/td>/',$class,$matches);
$numcredits = $matches[1];
$numcredits2 = addslashes($numcredits);
mdebug("num credits: $numcredits");

preg_match('/<\/table>(.*)<\/p>.*Pre/',str_replace("\n",'',$class),$matches);
$desc = str_replace(array("\n","<p>","</p>"),"",$matches[1]);
$desc2 = addslashes($desc);
mdebug("description: $desc");

$query = "SELECT `id` FROM `classes` WHERE `subject`='$subj2' AND `classnum`='$classnum2' LIMIT 1";
$result = @mysql_fetch_assoc(@mysql_query($query));
if(intval($result['id']) == 0)
{
    $query = "INSERT INTO `classes` (`subject`,`classnum`,`classname`,`credits`) VALUES ('$subj2','$classnum2','$classname2','$numcredits2')";
    mysql_query($query);
    $query = "SELECT `id` FROM `classes` ORDER BY `id` DESC LIMIT 1";
    $result = @mysql_fetch_assoc(@mysql_query($query));
    $id = $result['id'];
} else 
    $id = $result['id'];

$sections = explode("<tr>",$class);
array_shift($sections);array_shift($sections);
$offerings = array();

foreach($sections as $section)
{

preg_match('/<td width=[^>]*>(\d*)<\/td>[^<]*<td width=[^>]*>(.{0,4})<\/td>[^<]*<td width=[^>]*>([^<]*)<\/td>[^<]*<td width=[^>]*>/',str_replace("\n",'',$section),$matches);


$crn = $matches[1];
$atse3 = $matches[2];
$times = $matches[3];
mdebug("crn: $crn");
mdebug("atse3: $atse3");
mdebug("times: $times");
$times2 = addslashes($times);
if(strlen($crn) != 0 && strlen($times) != 0) {
    $offerings[] = "CRN $crn ($atse3), $times";

    $query = "SELECT * FROM `sections` WHERE `crn`='$crn' AND `term`='$term' LIMIT 1";
    $result = @mysql_fetch_assoc(@mysql_query($query));
    if(intval($result['id']) == 0)
    {
        $query = "INSERT INTO `sections` (`subject`,`classnum`,`class_id`,`crn`,`atse3`,`times`,`desc`,`term`) VALUES ('$subj2','$classnum2','$id','$crn','$atse3','$times2','$desc2','$term')";
        mysql_query($query);
        $query = "SELECT `id` FROM `sections` ORDER BY `id` DESC LIMIT 1";
        $result = @mysql_fetch_assoc(@mysql_query($query));
        $sid = $result['id'];

    $timesplits = explode("<br />",$times);
    foreach($timesplits as $timesplit)
    {
        $atoms = explode("&nbsp;",$timesplit);
        $building = $atoms[3];
        $room = $atoms[2];
        $days = $atoms[0];
        $time = $atoms[1];

        $trans = array("Mo" => "m","Tu" => "t","We" => "w","Th" => "h", "Fr" => "f");

        $numdays = strlen($days) / 2;
        for($i = 0; $i < $numdays; $i++) {
            $day = $trans[substr($days, $i*2, 2)];
            $cell = "$day-" . intval(substr($time,0,2));
            $startend = explode("-",$time);
            if(strpos($startend[0],"PM") !== false && intval(substr($startend[0],0,2)) != 12)
                $startend[0] = (intval(substr($startend[0],0,2)) + 12) . ":" . substr($startend[0],3,2) . ":00";
            if(strpos($startend[1],"PM") !== false && intval(substr($startend[1],0,2)) != 12)
                $startend[1] = (intval(substr($startend[1],0,2)) + 12) . ":" . substr($startend[1],3,2) . ":00";
            $offset = intval(substr($startend[0],3,2)) / 60;
            $start = date("H:i:s",strtotime($startend[0]));
            $end = date("H:i:s",strtotime($startend[1]));

            $length = (((intval(substr($end,0,2)) - intval(substr($start,0,2))) * 60) + (intval(substr($end,3,2)) - intval(substr($start,3,2)))) / 60;
            $query = "INSERT INTO `times` (`section_id`,`day`,`start`,`end`,`building`,`room`,`cell`,`offset`,`length`) VALUES ('$sid','$day','$start','$end','$building','$room','$cell','$offset','$length')";
            mysql_query($query);
        }

    }
}
}

}

//if(strlen($classnum) > 0)
  //  echo "$subj $classnum - $classname ($numcredits)<br />" . implode("<br />",$offerings) . "<br />$desc<br /><br />";
//print_r($matches);

}

} // end if(there are no entries for this subject and term)

$query = "SELECT * FROM `classes` WHERE `subject`='$subj2' ORDER BY `classnum` ASC";
$results = mysql_query($query);
echo '<select name="class" id="class" style="width: 200px;" onChange="getSections()">' . "\n";
echo '<option value="">Choose class</option>' . "\n";
while($result = mysql_fetch_assoc($results))
{

if(strlen($result['classnum']) != 0 && strlen($result['classname']) != 0)
{
    $classid = $result['id'];
    $query = "SELECT `id` FROM `sections` WHERE `class_id`='$classid' AND `term`='$term' LIMIT 1";
    if(intval(@mysql_num_rows(mysql_query($query))) != 0)
        echo '<option value="' . $result['id'] . '">' . $result['classnum'] . ": " . stripslashes($result['classname']) . "</option>\n";
}
}

?>
</select><br /><br />

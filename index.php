<!DOCTYPE html>
<html>
<head>
<link rel="icon" type="image/vnd.microsoft.icon" href="favicon.ico" />
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<title>Pitt Scheduler</title>
<script type="text/javascript" src="jquery-1.3.2.js"></script>
<script type="text/javascript" src="scripts.js"></script>
<script type="text/javascript" src="analytics.js"></script>
<script type="text/javascript">
$(document).ready(function()
{
	$('#subject').change(getClasses);
	$('#term').change(getClasses);
});
</script>
<style type="text/css">
<!--

body {
	background-color: white;
	color: #000000;
	font-family: verdana;
}

* {
	margin: 0;
	padding: 0;
}

#loading {
	margin: 0;
	padding: 0;
	position: absolute;
	width: 250px;
	height: 500px;
	background-color: white;
}

#main {
	text-align: right;
	width: 960px;
	margin: 0 auto 0 auto;
	padding: 0px;
}

#sidebar {
	font-size: 8pt;
	float: left;
	width: 250px;
	padding-right: 5px;
	text-align: left;
}

#buttons {
	float: right;
	margin-top: 5px;
	font-family: verdana;
	font-size: 7pt;
	color: #333333;
	line-height: 10.5pt;
}

#buttons img {
	border: 0;
}

#schedule {
	float: right;
	width: 693px;
	margin: 0;
	padding: 0;
}

#xmt {
	margin: 0;
	padding: 0;
	width: 323px;
	float: left;
	border-left: 2px solid black;
}

	#xm {
		margin: 0;
		padding: 0;
		width: 199px;
		float: left;
	}

		#x {
			margin: 0;
			padding: 0;
			width: 75px;
			float: left;
		}

		#m {
			margin: 0;
			padding: 0;
			width: 120px;
			float: right;
		}

	#t {
		width: 120px;
		margin: 0;
		padding: 0;
		float: right;
	}

#whf {
	margin: 0;
	padding: 0;
	width: 368px;
	float: right;
}

	#wh {
		margin: 0;
		padding: 0;
		width: 245px;
		float: left;
	}

		#w {
			width: 120px;
			margin: 0;
			padding: 0;
			float: left;
		}

		#h {
			width: 120px;
			margin: 0;
			padding: 0;
			float: right;
		}

	#f {
		margin: 0;
		padding: 0;
		width: 120px;
		float: right;
	}

.times {
	font-size: 8pt;
	width: 73px;
	margin: 0px;
	padding: 0px;
	text-align: center;
	border-right: 2px solid black;
}

.day {
	font-size: 8pt;
	width: 118px;
	margin: 0px;
	padding: 0px;
	text-align: center;
	border-right: 2px solid black;
}


.top {
	width: auto;
	padding: 0px;
	margin-bottom: 5px;
	border-bottom: 2px solid black;
	font-weight: bold;
}

.cell {
	max-height: 40px;
	width: 118px;
	margin: 0;
	padding: 0px;
	height: 40px;
	text-align: center;
	border-bottom: 1px solid #c3c3c3;
}

.timecell {
	width: 73px;
	margin: 0;
	padding: 0px;
	height: 40px;
	text-align: center;
	border-bottom: 1px solid #c3c3c3;
	background-color: white;	
}

.taken {
	background-color: #c3c3c3;
}

#desc {
	font-family: verdana;
	font-size: 8pt;
	width: auto;
	border: 1px #c3c3c3 dashed;
	padding: 5px;
}

a {
	color: blue;
	text-decoration: underline;
}

a:link {
	color: blue;
	text-decoration: underline;
}

a:visited {
	color: blue;
	text-decoration: underline;
}

a:hover {
	color: blue;
	text-decoration: none;
}

a:active {
	color: blue;
	text-decoration: underline;
}

-->
</style>
</head>

<body<?php

if(isset($_GET['id']) && is_numeric($_GET['id']))
{
?> onload="loadSaved(new Array('<?php
    require('mysql.php');
    $id = $_GET['id'];
    $query = "SELECT `crn` FROM `saveCRN` WHERE `save_id`='$id'";
    $crns = mysql_query($query);
    while($crn = @mysql_fetch_assoc($crns))
        $loads[] = $crn['crn'];

   echo implode("','",$loads);
?>'))"<?php
}
?>>

<div style="width: 100%; height: 100%; background-color: white;">
	<div id="main">
		<img src="images/pittscheduler.png" alt="pittscheduler" style="margin-bottom: 5px;"/><br />

		<div id="sidebar">
			<div id="loading" style="visibility: hidden;"><img src="images/loading.png" alt="" /></div>
			<div style="text-align: left; margin-bottom: 10px;">
				Use the dropdown menus below to browse classes for the upcoming semester and to choose a schedule that you like.
				<br /><br />
			</div>

			<select name="subject" id="subject" style="width: 125px;">
				<option value="">---College of Arts &amp; Sciences </option>
				<option value="AFRCNA">Africana Studies (AFRCNA)</option>
				<option value="ANTH">Anthropology (ANTH)</option>
				<option value="ARCH">Architectural Studies (subjs vary)</option>
				<option value="ARTSC">Arts and Sciences (ARTSC)</option>
				<option value="ASTRON">Astronomy (ASTRON)</option>
				<option value="BIOETH">Bioethics (BIOETH)</option>
				<option value="BIOSC">Biological Sciences (BIOSC)</option>
				<option value="CHEM">Chemistry (CHEM)</option>
				<option value="CHLIT">Children's Lit Program (subjs vary)</option>
				<option value="CHIN">Chinese (CHIN)</option>
				<option value="CLASS">Classics (CLASS)</option>
				<option value="COMMRC">Communication (COMMRC)</option>
				<option value="CS">Computer Science (CS)</option>
				<option value="CLST">Cultural Studies (subjs vary)</option>
				<option value="EAS">East Asian Studies (EAS)</option>
				<option value="ECON">Economics (ECON)</option>
				<option value="ENGCMP">English Composition (ENGCMP)</option>
				<option value="ENGFLM">English Film (ENGFLM)</option>
				<option value="ENGLIT">English Literature (ENGLIT)</option>
				<option value="ENGWRT">English Writing (ENGWRT)</option>
				<option value="ENV">Environmental Studies (subjs vary)</option>
				<option value="FILMST">Film Studies (subjs vary)</option>
				<option value="FP">Freshman Programs (FP)</option>
				<option value="FR">French (FR)</option>
				<option value="GEOL">Geology (GEOL)</option>
				<option value="GER">German (GER)</option>
				<option value="GREEK">Greek (GREEK)</option>
				<option value="HIST">History (HIST)</option>
				<option value="HPS">History and Philosophy of Science (HPS)</option>
				<option value="HAA">History of Art and Architecture (HAA)</option>
				<option value="ISSP">Intelligent Systems (ISSP)</option>
				<option value="ITAL">Italian (ITAL)</option>
				<option value="JPNSE">Japanese (JPNSE)</option>
				<option value="JS">Jewish Studies (JS)</option>
				<option value="KOREAN">Korean (KOREAN)</option>
				<option value="LATIN">Latin (LATIN)</option>
				<option value="LING">Linguistics (LING)</option>
				<option value="MATH">Mathematics (MATH)</option>
				<option value="MRST">Medieval and Renaissance St. (subjs vary)</option>
				<option value="MUSIC">Music (MUSIC)</option>
				<option value="NROSCI">Neuroscience (NROSCI)</option>
				<option value="PHIL">Philosophy (PHIL)</option>
				<option value="PEDC">Physical Education (PEDC)</option>
				<option value="PHYS">Physics (PHYS)</option>
				<option value="POLISH">Polish (POLISH)</option>
				<option value="PS">Political Science (PS)</option>
				<option value="PORT">Portuguese (PORT)</option>
				<option value="PSY">Psychology (PSY) </option>
				<option value="REL">Religion, Cooperative Program (REL)</option>
				<option value="RELGST">Religious Studies (RELGST)</option>
				<option value="RUSS">Russian (RUSS)</option>
				<option value="SERCRO">Serbian-Croatian (SERCRO)</option>
				<option value="SLAV">Slavic (SLAV)</option>
				<option value="SLOVAK">Slovak (SLOVAK)</option>
				<option value="SOC">Sociology (SOC)</option>
				<option value="SPAN">Spanish (SPAN)</option>
				<option value="STAT">Statistics (STAT)</option>
				<option value="SA">Studio Arts (SA)</option>
				<option value="THEA">Theatre Arts (THEA)</option>
				<option value="UKRAIN">Ukrainian (UKRAIN)</option>
				<option value="URBNST">Urban Studies (subjs vary)</option>
				<option value="WOMNST">Women's Studies (subjs vary)</option>
				<option value="">----- College of Business Administration</option>
				<option value="BUSACC">Accounting (BUSACC)</option>
				<option value="BUSECN">Economics (BUSECN)</option>
				<option value="BUSENV">Environment (BUSENV)</option>
				<option value="BUSFIN">Finance (BUSFIN)</option>
				<option value="BUSHRM">Human Resources Mgt. (BUSHRM)</option>
				<option value="BUSMIS">Management Info. Systems (BUSMIS)</option>
				<option value="BUSMKT">Marketing (BUSMKT)</option>
				<option value="BUSORG">Organizational Behavior (BUSORG)</option>
				<option value="BUSQOM">Quant. Methods/Operations Mgt.(BUSQOM)</option>
				<option value="BUSERV">Service (BUSERV)</option>
				<option value="BUSSPP">Strategic Planning and Policy (BUSSPP)</option>
			</select>

			<select name="term" id="term">
				<option value="2124">Spring 2012</option>
				<option value="">---</option>
				<option value="2127">Summer 2012</option>
				<option value="2124">Spring 2012</option>
				<option value="2121">Fall 2011</option>
				<option value="2117">Summer 2011</option>
				<option value="2114">Spring 2011</option>
				<option value="2111">Fall 2010</option>
				<option value="2107">Summer 2010</option>
				<option value="2104">Spring 2010</option>
				<option value="2101">Fall 2009</option>
			</select>
			<br /><br />

			<div id="divclasses" style="width: 200px;"></div>

			<div id="divsections" style="width: 200px;"></div>

			<div id="divsave"></div>

			<div id="desc"><i>course description will appear here</i></div>

		</div>

		<div id="schedule">
			<div id="xmt">
				<div id="xm">
					<div id="x" class="times">
						<div class="top">Times</div>
						<div id="x-8" class="timecell">8am</div>
						<div id="x-9" class="timecell">9am</div>
						<div id="x-10" class="timecell">10am</div>
						<div id="x-11" class="timecell">11am</div>
						<div id="x-12" class="timecell">12pm</div>
						<div id="x-1" class="timecell">1pm</div>
						<div id="x-2" class="timecell">2pm</div>
						<div id="x-3" class="timecell">3pm</div>
						<div id="x-4" class="timecell">4pm</div>
						<div id="x-5" class="timecell">5pm</div>
						<div id="x-6" class="timecell">6pm</div>
						<div id="x-7" class="timecell">7pm</div>
					</div>
					<div id="m" class="day">
						<div class="top">Mon</div>
						<div id="m-8" class="cell"></div>
						<div id="m-9" class="cell"></div>
						<div id="m-10" class="cell"></div>
						<div id="m-11" class="cell"></div>
						<div id="m-12" class="cell"></div>
						<div id="m-1" class="cell"></div>
						<div id="m-2" class="cell"></div>
						<div id="m-3" class="cell"></div>
						<div id="m-4" class="cell"></div>
						<div id="m-5" class="cell"></div>
						<div id="m-6" class="cell"></div>
						<div id="m-7" class="cell"></div>
					</div>
				</div>
				<div id="t" class="day">
					<div class="top">Tues</div>
					<div id="t-8" class="cell"></div>
					<div id="t-9" class="cell"></div>
					<div id="t-10" class="cell"></div>
					<div id="t-11" class="cell"></div>
					<div id="t-12" class="cell"></div>
					<div id="t-1" class="cell"></div>
					<div id="t-2" class="cell"></div>
					<div id="t-3" class="cell"></div>
					<div id="t-4" class="cell"></div>
					<div id="t-5" class="cell"></div>
					<div id="t-6" class="cell"></div>
					<div id="t-7" class="cell"></div>
				</div>
			</div>
			<div id="whf">
				<div id="wh">
					<div id="w" class="day">
						<div class="top">Wed</div>
						<div id="w-8" class="cell"></div>
						<div id="w-9" class="cell"></div>
						<div id="w-10" class="cell"></div>
						<div id="w-11" class="cell"></div>
						<div id="w-12" class="cell"></div>
						<div id="w-1" class="cell"></div>
						<div id="w-2" class="cell"></div>
						<div id="w-3" class="cell"></div>
						<div id="w-4" class="cell"></div>
						<div id="w-5" class="cell"></div>
						<div id="w-6" class="cell"></div>
						<div id="w-7" class="cell"></div>
					</div>
					<div id="h" class="day">
						<div class="top">Thur</div>
						<div id="h-8" class="cell"></div>
						<div id="h-9" class="cell"></div>
						<div id="h-10" class="cell"></div>
						<div id="h-11" class="cell"></div>
						<div id="h-12" class="cell"></div>
						<div id="h-1" class="cell"></div>
						<div id="h-2" class="cell"></div>
						<div id="h-3" class="cell"></div>
						<div id="h-4" class="cell"></div>
						<div id="h-5" class="cell"></div>
						<div id="h-6" class="cell"></div>
						<div id="h-7" class="cell"></div>
					</div>
				</div>
				<div id="f" class="day">
					<div class="top">Fri</div>
					<div id="f-8" class="cell"></div>
					<div id="f-9" class="cell"></div>
					<div id="f-10" class="cell"></div>
					<div id="f-11" class="cell"></div>
					<div id="f-12" class="cell"></div>
					<div id="f-1" class="cell"></div>
					<div id="f-2" class="cell"></div>
					<div id="f-3" class="cell"></div>
					<div id="f-4" class="cell"></div>
					<div id="f-5" class="cell"></div>
					<div id="f-6" class="cell"></div>
					<div id="f-7" class="cell"></div>
				</div>
			</div>
			<div id="buttons">
				<a href="http://validator.w3.org/check?uri=referer" target="_blank">
					<img src="images/html5.png" alt="W3C-validated HTML" /> 
				</a>
				<a href="http://jigsaw.w3.org/css-validator/check/referer" target="_blank">
					<img src="images/css.png" alt="W3C-validated CSS" /> 
				</a>
				<a href="http://www.facebook.com/pages/PittScheduler/126497940049" target="_blank">
					<img src="images/facebook.png" alt="Find PittScheduler on Facebook" /> 
				</a>
				<a href="http://twitter.com/PittScheduler" target="_blank">
					<img src="images/twitter.png" alt="Follow PittScheduler on Twitter" /> 
				</a>
				<a href="mailto:contact@anastas.eu">
					<img src="images/contact.png" alt="Contact PittScheduler" /> 
				</a><br /><br />
				<a href="http://www.pittscheduler.com/" target="_blank">PittScheduler</a> is not in any way affiliated with the <a href="http://www.pitt.edu/" target="_blank">University of Pittsburgh</a>. PittScheduler is a tool created by <a href="http://www.anastas.eu/" target="_blank">Anastas Stoyanovsky</a> for use by University of Pittsburgh students and PittScheduler.com is a website registered by Anastas Stoyanovsky. The author does not guarantee the accuracy of the information contained herein and all information may be double-checked against PeopleSoft in <a href="http://my.pitt.edu" target="_blank">my.pitt.edu</a>.
			</div>
		</div>
</div>
</div>
</body>
</html>

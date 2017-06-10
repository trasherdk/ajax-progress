<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0">
	<title>AJAX Progress</title>
	
	<link rel="stylesheet" href="css/styles.css">
	<script type='text/javascript' src='js/progress.js'></script>
</head>
<body>
<?
	$response = array();
	
	require_once('config.php');
/**
 * config.php
 *
 * 	$database = 'progress';
 *	$dbuser = 'db-login';
 *	$dbpass = 'db-password';
 *
 *  $description = '';
 */
	require_once('jsondb.php');
	if (!$db = dbconnect($database,$dbuser,$dbpass))
	{
		echo json_encode($response);
		exit();
	}
?>
<div class="header">
	<h1>Header</h1>
</div>

<div class="navleft">
	<h1>Navigation</h1>
</div>

<div class='content'>
	<div class='header'>
		<h1>Job Control</h1>
		<h2>Change Section: <?= $description ?></h2>
	</div>
</div>

<div class='content'>
	<section id='movesection'>
		<div>
			<label for="fromsection" class='inputlabel'>Move from Section (listbox)</label>
			<?
				$sql = "select distinct section from main where section > 0 order by section";
				listbox($sql,'fromsection','18','Select Section','','inputfield');
			?>
		</div>
		
		<div>
			<label for="rangebox">Move from Section (rangebox)</label>
			<?
				$sql = "select min(section) min, max(section) max from progress.main where section > 0";
				minmaxlist($sql, 'rangebox', '18', 'Select Section', '', 'inputfield')
			?>
		</div>
		
		<div>
			<label for="tosection" class='inputlabel' style='min-width:200px;'>Move to Section</label>
			<input id='tosection' class='inputfield' type='text' maxlength='2' size='3' value='19'>
		</div>
		<div>
			<label for="limit" class='inputlabel' style='min-width:200px;'>Limit number of products</label>
			<input id='limit' class='inputfield' type='text' maxlength='3' size='4' value='10'>
		</div>
		<div>
			<button onclick='changesection();return false'>Submit</button>
		</div>
		<progress id='movesectionprogress' max='100' value='0'></progress>
		<div id='movesectionexception' style='border:1px solid red'></div>
		<div id='movesectionstatus'></div>
	</section>
</div>

</body>
</html>

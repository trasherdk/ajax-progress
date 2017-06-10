<?
/**
	function dbconnect($dbase, $dblogin = "root", $dbpasswd = "", $server="localhost")
	function minmaxlist($sql, $name, $value, $default="", $onchange="", $class="")
	function listbox($sql, $name, $value, $default="", $onchange="", $class="", $id="")

**/
	function dbconnect($dbase, $dblogin = "root", $dbpasswd = "", $server="localhost")
	{
		global $mysql;
		global $db;
		global $response;
		
		if ($dbpasswd == "")
		{
			if (!$mysql = mysql_connect($server,$dblogin))
			{
				$response = array(
						'success' => false,
						'response' => "Connect to $server failed",
						'error' => mysql_error()
					);
				return false;
			}
		}
		else
		{
			if (!$mysql = mysql_connect($server,$dblogin,$dbpasswd))
			{
				$response = array(
						'success' => false,
						'response' => "Connect to $server failed",
						'error' => mysql_error()
					);
				return false;
			}
		}
		
		if (!$db = mysql_select_db($dbase,$mysql))
		{
			$response = array(
					'success' => false,
					'response' => "Select DB $dbase failed",
					'error' => mysql_error()
				);
			return false;
		}
		return $db;
	}

	function minmaxlist($sql, $name, $value, $default="", $onchange="", $class="")
	{
		global $mysql;
		global $response;
		
		if (! $res = mysql_query($sql,$mysql))
		{
			$response = array(
					'success' => false,
					'response' => "Error in minmaxlist '$name'<br>$sql",
					'error' => mysql_error()
				);
			return false;
		}
		
		$rowcnt = mysql_num_rows($res);
		
		if ($rowcnt <> 1)
		{
			$response = array(
					'success' => false,
					'response' => "Error: query returned $rowcnt results.<br/>Query Should return one row with Min and Max values.<br/>$sql",
					'error' => mysql_error()
				);
			mysql_free_result($res);
			return false;
		}
		
		$row = mysql_fetch_object($res);
		mysql_free_result($res);
		
		echo "<select name='$name' id='$name'";
		if ($class) echo " class='$class'";
		if ($onchange) echo " onChange='$onchange'";
		echo ">\n";
		
		if (!$value)
			echo "<option selected value=''>$default</option>\n";
			
		for ($i = $row->min; $i <= $row->max; $i++)
		{
			echo "<option " . ($value == $i ? " selected " : "") . " value='$i'>$i</option>\n";
		}
		echo "</select>\n";
		
		return true;
	}
	
	function listbox($sql, $name, $value, $default="", $onchange="", $class="", $id="")
	{
		global $mysql;
		global $response;
		global $debugsql;
		
		if ($debugsql) echo "<div class='box'>$sql</div>\n";
		
		if (! $qry = mysql_query($sql,$mysql))
		{
			$response = array(
					'success' => false,
					'response' => "Error in listbox '$name'<br>$sql",
					'error' => mysql_error()
				);
			return false;
		}
		
		if (! $id )	$id = preg_replace("/[^a-zA-Z0-9]/","",$name);
		
		echo "<select name='$name' id='$id'";
		if ($onchange) echo " onChange='$onchange'";
		if ($class) echo " class='$class'";
		echo ">\n";
		
		if (! $rcnt = mysql_num_rows($qry))
		{
			if (! $default or $default == "")
				$default="*** NO DATA ***";
			
			echo "<OPTION Selected Value='0'>$default</OPTION>\n";
			echo "</SELECT>\n";
			
			return $rcnt;
		}
		
		if ($default != "" and !$value )
		{
			echo "<option selected value='0'>$default</option>\n";
		}
		else
		{
			echo "<option value='0'>$default</option>\n";
		}
		
		$fcnt = mysql_num_fields($qry);
		
		while ($row = mysql_fetch_array($qry))
		{
			$field1 = $row[0];
			if ($fcnt > 1)
			{
				$field2 = $row[1];
			}
			else
			{
				$field2 = $field1;
			}
			
			if ($value == $field1)
			{
				echo "<option selected value='$field1'>";
			}
			else
			{
				echo "<option value='$field1'>";
			}
			
			echo "$field2</option>\n";
		}
	
		echo "</select>\n";
		
		mysql_free_result($qry);
		
		return $rcnt;
	}	
?>

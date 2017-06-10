<?php
	require_once("../config.php");
	
	set_time_limit(0); 
	ob_end_clean();
	ob_implicit_flush(true);
	
	$json = file_get_contents('php://input');
	$request = json_decode($json, true);

	error_log("JSON: $json");

	require_once("../jsondb.php");
	
	if (!dbconnect($database,$dbuser,$dbpass))
	{
		$response = array(
			'success' => false,
			'completed' => false,
			'message' => "Connect to DB $database failed.<br/>" . mysql_error()
		);
		
		echo json_encode($response);
		exit;
	}
	
	mysql_set_charset('utf-8');

	error_log("--------------- START --------------------");
	
	switch($request['action'])
	{
		case "change-section":
			
			$sql = "select code, parent from $database.main where section=" . $request['fromsection'] . " order by code";
			if ($request['limit'] != '' && $request['limit'] != '0') 
				$sql .= " limit " . $request['limit'];
				
			error_log("Executing query: $sql");
			
			if (!$res = mysql_query($sql))
			{
				$response = array(
					'success' => false,
					'completed' => false,
					'message' => "Fail. query failed :" . mysql_error($mysql) . "<br/>"
						. "SQL: " . $sql
				);
				break;
			}
			$cnt = 0;
			$total = mysql_num_rows($res);
			
			while ($row = mysql_fetch_object($res))
			{
				$cnt++;
				$response = array(
					'success' => true,
					'completed' => ($cnt == $total),
					'progress' => (100 / $total * $cnt),
					'total' => $total,
					'message' => "Processing [" . $row->code . "] $cnt of $total"
				);
				
				error_log("Processing $cnt of $total [$row->code]");
				
				ob_end_clean();
				
				if ($cnt < $total)
				{
					error_log("-- in loop --");
					$response['inloop'] = true;
					echo trim(json_encode($response));
					
					usleep(1000000 / 4);
					
					ob_end_flush();
				}
			}
			break;
		default:
			$response = array(
				'success' => false,
				'completed' => true,
				'message' => "Fail. Unknown action request: " . $request['action']
			);
			
	}
	
	ob_end_clean();
	error_log("-- after --");
	$response['inloop'] = false;
	echo trim(json_encode($response));
	
	mysql_close($mysql);
	
	error_log("--------------- END --------------------");
?>


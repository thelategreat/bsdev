<?php
ini_set('auto_detect_line_endings', true);
set_time_limit(900);

$db = new mysqli('localhost', 'root', 'root', 'products');

$currentDir =  opendir('.');
while($entryName = readdir($currentDir)) {
	$dirArray[] = $entryName;
}

$products_files = array();
$fileList = array();
foreach ($dirArray as $file) {
	$ext = substr($file, strlen($file) - 4);
	if ($ext != '.tab') continue;
	if (preg_match('/prod.*upda.*tab/', $file))  {
		$products_files[] = $file;
	}
}

$data = array();
$insertErrors = $errors = array();
foreach ($products_files as $file) {
	$handle = fopen($file, 'r');

	$recordCount = 0;
	$row = fgetcsv($handle,"\t");
	$fieldCount = 0;
	while ($row = fgetcsv($handle, 0, "\t")) {
	
		if ($fieldCount == 0) $fieldCount = count($row);
		if ($fieldCount != count($row)) {
			die("Missing fields on row $recordCount: <br>");
			var_dump($row);
		}
		$record = array();
		$recordCount++;
		$j = 0;

		$record['ean'] = $row[$j++];
		$record['record_status_id'] = getLinkedTableId('record_statuses', $row[$j++], 'name', false);
		$record['qhr'] = $row[$j++]; 
		$record['bnc_peer_oh'] = $row[$j++]; 
		$record['bnc_peer_oo'] = $row[$j++];
		$record['bnc_peer_asp'] = $row[$j++]; 
		$record['bnc_peer_stores'] = $row[$j++]; 
		$record['bnc_all_asp'] = $row[$j++];
		$record['bs_sales_4week'] = $row[$j++];
		$record['bnc_peer_sales_4week'] = $row[$j++];
		$record['bnc_all_sales_4week'] = $row[$j++];
		$record['bnc_all_sales_total'] = $row[$j++];
		$record['sortby'] = $row[$j++];
		$record['timestamp'] = $row[$j++];

		echo $recordCount;
		echo "<br>";
		flush();


		$success = updateRecord($record);
		if ($success !== true) {
			$insertErrors[] = $success;
			var_dump($success);
		}

		//die;
	}
}

echo '<pre>';

echo "Errors (" . count($errors) . "):\n";
foreach ($errors as $row=>$e) {
	foreach ($e as $err) {	
		echo "Row $row: " . var_dump($e) . "\n";
	}
}
print_r($data);


function updateRecord($r) {
	global $db;

	$fields = array(
		'ean'=>'s', 
		'record_status_id'=>'i',
		'qhr'=>'s',
		'bnc_peer_oh'=>'i',
		'bnc_peer_oo'=>'i',
		'bnc_peer_asp'=>'i',
		'bnc_peer_stores'=>'i',
		'bnc_all_asp'=>'i',
		'bs_sales_4week'=>'i',
		'bnc_peer_sales_4week'=>'i',
		'bnc_all_sales_4week'=>'i',
		'bnc_all_sales_total'=>'i',
		'sortby'=>'i',
		'timestamp'=>'s');


	$sql = "UPDATE products SET ";
	$f = array();

	foreach ($fields as $k=>$v) {
		$f[] .= "$k = ?";
	}
	
	$f = implode(',', $f);

	$sql .= $f;
	$sql .= " WHERE ean = '{$r['ean']}' ";

	if ($stmt = $db->prepare($sql)) {
		$f = ''; 
		$vals = '';
		foreach ($fields as $k=>$v) {
			$vals .= $v;
			$f[] = $r[$k];
		}

		$params[] = $vals;
		$params = array_merge($params, $f);
        call_user_func_array(array($stmt, 'bind_param'), $params);

		$stmt->execute();
		echo $db->error;
	} else {
		echo '<br>';
		echo $db->error;
	}


	/* Get the product ID */
	$sql = "SELECT id FROM products WHERE ean = '{$r['ean']}'";
	$result = $db->query($sql);
	if ($result->num_rows > 0) {
		$row = $result->fetch_object();
		$id = $row->id;
		echo $r['ean'] . ' - ';
		return true;	
	} else {
		return false;
		echo $sql . '<br>';
		echo $db->error;
	}


}



function explodeOn($char, $string) {
	if (strlen($string) < 1 || $string == null) {
		return false;
	}

	$ret = explode('|', $string);

	return $ret;
}

function createLinkedTableId($table, $value, $field) {
	global $db;

	$value = mysql_escape_string(trim($value));
	$sql = "INSERT INTO $table (`$field`) VALUES('$value')";
	$result = $db->query($sql);

	$sql = "SELECT MAX(id) AS id FROM `$table`";

	$result = $db->query($sql);
	$row = $result->fetch_object();

	return $row->id;
}

function getLinkedTableId($table, $value, $field, $caseSensitive) {
	global $db;

	if (!is_array($value)) {
		$value = mysql_escape_string(trim($value));
		if (!$caseSensitive) {
			$value = strtolower($value);
			$sql = "SELECT id FROM $table WHERE LOWER($field) = '$value'";
		} else {
			$sql = "SELECT id FROM $table WHERE $field = '$value'";
		}
	
		$result = $db->query($sql); 
		if ($result->num_rows == 0) {
			$sql = "INSERT INTO $table (`$field`) VALUES('$value')";

			$result = $db->query($sql); 
			if ($db->affected_rows < 1) {
				echo $sql . '<br>';
				echo $db->error;
				die;
			}
		}

		if (!$caseSensitive) {
			$value = strtolower($value);
			$sql = "SELECT id FROM $table WHERE LOWER($field) = '$value'";
		} else {
			$sql = "SELECT id FROM $table WHERE $field = '$value'";
		}

	} else {
		foreach ($value as &$val) {
			$val = mysql_escape_string(trim($val));
		}

		/* Fill in any missing fields with blanks - useful in case of first/last name only having a single name provided */
		if (count($value) < count($field)) {
			for ($i = 0; $i<count($field); $i++) {
				if (!isset($value[$i])) {
					$value[$i] = '';
				}
			}
		}

		/* If there are more values than fields we'll just cut them for now */
		$value = array_slice($value, 0, count($field));

		if (!$caseSensitive) {
			
			$sql = "SELECT id FROM $table WHERE ";
			for ($i = 0; $i < count($field); $i++) {
				$sql .= " LOWER({$field[$i]}) = LOWER('{$value[$i]}') ";
				if ($i < count($field)-1) {
					$sql .= " AND ";
				}
			}

		} else {
			$sql = "SELECT id FROM $table WHERE ";
			for ($i = 0; $i < count($field); $i++) {
				$sql .= " {$field[$i]} = '{$value[$i]}' ";
				if ($i < count($field)) {
					$sql .= " AND ";
				}
			}
		}

		$result = $db->query($sql); 
		if ($result->num_rows == 0) {
			$sql = "INSERT INTO $table ("; 
			for ($i=0; $i<count($field);$i++) {
				$sql .= "`{$field[$i]}`"; 
				if ($i < count($field)-1) {
					$sql .= ', ';
				}
			}
			$sql .= ") VALUES (";
			for ($i=0; $i<count($value);$i++) {
				$sql .= "'{$value[$i]}'"; 
				if ($i < count($value)-1) {
					$sql .= ', ';
				}
			}
			$sql .= ")";
				
			$result = $db->query($sql); 
			if ($db->affected_rows < 1) {
				echo $sql . '<br>';
				echo $db->error;
				die;
			}


			if (!$caseSensitive) {
			
				$sql = "SELECT id FROM $table WHERE ";
				for ($i = 0; $i < count($field); $i++) {
					$sql .= " LOWER({$field[$i]}) = LOWER('{$value[$i]}') ";
					if ($i < count($field)-1) {
						$sql .= " AND ";
					}
				}

			} else {
				$sql = "SELECT id FROM $table WHERE ";
				for ($i = 0; $i < count($field); $i++) {
					$sql .= " {$field[$i]} = '{$value[$i]}' ";
					if ($i < count($field)-1) {
						$sql .= " AND ";
					}
				}
			}

		}



		}
//	echo $sql . "<br>";
	$result = $db->query($sql); 
	$row = $result->fetch_object();	

	if (!isset($row->id)) {
		echo $sql;
		die;
	}
//	var_dump($row);
	return ($row->id);
}
?>
<?php
ini_set('auto_detect_line_endings', true);
set_time_limit(900);

$db = new mysqli('localhost', 'root', 'root', 'products');

$currentDir =  opendir('.');
while($entryName = readdir($currentDir)) {
	$dirArray[] = $entryName;
}

$fileList = array();
foreach ($dirArray as $file) {
	$ext = substr($file, strlen($file) - 4);
	if ($ext != '.tab') continue;
	if (preg_match('/supplier.*tab/', $file))  {
		$supplier_files[] = $file;
	}
}

$data = array();
$insertErrors = $errors = array();
foreach ($supplier_files as $file) {
	echo '<hr>' . $file . '<hr>';
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
		$record['record_status_id'] = getLinkedTableId('record_statuses', $row[$j++], 'status', false);
		$record['supplier_code_id'] = getLinkedTableId('supplier_codes', $row[$j++], 'code', false);
		$record['suppliers_id'] = getLinkedTableId('suppliers', $row[$j++], 'name', true);
		$record['supplier_tags_id'] = getLinkedTableId('supplier_tags', $row[$j++], 'tag', true);
		$record['vendors_id'] = getLinkedTableId('vendors', $row[$j++], 'name', true);
		$record['price_type'] = $row[$j++]; 
		$record['currency_id'] = getLinkedTableId('currencies', $row[$j++], 'code', true);
		$record['list_price'] = $row[$j++]; 
		$record['sell_price'] = $row[$j++]; 
		$record['cost_price'] = $row[$j++]; 
		$record['on_hand'] = $row[$j++]; 
		$record['on_order'] = $row[$j++]; 
		$record['ship_days'] = $row[$j++]; 
		$record['shelf_location'] = $row[$j++]; 
		$record['web_price_edit'] = $row[$j++]; 
		$record['comment'] = $row[$j++]; 
		$record['timestamp'] = $row[$j++]; 

		echo $recordCount;
		echo "<br>";
		flush();


		$success = addRecord($record);
		if ($success !== true) {
			$insertErrors[] = $success;
			echo "Existing record found at row {$recordCount} with EAN: {$record['ean']} and Supplier Code: {$row[2]}<br>";
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


function addRecord($r) {
	global $db;

	$sql = "SELECT id FROM prodsuppliers WHERE ean = '{$r['ean']}' 
			AND supplier_code_id = '{$r['supplier_code_id']}'
			AND price_type = '{$r['price_type']}'
			AND supplier_tags_id = '{$r['supplier_tags_id']}'
			";
	$result = $db->query($sql);

	//echo $sql; echo '<br><br>';
	if ($result->num_rows > 0) {
		die($sql);//return $r['ean'];
	}

	$fields = array(
		'ean'=>'s', 
		'record_status_id'=>'i',
		'supplier_code_id'=>'s',
		'suppliers_id'=>'i',
		'supplier_tags_id'=>'i',
		'vendors_id'=>'i',
		'price_type'=>'s',
		'currency_id'=>'i',
		'list_price'=>'d',
		'sell_price'=>'d',
		'cost_price'=>'d',
		'on_hand'=>'i',
		'on_order'=>'i',
		'ship_days'=>'s',
		'shelf_location'=>'s',
		'web_price_edit'=>'s',
		'comment'=>'s',
		'timestamp'=>'s');


	$sql = "INSERT INTO prodsuppliers(";
	$f = array();

	foreach ($fields as $k=>$v) {
		$f[] = $k;
		$q[] = '?';
	}
	$f = implode(',', $f);
	$q = implode(',', $q);

	$sql .= $f . ") VALUES (";
	$sql .= $q;
	$sql .= ")";
//echo $sql . '<br><br>';
	if ($stmt = $db->prepare($sql)) {
		$f = ''; 
		$vals = '';
		foreach ($fields as $k=>$v) {
			$vals .= $v;
			$f[] = $r[$k];
		}
//print_r($f);
		$params[] = $vals;
		$params = array_merge($params, $f);
        call_user_func_array(array($stmt, 'bind_param'), $params);

		$stmt->execute();
		if ($db->error) echo $db->error;
	} else {
		echo '<br>';
		if ($db->error) echo $db->error;
	}

	$sql = "SELECT id FROM products WHERE ean = '{$r['ean']}'";
	$result = $db->query($sql);
	if ($result->num_rows > 0) {
		$row = $result->fetch_object();
		$product_id = $row->id;

		$sql = "SELECT MAX(id) AS id FROM prodsuppliers";
		$presult = $db->query($sql);
		$row = $presult->fetch_object();

		$prodsuppliers_id = $row->id;

		$sql = "INSERT INTO products_prodsuppliers (products_id, prodsuppliers_id) VALUES ($product_id, $prodsuppliers_id)";
		$db->query($sql);	
	}
//echo $sql . '<hr>';
	return true;
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
	if ($value == '') return null;

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
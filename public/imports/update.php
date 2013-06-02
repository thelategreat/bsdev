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
	if (preg_match('/product.*tab/', $file))  {
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
		$record['record_type_id'] = getLinkedTableId('record_types', $row[$j++], 'name', false);
		$record['record_source_id'] = getLinkedTableId('record_sources', $row[$j++], 'name', true);
		$record['title'] = $row[$j++];
		$record['contributors'] = explodeOn('|', $row[$j++]);
		$record['contributor_types'] = explodeOn('|', $row[$j++]);
		$record['contributor_countries'] = explodeOn('|', $row[$j++]);



		for ($i = 0; $i < sizeof($record['contributors']); $i++) {
			if ($record['contributor_types'] !== false) {
				if (!isset($record['contributor_types'][$i])) {
					$errors[$recordCount][] = 'No contributor type match for contributor ' . $record['contributors'][$i];
				} else {
					$record['contributor_types_ids'][] = getLinkedTableId('contributor_types', $record['contributor_types'][$i], 'name', false);
				}
			}
			if ($record['contributor_countries'] !== false) {
				if (!isset($record['contributor_countries'][$i])) {
					$errors[$recordCount][] = 'No contributor country match for contributor ' . $record['contributors'][$i];
				} else {
					$record['contributor_country_ids'][] = getLinkedTableId('countries', $record['contributor_countries'][$i], 'code', false);
				}
			}

			$contributor = $record['contributors'][$i];
			$type 		 = $record['contributor_types_ids'][$i];

			/* The country may or may not be specified, should only be inserted if it exists */
			if (isset($record['contributor_country_ids'][$i])) {
				$country 	 = $record['contributor_country_ids'][$i];
				$record['contributor_ids'][] = getLinkedTableId('contributors', array($contributor, $type, $country), array('name', 'contributor_types_id', 'country_id'), false);
			} else {
				$record['contributor_ids'][] = getLinkedTableId('contributors', array($contributor, $type), array('name', 'contributor_types_id'), false);

			}

		}

		$record['publisher_id'] = getLinkedTableId('publishers', $row[$j++], 'name', true);
		$record['publish_date'] = $row[$j++];
		$record['bs_sub_codes'] = explodeOn('|', $row[$j++]);
		$record['bs_sub_code_ids'] = array();
		if ($record['bs_sub_codes'] !== false) {
			foreach ($record['bs_sub_codes'] as $code) {
				$record['bs_sub_code_ids'][] = getLinkedTableId('bs_sub_codes', $code, 'code', false);
			}
		}


		$record['bisac_codes'] = explodeOn('|', $row[$j++]);
		$record['bisac_code_ids'] = array();

		$record['bisac_subjects'] = explodeOn('|', $row[$j++]);
		for ($i=0; $i < count($record['bisac_codes']); $i++) { 
			$code = $record['bisac_codes'][$i];
			$text = $record['bisac_subjects'][$i];

			$record['bisac_ids'][] = getLinkedTableId('bisac', array($code, $text), array('code', 'text'), false);
		}


		$record['series_id'] = getLinkedTableId('series', $row[$j++], 'name', false);
		$record['format_code_id'] = getLinkedTableId('format_codes', $row[$j++], 'code', false);
		$record['category_id'] = getLinkedTableId('product_category', $row[$j++], 'name', false);
		$record['qhr'] = $row[$j++];
		$record['onix_bind_codes_id'] = getLinkedTableId('onix_bind_codes', $row[$j++], 'text', false);
		$record['pages'] = $row[$j++];
		$record['size'] = $row[$j++];
		$record['awards'] = $row[$j++];
		$record['audience_id'] = getLinkedTableId('audiences', $row[$j++], 'name', false);
		$record['list_price'] = $row[$j++];
		$record['sell_price'] = $row[$j++];
		$record['othertexts'] = explodeOn('|', $row[$j++]);
		$record['othertext_ids'] = array();
		if ($record['othertexts'] !== false) {
			foreach ($record['othertexts'] as $item) {
				$record['othertext_ids'][] = createLinkedTableId('othertext', $item, 'text');
			}
		}
		$record['othertext_codes'] = explodeOn('|', $row[$j++]);
		if ($record['othertext_codes'] !== false) {
			foreach ($record['othertext_codes'] as $item) {
				$record['othertext_code_ids'][] = getLinkedTableId('othertext_codes', $item, 'name', false);
			}
		}


		$record['teaser'] = $row[$j++];
		$record['description'] = $row[$j++];
		$record['web_meta_edit'] = $row[$j++];
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
		if (!isset($row[$j-1])) {
			var_dump($row);
			die;
		}
		$record['timestamp'] = $row[$j++];

		//$data[] = $record;

		echo $recordCount;
		echo "<br>";
		flush();


		$success = addRecord($record);
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


function addRecord($r) {
	global $db;

	$sql = "SELECT id FROM products WHERE ean = '{$r['ean']}'";
	$result = $db->query($sql);
	if ($result->num_rows > 0) {
		return $r['ean'];
	}

	$fields = array(
		'ean'=>'s', 
		'record_status_id'=>'i',
		'record_type_id'=>'i',
		'record_source_id'=>'i',
		'title'=>'s',
		'publisher_id'=>'i',
		'publish_date'=>'i',
		'series_id'=>'i',
		'format_code_id'=>'i',
		'category_id'=>'i',
		'onix_bind_codes_id'=>'i',
		'qhr'=>'i',
		'pages'=>'i',
		'size'=>'s',
		'awards'=>'s',
		'audience_id'=>'s',
		'list_price'=>'d',
		'sell_price'=>'d',
		'teaser'=>'s',
		'description'=>'s',
		'web_meta_edit'=>'s',
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


	$sql = "INSERT INTO products (";
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
	} else {
			echo $sql . '<br>';
			echo $db->error;
			die;
	}

	/* Add the Contributor IDs */
	$sql = 'DELETE FROM products_contributors WHERE products_id = {$id}';
	$db->query($sql);

	foreach ($r['contributor_ids'] as $cid) {
		$sql = "INSERT INTO products_contributors (products_id, contributors_id)
				VALUES ('{$id}', '$cid')";
		$query = $db->query($sql);
	}

	/* Add the BS sub code IDs */
	$sql = 'DELETE FROM products_bs_subcode_ids WHERE products_id = {$id}';
	$db->query($sql);

	foreach ($r['bs_sub_code_ids'] as $cid) {
		$sql = "INSERT INTO products_bs_subcode_ids (products_id, bs_sub_codes_id)
				VALUES ('{$id}', '{$cid}')";
		$query = $db->query($sql);
	}

	/* Add the BISAC product relation */
	$sql = 'DELETE FROM products_bisac WHERE products_id = {$id}';
	$db->query($sql);

	foreach ($r['bisac_ids'] as $cid) {
		$sql = "INSERT INTO products_bisac (products_id, bisac_id)
				VALUES ('{$id}', '{$cid}')";
		$query = $db->query($sql);
	}

	/* Add the othertext values */
	$sql = 'DELETE FROM product_othertext WHERE products_id = {$id}';
	$db->query($sql);

	for ($i=0; $i<count($r['othertext_ids']); $i++) {
		$text = $r['othertext_ids'][$i];
		$code = $r['othertext_code_ids'][$i];

		$sql = "INSERT INTO product_othertext (products_id, products_othertext_codes, othertext_id)
				VALUES ('{$id}', '{$code}', '{$text}')";
		$query = $db->query($sql);

	}}



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
<?php

class Databases {
	# connection to the database
	function connect($db_type,$var1 = false, $var2 = false, $var3 = false, $var4 = false, $var5 = '') {
		global $db_connected,$pdo,$mysql_connect,$mysql_select_db,$db_type1,$db_prefix;
		$db_type1 = $db_type;		
		$db_prefix = $var5;
		switch($db_type) {
			case 'mysql':
				# var1 = db_host | var2 = db_user | var3 = db_pass | var4 = db_name | var5 = db_prefix
				try {
					$pdo = new PDO("mysql:host=$var1;dbname=$var4;charset=utf8", $var2, $var3);
					$db_connected = true;
				} catch (PDOException $e) {
					die("ERROR: 001");
				}
				break;
			case 'sqlite':
				# var1 = db_path
				try {
					$pdo = new PDO("sqlite:$var1");
					$db_connected = true;
				} catch (PDOException $e) {
					die("ERROR: 001");
				}
				break;
			case 'mysqli':
				# var1 = db_host | var2 = db_user | var3 = db_pass | var4 = db_name | var5 = db_prefix
				$mysql_connect = mysqli_connect($var1,$var2,$var3, $var4) or die("ERROR: 001");
				if($mysql_connect) {
					mysqli_select_db($mysql_connect, $var4) or die("ERROR: 002");
					mysqli_query($mysql_connect, "SET NAMES 'utf8'");
					$db_connected = true;
				}
				break;
		}
	}
	
	#adding prefix to table_name
	function add_prefix($table) {
		global $db_prefix;
		if(empty($db_prefix)) return $table; else return $db_prefix.$table;
	}
	
	#getting data from database
	function fetch($table,$where_fields = false ,$where_values = false,$order_by = false, $order_val = false, $page = false, $per_page = false, $limit = false, $final_return = false) {
		global $db_connected,$db_type1,$pdo;
		$table = $this->add_prefix($table);
		if((empty($page) OR $page == '') AND !empty($per_page)) $page = 1;
		if($db_connected) {
			$query = "SELECT * FROM $table";
			if(is_array($where_fields)) {
				if($where_fields) { 
					$count = 0;
					foreach($where_fields as $k=>$f) {
						# !IMPORTANT $f = secure($f);
						$its_value = $where_values[$k];
						$fetch_type = substr($f,0,3);
						if($fetch_type == '[%]') {
							$f = substr($f,3);
							if($count == 0) $query .= " WHERE `$f` LIKE '%$its_value%'"; else $query .= " AND `$f` LIKE '%$its_value%'";
						} elseif($fetch_type == '[>]') {
							$f = substr($f,3);
							if($count == 0) $query .= " WHERE `$f` > '$its_value'"; else $query .= " AND `$f` > '$its_value'";
						} elseif($fetch_type == '[<]') {
							$f = substr($f,3);
							if($count == 0) $query .= " WHERE `$f` < '$its_value'"; else $query .= " AND `$f` < '$its_value'";
						} elseif($fetch_type == '[!]') {
							$f = substr($f,3);
							if($count == 0) $query .= " WHERE NOT `$f` = '$its_value'"; else $query .= " AND NOT `$f` = '$its_value'";
						} else {
							if($count == 0) $query .= " WHERE `$f` = '$its_value'"; else $query .= " AND `$f` = '$its_value'";
						}
						
						$count = 1;
					}
				}
			} else if(!empty($where_fields)) {
				$fetch_type = substr($where_fields,0,3);
				if($fetch_type == '[%]') {
					$where_fields = substr($where_fields,3);
					$query .= " WHERE `$where_fields` LIKE '%$where_values%'";
				} elseif($fetch_type == '[<]') {
					$where_fields = substr($where_fields,3);
					$query .= " WHERE `$where_fields` < '$where_values'";
				} elseif($fetch_type == '[>]') {
					$where_fields = substr($where_fields,3);
					$query .= " WHERE `$where_fields` > '$where_values'";
				} elseif($fetch_type == '[!]') {
					$where_fields = substr($where_fields,3);
					$query .= " WHERE NOT `$where_fields` = '$where_values'";
				} else {
					$query .= " WHERE `$where_fields` = '$where_values'";
				}
			}
			$final_order_by = "";
			if(is_array($order_by)) {
				$count=0;
				foreach($order_by as $this_order_by) {
					$count++;
					if($count == 1) $final_order_by .= "`$this_order_by`";
					else $final_order_by .= ",`$this_order_by`";
				}
			} elseif(!empty($order_by)) $final_order_by = "`$order_by`";
			if(isset($final_order_by) AND $final_order_by != false AND $final_order_by != '') $query .= " ORDER BY $final_order_by $order_val";
			if($page != false) {
				$from=($per_page*$page)-$per_page;
				$query .= " LIMIT $from,$per_page";
			}
			if($limit != false) $query .= " LIMIT $limit";
			switch($db_type1) {
				case 'mysql':
				case 'sqlite':
					try {
						$stmt = $pdo->query($query);
						$final_array = array();
						while($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
							$final_array[] = $r;
						}
						$return = $final_array;
					} catch(PDOException $ex) {
						die("ERROR: 005");
					}
					break;
				case 'mysqli':
					$final_array = array();
					$mysql_query = mysqli_query($mysql_connect, $query) or die("ERROR: 005");
					while($r = mysqli_fetch_array($mysql_query)) {
						$final_array[] = $r;
					}
					$return = $final_array;
					break;
			}
			
			if($final_return == 'query') {
				return $query;
			} else {
				return $return;
			}
			
		} else die("ERROR: 004");
	}
	
	function pages_array($table,$where_fields = false ,$where_values = false, $page = false, $per_page = false) {
		global $lang;
		if($page == false) $page = 1;
		if($per_page == false) $per_page = 10;
		$total = $this->num_rows($table,$where_fields,$where_values);
		$pages=ceil($total/$per_page);
		$final_array = array();
		
		$max_view = $page+8;
		if($page > 14) $min_view = $page-8; else $min_view = 0;
		for($i=1;$i<=$pages;$i++) {
			if($i < $max_view AND $i > $min_view) $final_array[] = $i;
		}
		return $final_array;
	}
	
	function query($sql_query) {
		global $db_connected,$db_type1,$pdo;
		if($db_connected) {
			switch($db_type1) {
				case 'mysql':
				case 'sqlite':
					try {
						$query = $pdo->query($sql_query);
					} catch(PDOException $ex) {
						die("ERROR: 008");
					}
					break;
				case 'mysqli':
					$query = mysqli_query($mysql_connect, $sql_query) or die("ERROR: 008");
					break;
			}
		} else die("ERROR: 005");
	}
	function insert($table,$fields,$values) {
		$table = $this->add_prefix($table);
		global $db_type1,$pdo;
		# fields = array | values = array
		$final_fields = '';
		if(is_array($fields)) {
			foreach($fields as $f) {
				# !IMPORTANT $f = secure($f);
				if(empty($final_fields)) $final_fields = "`$f`"; else $final_fields .= ",$f";
			}
		} else $final_fields = $fields;
		$final_values = '';
		if(is_array($values)) {
			foreach($values as $v) {
				# !IMPORTANT $v = secure($v);
				if(empty($final_values)) $final_values = "'$v'"; else $final_values .= ",'$v'";
			}
		} else $final_values = "'$values'";
		switch($db_type1) {
			case 'mysql':
			case 'sqlite':
				try {
					$op = $pdo->query("INSERT INTO $table($final_fields) VALUES($final_values)");
				} catch(PDOException $ex) {
					die("ERROR: 008");
				}
				break;
			case 'mysqli':
				$op = mysqli_query($mysql_connect, "INSERT INTO $table($final_fields) VALUES($final_values)");
				break;
		}
		if($op) return true; else return false;
	}
	
	function delete($table,$where_fields,$where_values) {
		global $db_type1,$pdo;
		$table = $this->add_prefix($table);
		$query = "DELETE FROM $table ";
		if(is_array($where_fields)) {
			if($where_fields) { 
				$count = 0;
				foreach($where_fields as $k=>$f) {
					# !IMPORTANT $f = secure($f);
					$its_value = $where_values[$k];
					if($count == 0) $query .= " WHERE `$f` = '$its_value'"; else $query .= " AND `$f` = '$its_value'";
					$count = 1;
				}
			}
		} else if(!empty($where_fields)) $query .= " WHERE `$where_fields` = '$where_values'";

		switch($db_type1) {
			case 'mysql':
			case 'sqlite':
				try {
					$op = $pdo->query($query);
				} catch(PDOException $ex) {
					die("ERROR: 012");
				}
				break;
			case 'mysqli':
				$op = mysqli_query($mysql_connect, $query);
				break;
		}
		if($op) return true; else return false;
	}
	
	function multi_delete($table,$field,$values) {
		global $db_type1;
		$table = $this->add_prefix($table);
		foreach($values as $value) {
			$this->delete($table, $field, $value);
		}
	}
	
	function update($table,$fields,$values,$where_fields = false,$where_values = false) {
		global $db_type1,$pdo;
		$table = $this->add_prefix($table);
		$query = "UPDATE $table SET ";
		
		$final_fields = '';
		$count=0;
		
		if(is_array($fields)) {
			foreach($fields as $k=>$f) {
				# !IMPORTANT $f = secure($f);
				$its_value = $values[$k];
				if($count == 0) $query .= "`$f` = '$its_value'"; else $query .= ", `$f` = '$its_value'";
				$count = 1;
			}
		} else $query .= "`$fields` = '$values'";
		if(is_array($where_fields)) {
			if($where_fields) { 
				$count = 0;
				foreach($where_fields as $k=>$f) {
					# !IMPORTANT $f = secure($f);
					$its_value = $where_values[$k];
					if($count == 0) $query .= " WHERE `$f` = '$its_value'"; else $query .= " AND `$f` = '$its_value'";
					$count = 1;
				}
			}
		} elseif($where_fields !== false AND $where_values !== false) $query .= " WHERE `$where_fields` = '$where_values'";
		
		switch($db_type1) {
			case 'mysql':
			case 'sqlite':
				try {
					$op = $pdo->query($query);
				} catch(PDOException $ex) {
					die("ERROR: 010");
				}
				break;
			case 'mysqli':
				$op = mysqli_query($mysql_connect, $query) or die("ERROR: 010");
				break;
		}

		if($op) return true; else return false;
		
	}
	function num_rows($table,$where_fields = '' ,$where_values = false ) {
		global $db_type1,$pdo;
		$table = $this->add_prefix($table);
		$query = "SELECT * FROM $table ";
		if(is_array($where_fields)) {
			if($where_fields) { 
				$count = 0;
				foreach($where_fields as $k=>$f) {
					# !IMPORTANT $f = secure($f);
					$its_value = $where_values[$k];
					
					$fetch_type = substr($f,0,3);
					if($fetch_type == '[%]') {
						$f = substr($f,3);
						if($count == 0) $query .= " WHERE `$f` LIKE '%$its_value%'"; else $query .= " AND `$f` LIKE '%$its_value%'";
					} elseif($fetch_type == '[<]') {
						$f = substr($f,3);
						if($count == 0) $query .= " WHERE `$f` < '$its_value'"; else $query .= " AND `$f` < '$its_value'";
					} elseif($fetch_type == '[>]') {
						$f = substr($f,3);
						if($count == 0) $query .= " WHERE `$f` > '$its_value'"; else $query .= " AND `$f` > '$its_value'";
					} elseif($fetch_type == '[!]') {
						$f = substr($f,3);
						if($count == 0) $query .= " WHERE NOT `$f` = '$its_value'"; else $query .= " AND NOT `$f` = '$its_value'";
					} else {
						if($count == 0) $query .= " WHERE `$f` = '$its_value'"; else $query .= " AND `$f` = '$its_value'";
					}
					$count = 1;
				}
			}
		} else if(!empty($where_fields)) {
			$fetch_type = substr($where_fields,0,3);
			if($fetch_type == '[%]') {
				$where_fields = substr($where_fields,3);
				$query .= " WHERE $where_fields LIKE '%$where_values%'";
			} elseif($fetch_type == '[<]') {
				$where_fields = substr($where_fields,3);
				$query .= " WHERE $where_fields < '$where_values'";
			} elseif($fetch_type == '[>]') {
				$where_fields = substr($where_fields,3);
				$query .= " WHERE $where_fields > '$where_values'";
			} elseif($fetch_type == '[!]') {
				$where_fields = substr($where_fields,3);
				$query .= " WHERE NOT $where_fields = '$where_values'";
			} else {
				$query .= " WHERE $where_fields = '$where_values'";
			}
			
		}
		switch($db_type1) {
			case 'mysql':
			case 'sqlite':
				try {
					$stmt = $pdo->query($query);
				} catch(PDOException $ex) {
					die("ERROR: 013");
				}
				return $stmt->rowCount();
				break;
			case 'mysqli':
				return mysql_num_rows(mysql_query($query));
				break;
		}
	}
	
	function add_field($table,$field_name) {
		global $db,$db_type1,$pdo;
		$query = "ALTER TABLE `$table` ADD `$field_name` TEXT NOT NULL";
		switch($db_type1) {
			case 'mysql':
			case 'sqlite':
				try {
					$pdo->query($query);
					return true;
				} catch(PDOException $ex) {
					return false;
				}
				break;
			case 'mysqli':
				if(mysqli_query($mysql_connect, $query)) return true; else return false;
				break;
		}
	}
	function delete_field($table,$field_name) {
		global $db_type1,$pdo;
		$query = "ALTER TABLE `$table` DROP `$field_name`";
		switch($db_type1) {
			case 'mysql':
			case 'sqlite':
				try {
					$pdo->query($query);
					return true;
				} catch(PDOException $ex) {
					return false;
				}
				break;
			case 'mysqli':
				if(mysqli_query($mysql_connect, $query)) return true; else return false;
				break;
		}
	}
	
	function query_num_rows($query) {
		global $db_type1,$pdo;
		switch($db_type1) {
			case 'mysql':
			case 'sqlite':
				try {
					$stmt = $pdo->query($query);
				} catch(PDOException $ex) {
					die("ERROR: 013");
				}
				return $stmt->rowCount();
				break;
			case 'mysqli':
				$query = mysqli_query($mysql_connect, $query);
				return mysqli_num_rows($query);
				break;
		}
	}
	
}

?>

<?php

class Users {
	
	function encrypt($i) {
		$i = md5($i);
		$i = sha1($i);
		$i = str_replace('1','6',$i);
		$i = str_replace('8','2',$i);
		return $i;
	}
	
	function register($fields = array(),$values = array()) {
		global $db;
		
		$final_fields = array();
		$final_values = array();
		
		foreach($fields as $key=>$value) {
			if($value == 'password') {
				$final_fields[] = 'password';
				$final_values[] = $this->encrypt($values[$key]);
			} elseif($value == 'username') {
				$final_fields[] = 'username'; $final_values[] = $values[$key];
			} elseif($value == 'level') {
				$final_fields[] = 'level'; $final_values[] = intval($values[$key]);
			} elseif($value == 'email') {
				$final_fields[] = 'email'; $final_values[] = $values[$key];
			} else {
				$check = $db->num_rows('users_fields','field_name',$value);
				if($check > 0) {
					$final_fields[] = "$value";
					$final_values[] = $values[$key];
				}
			}
		}
		
		$register = $db->insert('users',$final_fields,$final_values);
		if($register) return true; else return false;
		
	}
	
	function status($user_id,$status) {
		global $db;
		if($status == 'ban') $final_status = 1; else $final_status = 0;
		$do = $db->update('users','status',$final_status,'id',$user_id);
		if($do) return true; else return false;
	}
	
	function field($user_id, $field) {
		global $db;
		$id = (int) $user_id;
		$final_value = $db->fetch('users', 'id', $id);
		return $final_value[0][$field];
	}
	
	function add_field($name,$phrase,$type,$value) {
		global $db;
		$check = $db->num_rows('users_fields','field_name',$name);
		if($check > 0) return 'field_exsists'; else {
			$add_field = $db->add_field('users',$name);
			$add_field_data = $db->insert('users_fields',array('field_name','field_type','field_value','phrase'),array($name,$type,$value,$phrase));
			if($add_field AND $add_field_data) return true; else return false;
		}
	}
	
	function delete_field($name) {
		global $db;
		$op1 = $db->delete_field('users',$name);
		$op1 = $db->delete('users_fields','field_name',$name);
		if($op1 AND $op2) return true; else return false;
	}
	
	function update($user_id,$fields,$values) {
		global $db;
		$final_fields = array(); $final_values = array();
		foreach($fields as $key=>$field) {
			$final_fields[] = $field;
			if($field == 'password') {
				if(empty($values[$key])) {
					$final_values[] = $this->field($user_id,'password');
				} else {
					$final_values[] = $this->encrypt($values[$key]);
				}
			} else {
				$final_values[] = $values[$key];
			}
		}
		$update = $db->update('users',$final_fields,$final_values,'id',$user_id);
		if($update) return true; else return false;
		
	}
	
	function delete_group($id) {
		global $db;
		$update_users = $db->update('users','user_group','1','user_group',$id);
		$delete = $db->delete('users_groups','id',$id);
		if($delete) return true; else return false;
	}
	
	// Authentication
	function authenticate($username,$password,$remember = false) {
		global $db;
		$password = $this->encrypt($password);
		$check = $db->num_rows('users',array('username','password'),array($username,$password));
		if($check > 0) {
			$ip = $_SERVER["REMOTE_ADDR"];
			$useragent = $_SERVER["HTTP_USER_AGENT"];
			$cookie_hash = md5($username.'-'.$password);
			$fetch = $db->fetch('users',array('username','password'),array($username,$password));
			$fetch = $fetch[0];
			$check_session = $db->num_rows("users_sessions",array('ip','useragent','hash'),
			array($ip,$useragent,$cookie_hash));	
			if($check_session == 0) {
				if($remember) $remember = 1; else $emember = 1;
				$insert = $db->insert('users_sessions',array('user_id','ip','useragent','time','last_time','hash','remember'),
				array($fetch['id'],$ip,$useragent,time(),time(),$cookie_hash,$remember));
			}
			if($remember) {
				setcookie("login",$cookie_hash,3600*64);
			} else {
				$_SESSION['login'] = $cookie_hash;
			}
			return true;
		} else return false;
	}
	
	function getlogin($return = 'boolean') {
		global $db;
		if(isset($_COOKIE['login'])) $current_hash = $_COOKIE['login'];
		elseif(isset($_SESSION['login'])) $current_hash = $_SESSION['login'];
		else return false;
		
		$check = $db->num_rows('users_sessions','hash',$current_hash);
		if($check > 0) {
			$fetch_check = $db->fetch('users_sessions','hash',$current_hash);
			$fetch_check = $fetch_check[0];
			$fetch_user = $db->fetch('users','id',$fetch_check['user_id']);
			$fetch_user = $fetch_user[0];
			if(!empty($fetch_user['id'])) {
				if($return == 'boolean') return true;
				else return $fetch_user['id'];
			} else return false;
		} else return false;
		
	}
	
	function logout() {
		global $db;
		$user_id = $this->getlogin('id');
		if($this->getlogin()) {
			if(isset($_COOKIE['login'])) setcookie ("login", '', time() - 3600);
			elseif(isset($_SESSION['login'])) unset($_SESSION['login']);
			$ip = $_SERVER["REMOTE_ADDR"];
			$useragent = $_SERVER["HTTP_USER_AGENT"];
			$delete = $db->delete('users_sessions',array('user_id','ip','useragent'),
			array($user_id,$ip,$useragent));
			if($delete) return true;
		} else return false;
	}
	
	function check_priv($user_id,$priv) {
		global $db;
		$user = $db->fetch('users','id',$user_id);
		$user = $user[0];
		$usergroup = $db->fetch('users_groups','id',$user['user_group']); $usergroup = $usergroup[0];
		$usergroup = explode('|',$usergroup['privileges']);
		if($usergroup[0] == '[ADMIN]') {
			return true;
		} elseif($usergroup[0] == '[MOD]') {
			if(mb_ereg("($priv)", $usergroup[1])) return true; else return false;
		} else return false;
	}
	
}

?>

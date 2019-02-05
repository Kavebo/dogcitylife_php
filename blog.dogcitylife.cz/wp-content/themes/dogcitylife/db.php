<?php


if (!function_exists('mysqli_fetch_all')){
	function mysqli_fetch_all(mysqli_result $result) {
		$data = array();
		while ($row = $result->fetch_assoc()) {
		    $data[] = $row;
		}
		return $data;
	}
}

class Db{

	public $conn;

	private $login;
	private $password;
	private $server;
	private $database;


	function __construct(){
		if($_SERVER['SERVER_NAME'] == "localhost"){
			$this->login = "root";
			$this->password = "root";
			$this->server = "localhost";
			$this->database = "dogcitylife";
		}else{
			$this->login = "dogcitylifecz001";
			$this->password = "7df52uXL";
			$this->server = "127.0.0.1";
			$this->database = "dogcitylifecz";
		}


		ob_start();
		date_default_timezone_set("Europe/Prague");
		// Create connection
		$this->conn = mysqli_connect($this->server, $this->login, $this->password);
		if (!$this->conn) {
		    die("Can't connect to database server: " . mysqli_connect_error());
		}
		$select_db = mysqli_select_db($this->conn, $this->database);

		if (!$select_db) {
		    die("Can't connect to database"  . mysqli_connect_error());
		}

		mysqli_query($this->conn, "SET NAMES utf8");
	}

	function db_escape($co, $conn) {
	    $znaky = array("'", "\"", "<", ">", "\\"); //$ &
	    $promenna = $co;
	    $osetreni = trim(str_replace($znaky, "", $promenna));
	    return mysqli_real_escape_string($conn, $osetreni);
	}

	function fetch($co) {
	    $vysledek = @mysqli_fetch_array(mysqli_query($this->conn, $co), MYSQLI_ASSOC);
	    if (!$vysledek) {
	      return false;
	    } else {
	      return $vysledek;
	    }
	}

	function fetch_all($co){
	    $vysledek = mysqli_query($this->conn, $co);
	    if (!$vysledek) {
	      return false;
	    } else {
	      return mysqli_fetch_all($vysledek, MYSQLI_ASSOC);
	    }
	}

	function insert($table, $data){

		$SQL = "INSERT INTO " . $table. " ";
		$c = 0;
		foreach($data as $key => $value){
			$c++;
			if($c == 1){
				$SQL .= "(";
			}

			if($c == count($data)){
				$SQL .= $key;
			}else{
				$SQL .= $key . ",";
			}


			if($c == count($data)){
				$SQL .= ")";
			}
		}
		$SQL .= " VALUES ";

		$c = 0;
		foreach($data as $key => $value){
			$c++;
			if($c == 1){
				$SQL .= "(";
			}

			if($c == count($data)){
				$SQL .= "'" . mysqli_real_escape_string($this->conn, $value) . "'";
			}else{
				$SQL .= "'" . mysqli_real_escape_string($this->conn, $value) . "',";
			}


			if($c == count($data)){
				$SQL .= ")";
			}
		}

		$result = mysqli_query($this->conn, $SQL);
		if($result){
			$result = $this->fetch("SELECT MAX(ID) FROM " . $table);
			$result = $result['MAX(ID)'];
		}
		return $result;
	}

	function update($table, $key_change, $data){
		//key_change = array("ID" => $ID);

		$SQL = "UPDATE " . $table . " SET ";
		$c = 0;
		foreach($data as $key => $value){
			$c++;

			if($c == count($data)){
				$SQL .= $key . "='" . mysqli_real_escape_string($this->conn, $value) . "'";
			}else{
				$SQL .= $key . "='" . mysqli_real_escape_string($this->conn, $value) . "',";
			}
		}

		$current_key = "";
		$current_value = "";
		foreach($key_change as $key => $value){
			$current_key = $key;
			$current_value = $value;
		}

		$SQL .= " WHERE " . $current_key . "=" . $current_value;
		$result = mysqli_query($this->conn, $SQL);

		return $result;
	}

	function db_delete($table, $key_change){
		//key_change = array("ID" => $ID);

		$SQL = "DELETE FROM " . $table;

		$current_key = "";
		$current_value = "";
		foreach($key_change as $key => $value){
			$current_key = $key;
			$current_value = $value;
		}

		$SQL .= " WHERE " . $current_key . "=" . $current_value;

		$result = mysqli_query($this->conn, $SQL);

		return $result;
	}

	function get_zarizeni_filter($filter, $typ, $search, $address, $date, $paged){

		$filter_string = "";

		if(!empty($filter)){
			switch ($filter) {
				case 'id_desc':
					$filter_string = "ORDER BY zarizeni.ID DESC";
				break;

				case 'id_asc':
					$filter_string = "ORDER BY zarizeni.ID ASC";
				break;

				case 'name_desc':
					$filter_string = "ORDER BY zarizeni.name DESC";
				break;

				case 'name_asc':
					$filter_string = "ORDER BY zarizeni.name ASC";
				break;

				case 'ico_desc':
					$filter_string = "ORDER BY zarizeni.ico DESC";
				break;

				case 'ico_asc':
					$filter_string = "ORDER BY zarizeni.ico ASC";
				break;

				case 'user_desc':
					$filter_string = "ORDER BY zarizeni.fullname DESC";
				break;

				case 'user_asc':
					$filter_string = "ORDER BY zarizeni.fullname ASC";
				break;
			}
		}else{
			$filter_string = "ORDER BY zarizeni.ID DESC";
		}

		$search_string = "";

		if(!empty($search)){
			$search_string = "AND zarizeni.name LIKE '%" . $this->db_escape($search, $this->conn) . "%'";
		}

		$typ_string = "";

		if(!empty($typ)){
			$typ_string = "AND zarizeni." . $typ . "=1";
		}

		$address_string = "";

		if(!empty($address)){
			$address_string = "AND zarizeni.address LIKE '%" . $this->db_escape($address, $this->conn) . "%'";
		}

		$date_string = "";

		if(!empty($date)){
			$date_string = "AND zarizeni.inserted = '" . $this->db_escape($date, $this->conn) . "'";
		}


		$pagination_string = "";

		$per_page = 15;

		$all = $this->get_zarizeni_filter_all($search, $typ, $address, $date);

		$total_pages = ceil($all/$per_page);

		if($paged > 1){
			$offset = ($paged-1) * $per_page;
		}else{
			$offset = 0;
		}

		$pagination_string = "LIMIT " . $per_page . " OFFSET " . $offset;

		$pagination = array();

		if($total_pages > 1){
			for ($i=1; $i < $total_pages+1; $i++) {

				$link = "?paged=" . $i;

				if(!empty($filter)){
					$link .= "&order=" . $filter;
				}

				if(!empty($search)){
					$link .= "&search=" . $search;
				}

				if(!empty($address)){
					$link .= "&address=" . $address;
				}

				if(!empty($date)){
					$link .= "&date=" . $date;
				}

				$pagination[] = array(
					'page' => $i,
					'link' => $link
					);
			}
		}
		$users = $this->fetch_all("SELECT * FROM zarizeni WHERE lang LIKE 'cs' " . $search_string . $typ_string . $address_string . $date_string . " " . $filter_string . " " . $pagination_string);
		$new = array();
		$new['zarizeni'] = $users;
		$new['pagination'] = $pagination;
		return $new;
	}

	function get_zarizeni_filter_all($search, $typ, $address, $date){

		$search_string = "";

		if(!empty($search)){
			$search_string = "AND zarizeni.name LIKE '%" . $this->db_escape($search, $this->conn) . "%'";
		}

		$typ_string = "";

		if(!empty($typ)){
			$typ_string = "AND zarizeni." . $typ . "=1";
		}

		$address_string = "";

		if(!empty($address)){
			$address_string = "AND zarizeni.address LIKE '%" . $this->db_escape($address, $this->conn) . "%'";
		}

		$date_string = "";

		if(!empty($date)){
			$date_string = "AND zarizeni.inserted = '" . $this->db_escape($date, $this->conn) . "'";
		}

		$users = $this->fetch_all("SELECT count(zarizeni.ID) FROM zarizeni WHERE lang LIKE 'cs' " . $search_string . $typ_string . $address_string . $date_string);
		return($users[0]['count(zarizeni.ID)']);
	}


	function get_uzivatele_filter($filter, $typ, $search, $address, $date, $paged){

		$filter_string = "";

		if(!empty($filter)){
			switch ($filter) {
				case 'id_desc':
					$filter_string = "ORDER BY users.ID DESC";
				break;

				case 'id_asc':
					$filter_string = "ORDER BY users.ID ASC";
				break;

				case 'name_desc':
					$filter_string = "ORDER BY users.name DESC";
				break;

				case 'name_asc':
					$filter_string = "ORDER BY users.name ASC";
				break;

				case 'ico_desc':
					$filter_string = "ORDER BY users.ico DESC";
				break;

				case 'ico_asc':
					$filter_string = "ORDER BY users.ico ASC";
				break;

				case 'user_desc':
					$filter_string = "ORDER BY users.fullname DESC";
				break;

				case 'user_asc':
					$filter_string = "ORDER BY users.fullname ASC";
				break;
			}
		}else{
			$filter_string = "ORDER BY users.ID DESC";
		}

		$search_string = "";

		if(!empty($search)){
			$search_string = "AND users.login LIKE '%" . $this->db_escape($search, $this->conn) . "%'";
		}

		$typ_string = "";

		$address_string = "";

		if(!empty($address)){
			$address_string = "AND users.email LIKE '%" . $this->db_escape($address, $this->conn) . "%'";
		}

		$date_string = "";

		if(!empty($date)){
			$date_string = "AND users.register_date = '" . $this->db_escape($date, $this->conn) . "'";
		}


		$pagination_string = "";

		$per_page = 15;

		$all = $this->get_uzivatele_filter_all($search, $typ, $address, $date);

		$total_pages = ceil($all/$per_page);

		if($paged > 1){
			$offset = ($paged-1) * $per_page;
		}else{
			$offset = 0;
		}

		$pagination_string = "LIMIT " . $per_page . " OFFSET " . $offset;

		$pagination = array();

		if($total_pages > 1){
			for ($i=1; $i < $total_pages+1; $i++) {

				$link = "?paged=" . $i;

				if(!empty($filter)){
					$link .= "&order=" . $filter;
				}

				if(!empty($search)){
					$link .= "&search=" . $search;
				}

				if(!empty($address)){
					$link .= "&address=" . $address;
				}

				if(!empty($date)){
					$link .= "&date=" . $date;
				}

				$pagination[] = array(
					'page' => $i,
					'link' => $link
					);
			}
		}
		$users = $this->fetch_all("SELECT * FROM users WHERE 1 " . $search_string  . $address_string . $date_string . " " . $filter_string . " " . $pagination_string);
		$new = array();
		$new['zarizeni'] = $users;
		$new['pagination'] = $pagination;
		return $new;
	}

	function get_uzivatele_filter_all($search, $typ, $address, $date){

		$search_string = "";

		if(!empty($search)){
			$search_string = "AND users.login LIKE '%" . $this->db_escape($search, $this->conn) . "%'";
		}

		$address_string = "";

		if(!empty($address)){
			$address_string = "AND users.email LIKE '%" . $this->db_escape($address, $this->conn) . "%'";
		}

		$date_string = "";

		if(!empty($date)){
			$date_string = "AND users.register_date = '" . $this->db_escape($date, $this->conn) . "'";
		}
		$users = $this->fetch_all("SELECT count(users.ID) FROM users WHERE 1 " . $search_string . $address_string . $date_string);
		return($users[0]['count(users.ID)']);
	}







	function get_objednavky_filter($paged){

		$pagination_string = "";

		$per_page = 15;

		$all = $this->get_objednavky_filter_all();

		$total_pages = ceil($all/$per_page);
		if($paged > 1){
			$offset = ($paged-1) * $per_page;
		}else{
			$offset = 0;
		}

		$pagination_string = "LIMIT " . $per_page . " OFFSET " . $offset;
		$pagination = array();

		if($total_pages > 1){
			for ($i=1; $i < $total_pages+1; $i++) {

				$link = "?paged=" . $i;

				$pagination[] = array(
					'page' => $i,
					'link' => $link
					);
			}
		}
		$users = $this->fetch_all("SELECT * FROM objednavky WHERE 1 " . " ORDER BY ID DESC " . $pagination_string);
		$new = array();
		$new['zarizeni'] = $users;
		$new['pagination'] = $pagination;
		return $new;
	}

	function get_objednavky_filter_all(){

		$users = $this->fetch_all("SELECT count(objednavky.ID) FROM objednavky WHERE 1 ");
		return($users[0]['count(objednavky.ID)']);
	}
}
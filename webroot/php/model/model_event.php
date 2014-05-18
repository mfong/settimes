<?
class Event {
	private $conn;
	private $db;
	
	function __construct($conn = null) {
		$db = new db();
		if ($conn) $this->conn = $conn;
		$this->db = $db;
		if (!isset($this->conn)) $this->conn = $this->db->open();
	}
	
	function closeConn() {
	 	$this->db->close($this->conn);
	}

	function getEvent($d) {
		$query = "select id,
			json
			from event
			where id = '$d'";
		//echo '<div>' . $query . '</div>';
		$result = mysqli_query($this->conn, $query);
		$event = mysqli_fetch_assoc($result);

		return $event;
	}

	function getEvents() {
		$query = "select id, json from event";
		//echo '<div>' . $query . '</div>';
		$result = mysqli_query($this->conn, $query);
		$i = 0;
		while($row = mysqli_fetch_assoc($result)) {
			$arr[$i] = $row;
			$i++;
		}
		return $arr;
	}

	function jambaseZip($zipcode, $radius, $date, $page) {
		//$data = file_get_contents('http://api.jambase.com/events?zipCode=94105&radius=50&startDate=2014-05-15T20%3A00%3A00&page=0&api_key=63m5jxnfmpsynrpdu2e6ed76');
		$url = 'http://api.jambase.com/events?zipCode=' . $zipcode . '&radius=' . $radius . '&startDate=' . $date . '&page=' . $page . '&api_key=63m5jxnfmpsynrpdu2e6ed76';
		$data = file_get_contents($url);
		//echo $data;
		$json = json_decode($data);
		if ($json->Info->TotalResults > 0) {
			foreach ($json->Events as $event) {
				echo '<br/><br/><br/><hr/><br/>';
				$this->insertEvent($event);
			}
		} else {
			return false;
		}
	}

	function insertEvent($d) {
		//print_r($d);
		$id = $d->Id;
		$d = json_encode($d);
		$data = mysqli_real_escape_string($this->conn, $d);
		$query = "insert into event values (null, 'jambase', '$id', '$data')";
		//echo '<div>' . $query . '</div>';
		if (mysqli_query($this->conn, $query)) {
			return true;
		} else {
			return false;
		}
	}

	function createSlug($d) {
		$d = str_replace(' ', '_', $d);
		$d = str_replace('/', '_', $d);
		$d = str_replace('&', 'and', $d);
		$d = str_replace('+', 'and', $d);
		$d = str_replace("'", '', $d);
		$d = str_replace('!', '', $d);
		$d = str_replace('.', '', $d);
		$d = str_replace(',', '', $d);
		$d = str_replace(':', '', $d);
		$d = str_replace('?', '', $d);
		$d = str_replace('\'', '', $d);

		return strtolower($d);
	}
}?>
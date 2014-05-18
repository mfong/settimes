<?
class db {
	function open() {
		//$conn = mysqli_connect("localhost", "settimes", "timeset!", "settimes")
		$conn = mysqli_connect("127.0.0.1:3308", "settimes", "timeset!", "settimes")
			or die("Some error occurred during connection " . mysqli_error($conn));
		return $conn;
	}
	
	function close($conn) {
		mysqli_close($conn);
	}
}
?>
<!DOCTYPE html>

<html>
<body>

<?php

//registerNewUser
function registerNewUser($email, $name, $pw) {
	$sql = "INSERT INTO users (email, name, password) VALUES ('$email', '$name', '$pw')";
	$conn = mysqli_connect("localhost", "WebShopUser", "1VyldCNbXjpb", "teuns_webshop");
	if (!$conn) {
		die("Something went wrong. Please try again later");
	}
	mysqli_query($conn, $sql);
	mysqli_close($conn);
}

//getData
function getData($table){
	$sql = "SELECT * FROM $table";
	$conn = mysqli_connect("localhost", "WebShopUser", "1VyldCNbXjpb", "teuns_webshop");
	if (!$conn) {
		die("Something went wrong. Please try again later.");
	}
	$query = mysqli_query($conn, $sql);
	mysqli_close($conn);
	return $query;
}

//getRowData
function getColumnData($table, $column) {
	$sql = "SELECT $column FROM $table";
	$conn = mysqli_connect("localhost", "WebShopUser", "1VyldCNbXjpb", "teuns_webshop");
	if (!$conn) {
		die("Something went wrong. Please try again later.");
	}
	$query = mysqli_query($conn, $sql);
	mysqli_close($conn);
	return $query;
}

//getSpecificData
function getSpecificData($table, $column, $data){
	$sql = "SELECT * FROM $table WHERE $column='$data'";
	$conn = mysqli_connect("localhost", "WebShopUser", "1VyldCNbXjpb", "teuns_webshop");
	if (!$conn) {
		die("Something went wrong. Please try again later");
	}
	$query = mysqli_query($conn, $sql);
	mysqli_close($conn);
	return $query;
}

?>

</body>
</html>
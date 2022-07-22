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

//registerOrder
function registerOrder() {
	$conn = mysqli_connect("localhost", "WebShopUser", "1VyldCNbXjpb", "teuns_webshop");
	if (!$conn) {
		die("Something went wrong. Please try again later");
	}
	$user_id = $_SESSION['user_id'];
	$total = number_format(array_sum($_SESSION['total']), 2);
	$sql = "INSERT INTO orders (user_id, total) VALUES ('$user_id', '$total')";
	mysqli_query($conn, $sql);
	$sql = "SELECT ID FROM orders WHERE ID=(SELECT max(ID) FROM orders)";
	$data = mysqli_query($conn, $sql);
	$row = mysqli_fetch_array($data);
	$order_id = $row['ID'];
	$items = array_filter($_SESSION['cart']);
	foreach ($items as $product_id => $qty) {
		$sql = "INSERT INTO order_item (order_id, product_id, quantity) VALUES ('$order_id', '$product_id', '$qty')";
		mysqli_query($conn, $sql);
	}
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
function getRowData($table, $column, $data){
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
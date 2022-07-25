<?php

//connectToDB

use JetBrains\PhpStorm\ExpectedValues;

function connectToDB() {
	$conn = mysqli_connect("localhost", "WebShopUser", "1VyldCNbXjpb", "teuns_webshop");
	if (!$conn) {
		die("foutje"); //Moet nog een exception worden
	}
	return $conn;
}

//registerNewUser
function registerNewUser($email, $name, $pw) {
	$sql = "INSERT INTO users (email, name, password) VALUES ('$email', '$name', '$pw')";
	$conn = connectToDB();
	mysqli_query($conn, $sql);
	mysqli_close($conn);
}

//registerOrder
function registerOrder() {
	$conn = connectToDB();
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

//findUserByEmail
function findUserByEmail($email) {
	$sql = "SELECT * FROM users WHERE email='$email'";
	$conn = connectToDB();
	try {
		$result = mysqli_query($conn, $sql);
		if (!$result) {
			throw new Exception("findUserByEmail query failed, SQL: ".$sql." error: ".mysqli_error($conn));
		}
		$user = mysqli_fetch_assoc($result);
		return $user;
	}
	finally {
		mysqli_close($conn);
	}
}

//getAllProducts
function getAllProducts() {
	$sql = "SELECT * FROM product";
	$conn = connectToDB();
	try {
		$products = mysqli_query($conn, $sql);
		if (!$products) {
			throw new Exception("getAllProducts query failed, SQL: ".$sql." error: ".mysqli_error($conn));
		}
		return $products;
	}
	finally {
		mysqli_close($conn);
	}
}

//getData
function getData($table){
	$sql = "SELECT * FROM $table";
	$conn = connectToDB();
	$query = mysqli_query($conn, $sql);
	mysqli_close($conn);
	return $query;
}

//getRowData
function getColumnData($table, $column) {
	$sql = "SELECT $column FROM $table";
	$conn = connectToDB();
	$query = mysqli_query($conn, $sql);
	mysqli_close($conn);
	return $query;
}

//getSpecificData
function getRowData($table, $column, $data){
	$sql = "SELECT * FROM $table WHERE $column='$data'";
	$conn = connectToDB();
	$query = mysqli_query($conn, $sql);
	mysqli_close($conn);
	return $query;
}

?>
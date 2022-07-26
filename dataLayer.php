<?php

//connectToDB
function connectToDB() {
	try {
		$conn = mysqli_connect("localhost", "WebShopUser", "1VyldCNbXjpb", "teuns_webshop");
		if (!$conn) {
			throw new Exception("Connection failed".mysqli_connect_error());
		}
	} catch (Exception $e) {
		logError($e);
	}
	return $conn;
}

//registerNewUser
function registerNewUser($email, $name, $pw) {
	$sql = "INSERT INTO users (email, name, password) VALUES ('$email', '$name', '$pw')";
	$conn = connectToDB();
	try {
		$result = mysqli_query($conn, $sql);
		if (!$result) {
			throw new Exception("registerNewUser query failed, SQL: ".$sql." error: ".mysqli_error($conn));
		}
	}
	finally {
		mysqli_close($conn);
	}
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

//getSingleProduct
function getSingleProduct($id) {
	$sql = "SELECT * FROM product WHERE ID=$id";
	$conn = connectToDB();
	try {
		$product = mysqli_query($conn, $sql);
		if (!$product) {
			throw new Exception("getSingleProduct query failed, SQL: ".$sql." error: ".mysqli_error($conn));
		}
		return $product;
	}
	finally {
		mysqli_close($conn);
	}
}

//registerOrder
function registerOrder() {
	$user_id = $_SESSION['user_id'];
	$total = number_format(array_sum($_SESSION['total']), 2);
	$sql = "INSERT INTO orders (user_id, total) VALUES ('$user_id', '$total')";
	$conn = connectToDB();
	try {
		if (mysqli_query($conn, $sql)) {
			$order_id = mysqli_insert_id($conn);
		} else {
			throw new Exception("Can't insert order, SQL: ".$sql." error: ".mysqli_error($conn));
		}
		$items = array_filter($_SESSION['cart']);
		foreach ($items as $product_id => $qty) {
			$sql = "INSERT INTO order_item (order_id, product_id, quantity) VALUES ('$order_id', '$product_id', '$qty')";
			$result = mysqli_query($conn, $sql);
			if (!$result) {
				throw new Exception ("Can't insert item, SQL: ".$sql." error: ".mysqli_error($conn));
			}
		}
	}
	finally {
		mysqli_close($conn);
	}
}

?>
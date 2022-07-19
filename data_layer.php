<!DOCTYPE html>

<html>
<body>

<?php

//getUserData
function getUserData($data) {
	$sql = "SELECT * FROM users WHERE email='$data'";
	$conn = mysqli_connect("localhost", "WebShopUser", "1VyldCNbXjpb", "teuns_webshop");
	if (!$conn) {
		die("Something went wrong. Please try again later");
	}
	$query = mysqli_query($conn, $sql);
	$user = mysqli_fetch_assoc($query);
	mysqli_close($conn);
	return $user;
}

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

//getProductData
function getProductData(){
	$sql = "SELECT * FROM product";
	$conn = mysqli_connect("localhost", "WebShopUser", "1VyldCNbXjpb", "teuns_webshop");
	if (!$conn) {
		die("Something went wrong. Please try again later.");
	}
	$query = mysqli_query($conn, $sql);
	mysqli_close($conn);
	return $query;
}

?>

</body>
</html>
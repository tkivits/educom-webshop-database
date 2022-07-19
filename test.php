<!DOCTYPE html>

<html>
<body>

<?php

$servername = "localhost";
$username = "WebShopUser";
$password = "1VyldCNbXjpb";
$dbname = "teuns_webshop";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT * FROM users WHERE email='coach@man-kind.nl'";

$array = mysqli_fetch_array(mysqli_query($conn, $sql));
$name = $array['name'];
$email = $array['email'];
echo $name;
echo $email;


?>

</body>
</html>

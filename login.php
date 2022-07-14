<?php
session_start()
?>

<!DOCTYPE html>
<html>
<body>

<?php 

//Variabelen voor sessie
$_SESSION['login'] = False;
$_SESSION['name'] = "";

//Variabelen
$emailerr = $pwerr = "";
$email = $pw = $pw_check = "";
$login = False;

//Functie test_input
function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

//Functie check_user om gebruikersdata te vergelijken met users.txt
function check_user($data) {
	$sql = "SELECT * FROM users WHERE email='$data'";
	$conn = mysqli_connect("localhost", "WebShopUser", "1VyldCNbXjpb", "teuns_webshop");
	$query = mysqli_query($conn, $sql);
	$array = mysqli_fetch_array($query);
	$email = $array['email'];
	if ($data == $email) {
		return True;
	} else {
		return False;
	}
	mysqli_close($conn);
}

//Functie check_password om wachtwoord te vergelijken met users.txt
function check_password($data) {
	$sql = "SELECT * FROM users WHERE email='$data'";
	$conn = mysqli_connect("localhost", "WebShopUser", "1VyldCNbXjpb", "teuns_webshop");
	$array = mysqli_fetch_array(mysqli_query($conn, $sql));
	$pw_check = $array['password'];
	mysqli_close($conn);
	return $pw_check;
}

//Functie get_name om naam op te halen uit users.txt
function get_name($data) {
	$sql = "SELECT * FROM users WHERE email='$data'";
	$conn = mysqli_connect("localhost", "WebShopUser", "1VyldCNbXjpb", "teuns_webshop");
	$array = mysqli_fetch_array(mysqli_query($conn, $sql));
	$get_name = $array['name'];
	mysqli_close($conn);
	return $get_name;
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
	if (empty($_POST["email"])) {
		$emailerr = "E-mail is required";
	} else {
		$email = test_input($_POST["email"]);
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$emailerr = "Invalid e-mail";
		} else {
			if (check_user($email) == False){
				$emailerr = "Unknown e-mail";
			} else {
				$pw_check = check_password($email);
			}
		}
	}
	if (empty($_POST["pw"])) {
		$pwerr = "Password is required";
	} else {
		$pw = test_input($_POST["pw"]);
	}
	if ($pw !== $pw_check) {
		$pwerr = "E-mail doesn't match password";
	}
	if(empty($emailerr) && empty($pwerr)) {
		$_SESSION['login'] = True;
	}
}?>

<ul class="menu">
  <li><a href="?page=Home">Home</a></li>
  <li><a href="?page=About">About</a></li>
  <li><a href="?page=Contact">Contact</a></li>
  <?php if (empty($_SESSION['login'])) { ?>
  <li><a href="?page=Register">Register</a></li>
  <li><a href="?page=Login">Login</a></li>
  <?php } else { ?>
  <li><a href="?page=Logout">Logout <?php echo $_SESSION['name'] ?></a></li>
  <?php } ?>
</ul>


<?php //showLoginForm
if (!$_SESSION['login']) { ?>
<form class="form" method="post" action="<?php echo htmlspecialchars('?page=Login');?>">
  <div><label for="email">E-mail:</label>
    <input type="email" id="email" name="email" value="<?php echo $email;?>">
    <span class="error">* <?php echo $emailerr;?></span></div>
  <div><label for="password">Password:</label>
    <input type="password" id="pw" name="pw" value="<?php echo $pw;?>">
    <span class="error">* <?php echo $pwerr;?></span></div>
<input type="submit" value="Login">
<?php } else {
	$_SESSION['name'] = get_name($email);
	echo '<meta http-equiv="refresh" content="0; URL=?page=Home">';
} ?>

</body>
</html>
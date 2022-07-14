<?php
session_start()
?>

<!DOCTYPE html>

<html>
<body>

<?php
//Variabelen
$namerr = $emailerr = $pwerr = $pwrepeaterr = "";
$name = $email = $pw = $pwrepeat = "";
$valid = False;

//Functie test_input

function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

//Functie check_existing_mail om mail te matchen in users.txt

function check_existing_mail($data) {
	$checkfile = fread(fopen("Users/users.txt", "r"), filesize("Users/users.txt"));
	if(strstr($checkfile, $data) !== False) {
		return True;
	} else {
		return False;
	}
	$checkfile = fclose("Users/users.txt");
}

//RegisterForm verwerken

if($_SERVER["REQUEST_METHOD"] == "POST") {
	if (empty($_POST["name"])) {
		$namerr = "Name is required";
	} else {
		$name = test_input($_POST["name"]);
		if (!preg_match("/^[a-zA-Z-' ]*$/",$name)) {
        $namerr = "Only letters and spaces are allowed";
		}
	}
	if (empty($_POST["email"])) {
		$emailerr = "E-mail is required";
	} else {
		$email = test_input($_POST["email"]);
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$emailerr = "Invalid e-mail";
		} else {
			if (check_existing_mail($email) == True) {
				$emailerr = "E-mail already exists";
			}
		}
	}
	if (empty($_POST["pw"])) {
		$pwerr = "Password is required";
	} else {
		$pw = test_input($_POST["pw"]);
	}
	if (empty($_POST["pwrepeat"])) {
		$pwrepeaterr = "Please repeat your password";
	} else {
		$pwrepeat = test_input($_POST["pwrepeat"]);
		if ($pw !== $pwrepeat) {
			$pwrepeaterr = "Entered passwords do not match";
		}
	}
	//Formulier is valide
	if (empty($namerr) && empty($emailerr) && empty($pwerr) && empty($pwrepeaterr)) {
		$valid = True;
	}
}
?>

<ul class="menu">
  <li><a href="?page=Home">Home</a></li>
  <li><a href="?page=About">About</a></li>
  <li><a href="?page=Contact">Contact</a></li>
  <?php if (!$_SESSION['login']) { ?>
  <li><a href="?page=Register">Register</a></li>
  <li><a href="?page=Login">Login</a></li>
  <?php } else { ?>
  <li><a href="?page=Logout">Logout <?php echo $_SESSION['name'] ?></a></li>
  <?php } ?>
</ul>

<?php
// showRegisterForm
if(!$valid) { ?>
<div><span class="error">Fields with a * are required</span></div> 
<form class="form" method="post" action="<?php echo htmlspecialchars('?page=Register');?>">
  <div><label for="name">Name:</label>
    <input type="text" id="name" name="name" value="<?php echo $name;?>">
    <span class="error">* <?php echo $namerr;?></span></div>
  <div><label for="email">E-mail:</label>
    <input type="email" id="email" name="email" value="<?php echo $email;?>">
    <span class="error">* <?php echo $emailerr;?></span></div>
  <div><label for="password">Password:</label>
    <input type="password" id="pw" name="pw" value="<?php echo $pw;?>">
    <span class="error">* <?php echo $pwerr;?></span></div>
  <div><label for="password repeat">Repeat password:</label>
    <input type="password" id="pwrepeat" name="pwrepeat" value="<?php echo $pwrepeat;?>">
    <span class="error">* <?php echo $pwrepeaterr;?></span></div>
  <input type="submit" value="Register">
 </form>
<?php } else {
	$user = fopen("Users/users.txt", "a");
	fwrite($user, "\n$email|$name|$pw|");
	fclose($user);
	echo '<meta http-equiv="refresh" content="0; URL=?page=Login">';
} ?>

</body>
</html>
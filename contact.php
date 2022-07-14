<?php
session_start()
?>

<!DOCTYPE html>
<html>
<body>

<?php
//variabelen
$salerr = $namerr = $emailerr = $phonerr = $compreferr = $messerr = "";
$sal = $name = $email = $phone = $compref = $mess = "";
$valid = False;

//variabelen verwerken
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if(empty($_POST["salutation"])) {
    $salerr = "Salutation is required";
  } else {
    $sal = test_input($_POST["salutation"]);
  }
  
  if(empty($_POST["name"])) {
    $namerr = "Name is required";
  } else { 
  $name = test_input($_POST["name"]);
  if (!preg_match("/^[a-zA-Z-' ]*$/",$name)) {
    $namerr = "Only letters and spaces are allowed";
    }
  }
  
  if(empty($_POST["email"])) {
    $emailerr = "E-mail is required";
  } else {
    $email = test_input($_POST["email"]);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $emailerr = "Invalid e-mail";
    }
  }
  
  if(empty($_POST["phone"])) {
    $phonerr = "Phone number is required";
  } else {
    $phone = test_input($_POST["phone"]);
	if (!preg_match("/^0[0-9]{1,3}-{0,1}[0-9]{6,8}$/",$phone)) {
		$phonerr = "Invalid phone number";
	}
  }
  
  if(empty($_POST["compref"])) {
    $compreferr = "Communication preference is required";
  } else {
    $compref = test_input($_POST["compref"]);
  }
  
  if(empty($_POST["mess"])) {
    $messerr = "A message is required";
  } else {
    $mess = test_input($_POST["mess"]);
  }
  
//Als het formulier geen errors heeft bezoeker naar bedankpagina sturen
  if(empty($salerr) && empty($namerr) && empty($emailerr) && empty($phonerr) && empty($compreferr) && empty($messagerr)) {
    $valid = True;
  }
}

//functie test_input
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
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
//showContactForm

if (!$valid) { ?>
<div><span class="error">Fields with a * are required!</span></div>
<form class="form" method="post" action="<?php echo htmlspecialchars('?page=Contact');?>">
  <div><label for="salutation"></label>
    <select id="salutation" name="salutation">
      <option value="">Choose</option>
      <option value="Mr." <?php if ($sal=="Mr.") echo "selected";?>>Mr.</option>
      <option value="Mrs"<?php if ($sal=="Mrs") echo "selected";?>>Mrs</option>
    </select>
    <span class="error">* <?php echo $salerr ?></span></div>
  <div><label for="name">Name:</label>
    <input type="text" id="name" name="name" value="<?php echo $name;?>">
    <span class="error">* <?php echo $namerr;?></span></div>
  <div><label for="email">E-mail:</label>
    <input type="email" id="email" name="email" value="<?php echo $email;?>">
    <span class="error">* <?php echo $emailerr;?></span></div>
  <div><label for="phone">Phone number:</label>
    <input type="tel" id="phone" name="phone" value="<?php echo $phone;?>">
    <span class="error">* <?php echo $phonerr;?></span></div>
  <div><label for="compref">What is your communication preference?</label>
  <input type="radio" id="email" name="compref" <?php if ($compref=="E-mail") echo "checked";?> value="E-mail">
    <label for="email">E-mail</label>
  <input type="radio" id="telephone" name="compref" <?php if ($compref=="Telephone") echo "checked";?> value="Telephone">
    <label for="telephone">Telephone</label>
    <span class="error">* <?php echo $compreferr;?></span></div>
  <div><textarea id="mess" name="mess" rows="8" cols="50" placeholder= "Tell us why you want to contact us!"><?php echo $mess;?></textarea>
  <span class="error">* <?php echo $messerr ?></div>
  <input type="submit" value="Submit">
 </form> 
 <?php } else { ?>
 <h1>Thank you for filling in the contact form!</h1>
 <div>Your details are: <?php echo $sal ?> <?php echo $name ?></div>
 <div>Email: <?php echo $email ?></div>
 <div>Telephone: <?php echo $phone ?></div>
 <div>Communication preference: <?php echo $compref ?></div>
 <div>Message: <?php echo $mess;} ?></div>
 
</body>
</html>
<?php
session_start()
?>

<!DOCTYPE html>

<html>
<body>

<?php
//Variabelen
$salErr = $namErr = $emailErr = $phonErr = $comprefErr = $messErr = $pwErr = $pwRepeatErr = "";

//showMenu
function showMenu() {
	include 'menu.php';
}

//showHomePage
function showHomePage() {
	include 'home.php';
}

//showAboutpage
function showAboutPage() {
	include 'about.php';
}

//showContactPage
function showContactPage() {
	global $salErr, $namErr, $emailErr, $phonErr, $comprefErr, $messErr;
	include 'contact.php';
}

//showContactThanksPage
function showContactThanksPage() {
	include 'contactthanks.php';
}

//showRegisterPage
function showRegisterPage() {
	global $namErr, $emailErr, $pwErr, $pwRepeatErr;
	include 'register.php';
}

//showLoginPage
function showLoginPage() {
	global $emailErr, $pwErr;
	include 'login.php';
}

//showFooter
function showFooter() {
	include 'footer.php';
}

//testInput
function testInput($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

//testContact
function testContact() {
	global $salErr, $namErr, $emailErr, $phonErr, $comprefErr, $messErr;
	if($_SERVER["REQUEST_METHOD"] == "POST") {
	  if(empty($_POST["sal"])) {
      $salErr = "Salutation is required";
      } else {
      $sal = testInput($_POST["sal"]);
      }
      if(empty($_POST["name"])) {
        $namErr = "Name is required";
      } else { 
      $name = testInput($_POST["name"]);
      if (!preg_match("/^[a-zA-Z-' ]*$/",$name)) {
        $namErr = "Only letters and spaces are allowed";
        }
      }
      if(empty($_POST["email"])) {
        $emailErr = "E-mail is required";
      } else {
        $email = testInput($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          $emailErr = "Invalid e-mail";
        }
	  }
      if(empty($_POST["phone"])) {
        $phonErr = "Phone number is required";
      } else {
        $phone = testInput($_POST["phone"]);
	    if (!preg_match("/^0[0-9]{1,3}-{0,1}[0-9]{6,8}$/",$phone)) {
		    $phonErr = "Invalid phone number";
	    }
      }
      if(empty($_POST["compref"])) {
        $comprefErr = "Communication preference is required";
      } else {
        $compref = testInput($_POST["compref"]);
      }
      if(empty($_POST["mess"])) {
        $messErr = "A message is required";
      } else {
        $mess = testInput($_POST["mess"]);
      }
      if(empty($salErr) && empty($namErr) && empty($emailErr) && empty($phonErr) && empty($comprefErr) && empty($messErr)) {
        return True;
      }
	}
}

//checkUser
function checkUser($data) {
	$file = fopen("Users/users.txt", "r");
	$read = fread($file, filesize("Users/users.txt"));
	$read = preg_replace('~[\r\n]+~', '|', $read);
	$array = explode("|", $read);
	if (in_array($data, $array)) {
		return True;
	} else {
		return False;
	}
	fclose($file);
}

//checkRegistration
function checkRegistration() {
	global $namErr, $emailErr, $pwErr, $pwRepeatErr;
	if($_SERVER["REQUEST_METHOD"] == "POST") {
	  if (empty($_POST["name"])) {
		  $namErr = "Name is required";
	  } else {
		  $name = testInput($_POST["name"]);
		  if (!preg_match("/^[a-zA-Z-' ]*$/",$name)) {
          $namErr = "Only letters and spaces are allowed";
		  }
	  }
	  if (empty($_POST["email"])) {
		  $emailErr = "E-mail is required";
	  } else {
		  $email = testInput($_POST["email"]);
		  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			  $emailErr = "Invalid e-mail";
		  } else {
			  if (checkUser($email) == True) {
				  $emailErr = "E-mail already exists";
			  }
		  }
	  }
	  if (empty($_POST["pw"])) {
		  $pwErr = "Password is required";
	  } else {
		  $pw = testInput($_POST["pw"]);
	  }
	  if (empty($_POST["pwrepeat"])) {
		  $pwRepeatErr = "Please repeat your password";
	  } else {
		  $pwrepeat = testInput($_POST["pwrepeat"]);
		  if ($pw !== $pwrepeat) {
			  $pwRepeatErr = "Entered passwords do not match";
		  }
	  }
	  if (empty($namErr) && empty($emailErr) && empty($pwErr) && empty($pwRepeatErr)) {
		  $user = fopen("Users/users.txt", "a");
		  fwrite($user, "\n$email|$name|$pw");
		  fclose($user);
		  return True;
	  }
	}
}

//checkPassword
function checkPassword($data) {
	$file = fopen("Users/users.txt", "r");
	$read = fread($file, filesize("Users/users.txt"));
	$read = preg_replace('~[\r\n]+~', '|', $read);
	$array = explode("|", $read);
	$pw_check = $array[array_search($data, $array)+2];
	fclose ($file);
	return $pw_check;
}

//getName
function getName($data) {
	$file = fopen("Users/users.txt", "r");
	$read = fread($file, filesize("Users/users.txt"));
	$read = preg_replace('~[\r\n]+~', '|', $read);
	$array = explode("|", $read);
	$getName = $array[array_search($data, $array)+1];
	fclose ($file);
	return $getName;
}

//logInUser

function logInUser() {
	global $emailErr, $pwErr;
	$pw = $pwCheck = "";
	if($_SERVER["REQUEST_METHOD"] == "POST") {
	  if (empty($_POST["email"])) {
		  $emailErr = "E-mail is required";
	  } else {
		  $email = testInput($_POST["email"]);
		  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			  $emailErr = "Invalid e-mail";
		  } else {
			  if (checkUser($email) == False){
				  $emailErr = "Unknown e-mail";
			  } else {
				  $pwCheck = checkPassword($email);
			  }
		  }
	  }
	  if (empty($_POST["pw"])) {
		  $pwErr = "Password is required";
	  } else {
		  $pw = testInput($_POST["pw"]);
	  }
	  if ($pw !== $pwCheck) {
		  $pwErr = "E-mail doesn't match password";
	  }
	  if(empty($emailErr) && empty($pwErr)) {
		  $_SESSION['login'] = True;
		  $_SESSION['email'] = $email;
		  $_SESSION['name'] = getName($email);
		  return True;
	  }
	}
}

//logOutUser
function logOutUser() {
	session_unset();
}

//getRequestedPage
function getRequestedPage(){
	if(!isset($_GET['page'])){
		return 'Home';
	}
	else {
		return $_GET['page'];
	}
}

//processRequest
function processRequest($page) {
	switch($page)
	{
		case 'Contact';
		$data = testContact();
		if ($data == True) {
			$page = 'Thanks';
		}
		break;
		case 'Register';
		$data = checkRegistration();
		if ($data == True) {
			$page = 'Login';
		}
		break;
		case 'Login';
		$data = logInUser();
		if ($data == True) {
			$page = 'Home';
		}
		break;
		case 'Logout';
		logOutUser();
		$page = 'Home';
		break;
	}
	$data = $page;
	return $data;
}

//showResponsePage
function showResponsePage($data){
	echo '<h1 class="header">'.$data.'</h1>';
	showMenu();
	switch($data)
	{
		case 'Home';
		  showHomePage();
		  break;
		case 'About';
		  showAboutPage();
		  break;
		case 'Contact';
		  showContactPage();
		  break;
		case 'Thanks';
		  showContactThanksPage();
		  break;
		case 'Register';
		  showRegisterPage();
		  break;
		case 'Login';
		  showLoginPage();
		  break;
		default; 
		  showHomePage();
	}
	showFooter();
}

?>
</body>
</html>
<?php
session_start()
?>

<!DOCTYPE html>

<html>
<body>

<?php
//data_layer
require 'data_layer.php';

//variabelen
$salErr = $namErr = $emailErr = $phonErr = $comprefErr = $messErr = $pwErr = $pwRepeatErr = "";

//showHeader
function showHeader() {
	echo '<h1 class="header">'.$_GET['page'].'</h1>';
}

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

//showWebshopPage
function showWebshopPage() {
	showProductOverview();
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

//checkRegistration
function checkRegistration() {
	global $namErr, $emailErr, $pwErr, $pwRepeatErr;
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		$name = testInput($_POST['name']);
		$email = testInput($_POST['email']);
		$pw = testInput($_POST['pw']);
		$pwrepeat = testInput($_POST['pwrepeat']);
		$data = getData('users', 'email', $email);
		$user = mysqli_fetch_assoc($data);
	    if (empty($_POST["name"])) {
		    $namErr = "Name is required";
	    } elseif (!preg_match("/^[a-zA-Z-' ]*$/",$name)) {
            $namErr = "Only letters and spaces are allowed";
		}
	    if (empty($_POST["email"])) {
		    $emailErr = "E-mail is required";
			} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$emailErr = "Invalid e-mail";
				} elseif (!empty($user['email']) && $user['email'] == $email) {
					$emailErr = "E-mail already exists";
				}
	    if (empty($_POST["pw"])) {
		    $pwErr = "Password is required";
		}
	    if (empty($_POST["pwrepeat"])) {
		    $pwRepeatErr = "Please repeat your password";
	    } elseif ($pw !== $pwrepeat) {
			$pwRepeatErr = "Entered passwords do not match";
		}
		if (empty($namErr) && empty($emailErr) && empty($pwErr) && empty($pwRepeatErr)) {
			registerNewUser($email, $name, $pw);
		    return True;
		}
	}
}

//logInUser
function logInUser() {
	global $emailErr, $pwErr;
	$pw = $pwCheck = "";
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		$email = testInput($_POST['email']);
		$pw = testInput($_POST['pw']);
		$data = getSpecificData('users', 'email',$email);
		$user = mysqli_fetch_assoc($data);
	    if (empty($email)) {
		    $emailErr = "E-mail is required";
	    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$emailErr = "Invalid e-mail";
		} elseif (empty($user) || $user['email'] !== $email) {
		    $emailErr = "Unknown e-mail";
		}
	    if (empty($pw)) {
		    $pwErr = "Password is required";
		} elseif (empty($user) || $user['password'] !== $pw) {
			$pwErr = "E-mail doesn't match password";
		}
	    if(empty($emailErr) && empty($pwErr)) {
		    $_SESSION['login'] = True;
		    $_SESSION['email'] = $user['email'];
		    $_SESSION['name'] = $user['name'];
		    return True;
		}
	}
}

//logOutUser
function logOutUser() {
	session_unset();
}

//showProductOverview
function showProductOverview() {
	$product_data = getData('product');
	while ($row = mysqli_fetch_array($product_data))
	{
		$product_id = $row['ID'];
		$product_image = $row['filename_image'];
		$product_name = $row['name'];
		$product_price = $row['price'];
		echo '<li>';
		echo '<a href="?page='.$product_id.'"><img src="'.$product_image.'" alt="'.$product_name.'"/></a>';
		echo '<div>'.$product_name.'</div></li>';
		echo '<div>'.$product_price.'</div></li>';
	}
}

//showProductDetail
function showProductDetail() {
	$id = $_GET['page'];
	$data = getSpecificData('product', 'ID', $id);
	$product = mysqli_fetch_array($data);
	$image = $product['filename_image'];
	$name = $product['name'];
	$price = $product['price'];
	$descr = $product['item_description'];
	echo '<li>';
	echo '<img src="'.$image.'" alt="'.$name.'"/>';
	echo '<div>'.$name.'</div></li>';
	echo '<div>'.$price.'</div></li>';
	echo '<div>'.$descr.'</div></li>';
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
	showHeader();
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
		case 'Webshop';
		  showWebshopPage();
		  break;
		case in_array($data, range(0, 20));
		  showProductDetail();
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
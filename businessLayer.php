<?php
//data_layer
require 'dataLayer.php';

//variabelen
$salErr = $namErr = $emailErr = $phonErr = $comprefErr = $messErr = $pwErr = $pwRepeatErr = "";

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
		$data = getRowData('users', 'email', $email);
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
				} elseif ($user['email'] == $email) {
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
		$data = getRowData('users', 'email',$email);
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
			$_SESSION['user_id'] = $user['ID'];
		    $_SESSION['email'] = $user['email'];
		    $_SESSION['name'] = $user['name'];
		    return True;
		}
	}
}

//logOutUser
function logOutUser() {
	session_unset();
	session_destroy();
}

//addToCart
function addToCart(){
	if(isset($_POST['add'])){
		$id = testInput($_POST['add']);
		if (!isset($_SESSION['cart'])) {
			$data = getColumnData('product', 'ID');
			$_SESSION['cart'] = array();
			while ($row = mysqli_fetch_array($data)) {
				$single_id = $row['ID'];
				$_SESSION['cart'][$single_id] = '0';
			}
		}
		if ($_SESSION['cart'][$id] >= 0) {
			$qty = $_SESSION['cart'][$id];
			$qty++;
			$_SESSION['cart'][$id] = $qty;
		}
	}
	unset($_POST['add']);
}

//updateCart
function updateCart() {
	if(isset($_POST['CartID']) && isset($_POST['amountCart'])) {
		$item_id = testInput($_POST['CartID']);
		$amount = testInput($_POST['amountCart']);
		$_SESSION['cart'][$item_id] = $amount;
		unset($_POST['CartID']);
		unset($_POST['amountCart']);
		return False;
	}
	if(isset($_POST['termAgree'])) {
		registerOrder();
		return True;
	}
}

?>
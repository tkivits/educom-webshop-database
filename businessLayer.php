<?php
//testInput
function testInput($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

//logError
function logError($msg) {
	echo "LOG TO SERVER: ".$msg;
}

//setTotalArray
function setSessionTotal() {
	if (!isset($_SESSION['total'])) {
		$_SESSION['total'] = array();
	} else {
		unset($_SESSION['total']);
		$_SESSION['total'] = array();
	}
}

//testContact
function testContact() {
	$salErr = $namErr = $emailErr = $phonErr = $comprefErr = $messErr = "";
	$sal = $name = $email = $phone = $compref = $mess = "";
	$valid = False;
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
		$valid = True;
      }
	  return array('sal' => $sal, 'salErr' => $salErr, 'name' => $name, 'namErr' => $namErr, 'email' => $email, 'emailErr' => $emailErr, 'phone' => $phone, 'phonErr' => $phonErr, 'compref' => $compref, 'comprefErr' => $comprefErr, 'mess' => $mess, 'messErr' => $messErr, 'valid' => $valid);
	}
}

//checkRegistration
function checkRegistration() {
	$namErr = $emailErr = $pwErr = $pwRepeatErr = "";
	$name = $email = $pw = $pwrepeat = "";
	$valid = False;
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		$name = testInput($_POST['name']);
		$email = testInput($_POST['email']);
		$pw = testInput($_POST['pw']);
		$pwrepeat = testInput($_POST['pwrepeat']);
	    if (empty($_POST["name"])) {
		    $namErr = "Name is required";
	    } elseif (!preg_match("/^[a-zA-Z-' ]*$/",$name)) {
            $namErr = "Only letters and spaces are allowed";
		}
	    if (empty($_POST["email"])) {
		    $emailErr = "E-mail is required";
			} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$emailErr = "Invalid e-mail";
				}
	    if (empty($_POST["pw"])) {
		    $pwErr = "Password is required";
		}
	    if (empty($_POST["pwrepeat"])) {
		    $pwRepeatErr = "Please repeat your password";
	    } elseif ($pw !== $pwrepeat) {
			$pwRepeatErr = "Entered passwords do not match";
		}
		$user = findUserByEmail($email);
		if (isset($user['email'])) {
			$emailErr = "E-mail already exists";
		}
		if (empty($namErr) && empty($emailErr) && empty($pwErr) && empty($pwRepeatErr)) {
			try {
				registerNewUser($email, $name, $pw);
		    	$valid = True;
			} catch (Exception $e) {
				logError($e);
			}
		}
		return array('name' => $name, 'namErr' => $namErr, 'email' => $email, 'emailErr' => $emailErr, 'pw' => $pw, 'pwErr' => $pwErr, 'pwRepeat' => $pwrepeat, 'pwRepeatErr' => $pwRepeatErr, 'valid' => $valid);
	} 
}

//logInUser
function logInUser() {
	$emailErr = $pwErr = "";
	$email = $pw = "";
	$valid = False;
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		$email = testInput($_POST['email']);
		$pw = testInput($_POST['pw']);
	    if (empty($email)) {
		    $emailErr = "E-mail is required";
	    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$emailErr = "Invalid e-mail";
		}
	    if (empty($pw)) {
		    $pwErr = "Password is required";
		}
		if(empty($emailErr) && empty($pwErr)) {
			try {
				$user = findUserByEmail($email);

				if (empty($user) || $user['email'] !== $email) {
					$emailErr= "Unknown e-mail";
				}
				if (empty($user) || $user['password'] !== $pw) {
					$pwErr = "E-mail doesn't match password";
				}
			} catch (Exception $e) {
				logError($e);
			}
		}
	    if(empty($emailErr) && empty($pwErr)) {
		    $_SESSION['login'] = True;
			$_SESSION['user_id'] = $user['ID'];
		    $_SESSION['email'] = $user['email'];
		    $_SESSION['name'] = $user['name'];
		    $valid = True;
		}
		return array('email' => $email, 'emailErr' => $emailErr, 'pw' => $pw, 'pwErr' => $pwErr, 'valid' => $valid);
	}
}

//logOutUser
function logOutUser() {
	session_unset();
	session_destroy();
}

//addToCart
function addToCart(){
	if(isset($_POST['action'])){
		$id = testInput($_POST['CartID']);
		if (!isset($_SESSION['cart'])) {
			try {
				$products = getAllProducts();
				$_SESSION['cart'] = array();
				while ($row = mysqli_fetch_array($products)) {
					$single_id = $row['ID'];
					$_SESSION['cart'][$single_id] = 0;
				}
			} catch (Exception $e) {
				logError($e);
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
	$valid = "";
	if(isset($_POST['CartID']) && $_POST['action'] == 'updateCart') {
		$item_id = testInput($_POST['CartID']);
		$amount = testInput($_POST['amountCart']);
		$_SESSION['cart'][$item_id] = $amount;
		unset($_POST['CartID']);
		unset($_POST['amountCart']);
		$valid = False;
	}
	if(isset($_POST['termAgree'])) {
		registerOrder();
		$valid = True;
	}
	return array('valid' => $valid);
}

?>
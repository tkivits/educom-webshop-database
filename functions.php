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
function showHeader($page) {
	if ($page == 'Product') {
		echo '<h1 class="header">Webshop</h1>';
	} else {
		echo '<h1 class="header">'.$page.'</h1>';
	}
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
	addToCart();
}

//showShoppingCart
function showShoppingCart() {
	echo '<div class="cartcontainer">';
	if (!isset($_SESSION['cart'])) {
		echo '<div class="title"> Your shopping cart is empty!</div>';
		echo '<div class="cart">You can buy products in our <a href="?page=Webshop">webshop</a></div>';
	} else {
		showItemsCart($_SESSION['cart']);
	}
	echo '</div>';
}

//showShoppingCartPage
function showShoppingCartPage() {
	showShoppingCart();
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
	}
}


//showItemsCart
function showItemsCart($array){
	$items = array_filter($array);
	foreach ($items as $id => $amount) {
		$data = getSpecificData('product', 'ID', $id);
		$item = mysqli_fetch_array($data);
		$item_id = $item['ID'];
		$image = $item['filename_image'];
		$name = $item['name'];
		$price = $item['price'];
		$item_total = number_format($amount * $price, 2);
		if (isset($_POST['amount'])) {
			$amount = htmlspecialchars($_POST['amount']);
		}
		echo '<div class="cartitems">';
		echo '<div class="imagecontainer"><a href="?page='.$item_id.'"><img class="productimg" src="'.$image.'" alt="'.$name.'"/></a></div>';
		echo '<div class="about">';
		echo '<div class="itemtitle">'.$name.'</div>';
		echo '<div class="itemprice">'.$price.'</div>';
		echo '</div>';
		echo '<div class="countcontainer">';
		echo '<form method="post">';
		echo '<textarea class="count" id="amountCart" name="amountCart">'.$amount.'</textarea>';
		echo '<input type="hidden" name="CartID" value="'.$item_id.'">';
		echo '<input class="cartButton" type="submit" value="Update"></div>';
		echo '</form>';
		echo '<div class="pricetotalcontainer"><div class="priceitemtotal">'.$item_total.'</div></div>';
		echo '</div>';
	}
}

//showProductOverview
function showProductOverview() {
	$product_data = getData('product');
	while ($row = mysqli_fetch_array($product_data))
	{
		$item_id = $row['ID'];
		$image = $row['filename_image'];
		$name = $row['name'];
		$price = $row['price'];
		echo '<form class="menu" method="post">';
		echo '<a href="?page='.$item_id.'"><img class="productimg" src="'.$image.'" alt="'.$name.'"/></a>';
		echo '<div class="title">'.$name.'</div>';
		echo '<div class="price">'.$price.'</div>';
		if (isset($_SESSION['login'])){
			echo '<input class="cartButton" type="submit" value="Add to cart">';
			echo '<input type="hidden" name="add" value="'.$item_id.'">';
		}
		echo '</form>';
	}
}

//showProductDetail
function showProductDetail() {
	$item_id = $_GET['page'];
	$data = getSpecificData('product', 'ID', $item_id);
	$row = mysqli_fetch_array($data);
	$item_id = $row['ID'];
	$image = $row['filename_image'];
	$name = $row['name'];
	$price = $row['price'];
	$descr = $row['item_description'];
	echo '<form class="menu" method="post">';
	echo '<img class="productimg" src="'.$image.'" alt="'.$name.'"/>';
	echo '<div class="title">'.$name.'</div></li>';
	echo '<div class="price">'.$price.'</div></li>';
	echo '<div>'.$descr.'</div></li>';
	if (isset($_SESSION['login'])) {
		echo '<input class="cartButton" type="submit" value="Add to cart">';
		echo '<input type="hidden" name="add" value="'.$item_id.'">';
	}
	echo '</form>';
	addToCart();
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
		case 'Cart';
		updateCart();
		$page = 'Cart';
		break;
		case is_numeric($page);
		$page = 'Product';
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
	showHeader($data);
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
		case 'Product';
		  showProductDetail();
		  break;
		case 'Cart';
		  showShoppingCartPage();
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
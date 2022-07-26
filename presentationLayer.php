<?php
//Prerequisites
session_start();
require 'businessLayer.php';
require 'dataLayer.php';

//showBodyStart
function showDocStart() {
    echo 
        '<!DOCTYPE html>
        <html>
        <head>
        <link rel="stylesheet" href="CSS/stylesheet.css">
        </head>
        <body>';
}

//showBodyEnd
function showDocEnd() {
    echo 
        '</body>
        </html>';
}

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
	include 'Pages/menu.php';
}

//showFooter
function showFooter() {
	include 'Pages/footer.php';
}

//showHomePage
function showHomePage() {
	include 'Pages/home.php';
}

//showAboutpage
function showAboutPage() {
	include 'Pages/about.php';
}

//showContactPage
function showContactPage($data) {
	include 'Pages/contact.php';
}

//showContactThanksPage
function showContactThanksPage($data) {
	include 'Pages/contactthanks.php';
}

//showProductOverview
function showProductOverview() {
	$products = getAllProducts();
	while ($product = mysqli_fetch_array($products))
	{
		echo '<form class="menu" method="post">';
		echo '<a href="?page='.$product['ID'].'"><img class="productimg" src="'.$product['filename_image'].'" alt="'.$product['name'].'"/></a>';
		echo '<div class="title">'.$product['name'].'</div>';
		echo '<div class="price">'.$product['price'].'</div>';
		if (isset($_SESSION['login'])){
			echo '<input class="cartButton" type="submit" value="Add to cart">';
			echo '<input type="hidden" name="add" value="'.$product['ID'].'">';
		}
		echo '</form>';
	}
}

//showWebshopPage
function showWebshopPage() {
	showProductOverview();
	addToCart();
}

//showProductDetail
function showProductDetail() {
	$itemid = $_GET['page'];
	$data = getSingleProduct($itemid);
	$product = mysqli_fetch_array($data);
	echo '<form class="menu" method="post">';
	echo '<img class="productimg" src="'.$product['filename_image'].'" alt="'.$product['name'].'"/>';
	echo '<div class="title">'.$product['name'].'</div></li>';
	echo '<div class="price">'.$product['price'].'</div></li>';
	echo '<div>'.$product['item_description'].'</div></li>';
	if (isset($_SESSION['login'])) {
		echo '<input class="cartButton" type="submit" value="Add to cart">';
		echo '<input type="hidden" name="add" value="'.$product['ID'].'">';
	}
	echo '</form>';
	addToCart();
}

//showRegisterPage
function showRegisterPage($data) {
	include 'Pages/register.php';
}

//showLoginPage
function showLoginPage($data) {
	include 'Pages/login.php';
}

//showItemsCart
function showItemsCart($array){
	$items = array_filter($array);
	if (!isset($_SESSION['total'])) {
		$_SESSION['total'] = array();
	} else {
		unset($_SESSION['total']);
		$_SESSION['total'] = array();
	}
    $products = getAllProducts();
    while ($product = mysqli_fetch_array($products))
    {
        if (array_key_exists($product['ID'], $items)) {
		    $item_total = number_format($items[$product['ID']] * $product['price'], 2);
		    echo '<div class="cartitems">';
		    echo '<div class="imagecontainer"><a href="?page='.$product['ID'].'"><img class="productimg" src="'.$product['filename_image'].'" alt="'.$product['name'].'"/></a></div>';
		    echo '<div class="about">';
		    echo '<div class="itemtitle">'.$product['name'].'</div>';
		    echo '<div class="itemprice">'.$product['price'].'</div>';
		    echo '</div>';
		    echo '<div class="countcontainer">';
		    echo '<form method="post">';
		    echo '<input type="number" class="count" id="amountCart" name="amountCart" value="'.$items[$product['ID']].'">';
		    echo '<input type="hidden" name="CartID" value="'.$product['ID'].'">';
		    echo '<input class="cartButton" type="submit" value="Update">';
		    echo '</form></div>';
		    echo '<div class="pricetotalcontainer"><div class="priceitemtotal">'.$item_total.'</div></div>';
		    echo '</div>';
		    array_push($_SESSION['total'], $item_total);
        }
	}
}

//showCheckoutButton
function showPlaceOrder() {
	if (isset($_SESSION['total'])) {
		$total = number_format(array_sum($_SESSION['total']), 2);
		echo '<div class="price">Total amount: '.$total.'</div>';
		echo '<div>';
		echo '<form method="post">';
		echo '<div class="accept"><input type="checkbox" id="agree" name="termAgree" value="agree"><label for="agree">I accept all terms & conditions</label></div>';
		echo '<input class="cartButton" type="submit" value="Place order!">';
		echo '<input type="hidden" name="placeOrder>';
		echo '</form>';
		echo '</div>';
	}
}

//showShoppingCart
function showShoppingCartPage() {
	echo '<div class="cartcontainer">';
	if (!isset($_SESSION['cart'])) {
		include 'Pages/emptycartPage.php';
	} else {
		showItemsCart($_SESSION['cart']);
        showPlaceOrder();
	}
	echo '</div>';

}

//showYourOrder
function showYourOrder(){
	unset($_SESSION['cart']);
	unset($_SESSION['total']);
	echo '<div class="title">Thank you for your order!</div>';
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
		if (isset($data['valid']) && $data['valid'] == True) {
			$page = 'Thanks';
		}
		break;
		case 'Register';
		$data = checkRegistration();
		if (isset($data['valid']) && $data['valid'] == True) {
			$page = 'Login';
		}
		break;
		case 'Login';
		$data = logInUser();
		if (isset($data['valid']) && $data['valid'] == True) {
			$page = 'Home';
		}
		break;
		case 'Cart';
		$data = updateCart();
		if ($data == True) {
			$page = 'YourOrder';
		} else {
			$page = 'Cart';
		}
		break;
		case is_numeric($page);
		$page = 'Product';
		break;
		case 'Logout';
		logOutUser();
		$page = 'Home';
		break;
	}
	$data['page'] = $page;
	return $data;
}

//showResponsePage
function showResponsePage($data){
    showDocStart();
	showHeader($data['page']);
	showMenu();
	switch($data['page'])
	{
		case 'Home';
		  showHomePage();
		  break;
		case 'About';
		  showAboutPage();
		  break;
		case 'Contact';
		  showContactPage($data);
		  break;
		case 'Thanks';
		  showContactThanksPage($data);
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
		case 'YourOrder';
		  showYourOrder();
		  break;
		case 'Register';
		  showRegisterPage($data);
		  break;
		case 'Login';
		  showLoginPage($data);
		  break;
		default; 
		  showHomePage();
	}
	showFooter();
    showDocEnd();
}

?>
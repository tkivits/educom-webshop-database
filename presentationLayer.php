<?php

require 'businessLayer.php';

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

//showHomePage
function showHomePage() {
	include 'Pages/home.php';
}

//showAboutpage
function showAboutPage() {
	include 'Pages/about.php';
}

//showContactPage
function showContactPage() {
	global $salErr, $namErr, $emailErr, $phonErr, $comprefErr, $messErr;
	include 'Pages/contact.php';
}

//showContactThanksPage
function showContactThanksPage() {
	include 'Pages/contactthanks.php';
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
		include 'Pages/emptycartPage.php';
	} else {
		showItemsCart($_SESSION['cart']);
	}
	echo '</div>';

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

//showShoppingCartPage
function showShoppingCartPage() {
	showShoppingCart();
	showPlaceOrder();
}

//showYourOrder
function showYourOrder(){
	unset($_SESSION['cart']);
	unset($_SESSION['total']);
	echo '<div class="title">Thank you for your order!</div>';
}

//showRegisterPage
function showRegisterPage() {
	global $namErr, $emailErr, $pwErr, $pwRepeatErr;
	include 'Pages/register.php';
}

//showLoginPage
function showLoginPage() {
	global $emailErr, $pwErr;
	include 'Pages/login.php';
}

//showFooter
function showFooter() {
	include 'Pages/footer.php';
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
	foreach ($items as $id => $amount) {
		$data = getRowData('product', 'ID', $id);
		$item = mysqli_fetch_array($data);
		$item_id = $item['ID'];
		$image = $item['filename_image'];
		$name = $item['name'];
		$price = $item['price'];
		$item_total = number_format($amount * $price, 2);
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
		echo '<input class="cartButton" type="submit" value="Update">';
		echo '</form></div>';
		echo '<div class="pricetotalcontainer"><div class="priceitemtotal">'.$item_total.'</div></div>';
		echo '</div>';
		array_push($_SESSION['total'], $item_total);
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
	$data = getRowData('product', 'ID', $item_id);
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
		case 'YourOrder';
		  showYourOrder();
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
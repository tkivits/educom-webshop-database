<ul class="menu">
  <li><a href="?page=Home">Home</a></li>
  <li><a href="?page=About">About</a></li>
  <li><a href="?page=Contact">Contact</a></li>
  <li><a href="?page=Webshop">Webshop</a></li>
  <?php if (!isset($_SESSION['login'])) { ?>
    <li><a href="?page=Register">Register</a></li>
    <li><a href="?page=Login">Login</a></li>
  <?php } else { ?>
    <li><a href="?page=Cart">Cart</a></li>
    <li><a href="?page=Logout">Logout <?php echo $_SESSION['name'] ?></a></li>
  <?php } ?>
</ul>
<!DOCTYPE html>
<html>
<body>

<form class="form" method="post" action="<?php echo htmlspecialchars('?page=Login');?>">
  <div><label for="email">E-mail:</label>
    <input type="email" id="email" name="email" value="<?php if (isset($_POST['email'])) {
		echo $_POST['email'];
	}?>">
    <span class="error">* <?php echo $emailErr;?></span></div>
  <div><label for="password">Password:</label>
    <input type="password" id="pw" name="pw" value="<?php if (isset($_POST['pw'])) {
		echo $_POST['pw'];
	}?>">
    <span class="error">* <?php echo $pwErr;?></span></div>
<input type="submit" value="Login">

</body>
</html>
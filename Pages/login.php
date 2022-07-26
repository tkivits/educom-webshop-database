<form class="form" method="post" action="<?php echo htmlspecialchars('?page=Login');?>">
  <div><label for="email">E-mail:</label>
    <input type="email" id="email" name="email" value="<?php if (isset($data['email'])) {
		echo $data['email'];
	}?>">
    <span class="error">* <?php if (isset($data['emailErr'])) {echo $data['emailErr'];}?></span></div>
  <div><label for="password">Password:</label>
    <input type="password" id="pw" name="pw" value="<?php if (isset($data['pw'])) {
		echo $data['pw'];
	}?>">
    <span class="error">* <?php if (isset($data['pwErr'])) {echo $data['pwErr'];}?></span></div>
<input type="submit" value="Login">
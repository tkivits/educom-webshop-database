
<div><span class="error">Fields with a * are required</span></div> 
<form class="form" method="post" action="<?php echo htmlspecialchars('?page=Register');?>">
  <div><label for="name">Name:</label>
    <input type="text" id="name" name="name" value="<?php if (isset($data['name'])) {
		echo $data['name'];
	};?>">
    <span class="error">* <?php if (isset($data['namErr'])) {echo $data['namErr'];} ?></span></div>
  <div><label for="email">E-mail:</label>
    <input type="email" id="email" name="email" value="<?php if (isset($data['email'])) {
		echo $data['email'];
	};?>">
    <span class="error">* <?php if (isset($data['emailErr'])) {echo $data['emailErr'];} ?></span></div>
  <div><label for="password">Password:</label>
    <input type="password" id="pw" name="pw" value="<?php if (isset($data['pw'])) {
		echo $data['pw'];
	};?>">
    <span class="error">* <?php if (isset($data['pwErr'])) {echo $data['pwErr'];} ?></span></div>
  <div><label for="password repeat">Repeat password:</label>
    <input type="password" id="pwrepeat" name="pwrepeat" value="<?php if(isset($data['pwrepeat'])) {
		echo $data['pwrepeat'];
	};?>">
    <span class="error">* <?php if (isset($data['pwRepeatErr'])) {echo $data['pwRepeatErr'];} ?></span></div>
  <input type="submit" value="Register">
</form>
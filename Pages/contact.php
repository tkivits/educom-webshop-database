<div><span class="error">Fields with a * are required!</span></div>
<form class="form" method="post" action="<?php echo htmlspecialchars('?page=Contact');?>">
<div><label for="salutation"></label>
<select id="sal" name="sal">
<option value="">Choose</option>
<option value="Mr." <?php if (isset($data['sal']) && $data['sal'] == "Mr.") echo "selected";?>>Mr.</option>
<option value="Mrs"<?php if (isset($data['sal']) && $data['sal'] == "Mrs") echo "selected";?>>Mrs</option>
</select>
<span class="error">* <?php if (isset($data['salErr'])) {echo $data['salErr'];} ?></span></div>
<div><label for="name">Name:</label>
<input type="text" id="name" name="name" value="<?php if (isset($data['name'])) {echo $data['name'];} elseif (isset($_SESSION['name'])) {echo $_SESSION['name'];}?>">
<span class="error">* <?php if(isset($data['namErr'])) {echo $data['namErr'];}?></span></div>
<div><label for="email">E-mail:</label><input type="email" id="email" name="email" value="<?php if (isset($data['email'])) {echo $data['email'];} elseif (isset($_SESSION['email'])) {echo $_SESSION['email'];}?>">
<span class="error">* <?php if (isset($data['emailErr'])) {echo $data['emailErr'];}?></span></div>
<div><label for="phone">Phone number:</label>
<input type="tel" id="phone" name="phone" value="<?php if (isset($data['phone'])) {echo $data['phone'];}?>">
<span class="error">* <?php if (isset($data['phonErr'])) {echo $data['phonErr'];}?></span></div>
<div><label for="compref">What is your communication preference?</label>
<input type="radio" id="email" name="compref" <?php if (isset($data['compref']) && $data['compref'] == "E-mail") echo "checked";?> value="E-mail">
<label for="email">E-mail</label>
<input type="radio" id="telephone" name="compref" <?php if (isset($data['compref']) && $data['compref'] == "Telephone") echo "checked";?> value="Telephone">
<label for="telephone">Telephone</label>
<span class="error">* <?php if (isset($data['comprefErr'])) {echo $data['comprefErr'];}?></span></div>
<div><textarea id="mess" name="mess" rows="8" cols="50" placeholder= "Tell us why you want to contact us!"><?php if (isset($data['mess'])) {echo $data['mess'];};?></textarea>
<span class="error">* <?php if (isset($data['messErr'])) {echo $data['messErr'];} ?></div>
<input type="submit" value="Submit">
</form>
<?php
session_start()
?>

<!DOCTYPE html>

<html>
<body>

<?php 
if (empty($_SESSION['login'])) {
	echo '<meta http-equiv="refresh" content="0; URL=?page=Home">';
} else {
	session_unset();
	echo '<meta http-equiv="refresh" content="0; URL=">';
} ?>

</body>
</html>
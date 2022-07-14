<?php
session_start()
?>

<!DOCTYPE html>

<html>
<body>
<ul class="menu">
  <li><a href="?page=Home">Home</a></li>
  <li><a href="?page=About">About</a></li>
  <li><a href="?page=Contact">Contact</a></li>
  <?php if (!$_SESSION['login']) { ?>
  <li><a href="?page=Register">Register</a></li>
  <li><a href="?page=Login">Login</a></li>
  <?php } else { ?>
  <li><a href="?page=Logout">Logout <?php echo $_SESSION['name'] ?></a></li>
  <?php } ?>
</ul>

<?php echo "My name is Teun Kivits. I am 28 years old and i live together with my girlfriend and our two cats.<br>Below you can see a list of some of my hobby's:
<ul>
  <li>Strength sports like powerlifting and strongman</li>
  <li>Listening to music</li>
  <li>Going out with friends</li>
  <li>Walks</li>
</ul>
Recently, I've started a traineeship at Educom where i'm learning to build websites like this one. Hopefully this will become a functional webshop!<br>
Right now i'm still learning though, as you can clearly see on this site."; ?>

</body>
</html>

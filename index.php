<!DOCTYPE html>

<html>
<head>
<link rel="stylesheet" href="CSS/stylesheet.css">
</head>
<body>

<?php

require "functions.php";

$page = getRequestedPage();
$data = processRequest($page);
showResponsePage($data);

?> 

</body>
</html>
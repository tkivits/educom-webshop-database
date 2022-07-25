<?php

require "presentationLayer.php";

$page = getRequestedPage();
$data = processRequest($page);
showResponsePage($data);

?>
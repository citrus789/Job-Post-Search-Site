<?php
session_start();
$_SESSION['loggedin'] = false;
unset($_SESSION["Id"]);
unset($_SESSION["Email"]);
header("Location:loginpage.php");
?>

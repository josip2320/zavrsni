<?php

session_start();
 
// Postavljanje početnih vrijednosti sesije
$_SESSION = array();
 
// Uništavanje sesije
session_destroy();
 
// Preusmjeravanje na stranicu za prijavu
header("location: login.php");
exit;
?>
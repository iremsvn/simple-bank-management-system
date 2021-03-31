<?php

@ob_start();
session_start();

$_SESSION = array();

session_destroy();

echo "<script LANGUAGE='JavaScript'>
          window.alert('See you later.');
          window.location.href='index.php';
       </script>";

exit;
?>
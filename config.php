<?php

   define('DB_SERVER', 'dijkstra.ug.bcc.bilkent.edu.tr');
   define('DB_USERNAME', '***');
   define('DB_PASSWORD', '***');
   define('DB_DATABASE', '***');
   $db = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);

if ($db->connect_errno) {
    die("failed !!! " . mysqli_connect_error());
}
?>


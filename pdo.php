<?php
 //In development
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=foodshala',
   'ragini', 'gaurav');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

?>


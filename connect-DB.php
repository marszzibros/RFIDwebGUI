<?php
$databaseName = 'JJUNG2_RFID_TEST';
$dsn = 'mysql:host=webdb.uvm.edu;dbname='. $databaseName;
$username = 'jjung2_admin';
$password = 'UzAn4dsM6VIZigk1';

$pdo = new PDO($dsn, $username, $password);
?>
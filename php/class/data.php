<?php

$server = '192.168.0.194:5100';
$username = 'ucid';
$password = '1974';
$database = 'qc';

try {
  $conn = new PDO("mysql:host=$server;dbname=$database;", $username, $password);
} catch (PDOException $e) {
  die('Connection Failed: ' . $e->getMessage());
}

?>

<?php
$host = 'localhost';
$user = 'root';
$database = 'ecommerce';
$password = '';


try{
$pdo = new PDO(
    "mysql:host=$host;dbname=$database", $user, $password
);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $a){
    die("erreur de connexion : ".$a->getMessage());
}
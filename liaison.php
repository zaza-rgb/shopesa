<?php
$host = 'localhost';
$user = 'root';
$database = 'ecommerce';
$password = '';


try{
$com= new PDO(
    "mysql:host=$host; dbname=$database",$user,$password
);
}catch(PDOException $a){
    die("erreur de connexion".$a->getMessage());
}
?>
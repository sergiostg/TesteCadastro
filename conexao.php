<?php


$host = "172.17.0.3";
$user = "root";
$password = "200501";
$dbname = "apimysql";
try {
    $coon = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $coon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage();
}
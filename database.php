<?php

try {
    $pdo = new PDO("mysql:host=localhost;dbname=quiz", "root", "", array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

return $pdo;

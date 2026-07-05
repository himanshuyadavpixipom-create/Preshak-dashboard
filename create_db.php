<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306', 'root', '');
    $pdo->exec('CREATE DATABASE IF NOT EXISTS preshak_crm');
    echo "Database preshak_crm created or already exists.\n";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
    exit(1);
}

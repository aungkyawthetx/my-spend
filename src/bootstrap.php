<?php

    // session_start();
    require_once __DIR__ . '/../config/db.php';

    try {
        $pdo = new PDO(
            "mysql:host={$DB_HOST};port=3306;dbname={$DB_NAME};charset=utf8mb4", 
            $DB_USER, 
            $DB_PASS, 
            [
                PDO::ATTR_ERRMODE=> PDO::ERRMODE_EXCEPTION, 
                PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC
            ]
        );
    } catch (PDOException $e) {
        die("DB connection failed: " . $e->getMessage());
    }

?>
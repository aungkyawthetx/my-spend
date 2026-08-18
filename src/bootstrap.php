<?php
    require_once __DIR__ . '/../config/db.php';
    require_once __DIR__ . '/helpers/errors.php';

    date_default_timezone_set('Asia/Yangon');

    registerErrorHandlers();

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
        abortWithError('Database connection failed. Please try again later.', $e);
    }

    if (!function_exists('tableHasColumn')) {
        function tableHasColumn(PDO $pdo, string $table, string $column): bool {
            static $cache = [];
            $key = $table . '.' . $column;

            if (array_key_exists($key, $cache)) {
                return $cache[$key];
            }

            $stmt = $pdo->prepare("
                SELECT 1
                FROM INFORMATION_SCHEMA.COLUMNS
                WHERE TABLE_SCHEMA = DATABASE()
                  AND TABLE_NAME = :table_name
                  AND COLUMN_NAME = :column_name
                LIMIT 1
            ");
            $stmt->execute([
                ':table_name' => $table,
                ':column_name' => $column,
            ]);

            $cache[$key] = (bool) $stmt->fetchColumn();
            return $cache[$key];
        }
    }

?>

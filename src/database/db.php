<?php
// Debug: Cek autoload
$autoload_path = __DIR__ . '/../../vendor/autoload.php';
if (!file_exists($autoload_path)) {
    error_log("Autoload tidak ditemukan di: " . $autoload_path);
    throw new Exception("Composer autoload tidak ditemukan");
}

require_once $autoload_path;

// Debug: Cek .env file
$env_path = __DIR__ . '/../../.env';
if (!file_exists($env_path)) {
    error_log(".env file tidak ditemukan di: " . $env_path);
    throw new Exception(".env file tidak ditemukan");
}

try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
    $dotenv->load();
} catch (Exception $e) {
    error_log("Error loading .env: " . $e->getMessage());
    throw new Exception("Error loading environment variables: " . $e->getMessage());
}

// Debug: Cek environment variables
$host = $_ENV['DB_HOST'] ?? null;
$dbname = $_ENV['DB_NAME'] ?? null;
$user = $_ENV['DB_USER'] ?? null;
$pass = $_ENV['DB_PASS'] ?? null;

if (!$host || !$dbname || !$user) {
    error_log("Missing environment variables");
    error_log("DB_HOST: " . ($host ? 'OK' : 'MISSING'));
    error_log("DB_NAME: " . ($dbname ? 'OK' : 'MISSING'));
    error_log("DB_USER: " . ($user ? 'OK' : 'MISSING'));
    throw new Exception("Missing required database environment variables");
}

try {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    
    error_log("Database connection successful");
    
} catch (PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    
    // Untuk web request, simpan di session
    if (session_status() === PHP_SESSION_ACTIVE) {
        $_SESSION['login_error'] = 'Database connection failed';
    }
    
    throw new Exception('Database connection failed: ' . $e->getMessage());
}
?>
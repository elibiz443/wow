<?php
declare(strict_types=1);

$hostName = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? '';
$hostName = preg_replace('/:\d+$/', '', $hostName);
$isLocal = in_array($hostName, ['localhost', '127.0.0.1'], true);

$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
  (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ||
  (isset($_SERVER['SERVER_PORT']) && (int)$_SERVER['SERVER_PORT'] === 443);

if (!defined('ROOT_URL')) {
  define('ROOT_URL', $isLocal ? 'http://localhost/wow' : 'https://wow.ellyambet.com');
}

if (!defined('ROOT_PATH')) {
  define('ROOT_PATH', __DIR__);
}

ini_set('display_errors', $isLocal ? '1' : '0');
ini_set('display_startup_errors', $isLocal ? '1' : '0');
error_reporting($isLocal ? E_ALL : 0);

if (session_status() !== PHP_SESSION_ACTIVE) {
  ini_set('session.use_strict_mode', '1');
  ini_set('session.use_only_cookies', '1');
  session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => $isHttps,
    'httponly' => true,
    'samesite' => 'Lax'
  ]);
  session_start();
}

if ($isLocal) {
  define('DB_HOST', 'localhost');
  define('DB_NAME', 'wow_db');
  define('DB_USER', 'root');
  define('DB_PASS', '');
} else {
  define('DB_HOST', 'localhost');
  define('DB_NAME', 'ellyamb1_wow');
  define('DB_USER', 'ellyamb1_wow');
  define('DB_PASS', 'MaZQtZmhuwdBRFPbWLay');
}

try {
  $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
  ]);
} catch (PDOException $e) {
  die("Connection failed: " . $e->getMessage());
}

require_once ROOT_PATH . '/db/dbsetup.php';
db_init($pdo);
db_seed_default_user($pdo);

$message = null;
if (isset($_SESSION['message'])) {
  $message = $_SESSION['message'];
  unset($_SESSION['message']);
}

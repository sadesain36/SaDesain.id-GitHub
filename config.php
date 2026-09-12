<?php
declare(strict_types=1);
$DB_HOST = getenv('SADESAIN_DB_HOST') ?: '127.0.0.1';
$DB_NAME = getenv('SADESAIN_DB_NAME') ?: 'sadesain';
$DB_USER = getenv('SADESAIN_DB_USER') ?: 'root';
$DB_PASS = getenv('SADESAIN_DB_PASS') ?: '';
try {
    $pdo = new PDO("mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4", $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    die('Koneksi database gagal. Pastikan MySQL aktif dan database sadesain sudah dibuat.');
}
function e(?string $value): string { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
function secure_session_start(): void {
    if (session_status() === PHP_SESSION_ACTIVE) return;
    $secure = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    session_set_cookie_params(['httponly'=>true,'secure'=>$secure,'samesite'=>'Strict','path'=>'/']);
    session_start();
}
function csrf_token(): string {
    secure_session_start();
    if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf_token'];
}
function verify_csrf(?string $token): void {
    secure_session_start();
    if (!$token || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
        http_response_code(403); die('Permintaan ditolak: token keamanan tidak valid. Silakan muat ulang halaman.');
    }
}

<?php
$host = '127.0.0.1';
$db   = 'infuse';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
     $pdo = new PDO($dsn, $user, $pass);
} catch (\PDOException $e) {
     throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

$url =  $_SERVER['HTTP_REFERER'] ?? $_SERVER['REQUEST_URI'];
$ip =  $_SERVER['REMOTE_ADDR'];
$agent = $_SERVER['HTTP_USER_AGENT'];
$date = date("Y-m-d H:i:s");
$stmt = $pdo->prepare('UPDATE user_views SET views_count = views_count + 1, view_date = ? WHERE page_url = ? AND ip_address = ? AND user_agent = ?');
$stmt->execute([$date, $url, $ip, $agent]);

if ($stmt->rowCount() === 0) {
    $stmt = $pdo->prepare('INSERT INTO user_views (view_date, views_count, page_url, ip_address, user_agent) VALUES(?, 1, ?, ?, ?)');
    $stmt->execute([$date, $url, $ip, $agent]);
} 

return;
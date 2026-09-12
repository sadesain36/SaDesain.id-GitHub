<?php
require_once __DIR__ . '/../config.php';
secure_session_start();
if (empty($_SESSION['admin_id'])) { header('Location: login.php'); exit; }
$adminStmt=$pdo->prepare('SELECT * FROM admins WHERE id=?'); $adminStmt->execute([(int)$_SESSION['admin_id']]);
$admin=$adminStmt->fetch();
if (!$admin) { session_unset(); session_destroy(); header('Location: login.php'); exit; }
function admin_header(string $title, string $active='dashboard'): void {
    global $admin;
    $links=['dashboard'=>['Dashboard','dashboard.php'],'pesanan'=>['Pesanan','dashboard.php#pesanan'],'settings'=>['Website','settings.php'],'portfolio'=>['Portfolio','portfolio.php'],'designers'=>['Designer','designers.php'],'members'=>['Anggota','members.php'],'security'=>['Keamanan','security.php']];
    echo '<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex,nofollow"><title>'.e($title).' — SaDesain.id</title><link rel="stylesheet" href="admin.css"></head><body class="dash"><aside><div class="sidebrand"><img src="../assets/logo.png" alt="Logo"><b>SaDesain.id</b></div>';
    foreach($links as $key=>$v) echo '<a class="'.($active===$key?'active':''). '" href="'.e($v[1]).'">'.e($v[0]).'</a>';
    echo '<a href="../" target="_blank" rel="noopener">Website Publik ↗</a><a href="logout.php">Logout</a></aside><main><header class="top"><div><span class="eyebrow">ADMIN PRIVAT</span><h1>'.e($title).'</h1><p>Area khusus administrator. Tidak ditampilkan di website publik.</p></div><div class="profile"><img src="../'.e($admin['photo']??'assets/admin.png').'" alt="Admin"><span>'.e($admin['name']).'</span></div></header>';
}
function admin_footer(): void { echo '</main></body></html>'; }

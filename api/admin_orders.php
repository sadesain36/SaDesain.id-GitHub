<?php
require_once __DIR__.'/../config.php'; secure_session_start(); header('Content-Type: application/json; charset=utf-8');
if(empty($_SESSION['admin_id'])){http_response_code(401);echo json_encode(['ok'=>false,'message'=>'Sesi admin habis.']);exit;}
$rows=$pdo->query("SELECT o.*,d.name designer_name FROM orders o LEFT JOIN designers d ON d.id=o.designer_id ORDER BY o.created_at DESC")->fetchAll();
$counts=$pdo->query("SELECT status,COUNT(*) n FROM orders GROUP BY status")->fetchAll();echo json_encode(['ok'=>true,'orders'=>$rows,'counts'=>$counts]);

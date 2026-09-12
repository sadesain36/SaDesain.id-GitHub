<?php
require_once __DIR__ . '/../config.php';
header('Content-Type: application/json; charset=utf-8');
$code=trim($_GET['code']??'');
if(!$code){echo json_encode(['ok'=>false,'message'=>'Masukkan kode tracking.']);exit;}
$stmt=$pdo->prepare("SELECT o.tracking_code,o.customer_name,o.design_type,o.price,o.status,o.admin_note,o.updated_at,o.result_file,o.result_original_name,d.name designer_name FROM orders o LEFT JOIN designers d ON d.id=o.designer_id WHERE o.tracking_code=?");
$stmt->execute([$code]);$o=$stmt->fetch();
if(!$o){echo json_encode(['ok'=>false,'message'=>'Kode tracking tidak ditemukan.']);exit;}
echo json_encode(['ok'=>true,'order'=>$o]);

<?php
require_once __DIR__.'/../config.php'; secure_session_start(); header('Content-Type: application/json; charset=utf-8');
if(empty($_SESSION['admin_id'])){http_response_code(401);echo json_encode(['ok'=>false,'message'=>'Sesi admin habis.']);exit;} verify_csrf($_POST['csrf_token']??'');$id=(int)($_POST['id']??0);if(!$id){echo json_encode(['ok'=>false,'message'=>'ID tidak valid.']);exit;}$q=$pdo->prepare('SELECT reference_file,result_file FROM orders WHERE id=?');$q->execute([$id]);$old=$q->fetch();
$stmt=$pdo->prepare('DELETE FROM orders WHERE id=?');$stmt->execute([$id]);
if($old){
 if(!empty($old['reference_file'])){ $file=__DIR__.'/../storage/reference_images/'.basename($old['reference_file']); if(is_file($file)) @unlink($file); }
 if(!empty($old['result_file'])){ $file=__DIR__.'/../storage/order_results/'.basename($old['result_file']); if(is_file($file)) @unlink($file); }
}
echo json_encode(['ok'=>true]);

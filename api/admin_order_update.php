<?php
require_once __DIR__.'/../config.php'; secure_session_start(); header('Content-Type: application/json; charset=utf-8');
if(empty($_SESSION['admin_id'])){http_response_code(401);echo json_encode(['ok'=>false,'message'=>'Sesi admin habis.']);exit;} verify_csrf($_POST['csrf_token']??'');
$id=(int)($_POST['id']??0);$status=$_POST['status']??'Menunggu';$designer=(int)($_POST['designer_id']??0);$note=trim($_POST['admin_note']??'');$price=trim($_POST['price']??'');$allowed=['Menunggu','Diterima','Diproses','Revisi','Selesai'];
if(!$id||!in_array($status,$allowed,true)){echo json_encode(['ok'=>false,'message'=>'Data tidak valid.']);exit;}
$noteWords=preg_match_all('/\S+/u',$note,$noteMatches);
if($noteWords>50){echo json_encode(['ok'=>false,'message'=>'Catatan admin maksimal 50 kata.']);exit;}
if($designer){$q=$pdo->prepare('SELECT id FROM designers WHERE id=? AND active=1');$q->execute([$designer]);if(!$q->fetch())$designer=0;}
$stmt=$pdo->prepare('UPDATE orders SET status=?,designer_id=?,admin_note=?,price=? WHERE id=?');$stmt->execute([$status,$designer?:null,$note,$price,$id]);echo json_encode(['ok'=>true]);

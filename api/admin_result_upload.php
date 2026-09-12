<?php
declare(strict_types=1);
require_once __DIR__.'/../config.php';
secure_session_start();
header('Content-Type: application/json; charset=utf-8');
if(empty($_SESSION['admin_id'])){http_response_code(401);echo json_encode(['ok'=>false,'message'=>'Sesi admin habis.']);exit;}
if($_SERVER['REQUEST_METHOD']!=='POST'){http_response_code(405);echo json_encode(['ok'=>false,'message'=>'Metode tidak valid.']);exit;}
verify_csrf($_POST['csrf_token']??'');
$id=(int)($_POST['id']??0);
if(!$id || !isset($_FILES['result_file'])){echo json_encode(['ok'=>false,'message'=>'Pesanan dan file hasil wajib diisi.']);exit;}
$file=$_FILES['result_file'];
if($file['error']!==UPLOAD_ERR_OK){echo json_encode(['ok'=>false,'message'=>'Upload hasil desain gagal.']);exit;}
if((int)$file['size']>20*1024*1024){echo json_encode(['ok'=>false,'message'=>'Ukuran hasil desain maksimal 20 MB.']);exit;}
$allowed=[
 'application/pdf'=>'pdf','image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp',
 'application/zip'=>'zip','application/x-zip-compressed'=>'zip'
];
$finfo=new finfo(FILEINFO_MIME_TYPE); $mime=$finfo->file($file['tmp_name']);
if(!isset($allowed[$mime])){echo json_encode(['ok'=>false,'message'=>'Format hasil hanya PDF, JPG, PNG, WEBP, atau ZIP.']);exit;}
$q=$pdo->prepare('SELECT result_file FROM orders WHERE id=?');$q->execute([$id]);$old=$q->fetch();
if(!$old){echo json_encode(['ok'=>false,'message'=>'Pesanan tidak ditemukan.']);exit;}
$dir=__DIR__.'/../storage/order_results';
if(!is_dir($dir) && !mkdir($dir,0755,true)){echo json_encode(['ok'=>false,'message'=>'Folder hasil desain tidak dapat dibuat.']);exit;}
$ext=$allowed[$mime];$name='result_'.bin2hex(random_bytes(16)).'.'.$ext;$target=$dir.'/'.$name;
if(!move_uploaded_file($file['tmp_name'],$target)){echo json_encode(['ok'=>false,'message'=>'File hasil tidak dapat disimpan.']);exit;}
$original=basename((string)$file['name']);
$stmt=$pdo->prepare("UPDATE orders SET result_file=?,result_original_name=?,status='Selesai' WHERE id=?");
try{$stmt->execute([$name,$original,$id]);}catch(Throwable $e){@unlink($target);echo json_encode(['ok'=>false,'message'=>'Hasil desain gagal disimpan.']);exit;}
if(!empty($old['result_file'])){@unlink($dir.'/'.basename($old['result_file']));}
echo json_encode(['ok'=>true,'message'=>'Hasil desain berhasil diunggah dan pesanan ditandai Selesai.','filename'=>$original,'status'=>'Selesai']);

<?php
declare(strict_types=1);
require_once __DIR__.'/../config.php';
secure_session_start();
if(empty($_SESSION['admin_id'])){http_response_code(401);exit('Unauthorized');}
$id=(int)($_GET['id']??0);
if(!$id){http_response_code(400);exit('ID tidak valid.');}
$stmt=$pdo->prepare('SELECT reference_file, reference_original_name FROM orders WHERE id=?');
$stmt->execute([$id]); $o=$stmt->fetch();
if(!$o || !$o['reference_file']){http_response_code(404);exit('Gambar tidak ditemukan.');}
$file=__DIR__.'/../storage/reference_images/'.basename($o['reference_file']);
if(!is_file($file)){http_response_code(404);exit('File tidak ditemukan.');}
$finfo=new finfo(FILEINFO_MIME_TYPE); $mime=$finfo->file($file);
$allowed=['image/jpeg','image/png','image/webp'];
if(!in_array($mime,$allowed,true)){http_response_code(415);exit('Format tidak didukung.');}
header('Content-Type: '.$mime);
header('Content-Length: '.(string)filesize($file));
$safeName=str_replace([chr(34),chr(92)],['_','_'],(string)$o['reference_original_name']);
header('Content-Disposition: inline; filename="'.$safeName.'"');
header('X-Content-Type-Options: nosniff');
readfile($file);

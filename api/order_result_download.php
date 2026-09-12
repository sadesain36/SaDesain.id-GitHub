<?php
declare(strict_types=1);
require_once __DIR__.'/../config.php';
$code=trim($_GET['code']??'');
if(!$code){http_response_code(400);exit('Kode tracking tidak valid.');}
$stmt=$pdo->prepare("SELECT result_file,result_original_name,status FROM orders WHERE tracking_code=?");
$stmt->execute([$code]);$o=$stmt->fetch();
if(!$o || !$o['result_file']){http_response_code(404);exit('Hasil desain belum tersedia.');}
if($o['status'] !== 'Selesai'){http_response_code(403);exit('Hasil desain belum dapat diunduh. Pesanan belum berstatus Selesai.');}
$file=__DIR__.'/../storage/order_results/'.basename($o['result_file']);
if(!is_file($file)){http_response_code(404);exit('File hasil tidak ditemukan.');}
$finfo=new finfo(FILEINFO_MIME_TYPE);$mime=$finfo->file($file);
$allowed=['application/pdf','image/jpeg','image/png','image/webp','application/zip','application/x-zip-compressed'];
if(!in_array($mime,$allowed,true)){http_response_code(415);exit('Format file tidak didukung.');}
$name=preg_replace('/[^\pL\pN._ -]/u','_',basename((string)$o['result_original_name'])) ?: 'hasil-desain';
$size=filesize($file);
$safeAscii=preg_replace('/[^A-Za-z0-9._-]/','_', $name) ?: 'hasil-desain';
header('Content-Type: '.$mime);
header('Content-Length: '.(string)$size);
header("Content-Disposition: attachment; filename=\"$safeAscii\"; filename*=UTF-8''" . rawurlencode($name));
header('X-Content-Type-Options: nosniff');
header('Cache-Control: private, no-store, max-age=0');
header('Pragma: no-cache');
readfile($file);

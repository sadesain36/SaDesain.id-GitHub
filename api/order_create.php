<?php
require_once __DIR__ . '/../config.php';
header('Content-Type: application/json; charset=utf-8');
if($_SERVER['REQUEST_METHOD']!=='POST'){echo json_encode(['ok'=>false,'message'=>'Metode tidak valid.']);exit;}
$name=trim($_POST['customer_name']??''); $email=trim($_POST['email']??''); $wa=trim($_POST['whatsapp']??'');
$type=trim($_POST['design_type']??''); $desc=trim($_POST['description']??'');
if(!$name||!filter_var($email,FILTER_VALIDATE_EMAIL)||!$wa||!$type||!$desc){echo json_encode(['ok'=>false,'message'=>'Lengkapi semua data wajib.']);exit;}
$wordCount=preg_match_all('/\S+/u',$desc,$matches);
if($wordCount>50){echo json_encode(['ok'=>false,'message'=>'Catatan pesanan maksimal 50 kata.']);exit;}

$referenceFile = null; $referenceOriginal = null;
if(isset($_FILES['reference_image']) && $_FILES['reference_image']['error'] !== UPLOAD_ERR_NO_FILE){
    $file=$_FILES['reference_image'];
    if($file['error']!==UPLOAD_ERR_OK){echo json_encode(['ok'=>false,'message'=>'Gambar contoh desain gagal diunggah.']);exit;}
    if((int)$file['size']>2*1024*1024){echo json_encode(['ok'=>false,'message'=>'Ukuran gambar contoh maksimal 2 MB.']);exit;}
    $finfo=new finfo(FILEINFO_MIME_TYPE); $mime=$finfo->file($file['tmp_name']);
    $allowed=['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'];
    if(!isset($allowed[$mime])){echo json_encode(['ok'=>false,'message'=>'Format gambar hanya JPG, PNG, atau WEBP.']);exit;}
    $dir=__DIR__.'/../storage/reference_images';
    if(!is_dir($dir) && !mkdir($dir,0755,true)){echo json_encode(['ok'=>false,'message'=>'Folder penyimpanan gambar tidak dapat dibuat.']);exit;}
    $referenceFile='ref_'.bin2hex(random_bytes(16)).'.'.$allowed[$mime];
    $target=$dir.'/'.$referenceFile;
    if(!move_uploaded_file($file['tmp_name'],$target)){echo json_encode(['ok'=>false,'message'=>'Gambar contoh tidak dapat disimpan.']);exit;}
    $referenceOriginal=basename($file['name']);
}
do{$code='SD-'.strtoupper(substr(bin2hex(random_bytes(5)),0,8));$q=$pdo->prepare("SELECT id FROM orders WHERE tracking_code=?");$q->execute([$code]);}while($q->fetch());
$stmt=$pdo->prepare("INSERT INTO orders(tracking_code,customer_name,email,whatsapp,design_type,description,reference_file,reference_original_name) VALUES(?,?,?,?,?,?,?,?)");
try {
    $stmt->execute([$code,$name,$email,$wa,$type,$desc,$referenceFile,$referenceOriginal]);
} catch(Throwable $e) {
    if($referenceFile && isset($target) && is_file($target)) @unlink($target);
    echo json_encode(['ok'=>false,'message'=>'Pesanan gagal disimpan. Silakan coba lagi.']); exit;
}
echo json_encode(['ok'=>true,'tracking_code'=>$code]);

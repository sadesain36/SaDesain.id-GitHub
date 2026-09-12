<?php
require_once __DIR__ . '/../config.php';
secure_session_start();
if(!empty($_SESSION['admin_id'])){header('Location: dashboard.php');exit;}
$error='';
$now=time(); $_SESSION['login_window']=$_SESSION['login_window']??$now;
if($now-(int)$_SESSION['login_window']>600){$_SESSION['login_window']=$now;$_SESSION['login_attempts']=0;}
$_SESSION['login_attempts']=$_SESSION['login_attempts']??0;
if($_SERVER['REQUEST_METHOD']==='POST'){
  verify_csrf($_POST['csrf_token']??'');
  if($_SESSION['login_attempts']>=5){$error='Terlalu banyak percobaan login. Tunggu 10 menit lalu coba lagi.';}
  else{
    $email=trim($_POST['email']??'');$pass=$_POST['password']??'';
    $st=$pdo->prepare('SELECT * FROM admins WHERE email=? LIMIT 1');$st->execute([$email]);$a=$st->fetch();
    if($a && password_verify($pass,$a['password_hash'])){
      session_regenerate_id(true); $_SESSION['admin_id']=$a['id'];$_SESSION['admin_name']=$a['name'];$_SESSION['login_attempts']=0;
      if(password_needs_rehash($a['password_hash'],PASSWORD_DEFAULT)){$h=password_hash($pass,PASSWORD_DEFAULT);$pdo->prepare('UPDATE admins SET password_hash=? WHERE id=?')->execute([$h,$a['id']]);}
      header('Location: dashboard.php');exit;
    }
    $_SESSION['login_attempts']++; $error='Email atau password salah.';
  }
}
$csrf=csrf_token();
?><!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex,nofollow"><title>Login Admin — SaDesain.id</title><link rel="stylesheet" href="admin.css"></head><body class="login-page"><div class="login-card"><img src="../assets/logo.png" class="admin-logo" alt="Logo SaDesain.id"><h1>Admin SaDesain.id</h1><p>Panel privat pengelolaan website</p><?php if($error):?><div class="err"><?=e($error)?></div><?php endif;?><form method="post" autocomplete="on"><input type="hidden" name="csrf_token" value="<?=e($csrf)?>"><label>Email<input type="email" name="email" autocomplete="username" required></label><label>Password<input type="password" name="password" autocomplete="current-password" required></label><button type="submit">Masuk ke Dashboard</button></form><small>Akses admin tidak muncul pada menu website publik.</small></div></body></html>

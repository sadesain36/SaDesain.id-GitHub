<?php
require_once __DIR__.'/auth.php';
$msg='';$err='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 try{verify_csrf($_POST['csrf_token']??'');
  $site=trim($_POST['site_name']??'SaDesain.id');$tag=trim($_POST['tagline']??'');$wa=preg_replace('/\D+/','',$_POST['whatsapp']??'');$email=trim($_POST['email']??'');
  $ig=trim($_POST['instagram']??'');$fb=trim($_POST['facebook']??'');$tt=trim($_POST['tiktok']??'');
  foreach(['instagram'=>$ig,'facebook'=>$fb,'tiktok'=>$tt] as $k=>$u) if($u!=='' && !filter_var($u,FILTER_VALIDATE_URL)) throw new RuntimeException('Link '.$k.' tidak valid.');
  if(!filter_var($email,FILTER_VALIDATE_EMAIL)) throw new RuntimeException('Email tidak valid.');
  $bg=$_POST['background']??'assets/background.png';$logo=$_POST['logo']??'assets/logo.png';
  $st=$pdo->prepare('UPDATE settings SET site_name=?,tagline=?,whatsapp=?,email=?,instagram=?,facebook=?,tiktok=?,background=?,logo=? WHERE id=1');$st->execute([$site,$tag,$wa,$email,$ig,$fb,$tt,$bg,$logo]);$msg='Pengaturan website berhasil disimpan.';
 }catch(Throwable $x){$err=$x->getMessage();}
}
$s=$pdo->query('SELECT * FROM settings WHERE id=1')->fetch()?:[];$csrf=csrf_token();
admin_header('Pengaturan Website','settings');
if($msg)echo '<div class="notice success-notice">'.e($msg).'</div>';if($err)echo '<div class="notice error-notice">'.e($err).'</div>';
?>
<section class="panel"><div class="panelhead"><div><h2>Identitas Website</h2><p>Semua perubahan di sini langsung digunakan oleh halaman publik.</p></div></div><form method="post" class="member-grid"><input type="hidden" name="csrf_token" value="<?=e($csrf)?>"><label>Nama Website<input name="site_name" value="<?=e($s['site_name']??'SaDesain.id')?>" required></label><label>Tagline<input name="tagline" value="<?=e($s['tagline']??'')?>" required></label><label>WhatsApp<input name="whatsapp" value="<?=e($s['whatsapp']??'')?>" required></label><label>Email<input type="email" name="email" value="<?=e($s['email']??'')?>" required></label><label>Instagram<input name="instagram" value="<?=e($s['instagram']??'')?>"></label><label>Facebook<input name="facebook" value="<?=e($s['facebook']??'')?>"></label><label>TikTok<input name="tiktok" value="<?=e($s['tiktok']??'')?>"></label><label>Background<input name="background" value="<?=e($s['background']??'assets/background.png')?>"></label><label>Logo<input name="logo" value="<?=e($s['logo']??'assets/logo.png')?>"></label><div class="full"><button class="save" type="submit">Simpan Pengaturan</button></div></form></section>
<?php admin_footer(); ?>

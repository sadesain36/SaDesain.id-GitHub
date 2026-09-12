<?php
require_once __DIR__.'/auth.php';$msg='';$err='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 try{verify_csrf($_POST['csrf_token']??'');$old=$_POST['old_password']??'';$new=$_POST['new_password']??'';$confirm=$_POST['confirm_password']??'';
  if(!password_verify($old,$admin['password_hash']))throw new RuntimeException('Password lama salah.');
  if(strlen($new)<10)throw new RuntimeException('Password baru minimal 10 karakter.');
  if($new!==$confirm)throw new RuntimeException('Konfirmasi password tidak sama.');
  $h=password_hash($new,PASSWORD_DEFAULT);$pdo->prepare('UPDATE admins SET password_hash=? WHERE id=?')->execute([$h,$admin['id']]);$msg='Password admin berhasil diganti.';
 }catch(Throwable $x){$err=$x->getMessage();}
}
admin_header('Keamanan Admin','security');if($msg)echo '<div class="notice success-notice">'.e($msg).'</div>';if($err)echo '<div class="notice error-notice">'.e($err).'</div>';
?><section class="panel security-box"><h2>Ganti Password Admin</h2><p>Gunakan password unik minimal 10 karakter. Jangan gunakan password demo untuk website online.</p><form method="post"><input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>"><label>Password lama<input type="password" name="old_password" required autocomplete="current-password"></label><label>Password baru<input type="password" name="new_password" minlength="10" required autocomplete="new-password"></label><label>Konfirmasi password<input type="password" name="confirm_password" minlength="10" required autocomplete="new-password"></label><button class="save">Simpan Password Baru</button></form></section><?php admin_footer(); ?>

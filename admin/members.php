<?php
require_once __DIR__ . '/auth.php';
$msg=''; $err='';
$uploadDir=__DIR__.'/../assets/team/';
if(!is_dir($uploadDir)) mkdir($uploadDir,0755,true);
function uploadPhoto(string $field, ?string $old=null): ?string {
    global $uploadDir;
    if(empty($_FILES[$field]['name'])) return $old;
    if($_FILES[$field]['error']!==UPLOAD_ERR_OK) throw new RuntimeException('Upload foto gagal.');
    if($_FILES[$field]['size']>5*1024*1024) throw new RuntimeException('Ukuran foto maksimal 5 MB.');
    $finfo=new finfo(FILEINFO_MIME_TYPE); $mime=$finfo->file($_FILES[$field]['tmp_name']);
    $allowed=['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'];
    if(!isset($allowed[$mime])) throw new RuntimeException('Format foto harus JPG, PNG, atau WEBP.');
    $name='member_'.bin2hex(random_bytes(6)).'.'.$allowed[$mime];
    if(!move_uploaded_file($_FILES[$field]['tmp_name'],$uploadDir.$name)) throw new RuntimeException('Foto tidak dapat disimpan.');
    if($old && str_starts_with($old,'assets/team/') && is_file(__DIR__.'/../'.$old)) @unlink(__DIR__.'/../'.$old);
    return 'assets/team/'.$name;
}
try{
 if($_SERVER['REQUEST_METHOD']==='POST'){
   $action=$_POST['action']??'';
   if($action==='save'){
     verify_csrf($_POST['csrf_token']??'');
     $id=(int)($_POST['id']??0); $name=trim($_POST['name']??''); $position=trim($_POST['position']??''); $bio=trim($_POST['bio']??''); $active=isset($_POST['active'])?1:0;
     if($name==='') throw new RuntimeException('Nama anggota wajib diisi.');
     $old=null; if($id){$q=$pdo->prepare('SELECT photo FROM members WHERE id=?');$q->execute([$id]);$old=$q->fetchColumn()?:null;}
     $photo=uploadPhoto('photo',$old);
     if($id){$st=$pdo->prepare('UPDATE members SET name=?,position=?,bio=?,photo=?,active=? WHERE id=?');$st->execute([$name,$position,$bio,$photo,$active,$id]);$msg='Data anggota berhasil diperbarui.';}
     else{$st=$pdo->prepare('INSERT INTO members(name,position,bio,photo,active) VALUES(?,?,?,?,?)');$st->execute([$name,$position,$bio,$photo?:'assets/admin.png',$active]);$msg='Anggota baru berhasil ditambahkan.';}
   }
   if($action==='delete'){
     verify_csrf($_POST['csrf_token']??'');
     $id=(int)($_POST['id']??0); $q=$pdo->prepare('SELECT photo FROM members WHERE id=?');$q->execute([$id]);$photo=$q->fetchColumn();
     $pdo->prepare('DELETE FROM members WHERE id=?')->execute([$id]);
     if($photo && str_starts_with($photo,'assets/team/') && is_file(__DIR__.'/../'.$photo)) @unlink(__DIR__.'/../'.$photo);
     $msg='Anggota berhasil dihapus.';
   }
 }
}catch(Throwable $ex){$err=$ex->getMessage();}
$edit=null; if(isset($_GET['edit'])){$q=$pdo->prepare('SELECT * FROM members WHERE id=?');$q->execute([(int)$_GET['edit']]);$edit=$q->fetch()?:null;}
$members=$pdo->query('SELECT * FROM members ORDER BY id DESC')->fetchAll();
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Anggota Kelompok — SaDesain.id</title><link rel="stylesheet" href="admin.css"></head><body class="dash"><aside><div class="sidebrand"><img src="../assets/logo.png"><b>SaDesain.id</b></div><a href="dashboard.php">Dashboard</a><a class="active" href="members.php">Anggota Kelompok</a><a href="../" target="_blank">Website Publik ↗</a><a href="logout.php">Logout</a></aside><main><header class="top"><div><span class="eyebrow">ADMIN ONLY</span><h1>Anggota Kelompok</h1><p>Kelola data anggota tim secara khusus dari dashboard admin. Data anggota tidak ditampilkan pada website publik.</p></div></header>
<?php if($msg): ?><div class="notice success-notice"><?=e($msg)?></div><?php endif; ?><?php if($err): ?><div class="notice error-notice"><?=e($err)?></div><?php endif; ?>
<section class="panel member-editor"><div class="panelhead"><div><h2><?= $edit?'Edit Anggota':'Tambah Anggota' ?></h2><p>Foto dapat diganti langsung dari dashboard admin.</p></div></div>
<form method="post" enctype="multipart/form-data" class="member-form" id="memberForm"><input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>"><input type="hidden" name="action" value="save"><input type="hidden" name="id" value="<?=e($edit['id']??'0')?>"><div class="member-editor-layout"><div class="member-grid"><label>Nama<input name="name" required value="<?=e($edit['name']??'')?>" placeholder="Nama anggota"></label><label>Jabatan / Peran<input name="position" value="<?=e($edit['position']??'Anggota Kelompok')?>" placeholder="Contoh: Graphic Designer"></label><label class="full">Deskripsi singkat<textarea name="bio" placeholder="Deskripsi anggota..."><?=e($edit['bio']??'')?></textarea></label><label class="full photo-upload">Foto anggota<input id="photoInput" type="file" name="photo" accept="image/jpeg,image/png,image/webp"><small>JPG, PNG, WEBP • maksimal 5 MB</small></label><label class="check full"><input type="checkbox" name="active" value="1" <?=(!isset($edit['active'])||$edit['active'])?'checked':''?>> Aktif untuk pengelolaan admin</label></div><div class="member-live"><span>Pratinjau Foto</span><div class="live-photo"><img id="photoPreview" src="../<?=e($edit['photo']??'assets/admin.png')?>" alt="Pratinjau foto anggota"></div><strong id="liveName"><?=e($edit['name']??'Nama Anggota')?></strong><small id="livePosition"><?=e($edit['position']??'Anggota Kelompok')?></small></div></div><div class="member-actions"><button class="save" type="submit">Simpan Anggota</button><?php if($edit): ?><a class="cancel" href="members.php">Batal Edit</a><?php endif; ?></div></form></section>
<section class="panel"><div class="panelhead"><div><h2>Daftar Anggota</h2><p><?=count($members)?> anggota tersimpan. Kelola foto dan data tanpa menampilkannya ke pengunjung.</p></div><div class="member-tools"><input id="memberSearch" type="search" placeholder="Cari anggota..."><select id="memberFilter"><option value="all">Semua status</option><option value="active">Aktif</option><option value="inactive">Nonaktif</option></select></div></div><div class="member-admin-grid" id="memberList"><?php foreach($members as $m): ?><article class="member-admin-card" data-name="<?=e(strtolower($m['name'].' '.$m['position']))?>" data-status="<?=$m['active']?'active':'inactive'?>"><img src="../<?=e($m['photo'])?>" alt="<?=e($m['name'])?>"><div><h3><?=e($m['name'])?></h3><b><?=e($m['position'])?></b><p><?=e($m['bio'])?></p><span class="member-status <?= $m['active']?'on':'off' ?>"><?= $m['active']?'Aktif':'Nonaktif' ?></span><div class="member-card-actions"><a class="save" href="members.php?edit=<?=$m['id']?>">Edit</a><form method="post" onsubmit="return confirm('Hapus anggota ini?')"><input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?=$m['id']?>"><button class="del" type="submit">Hapus</button></form></div></div></article><?php endforeach; ?></div></section>
</main><script>
const input=document.getElementById('photoInput'), preview=document.getElementById('photoPreview');
if(input){input.addEventListener('change',()=>{const f=input.files[0]; if(!f)return; if(f.size>5*1024*1024){alert('Ukuran foto maksimal 5 MB.');input.value='';return;} const r=new FileReader();r.onload=e=>preview.src=e.target.result;r.readAsDataURL(f);});}
const nameInput=document.querySelector('input[name="name"]'), posInput=document.querySelector('input[name="position"]');
function live(){document.getElementById('liveName').textContent=nameInput?.value||'Nama Anggota';document.getElementById('livePosition').textContent=posInput?.value||'Anggota Kelompok';} nameInput?.addEventListener('input',live);posInput?.addEventListener('input',live);
const search=document.getElementById('memberSearch'), filter=document.getElementById('memberFilter');
function filterMembers(){const q=(search.value||'').toLowerCase().trim(), st=filter.value;document.querySelectorAll('.member-admin-card').forEach(c=>{c.hidden=(q&&!c.dataset.name.includes(q))||(st!=='all'&&c.dataset.status!==st);});}
search?.addEventListener('input',filterMembers);filter?.addEventListener('change',filterMembers);
</script></body></html>

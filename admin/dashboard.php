<?php
require_once __DIR__.'/auth.php';
$designers=$pdo->query("SELECT * FROM designers WHERE active=1 ORDER BY name")->fetchAll();
admin_header('Dashboard Pengelolaan','dashboard');
$csrf=csrf_token();
?>
<section class="stats">
  <div><b id="total">0</b><span>Total Pesanan</span></div>
  <div><b id="waiting">0</b><span>Menunggu</span></div>
  <div><b id="processing">0</b><span>Diproses</span></div>
  <div><b id="done">0</b><span>Selesai</span></div>
</section>

<section id="pesanan" class="panel order-panel">
  <div class="panelhead order-panelhead">
    <div>
      <h2>Pesanan Pelanggan</h2>
      <p>Kelola designer, status, harga desain, dan catatan proses pelanggan dari satu tempat.</p>
    </div>
    <div class="order-tools">
      <span id="editState" class="edit-state">✓ Siap digunakan</span>
      <button type="button" class="refresh-btn" onclick="manualRefresh()">↻ Perbarui</button>
      <span id="bell" class="bell" aria-label="Notifikasi pesanan baru">●</span>
    </div>
  </div>

  <div class="tablewrap order-tablewrap">
    <table class="order-table">
      <thead>
        <tr>
          <th class="col-tracking">Tracking</th>
          <th class="col-customer">Pelanggan</th>
          <th class="col-design">Desain</th>
          <th class="col-detail">Detail Pesanan</th>
          <th class="col-reference">Contoh Desain</th>
          <th class="col-designer">Designer</th>
          <th class="col-status">Status</th>
          <th class="col-price">Harga Desain</th>
          <th class="col-note">Catatan Proses <small>(maks. 50 kata)</small></th>
          <th class="col-result">Hasil Desain</th>
          <th class="col-action">Aksi</th>
        </tr>
      </thead>
      <tbody id="orders"></tbody>
    </table>
  </div>
</section>

<script>
const designers=<?=json_encode($designers,JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT)?>;
const csrf=<?=json_encode($csrf)?>;
let known=0;
let loading=false;
let hasDraft=false;

function esc(s){
  return String(s??'').replace(/[&<>"']/g,m=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[m]));
}
function countWords(text){return text.trim()?text.trim().split(/\s+/u).length:0;}

function getDrafts(){
  const drafts={};
  document.querySelectorAll('#orders tr[data-order-id]').forEach(row=>{
    const id=row.dataset.orderId;
    const price=row.querySelector('.price-input');
    const note=row.querySelector('.note-input');
    const designer=row.querySelector('.designer-select');
    const status=row.querySelector('.status-select');
    if(price||note||designer||status){
      drafts[id]={
        price:price?.value??'',
        note:note?.value??'',
        designer:designer?.value??'',
        status:status?.value??''
      };
    }
  });
  return drafts;
}

function applyDrafts(drafts){
  Object.entries(drafts).forEach(([id,d])=>{
    const price=document.querySelector('#p'+id);
    const note=document.querySelector('#n'+id);
    const designer=document.querySelector('#d'+id);
    const status=document.querySelector('#s'+id);
    if(price && d.price!==undefined) price.value=d.price;
    if(note && d.note!==undefined) note.value=d.note;
    if(designer && d.designer!==undefined) designer.value=d.designer;
    if(status && d.status!==undefined) status.value=d.status;
  });
  updateNoteCounters();
}

function updateNoteCounters(){
  document.querySelectorAll('.note-input').forEach(el=>{
    const id=el.id.slice(1);
    const counter=document.querySelector('#nc'+id);
    let words=countWords(el.value);
    if(words>50){
      el.value=el.value.trim().split(/\s+/u).slice(0,50).join(' ');
      words=50;
    }
    if(counter){
      counter.textContent=words+'/50 kata';
      counter.classList.toggle('over',words>50);
    }
  });
}

function setEditingState(active){
  hasDraft=active;
  const state=document.querySelector('#editState');
  if(!state)return;
  state.textContent=active?'● Sedang mengedit — perubahan belum disimpan':'✓ Siap digunakan';
  state.classList.toggle('editing',active);
}

function markDraft(){setEditingState(true);}

document.addEventListener('input',e=>{
  if(e.target.matches('.note-input,.price-input')){
    markDraft();
    if(e.target.matches('.note-input'))updateNoteCounters();
  }
});
document.addEventListener('change',e=>{
  if(e.target.matches('.designer-select,.status-select'))markDraft();
});

async function load(force=false){
  if(loading)return;
  if(hasDraft && !force)return;
  loading=true;
  try{
    const r=await fetch('../api/admin_orders.php',{cache:'no-store'});
    if(!r.ok)return;
    const d=await r.json(),orders=d.orders||[];
    document.querySelector('#total').textContent=orders.length;
    const c=Object.fromEntries((d.counts||[]).map(x=>[x.status,Number(x.n)]));
    document.querySelector('#waiting').textContent=c.Menunggu||0;
    document.querySelector('#processing').textContent=c.Diproses||0;
    document.querySelector('#done').textContent=c.Selesai||0;
    if(known&&orders.length>known)document.querySelector('#bell').classList.add('pulse');
    known=orders.length;

    const oldDrafts=force?{}:getDrafts();
    document.querySelector('#orders').innerHTML=orders.map(o=>`<tr data-order-id="${o.id}">
      <td class="tracking-cell"><b>${esc(o.tracking_code)}</b><small>${esc(o.created_at)}</small></td>
      <td class="customer-cell"><strong>${esc(o.customer_name)}</strong><small>${esc(o.whatsapp)}<br>${esc(o.email)}</small></td>
      <td><span class="design-badge">${esc(o.design_type)}</span></td>
      <td class="detail-cell">${esc(o.description)}</td>
      <td class="reference-cell">${o.reference_file ? `<a class="reference-link" href="../api/admin_reference_image.php?id=${o.id}" target="_blank" rel="noopener">🖼️ Lihat Gambar</a><small>${esc(o.reference_original_name||'Gambar referensi')}</small>` : '<span class="no-reference">Tidak ada</span>'}</td>
      <td><select id="d${o.id}" class="designer-select">${designers.map(x=>`<option value="${x.id}" ${String(x.id)===String(o.designer_id)?'selected':''}>${esc(x.name)}</option>`).join('')}</select></td>
      <td><select id="s${o.id}" class="status-select">${['Menunggu','Diterima','Diproses','Revisi','Selesai'].map(s=>`<option ${o.status===s?'selected':''}>${s}</option>`).join('')}</select></td>
      <td class="price-cell">
        <div class="input-prefix"><span>Rp</span><input id="p${o.id}" class="price-input" value="${esc(o.price||'')}" placeholder="150.000" inputmode="decimal" autocomplete="off"></div>
        <small class="field-help">Ditentukan admin</small>
      </td>
      <td class="note-cell">
        <textarea id="n${o.id}" class="note-input" maxlength="500" rows="4" placeholder="Tulis proses desain untuk pelanggan...">${esc(o.admin_note||'')}</textarea>
        <small id="nc${o.id}" class="note-counter"></small>
      </td>
      <td class="result-cell">
        ${o.result_file ? `<a class="result-link" href="../api/order_result_download.php?code=${encodeURIComponent(o.tracking_code)}" target="_blank" rel="noopener">⬇ ${esc(o.result_original_name||'Download hasil')}</a><small>File hasil tersedia</small>` : '<span class="no-reference">Belum ada hasil</span>'}
        <input type="file" id="r${o.id}" class="result-file" accept=".pdf,.jpg,.jpeg,.png,.webp,.zip">
        <small class="field-help">PDF/JPG/PNG/WEBP/ZIP • maks. 20 MB</small>
        <button type="button" class="upload-result" onclick="uploadResult(${o.id})">Upload Hasil</button>
      </td>
      <td class="action-cell"><button class="save" onclick="saveOrder(${o.id})">Simpan</button><button class="del" onclick="delOrder(${o.id})">Hapus</button></td>
    </tr>`).join('')||'<tr><td colspan="11" class="empty">Belum ada pesanan.</td></tr>';
    applyDrafts(oldDrafts);
    if(!hasDraft)setEditingState(false);
  }catch(err){console.error(err)}finally{loading=false;}
}

async function manualRefresh(){
  if(hasDraft && !confirm('Ada perubahan yang belum disimpan. Perbarui sekarang dan batalkan perubahan tersebut?'))return;
  hasDraft=false;
  setEditingState(false);
  await load(true);
}

async function saveOrder(id){
  const price=document.querySelector('#p'+id)?.value.trim()||'';
  const note=document.querySelector('#n'+id)?.value.trim()||'';
  const f=new FormData();
  f.append('csrf_token',csrf);f.append('id',id);
  f.append('status',document.querySelector('#s'+id).value);
  f.append('designer_id',document.querySelector('#d'+id).value);
  f.append('admin_note',note);f.append('price',price);
  const button=document.querySelector(`tr[data-order-id="${id}"] .save`);
  if(button){button.disabled=true;button.textContent='Menyimpan...';}
  try{
    const r=await fetch('../api/admin_order_update.php',{method:'POST',body:f}).then(x=>x.json());
    if(!r.ok){alert(r.message||'Gagal menyimpan');return;}
    hasDraft=false;setEditingState(false);await load(true);
  }catch(err){alert('Koneksi bermasalah. Silakan coba lagi.');}
}

async function uploadResult(id){
  const input=document.querySelector('#r'+id), file=input?.files?.[0];
  if(!file){alert('Pilih file hasil desain terlebih dahulu.');return;}
  if(file.size>20*1024*1024){alert('Ukuran hasil desain maksimal 20 MB.');return;}
  const allowed=['application/pdf','image/jpeg','image/png','image/webp','application/zip','application/x-zip-compressed'];
  if(!allowed.includes(file.type)){alert('Format hasil hanya PDF, JPG, PNG, WEBP, atau ZIP.');return;}
  const f=new FormData();f.append('csrf_token',csrf);f.append('id',id);f.append('result_file',file);
  const button=document.querySelector(`tr[data-order-id="${id}"] .upload-result`);
  if(button){button.disabled=true;button.textContent='Mengunggah...';}
  try{
    const r=await fetch('../api/admin_result_upload.php',{method:'POST',body:f}).then(x=>x.json());
    if(!r.ok){alert(r.message||'Gagal mengunggah hasil.');return;}
    alert(r.message || 'Hasil desain berhasil diunggah dan pesanan ditandai Selesai.');
    hasDraft=false;setEditingState(false);await load(true);
  }catch(e){alert('Koneksi bermasalah. Silakan coba lagi.');}
  finally{if(button){button.disabled=false;button.textContent='Upload Hasil';}}
}

async function delOrder(id){
  if(!confirm('Hapus pesanan ini?'))return;
  const f=new FormData();f.append('csrf_token',csrf);f.append('id',id);
  const r=await fetch('../api/admin_delete_order.php',{method:'POST',body:f}).then(x=>x.json());
  if(!r.ok){alert(r.message||'Gagal menghapus');return;}
  hasDraft=false;setEditingState(false);load(true);
}

load();
setInterval(()=>load(false),5000);
</script>
<?php admin_footer(); ?>

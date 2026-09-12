const $=s=>document.querySelector(s);
const esc=s=>String(s??'').replace(/[&<>"']/g,m=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[m]));
$('#menu')?.addEventListener('click',()=>$('#navlinks').classList.toggle('open'));
document.querySelectorAll('.nav a').forEach(a=>a.addEventListener('click',()=>$('#navlinks').classList.remove('open')));

const io=new IntersectionObserver(es=>es.forEach(e=>e.isIntersecting&&e.target.classList.add('show')),{threshold:.1});
document.querySelectorAll('.cards article,.work,.section').forEach(x=>x.classList.add('reveal'));
document.querySelectorAll('.reveal').forEach(x=>io.observe(x));

function openPreview(el){$('#previewImg').src=el.dataset.image;$('#previewTitle').textContent=el.dataset.title;$('#preview').classList.add('show')}
function closePreview(){$('#preview').classList.remove('show')}

$('#orderForm')?.addEventListener('submit',async e=>{
 e.preventDefault();
 const fd=new FormData(e.target);
 const r=await fetch('api/order_create.php',{method:'POST',body:fd}).then(x=>x.json()).catch(()=>({ok:false,message:'Server tidak dapat dihubungi.'}));
 const box=$('#orderResult');
 if(r.ok){
   const data=Object.fromEntries(fd.entries());
   const waText=`Halo SaDesain.id, saya ingin memesan desain.%0A%0AKode Tracking: ${r.tracking_code}%0ANama: ${encodeURIComponent(data.customer_name)}%0AEmail: ${encodeURIComponent(data.email)}%0AWhatsApp: ${encodeURIComponent(data.whatsapp)}%0AJenis Desain: ${encodeURIComponent(data.design_type)}%0AHarga: Akan ditentukan admin%0ADetail: ${encodeURIComponent(data.description)}`;
   const waUrl=`https://wa.me/628134947692?text=${waText}`;
   box.innerHTML=`<div class="success"><b>Pesanan berhasil dibuat!</b><br>Kode tracking Anda: <strong>${r.tracking_code}</strong><br>Simpan kode ini untuk memantau proses desain.<div class="result-actions"><a class="btn primary" href="${waUrl}" target="_blank" rel="noopener"><svg class="wa-icon" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M12.04 2A9.94 9.94 0 0 0 3.5 17.02L2 22l5.1-1.46A10 10 0 1 0 12.04 2Zm0 18.18c-1.58 0-3.13-.42-4.49-1.22l-.32-.19-3.03.87.9-2.95-.21-.32a8.2 8.2 0 1 1 7.15 3.81Zm4.5-6.13c-.25-.13-1.48-.73-1.71-.81-.23-.09-.4-.13-.57.13-.17.25-.65.81-.8.98-.15.17-.3.19-.55.06-.25-.13-1.05-.39-2-1.24-.74-.66-1.24-1.47-1.38-1.72-.14-.25-.02-.39.11-.52.11-.11.25-.3.38-.45.13-.15.17-.25.25-.42.08-.17.04-.32-.02-.45-.06-.13-.57-1.37-.78-1.88-.21-.5-.42-.43-.57-.44h-.49c-.17 0-.45.06-.68.32-.23.25-.89.87-.89 2.11s.91 2.45 1.04 2.62c.13.17 1.78 2.72 4.31 3.82.6.26 1.07.41 1.43.52.6.19 1.15.16 1.58.1.48-.07 1.48-.61 1.69-1.2.21-.59.21-1.09.15-1.2-.06-.11-.23-.17-.48-.3Z"/></svg><span>Kirim Detail ke WhatsApp</span></a><a class="btn secondary" href="#tracking" onclick="document.querySelector('#trackingCode').value='${r.tracking_code}'">Lacak Pesanan</a></div></div>`;
   window.open(waUrl,'_blank','noopener');
   e.target.reset();
 }else box.innerHTML=`<div class="success" style="background:#fff1f2;color:#9f1239;border-color:#fecdd3">${r.message||'Pesanan gagal.'}</div>`;
});

$('#trackingForm')?.addEventListener('submit',async e=>{
 e.preventDefault();
 const code=$('#trackingCode').value.trim();
 const r=await fetch('api/order_track.php?code='+encodeURIComponent(code)).then(x=>x.json()).catch(()=>({ok:false,message:'Gagal menghubungi server.'}));
 const box=$('#trackingResult');
 if(!r.ok){box.innerHTML=`<div class="track-card"><b>${r.message||'Pesanan tidak ditemukan.'}</b></div>`;return}
 const order=r.order, statuses=['Menunggu','Diterima','Diproses','Revisi','Selesai'], idx=statuses.indexOf(order.status);
 box.innerHTML=`<div class="track-card">
   <h3>${esc(order.tracking_code)}</h3><p><b>${esc(order.customer_name)}</b> — ${esc(order.design_type)}</p>
   <span class="status">${esc(order.status)}</span>
   <div class="steps">${statuses.map((s,i)=>`<div class="step ${i<=idx?'active':''}">${s}</div>`).join('')}</div>
   <p><b>Designer:</b> ${esc(order.designer_name||'Akan ditentukan admin')}</p>
   ${order.price?`<div class="price-display"><b>Harga Desain:</b> Rp ${esc(order.price)}</div>`:''}
   ${order.admin_note?`<div class="note"><b>Catatan dari SaDesain:</b><br>${esc(order.admin_note)}</div>`:''}
   ${order.result_file?`<div class="result-download"><b>Hasil desain tersedia</b><br><small>${esc(order.result_original_name||'File hasil desain')}</small><br><a class="btn primary download-result" href="api/order_result_download.php?code=${encodeURIComponent(order.tracking_code)}">⬇ Download Hasil Desain</a></div>`:''}
   <small>Terakhir diperbarui: ${esc(order.updated_at)}</small>
 </div>`;
});

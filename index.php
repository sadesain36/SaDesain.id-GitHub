<?php
require_once __DIR__ . '/config.php';

$setting = $pdo->query("SELECT * FROM settings WHERE id=1")->fetch() ?: [];
$portfolio = $pdo->query("SELECT * FROM portfolios ORDER BY id DESC")->fetchAll();

$instagram = $setting['instagram'] ?? 'https://www.instagram.com/sadesain.id_?igsi=aDExa3F1dzlzMzlu';
$facebook = $setting['facebook'] ?? 'https://www.facebook.com/profile.php?id=61592431325858';
$tiktok = $setting['tiktok'] ?? 'https://www.tiktok.com/@sadesainid?_r=1&_t=ZS-99ERqGPsxBR';

$siteName = $setting['site_name'] ?? 'SaDesain.id';
$tagline = $setting['tagline'] ?? 'Jasa desain kreatif untuk kebutuhan Anda';
$email = $setting['email'] ?? 'sadesain.id13@gmail.com';
$waNumber = preg_replace('/\D+/', '', $setting['whatsapp'] ?? '628134947692');

?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="description" content="SaDesain.id - Jasa desain grafis kreatif, profesional, mudah dipesan dan dapat dipantau secara online.">
<title><?=e($siteName)?> — Creative Design Studio</title>

<style>
:root{
  --purple:#6d28d9;--violet:#8b5cf6;--cyan:#06b6d4;--dark:#120b19;
  --text:#211827;--muted:#51465a;--line:#ddd2e5;--yellow:#f4ff00;
}
*{box-sizing:border-box}
html{scroll-behavior:smooth}
body{margin:0;font-family:Inter,Segoe UI,Arial,sans-serif;color:var(--text);background:#100914;line-height:1.6}
button,input,select,textarea{font:inherit}
a{transition:.25s}
.nav{
  position:sticky;top:0;z-index:1000;display:flex;align-items:center;justify-content:space-between;
  padding:10px 6%;background:rgba(14,8,18,.90);backdrop-filter:blur(16px);
  border-bottom:1px solid rgba(255,255,255,.14)
}
.brand{display:flex;align-items:center;gap:10px;color:#fff;text-decoration:none;font-weight:900;font-size:18px}
.brand img{width:50px;height:50px;object-fit:contain}
.nav nav{display:flex;gap:20px;align-items:center}
.nav nav a{color:#fff;text-decoration:none;font-weight:800;font-size:14px;text-shadow:0 2px 5px #000}
.nav nav a:hover{color:#67e8f9}
.menu{display:none;background:none;border:0;color:#fff;font-size:28px;cursor:pointer}

.hero{
  min-height:92vh;background:url('<?=e($setting['background'] ?? 'assets/background.png')?>') center/cover fixed;
  position:relative;display:grid;place-items:center;padding:110px 7% 70px
}
.hero-overlay{position:absolute;inset:0;background:linear-gradient(90deg,rgba(7,3,12,.94),rgba(54,15,86,.72),rgba(7,3,12,.40))}
.hero-content{position:relative;max-width:920px;color:#fff}
.eyebrow{font-size:12px;letter-spacing:3px;font-weight:900;color:var(--purple)}
.hero .eyebrow{color:var(--yellow);text-shadow:0 2px 8px #000}
h1{font-size:clamp(42px,7vw,82px);line-height:1.02;margin:18px 0;text-shadow:0 5px 22px #000}
h1 b{color:var(--yellow)}
.hero p{font-size:20px;max-width:720px;color:#fff;line-height:1.75;text-shadow:0 2px 7px #000;font-weight:600}
.hero-actions,.order-actions,.result-actions{display:flex;gap:12px;flex-wrap:wrap;align-items:center}
.hero-actions{margin:28px 0}
.btn{border:0;border-radius:14px;padding:14px 22px;font-weight:900;cursor:pointer;text-decoration:none;display:inline-block}
.primary{background:linear-gradient(135deg,var(--purple),var(--cyan));color:#fff;box-shadow:0 12px 30px rgba(124,58,237,.38)}
.primary:hover{transform:translateY(-3px);box-shadow:0 16px 35px rgba(6,182,212,.35)}
.glass{color:#fff;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.45);backdrop-filter:blur(8px)}
.glass:hover{background:rgba(255,255,255,.22)}
.trust{display:flex;gap:20px;flex-wrap:wrap;font-size:13px;color:#fff;font-weight:800;text-shadow:0 2px 6px #000}

.section{padding:95px 7%;background:#fbf9fc}
.section.dark{background:rgba(8,4,12,.96);color:#fff}
.section-head{max-width:780px;margin:0 auto 42px;text-align:center}
.section-head h2{font-size:42px;line-height:1.15;margin:10px 0;color:#1b1421}
.section-head p{color:#51465a;line-height:1.75;font-weight:600}
.dark .section-head h2,.dark .section-head p{color:#fff}
.dark .section-head p{color:#ded5e5}
.cards{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;max-width:1150px;margin:auto}
.cards article{background:#fff;border:1px solid var(--line);padding:28px;border-radius:24px;box-shadow:0 12px 40px rgba(45,13,69,.10);transition:.25s}
.cards article:hover{transform:translateY(-7px);box-shadow:0 20px 50px rgba(124,58,237,.20)}
.icon{width:50px;height:50px;border-radius:15px;display:grid;place-items:center;background:#efe7ff;color:var(--purple);font-size:24px;font-weight:900}
.cards h3{font-size:20px;color:#241a2c}
.cards p{color:#403747;line-height:1.7;font-weight:600}

.gallery{display:grid;grid-template-columns:repeat(4,1fr);gap:18px;max-width:1200px;margin:auto}
.work{padding:0;border:0;border-radius:20px;overflow:hidden;background:#211527;color:#fff;text-align:left;cursor:pointer;box-shadow:0 10px 35px #0005}
.work img{width:100%;height:270px;object-fit:cover;display:block;transition:.35s}
.work:hover img{transform:scale(1.04)}
.work span{display:block;padding:16px}
.work small{display:block;color:#d0c5d8;margin-top:5px}

.order-section{background:linear-gradient(180deg,#321044,#160b20);color:#fff}
.order-section .section-head h2{color:#fff;text-shadow:0 3px 12px #000}
.order-section .section-head p{color:#fff;text-shadow:0 2px 5px #000}
.order-section .eyebrow{color:#67e8f9;text-shadow:0 2px 6px #000}

.order-intro{
  max-width:850px;margin:0 auto 18px;padding:16px 20px;border-radius:18px;
  background:linear-gradient(135deg,rgba(244,255,0,.16),rgba(6,182,212,.16));
  border:1px solid rgba(244,255,0,.45);color:#fff;font-weight:800;
  box-shadow:0 10px 35px rgba(0,0,0,.22)
}
.order-form,.track-form,.track-card{
  max-width:850px;margin:auto;background:#fff;padding:30px;border-radius:28px;
  box-shadow:0 20px 70px rgba(0,0,0,.28);border:2px solid rgba(255,255,255,.7)
}
.grid2{display:grid;grid-template-columns:1fr 1fr;gap:0 18px}
label{display:block;font-weight:900;margin:12px 0;color:#211827}
label small{display:block;color:#62566a;font-weight:600;margin-top:3px}
input,select,textarea{
  display:block;width:100%;margin-top:8px;padding:14px 15px;border:2px solid #cfc2d8;
  border-radius:13px;font:inherit;outline:none;background:#fff;color:#211827;font-weight:600
}
input:focus,select:focus,textarea:focus{border-color:var(--purple);box-shadow:0 0 0 4px rgba(124,58,237,.14)}
textarea{min-height:150px;resize:vertical}
input::placeholder,textarea::placeholder{color:#62566a;opacity:1}
.reference-upload{margin:12px 0 6px;padding:16px;border:2px dashed #d8c9e3;border-radius:16px;background:#faf7fc}.reference-upload input[type=file]{padding:12px;background:#fff}.file-help{display:block;color:#62566a;font-size:12px;font-weight:600;margin-top:6px}.file-preview{display:none;margin-top:10px;max-width:180px;max-height:120px;border-radius:12px;border:1px solid #ddd2e4;object-fit:cover;box-shadow:0 6px 18px rgba(45,13,69,.08)}.file-name{display:block;margin-top:7px;color:#5b21b6;font-size:12px;font-weight:800;min-height:18px}.order-actions{margin-top:18px}
.order-actions .btn{min-height:52px}
.wa-icon{width:22px;height:22px;display:inline-block;vertical-align:middle;flex:0 0 auto}.whatsapp-btn{background:#25d366;color:#fff;box-shadow:0 10px 25px rgba(37,211,102,.28)}
.whatsapp-btn:hover{transform:translateY(-3px)}
.secondary{background:#ece8ef;color:#302536}
.result{margin-top:18px}
.success{padding:20px;border-radius:16px;background:#ecfdf5;color:#065f46;border:2px solid #86efac;font-weight:700}
.success strong{font-size:18px}

.tracking{background:linear-gradient(180deg,#160b20,#0d0711);color:#fff}
.tracking .section-head h2{color:#fff;text-shadow:0 3px 12px #000}
.tracking .section-head p{color:#e5ddea}
.track-form{display:flex;gap:10px;padding:14px}
.track-form input{margin:0}
.track-card{margin:20px auto 0;color:#211827;padding:25px}
.status{display:inline-block;padding:8px 12px;border-radius:999px;background:#efe4ff;color:#6b21a8;font-weight:900}
.steps{display:grid;grid-template-columns:repeat(5,1fr);gap:8px;margin-top:22px}
.step{padding:12px;border-radius:12px;background:#eee;text-align:center;font-size:12px;font-weight:800}
.step.active{background:linear-gradient(135deg,var(--purple),var(--cyan));color:#fff}
.note{background:#fff7cc;padding:14px;border-radius:14px;margin-top:15px;border:1px solid #facc15}

.social-section{background:linear-gradient(180deg,#fff,#f5effa)}
.social-grid{max-width:1100px;margin:auto;display:grid;grid-template-columns:repeat(4,1fr);gap:18px}
.social-card{
  display:flex;align-items:center;gap:14px;min-height:110px;padding:18px;border-radius:22px;
  color:#fff;text-decoration:none;overflow:hidden;box-shadow:0 14px 35px rgba(27,12,40,.16);
  border:1px solid rgba(255,255,255,.4);transition:.25s
}
.social-card:hover{transform:translateY(-7px);box-shadow:0 20px 45px rgba(27,12,40,.25)}
.instagram{background:linear-gradient(135deg,#833ab4,#fd1d1d 55%,#fcb045)}
.facebook{background:linear-gradient(135deg,#1877f2,#0b55c9)}
.email{background:linear-gradient(135deg,#b42318,#ef4444)}
.tiktok{background:linear-gradient(135deg,#111827,#000)}
.social-icon{width:54px;height:54px;flex:0 0 54px;border-radius:16px;background:rgba(255,255,255,.18);display:grid;place-items:center;border:1px solid rgba(255,255,255,.4)}
.social-icon svg{width:29px;height:29px}
.social-card b{display:block;font-size:18px;line-height:1.2;color:#fff}
.social-card small{display:block;margin-top:5px;color:#fff;font-size:12px;line-height:1.35;font-weight:700}
.social-arrow{margin-left:auto;font-size:25px;font-weight:900}
.social-cta{max-width:1100px;margin:24px auto 0;padding:20px;border-radius:20px;background:#17121f;color:#fff;display:flex;align-items:center;justify-content:space-between;gap:18px;box-shadow:0 15px 40px rgba(23,18,31,.18)}
.social-cta strong{display:block;font-size:20px;color:#fff}
.social-cta span{display:block;margin-top:4px;color:#e5ddea;font-size:14px}

footer{background:#0c0810;color:#d9d0df;display:grid;grid-template-columns:2fr 1fr 1fr;gap:30px;padding:50px 7%}
footer img{width:60px;display:block;margin-bottom:8px}
footer b{font-size:20px;color:#fff}
footer p{line-height:1.7}
footer a{color:#67e8f9;text-decoration:none;font-weight:700}

.whatsapp-float{
  position:fixed;right:22px;bottom:22px;z-index:9999;display:flex;align-items:center;gap:11px;
  padding:9px 17px 9px 9px;border-radius:999px;background:#25d366;color:#fff;text-decoration:none;
  box-shadow:0 10px 30px rgba(37,211,102,.45),0 3px 10px rgba(0,0,0,.25);
  border:2px solid rgba(255,255,255,.35);animation:waPulse 2s infinite
}
.whatsapp-float:hover{transform:translateY(-5px) scale(1.02)}
.wa-symbol{width:50px;height:50px;border-radius:50%;background:#fff;color:#25d366;display:grid;place-items:center}
.wa-symbol svg{width:30px;height:30px}
.wa-text{display:flex;flex-direction:column;line-height:1.25}
.wa-text b{font-size:14px}.wa-text small{font-size:11px;font-weight:700}
.wa-badge{position:absolute;right:7px;top:-6px;min-width:22px;height:22px;padding:0 5px;border-radius:999px;background:#ef233c;color:#fff;border:2px solid #fff;display:grid;place-items:center;font-size:11px;font-weight:900}
@keyframes waPulse{0%,100%{box-shadow:0 10px 30px rgba(37,211,102,.34),0 3px 10px rgba(0,0,0,.2)}50%{box-shadow:0 12px 40px rgba(37,211,102,.65),0 3px 10px rgba(0,0,0,.2)}}

.modal{display:none;position:fixed;z-index:5000;inset:0;background:rgba(0,0,0,.88);align-items:center;justify-content:center;flex-direction:column;padding:30px}
.modal.show{display:flex}.modal img{max-width:90%;max-height:75vh;border-radius:18px}
.modal button{position:absolute;top:20px;right:25px;background:#fff;border:0;border-radius:50%;width:44px;height:44px;font-size:28px;cursor:pointer}
.modal h3{color:#fff;text-align:center}
.reveal{opacity:0;transform:translateY(20px);transition:.6s}.reveal.show{opacity:1;transform:none}
:focus-visible{outline:3px solid var(--yellow);outline-offset:3px}

@media(max-width:900px){
  .nav nav{display:none;position:absolute;top:70px;left:0;right:0;background:#100b16;padding:20px;flex-direction:column;align-items:flex-start}
  .nav nav.open{display:flex}.menu{display:block}
  .cards{grid-template-columns:1fr 1fr}.gallery{grid-template-columns:1fr 1fr}.social-grid{grid-template-columns:1fr 1fr}
  footer{grid-template-columns:1fr 1fr}
}
@media(max-width:600px){
  .section{padding:75px 5%}.hero{padding:105px 5% 60px;background-attachment:scroll}
  .hero p{font-size:17px}.cards,.gallery,.grid2,.social-grid,footer{grid-template-columns:1fr}
  .track-form{flex-direction:column}.steps{grid-template-columns:1fr 1fr}
  .work img{height:220px}.section-head h2{font-size:34px}
  .whatsapp-float{right:14px;bottom:14px;padding:8px}.wa-text{display:none}.wa-symbol{width:54px;height:54px}
  .order-actions .btn,.result-actions .btn{width:100%;text-align:center}
}
.result-download{margin-top:16px;padding:16px;border:1px solid #ddd2e4;border-radius:14px;background:#faf7fc}.result-download .download-result{display:inline-flex;align-items:center;gap:7px;margin-top:10px}.price-display{margin-top:14px;padding:13px 15px;border-radius:12px;background:#f3e8ff;color:#5b21b6;font-weight:800}</style>
</head>

<body>
<header class="nav">
  <a class="brand" href="#home">
    <img src="<?=e($setting['logo'] ?? 'assets/logo.png')?>" alt="Logo SaDesain.id">
    <span><?=e($siteName)?></span>
  </a>
  <button class="menu" id="menu" aria-label="Buka menu">☰</button>
  <nav id="navlinks">
    <a href="#home">Beranda</a>
    <a href="#layanan">Layanan</a>
    <a href="#portfolio">Portfolio</a>
    <a href="#pesan">Pesan Sekarang</a>
    <a href="#tracking">Lacak Pesanan</a>
    <a href="#media">Media</a>
  </nav>
</header>

<main>
<section class="hero" id="home">
  <div class="hero-overlay"></div>
  <div class="hero-content">
    <span class="eyebrow">CREATIVE DESIGN STUDIO</span>
    <h1>Wujudkan ide Anda menjadi <b>desain yang menarik.</b></h1>
    <p><?=e($tagline)?> — profesional, komunikatif, dan mudah dipantau dari awal sampai selesai.</p>
    <div class="hero-actions">
      <a class="btn primary" href="#pesan">Pesan Desain Sekarang</a>
      <a class="btn glass" href="#portfolio">Lihat Portfolio</a>
    </div>
    <div class="trust">
      <span>✓ Desain original</span>
      <span>✓ Proses transparan</span>
      <span>✓ Revisi sesuai kesepakatan</span>
    </div>
  </div>
</section>

<section class="section" id="layanan">
  <div class="section-head">
    <span class="eyebrow">LAYANAN</span>
    <h2>Solusi desain untuk kebutuhan Anda</h2>
    <p>Pilih layanan yang sesuai, jelaskan kebutuhan Anda, lalu pantau proses pengerjaan menggunakan kode tracking.</p>
  </div>
  <div class="cards">
    <article><div class="icon">✦</div><h3>Logo & Branding</h3><p>Identitas visual yang kuat untuk brand, usaha, komunitas, maupun kebutuhan personal.</p></article>
    <article><div class="icon">▣</div><h3>Banner & Spanduk</h3><p>Materi promosi digital maupun cetak dengan tampilan rapi, jelas, dan profesional.</p></article>
    <article><div class="icon">◈</div><h3>Mockup Produk</h3><p>Visual produk dan kemasan agar presentasi bisnis terlihat lebih menarik.</p></article>
    <article><div class="icon">▤</div><h3>Desain Kaos</h3><p>Desain apparel dan merchandise sesuai konsep dan karakter yang Anda inginkan.</p></article>
    <article><div class="icon">✉</div><h3>Undangan & Poster</h3><p>Undangan, poster, flyer, dan kebutuhan publikasi dengan informasi yang mudah dibaca.</p></article>
    <article><div class="icon">＋</div><h3>Custom Design</h3><p>Punya kebutuhan lain? Jelaskan konsep dan tujuan desain Anda pada form pemesanan.</p></article>
  </div>
</section>

<section class="section dark" id="portfolio">
  <div class="section-head">
    <span class="eyebrow">PORTFOLIO</span>
    <h2>Karya SaDesain.id</h2>
    <p>Gunakan karya berikut sebagai referensi sebelum menentukan konsep desain Anda.</p>
  </div>
  <div class="gallery">
  <?php foreach($portfolio as $item): ?>
    <button class="work" onclick="openPreview(this)" data-image="<?=e($item['image'])?>" data-title="<?=e($item['title'])?>">
      <img src="<?=e($item['image'])?>" alt="<?=e($item['title'])?>">
      <span><b><?=e($item['title'])?></b><small><?=e($item['description'])?></small></span>
    </button>
  <?php endforeach; ?>
  </div>
</section>

<section class="section order-section" id="pesan">
  <div class="section-head">
    <span class="eyebrow">PESANAN ONLINE</span>
    <h2>Pesan desain dengan jelas dan mudah</h2>
    <p>Isi data dan jelaskan kebutuhan desain Anda. Setelah dikirim, sistem akan memberikan kode tracking untuk memantau status pesanan.</p>
  </div>

  <div class="order-intro">
    💡 <strong>Tips:</strong> semakin lengkap informasi yang Anda berikan, semakin mudah desainer memahami hasil yang Anda inginkan.
  </div>

  <form id="orderForm" class="order-form">
    <div class="grid2">
      <label>
        Nama lengkap
        <small>Masukkan nama yang mudah kami hubungi.</small>
        <input name="customer_name" autocomplete="name" required placeholder="Contoh: Dendi Rahalus">
      </label>

      <label>
        Email
        <small>Gunakan email yang aktif.</small>
        <input type="email" name="email" autocomplete="email" required placeholder="nama@email.com">
      </label>

      <label>
        Nomor WhatsApp
        <small>Untuk komunikasi mengenai pesanan.</small>
        <input name="whatsapp" autocomplete="tel" placeholder="08xxxxxxxxxx" required>
      </label>

      <label>
        Jenis desain
        <small>Pilih kebutuhan utama Anda.</small>
        <select name="design_type" required>
          <option value="">— Pilih jenis desain —</option>
          <option>Logo & Branding</option>
          <option>Banner / Spanduk</option>
          <option>Mockup Produk</option>
          <option>Desain Kaos</option>
          <option>Undangan / Poster</option>
          <option>Custom Design</option>
        </select>
      </label>
    </div>

    <div class="price-info">💰 <strong>Harga desain ditentukan oleh admin</strong> setelah pesanan ditinjau. Harga akan ditampilkan pada status/lacak pesanan setelah admin menetapkannya.</div>

    <div class="reference-upload">
      <label for="referenceImage">Gambar contoh desain <span style="font-weight:700;color:#7c3aed">(opsional)</span></label>
      <input type="file" name="reference_image" id="referenceImage" accept="image/jpeg,image/png,image/webp">
      <small class="file-help">Kirim gambar referensi agar desainer lebih mudah memahami konsep Anda. Maksimal 2 MB • JPG, PNG, WEBP.</small>
      <img id="referencePreview" class="file-preview" alt="Pratinjau gambar contoh desain"><span id="referenceFileName" class="file-name"></span>
    </div>

    <label>
      Detail kebutuhan / catatan pesanan
      <small>Maksimal 50 kata. Jelaskan ukuran, warna, tulisan, konsep, referensi, deadline, dan informasi penting lainnya.</small>
      <textarea name="description" id="orderDescription" required maxlength="500" placeholder="Contoh: Saya membutuhkan desain logo untuk usaha kopi, warna hitam dan cokelat, gaya minimalis, digunakan untuk Instagram dan kemasan."></textarea>
      <small id="descriptionCounter" class="word-counter">0/50 kata</small>
    </label>

    <div class="order-actions">
      <button class="btn primary" type="submit">Kirim Pesanan & Buka WhatsApp →</button>
      <a class="btn whatsapp-btn" href="https://wa.me/<?=e($waNumber)?>?text=Halo%20SaDesain.id%2C%20saya%20ingin%20konsultasi%20dan%20memesan%20desain." target="_blank" rel="noopener noreferrer"><svg class="wa-icon" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M12.04 2A9.94 9.94 0 0 0 3.5 17.02L2 22l5.1-1.46A10 10 0 1 0 12.04 2Zm0 18.18c-1.58 0-3.13-.42-4.49-1.22l-.32-.19-3.03.87.9-2.95-.21-.32a8.2 8.2 0 1 1 7.15 3.81Zm4.5-6.13c-.25-.13-1.48-.73-1.71-.81-.23-.09-.4-.13-.57.13-.17.25-.65.81-.8.98-.15.17-.3.19-.55.06-.25-.13-1.05-.39-2-1.24-.74-.66-1.24-1.47-1.38-1.72-.14-.25-.02-.39.11-.52.11-.11.25-.3.38-.45.13-.15.17-.25.25-.42.08-.17.04-.32-.02-.45-.06-.13-.57-1.37-.78-1.88-.21-.5-.42-.43-.57-.44h-.49c-.17 0-.45.06-.68.32-.23.25-.89.87-.89 2.11s.91 2.45 1.04 2.62c.13.17 1.78 2.72 4.31 3.82.6.26 1.07.41 1.43.52.6.19 1.15.16 1.58.1.48-.07 1.48-.61 1.69-1.2.21-.59.21-1.09.15-1.2-.06-.11-.23-.17-.48-.3Z"/></svg><span>Chat WhatsApp</span></a>
    </div>

    <div id="orderResult" class="result" aria-live="polite"></div>
  </form>
</section>

<section class="section tracking" id="tracking">
  <div class="section-head">
    <span class="eyebrow">ORDER TRACKING</span>
    <h2>Pantau proses desain Anda</h2>
    <p>Masukkan kode tracking yang Anda terima setelah membuat pesanan.</p>
  </div>

  <form id="trackingForm" class="track-form">
    <input id="trackingCode" placeholder="Contoh: SD-ABC12345" autocomplete="off" required>
    <button class="btn primary" type="submit">Cek Status</button>
  </form>
  <div id="trackingResult" aria-live="polite"></div>
</section>

<section class="section social-section" id="media">
  <div class="section-head">
    <span class="eyebrow">MEDIA & KONTAK</span>
    <h2>Ikuti dan hubungi SaDesain.id</h2>
    <p>Klik ikon media di bawah untuk langsung membuka Instagram, Facebook, TikTok, atau email SaDesain.id.</p>
  </div>

  <div class="social-grid">
    <a class="social-card instagram" href="<?=e($instagram)?>" target="_blank" rel="noopener noreferrer" aria-label="Buka Instagram SaDesain.id">
      <span class="social-icon">
        <svg viewBox="0 0 24 24" fill="none"><rect x="3" y="3" width="18" height="18" rx="5" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="2"/><circle cx="17.5" cy="6.5" r="1.2" fill="currentColor"/></svg>
      </span>
      <span><b>Instagram</b><small>Lihat karya & update terbaru</small></span><span class="social-arrow">↗</span>
    </a>

    <a class="social-card facebook" href="<?=e($facebook)?>" target="_blank" rel="noopener noreferrer" aria-label="Buka Facebook SaDesain.id">
      <span class="social-icon">
        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M13.7 21v-8h2.7l.4-3h-3.1V8.1c0-.9.3-1.5 1.5-1.5h1.7V4a18 18 0 0 0-2.4-.1c-2.4 0-4 1.5-4 4.1V10H8v3h2.5v8h3.2Z"/></svg>
      </span>
      <span><b>Facebook</b><small>Ikuti halaman resmi kami</small></span><span class="social-arrow">↗</span>
    </a>

    <a class="social-card tiktok" href="<?=e($tiktok)?>" target="_blank" rel="noopener noreferrer" aria-label="Buka TikTok SaDesain.id">
      <span class="social-icon">
        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M15.2 3c.4 2.5 1.8 4 4.3 4.2v3.1a8.6 8.6 0 0 1-4.3-1.3v6.1a5.9 5.9 0 1 1-5.1-5.8v3.2a2.8 2.8 0 1 0 1.9 2.6V3h3.2Z"/></svg>
      </span>
      <span><b>TikTok</b><small>Lihat video & hasil desain</small></span><span class="social-arrow">↗</span>
    </a>

    <a class="social-card email" href="mailto:<?=e($email)?>?subject=Pesanan%20Desain%20SaDesain.id" aria-label="Kirim email ke SaDesain.id">
      <span class="social-icon">
        <svg viewBox="0 0 24 24" fill="none"><rect x="3" y="5" width="18" height="14" rx="2" stroke="currentColor" stroke-width="2"/><path d="m4 7 8 6 8-6" stroke="currentColor" stroke-width="2"/></svg>
      </span>
      <span><b>Email</b><small><?=e($email)?></small></span><span class="social-arrow">↗</span>
    </a>
  </div>

  <div class="social-cta">
    <div>
      <strong>Butuh konsultasi sebelum memesan?</strong>
      <span>Hubungi SaDesain.id melalui WhatsApp untuk membicarakan kebutuhan desain Anda.</span>
    </div>
    <a class="btn whatsapp-btn" href="https://wa.me/<?=e($waNumber)?>?text=Halo%20SaDesain.id%2C%20saya%20ingin%20konsultasi%20tentang%20desain." target="_blank" rel="noopener noreferrer"><svg class="wa-icon" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M12.04 2A9.94 9.94 0 0 0 3.5 17.02L2 22l5.1-1.46A10 10 0 1 0 12.04 2Zm0 18.18c-1.58 0-3.13-.42-4.49-1.22l-.32-.19-3.03.87.9-2.95-.21-.32a8.2 8.2 0 1 1 7.15 3.81Zm4.5-6.13c-.25-.13-1.48-.73-1.71-.81-.23-.09-.4-.13-.57.13-.17.25-.65.81-.8.98-.15.17-.3.19-.55.06-.25-.13-1.05-.39-2-1.24-.74-.66-1.24-1.47-1.38-1.72-.14-.25-.02-.39.11-.52.11-.11.25-.3.38-.45.13-.15.17-.25.25-.42.08-.17.04-.32-.02-.45-.06-.13-.57-1.37-.78-1.88-.21-.5-.42-.43-.57-.44h-.49c-.17 0-.45.06-.68.32-.23.25-.89.87-.89 2.11s.91 2.45 1.04 2.62c.13.17 1.78 2.72 4.31 3.82.6.26 1.07.41 1.43.52.6.19 1.15.16 1.58.1.48-.07 1.48-.61 1.69-1.2.21-.59.21-1.09.15-1.2-.06-.11-.23-.17-.48-.3Z"/></svg><span>Konsultasi WhatsApp</span></a>
  </div>
</section>
</main>

<footer>
  <div>
    <img src="<?=e($setting['logo'] ?? 'assets/logo.png')?>" alt="Logo SaDesain.id">
    <b><?=e($siteName)?></b>
    <p>Jasa desain kreatif untuk kebutuhan bisnis, personal, promosi, dan berbagai kebutuhan visual lainnya.</p>
  </div>
  <div>
    <h4>Kontak</h4>
    <p>Email: <a href="mailto:<?=e($email)?>"><?=e($email)?></a></p>
    <p>WhatsApp: <a href="https://wa.me/<?=e($waNumber)?>" target="_blank" rel="noopener noreferrer">Chat sekarang</a></p>
  </div>
  <div>
    <h4>Media</h4>
    <p><a href="<?=e($instagram)?>" target="_blank" rel="noopener noreferrer">Instagram</a></p>
    <p><a href="<?=e($facebook)?>" target="_blank" rel="noopener noreferrer">Facebook</a></p>
    <p><a href="<?=e($tiktok)?>" target="_blank" rel="noopener noreferrer">TikTok</a></p>
  </div>
</footer>

<!-- WHATSAPP MENGAMBANG -->
<a class="whatsapp-float"
   href="https://wa.me/<?=e($waNumber)?>?text=Halo%20SaDesain.id%2C%20saya%20ingin%20konsultasi%20atau%20memesan%20desain."
   target="_blank" rel="noopener noreferrer"
   aria-label="Chat WhatsApp SaDesain.id">
  <span class="wa-symbol" aria-hidden="true">
    <svg viewBox="0 0 24 24" fill="none">
      <path d="M20.5 3.5A11 11 0 0 0 3.2 17.1L2 22l5-1.3A11 11 0 1 0 20.5 3.5Z" fill="currentColor"/>
      <path d="M8.3 7.3c.3-.2.7-.2 1 0l1.1 1.8c.2.3.2.7 0 1l-.7.8c.7 1.3 1.7 2.3 3 3l.8-.7c.3-.2.7-.2 1 0l1.8 1.1c.3.2.4.6.2 1-.4.8-1.1 1.3-1.9 1.3-1.7-.1-4-1.4-5.6-3s-2.9-3.9-3-5.6c0-.7.5-1.5 1.3-1.9Z" fill="#25D366"/>
    </svg>
  </span>
  <span class="wa-text"><b>Chat WhatsApp</b><small>Pesan desain sekarang</small></span>
</a>

<div class="modal" id="preview" role="dialog" aria-modal="true">
  <button onclick="closePreview()" aria-label="Tutup">×</button>
  <img id="previewImg" alt="">
  <h3 id="previewTitle"></h3>
</div>

<script>
const $ = s => document.querySelector(s);

$('#menu')?.addEventListener('click', () => $('#navlinks').classList.toggle('open'));
document.querySelectorAll('#navlinks a').forEach(a => a.addEventListener('click', () => $('#navlinks').classList.remove('open')));

const io = new IntersectionObserver(entries => {
  entries.forEach(e => { if(e.isIntersecting) e.target.classList.add('show'); });
},{threshold:.08});
document.querySelectorAll('.cards article,.work,.section').forEach(x => x.classList.add('reveal'));
document.querySelectorAll('.reveal').forEach(x => io.observe(x));

function openPreview(el){
  $('#previewImg').src = el.dataset.image;
  $('#previewImg').alt = el.dataset.title || 'Portfolio SaDesain.id';
  $('#previewTitle').textContent = el.dataset.title || '';
  $('#preview').classList.add('show');
}
function closePreview(){ $('#preview').classList.remove('show'); }
$('#preview')?.addEventListener('click', e => { if(e.target.id === 'preview') closePreview(); });

function esc(v){
  return String(v ?? '').replace(/[&<>"']/g, c => ({
    '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'
  }[c]));
}

const descriptionField = $('#orderDescription');
const descriptionCounter = $('#descriptionCounter');
const referenceImage = $('#referenceImage');
const referenceFileName = $('#referenceFileName');
const referencePreview = $('#referencePreview');
let referenceObjectUrl = null;
referenceImage?.addEventListener('change', () => {
  const file=referenceImage.files?.[0];
  if(!file){if(referenceFileName) referenceFileName.textContent=''; if(referencePreview){referencePreview.style.display='none';referencePreview.removeAttribute('src');} return;}
  const allowed=['image/jpeg','image/png','image/webp'];
  if(!allowed.includes(file.type) || file.size>2*1024*1024){
    alert('Gambar harus JPG, PNG, atau WEBP dan maksimal 2 MB.');
    referenceImage.value='';
    if(referenceFileName) referenceFileName.textContent='';
    return;
  }
  if(referenceFileName) referenceFileName.textContent='✓ '+file.name;
  if(referenceObjectUrl) URL.revokeObjectURL(referenceObjectUrl);
  referenceObjectUrl=URL.createObjectURL(file);
  if(referencePreview){referencePreview.src=referenceObjectUrl;referencePreview.style.display='block';}
});
function countWords(text){
  return text.trim() ? text.trim().split(/\s+/).length : 0;
}
function updateDescriptionCounter(){
  if(!descriptionField || !descriptionCounter) return countWords(descriptionField?.value || '');
  const count = countWords(descriptionField.value);
  descriptionCounter.textContent = `${count}/50 kata`;
  descriptionCounter.style.color = count > 50 ? '#dc2626' : '';
  return count;
}
descriptionField?.addEventListener('input', () => {
  let count = countWords(descriptionField.value);
  if(count > 50){
    descriptionField.value = descriptionField.value.trim().split(/\s+/).slice(0,50).join(' ');
  }
  updateDescriptionCounter();
});

$('#orderForm')?.addEventListener('submit', async e => {
  e.preventDefault();

  const button = e.target.querySelector('button[type="submit"]');
  const oldText = button.textContent;
  button.disabled = true;
  button.textContent = 'Memproses pesanan...';

  const box = $('#orderResult');
  const wordCount = updateDescriptionCounter();
  if(wordCount > 50){
    box.innerHTML = '<div class="success" style="background:#fff1f2;color:#9f1239;border-color:#fecdd3">Catatan pesanan maksimal 50 kata.</div>';
    button.disabled = false;
    button.textContent = oldText;
    return;
  }

  const fd = new FormData(e.target);

  try{
    const r = await fetch('api/order_create.php',{method:'POST',body:fd})
      .then(x => x.json());

    if(r.ok){
      const data = Object.fromEntries(fd.entries());
      const waText =
        `Halo SaDesain.id, saya ingin memesan desain.\n\n` +
        `Kode Tracking: ${r.tracking_code}\n` +
        `Nama: ${data.customer_name}\n` +
        `Email: ${data.email}\n` +
        `WhatsApp: ${data.whatsapp}\n` +
        `Jenis Desain: ${data.design_type}\n` +
        `Detail: ${data.description}`;

      const waUrl = 'https://wa.me/<?=e($waNumber)?>?text=' + encodeURIComponent(waText);

      box.innerHTML =
        `<div class="success">
          <b>Pesanan berhasil dibuat!</b><br>
          Kode tracking Anda: <strong>${esc(r.tracking_code)}</strong><br>
          Simpan kode ini untuk memantau proses desain.
          <div class="result-actions">
            <a class="btn primary" href="${waUrl}" target="_blank" rel="noopener"><svg class="wa-icon" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M12.04 2A9.94 9.94 0 0 0 3.5 17.02L2 22l5.1-1.46A10 10 0 1 0 12.04 2Zm0 18.18c-1.58 0-3.13-.42-4.49-1.22l-.32-.19-3.03.87.9-2.95-.21-.32a8.2 8.2 0 1 1 7.15 3.81Zm4.5-6.13c-.25-.13-1.48-.73-1.71-.81-.23-.09-.4-.13-.57.13-.17.25-.65.81-.8.98-.15.17-.3.19-.55.06-.25-.13-1.05-.39-2-1.24-.74-.66-1.24-1.47-1.38-1.72-.14-.25-.02-.39.11-.52.11-.11.25-.3.38-.45.13-.15.17-.25.25-.42.08-.17.04-.32-.02-.45-.06-.13-.57-1.37-.78-1.88-.21-.5-.42-.43-.57-.44h-.49c-.17 0-.45.06-.68.32-.23.25-.89.87-.89 2.11s.91 2.45 1.04 2.62c.13.17 1.78 2.72 4.31 3.82.6.26 1.07.41 1.43.52.6.19 1.15.16 1.58.1.48-.07 1.48-.61 1.69-1.2.21-.59.21-1.09.15-1.2-.06-.11-.23-.17-.48-.3Z"/></svg><span>Kirim Detail ke WhatsApp</span></a>
            <a class="btn secondary" href="#tracking" onclick="document.querySelector('#trackingCode').value=${JSON.stringify(r.tracking_code)}">Lacak Pesanan</a>
          </div>
        </div>`;

      window.open(waUrl,'_blank','noopener');
      e.target.reset();
      if(referenceFileName) referenceFileName.textContent='';
      if(referencePreview){referencePreview.style.display='none';referencePreview.removeAttribute('src');}
    }else{
      box.innerHTML = `<div class="success" style="background:#fff1f2;color:#9f1239;border-color:#fecdd3">${esc(r.message || 'Pesanan gagal dibuat.')}</div>`;
    }
  }catch(err){
    box.innerHTML = `<div class="success" style="background:#fff1f2;color:#9f1239;border-color:#fecdd3">Server tidak dapat dihubungi. Pastikan Apache, PHP, dan MySQL/XAMPP sedang berjalan.</div>`;
  }finally{
    button.disabled = false;
    button.textContent = oldText;
  }
});

$('#trackingForm')?.addEventListener('submit', async e => {
  e.preventDefault();
  const code = $('#trackingCode').value.trim();
  const box = $('#trackingResult');
  box.innerHTML = '<div class="track-card">Memeriksa status pesanan...</div>';

  try{
    const r = await fetch('api/order_track.php?code=' + encodeURIComponent(code)).then(x => x.json());

    if(!r.ok){
      box.innerHTML = `<div class="track-card"><b>${esc(r.message || 'Pesanan tidak ditemukan.')}</b></div>`;
      return;
    }

    const order = r.order;
    const statuses = ['Menunggu','Diterima','Diproses','Revisi','Selesai'];
    const idx = statuses.indexOf(order.status);

    box.innerHTML = `
      <div class="track-card">
        <h3>${esc(order.tracking_code)}</h3>
        <p><b>${esc(order.customer_name)}</b> — ${esc(order.design_type)}</p>
        <span class="status">${esc(order.status)}</span>
        <div class="steps">
          ${statuses.map((s,i)=>`<div class="step ${i<=idx?'active':''}">${s}</div>`).join('')}
        </div>
        <p><b>Designer:</b> ${esc(order.designer_name || 'Akan ditentukan admin')}</p>
        <p><b>Harga desain:</b> ${esc(order.price || 'Akan ditentukan admin')}</p>
        ${order.admin_note ? `<div class="note"><b>Catatan dari SaDesain:</b><br>${esc(order.admin_note)}</div>` : ''}
        ${order.result_file && order.status === 'Selesai' ? `<div class="result-download">
          <b>Hasil desain sudah selesai</b>
          <p>File hasil desain Anda sudah tersedia dan dapat diunduh langsung.</p>
          <small>${esc(order.result_original_name || 'Hasil desain')}</small><br>
          <a class="btn primary download-result" href="api/order_result_download.php?code=${encodeURIComponent(order.tracking_code)}">⬇ Download Hasil Desain</a>
        </div>` : (order.result_file ? `<div class="result-download pending"><b>Hasil desain sedang diproses</b><p>File sudah diterima admin dan akan tersedia setelah pesanan dinyatakan selesai.</p></div>` : '')}
        <small>Terakhir diperbarui: ${esc(order.updated_at)}</small>
      </div>`;
  }catch(err){
    box.innerHTML = '<div class="track-card"><b>Gagal menghubungi server. Pastikan XAMPP/hosting aktif.</b></div>';
  }
});
</script>
</body>
</html>

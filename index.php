<?php
include 'config.php';
render_header('Dashboard', 'index.php');

$cards = [
    ['Kategori Produk', 'Kelola kelompok dan deskripsi kategori produk.', 'kategori.php', '#D946EF', '01'],
    ['Produk', 'Atur katalog, harga, stok, dan tanggal kedaluwarsa.', 'produk.php', '#22D3EE', '02'],
    ['Pemasok', 'Simpan informasi pemasok dan kontak.', 'pemasok.php', '#FACC15', '03'],
    ['Pegawai', 'Kelola akun, jabatan, dan status pegawai.', 'pegawai.php', '#3B82F6', '04'],
    ['Pelanggan', 'Kelola data pelanggan dan poin member.', 'pelanggan.php', '#22C55E', '05'],
    ['Penjualan', 'Catat transaksi kasir, termasuk pelanggan umum.', 'penjualan.php', '#D946EF', '06'],
    ['Detail Penjualan', 'Kelola rincian produk pada setiap transaksi.', 'detail_penjualan.php', '#22D3EE', '07'],
    ['Pembayaran', 'Catat metode, jumlah, dan status pembayaran.', 'pembayaran.php', '#FACC15', '08'],
    ['Stok Masuk', 'Pantau penerimaan stok dari pemasok.', 'stok_masuk.php', '#3B82F6', '09'],
];
?>
<section class="row align-items-center g-4 mb-5">
    <div class="col-lg-8">
        <div class="eyebrow mb-2">Operasional supermarket</div>
        <h1 class="page-title display-5 mb-3">Semua kebutuhan toko,<br><span style="color:#D946EF">dalam satu dashboard.</span></h1>
        <p class="lead text-secondary mb-0">Kelola produk, pelanggan, penjualan, pembayaran, dan stok dengan lebih mudah.</p>
    </div>
    <div class="col-lg-4">
        <div class="p-4 p-lg-5 rounded-4 text-dark" style="background:linear-gradient(135deg,#FDF4FF,#CFFAFE)">
            <div class="eyebrow">ShopVibe management</div>
            <div class="h3 fw-bold mt-2 mb-2">Siap melayani.</div>
            <p class="mb-0 text-secondary">Pilih modul untuk mulai mengelola data supermarket.</p>
        </div>
    </div>
</section>
<section>
    <div class="d-flex align-items-end justify-content-between mb-3">
        <div><div class="eyebrow">Modul data</div><h2 class="h3 fw-bold mb-0">Kelola sistem</h2></div>
        <span class="badge rounded-pill text-bg-light border"><?= count($cards) ?> modul</span>
    </div>
    <div class="row g-3">
        <?php foreach ($cards as $card): ?>
            <div class="col-md-6 col-xl-4">
                <a class="card h-100 p-4 text-decoration-none text-dark" href="<?= e($card[2]) ?>">
                    <div class="d-flex justify-content-between align-items-start">
                        <span class="badge rounded-pill" style="background:<?= e($card[3]) ?>20;color:<?= e($card[3]) ?>">MODUL <?= e($card[4]) ?></span>
                        <span class="fw-bold" style="color:#D946EF">↗</span>
                    </div>
                    <h3 class="h5 fw-bold mt-4 mb-2"><?= e($card[0]) ?></h3>
                    <p class="text-secondary mb-0"><?= e($card[1]) ?></p>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php render_footer(); ?>

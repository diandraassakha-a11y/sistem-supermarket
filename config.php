<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$conn = new mysqli('localhost', 'root', '', 'db_sistem_supermarket');
$conn->set_charset('utf8mb4');

function e($value): string
{
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
}

function render_header(string $title, string $active = ''): void
{
    $menus = [
        'index.php' => 'Dashboard',
        'kategori.php' => 'Kategori',
        'produk.php' => 'Produk',
        'pemasok.php' => 'Pemasok',
        'pegawai.php' => 'Pegawai',
        'pelanggan.php' => 'Pelanggan',
        'penjualan.php' => 'Penjualan',
        'detail_penjualan.php' => 'Detail Penjualan',
        'pembayaran.php' => 'Pembayaran',
        'stok_masuk.php' => 'Stok Masuk',
    ];
    ?>
    <!doctype html>
    <html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= e($title) ?> | Sistem Supermarket</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            :root { --shop-primary:#D946EF; --shop-cyan:#22D3EE; --shop-yellow:#FACC15; --shop-bg:#FAFAFA; --shop-ink:#171717; }
            body { background:var(--shop-bg); color:var(--shop-ink); font-family:'Nunito',sans-serif; }
            h1,h2,h3,.navbar-brand { font-family:'Poppins',sans-serif; }
            .navbar { background:#fff; box-shadow:0 2px 12px #0000000a; }
            .navbar-brand { color:var(--shop-primary)!important; font-weight:800; letter-spacing:-.03em; }
            .nav-link { border-radius:999px; color:#525252; font-weight:700; padding:.55rem .85rem!important; }
            .nav-link:hover,.nav-link.active { background:#FDF4FF; color:var(--shop-primary)!important; }
            .page-title { font-weight:700; letter-spacing:-.03em; }
            .card { border:1px solid #eee; border-radius:16px; box-shadow:0 4px 12px #00000008; }
            .btn-primary { --bs-btn-bg:var(--shop-primary); --bs-btn-border-color:var(--shop-primary); --bs-btn-hover-bg:#C026D3; --bs-btn-hover-border-color:#C026D3; --bs-btn-active-bg:#A21CAF; border-radius:999px; font-weight:800; padding:.6rem 1.3rem; }
            .btn-outline-primary { --bs-btn-color:var(--shop-primary); --bs-btn-border-color:var(--shop-primary); --bs-btn-hover-bg:#FDF4FF; --bs-btn-hover-color:var(--shop-primary); --bs-btn-hover-border-color:var(--shop-primary); border-radius:999px; font-weight:700; }
            .btn-danger { border-radius:999px; }
            .form-control,.form-select { min-height:44px; border:1.5px solid #D4D4D4; border-radius:12px; }
            .form-control:focus,.form-select:focus { border-color:var(--shop-primary); box-shadow:0 0 0 3px #D946EF26; }
            .table thead th { background:#FDF4FF; color:#701A75; border-bottom:0; white-space:nowrap; }
            .table td { vertical-align:middle; }
            .table-responsive { border-radius:12px; }
            .eyebrow { color:#A21CAF; font-size:.75rem; font-weight:800; letter-spacing:.12em; text-transform:uppercase; }
        </style>
    </head>
    <body>
    <nav class="navbar navbar-expand-xl sticky-top">
        <div class="container-fluid px-3 px-lg-4">
            <a class="navbar-brand" href="index.php">Shop<span style="color:#22D3EE">Vibe</span></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Buka navigasi">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <div class="navbar-nav ms-auto flex-wrap">
                    <?php foreach ($menus as $url => $label): ?>
                        <a class="nav-link <?= $active === $url ? 'active' : '' ?>" href="<?= e($url) ?>"><?= e($label) ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </nav>
    <main class="container-fluid px-3 px-lg-4 py-4 py-lg-5">
    <?php
}

function render_footer(): void
{
    ?>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
    <?php
}

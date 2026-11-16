<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = $_POST['aksi'] ?? '';
    $id = (int) ($_POST['id_pembayaran'] ?? 0);
    if ($aksi === 'hapus' && $id > 0) {
        $stmt = $conn->prepare('DELETE FROM pembayaran WHERE id_pembayaran = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        header('Location: pembayaran.php');
        exit;
    }
    if ($aksi === 'simpan') {
        $idPenjualan = (int) ($_POST['id_penjualan'] ?? 0);
        $metode = $_POST['metode_pembayaran'] ?? '';
        $jumlah = (float) ($_POST['jumlah_bayar'] ?? 0);
        $waktu = str_replace('T', ' ', trim($_POST['waktu_pembayaran'] ?? ''));
        if (strlen($waktu) === 16) {
            $waktu .= ':00';
        }
        $status = $_POST['status_pembayaran'] ?? 'Berhasil';
        if ($id > 0) {
            $stmt = $conn->prepare('UPDATE pembayaran SET id_penjualan=?, metode_pembayaran=?, jumlah_bayar=?, waktu_pembayaran=?, status_pembayaran=? WHERE id_pembayaran=?');
            $stmt->bind_param('isdssi', $idPenjualan, $metode, $jumlah, $waktu, $status, $id);
        } else {
            $stmt = $conn->prepare('INSERT INTO pembayaran (id_penjualan, metode_pembayaran, jumlah_bayar, waktu_pembayaran, status_pembayaran) VALUES (?, ?, ?, ?, ?)');
            $stmt->bind_param('isdss', $idPenjualan, $metode, $jumlah, $waktu, $status);
        }
        $stmt->execute();
        header('Location: pembayaran.php');
        exit;
    }
}

$edit = null;
if (isset($_GET['edit'])) {
    $idEdit = (int) $_GET['edit'];
    $stmt = $conn->prepare('SELECT * FROM pembayaran WHERE id_pembayaran = ?');
    $stmt->bind_param('i', $idEdit);
    $stmt->execute();
    $edit = $stmt->get_result()->fetch_assoc();
}
$penjualan = $conn->query('SELECT id_penjualan, nomor_struk, total_bayar FROM penjualan ORDER BY id_penjualan DESC');
$rows = $conn->query('SELECT b.*, j.nomor_struk, j.total_bayar FROM pembayaran b JOIN penjualan j ON j.id_penjualan=b.id_penjualan ORDER BY b.id_pembayaran DESC');
render_header('Pembayaran', 'pembayaran.php');
?>
<div class="mb-4"><div class="eyebrow">Pembayaran transaksi</div><h1 class="page-title h2 mb-0">Pembayaran</h1></div>
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card p-4">
            <h2 class="h5 fw-bold mb-3"><?= $edit ? 'Ubah pembayaran' : 'Tambah pembayaran' ?></h2>
            <form method="post">
                <input type="hidden" name="aksi" value="simpan"><input type="hidden" name="id_pembayaran" value="<?= e($edit['id_pembayaran'] ?? '') ?>">
                <div class="mb-3"><label class="form-label fw-bold">Transaksi</label><select class="form-select" name="id_penjualan" required><option value="">Pilih transaksi</option><?php while ($item = $penjualan->fetch_assoc()): ?><option value="<?= e($item['id_penjualan']) ?>" <?= (string) ($edit['id_penjualan'] ?? '') === (string) $item['id_penjualan'] ? 'selected' : '' ?>><?= e($item['nomor_struk']) ?> — Rp <?= number_format((float) $item['total_bayar'], 2, ',', '.') ?></option><?php endwhile; ?></select></div>
                <div class="mb-3"><label class="form-label fw-bold">Metode pembayaran</label><select class="form-select" name="metode_pembayaran" required><?php foreach (['Tunai', 'Debit', 'Kredit', 'QRIS', 'E-Wallet'] as $metode): ?><option value="<?= e($metode) ?>" <?= ($edit['metode_pembayaran'] ?? '') === $metode ? 'selected' : '' ?>><?= e($metode) ?></option><?php endforeach; ?></select></div>
                <div class="mb-3"><label class="form-label fw-bold">Jumlah bayar</label><input class="form-control" type="number" name="jumlah_bayar" min="0" step="0.01" required value="<?= e($edit['jumlah_bayar'] ?? '0.00') ?>"></div>
                <div class="mb-3"><label class="form-label fw-bold">Waktu pembayaran</label><input class="form-control" type="datetime-local" name="waktu_pembayaran" required value="<?= e(isset($edit['waktu_pembayaran']) ? str_replace(' ', 'T', substr($edit['waktu_pembayaran'], 0, 16)) : date('Y-m-d\TH:i')) ?>"></div>
                <div class="mb-3"><label class="form-label fw-bold">Status pembayaran</label><select class="form-select" name="status_pembayaran" required><?php foreach (['Berhasil', 'Menunggu', 'Gagal', 'Dikembalikan'] as $status): ?><option value="<?= e($status) ?>" <?= ($edit['status_pembayaran'] ?? 'Berhasil') === $status ? 'selected' : '' ?>><?= e($status) ?></option><?php endforeach; ?></select></div>
                <button class="btn btn-primary" type="submit">Simpan pembayaran</button>
                <?php if ($edit): ?><a class="btn btn-outline-primary ms-1" href="pembayaran.php">Batal</a><?php endif; ?>
            </form>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card p-3 p-lg-4">
            <div class="d-flex justify-content-between align-items-center mb-3"><h2 class="h5 fw-bold mb-0">Daftar pembayaran</h2><span class="text-secondary small"><?= $rows->num_rows ?> data</span></div>
            <div class="table-responsive"><table class="table table-hover align-middle mb-0">
                <thead><tr><th>ID</th><th>Nomor struk</th><th>Total transaksi</th><th>Metode</th><th>Jumlah dibayar</th><th>Waktu</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                <?php while ($row = $rows->fetch_assoc()): ?>
                    <tr><td><?= e($row['id_pembayaran']) ?></td><td class="fw-bold"><?= e($row['nomor_struk']) ?></td><td>Rp <?= number_format((float) $row['total_bayar'], 2, ',', '.') ?></td><td><?= e($row['metode_pembayaran']) ?></td><td>Rp <?= number_format((float) $row['jumlah_bayar'], 2, ',', '.') ?></td><td><?= e($row['waktu_pembayaran']) ?></td><td><?= e($row['status_pembayaran']) ?></td>
                    <td class="text-end text-nowrap"><a class="btn btn-sm btn-outline-primary" href="?edit=<?= e($row['id_pembayaran']) ?>">Ubah</a>
                        <form class="d-inline" method="post" onsubmit="return confirm('Hapus pembayaran ini?')"><input type="hidden" name="aksi" value="hapus"><input type="hidden" name="id_pembayaran" value="<?= e($row['id_pembayaran']) ?>"><button class="btn btn-sm btn-danger" type="submit">Hapus</button></form>
                    </td></tr>
                <?php endwhile; ?>
                <?php if ($rows->num_rows === 0): ?><tr><td class="text-center text-secondary py-4" colspan="8">Belum ada pembayaran.</td></tr><?php endif; ?>
                </tbody>
            </table></div>
        </div>
    </div>
</div>
<?php render_footer(); ?>

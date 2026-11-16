<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = $_POST['aksi'] ?? '';
    $id = (int) ($_POST['id_stok_masuk'] ?? 0);
    if ($aksi === 'hapus' && $id > 0) {
        $stmt = $conn->prepare('DELETE FROM stok_masuk WHERE id_stok_masuk = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        header('Location: stok_masuk.php');
        exit;
    }
    if ($aksi === 'simpan') {
        $idProduk = (int) ($_POST['id_produk'] ?? 0);
        $idPemasok = (int) ($_POST['id_pemasok'] ?? 0);
        $idPegawai = (int) ($_POST['id_pegawai'] ?? 0);
        $tanggal = str_replace('T', ' ', trim($_POST['tanggal_masuk'] ?? ''));
        if (strlen($tanggal) === 16) {
            $tanggal .= ':00';
        }
        $jumlah = (int) ($_POST['jumlah_masuk'] ?? 0);
        $harga = (float) ($_POST['harga_beli_satuan'] ?? 0);
        $catatan = trim($_POST['catatan'] ?? '');
        $catatan = $catatan !== '' ? $catatan : null;
        if ($id > 0) {
            $stmt = $conn->prepare('UPDATE stok_masuk SET id_produk=?, id_pemasok=?, id_pegawai=?, tanggal_masuk=?, jumlah_masuk=?, harga_beli_satuan=?, catatan=? WHERE id_stok_masuk=?');
            $stmt->bind_param('iiisidsi', $idProduk, $idPemasok, $idPegawai, $tanggal, $jumlah, $harga, $catatan, $id);
        } else {
            $stmt = $conn->prepare('INSERT INTO stok_masuk (id_produk, id_pemasok, id_pegawai, tanggal_masuk, jumlah_masuk, harga_beli_satuan, catatan) VALUES (?, ?, ?, ?, ?, ?, ?)');
            $stmt->bind_param('iiisids', $idProduk, $idPemasok, $idPegawai, $tanggal, $jumlah, $harga, $catatan);
        }
        $stmt->execute();
        header('Location: stok_masuk.php');
        exit;
    }
}

$edit = null;
if (isset($_GET['edit'])) {
    $idEdit = (int) $_GET['edit'];
    $stmt = $conn->prepare('SELECT * FROM stok_masuk WHERE id_stok_masuk = ?');
    $stmt->bind_param('i', $idEdit);
    $stmt->execute();
    $edit = $stmt->get_result()->fetch_assoc();
}
$produk = $conn->query('SELECT id_produk, nama_produk FROM produk ORDER BY nama_produk');
$pemasok = $conn->query('SELECT id_pemasok, nama_pemasok FROM pemasok ORDER BY nama_pemasok');
$pegawai = $conn->query('SELECT id_pegawai, nama_pegawai FROM pegawai ORDER BY nama_pegawai');
$rows = $conn->query('SELECT s.*, p.nama_produk, v.nama_pemasok, e.nama_pegawai FROM stok_masuk s JOIN produk p ON p.id_produk=s.id_produk JOIN pemasok v ON v.id_pemasok=s.id_pemasok JOIN pegawai e ON e.id_pegawai=s.id_pegawai ORDER BY s.id_stok_masuk DESC');
render_header('Stok Masuk', 'stok_masuk.php');
?>
<div class="mb-4"><div class="eyebrow">Penerimaan barang</div><h1 class="page-title h2 mb-0">Stok Masuk</h1></div>
<div class="row g-4">
    <div class="col-xl-4">
        <div class="card p-4">
            <h2 class="h5 fw-bold mb-3"><?= $edit ? 'Ubah penerimaan' : 'Catat stok masuk' ?></h2>
            <form method="post">
                <input type="hidden" name="aksi" value="simpan"><input type="hidden" name="id_stok_masuk" value="<?= e($edit['id_stok_masuk'] ?? '') ?>">
                <div class="mb-3"><label class="form-label fw-bold">Produk</label><select class="form-select" name="id_produk" required><option value="">Pilih produk</option><?php while ($item = $produk->fetch_assoc()): ?><option value="<?= e($item['id_produk']) ?>" <?= (string) ($edit['id_produk'] ?? '') === (string) $item['id_produk'] ? 'selected' : '' ?>><?= e($item['nama_produk']) ?></option><?php endwhile; ?></select></div>
                <div class="mb-3"><label class="form-label fw-bold">Pemasok</label><select class="form-select" name="id_pemasok" required><option value="">Pilih pemasok</option><?php while ($item = $pemasok->fetch_assoc()): ?><option value="<?= e($item['id_pemasok']) ?>" <?= (string) ($edit['id_pemasok'] ?? '') === (string) $item['id_pemasok'] ? 'selected' : '' ?>><?= e($item['nama_pemasok']) ?></option><?php endwhile; ?></select></div>
                <div class="mb-3"><label class="form-label fw-bold">Pegawai penerima</label><select class="form-select" name="id_pegawai" required><option value="">Pilih pegawai</option><?php while ($item = $pegawai->fetch_assoc()): ?><option value="<?= e($item['id_pegawai']) ?>" <?= (string) ($edit['id_pegawai'] ?? '') === (string) $item['id_pegawai'] ? 'selected' : '' ?>><?= e($item['nama_pegawai']) ?></option><?php endwhile; ?></select></div>
                <div class="mb-3"><label class="form-label fw-bold">Tanggal masuk</label><input class="form-control" type="datetime-local" name="tanggal_masuk" required value="<?= e(isset($edit['tanggal_masuk']) ? str_replace(' ', 'T', substr($edit['tanggal_masuk'], 0, 16)) : date('Y-m-d\TH:i')) ?>"></div>
                <div class="mb-3"><label class="form-label fw-bold">Jumlah masuk</label><input class="form-control" type="number" name="jumlah_masuk" min="1" required value="<?= e($edit['jumlah_masuk'] ?? '1') ?>"></div>
                <div class="mb-3"><label class="form-label fw-bold">Harga beli satuan</label><input class="form-control" type="number" name="harga_beli_satuan" min="0" step="0.01" required value="<?= e($edit['harga_beli_satuan'] ?? '0.00') ?>"></div>
                <div class="mb-3"><label class="form-label fw-bold">Catatan <span class="text-secondary fw-normal">(opsional)</span></label><textarea class="form-control" name="catatan" rows="3"><?= e($edit['catatan'] ?? '') ?></textarea></div>
                <button class="btn btn-primary" type="submit">Simpan stok masuk</button>
                <?php if ($edit): ?><a class="btn btn-outline-primary ms-1" href="stok_masuk.php">Batal</a><?php endif; ?>
            </form>
        </div>
    </div>
    <div class="col-xl-8">
        <div class="card p-3 p-lg-4">
            <div class="d-flex justify-content-between align-items-center mb-3"><h2 class="h5 fw-bold mb-0">Riwayat stok masuk</h2><span class="text-secondary small"><?= $rows->num_rows ?> data</span></div>
            <div class="table-responsive"><table class="table table-hover align-middle mb-0">
                <thead><tr><th>ID</th><th>Tanggal</th><th>Produk</th><th>Pemasok</th><th>Pegawai</th><th>Jumlah</th><th>Harga beli</th><th>Catatan</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                <?php while ($row = $rows->fetch_assoc()): ?>
                    <tr><td><?= e($row['id_stok_masuk']) ?></td><td><?= e($row['tanggal_masuk']) ?></td><td class="fw-bold"><?= e($row['nama_produk']) ?></td><td><?= e($row['nama_pemasok']) ?></td><td><?= e($row['nama_pegawai']) ?></td><td><?= e($row['jumlah_masuk']) ?></td><td>Rp <?= number_format((float) $row['harga_beli_satuan'], 2, ',', '.') ?></td><td><?= nl2br(e($row['catatan'])) ?></td>
                    <td class="text-end text-nowrap"><a class="btn btn-sm btn-outline-primary" href="?edit=<?= e($row['id_stok_masuk']) ?>">Ubah</a>
                        <form class="d-inline" method="post" onsubmit="return confirm('Hapus catatan stok masuk ini?')"><input type="hidden" name="aksi" value="hapus"><input type="hidden" name="id_stok_masuk" value="<?= e($row['id_stok_masuk']) ?>"><button class="btn btn-sm btn-danger" type="submit">Hapus</button></form>
                    </td></tr>
                <?php endwhile; ?>
                <?php if ($rows->num_rows === 0): ?><tr><td class="text-center text-secondary py-4" colspan="9">Belum ada catatan stok masuk.</td></tr><?php endif; ?>
                </tbody>
            </table></div>
        </div>
    </div>
</div>
<?php render_footer(); ?>

<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = $_POST['aksi'] ?? '';
    $id = (int) ($_POST['id_produk'] ?? 0);
    if ($aksi === 'hapus' && $id > 0) {
        $stmt = $conn->prepare('DELETE FROM produk WHERE id_produk = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        header('Location: produk.php');
        exit;
    }
    if ($aksi === 'simpan') {
        $idKategori = (int) ($_POST['id_kategori'] ?? 0);
        $idPemasok = (int) ($_POST['id_pemasok'] ?? 0);
        $kode = trim($_POST['kode_produk'] ?? '');
        $barcode = trim($_POST['barcode'] ?? '');
        $nama = trim($_POST['nama_produk'] ?? '');
        $merek = trim($_POST['merek'] ?? '');
        $satuan = trim($_POST['satuan'] ?? '');
        $hargaBeli = (float) ($_POST['harga_beli'] ?? 0);
        $hargaJual = (float) ($_POST['harga_jual'] ?? 0);
        $stok = (int) ($_POST['stok'] ?? 0);
        $stokMinimum = (int) ($_POST['stok_minimum'] ?? 0);
        $kedaluwarsa = trim($_POST['tanggal_kedaluwarsa'] ?? '');
        $barcode = $barcode !== '' ? $barcode : null;
        $merek = $merek !== '' ? $merek : null;
        $kedaluwarsa = $kedaluwarsa !== '' ? $kedaluwarsa : null;
        if ($id > 0) {
            $stmt = $conn->prepare('UPDATE produk SET id_kategori=?, id_pemasok=?, kode_produk=?, barcode=?, nama_produk=?, merek=?, satuan=?, harga_beli=?, harga_jual=?, stok=?, stok_minimum=?, tanggal_kedaluwarsa=? WHERE id_produk=?');
            $stmt->bind_param('iisssssddiisi', $idKategori, $idPemasok, $kode, $barcode, $nama, $merek, $satuan, $hargaBeli, $hargaJual, $stok, $stokMinimum, $kedaluwarsa, $id);
        } else {
            $stmt = $conn->prepare('INSERT INTO produk (id_kategori, id_pemasok, kode_produk, barcode, nama_produk, merek, satuan, harga_beli, harga_jual, stok, stok_minimum, tanggal_kedaluwarsa) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
            $stmt->bind_param('iisssssddiis', $idKategori, $idPemasok, $kode, $barcode, $nama, $merek, $satuan, $hargaBeli, $hargaJual, $stok, $stokMinimum, $kedaluwarsa);
        }
        $stmt->execute();
        header('Location: produk.php');
        exit;
    }
}

$edit = null;
if (isset($_GET['edit'])) {
    $idEdit = (int) $_GET['edit'];
    $stmt = $conn->prepare('SELECT * FROM produk WHERE id_produk = ?');
    $stmt->bind_param('i', $idEdit);
    $stmt->execute();
    $edit = $stmt->get_result()->fetch_assoc();
}
$kategori = $conn->query('SELECT id_kategori, nama_kategori FROM kategori ORDER BY nama_kategori');
$pemasok = $conn->query('SELECT id_pemasok, nama_pemasok FROM pemasok ORDER BY nama_pemasok');
$rows = $conn->query('SELECT p.*, k.nama_kategori, s.nama_pemasok FROM produk p JOIN kategori k ON k.id_kategori=p.id_kategori JOIN pemasok s ON s.id_pemasok=p.id_pemasok ORDER BY p.id_produk DESC');
render_header('Produk', 'produk.php');
?>
<div class="mb-4"><div class="eyebrow">Katalog dan inventaris</div><h1 class="page-title h2 mb-0">Produk</h1></div>
<div class="row g-4">
    <div class="col-xl-4">
        <div class="card p-4">
            <h2 class="h5 fw-bold mb-3"><?= $edit ? 'Ubah produk' : 'Tambah produk' ?></h2>
            <form method="post">
                <input type="hidden" name="aksi" value="simpan"><input type="hidden" name="id_produk" value="<?= e($edit['id_produk'] ?? '') ?>">
                <div class="mb-3"><label class="form-label fw-bold">Kategori</label><select class="form-select" name="id_kategori" required><option value="">Pilih kategori</option><?php while ($item = $kategori->fetch_assoc()): ?><option value="<?= e($item['id_kategori']) ?>" <?= (string) ($edit['id_kategori'] ?? '') === (string) $item['id_kategori'] ? 'selected' : '' ?>><?= e($item['nama_kategori']) ?></option><?php endwhile; ?></select></div>
                <div class="mb-3"><label class="form-label fw-bold">Pemasok</label><select class="form-select" name="id_pemasok" required><option value="">Pilih pemasok</option><?php while ($item = $pemasok->fetch_assoc()): ?><option value="<?= e($item['id_pemasok']) ?>" <?= (string) ($edit['id_pemasok'] ?? '') === (string) $item['id_pemasok'] ? 'selected' : '' ?>><?= e($item['nama_pemasok']) ?></option><?php endwhile; ?></select></div>
                <div class="mb-3"><label class="form-label fw-bold">Kode produk</label><input class="form-control" name="kode_produk" maxlength="30" required value="<?= e($edit['kode_produk'] ?? '') ?>"></div>
                <div class="mb-3"><label class="form-label fw-bold">Barcode <span class="text-secondary fw-normal">(opsional)</span></label><input class="form-control" name="barcode" maxlength="50" value="<?= e($edit['barcode'] ?? '') ?>"></div>
                <div class="mb-3"><label class="form-label fw-bold">Nama produk</label><input class="form-control" name="nama_produk" maxlength="150" required value="<?= e($edit['nama_produk'] ?? '') ?>"></div>
                <div class="mb-3"><label class="form-label fw-bold">Merek</label><input class="form-control" name="merek" maxlength="100" value="<?= e($edit['merek'] ?? '') ?>"></div>
                <div class="mb-3"><label class="form-label fw-bold">Satuan</label><input class="form-control" name="satuan" maxlength="30" required placeholder="Contoh: pcs, kg, botol" value="<?= e($edit['satuan'] ?? '') ?>"></div>
                <div class="row g-3">
                    <div class="col-md-6 mb-3"><label class="form-label fw-bold">Harga beli</label><input class="form-control" type="number" name="harga_beli" min="0" step="0.01" required value="<?= e($edit['harga_beli'] ?? '0.00') ?>"></div>
                    <div class="col-md-6 mb-3"><label class="form-label fw-bold">Harga jual</label><input class="form-control" type="number" name="harga_jual" min="0" step="0.01" required value="<?= e($edit['harga_jual'] ?? '0.00') ?>"></div>
                    <div class="col-md-6 mb-3"><label class="form-label fw-bold">Stok</label><input class="form-control" type="number" name="stok" min="0" required value="<?= e($edit['stok'] ?? '0') ?>"></div>
                    <div class="col-md-6 mb-3"><label class="form-label fw-bold">Stok minimum</label><input class="form-control" type="number" name="stok_minimum" min="0" required value="<?= e($edit['stok_minimum'] ?? '0') ?>"></div>
                </div>
                <div class="mb-3"><label class="form-label fw-bold">Tanggal kedaluwarsa <span class="text-secondary fw-normal">(opsional)</span></label><input class="form-control" type="date" name="tanggal_kedaluwarsa" value="<?= e($edit['tanggal_kedaluwarsa'] ?? '') ?>"></div>
                <button class="btn btn-primary" type="submit">Simpan produk</button>
                <?php if ($edit): ?><a class="btn btn-outline-primary ms-1" href="produk.php">Batal</a><?php endif; ?>
            </form>
        </div>
    </div>
    <div class="col-xl-8">
        <div class="card p-3 p-lg-4">
            <div class="d-flex justify-content-between align-items-center mb-3"><h2 class="h5 fw-bold mb-0">Daftar produk</h2><span class="text-secondary small"><?= $rows->num_rows ?> data</span></div>
            <div class="table-responsive"><table class="table table-hover align-middle mb-0">
                <thead><tr><th>ID</th><th>Produk</th><th>Kategori</th><th>Pemasok</th><th>Kode / Barcode</th><th>Harga beli</th><th>Harga jual</th><th>Stok</th><th>Kedaluwarsa</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                <?php while ($row = $rows->fetch_assoc()): ?>
                    <tr><td><?= e($row['id_produk']) ?></td><td class="fw-bold"><?= e($row['nama_produk']) ?><div class="small text-secondary"><?= e($row['merek']) ?> · <?= e($row['satuan']) ?></div></td><td><?= e($row['nama_kategori']) ?></td><td><?= e($row['nama_pemasok']) ?></td><td><?= e($row['kode_produk']) ?><div class="small text-secondary"><?= e($row['barcode']) ?></div></td><td>Rp <?= number_format((float) $row['harga_beli'], 2, ',', '.') ?></td><td>Rp <?= number_format((float) $row['harga_jual'], 2, ',', '.') ?></td><td><span class="badge rounded-pill <?= (int) $row['stok'] <= (int) $row['stok_minimum'] ? 'text-bg-warning' : 'text-bg-success' ?>"><?= e($row['stok']) ?> unit</span></td><td><?= e($row['tanggal_kedaluwarsa'] ?: '—') ?></td>
                    <td class="text-end text-nowrap"><a class="btn btn-sm btn-outline-primary" href="?edit=<?= e($row['id_produk']) ?>">Ubah</a>
                        <form class="d-inline" method="post" onsubmit="return confirm('Hapus produk ini?')"><input type="hidden" name="aksi" value="hapus"><input type="hidden" name="id_produk" value="<?= e($row['id_produk']) ?>"><button class="btn btn-sm btn-danger" type="submit">Hapus</button></form>
                    </td></tr>
                <?php endwhile; ?>
                <?php if ($rows->num_rows === 0): ?><tr><td class="text-center text-secondary py-4" colspan="10">Belum ada produk. Pastikan kategori dan pemasok sudah tersedia.</td></tr><?php endif; ?>
                </tbody>
            </table></div>
        </div>
    </div>
</div>
<?php render_footer(); ?>

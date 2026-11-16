<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = $_POST['aksi'] ?? '';
    $id = (int) ($_POST['id_detail'] ?? 0);
    if ($aksi === 'hapus' && $id > 0) {
        $stmt = $conn->prepare('DELETE FROM detail_penjualan WHERE id_detail = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        header('Location: detail_penjualan.php');
        exit;
    }
    if ($aksi === 'simpan') {
        $idPenjualan = (int) ($_POST['id_penjualan'] ?? 0);
        $idProduk = (int) ($_POST['id_produk'] ?? 0);
        $jumlah = (int) ($_POST['jumlah'] ?? 0);
        $harga = (float) ($_POST['harga_satuan'] ?? 0);
        $diskon = (float) ($_POST['diskon_item'] ?? 0);
        $subtotal = (float) ($_POST['subtotal_item'] ?? 0);
        if ($id > 0) {
            $stmt = $conn->prepare('UPDATE detail_penjualan SET id_penjualan=?, id_produk=?, jumlah=?, harga_satuan=?, diskon_item=?, subtotal_item=? WHERE id_detail=?');
            $stmt->bind_param('iiidddi', $idPenjualan, $idProduk, $jumlah, $harga, $diskon, $subtotal, $id);
        } else {
            $stmt = $conn->prepare('INSERT INTO detail_penjualan (id_penjualan, id_produk, jumlah, harga_satuan, diskon_item, subtotal_item) VALUES (?, ?, ?, ?, ?, ?)');
            $stmt->bind_param('iiiddd', $idPenjualan, $idProduk, $jumlah, $harga, $diskon, $subtotal);
        }
        $stmt->execute();
        header('Location: detail_penjualan.php');
        exit;
    }
}

$edit = null;
if (isset($_GET['edit'])) {
    $idEdit = (int) $_GET['edit'];
    $stmt = $conn->prepare('SELECT * FROM detail_penjualan WHERE id_detail = ?');
    $stmt->bind_param('i', $idEdit);
    $stmt->execute();
    $edit = $stmt->get_result()->fetch_assoc();
}
$penjualan = $conn->query('SELECT id_penjualan, nomor_struk FROM penjualan ORDER BY id_penjualan DESC');
$produk = $conn->query('SELECT id_produk, nama_produk FROM produk ORDER BY nama_produk');
$rows = $conn->query('SELECT d.*, j.nomor_struk, p.nama_produk FROM detail_penjualan d JOIN penjualan j ON j.id_penjualan=d.id_penjualan JOIN produk p ON p.id_produk=d.id_produk ORDER BY d.id_detail DESC');
render_header('Detail Penjualan', 'detail_penjualan.php');
?>
<div class="mb-4"><div class="eyebrow">Rincian transaksi</div><h1 class="page-title h2 mb-0">Detail Penjualan</h1></div>
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card p-4">
            <h2 class="h5 fw-bold mb-3"><?= $edit ? 'Ubah detail' : 'Tambah detail' ?></h2>
            <form method="post">
                <input type="hidden" name="aksi" value="simpan"><input type="hidden" name="id_detail" value="<?= e($edit['id_detail'] ?? '') ?>">
                <div class="mb-3"><label class="form-label fw-bold">Nomor struk</label><select class="form-select" name="id_penjualan" required><option value="">Pilih transaksi</option><?php while ($item = $penjualan->fetch_assoc()): ?><option value="<?= e($item['id_penjualan']) ?>" <?= (string) ($edit['id_penjualan'] ?? '') === (string) $item['id_penjualan'] ? 'selected' : '' ?>><?= e($item['nomor_struk']) ?></option><?php endwhile; ?></select></div>
                <div class="mb-3"><label class="form-label fw-bold">Produk</label><select class="form-select" name="id_produk" required><option value="">Pilih produk</option><?php while ($item = $produk->fetch_assoc()): ?><option value="<?= e($item['id_produk']) ?>" <?= (string) ($edit['id_produk'] ?? '') === (string) $item['id_produk'] ? 'selected' : '' ?>><?= e($item['nama_produk']) ?></option><?php endwhile; ?></select></div>
                <div class="mb-3"><label class="form-label fw-bold">Jumlah</label><input class="form-control" type="number" name="jumlah" min="1" required value="<?= e($edit['jumlah'] ?? '1') ?>"></div>
                <div class="mb-3"><label class="form-label fw-bold">Harga satuan</label><input class="form-control" type="number" name="harga_satuan" min="0" step="0.01" required value="<?= e($edit['harga_satuan'] ?? '0.00') ?>"></div>
                <div class="mb-3"><label class="form-label fw-bold">Diskon item</label><input class="form-control" type="number" name="diskon_item" min="0" step="0.01" required value="<?= e($edit['diskon_item'] ?? '0.00') ?>"></div>
                <div class="mb-3"><label class="form-label fw-bold">Subtotal item</label><input class="form-control" type="number" name="subtotal_item" min="0" step="0.01" required value="<?= e($edit['subtotal_item'] ?? '0.00') ?>"></div>
                <button class="btn btn-primary" type="submit">Simpan detail</button>
                <?php if ($edit): ?><a class="btn btn-outline-primary ms-1" href="detail_penjualan.php">Batal</a><?php endif; ?>
            </form>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card p-3 p-lg-4">
            <div class="d-flex justify-content-between align-items-center mb-3"><h2 class="h5 fw-bold mb-0">Rincian barang</h2><span class="text-secondary small"><?= $rows->num_rows ?> data</span></div>
            <div class="table-responsive"><table class="table table-hover align-middle mb-0">
                <thead><tr><th>ID</th><th>Nomor struk</th><th>Produk</th><th>Jumlah</th><th>Harga satuan</th><th>Diskon</th><th>Subtotal</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                <?php while ($row = $rows->fetch_assoc()): ?>
                    <tr><td><?= e($row['id_detail']) ?></td><td class="fw-bold"><?= e($row['nomor_struk']) ?></td><td><?= e($row['nama_produk']) ?></td><td><?= e($row['jumlah']) ?></td><td>Rp <?= number_format((float) $row['harga_satuan'], 2, ',', '.') ?></td><td>Rp <?= number_format((float) $row['diskon_item'], 2, ',', '.') ?></td><td class="fw-bold">Rp <?= number_format((float) $row['subtotal_item'], 2, ',', '.') ?></td>
                    <td class="text-end text-nowrap"><a class="btn btn-sm btn-outline-primary" href="?edit=<?= e($row['id_detail']) ?>">Ubah</a>
                        <form class="d-inline" method="post" onsubmit="return confirm('Hapus detail penjualan ini?')"><input type="hidden" name="aksi" value="hapus"><input type="hidden" name="id_detail" value="<?= e($row['id_detail']) ?>"><button class="btn btn-sm btn-danger" type="submit">Hapus</button></form>
                    </td></tr>
                <?php endwhile; ?>
                <?php if ($rows->num_rows === 0): ?><tr><td class="text-center text-secondary py-4" colspan="8">Belum ada detail penjualan.</td></tr><?php endif; ?>
                </tbody>
            </table></div>
        </div>
    </div>
</div>
<?php render_footer(); ?>

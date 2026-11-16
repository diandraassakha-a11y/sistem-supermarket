<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = $_POST['aksi'] ?? '';
    $id = (int) ($_POST['id_penjualan'] ?? 0);
    if ($aksi === 'hapus' && $id > 0) {
        $stmt = $conn->prepare('DELETE FROM penjualan WHERE id_penjualan = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        header('Location: penjualan.php');
        exit;
    }
    if ($aksi === 'simpan') {
        $idPegawai = (int) ($_POST['id_pegawai'] ?? 0);
        $idPelanggan = ($_POST['id_pelanggan'] ?? '') !== '' ? (int) $_POST['id_pelanggan'] : null;
        $nomor = trim($_POST['nomor_struk'] ?? '');
        $tanggal = str_replace('T', ' ', trim($_POST['tanggal_penjualan'] ?? ''));
        if (strlen($tanggal) === 16) {
            $tanggal .= ':00';
        }
        $subtotal = (float) ($_POST['subtotal'] ?? 0);
        $diskon = (float) ($_POST['diskon'] ?? 0);
        $pajak = (float) ($_POST['pajak'] ?? 0);
        $total = (float) ($_POST['total_bayar'] ?? 0);
        $status = $_POST['status_transaksi'] ?? 'Selesai';
        if ($id > 0) {
            $stmt = $conn->prepare('UPDATE penjualan SET id_pegawai=?, id_pelanggan=?, nomor_struk=?, tanggal_penjualan=?, subtotal=?, diskon=?, pajak=?, total_bayar=?, status_transaksi=? WHERE id_penjualan=?');
            $stmt->bind_param('iissddddsi', $idPegawai, $idPelanggan, $nomor, $tanggal, $subtotal, $diskon, $pajak, $total, $status, $id);
        } else {
            $stmt = $conn->prepare('INSERT INTO penjualan (id_pegawai, id_pelanggan, nomor_struk, tanggal_penjualan, subtotal, diskon, pajak, total_bayar, status_transaksi) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
            $stmt->bind_param('iissdddds', $idPegawai, $idPelanggan, $nomor, $tanggal, $subtotal, $diskon, $pajak, $total, $status);
        }
        $stmt->execute();
        header('Location: penjualan.php');
        exit;
    }
}

$edit = null;
if (isset($_GET['edit'])) {
    $idEdit = (int) $_GET['edit'];
    $stmt = $conn->prepare('SELECT * FROM penjualan WHERE id_penjualan = ?');
    $stmt->bind_param('i', $idEdit);
    $stmt->execute();
    $edit = $stmt->get_result()->fetch_assoc();
}
$pegawai = $conn->query('SELECT id_pegawai, nama_pegawai FROM pegawai ORDER BY nama_pegawai');
$pelanggan = $conn->query('SELECT id_pelanggan, nama_pelanggan FROM pelanggan ORDER BY nama_pelanggan');
$rows = $conn->query('SELECT j.*, p.nama_pegawai, l.nama_pelanggan FROM penjualan j JOIN pegawai p ON p.id_pegawai=j.id_pegawai LEFT JOIN pelanggan l ON l.id_pelanggan=j.id_pelanggan ORDER BY j.id_penjualan DESC');
render_header('Penjualan', 'penjualan.php');
?>
<div class="mb-4"><div class="eyebrow">Kasir dan transaksi</div><h1 class="page-title h2 mb-0">Penjualan</h1></div>
<div class="row g-4">
    <div class="col-xl-4">
        <div class="card p-4">
            <h2 class="h5 fw-bold mb-3"><?= $edit ? 'Ubah transaksi' : 'Tambah transaksi' ?></h2>
            <form method="post">
                <input type="hidden" name="aksi" value="simpan"><input type="hidden" name="id_penjualan" value="<?= e($edit['id_penjualan'] ?? '') ?>">
                <div class="mb-3"><label class="form-label fw-bold">Pegawai kasir</label><select class="form-select" name="id_pegawai" required><option value="">Pilih pegawai</option><?php while ($item = $pegawai->fetch_assoc()): ?><option value="<?= e($item['id_pegawai']) ?>" <?= (string) ($edit['id_pegawai'] ?? '') === (string) $item['id_pegawai'] ? 'selected' : '' ?>><?= e($item['nama_pegawai']) ?></option><?php endwhile; ?></select></div>
                <div class="mb-3"><label class="form-label fw-bold">Pelanggan</label><select class="form-select" name="id_pelanggan"><option value="">-- Pelanggan Umum --</option><?php while ($item = $pelanggan->fetch_assoc()): ?><option value="<?= e($item['id_pelanggan']) ?>" <?= (string) ($edit['id_pelanggan'] ?? '') === (string) $item['id_pelanggan'] ? 'selected' : '' ?>><?= e($item['nama_pelanggan']) ?></option><?php endwhile; ?></select><div class="form-text">Kosongkan untuk transaksi pelanggan umum.</div></div>
                <div class="mb-3"><label class="form-label fw-bold">Nomor struk</label><input class="form-control" name="nomor_struk" maxlength="40" required value="<?= e($edit['nomor_struk'] ?? '') ?>"></div>
                <div class="mb-3"><label class="form-label fw-bold">Tanggal penjualan</label><input class="form-control" type="datetime-local" name="tanggal_penjualan" required value="<?= e(isset($edit['tanggal_penjualan']) ? str_replace(' ', 'T', substr($edit['tanggal_penjualan'], 0, 16)) : date('Y-m-d\TH:i')) ?>"></div>
                <div class="row g-3">
                    <div class="col-6 mb-3"><label class="form-label fw-bold">Subtotal</label><input class="form-control" type="number" name="subtotal" min="0" step="0.01" required value="<?= e($edit['subtotal'] ?? '0.00') ?>"></div>
                    <div class="col-6 mb-3"><label class="form-label fw-bold">Diskon</label><input class="form-control" type="number" name="diskon" min="0" step="0.01" required value="<?= e($edit['diskon'] ?? '0.00') ?>"></div>
                    <div class="col-6 mb-3"><label class="form-label fw-bold">Pajak</label><input class="form-control" type="number" name="pajak" min="0" step="0.01" required value="<?= e($edit['pajak'] ?? '0.00') ?>"></div>
                    <div class="col-6 mb-3"><label class="form-label fw-bold">Total bayar</label><input class="form-control" type="number" name="total_bayar" min="0" step="0.01" required value="<?= e($edit['total_bayar'] ?? '0.00') ?>"></div>
                </div>
                <div class="mb-3"><label class="form-label fw-bold">Status transaksi</label><select class="form-select" name="status_transaksi" required><?php foreach (['Selesai', 'Dibatalkan', 'Dikembalikan'] as $status): ?><option value="<?= e($status) ?>" <?= ($edit['status_transaksi'] ?? 'Selesai') === $status ? 'selected' : '' ?>><?= e($status) ?></option><?php endforeach; ?></select></div>
                <button class="btn btn-primary" type="submit">Simpan penjualan</button>
                <?php if ($edit): ?><a class="btn btn-outline-primary ms-1" href="penjualan.php">Batal</a><?php endif; ?>
            </form>
        </div>
    </div>
    <div class="col-xl-8">
        <div class="card p-3 p-lg-4">
            <div class="d-flex justify-content-between align-items-center mb-3"><h2 class="h5 fw-bold mb-0">Daftar penjualan</h2><span class="text-secondary small"><?= $rows->num_rows ?> data</span></div>
            <div class="table-responsive"><table class="table table-hover align-middle mb-0">
                <thead><tr><th>ID</th><th>No. struk</th><th>Tanggal</th><th>Pegawai</th><th>Pelanggan</th><th>Subtotal</th><th>Diskon</th><th>Pajak</th><th>Total</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                <?php while ($row = $rows->fetch_assoc()): ?>
                    <tr><td><?= e($row['id_penjualan']) ?></td><td class="fw-bold"><?= e($row['nomor_struk']) ?></td><td><?= e($row['tanggal_penjualan']) ?></td><td><?= e($row['nama_pegawai']) ?></td><td><?= e($row['nama_pelanggan'] ?? 'Pelanggan Umum') ?></td><td>Rp <?= number_format((float) $row['subtotal'], 2, ',', '.') ?></td><td>Rp <?= number_format((float) $row['diskon'], 2, ',', '.') ?></td><td>Rp <?= number_format((float) $row['pajak'], 2, ',', '.') ?></td><td class="fw-bold">Rp <?= number_format((float) $row['total_bayar'], 2, ',', '.') ?></td><td><?= e($row['status_transaksi']) ?></td>
                    <td class="text-end text-nowrap"><a class="btn btn-sm btn-outline-primary" href="?edit=<?= e($row['id_penjualan']) ?>">Ubah</a>
                        <form class="d-inline" method="post" onsubmit="return confirm('Hapus transaksi ini?')"><input type="hidden" name="aksi" value="hapus"><input type="hidden" name="id_penjualan" value="<?= e($row['id_penjualan']) ?>"><button class="btn btn-sm btn-danger" type="submit">Hapus</button></form>
                    </td></tr>
                <?php endwhile; ?>
                <?php if ($rows->num_rows === 0): ?><tr><td class="text-center text-secondary py-4" colspan="11">Belum ada transaksi penjualan.</td></tr><?php endif; ?>
                </tbody>
            </table></div>
        </div>
    </div>
</div>
<?php render_footer(); ?>

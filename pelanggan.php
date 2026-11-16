<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = $_POST['aksi'] ?? '';
    $id = (int) ($_POST['id_pelanggan'] ?? 0);
    if ($aksi === 'hapus' && $id > 0) {
        $stmt = $conn->prepare('DELETE FROM pelanggan WHERE id_pelanggan = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        header('Location: pelanggan.php');
        exit;
    }
    if ($aksi === 'simpan') {
        $nama = trim($_POST['nama_pelanggan'] ?? '');
        $telepon = trim($_POST['no_telepon'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $tanggal = trim($_POST['tanggal_daftar'] ?? '');
        $poin = (int) ($_POST['poin_member'] ?? 0);
        $telepon = $telepon !== '' ? $telepon : null;
        $email = $email !== '' ? $email : null;
        if ($id > 0) {
            $stmt = $conn->prepare('UPDATE pelanggan SET nama_pelanggan = ?, no_telepon = ?, email = ?, tanggal_daftar = ?, poin_member = ? WHERE id_pelanggan = ?');
            $stmt->bind_param('ssssii', $nama, $telepon, $email, $tanggal, $poin, $id);
        } else {
            $stmt = $conn->prepare('INSERT INTO pelanggan (nama_pelanggan, no_telepon, email, tanggal_daftar, poin_member) VALUES (?, ?, ?, ?, ?)');
            $stmt->bind_param('ssssi', $nama, $telepon, $email, $tanggal, $poin);
        }
        $stmt->execute();
        header('Location: pelanggan.php');
        exit;
    }
}

$edit = null;
if (isset($_GET['edit'])) {
    $idEdit = (int) $_GET['edit'];
    $stmt = $conn->prepare('SELECT * FROM pelanggan WHERE id_pelanggan = ?');
    $stmt->bind_param('i', $idEdit);
    $stmt->execute();
    $edit = $stmt->get_result()->fetch_assoc();
}
$rows = $conn->query('SELECT * FROM pelanggan ORDER BY id_pelanggan DESC');
render_header('Pelanggan', 'pelanggan.php');
?>
<div class="mb-4"><div class="eyebrow">Hubungan pelanggan</div><h1 class="page-title h2 mb-0">Pelanggan</h1></div>
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card p-4">
            <h2 class="h5 fw-bold mb-3"><?= $edit ? 'Ubah pelanggan' : 'Tambah pelanggan' ?></h2>
            <form method="post">
                <input type="hidden" name="aksi" value="simpan"><input type="hidden" name="id_pelanggan" value="<?= e($edit['id_pelanggan'] ?? '') ?>">
                <div class="mb-3"><label class="form-label fw-bold">Nama pelanggan</label><input class="form-control" name="nama_pelanggan" maxlength="100" required value="<?= e($edit['nama_pelanggan'] ?? '') ?>"></div>
                <div class="mb-3"><label class="form-label fw-bold">No. telepon</label><input class="form-control" name="no_telepon" maxlength="20" value="<?= e($edit['no_telepon'] ?? '') ?>"></div>
                <div class="mb-3"><label class="form-label fw-bold">Email</label><input class="form-control" type="email" name="email" maxlength="100" value="<?= e($edit['email'] ?? '') ?>"></div>
                <div class="mb-3"><label class="form-label fw-bold">Tanggal daftar</label><input class="form-control" type="date" name="tanggal_daftar" required value="<?= e($edit['tanggal_daftar'] ?? date('Y-m-d')) ?>"></div>
                <div class="mb-3"><label class="form-label fw-bold">Poin member</label><input class="form-control" type="number" name="poin_member" min="0" value="<?= e($edit['poin_member'] ?? '0') ?>"></div>
                <button class="btn btn-primary" type="submit">Simpan pelanggan</button>
                <?php if ($edit): ?><a class="btn btn-outline-primary ms-1" href="pelanggan.php">Batal</a><?php endif; ?>
            </form>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card p-3 p-lg-4">
            <div class="d-flex justify-content-between align-items-center mb-3"><h2 class="h5 fw-bold mb-0">Daftar pelanggan</h2><span class="text-secondary small"><?= $rows->num_rows ?> data</span></div>
            <div class="table-responsive"><table class="table table-hover align-middle mb-0">
                <thead><tr><th>ID</th><th>Nama</th><th>Telepon</th><th>Email</th><th>Tanggal daftar</th><th>Poin</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                <?php while ($row = $rows->fetch_assoc()): ?>
                    <tr><td><?= e($row['id_pelanggan']) ?></td><td class="fw-bold"><?= e($row['nama_pelanggan']) ?></td><td><?= e($row['no_telepon']) ?></td><td><?= e($row['email']) ?></td><td><?= e($row['tanggal_daftar']) ?></td><td><span class="badge rounded-pill" style="background:#FEF9C3;color:#854D0E"><?= e($row['poin_member']) ?> poin</span></td>
                    <td class="text-end text-nowrap"><a class="btn btn-sm btn-outline-primary" href="?edit=<?= e($row['id_pelanggan']) ?>">Ubah</a>
                        <form class="d-inline" method="post" onsubmit="return confirm('Hapus pelanggan ini?')"><input type="hidden" name="aksi" value="hapus"><input type="hidden" name="id_pelanggan" value="<?= e($row['id_pelanggan']) ?>"><button class="btn btn-sm btn-danger" type="submit">Hapus</button></form>
                    </td></tr>
                <?php endwhile; ?>
                <?php if ($rows->num_rows === 0): ?><tr><td class="text-center text-secondary py-4" colspan="7">Belum ada pelanggan.</td></tr><?php endif; ?>
                </tbody>
            </table></div>
        </div>
    </div>
</div>
<?php render_footer(); ?>

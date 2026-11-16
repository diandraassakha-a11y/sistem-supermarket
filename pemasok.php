<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = $_POST['aksi'] ?? '';
    $id = (int) ($_POST['id_pemasok'] ?? 0);
    if ($aksi === 'hapus' && $id > 0) {
        $stmt = $conn->prepare('DELETE FROM pemasok WHERE id_pemasok = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        header('Location: pemasok.php');
        exit;
    }
    if ($aksi === 'simpan') {
        $nama = trim($_POST['nama_pemasok'] ?? '');
        $kontak = trim($_POST['nama_kontak'] ?? '');
        $telepon = trim($_POST['no_telepon'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $alamat = trim($_POST['alamat'] ?? '');
        $kontak = $kontak !== '' ? $kontak : null;
        $telepon = $telepon !== '' ? $telepon : null;
        $email = $email !== '' ? $email : null;
        $alamat = $alamat !== '' ? $alamat : null;
        if ($id > 0) {
            $stmt = $conn->prepare('UPDATE pemasok SET nama_pemasok = ?, nama_kontak = ?, no_telepon = ?, email = ?, alamat = ? WHERE id_pemasok = ?');
            $stmt->bind_param('sssssi', $nama, $kontak, $telepon, $email, $alamat, $id);
        } else {
            $stmt = $conn->prepare('INSERT INTO pemasok (nama_pemasok, nama_kontak, no_telepon, email, alamat) VALUES (?, ?, ?, ?, ?)');
            $stmt->bind_param('sssss', $nama, $kontak, $telepon, $email, $alamat);
        }
        $stmt->execute();
        header('Location: pemasok.php');
        exit;
    }
}

$edit = null;
if (isset($_GET['edit'])) {
    $idEdit = (int) $_GET['edit'];
    $stmt = $conn->prepare('SELECT * FROM pemasok WHERE id_pemasok = ?');
    $stmt->bind_param('i', $idEdit);
    $stmt->execute();
    $edit = $stmt->get_result()->fetch_assoc();
}
$rows = $conn->query('SELECT * FROM pemasok ORDER BY id_pemasok DESC');
render_header('Pemasok', 'pemasok.php');
?>
<div class="mb-4"><div class="eyebrow">Jaringan pasokan</div><h1 class="page-title h2 mb-0">Pemasok</h1></div>
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card p-4">
            <h2 class="h5 fw-bold mb-3"><?= $edit ? 'Ubah pemasok' : 'Tambah pemasok' ?></h2>
            <form method="post">
                <input type="hidden" name="aksi" value="simpan"><input type="hidden" name="id_pemasok" value="<?= e($edit['id_pemasok'] ?? '') ?>">
                <div class="mb-3"><label class="form-label fw-bold">Nama pemasok</label><input class="form-control" name="nama_pemasok" maxlength="120" required value="<?= e($edit['nama_pemasok'] ?? '') ?>"></div>
                <div class="mb-3"><label class="form-label fw-bold">Nama kontak</label><input class="form-control" name="nama_kontak" maxlength="100" value="<?= e($edit['nama_kontak'] ?? '') ?>"></div>
                <div class="mb-3"><label class="form-label fw-bold">No. telepon</label><input class="form-control" name="no_telepon" maxlength="20" value="<?= e($edit['no_telepon'] ?? '') ?>"></div>
                <div class="mb-3"><label class="form-label fw-bold">Email</label><input class="form-control" type="email" name="email" maxlength="100" value="<?= e($edit['email'] ?? '') ?>"></div>
                <div class="mb-3"><label class="form-label fw-bold">Alamat</label><textarea class="form-control" name="alamat" rows="3"><?= e($edit['alamat'] ?? '') ?></textarea></div>
                <button class="btn btn-primary" type="submit">Simpan pemasok</button>
                <?php if ($edit): ?><a class="btn btn-outline-primary ms-1" href="pemasok.php">Batal</a><?php endif; ?>
            </form>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card p-3 p-lg-4">
            <div class="d-flex justify-content-between align-items-center mb-3"><h2 class="h5 fw-bold mb-0">Daftar pemasok</h2><span class="text-secondary small"><?= $rows->num_rows ?> data</span></div>
            <div class="table-responsive"><table class="table table-hover align-middle mb-0">
                <thead><tr><th>ID</th><th>Nama pemasok</th><th>Kontak</th><th>Telepon</th><th>Email</th><th>Alamat</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                <?php while ($row = $rows->fetch_assoc()): ?>
                    <tr><td><?= e($row['id_pemasok']) ?></td><td class="fw-bold"><?= e($row['nama_pemasok']) ?></td><td><?= e($row['nama_kontak']) ?></td><td><?= e($row['no_telepon']) ?></td><td><?= e($row['email']) ?></td><td><?= nl2br(e($row['alamat'])) ?></td>
                    <td class="text-end text-nowrap"><a class="btn btn-sm btn-outline-primary" href="?edit=<?= e($row['id_pemasok']) ?>">Ubah</a>
                        <form class="d-inline" method="post" onsubmit="return confirm('Hapus pemasok ini?')"><input type="hidden" name="aksi" value="hapus"><input type="hidden" name="id_pemasok" value="<?= e($row['id_pemasok']) ?>"><button class="btn btn-sm btn-danger" type="submit">Hapus</button></form>
                    </td></tr>
                <?php endwhile; ?>
                <?php if ($rows->num_rows === 0): ?><tr><td class="text-center text-secondary py-4" colspan="7">Belum ada pemasok.</td></tr><?php endif; ?>
                </tbody>
            </table></div>
        </div>
    </div>
</div>
<?php render_footer(); ?>

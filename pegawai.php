<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = $_POST['aksi'] ?? '';
    $id = (int) ($_POST['id_pegawai'] ?? 0);
    if ($aksi === 'hapus' && $id > 0) {
        $stmt = $conn->prepare('DELETE FROM pegawai WHERE id_pegawai = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        header('Location: pegawai.php');
        exit;
    }
    if ($aksi === 'simpan') {
        $nama = trim($_POST['nama_pegawai'] ?? '');
        $gender = $_POST['jenis_kelamin'] ?? '';
        $jabatan = trim($_POST['jabatan'] ?? '');
        $telepon = trim($_POST['no_telepon'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $status = $_POST['status_pegawai'] ?? 'Aktif';
        $telepon = $telepon !== '' ? $telepon : null;
        $email = $email !== '' ? $email : null;
        if ($id > 0) {
            if ($password !== '') {
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare('UPDATE pegawai SET nama_pegawai = ?, jenis_kelamin = ?, jabatan = ?, no_telepon = ?, email = ?, username = ?, password_hash = ?, status_pegawai = ? WHERE id_pegawai = ?');
                $stmt->bind_param('ssssssssi', $nama, $gender, $jabatan, $telepon, $email, $username, $passwordHash, $status, $id);
            } else {
                $stmt = $conn->prepare('UPDATE pegawai SET nama_pegawai = ?, jenis_kelamin = ?, jabatan = ?, no_telepon = ?, email = ?, username = ?, status_pegawai = ? WHERE id_pegawai = ?');
                $stmt->bind_param('sssssssi', $nama, $gender, $jabatan, $telepon, $email, $username, $status, $id);
            }
        } else {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare('INSERT INTO pegawai (nama_pegawai, jenis_kelamin, jabatan, no_telepon, email, username, password_hash, status_pegawai) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
            $stmt->bind_param('ssssssss', $nama, $gender, $jabatan, $telepon, $email, $username, $passwordHash, $status);
        }
        $stmt->execute();
        header('Location: pegawai.php');
        exit;
    }
}

$edit = null;
if (isset($_GET['edit'])) {
    $idEdit = (int) $_GET['edit'];
    $stmt = $conn->prepare('SELECT * FROM pegawai WHERE id_pegawai = ?');
    $stmt->bind_param('i', $idEdit);
    $stmt->execute();
    $edit = $stmt->get_result()->fetch_assoc();
}
$rows = $conn->query('SELECT id_pegawai, nama_pegawai, jenis_kelamin, jabatan, no_telepon, email, username, status_pegawai FROM pegawai ORDER BY id_pegawai DESC');
render_header('Pegawai', 'pegawai.php');
?>
<div class="mb-4"><div class="eyebrow">Tim toko</div><h1 class="page-title h2 mb-0">Pegawai</h1></div>
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card p-4">
            <h2 class="h5 fw-bold mb-3"><?= $edit ? 'Ubah pegawai' : 'Tambah pegawai' ?></h2>
            <form method="post">
                <input type="hidden" name="aksi" value="simpan"><input type="hidden" name="id_pegawai" value="<?= e($edit['id_pegawai'] ?? '') ?>">
                <div class="mb-3"><label class="form-label fw-bold">Nama pegawai</label><input class="form-control" name="nama_pegawai" maxlength="100" required value="<?= e($edit['nama_pegawai'] ?? '') ?>"></div>
                <div class="mb-3"><label class="form-label fw-bold">Jenis kelamin</label><select class="form-select" name="jenis_kelamin" required><option value="">Pilih jenis kelamin</option><option value="L" <?= ($edit['jenis_kelamin'] ?? '') === 'L' ? 'selected' : '' ?>>Laki-laki</option><option value="P" <?= ($edit['jenis_kelamin'] ?? '') === 'P' ? 'selected' : '' ?>>Perempuan</option></select></div>
                <div class="mb-3"><label class="form-label fw-bold">Jabatan</label><input class="form-control" name="jabatan" maxlength="50" required value="<?= e($edit['jabatan'] ?? '') ?>"></div>
                <div class="mb-3"><label class="form-label fw-bold">No. telepon</label><input class="form-control" name="no_telepon" maxlength="20" value="<?= e($edit['no_telepon'] ?? '') ?>"></div>
                <div class="mb-3"><label class="form-label fw-bold">Email</label><input class="form-control" type="email" name="email" maxlength="100" value="<?= e($edit['email'] ?? '') ?>"></div>
                <div class="mb-3"><label class="form-label fw-bold">Username</label><input class="form-control" name="username" maxlength="50" required value="<?= e($edit['username'] ?? '') ?>"></div>
                <div class="mb-3"><label class="form-label fw-bold">Password <?= $edit ? '(kosongkan jika tidak diubah)' : '' ?></label><input class="form-control" type="password" name="password" <?= $edit ? '' : 'required' ?> autocomplete="new-password"></div>
                <div class="mb-3"><label class="form-label fw-bold">Status pegawai</label><select class="form-select" name="status_pegawai" required><option value="Aktif" <?= ($edit['status_pegawai'] ?? 'Aktif') === 'Aktif' ? 'selected' : '' ?>>Aktif</option><option value="Tidak Aktif" <?= ($edit['status_pegawai'] ?? '') === 'Tidak Aktif' ? 'selected' : '' ?>>Tidak Aktif</option></select></div>
                <button class="btn btn-primary" type="submit">Simpan pegawai</button>
                <?php if ($edit): ?><a class="btn btn-outline-primary ms-1" href="pegawai.php">Batal</a><?php endif; ?>
            </form>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card p-3 p-lg-4">
            <div class="d-flex justify-content-between align-items-center mb-3"><h2 class="h5 fw-bold mb-0">Daftar pegawai</h2><span class="text-secondary small"><?= $rows->num_rows ?> data</span></div>
            <div class="table-responsive"><table class="table table-hover align-middle mb-0">
                <thead><tr><th>ID</th><th>Nama</th><th>Gender</th><th>Jabatan</th><th>Telepon</th><th>Email</th><th>Username</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                <?php while ($row = $rows->fetch_assoc()): ?>
                    <tr><td><?= e($row['id_pegawai']) ?></td><td class="fw-bold"><?= e($row['nama_pegawai']) ?></td><td><?= $row['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan' ?></td><td><?= e($row['jabatan']) ?></td><td><?= e($row['no_telepon']) ?></td><td><?= e($row['email']) ?></td><td><?= e($row['username']) ?></td><td><span class="badge rounded-pill <?= $row['status_pegawai'] === 'Aktif' ? 'text-bg-success' : 'text-bg-secondary' ?>"><?= e($row['status_pegawai']) ?></span></td>
                    <td class="text-end text-nowrap"><a class="btn btn-sm btn-outline-primary" href="?edit=<?= e($row['id_pegawai']) ?>">Ubah</a>
                        <form class="d-inline" method="post" onsubmit="return confirm('Hapus pegawai ini?')"><input type="hidden" name="aksi" value="hapus"><input type="hidden" name="id_pegawai" value="<?= e($row['id_pegawai']) ?>"><button class="btn btn-sm btn-danger" type="submit">Hapus</button></form>
                    </td></tr>
                <?php endwhile; ?>
                <?php if ($rows->num_rows === 0): ?><tr><td class="text-center text-secondary py-4" colspan="9">Belum ada pegawai.</td></tr><?php endif; ?>
                </tbody>
            </table></div>
        </div>
    </div>
</div>
<?php render_footer(); ?>

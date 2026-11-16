<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = $_POST['aksi'] ?? '';
    $id = (int) ($_POST['id_kategori'] ?? 0);

    if ($aksi === 'hapus' && $id > 0) {
        $stmt = $conn->prepare('DELETE FROM kategori WHERE id_kategori = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        header('Location: kategori.php');
        exit;
    }

    if ($aksi === 'simpan') {
        $nama = trim($_POST['nama_kategori'] ?? '');
        $deskripsi = trim($_POST['deskripsi'] ?? '');
        $deskripsi = $deskripsi !== '' ? $deskripsi : null;

        if ($id > 0) {
            $stmt = $conn->prepare('UPDATE kategori SET nama_kategori = ?, deskripsi = ? WHERE id_kategori = ?');
            $stmt->bind_param('ssi', $nama, $deskripsi, $id);
        } else {
            $stmt = $conn->prepare('INSERT INTO kategori (nama_kategori, deskripsi) VALUES (?, ?)');
            $stmt->bind_param('ss', $nama, $deskripsi);
        }
        $stmt->execute();
        header('Location: kategori.php');
        exit;
    }
}

$edit = null;
if (isset($_GET['edit'])) {
    $idEdit = (int) $_GET['edit'];
    $stmt = $conn->prepare('SELECT id_kategori, nama_kategori, deskripsi FROM kategori WHERE id_kategori = ?');
    $stmt->bind_param('i', $idEdit);
    $stmt->execute();
    $edit = $stmt->get_result()->fetch_assoc();
}
$rows = $conn->query('SELECT id_kategori, nama_kategori, deskripsi FROM kategori ORDER BY id_kategori DESC');
render_header('Kategori', 'kategori.php');
?>
<div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
    <div><div class="eyebrow">Data produk</div><h1 class="page-title h2 mb-0">Kategori</h1></div>
    <span class="badge rounded-pill text-bg-light border">Kelola kategori produk</span>
</div>
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card p-4">
            <h2 class="h5 fw-bold mb-3"><?= $edit ? 'Ubah kategori' : 'Tambah kategori' ?></h2>
            <form method="post">
                <input type="hidden" name="aksi" value="simpan">
                <input type="hidden" name="id_kategori" value="<?= e($edit['id_kategori'] ?? '') ?>">
                <div class="mb-3">
                    <label class="form-label fw-bold" for="nama_kategori">Nama kategori</label>
                    <input class="form-control" id="nama_kategori" name="nama_kategori" maxlength="100" required value="<?= e($edit['nama_kategori'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold" for="deskripsi">Deskripsi</label>
                    <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4"><?= e($edit['deskripsi'] ?? '') ?></textarea>
                </div>
                <button class="btn btn-primary" type="submit">Simpan kategori</button>
                <?php if ($edit): ?><a class="btn btn-outline-primary ms-1" href="kategori.php">Batal</a><?php endif; ?>
            </form>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card p-3 p-lg-4">
            <div class="d-flex justify-content-between align-items-center mb-3"><h2 class="h5 fw-bold mb-0">Daftar kategori</h2><span class="text-secondary small"><?= $rows->num_rows ?> data</span></div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead><tr><th>ID</th><th>Nama kategori</th><th>Deskripsi</th><th class="text-end">Aksi</th></tr></thead>
                    <tbody>
                    <?php while ($row = $rows->fetch_assoc()): ?>
                        <tr>
                            <td><?= e($row['id_kategori']) ?></td><td class="fw-bold"><?= e($row['nama_kategori']) ?></td>
                            <td><?= nl2br(e($row['deskripsi'])) ?></td>
                            <td class="text-end text-nowrap">
                                <a class="btn btn-sm btn-outline-primary" href="?edit=<?= e($row['id_kategori']) ?>">Ubah</a>
                                <form class="d-inline" method="post" onsubmit="return confirm('Hapus kategori ini?')">
                                    <input type="hidden" name="aksi" value="hapus"><input type="hidden" name="id_kategori" value="<?= e($row['id_kategori']) ?>">
                                    <button class="btn btn-sm btn-danger" type="submit">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    <?php if ($rows->num_rows === 0): ?><tr><td class="text-center text-secondary py-4" colspan="4">Belum ada kategori.</td></tr><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php render_footer(); ?>

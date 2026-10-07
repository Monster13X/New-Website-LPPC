<?php
session_start();
require_once __DIR__ . '/../db.php';

if (empty($_SESSION['admin_user'])) {
    header('Location: login.php');
    exit;
}

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header('Location: login.php');
    exit;
}

// Ambil data Role dan Kursus ID milik Admin yang sedang login
$userRole = $_SESSION['admin_role'] ?? 'admin_kursus';
$userKursusId = $_SESSION['admin_kursus_id'] ?? null;

// Data untuk dropdown/switch filter (hanya dipanggil jika superadmin)
$kursus = [];
if ($userRole === 'superadmin') {
    $kursus = $pdo->query('SELECT id, nama FROM kursus ORDER BY id ASC')->fetchAll();
}

$selectedKursus = filter_input(INPUT_GET, 'kursus_id', FILTER_VALIDATE_INT);
$selectedJurusan = filter_input(INPUT_GET, 'id_jurusan', FILTER_VALIDATE_INT);

// Gabungkan LEFT JOIN kursus (k) dan jurusan (j) dalam satu query
$sql = 'SELECT p.*, k.nama AS kursus_nama, j.nama_jurusan AS nama_jurusan 
        FROM pendaftar p 
        LEFT JOIN kursus k ON k.id = p.kursus_id 
        LEFT JOIN jurusan j ON j.id_jurusan = p.id_jurusan';

$where = [];
$params = [];

// Filter kursus opsional (via GET ?kursus_id=), tanpa paksaan berdasarkan role
// sehingga SEMUA data pendaftar ditampilkan.
if ($selectedKursus) {
    $where[] = 'p.kursus_id = :kursus_id';
    $params['kursus_id'] = $selectedKursus;
}

if ($selectedJurusan) {
    $where[] = 'p.id_jurusan = :id_jurusan';
    $params['id_jurusan'] = $selectedJurusan;
}

if (!empty($where)) {
    $sql .= ' WHERE ' . implode(' AND ', $where);
}

$sql .= ' ORDER BY p.nama ASC';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

$pendaftar = $stmt->fetchAll();
$count = count($pendaftar);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../style.css">

    <!-- CSS PAKSA FILTER SWITCH -->
    <style>
        .dashboard-actions {
            display: flex !important;
            align-items: center !important;
            gap: 12px !important;
        }

        /* Container Kapsul Terang */
        .dashboard-actions .filter-switch {
            display: inline-flex !important;
            align-items: center !important;
            background-color: #111111 !important;
            border: 1px solid #222222;
            padding: 3px !important;
            border-radius: 20px !important;
            gap: 2px !important;
        }

        /* Style Default Link / Tombol */
        .dashboard-actions .filter-switch a,
        .dashboard-actions .filter-switch a.filter-btn {
            display: inline-block !important;
            padding: 6px 14px !important;
            color: #65676b !important;
            text-decoration: none !important;
            font-size: 13px !important;
            font-weight: 500 !important;
            border-radius: 16px !important;
            transition: all 0.2s ease !important;
            line-height: 1 !important;
            background: transparent !important;
        }

        /* Hover */
        .dashboard-actions .filter-switch a:hover {
            color: #ffffff !important;
            background-color: rgba(255, 255, 255, 0.1) !important;
        }

        /* Kondisi Aktif / Selected */
        .dashboard-actions .filter-switch a.active {
            background-color: #222222 !important;
            color: #ffffff !important;
            font-weight: 600 !important;
            border: 1px solid #333333 !important;
        }

        .dashboard-actions .btn-cetak {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 6px 16px !important;
            background-color: #1a1a1a !important;
            color: #ffffff !important;
            border: 1px solid #333333 !important;
            border-radius: 20px !important;
            font-size: 13px !important;
            font-weight: 500 !important;
            text-decoration: none !important;
            transition: all 0.2s ease !important;
            height: 32px !important;
            box-sizing: border-box !important;
        }

        .dashboard-actions .btn-cetak:hover {
            background-color: #252525 !important;
            border-color: #555555 !important;
            color: #ffffff !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.4) !important;
        }
    </style>
</head>
<body>
    <input type="checkbox" id="menuToggle" class="menu-toggle" checked>
    <label for="menuToggle" class="menu-button"><span></span></label>

   <aside class="navbar">
        <h2>Admin</h2>
        <ul>
            <!-- Tampilkan menu Editor jika role Superadmin atau Editor -->
            <?php if (in_array($_SESSION['admin_role'] ?? '', ['superadmin', 'editor'])): ?>
                <li><a href="editor.php">Web Editor</a></li>
            <?php endif; ?>

            <!-- Menu Dashboard Pendaftar (Khusus Superadmin & Admin Kursus) -->
            <?php if (in_array($_SESSION['admin_role'] ?? '', ['superadmin', 'admin_kursus'])): ?>
                <li><a href="index.php" class="active">Data Pendaftar</a></li>
            <?php endif; ?>

            <li><a href="?action=logout">Logout</a></li>
        </ul>
    </aside>

    <div class="page-content">
        <main class="dashboard-page">
            <header class="dashboard-header">
                <div>
                    <p class="dashboard-label">Dashboard <?= ($userRole === 'superadmin') ? 'Super Admin' : 'Admin Kursus' ?></p>
                    <h1>Ringkasan Data</h1>
                </div>
                <div class="dashboard-actions"> 
                    <!-- HANYA TAMPILKAN FILTER SWITCH JIKA SUPERADMIN -->
                    <?php if ($userRole === 'superadmin'): ?>
                        <div class="filter-switch">
                            <a href="?" class="filter-btn <?= empty($selectedKursus) ? 'active' : '' ?>">All</a>
                            <?php foreach ($kursus as $item): ?>
                                <a href="?kursus_id=<?= $item['id'] ?>" 
                                class="filter-btn <?= ($selectedKursus === (int)$item['id']) ? 'active' : '' ?>">
                                <?= htmlspecialchars($item['nama']) ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <a class="btn-cetak" href="export.php<?= $selectedKursus ? '?kursus_id=' . urlencode((string) $selectedKursus) : '' ?>">Cetak</a>
                </div>
            </header>

            <section class="dashboard-cards">
                <article class="card empty-card">
                    <p>Data Saat Ini</p>
                    <strong><?= $count ?></strong>
                </article>
            </section>

            <section class="table-section">
                <div class="table-header">
                    <h2>Daftar Pendaftar</h2>
                    <?php if ($count === 0): ?>
                        <p>Belum ada data yang tersedia. Tambahkan pendaftar untuk melihat tabel.</p>
                    <?php endif; ?>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>NPM</th>
                            <th>Jurusan</th>
                            <th>Kelas</th>
                            <th>Email</th>
                            <th>No.HP</th>
                            <th>Kursus</th>
                            <th>Tanggal Daftar</th>
                            <th>Waktu Daftar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($count === 0): ?>
                        <tr>
                            <td colspan="10" class="empty-row">Tidak ada data pendaftar.</td>
                        </tr>
                        <?php else: ?>
                            <?php $i = 1; foreach ($pendaftar as $row): ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?= htmlspecialchars($row['nama']) ?></td>
                                    <td><?= htmlspecialchars($row['npm']) ?></td>
                                    <td><?= htmlspecialchars($row['nama_jurusan'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($row['kelas']) ?></td>
                                    <td><?= htmlspecialchars($row['email']) ?></td>
                                    <td><?= htmlspecialchars($row['nohp']) ?></td>
                                    <td><?= htmlspecialchars($row['kursus_nama'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars(!empty($row['tanggal_daftar']) ? date('d/m/Y', strtotime($row['tanggal_daftar'])) : '-') ?></td>
                                    <td><?= htmlspecialchars(!empty($row['waktu_daftar']) ? date('H:i:s', strtotime($row['waktu_daftar'])) : '-') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
</body>
</html>
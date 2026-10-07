<?php
session_start();
require_once __DIR__ . '/../db.php';

if (empty($_SESSION['admin_user'])) {
    header('HTTP/1.1 403 Forbidden');
    echo 'Forbidden';
    exit;
}

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=pendaftar.csv');
header('Pragma: public');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Content-Transfer-Encoding: binary');

// Add UTF-8 BOM for Excel compatibility on Windows
echo "\xEF\xBB\xBF";

$out = fopen('php://output', 'w');
// Use the same course filter as the dashboard when one is selected.
$selectedKursus = filter_input(INPUT_GET, 'kursus_id', FILTER_VALIDATE_INT);
if ($selectedKursus) {
    $stmt = $pdo->prepare('SELECT p.*, k.nama AS kursus_nama FROM pendaftar p LEFT JOIN kursus k ON k.id = p.kursus_id WHERE p.kursus_id = :kursus_id ORDER BY p.id ASC');
    $stmt->execute(['kursus_id' => $selectedKursus]);
} else {
    $stmt = $pdo->query('SELECT p.*, k.nama AS kursus_nama FROM pendaftar p LEFT JOIN kursus k ON k.id = p.kursus_id ORDER BY p.id ASC');
}

// write header row
fputcsv($out, ['No', 'Nama', 'NPM', 'Kelas', 'Email', 'NoHP', 'Kursus', 'Tanggal Daftar', 'Waktu Daftar']);

$i = 1;
while ($row = $stmt->fetch()) {
    fputcsv($out, [$i++, $row['nama'], $row['npm'], $row['kelas'], $row['email'], $row['nohp'], $row['kursus_nama'] ?? '-', $row['tanggal_daftar'], $row['waktu_daftar']]);
}
fclose($out);
exit;

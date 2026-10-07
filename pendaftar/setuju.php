<?php
// Records that the visitor accepted the Syarat & Ketentuan on
// Leptek_LandingPages/Syarat_Daftar.html, so peserta_daftar.php lets them in.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$_SESSION['syarat_diterima'] = 1;

header('Content-Type: application/json; charset=utf-8');
echo json_encode(['ok' => true]);

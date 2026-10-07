<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION["pelanggan"]) OR empty($_SESSION["pelanggan"]))
{
    echo "<script>alert('login hela anying!');</script>";
    echo "<script>location='login.php';</script>";
    exit();
}

//mendapatkan id dari url
$idpem = $_GET["id"];
$ambil=$koneksi->query("SELECT * FROM pembelian WHERE id_pembelian='$idpem'");
$detpem =$ambil ->fetch_assoc();

//mendaptkan id_pelanggan yang beli
$id_pelanggan_beli = $detpem["id_pelanggan"];
//mendapatkan id_pelanggan yang login
$id_pelanggan_login = $_SESSION["pelanggan"]["id_pelanggan"];

if ($id_pelanggan_login !==$id_pelanggan_beli)
{
    echo "<script>alert('login hela anying!');</script>";
    echo "<script>location='riwayat.php';</script>";
    exit();
}
echo "<pre>";
print_r($detpem);
print_r($_SESSION);
echo "</pre>"
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin/assets/css/bootstrap.css">
    <link rel="stylesheet" href="shop.css">
    <title>Pembayaran</title>
</head>
<body>
    <?php include 'menu.php';?>

    <div class="container">
        <h2>Konfirmasi Pembayaran</h2>
        <p>Kirim Bukti Pembayaran Anda Disini</p>
        <div class="alert alert-info">total tagihan anda <strong> Rp.
            <?php echo number_format($detpem["total_pembelian"])?></strong></div>

        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label> Nama Penyetor </label>
                <input type="text" class="form-control" name="nama">
            </div>
            <div class="form-group">
                <label> Bank </label>
                <input type="text" class="form-control" name="bank">
            </div>
            <div class="form-group">
                <label> Jumlah </label>
                <input type="number" class="form-control" name="jumlah" min="1">
            </div>
            <div class="form-group">
                <label> Foto Bukti </label>
                <input type="file" class="form-control" name="bukti">
                <p class="text-danger">foto bukti harus jpg/JPEG maks 2MB</p>
            </div>
            <button class="btn btn-primary" name="kirim">Kirim</button>
        </form>
    </div>
    <?php 
    if(isset($_POST["kirim"]))
    {
        $namabukti = $_FILES["bukti"]["name"];
        $lokasibukti=$_FILES["bukti"]["tmp_name"];
        $namafiks= date("YmdHis").$namabukti;
        move_uploaded_file($lokasibukti, "bukti_pembayaran/$namafiks");

        $nama= $_POST["nama"];
        $bank= $_POST["bank"];
        $jumlah= $_POST["jumlah"];
        $tanggal = date("Y-m-d");

        $koneksi->query("INSERT INTO pembayaran(id_pembelian, nama, bank, jumlah, tanggal, bukti) 
                        VALUES ('$idpem','$nama','$bank','$jumlah','$tanggal','$namafiks')");
        

        //update status pembelian dari pending menjadi sudah kirim pembayaran
        $koneksi->query("UPDATE pembelian 
                        SET status_pembelian='Sudah kirim pembayaran' 
                        WHERE id_pembelian='$idpem'");

        echo "<script>alert('bayar juga lu bangsat!');</script>";
        echo "<script>location ='riwayat.php?id=$id_pelanggan_login';</script>";
    }
    ?>
    
</body>
</html>
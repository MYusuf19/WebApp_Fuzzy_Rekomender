<?php
session_start();
if(!isset($_SESSION['login'])) { header("Location: login.php");}
include 'function.php';
include 'fuzzy_function_penjelas.php';


$tampil=0;
if(isset($_POST['tombolcari'])) {
   $data=caridata($_POST['cari']);
    if(isset($data['id'])){$tampil=1;}
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Penjelasan Fungsi Fuzzy</title>
    <style>
        img {
            width: 400px;
        }
        img.trapesium {
            margin-left: 100px;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <br>
<a href="halrekomendasi.php"><button>Kembali</button></a>
<form action="" method="POST">
<input type="number" placeholder="Masukkan ID Barang" name="cari" required>
<button type="submit" name="tombolcari">Cari</button>
</form>

<?php if($tampil==1) : ?>
<ul>
    <li>ID: <?=$data['id']?></li>
    <li>Nama: <?=$data['nama']?></li>
    <li>Barang Terjual : <?=$data['jumlah']?></li>
    <li>Untung : <?=$data['untung']?></li>
    <li>Stok : <?=$data['stok']?></li>
</ul>
<h4> Derajat Anggota Himpunan Fuzzi</h4>
<img src="img/Variabel Penjualan.png" alt="">
<img src="img/Var Untung.png" alt="">
<img src="img/Variabel Stok.png" alt="">
<img src="img/Rumus Fungsi Trapesium.png" alt="" class="trapesium">
<br>
<?php
$a=fuzzijual($data['jumlah']);
$b=fuzziuntung($data['untung']);
$c=fuzzistok($data['stok']);

?>
<h4>IMPLIKASI RULE dan memakai Fungsi MIN</h4>
<img src="img/Rule Fuzzi.png" alt="">
<?php $hasil=Inferensi($a,$b,$c); ?>
<h4>Melakukan Komposisi Aturan dengan fungsi MAX</h4>
<?php $prioritas=komposisi($hasil);?>
<h4>Melakukan Defuzzifikasi Dengan Nilai Crisp</h4>

<p>Menggunakan Metode Weighted Average</p>
<img src="img/weighted mean formula.JPG" alt="">
<p>Mencari Nilai Rata-Rata</p>
<img src="img/Variabel Prioritas Barang.png" alt="">
<?php Deffuzifikasi($prioritas);?>
<?php endif ?>


</body>
</html>
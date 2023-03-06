<?php
session_start();
require 'function.php';

if(!isset($_SESSION['login'])) { header("Location: login.php");}

if(isset($_POST["tombol1"])) {
    $kondisi=input($_POST);
    if($kondisi) echo "data sudah berhasil ditambahkan";
    else echo "Data gagal ditambahkan, cek kembali inputan anda";
    // if (isset($_POST['hanyastok'])) echo "sudah checklist";
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Input</title>
    <link rel="stylesheet" href="css/halinput.css">
</head>
<body>

    <div class="kontainer">
    <div class="judul">E-Stok UD Rahmat YH</div>
    <form action="" method="POST">
    <ul>
    <pre>
  <label for="id">   <li>ID      :<input type="number" id="id" name="id" placeholder="Masukkan ID" autofocus autocomplete="off" required></li></label>
  <label for="nama"> <li>NAMA    :<input type="text" id="nama" name="barang" placeholder="Nama Barang" ></li>
  <label for="satuan"><li>SATUAN  :<input type="text" id="satuan" name="satuan" placeholder="Nama Satuan" ></li>
  <label for="modal"><li>MODAL   :<input type="number" id="modal" name="modal" placeholder="Masukkan Modal" ></li>
  <label for="harga"><li>HARGA   :<input type="number" id="harga" name="harga" placeholder="Masukkan Harga" ></li>
  <label for="stok"><li>STOK    :<input type="number" id="stok" name="stok" placeholder="Masukkan Stok" ></li>
  <label for="hanyastok"><li>           <input type="checkbox" id="hanyastok" name="hanyastok" placeholder="Masukkan Harga" value="1">Hanya STOK</li>
    </pre>
    <button type="submit" name="tombol1">Tambah</button>
    <button type="reset" name="tombol1">Reset</button>
</ul>
    </form>
    </div>
    <a href="mainmenu.php"><button>Kembali</button></a>
</body>
</html>
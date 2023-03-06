<?php
session_start();

require 'function.php';
require 'fuzzy_function.php';

if(!isset($_SESSION['login'])) { header("Location: login.php");}

$datautama=persiapandata();
isidatafuzzy($datautama);
$db=urutfuzzy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Rekomendasi</title>
    <link rel="stylesheet" href="css/halrekomendasi.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
</head>
<div class="kontainer">
<a href="mainmenu.php"><button>Kembali Ke Halaman Utama</button></a>
<a href="hal_penjelas.php" "><button>Halaman Penjelas</button></a>
<body>
    <table border="1" cellpadding="20" cellspacing="0">
        <tr>
            <td>
                id
            </td>
            <td>
                nama
            </td>
            <td>
                harga
            </td>
            <td>
                stok
            </td>
            <td>
                satuan
            </td>
            <td>
                barang terjual
            </td>
            <td>
                untung
            </td>
            <td>
                Prioritas Urutan
            </td>
        </tr>
    <?php $initial=1; while($tampil=mysqli_fetch_assoc($db))  : ?>
        <tr>
            <td><?php echo $tampil['id'];?></td>


            <td><?php echo $tampil['nama'];?></td>


            <td><?php echo $tampil['harga'];?></td>


            <td><?php echo $tampil['stok'];?></td>


            <td><?php echo $tampil['satuan'];?></td>


            <td><?php echo $tampil['jumlah'];?></td>


            <td><?php echo $tampil['untung']; ?></td>
            <td><?php echo $initial; $initial=$initial+1; ?></td>
            <!-- <td><?php echo $tampil['prioritas'];?></td> -->
        </tr>
    <?php endwhile;?>
    </table>
    </div>
</body>
</html>
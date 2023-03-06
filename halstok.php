<?php
#region
session_start();
include 'function.php';

if(!isset($_SESSION['login'])) { header("Location: login.php");}

$MenuEdit=0;

if(isset($_GET['hapus'])){
    $id=$_GET['hapus'];
    $cmd="DELETE FROM barang WHERE ID=$id";
    query($cmd);
    header("Location: halstok.php");
}

if(isset($_POST['tombol2'])){
    $db=cari($_POST);
    if(mysqli_num_rows($db)==1){
    $data=mysqli_fetch_assoc($db);
    $id=$data['id'];
    $stok=$data['stok']+$_POST['tambahstok'];
    $query="UPDATE barang SET stok=$stok WHERE id=$id";
    query($query);
    } else echo "Kata Kunci Belum Spesifik";
    }


if(isset($_GET['edit'])){
$id=$_GET['edit'];
$MenuEdit=1;
$sqlquery="SELECT * FROM barang WHERE id=$id";
$db=query($sqlquery);
$data=mysqli_fetch_assoc($db);

}

if(isset($_POST["tombolcari"])) {
    $db=cari($_POST);
} else {
    $querysql="SELECT * FROM barang";
    $db=query($querysql);
}

if(isset($_POST['tomboledit'])){
$id=$_GET["edit"];
$cmd="SELECT * FROM barang WHERE id=$id";
$datatmp=query($cmd);
$cmd="DELETE FROM barang WHERE id=$id";
query($cmd);
$a=input($_POST);
if($a==1){
echo "<script> document.location.href='halstok.php';</script>";}
else { inputcepat($datatmp);
    echo "<script> alert('ID sudah dimiliki barang lain, Data gagal Diubah'); document.location.href='halstok.php';</script>"; }
}



#endregion    ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Stok</title>
    <link rel="stylesheet" href="css/halstok.css">
</head>
<body>
<a href="mainmenu.php"><button>Kembali</button></a>
<div class="kontainer">
<div class="cari">
<!-- menu utama -->
<form action="" method="post">
    <input id="cari" name="cari" type="text" placeholder="Masukkan Kata Kunci" required>
    <?php if(isset($_GET['tambah'])) if($_GET["tambah"]==1) : ?>
    <input class="tambahstok" name="tambahstok" type="number" placeholder="Masukkan Tambahan Stok" required>
    <button type="submit" name="tombol2">Tambah Stok</button>
    <?php endif; ?>

</form>
</div>

<!-- Tombol Aksi -->
<!-- <?php if(isset($_GET["tambah"])) if($_GET["tambah"]==1) : ?>
    <a href="halstok.php?tambah=0"><button name="tambah" value="1">Mode Biasa</button></a>
<?php else : ?>
    <a href="halstok.php?tambah=1"><button name="tambah" value="1">Mode Tambah Stok</button></a>
<?php endif;?>
<?php if(!isset($_GET["tambah"])) : ?>
    <a href="halstok.php?tambah=1"><button name="tambah" value="1">Mode Tambah Stok</button></a>
<?php endif?> -->
<br>



<?php if ($MenuEdit==1) :?>
    <form action="" method="POST">

<pre>
ID      :<input type="text" id="id" name="id" placeholder="Masukkan ID" autofocus autocomplete="off" value="<?=$data['id']?>"><br>
NAMA    :<input type="text" id="nama" name="barang" placeholder="Nama Barang" value="<?=$data['nama']?>"><br>
MODAL   :<input type="number" id="modal" name="modal" placeholder="Masukkan Modal" value="<?=$data['modal']?>"><br>
HARGA   :<input type="number" id="harga" name="harga" placeholder="Masukkan Harga" value="<?=$data['harga']?>"><br>
STOK    :<input type="number" id="stok" name="stok" placeholder="Masukkan Harga" value="<?=$data['stok']?>"><br>
    <button name="tomboledit">Ubah</button>
</pre>

    </form>
<?php endif;?>


<div id="isi">
    <table border="1" cellspacing="0" cellpadding="20">
        <tr>
            <td>
                ID
            </td>
            <td>
                Nama
            </td>
            <td>
                Satuan
            </td>
            <td>
                Modal
            </td>
            <td>
                Harga
            </td>
            <td>
                Untung
            </td>
            <td>
                Stok
            </td>
            <!-- <td>
                Action
            </td> -->
        </tr>
        <?php while ($data=mysqli_fetch_assoc($db)) : ?>
        <tr>
            <td>
                <?=$data["id"]; ?>
            </td>
            <td>
                <?=$data["nama"]; ?>
            </td>
            <td>
                <?=$data["satuan"]; ?>
            </td>
            <td>
                <?=$data["modal"]; ?>
            </td>
            <td>
                <?=$data["harga"]; ?>
            </td>
            <td>
                <?=$data["untung"]; ?>
            </td>
            <td>
                <?=$data["stok"]; ?>
            </td>
            <!-- <td>
                <a href="halstok.php?edit=<?=$data['id']; ?>">Edit</a> || <a href="halstok.php?hapus=<?=$data['id']; ?>">Hapus</a>
            </td> -->
        </tr>
        <?php endwhile; ?>
    </table>
</div>
</div>

<script src="js/script.js"></script>
</body>
</html>
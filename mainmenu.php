<?php
#region
session_start();
require 'function.php';

if(!isset($_SESSION['login'])) { header("Location: login.php");}
$menu1=0;
$menu2=0;
$menu3=0;
$tampilpesan=0;

// tambah barang via ajax
if(isset($_GET['tambah']))
{
    $id=$_GET['tambah'];
    $querysql="SELECT * FROM barang WHERE id = '$id' ";
    $dbkirim=query($querysql);
    $brg=mysqli_fetch_assoc($dbkirim);
    $menu1=1;
    $menu2=0;
    $menu3=0;
}

// ubah barang dari belanjaan
if(isset($_GET['ubah'])) {
    $id=$_GET['ubah'];
    $querysql="SELECT * FROM barang WHERE id = '$id' ";
    $dbkirim=query($querysql);
    $brg=mysqli_fetch_assoc($dbkirim);
    $menu2=1;
    $menu1=0;
    $menu3=0;

}

// ubah total harga  dari belanjaan
if(isset($_GET['ubah1'])) {
    $menu2=0;
    $menu1=0;
    $menu3=1;
    $urutan=$_GET['ubah1'];
}


// hapus barang dari belanjaan
if(isset($_GET['hapus'])) {
    $urutan=$_GET['hapus'];
    hapustransaksi($urutan);
    echo "<script>document.location.href='mainmenu.php';</script>";
}

// tombol form 1 ditekan
if(isset($_POST['tambah1'])){
    $jumlah=$_POST['jumlah'];
    $harga=$_POST['harga'];
    $id=$_GET['tambah'];
    if(cekstokid($id,$jumlah)){
    $querysql="SELECT * FROM barang WHERE id = '$id' ";
    $dbkirim=query($querysql);
    tambahtransaksi($dbkirim,$jumlah,$harga);}
    else $tampilpesan=2;
    $menu1=0;
    echo "<script>document.location.href='mainmenu.php';</script>";
}

// tombol form 2 ditekan
if(isset($_POST['tambah2'])){
    $jumlah=$_POST['jumlah'];
    $id=$_GET['ubah'];
    $harga=$_POST['harga'];
    if(cekstokid($id,$jumlah)) {
        ubahtransaksi($id,$jumlah,$harga);
        } else $tampilpesan=2;
    $menu2=0;
    echo "<script>document.location.href='mainmenu.php';</script>";
}

// tombol form 3 mengubah total harga ditekan
if(isset($_POST['tambah3'])){
    $isi = $_POST['total'];
    $urutan = $_POST['urutan'];
    $_SESSION['transaksi']['total'][$urutan]=$isi;
    $menu3=0;
    echo "<script>document.location.href='mainmenu.php';</script>";
}


// tombol tambah barang ditekan
if(isset($_POST['tambahbarang'])){

    $jumlah=$_POST['jumlahbarang'];
    $db=cari($_POST);
    if (mysqli_num_rows($db)==1) {
        if(cekstok($_POST,$jumlah)){
        $result=cari($_POST);
        $data1=mysqli_fetch_assoc($result);
        $harga=$data1['harga'];
        tambahtransaksi($db,$jumlah,$harga);

        }else $tampilpesan=2;}
        else $tampilpesan=1;
}

// Menghilangkan seluruh belanjaan
if(isset($_POST['resetsession'])){
    $_SESSION['transaksi']=[];
}

// seluruh belanjaan disimpan dalam database tabel transaksi
if(isset($_POST['catat'])){
    if(isset($_SESSION['transaksi']['id'])){
    catat_transaksi();
    kurangi_stok();
    $_SESSION['transaksi']=[];} else echo "<h5>Keranjang masih kosong</h5>";
}

//Melakukan LogOut
if(isset($_POST['logout'])){
    session_destroy();
    session_unset();
    header('location: login.php');
}


#endregion
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <title>Halaman Utama</title>
    <style>

    </style>
    <link rel="stylesheet" href="css/mainmenu.css">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@500&display=swap" rel="stylesheet">
</head>

<body>

    <!-- navigasi -->
    <div class="navigasi">
        <button>USER</button>
        <a href="halinput.php"><button>Barang Masuk</button></a>
        <a href="halstok.php"><button>Stok</button></a>
        <a href="haltransaksi.php"><button>Transaksi</button></a>
        <a href="halrekomendasi.php"><button>Rekomendasi</button></a>
        <form method="POST"><button class="logout" name="logout">Logout</button></form>

    </div>
    <div class="kontainer">
        <?php if($tampilpesan==1) echo "<div class=pesan>Pencarian Anda Belum Spesifik</div>"; ?>
        <?php if($tampilpesan==2) echo "<div class=pesan>Stok Tidak Mencukupi</div>"; ?>
        <div class="totaluang">
            <p>Total Uang </p>
            <p>Rp. <?= uanglaci();?></p>
        </div>
        <div class="kontainercari">

            <!-- Tombol Utama -->
            <form action="" method="post">
                <input type="text" name="cari" id="caribarang" placeholder="Masukkan kata kunci" autocomplete="off" required>
                <input type="number" name="jumlahbarang" placeholder="jumlah barang" required>
                <button name="tambahbarang">Tambah Barang</button>
                <div class="reset"><a href="mainmenu.php">Reset</a></div>
            </form>
        </div>


        <!-- Menu form 1 untuk tambah via AJAX -->
        <?php if($menu1) : ?>
        <form action="" method="POST">
            <h5>Nama</h5><input type="text" name="nama" value="<?=$brg['nama']?>" disabled>
            <h5>Jumlah</h5><input type="number" name="jumlah" autofocus required>
            <h5>Harga</h5><input type="number" name="harga" value="<?=$brg['harga']?>" required>
            <button type="kirim" name="tambah1">kirim</button>
        </form>
        <?php endif ?>

        <!-- menu form 2 untuk ubah belanjaan -->
        <?php if($menu2) : ?>
        <form action="" method="POST">
            <h5>Nama</h5><input type="text" name="nama" value="<?=$brg['nama']?>" disabled>
            <h5>Jumlah</h5><input type="number" name="jumlah" autofocus required>
            <h5>Harga</h5><input type="number" name="harga" value="<?=$brg['harga']?>" required>
            <button type="kirim" name="tambah2">Ubah</button>
        </form>
        <?php endif ?>


        <!-- menu form 3 untuk ubah total belanjaan -->
        <?php if($menu3) : ?>
        <form action="" method="POST">
            <input type="number" name="urutan" value="<?=$urutan;?>" hidden>
            <h5>Nama </h5><input type="text" name="nama" value="test" disabled>
            <h5>Jumlah </h5><input type="number" name="jumlah" value="<?=$_SESSION['transaksi']['jumlah'][$urutan];?>" disabled>
            <h5>Total </h5><input type="number" name="total" value="<?=$_SESSION['transaksi']['total'][$urutan]; ?>" autofocus required>
            <button type="kirim" name="tambah3">Ubah</button>
        </form>
        <?php endif ?>



        <!-- Tabel berisi pencarian AJAX -->
        <br>
        <div id="isi">
            <!-- <table border="1" cellpadding="20" cellspacing="0">
        <tr>
            <td>
                Nama Barang
            </td>
            <td>
                Jumlah
            </td>
            <td>
                Harga Satuan
            </td>
            <td>
                Total
            </td>
        </tr>
    </table> -->
        </div>


        <br><br><br>

        <!-- Tabel Belanja Barang -->

        <div class="totalbelanja">
            <div class="total">
                <p>Total</p><span>Rp.<?= totalbelanjaan(); ?></span>
            </div>

            <form action="" method="post">
                <button name="catat" class="catat" onclick="Alert('Barang berhasil diubah');">Catat Transaksi</button>
                <button name="resetsession">Hapus</button>
            </form>
        </div>
        <div class="tabelbarang">
            <p>Keranjang Belanja</p>
            <table border="1" cellpadding="20" cellspacing="0">
                <tr>
                    <td>Nama</td>
                    <td>Jumlah</td>
                    <td>Satuan</td>
                    <td>Harga</td>
                    <td>Total</td>
                    <td>Aksi</td>
                </tr>
                <?php $initial=0; if(isset($_SESSION['transaksi']['id'])) : ?>
                <?php foreach($_SESSION['transaksi']['id'] as $id) : ?>
                <tr>
                    <td>
                        <?=$_SESSION['transaksi']['nama'][$initial]; ?>
                    </td>
                    <td>
                        <?=$_SESSION['transaksi']['jumlah'][$initial]; ?>
                    </td>
                    <td>
                        <?=$_SESSION['transaksi']['satuan'][$initial]; ?>
                    </td>
                    <td>
                        <?=$_SESSION['transaksi']['harga'][$initial]; ?>
                    </td>
                    <td>
                        <?php echo $_SESSION['transaksi']['total'][$initial];  ?>
                    </td>
                    <td>
                        <a href="mainmenu.php?ubah1=<?=$initial?>"><button>Ubah Harga</button></a>
                        <a href="mainmenu.php?ubah=<?=$id?>"><button>Ubah</button></a>
                            <a href="mainmenu.php?hapus=<?=$initial?>"><button>Hapus</button></a>

                        <?php $initial=$initial+1; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </table>
        </div>


    </div>
    <!-- script untuk AJAX -->
    <script src="js/cari-mainmenu.js"></script>
</body>

</html>
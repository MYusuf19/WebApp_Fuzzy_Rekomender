<?php
require 'function.php';
session_start();
if(!isset($_SESSION['login'])) { header("Location: login.php");}





#Mengecek Tombol sudah ditekan, bila belum tampilkan nilai semua
if(isset($_POST['semua']))
    { $db=saring_transaksi('semua'); $tampil=ambilnilai('semua');}
        else
            if(isset($_POST['bulan']))
                { $db=saring_transaksi('bulan'); $tampil=ambilnilai('bulan');}
                    else
                        if(isset($_POST['hari'])){ $db=saring_transaksi('hari'); $tampil=ambilnilai('hari'); }
                            else {$db=saring_transaksi('semua'); $tampil=ambilnilai('semua');}


?>

<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Transaksi</title>
    <link rel="stylesheet" href="css/halamantransaksi.css">
</head>



<body>
<div class="kontainer">
<div class="tampilanatas">
<div class="totaluang">Total Uang <span>Rp. <?=number_format($tampil['total'],0,",",".");?></span> </div> <div class="totaluntung">Total Keuntungan<span>Rp. <?=number_format($tampil['untung'],0,",",".") ;?></span></div>
</div>
<div class="action">
<form action="" method="post">
    <button name="semua" value="semua">Semua</button>
    <button name="bulan" value="bulan">Bulan Ini</button>
    <button name="hari" value="hari">Hari Ini</button>
</form>
<a href="haltransaksi.php"><button>Reset</button></a>
<a href="mainmenu.php"><button>Kembali</button></a>
</div>


<?php if(isset($_GET['no'])) : ?>
<?php $no=$_GET['no'];
$query="SELECT * FROM transaksi WHERE no='$no'";
$dbdetail=query($query);?>


<table border="1" cellpadding='20' cellspacing='0' class="detail" >
<tr>
    <td>No</td>
    <td>Waktu</td>
    <td>Nama Barang</td>
    <td>Harga</td>
    <td>Jumlah</td>
    <td>Satuan</td>
    <td>Jumlah</td>
    <td>Untung</td>
</tr>
<?php while($data=mysqli_fetch_assoc($dbdetail)) : ?>

    <tr <?php if($data['tipe']==1) echo"id='masuk'"; else echo "id='keluar'"; ?> >
            <td>
                <?=$data["no"]; ?>
            </td>
            <td>
                <?=$data["waktu"]; ?>
            </td>
            <td>
                <?=$data["barang"]; ?>
            </td>
            <td>
                <?=$data["harga"]; ?>
            </td>
            <td>
                <?=$data["jumlah"]; ?>
            </td>
            <td>
                <?=$data["satuan"]; ?>
            </td>
            <td>
                <?=$data["total"]; ?>
            </td>
            <td>
                <?=$data["untung"]; ?>
            </td>

        </tr>

<?php endwhile; ?>

</table>
<?php endif; ?>




<table border="1" cellpadding='20' cellspacing='0'>
    <tr>
        <td>
            no
        </td>
        <td>
            waktu
        </td>
        <td>
            total
        </td>
        <td>
            untung
        </td>
        <td>
            Aksi
        </td>
    </tr>
<?php while($data=mysqli_fetch_assoc($db)) : ?>
   <?php if($data['cek']=="ya") :?>
    <tr class="<?php if($data['tipe']==1) echo"tmasuk"; else echo "tkeluar" ?>">
        <td>
            <?=$data['no']; ?>
        </td>
        <td>
            <?=$data['waktu']; ?>
        </td>
        <td>
            <?=$data['total']; ?>
        </td>
        <td>
            <?=$data['untung']; ?>
        </td>
        <td>
            <a href="haltransaksi.php?no=<?=$data['no']; ?>"><button>Detail</button></a>
        </td>
    </tr>
    <?php endif; ?>
<?php endwhile; ?>
</table>

</div>
</body>
</html>
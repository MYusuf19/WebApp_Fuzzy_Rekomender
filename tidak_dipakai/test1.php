<?php
session_start();
$initial=0;



function tambahtransaksi($data){
    #cek id sudah ada atau belum
    $id=$data['id'];
    $nama=$data['nama'];
    $jumlah=$data['jumlah'];
    $harga=$data['harga'];
    $untung=$data['untung'];

    $cek=in_array($id,$_SESSION['transaksi']['id']);

    if(!$cek){
$_SESSION['transaksi']['id'][]=$id;
$_SESSION['transaksi']['nama'][]=$nama;
$_SESSION['transaksi']['jumlah'][]=$jumlah;;
$_SESSION['transaksi']['harga'][]=$harga;
$_SESSION['transaksi']['untung'][]=$untung;
    } else {

    }


}

function reset_transaksi(){
    unset($_SESSION['transaksi']);
}

if (isset($_POST['tambah'])) {
    tambahtransaksi($_POST);
}

if(isset($_POST['hapus'])) reset_transaksi();


?>

<form action="" method="POST">
<input type="text" name="id" placeholder="id">
<input type="text" name="nama" placeholder="nama">
<input type="text" name="jumlah" placeholder="jumlah">
<input type="text" name="harga" placeholder="harga">
<input type="text" name="untung" placeholder="untung">
<button type="submit" name="tambah">Tambah</button>
</form>
<form action="" method="post"><button name="hapus">Hapus Semua</button></form>


<?php if(!empty($_SESSION['transaksi']['id'])) :?>
    <table>
        <?php foreach($_SESSION['transaksi']['id'] as $a) : ?>
            <tr>
                <td>
                    <?php echo $_SESSION['transaksi']['id'][$initial]; ?>
                </td>
                <td>
                    <?php echo $_SESSION['transaksi']['nama'][$initial];  ?>
                </td>
                <td>
                    <?php echo $_SESSION['transaksi']['jumlah'][$initial];  ?>
                </td>
                <td>
                    <?php echo $_SESSION['transaksi']['harga'][$initial]; $initial=$initial+1; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>


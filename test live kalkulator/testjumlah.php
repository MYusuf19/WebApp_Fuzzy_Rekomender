<?php
session_start();
if(!isset($_SESSION['id']))$_SESSION['id']=0;
if(!isset($_SESSION['nama']))$_SESSION['nama']=[];
if(!isset($_SESSION['jumlah']))$_SESSION['jumlah']=[];
if(!isset($_SESSION['total']))$_SESSION['total']=[];
if(!isset($_SESSION['harga']))$_SESSION['harga']=[];
$_SESSION['nama'][0]="plastik";
$_SESSION['jumlah'][0]=2;
$_SESSION['harga'][0]=3000;
$_SESSION['total'][0]=$_SESSION['jumlah'][0]*$_SESSION['harga'][0];

function pilih1(){
    if(!isset($_GET['ubah'])) echo "";
        else {
            $a=$_GET["ubah"];
            $b=$_SESSION["nama"][$a];
            return $b;
        }
}

function pilih2(){
    if(!isset($_GET['ubah'])) echo "";
        else {
            $a=$_GET["ubah"];
            $b=$_SESSION["jumlah"][$a];
            return $b;
        }
}

function pilih3(){
    if(!isset($_GET['ubah'])) echo "";
        else {
            $a=$_GET["ubah"];
            $b=$_SESSION["harga"][$a];
            return $b;
        }
}


$namas="plastik";
$jumlahs=2;
$hargas=3000;

$_SESSION["nama"][0]=$namas;
$_SESSION["jumlah"][0]=$jumlahs;
$_SESSION["hargas"][0]=$hargas;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<input type="text" value="<?=pilih1();?>" >
<input type="text" value="<?=pilih2();?>" id="1">
<input type="number" value="<?=pilih3();?>" id="2">
<button>Tambah</button>

<div id="hasil"> <h2><?=$_SESSION['total'][0];?></h2></div>
<table border="1" cellpadding="20" cellspacing="0">

    <tr>
    <div><td><a href="testjumlah.php?ubah=<?= $_SESSION['id']; ?>"><?=$namas;?></a></td></div>
        <td><?=$jumlahs;?></td>
        <td><?=$hargas;?></td>
    </tr>
</table>








<script src="jumlahajax.js"></script>

</body>
</html>
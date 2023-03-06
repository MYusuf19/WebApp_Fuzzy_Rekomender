<?php
#$conn=mysqli_connect("hostname","username","password","database","port");
#$conn=mysqli_connect("localhost","root","","barang",);
$conn=mysqli_connect("sql311.epizy.com","epiz_31568285","PasswordBaru","epiz_31568285_RYH");

function query($query){
    global $conn;
    $db=mysqli_query($conn,$query);
    return $db;}

function status($a,$penyebab){
    if($a==1) echo"data berhasil ditambahkan";
        else    echo"data gagal ditambahkan, ",$penyebab;
}

function input($data){
    global $conn;
    $id=$data["id"];
    $result=mysqli_query($conn,"SELECT * FROM barang WHERE id=$id");
    $cekinput=cekisiinput($data);

    if(!isset($data['hanyastok'])&&$cekinput==true){
        if(mysqli_num_rows($result)==1){
        $query="DELETE FROM barang WHERE id=$id";
        query($query);}
        $namabrg=$data["barang"];
        $satuan=$data["satuan"];
        $harga=(int)$data["harga"];
        $modal=(int)$data["modal"];
        $untung=$harga-$modal;
        $stok=(int)$data["stok"];
        $query="INSERT INTO barang VALUES ('$id','$namabrg','$satuan','$modal','$harga','$untung','$stok')";
        query($query);
        catat_input($data);
        return true;}
        else if(isset($data['hanyastok'])&&mysqli_num_rows($result)==1){
            if($data['stok']==null) return false;
            $data1=mysqli_fetch_assoc($result);
            $nomor=nomor_transaksi();
            $nama=$data1['nama'];
            $satuan=$data1['satuan'];
            $modal=$data1['modal'];
            $harga=$data1['harga'];
            $untung=$data1['untung'];
            $stok=$data['stok'];
            $total=$modal*$stok;
            $stoksebelum=$data1['stok'];
            $totalstok=$stok+$stoksebelum;

            $query="UPDATE barang SET stok=$totalstok WHERE id=$id";
            query($query);
            $query="INSERT INTO transaksi VALUES ('$nomor',CURRENT_TIMESTAMP,'$id','$nama','$modal','$stok','$satuan','$total','0','1')";
            query($query);
            return true;
        } else echo"Datanya gagal";

                        }

function inputcepat($db){
    $data=mysqli_fetch_assoc($db);
    $id=$data['id'];
    $namabrg=$data['nama'];
    $harga=(int)$data['harga'];
    $modal=(int)$data['modal'];
    $untung=$data['untung'];
    $stok=(int)$data['stok'];
    $query="INSERT INTO barang VALUES ('$id','$namabrg','$modal','$harga','$untung','$stok')";
    query($query);
}

function cekisiinput ($data){
    if($data['barang']==null||$data['satuan']==null||$data['modal']==null||$data['harga']==null||$data['stok']==null)
    {return false;}
    else return true;
}

//Memasukkan Transaksi Input kedalam tabel transaksi
function catat_input ($data){
    $nomor=nomor_transaksi();
    $id=$data['id'];
    $namabrg=$data["barang"];
    $satuan=$data["satuan"];
    $harga=(int)$data["harga"];
    $modal=(int)$data["modal"];
    $untung=$harga-$modal;
    $stok=(int)$data["stok"];
    $total=$modal*$stok;
$query="INSERT INTO transaksi VALUES ('$nomor',CURRENT_TIMESTAMP,'$id','$namabrg','$modal','$stok','$satuan','$total','0','1')";
query($query);
}


function ceklogin($data)    {
    $username=htmlspecialchars($data['username']);
    $password=htmlspecialchars($data['password']);
    $query="SELECT password FROM tableuser WHERE username='$username'";
    $result=query($query);
    if(mysqli_num_rows($result)==0) return 0;
    $data=mysqli_fetch_assoc($result);
    if(password_verify($password,$data['password']))
    return 1;}

function blokir(){
        if(!isset($_SESSION['try'])) $_SESSION['try']=0;
         $_SESSION['try']=$_SESSION['try']+1;
        if($_SESSION['try']%3==0){
            $_SESSION['waktublokir']=time()+60;
            $_SESSION['blokir']=1;
            echo "<br> <div class='pesan'>anda di blokir selama 60 detik</div>";
        } return $_SESSION['try'];}

function cari($keyword){
    $nama=$keyword["cari"];
    $query="SELECT * FROM barang WHERE id LIKE '%$nama%' OR nama LIKE '%$nama%' OR modal LIKE '%$nama%' ";
    $db=query($query);
    return $db;        }

#Menghitung Jumlah Uang Transaksi Hari Ini
function uanglaci(){
$query="SELECT SUM(IF(DAY(waktu)=DAY(CURRENT_TIMESTAMP),total,0)) AS jumlah FROM transaksi";
$db=query($query);
$data=mysqli_fetch_assoc($db);
return number_format($data['jumlah'],0,",",".");
}


function tambahtransaksi($db,$jumlah,$harga=0){
    if ($jumlah<=0) {return false;}
    $data=mysqli_fetch_assoc($db);
    if($harga<=0) return false;
        $id=$data['id'];
        $nama=$data['nama'];
        $modal=$data['modal'];
        $satuan=$data['satuan'];
        $untung=$data['untung'];
        $untung=$untung*$jumlah;
        $total=$harga*$jumlah;

    if(isset($_SESSION['transaksi']['id'])){
        $cek=in_array($id,$_SESSION['transaksi']['id']);}
            else {
                $cek=0;
            }

    if(!$cek)
            {
    $_SESSION['transaksi']['id'][]=$id;
    $_SESSION['transaksi']['nama'][]=$nama;
    $_SESSION['transaksi']['jumlah'][]=$jumlah;;
    $_SESSION['transaksi']['satuan'][]=$satuan;;
    $_SESSION['transaksi']['harga'][]=$harga;
    $_SESSION['transaksi']['untung'][]=$untung;
    $_SESSION['transaksi']['total'][]=$total;
    $_SESSION['transaksi']['modal'][]=$modal;        }
        else                                            {
            $initial=0;
            //Proses menambah stok bila barang yang sama dimasukkan
            foreach($_SESSION['transaksi']['id'] as $id1){
                if($id1==$id){


                    $_SESSION['transaksi']['jumlah'][$initial]=$_SESSION['transaksi']['jumlah'][$initial]+$jumlah;
                    $_SESSION['transaksi']['untung'][$initial]=$_SESSION['transaksi']['untung'][$initial]+$untung;
                    $_SESSION['transaksi']['total'][$initial]=$_SESSION['transaksi']['total'][$initial]+$total;

                } $initial=$initial+1;
            }
                                                        }
}

//Cek stok berdasarkan pencarian ajax
function cekstok ($post,$jumlah){

    $db=cari($post);
    $data=mysqli_fetch_assoc($db);
    $id=$data['id'];
    $stok=$data['stok'];
    #cek data sudah ada di session dan cukup stok di gudang
    if(isset($_SESSION['transaksi']['id'])){
    $cek=in_array($id,$_SESSION['transaksi']['id']);
    if($cek==true) {$cekstoksesi=cekkeranjang($id,$stok,$jumlah);
    if ($cekstoksesi==false) return $hasil=0;}}

    $tmp=$stok-$jumlah;
    if($tmp>=0&&$jumlah!=0) $hasil=1; else $hasil =0;
    return $hasil;
}

#fungsi memeriksa isi keranjang dengan isi stok
function cekkeranjang($id,$stok,$jumlah) {
$initial=0;
foreach ($_SESSION['transaksi']['id'] as $id1){
if($id==$id1){ $indeks=$initial;} else
    $initial=$initial+1;
}
$stoksesi=$_SESSION['transaksi']['jumlah'][$indeks];
$tmp=$stoksesi+$jumlah;
if($tmp>$stok) return false;
return true;
}

//mencari stok berdasarkan ID
function cekstokid ($id,$jumlah){
    $querysql="SELECT * FROM barang WHERE id = '$id' ";
    $db=query($querysql);
    $data=mysqli_fetch_assoc($db);
    $stok=$data['stok'];
#cek data sudah ada di session dan cukup stok di gudang
if(isset($_SESSION['transaksi']['id'])){
$cek=in_array($id,$_SESSION['transaksi']['id']);
if($cek==true) {$cekstoksesi=cekkeranjang($id,$stok,$jumlah);
if ($cekstoksesi==false) return $hasil=0;}}

    $tmp=$stok-$jumlah;
    if($tmp>0) $hasil=1; else $hasil =0;
    return $hasil;
}

function ubahtransaksi($id,$jumlah,$harga) {
    $a=0;
if ($jumlah<=0) {return false;}
foreach($_SESSION['transaksi']['id'] as $id1) {

    if($id1==$id) {
        $untung=$harga-$_SESSION['transaksi']['modal'][$a];
        $untung=$untung*$jumlah;
        $_SESSION['transaksi']['jumlah'][$a]=$jumlah;
        $_SESSION['transaksi']['total'][$a]=$harga*$jumlah;
        $_SESSION['transaksi']['untung'][$a]=$untung;

    }
    $a=$a+1;
}
}

function hapustransaksi($urutan){
array_splice($_SESSION['transaksi']['id'],$urutan,1);
array_splice($_SESSION['transaksi']['nama'],$urutan,1);
array_splice($_SESSION['transaksi']['jumlah'],$urutan,1);
array_splice($_SESSION['transaksi']['satuan'],$urutan,1);
array_splice($_SESSION['transaksi']['harga'],$urutan,1);
array_splice($_SESSION['transaksi']['untung'],$urutan,1);
array_splice($_SESSION['transaksi']['total'],$urutan,1);
array_splice($_SESSION['transaksi']['modal'],$urutan,1);

}

function catat_transaksi(){
    $nomor=nomor_transaksi();
    $initial=0;
    foreach($_SESSION['transaksi']['id'] as $id){
        $nama=$_SESSION['transaksi']['nama'][$initial];
        $jumlah=$_SESSION['transaksi']['jumlah'][$initial];
        $satuan=$_SESSION['transaksi']['satuan'][$initial];
        $harga=$_SESSION['transaksi']['harga'][$initial];
        $untung=$_SESSION['transaksi']['untung'][$initial];
        $total=$_SESSION['transaksi']['total'][$initial];
    $querysql="INSERT INTO transaksi VALUES ($nomor,CURRENT_TIMESTAMP,$id,'$nama','$harga','$jumlah','$satuan','$total','$untung','2')";
    query($querysql);
    $initial=$initial+1;
    }

}

function nomor_transaksi(){
    $initial=1;
    $querysql="SELECT no FROM transaksi";
    $db=query($querysql);
    $a=mysqli_num_rows($db);
    if($a!=0) {
        $querysql="SELECT MAX(no) AS nomor FROM transaksi";
        $db=query($querysql);
        $a=mysqli_fetch_assoc($db);
        $a['nomor']=$a['nomor']+1;
        return $a['nomor'];}
            else {
                return 0;}
}

function totalbelanjaan(){
    $totalsemua=0;
    if(isset($_SESSION['transaksi']['total'])){

        foreach($_SESSION['transaksi']['total'] as $totalharga){
            $totalsemua=$totalsemua+$totalharga;
        }
    }
    return $totalsemua;
}

function kurangi_stok(){
    $initial=0;
foreach($_SESSION['transaksi']['id'] as $id){

    $cmd="SELECT stok FROM barang WHERE id=$id";
    $db=query($cmd);
    $data=mysqli_fetch_assoc($db);
    $stok=$data['stok']-$_SESSION['transaksi']['jumlah'][$initial];
    $cmd="UPDATE barang SET stok=$stok WHERE id=$id";
    query($cmd);
    $initial=$initial+1;
}
}

function saring_transaksi($waktu){

    if($waktu=='bulan'){
    $query="SELECT no, waktu,tipe, SUM(total) as total,SUM(untung) as untung, IF(MONTH(waktu)=MONTH(CURRENT_TIMESTAMP),'ya','tidak') AS cek FROM transaksi GROUP BY no";
    $db=query($query);
    return $db;}
    if($waktu=='semua'){
        $query="SELECT no, waktu,tipe, SUM(total) as total,SUM(untung) as untung,'ya' as cek FROM transaksi GROUP BY no";
        $db=query($query);
        return $db;}
    if($waktu=='hari'){
        $query="SELECT no, waktu,tipe, SUM(total) as total,SUM(untung) as untung, IF(DAY(waktu)=DAY(CURRENT_TIMESTAMP),'ya','tidak') AS cek FROM transaksi GROUP BY no";
        $db=query($query);
        return $db;}
}

function ambilnilai($nilai){
    if($nilai=='bulan'){
        $cmd="SELECT SUM(IF(MONTH(waktu)=MONTH(CURRENT_TIMESTAMP),total,0)) AS total,IF(MONTH(waktu)=MONTH(CURRENT_TIMESTAMP),SUM(untung),0) AS untung FROM transaksi";
        $db=query($cmd);

        if(mysqli_num_rows($db)==0) {
            $data['untung']=0;
            $data['total']=0;
            return $data;}
        $data=mysqli_fetch_assoc($db);
        return $data;}
        if($nilai=='semua'){
            $cmd="SELECT SUM(total) AS total,SUM(untung) AS untung FROM transaksi";
            $db=query($cmd);
            if(mysqli_num_rows($db)==0) {
                $data['untung']=0;
                $data['total']=0;
                return $data;}
            $data=mysqli_fetch_assoc($db);
            return $data;}

        if($nilai=='hari'){
            $cmd="SELECT SUM(IF(DAY(waktu)=DAY(CURRENT_TIMESTAMP),total,0)) AS total,SUM(IF(DAY(waktu)=DAY(CURRENT_TIMESTAMP),untung,0)) AS untung FROM transaksi";
            $db=query($cmd);
            if(mysqli_num_rows($db)==0) {
                echo "data kosong";
                $data['untung']=0;
                $data['total']=0;
                return $data;
            }
            $data=mysqli_fetch_assoc($db);
            return $data;}
}
?>


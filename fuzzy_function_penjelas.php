<?php
#region

function caridata($cari){

    $query="SELECT barang,harga,SUM(jumlah) AS jumlah,SUM(untung) as untung FROM transaksi WHERE id=$cari";
    $data1=query($query);
    $query="SELECT * FROM barang WHERE id=$cari";
    $data2=query($query);
    $datasatu=mysqli_fetch_assoc($data1);
    $datadua=mysqli_fetch_assoc($data2);
    if($datasatu['barang']==null||$datadua['nama']==null) {
        echo "Data tidak ditemukan di database, atau tidak memiliki transaksi";
        return false;
    }
    $datautama['id']=$datadua['id'];
    $datautama['nama']=$datasatu['barang'];
    $datautama['harga']=$datadua['harga'];
    $datautama['stok']=$datadua['stok'];
    $datautama['jumlah']=$datasatu['jumlah'];
    $datautama['untung']=$datasatu['untung'];

    return $datautama;
}


function persiapandata(){
    $query="SELECT id,barang,harga,SUM(jumlah) AS jumlah,SUM(untung) AS untung FROM transaksi GROUP BY barang";
    $data1=query($query);
    $query="SELECT * FROM barang";
    $data2=query($query);
    foreach( $data2 as $datadua){
        foreach($data1 as $datasatu){
            if($datadua['id']==$datasatu['id']){
                $datautama['id'][]=$datasatu['id'];
                $datautama['nama'][]=$datasatu['barang'];
                $datautama['harga'][]=$datadua['harga'];
                $datautama['stok'][]=$datadua['stok'];
                $datautama['jumlah'][]=$datasatu['jumlah'];
                $datautama['untung'][]=$datasatu['untung'];
                $datautama['prioritas'][]=fuzzi($datasatu['jumlah'],$datadua['untung'],$datadua['stok']);
                                                }
                                    }
                                }
return $datautama;}

function isidatafuzzy($data){
    $initial=0;
    $query="DELETE FROM rekomendasi";
    query($query);
    foreach($data['id'] as $idbrg){
        $id=$idbrg;
        $nama=$data['nama'][$initial];
        $harga=$data['harga'][$initial];
        $stok=$data['stok'][$initial];
        $jumlah=$data['jumlah'][$initial];
        $untung=$data['untung'][$initial];
        $prioritas=$data['prioritas'][$initial];
        $query="INSERT INTO rekomendasi VALUES ('$id','$nama','$harga','$stok','$jumlah','$untung','$prioritas')";
        query($query);
        $initial=$initial+1;}}

function urutfuzzy(){
$query="SELECT * FROM rekomendasi ORDER BY prioritas DESC";
$db=query($query);
return $db;}

function fuzzi($jual,$untung,$stok){
$a=fuzzijual($jual);
$b=fuzziuntung($untung);
$c=fuzzistok($stok);
$Prioritas=Inferensi($a,$b,$c);
$nilai=saring($Prioritas);
$hasil=Deffuzifikasi($nilai);
return $hasil;}



    #Mencari Nilai Fuzzi Penjualan
function fuzzijual($pjl=0)  {

$penjualan[0]=$pjl;
if ($penjualan[0]<=25) {$penjualan[1]=1; echo "Penjualan Sedikit Bernilai 1";}
if ($penjualan[0]> 25 && $penjualan[0]<35)
            {
            $penjualan[1]=BahuKanan($penjualan[0],25,35);
            echo "<br>Penjualan Sedikit Bernilai $penjualan[1]";
            $penjualan[2]=BahuKiri($penjualan[0],25,35);
            echo "<br>Penjualan Sedang Bernilai $penjualan[2]";
            }
if ($penjualan[0]>=35 && $penjualan[0]<=65) {$penjualan[2]=1; echo "<br>Penjualan Sedang Bernilai $penjualan[2]";}
if ($penjualan[0]> 65 && $penjualan[0]<75)
            {
            $penjualan[2]=BahuKanan($penjualan[0],65,75);
            echo "<br>Penjualan Sedang Bernilai $penjualan[2]";
            $penjualan[3]=BahuKiri($penjualan[0],65,75);
            echo "<br>Penjualan Banyak Bernilai $penjualan[3]";
            }
if ($penjualan[0]>=75) {$penjualan[3]=1; echo "<br>Penjualan Banyak Bernilai $penjualan[3]";}
        return $penjualan;        }

#Mencari Nilai Fuzzi Untung
function fuzziuntung($utg)  {
    if($utg==0) $stk=1;
$untung[0]=$utg;

if ($untung[0]<=300000) {$untung[1]=1; echo "<br>Keuntungan Sedikit Bernilai 1";}
if ($untung[0]> 300000 && $untung[0]<500000)
            {
            $untung[1]=BahuKanan($untung[0],300000,500000);
            $untung[2]=BahuKiri($untung[0],300000,500000);
            echo "<br>Keuntungan Sedikit Bernilai $untung[1]";
            echo "<br>Keuntungan Sedang Bernilai $untung[2]";
            }
if ($untung[0]>=500000 && $untung[0]<=600000) {$untung[2]=1; echo "<br>Keuntungan Sedikit Bernilai $untung[2]";}
if ($untung[0]> 600000 && $untung[0]<800000)
            {
            $untung[2]=BahuKanan($untung[0],600000,800000);
            $untung[3]=BahuKiri($untung[0],600000,800000);
            echo "<br>Keuntungan Sedang Bernilai $untung[2]";
            echo "<br>Keuntungan Banyak Bernilai $untung[3]";
            }
if ($untung[0]>=800000) {$untung[3]=1; echo "<br>Keuntungan Sedang Bernilai $untung[3]";}
          return $untung;                   }

#Mencari Nilai Fuzzi Stok

function fuzzistok($stk=0)  {
if($stk==0) $stk=1;
    $stok[0]=$stk;
if ($stok[0] <= 25) {$stok[1] = 1; echo "<br>Stok Sedikit Bernilai $stok[1]";}
if ($stok[0] > 25 && $stok[0] < 35) {
            $stok[1] = BahuKanan($stok[0], 25, 35);
            echo "<br>Stok Sedikit Bernilai $stok[1]";
            $stok[2] = BahuKiri($stok[0], 25, 35);
            echo "<br>Stok Sedang Bernilai $stok[2]";
            }
if ($stok[0] >= 35 && $stok[0] <= 65) {$stok[2] = 1; echo "<br>Stok Sedang Bernilai $stok[2]";}
if ($stok[0] > 65 && $stok[0] < 75) {
            $stok[2] = BahuKanan($stok[0], 65, 75);
            echo "<br>Stok Sedang Bernilai $stok[2]";
            $stok[3] = BahuKiri($stok[0], 65, 75);
            echo "<br>Stok Banyak Bernilai $stok[3]";
            }
if ($stok[0] >= 75) {$stok[3] = 1; echo "<br>Stok Banyak Bernilai $stok[3]";}
            return $stok;    }



function BahuKanan($x,$a,$b){
    $tmp=$b-$x;
    $tmp2=$b-$a;
    $hasil=$tmp/$tmp2;
    return $hasil;          }

function BahuKiri($x,$a,$b){
    $tmp=$x-$a;
    $tmp2=$b-$a;
    $hasil=$tmp/$tmp2;
    return $hasil;
            }



function Inferensi($jual,$untung,$stok){
global $ling;
if( !empty($jual[1]) && !empty($stok[1])  && !empty($untung[1]) )                           {
    $tmp=min($jual[1],$stok[1],$untung[1]);
    echo "<br>Memiliki Nilai Jual SEDIKIT (",$jual[1],") Nilai Stok SEDIKIT (",$stok['1'],") Nilai Untung SEDIKIT (",$untung['1'],") sehingga Bernilai Linguistik NORMAL (",$tmp,")";
                                            $ling[2][]=min($jual[1],$stok[1],$untung[1]);   }
if( !empty($jual[1]) && !empty($stok[1]) && !empty($untung[2]) )                            {
    $tmp=min($jual[1],$stok[1],$untung[2]);
    echo "<br>Memiliki Nilai Jual SEDIKIT (",$jual[1],") Nilai Stok SEDIKIT (",$stok['1'],") Nilai Untung SEDANG (",$untung['2'],") sehingga Bernilai Linguistik NORMAL (",$tmp,")";
                                            $ling[2][]=min($jual[1],$stok[1],$untung[2]);   }
if( !empty($jual[1]) && !empty($stok[1]) && !empty($untung[3]) )                            {
    $tmp=min($jual[1],$stok[1],$untung[3]);
    echo "<br>Memiliki Nilai Jual SEDIKIT (",$jual[1],") Nilai Stok SEDIKIT (",$stok['1'],") Nilai Untung BANYAK (",$untung['3'],") sehingga Bernilai Linguistik NORMAL (",$tmp,")";
                                            $ling[2][]=min($jual[1],$stok[1],$untung[3]);   }
if( !empty($jual[1]) && !empty($stok[2]) && !empty($untung[1]) )                            {
    $tmp=min($jual[1],$stok[2],$untung[1]);
    echo "<br>Memiliki Nilai Jual SEDIKIT (",$jual[1],") Nilai Stok SEDANG (",$stok['2'],") Nilai Untung SEDIKIT (",$untung['1'],") sehingga Bernilai Linguistik NORMAL (",$tmp,")";
                                            $ling[2][]=min($jual[1],$stok[2],$untung[1]);   }
if( !empty($jual[1]) && !empty($stok[2]) && !empty($untung[2]) )                            {
    $tmp=min($jual[1],$stok[2],$untung[2]);
    echo "<br>Memiliki Nilai Jual SEDIKIT (",$jual[1],") Nilai Stok SEDANG (",$stok['2'],") Nilai Untung SEDANG (",$untung['2'],") sehingga Bernilai Linguistik NORMAL (",$tmp,")";
                                            $ling[2][]=min($jual[1],$stok[2],$untung[2]);   }
if( !empty($jual[1]) && !empty($stok[2]) && !empty($untung[3]) )                            {
    $tmp=min($jual[1],$stok[2],$untung[3]);
    echo "<br>Memiliki Nilai Jual SEDIKIT (",$jual[1],") Nilai Stok SEDANG (",$stok['2'],") Nilai Untung BANYAK (",$untung['3'],") sehingga Bernilai Linguistik NORMAL (",$tmp,")";
                                            $ling[2][]=min($jual[1],$stok[2],$untung[3]);   }
if( !empty($jual[1]) && !empty($stok[3]) && !empty($untung[1]) )                            {
    $tmp=min($jual[1],$stok[3],$untung[1]);
    echo "<br>Memiliki Nilai Jual SEDIKIT (",$jual[1],") Nilai Stok BANYAK (",$stok['3'],") Nilai Untung SEDIKIT (",$untung['1'],") sehingga Bernilai Linguistik KURANG PENTING (",$tmp,")";
                                            $ling[1][]=min($jual[1],$stok[3],$untung[1]);   }
if( !empty($jual[1]) && !empty($stok[3]) && !empty($untung[2]) )                            {
    $tmp=min($jual[1],$stok[3],$untung[2]);
    echo "<br>Memiliki Nilai Jual SEDIKIT (",$jual[1],") Nilai Stok BANYAK (",$stok['3'],") Nilai Untung SEDANG (",$untung['2'],") sehingga Bernilai Linguistik KURANG PENTING (",$tmp,")";
                                            $ling[1][]=min($jual[1],$stok[3],$untung[2]);   }
if( !empty($jual[1]) && !empty($stok[3]) && !empty($untung[3]) )                            {
    $tmp=min($jual[1],$stok[3],$untung[3]);
    echo "<br>Memiliki Nilai Jual SEDIKIT (",$jual[1],") Nilai Stok BANYAK (",$stok['3'],") Nilai Untung BANYAK (",$untung['3'],") sehingga Bernilai Linguistik KURANG PENTING (",$tmp,")";
                                            $ling[1][]=min($jual[1],$stok[3],$untung[3]);   }
if( !empty($jual[2]) && !empty($stok[1]) && !empty($untung[1]) )                            {
    $tmp=min($jual[2],$stok[1],$untung[1]);
    echo "<br>Memiliki Nilai Jual SEDANG (",$jual[2],") Nilai Stok SEDIKIT (",$stok['1'],") Nilai Untung SEDIKIT (",$untung['1'],") sehingga Bernilai Linguistik NORMAL (",$tmp,")";
                                            $ling[2][]=min($jual[2],$stok[1],$untung[1]);   }
if( !empty($jual[2]) && !empty($stok[1]) && !empty($untung[2]) )                            {
    $tmp=min($jual[2],$stok[1],$untung[2]);
    echo "<br>Memiliki Nilai Jual SEDANG (",$jual[2],") Nilai Stok SEDIKIT (",$stok['1'],") Nilai Untung SEDANG (",$untung['2'],") sehingga Bernilai Linguistik NORMAL (",$tmp,")";
                                            $ling[2][]=min($jual[2],$stok[1],$untung[2]);   }
if( !empty($jual[2]) && !empty($stok[1]) && !empty($untung[3]) )                            {
    $tmp=min($jual[2],$stok[1],$untung[3]);
    echo "<br>Memiliki Nilai Jual SEDANG (",$jual[2],") Nilai Stok SEDIKIT (",$stok['1'],") Nilai Untung BANYAK (",$untung['3'],") sehingga Bernilai Linguistik PENTING (",$tmp,")";
                                            $ling[3][]=min($jual[2],$stok[1],$untung[3]);   }
if( !empty($jual[2]) && !empty($stok[2]) && !empty($untung[1]) )                            {
    $tmp=min($jual[2],$stok[2],$untung[1]);
    echo "<br>Memiliki Nilai Jual SEDANG (",$jual[2],") Nilai Stok SEDANG (",$stok['2'],") Nilai Untung SEDIKIT (",$untung['1'],") sehingga Bernilai Linguistik KURANG PENTING (",$tmp,")";
                                            $ling[1][]=min($jual[2],$stok[2],$untung[1]);   }
if( !empty($jual[2]) && !empty($stok[2]) && !empty($untung[2]) )                            {
    $tmp=min($jual[2],$stok[2],$untung[2]);
    echo "<br>Memiliki Nilai Jual SEDANG (",$jual[2],") Nilai Stok SEDANG (",$stok['2'],") Nilai Untung SEDANG (",$untung['2'],") sehingga Bernilai Linguistik KURANG PENTING (",$tmp,")";
                                            $ling[1][]=min($jual[2],$stok[2],$untung[2]);   }
if( !empty($jual[2]) && !empty($stok[2]) && !empty($untung[3]) )                            {
    $tmp=min($jual[2],$stok[2],$untung[3]);
    echo "<br>Memiliki Nilai Jual SEDANG (",$jual[2],") Nilai Stok SEDANG (",$stok['2'],") Nilai Untung BANYAK (",$untung['3'],") sehingga Bernilai Linguistik KURANG PENTING (",$tmp,")";
                                            $ling[1][]=min($jual[2],$stok[2],$untung[3]);   }
if( !empty($jual[2]) && !empty($stok[3]) && !empty($untung[1]) )                            {
    $tmp=min($jual[2],$stok[3],$untung[1]);
    echo "<br>Memiliki Nilai Jual SEDANG (",$jual[2],") Nilai Stok BANYAK (",$stok['3'],") Nilai Untung SEDIKIT (",$untung['1'],") sehingga Bernilai Linguistik KURANG PENTING (",$tmp,")";
                                            $ling[1][]=min($jual[2],$stok[3],$untung[1]);   }
if( !empty($jual[2]) && !empty($stok[3]) && !empty($untung[2]) )                            {
    $tmp=min($jual[2],$stok[3],$untung[2]);
    echo "<br>Memiliki Nilai Jual SEDANG (",$jual[2],") Nilai Stok BANYAK (",$stok['3'],") Nilai Untung SEDANG (",$untung['2'],") sehingga Bernilai Linguistik NORMAL (",$tmp,")";
                                            $ling[2][]=min($jual[2],$stok[3],$untung[2]);   }
if( !empty($jual[2]) && !empty($stok[3]) && !empty($untung[3]) )                            {
    $tmp=min($jual[2],$stok[3],$untung[3]);
    echo "<br>Memiliki Nilai Jual SEDANG (",$jual[2],") Nilai Stok BANYAK (",$stok['3'],") Nilai Untung BANYAK (",$untung['3'],") sehingga Bernilai Linguistik NORMAL (",$tmp,")";
                                            $ling[2][]=min($jual[2],$stok[3],$untung[3]);   }
if( !empty($jual[3]) && !empty($stok[1]) && !empty($untung[1]) )                            {
    $tmp=min($jual[3],$stok[1],$untung[1]);
    echo "<br>Memiliki Nilai Jual BANYAK (",$jual[3],") Nilai Stok SEDIKIT (",$stok['1'],") Nilai Untung SEDIKIT (",$untung['1'],") sehingga Bernilai Linguistik PENTING (",$tmp,")";
                                            $ling[3][]=min($jual[3],$stok[1],$untung[1]);   }
if( !empty($jual[3]) && !empty($stok[1]) && !empty($untung[2]) )                            {
    $tmp=min($jual[3],$stok[1],$untung[2]);
    echo "<br>Memiliki Nilai Jual BANYAK (",$jual[3],") Nilai Stok SEDIKIT (",$stok['1'],") Nilai Untung SEDANG (",$untung['2'],") sehingga Bernilai Linguistik PENTING (",$tmp,")";
                                            $ling[3][]=min($jual[3],$stok[1],$untung[2]);   }
if( !empty($jual[3]) && !empty($stok[1]) && !empty($untung[3]) )                            {
    $tmp=min($jual[3],$stok[1],$untung[3]);
    echo "<br>Memiliki Nilai Jual BANYAK (",$jual[3],") Nilai Stok SEDIKIT (",$stok['1'],") Nilai Untung BANYAK (",$untung['3'],") sehingga Bernilai Linguistik PENTING (",$tmp,")";
                                            $ling[3][]=min($jual[3],$stok[1],$untung[3]);   }
if( !empty($jual[3]) && !empty($stok[2]) && !empty($untung[1]) )                            {
    $tmp=min($jual[3],$stok[2],$untung[1]);
    echo "<br>Memiliki Nilai Jual BANYAK (",$jual[3],") Nilai Stok SEDANG (",$stok['2'],") Nilai Untung SEDIKIT (",$untung['1'],") sehingga Bernilai Linguistik PENTING (",$tmp,")";
                                            $ling[3][]=min($jual[3],$stok[2],$untung[1]);   }
if( !empty($jual[3]) && !empty($stok[2]) && !empty($untung[2]) )                            {
    $tmp=min($jual[3],$stok[2],$untung[2]);
    echo "<br>Memiliki Nilai Jual BANYAK (",$jual[3],") Nilai Stok SEDANG (",$stok['2'],") Nilai Untung SEDANG (",$untung['2'],") sehingga Bernilai Linguistik Penting (",$tmp,")";
                                            $ling[3][]=min($jual[3],$stok[2],$untung[2]);   }
if( !empty($jual[3]) && !empty($stok[2]) && !empty($untung[3]) )                            {
    $tmp=min($jual[3],$stok[2],$untung[3]);
    echo "<br>Memiliki Nilai Jual BANYAK (",$jual[3],") Nilai Stok SEDANG (",$stok['2'],") Nilai Untung BANYAK (",$untung['3'],") sehingga Bernilai Linguistik PENTING (",$tmp,")";
                                            $ling[3][]=min($jual[3],$stok[2],$untung[3]);   }
if( !empty($jual[3]) && !empty($stok[3]) && !empty($untung[1]) )                            {
    $tmp=min($jual[3],$stok[3],$untung[1]);
    echo "<br>Memiliki Nilai Jual BANYAK (",$jual[3],") Nilai Stok BANYAK (",$stok['3'],") Nilai Untung SEDIKIT (",$untung['1'],") sehingga Bernilai Linguistik NORMAL (",$tmp,")";
                                            $ling[2][]=min($jual[3],$stok[3],$untung[1]);   }
if( !empty($jual[3]) && !empty($stok[3]) && !empty($untung[2]) )                            {
    $tmp=min($jual[3],$stok[3],$untung[2]);
    echo "<br>Memiliki Nilai Jual BANYAK (",$jual[3],") Nilai Stok BANYAK (",$stok['3'],") Nilai Untung SEDANG (",$untung['2'],") sehingga Bernilai Linguistik NORMAL (",$tmp,")";
                                            $ling[2][]=min($jual[3],$stok[3],$untung[2]);   }
if( !empty($jual[3]) && !empty($stok[3]) && !empty($untung[3]) )                            {
    $tmp=min($jual[3],$stok[3],$untung[3]);
    echo "<br>Memiliki Nilai Jual BANYAK (",$jual[3],") Nilai Stok BANYAK (",$stok['3'],") Nilai Untung BANYAK (",$untung['3'],") sehingga Bernilai Linguistik NORMAL (",$tmp,")";
                                            $ling[2][]=min($jual[3],$stok[3],$untung[3]);   }


return $ling;   }

function saring($ling){
if (!empty($ling[1])) { $prioritas[1]=max($ling[1]); }
if (!empty($ling[2])) { $prioritas[2]=max($ling[2]); }
if (!empty($ling[3])) { $prioritas[3]=max($ling[3]); }
return $prioritas;}

function komposisi($ling){
    if (!empty($ling[1])) {
        $prioritas[1]=max($ling[1]);
        foreach($ling[1] as $nilai) echo "<br>Nilai Kurang Penting Bernilai $nilai";
        echo "<br>Nilai Akhir Kurang Penting adalah ",$prioritas[1];
                            }
    if (!empty($ling[2])) { $prioritas[2]=max($ling[2]);
        foreach($ling[2] as $nilai) echo "<br>Nilai Normal Bernilai $nilai";
        echo "<br>Nilai Akhir Normal adalah ",$prioritas[2];
    }
    if (!empty($ling[3])) { $prioritas[3]=max($ling[3]);
        foreach($ling[3] as $nilai) echo "<br>Nilai  Penting Bernilai $nilai";
        echo "<br>Nilai Akhir Penting adalah ",$prioritas[3];
    }
    return $prioritas;}



function TampilInferensi($prioritas)   {
if(!empty($prioritas[1])) { echo "Memiliki Nilai Kurang penting dengan isi : ";
    foreach ($prioritas[1] as $tampil) echo $tampil; }
if(!empty($prioritas[2])) { echo "Memiliki Nilai Normal dengan isi : ";
    foreach ($prioritas[2] as $tampil) echo $tampil; }
if(!empty($prioritas[3])) { echo "Memiliki Nilai penting dengan isi : ";
    foreach ($prioritas[3] as $tampil) echo $tampil," "; }}





function Deffuzifikasi($prioritas){

if(!empty($prioritas[1])){
    echo "<br>Nilai Prioritas Kurang Penting = 20+21+22+23+24+25+26+27+28+29 * $prioritas[1]";
    $a=(20+21+22+23+24+25+26+27+28+29)*$prioritas[1];
    echo "<br>Nilai = $a";
    $a1=10;
    echo "<br>Jumlah Nilai Kurang Penting $a1";
} else {$a=0; $a1=0;}

if(!empty($prioritas[2])){
    echo "<br>Nilai Prioritas  Normal = 50+51+52+53+54+55+56+57+58+59 * $prioritas[2]";
    $b=(50+51+52+53+54+55+56+57+58+59)*$prioritas[2];
    echo "<br>Nilai = $b";
    $b1=10;
    echo "<br>Jumlah Nilai Normal $b1";
} else {$b=0; $b1=0;}

if(!empty($prioritas[3])){
    echo "<br>Nilai Prioritas  Penting = 90+91+92+93+94+95+96+97+98+99+100+101+102+103+104+105+106+107+108+109 * $prioritas[3]";
    $c=(90+91+92+93+94+95+96+97+98+99+100+101+102+103+104+105+106+107+108+109)*$prioritas[3];
    echo "<br>Nilai = $c";
    $c1=20;
    echo "<br>Jumlah Penting = $c1";
} else {$c=0; $c1=0;}
$total=$a+$b+$c;
echo "<br> Total Semua Nilai Adalah $total";
$total1=$a1+$b1+$c1;
echo "<br> Jumlah Semua Nilai Adalah $total1";
$total=$total/$total1;
echo "<br>Nilai Akhir Rekomendasi Adalah $total";
return $total;}



#endregion ?>
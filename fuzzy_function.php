<?php
#region

function persiapandata(){
    $query="SELECT id,barang,harga,SUM(jumlah) AS jumlah,SUM(untung) as untung FROM transaksi GROUP BY barang";
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
                $datautama['satuan'][]=$datadua['satuan'];
                $datautama['untung'][]=$datasatu['untung'];
                // echo $datasatu['barang'],"Stok ",$datadua['stok'],"Penjualan ",$datasatu['jumlah'],"Untung ",$datadua['untung']," ",fuzzi($datasatu['jumlah'],$datadua['untung'],$datadua['stok']),"<br>";
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
        $satuan=$data['satuan'][$initial];
        $untung=$data['untung'][$initial];
        $prioritas=$data['prioritas'][$initial];
        $query="INSERT INTO rekomendasi VALUES ('$id','$nama','$harga','$stok','$jumlah','$satuan','$untung','$prioritas')";
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
$prioritas=Inferensi($a,$b,$c);
$nilai=saring($prioritas);
$hasil=Deffuzifikasi($nilai);
return $hasil;}



    #Mencari Nilai Fuzzi Penjualan
function fuzzijual($pjl)  {
if($pjl==0) $pjl=1;
        $penjualan[0]=$pjl;
if ($penjualan[0]<=25) $penjualan[1]=1;
if ($penjualan[0]> 25 && $penjualan[0]<35)
            {
            $penjualan[1]=BahuKanan($penjualan[0],25,35);
            $penjualan[2]=BahuKiri($penjualan[0],25,35);
            }
if ($penjualan[0]>=35 && $penjualan[0]<=65) $penjualan[2]=1;
if ($penjualan[0]> 65 && $penjualan[0]<75)
            {
            $penjualan[2]=BahuKanan($penjualan[0],65,75);
            $penjualan[3]=BahuKiri($penjualan[0],65,75);
            }
if ($penjualan[0]>=75) $penjualan[3]=1;
        return $penjualan;        }

#Mencari Nilai Fuzzi Untung
function fuzziuntung($utg)  {
    if($utg==0) $stk=1;
$untung[0]=$utg;

if ($untung[0]<=300000) $untung[1]=1;
if ($untung[0]> 300000 && $untung[0]<500000)
            {
            $untung[1]=BahuKanan($untung[0],300000,500000);
            $untung[2]=BahuKiri($untung[0],300000,500000);
            }
if ($untung[0]>=500000 && $untung[0]<=600000) $untung[2]=1;
if ($untung[0]> 600000 && $untung[0]<800000)
            {
            $untung[2]=BahuKanan($untung[0],600000,800000);
            $untung[3]=BahuKiri($untung[0],600000,800000);
            }
if ($untung[0]>=800000) $untung[3]=1;
          return $untung;                   }

#Mencari Nilai Fuzzi Stok

function fuzzistok($stk)  {
if($stk==0) $stk=1;
    $stok[0]=$stk;
if ($stok[0] <= 25) $stok[1] = 1;
if ($stok[0] > 25 && $stok[0] < 35) {
            $stok[1] = BahuKanan($stok[0], 25, 35);
            $stok[2] = BahuKiri($stok[0], 25, 35);
            }
if ($stok[0] >= 35 && $stok[0] <= 65) $stok[2] = 1;
if ($stok[0] > 65 && $stok[0] < 75) {
            $stok[2] = BahuKanan($stok[0], 65, 75);
            $stok[3] = BahuKiri($stok[0], 65, 75);
            }
if ($stok[0] >= 75) $stok[3] = 1;
            return $stok;    }




function BahuKanan($x,$a,$b){
    $tmp=$b-$a;
    $tmp2=$b-$a;
    $hasil=$tmp/$tmp2;
    return $hasil;          }

function BahuKiri($x,$a,$b){
    $tmp=$b-$x;
    $tmp2=$b-$a;
    $hasil=$tmp/$tmp2;
    return $hasil;
            }



function Inferensi($jual,$untung,$stok){
$ling=[];
if( !empty($jual[1]) && !empty($stok[1])  && !empty($untung[1]) )                           {
                                            $ling[2][]=min($jual[1],$stok[1],$untung[1]);   }
if( !empty($jual[1]) && !empty($stok[1]) && !empty($untung[2]) )                            {
                                            $ling[2][]=min($jual[1],$stok[1],$untung[2]);   }
if( !empty($jual[1]) && !empty($stok[1]) && !empty($untung[3]) )                            {
                                            $ling[2][]=min($jual[1],$stok[1],$untung[3]);   }
if( !empty($jual[1]) && !empty($stok[2]) && !empty($untung[1]) )                            {
                                            $ling[2][]=min($jual[1],$stok[2],$untung[1]);   }
if( !empty($jual[1]) && !empty($stok[2]) && !empty($untung[2]) )                            {
                                            $ling[2][]=min($jual[1],$stok[2],$untung[2]);   }
if( !empty($jual[1]) && !empty($stok[2]) && !empty($untung[3]) )                            {
                                            $ling[2][]=min($jual[1],$stok[2],$untung[3]);   }
if( !empty($jual[1]) && !empty($stok[3]) && !empty($untung[1]) )                            {
                                            $ling[1][]=min($jual[1],$stok[3],$untung[1]);   }
if( !empty($jual[1]) && !empty($stok[3]) && !empty($untung[2]) )                            {
                                            $ling[1][]=min($jual[1],$stok[3],$untung[2]);   }
if( !empty($jual[1]) && !empty($stok[3]) && !empty($untung[3]) )                            {
                                            $ling[1][]=min($jual[1],$stok[3],$untung[3]);   }
if( !empty($jual[2]) && !empty($stok[1]) && !empty($untung[1]) )                            {
                                            $ling[2][]=min($jual[2],$stok[1],$untung[1]);   }
if( !empty($jual[2]) && !empty($stok[1]) && !empty($untung[2]) )                            {
                                            $ling[2][]=min($jual[2],$stok[1],$untung[2]);   }
if( !empty($jual[2]) && !empty($stok[1]) && !empty($untung[3]) )                            {
                                            $ling[3][]=min($jual[2],$stok[1],$untung[3]);   }
if( !empty($jual[2]) && !empty($stok[2]) && !empty($untung[1]) )                            {
                                            $ling[1][]=min($jual[2],$stok[2],$untung[1]);   }
if( !empty($jual[2]) && !empty($stok[2]) && !empty($untung[2]) )                            {
                                            $ling[1][]=min($jual[2],$stok[2],$untung[2]);   }
if( !empty($jual[2]) && !empty($stok[2]) && !empty($untung[3]) )                            {
                                            $ling[1][]=min($jual[2],$stok[2],$untung[3]);   }
if( !empty($jual[2]) && !empty($stok[3]) && !empty($untung[1]) )                            {
                                            $ling[1][]=min($jual[2],$stok[3],$untung[1]);   }
if( !empty($jual[2]) && !empty($stok[3]) && !empty($untung[2]) )                            {
                                            $ling[2][]=min($jual[2],$stok[3],$untung[2]);   }
if( !empty($jual[2]) && !empty($stok[3]) && !empty($untung[3]) )                            {
                                            $ling[2][]=min($jual[2],$stok[3],$untung[3]);   }
if( !empty($jual[3]) && !empty($stok[1]) && !empty($untung[1]) )                            {
                                            $ling[3][]=min($jual[3],$stok[1],$untung[1]);   }
if( !empty($jual[3]) && !empty($stok[1]) && !empty($untung[2]) )                            {
                                            $ling[3][]=min($jual[3],$stok[1],$untung[2]);   }
if( !empty($jual[3]) && !empty($stok[1]) && !empty($untung[3]) )                            {
                                            $ling[3][]=min($jual[3],$stok[1],$untung[3]);   }
if( !empty($jual[3]) && !empty($stok[2]) && !empty($untung[1]) )                            {
                                            $ling[3][]=min($jual[3],$stok[2],$untung[1]);   }
if( !empty($jual[3]) && !empty($stok[2]) && !empty($untung[2]) )                            {
                                            $ling[3][]=min($jual[3],$stok[2],$untung[2]);   }
if( !empty($jual[3]) && !empty($stok[2]) && !empty($untung[3]) )                            {
                                            $ling[3][]=min($jual[3],$stok[2],$untung[3]);   }
if( !empty($jual[3]) && !empty($stok[3]) && !empty($untung[1]) )                            {
                                            $ling[2][]=min($jual[3],$stok[3],$untung[1]);   }
if( !empty($jual[3]) && !empty($stok[3]) && !empty($untung[2]) )                            {
                                            $ling[2][]=min($jual[3],$stok[3],$untung[2]);   }
if( !empty($jual[3]) && !empty($stok[3]) && !empty($untung[3]) )                            {

                                            $ling[2][]=min($jual[3],$stok[3],$untung[3]);   }


return $ling;   }

function saring($ling){
if (!empty($ling[1])) { $prioritas[1]=max($ling[1]); }
if (!empty($ling[2])) { $prioritas[2]=max($ling[2]); }
if (!empty($ling[3])) { $prioritas[3]=max($ling[3]); }
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
    $a=(20+21+22+23+24+25+26+27+28+29)*$prioritas[1];
    $a1=10;
} else {$a=0; $a1=0;}

if(!empty($prioritas[2])){
    $b=(50+51+52+53+54+55+56+57+58+59)*$prioritas[2];
    $b1=10;
} else {$b=0; $b1=0;}

if(!empty($prioritas[3])){
    $c=(90+91+92+93+94+95+96+97+98+99+100+101+102+103+104+105+106+107+108+109)*$prioritas[3];
    #echo $c,$prioritas[3];
    $c1=20;
} else {$c=0; $c1=0;}
$total=$a+$b+$c;
$total1=$a1+$b1+$c1;
$total=$total/$total1;
return $total;}



#endregion ?>
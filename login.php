<?php
session_start();


require 'function.php';

if(!isset($_SESSION['blokir'])) { $_SESSION['blokir']=0;}

if($_SESSION['blokir']==1)  {
    if(time()>=$_SESSION['waktublokir']) $_SESSION['blokir']=0;
                            }


if(isset($_POST['tombol1'])){

    if($_SESSION['blokir']==0) {
        $login=ceklogin($_POST);
        if ($login)
            { $_SESSION['login']=1; header('Location: mainmenu.php'); }
                else
                {echo '<div class="pesan">Password Yang Anda Masukkan Salah</div>';
                blokir(); }
                                }
     else echo '<div class="pesan">Anda masih diblokir selama ',$_SESSION['waktublokir']-time(),' detik </div>';
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Login</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>

<div class="kontainer">

                                    <h3>             LOGIN MENU</h3>
    <form action="" method="POST">
        <input id="username" type="text" name="username" autocomplete="off" autofocuss placeholder="Username"></label>
        <input id="password" type="password" name="password" placeholder="Password"></label>
        <label for=""><button name="tombol1">Login</button></label>
    </form>

</div>

</body>
</html>
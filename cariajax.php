<?php
include 'function.php';
$datacari=cari($_GET);

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
<table>
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
        <?php while ($data=mysqli_fetch_assoc($datacari)) : ?>
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
                <a href="edit.php?id=<?=$data['id']; ?>">Edit</a> || <a href="hapus.php?id=<?=$data['id']; ?>">Hapus</a>
            </td> -->
        </tr>
        <?php endwhile; ?>
    </table>
</table>
</body>
</html>
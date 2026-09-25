<html>
    <head>

    </head>
    <body>
        <?php 
        require_once '../database/config.php';
        $id = @$_GET['id'];
        $id_rumah = @$_GET['id_rumah'];
        
        
        $hapus_transaksi = mysqli_query($conn, "DELETE FROM transaksi 
        WHERE id_transaksi = '$id'")or die (mysqli_error($conn));

        $hapus_jns_pem = mysqli_query($conn, "DELETE FROM jenis_pembayaran 
        WHERE id_transaksi = '$id'")or die (mysqli_error($conn));

        $hapus_detail_trans = mysqli_query($conn, "DELETE FROM detail_transaksi 
        WHERE id_transaksi = '$id'")or die (mysqli_error($conn));

        $update_status_rumah = mysqli_query($conn, "UPDATE rumah SET status = '0' WHERE id_rumah = $id_rumah")or die(mysqli_error($conn));

        echo '<script>alert("Data Transaksi Berhasil Dihapus!!");
        window.location.href="index.php";
        </script>';
        ?>
    </body>
</html>
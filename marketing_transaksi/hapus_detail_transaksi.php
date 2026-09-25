<html>
    <head>

    </head>
    <body>
        <?php 
        require_once '../database/config.php';
        $id = @$_GET['id'];
        $id_transaksi =@$_GET['id_transaksi'];
        
        
        $hapus_d_trans = mysqli_query($conn, "DELETE FROM detail_transaksi 
        WHERE id_detail_transaksi = '$id'")or die (mysqli_error($conn));


        echo '<script>alert("Data Transaksi Berhasil Dihapus!!");
        window.location.href="detail.php?id='.$id_transaksi.'";
        </script>';
        ?>
    </body>
</html>
<html>
    <head>

    </head>
    <body>
        <?php 
        require_once '../database/config.php';
        $id = @$_GET['id'];
        $detail_kategori = @$_GET['id_kategori'];
        
        $hapus_kategori = mysqli_query($conn, "DELETE FROM jenis_pembayaran 
        WHERE id_jenis_pembayaran = '$id'")or die (mysqli_error($conn));
        


        echo '<script>alert("Data Kategori Berhasil Dihapus");
        window.location.href="detail.php?id='.$detail_kategori.'";
        </script>';
        ?>
    </body>
</html>
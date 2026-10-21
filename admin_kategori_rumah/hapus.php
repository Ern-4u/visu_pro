<html>
    <head>

    </head>
    <body>
        <?php 
        require_once '../database/config.php';
        $id = @$_GET['id'];
        
        $hapus_kategori = mysqli_query($conn, "DELETE FROM kategori_rumah 
        WHERE id_kategori = '$id'")or die (mysqli_error($conn));
        $hapus_rumah = mysqli_query($conn, "DELETE FROM rumah 
        WHERE id_kategori = '$id'")or die (mysqli_error($conn));
        $hapus_pembayaran = mysqli_query($conn, "DELETE FROM jenis_pembayaran 
        WHERE id_kategori = '$id'")or die (mysqli_error($conn));
        $hapus_foto = mysqli_query($conn, "DELETE FROM foto_rumah 
        WHERE id_kategori = '$id'")or die (mysqli_error($conn));


        echo '<script>alert("Data Kategori Berhasil Dihapus");
        window.location.href="index.php";
        </script>';
        ?>
    </body>
</html>
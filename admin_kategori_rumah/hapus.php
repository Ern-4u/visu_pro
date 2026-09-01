<html>
    <head>

    </head>
    <body>
        <?php 
        require_once '../database/config.php';
        $id = @$_GET['id'];
        
        
        $hapus_kategori = mysqli_query($conn, "DELETE FROM kategori_rumah 
        WHERE id_kategori = '$id'")or die (mysqli_error($conn));


        echo '<script>alert("Data Kategori Berhasil Dihapus");
        window.location.href="index.php";
        </script>';
        ?>
    </body>
</html>
<html>
    <head>

    </head>
    <body>
        <?php 
        require_once '../database/config.php';
        $id = @$_GET['id'];
        $id_kategori = @$_GET['id_kategori'];
        
        $hapus_foto = mysqli_query($conn, "DELETE FROM foto_rumah 
        WHERE id_foto_rumah = '$id'")or die (mysqli_error($conn));


        echo '<script>alert("Foto Kategori Rumah Berhasil Dihapus");
        window.location.href="detail.php?id='.$id_kategori.'";
        </script>';
        ?>
    </body>
</html>
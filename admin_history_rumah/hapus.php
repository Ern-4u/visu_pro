<html>
    <head>

    </head>
    <body>
        <?php 
        require_once '../database/config.php';
        $id = @$_GET['id'];
        $id_rumah = @$_GET['id_rumah'];
        
        
        $hapus_kategori = mysqli_query($conn, "DELETE FROM history_pembangunan 
        WHERE id_history_pembangunan = '$id'")or die (mysqli_error($conn));


        echo '<script>alert("Data Kategori Berhasil Dihapus");
        window.location.href="index.php?id='.$id_rumah.'";
        </script>';
        ?>
    </body>
</html>
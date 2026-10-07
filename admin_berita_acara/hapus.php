<html>
    <head>

    </head>
    <body>
        <?php 
        require_once '../database/config.php';
        $id = @$_GET['id'];
        
        
        $hapus_site_plan = mysqli_query($conn, "DELETE FROM berita_acara 
        WHERE id_berita_acara = '$id'")or die (mysqli_error($conn));


        echo '<script>alert("Data Berhasil Dihapus");
        window.location.href="index.php";
        </script>';
        ?>
    </body>
</html>
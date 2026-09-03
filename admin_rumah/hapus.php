<html>
    <head>

    </head>
    <body>
        <?php 
        require_once '../database/config.php';
        $id = @$_GET['id'];
        
        
        $hapus_rumah = mysqli_query($conn, "DELETE FROM rumah 
        WHERE id_rumah = '$id'")or die (mysqli_error($conn));


        echo '<script>alert("Data Rumah Berhasil Dihapus");
        window.location.href="index.php";
        </script>';
        ?>
    </body>
</html>
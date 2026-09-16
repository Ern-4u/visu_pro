<html>
    <head>

    </head>
    <body>
        <?php 
        require_once '../database/config.php';
        $id = @$_GET['id'];
        
        
        $hapus_site_plan = mysqli_query($conn, "DELETE FROM booking 
        WHERE id_transaksi = '$id'")or die (mysqli_error($conn));


        echo '<script> alert("Data Berhasil Dihapus!!"); 
         window.location.href="detail.php?id='.$id.'" </script>';
        ?>
    </body>
</html>
<html>
    <head>

    </head>
    <body>
        <?php 
        require_once '../database/config.php';
        $id = @$_GET['id'];
        
        
        $hapus_site_plan = mysqli_query($conn, "DELETE FROM site_plan 
        WHERE id_site_plan = '$id'")or die (mysqli_error($conn));


        echo '<script>alert("Data Site Plan Berhasil Dihapus");
        window.location.href="index.php";
        </script>';
        ?>
    </body>
</html>
<html>
    <head>

    </head>
    <body>
        <?php 
        require_once '../database/config.php';
        $id = @$_GET['id'];
        
        
        $hapus_karyawan = mysqli_query($conn, "DELETE FROM marketing 
        WHERE id_karyawan = '$id'")or die (mysqli_error($conn));

        $hapus_data_pengguna = mysqli_query($conn, "DELETE FROM users WHERE username = '$id'") or die(mysqli_error($conn));

        echo '<script>alert("Data Pengguna '.$id.' Berhasil Dihapus");
        window.location.href="index.php"
        </script>';
        ?>
    </body>
</html>
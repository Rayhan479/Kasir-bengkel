<?php 

    @ob_start();
    session_start();

    // Cek apakah ada session admin atau kasir
    if (!empty($_SESSION['admin']) || !empty($_SESSION['kasir'])) {
        require 'config.php';
        include $view;
        $lihat = new view($config);
        $toko = $lihat->toko();

        // Jika user adalah admin
        if (!empty($_SESSION['admin'])) {
            // Menampilkan halaman admin
            include 'admin/template/header.php';
            include 'admin/template/sidebar.php';
            
            if (!empty($_GET['page'])) {
                include 'admin/module/' . $_GET['page'] . '/index.php';
            } else {
                include 'admin/template/home.php';
            }

            include 'admin/template/footer.php';

        // Jika user adalah kasir
        } elseif (!empty($_SESSION['kasir'])) {
            // Menampilkan halaman kasir
            include 'kasir/template/header.php'; 
            include 'kasir/template/sidebar.php';

            if (!empty($_GET['page'])) {
                include 'kasir/module/' . $_GET['page'] . '/index.php';
            } else {
                include 'kasir\module\jual\index.php';
            }

            include 'kasir/template/footer.php';
        }

    } else {
        echo '<script>window.location="login.php";</script>';
        exit;
    }
?>

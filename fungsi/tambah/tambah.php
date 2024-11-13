<?php

session_start();
if (!empty($_SESSION['admin']) || !empty($_SESSION['kasir'])) {
    require '../../config.php';
    if (!empty($_GET['kategori'])) {
        $nama= htmlentities(htmlentities($_POST['kategori']));
        $tgl= date("j F Y, G:i");
        $data[] = $nama;
        $data[] = $tgl;
        $sql = 'INSERT INTO kategori (nama_kategori,tgl_input) VALUES(?,?)';
        $row = $config -> prepare($sql);
        $row -> execute($data);
        echo '<script>window.location="../../index.php?page=kategori&&success=tambah-data"</script>';
    }

    if (!empty($_GET['pengguna'])) {
        $nm_member = htmlentities($_POST['nm_member']);
        $alamat_member = htmlentities($_POST['alamat_member']);
        $telepon = htmlentities($_POST['telepon']);
        $email = htmlentities($_POST['email']);
        $nik = htmlentities($_POST['nik']);
        
        // Handle upload gambar
        $gambar = '';
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['gambar']['tmp_name'];
            $fileName = time() . $_FILES['gambar']['name'];
            $uploadFileDir = '../../assets/img/user/';
            $destPath = $uploadFileDir . $fileName;
        
            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $gambar = $fileName;
            } else {
                echo '<script>alert("Error saat mengunggah file!");</script>';
                $gambar = '';
            }
        }
        
        // Insert data member
        $data_member = [$nm_member, $alamat_member, $telepon, $email, $gambar, $nik];
        $sql_member = 'INSERT INTO member (nm_member, alamat_member, telepon, email, gambar, nik) 
                       VALUES (?, ?, ?, ?, ?, ?)';
        $row_member = $config->prepare($sql_member);
        $row_member->execute($data_member);
        
        // Dapatkan id_member yang baru saja di-insert
        $id_member = $config->lastInsertId();
        
        // Insert data login
        $username = htmlentities($_POST['username']);
        $password = md5(htmlentities($_POST['password'])); // Enkripsi password dengan MD5
        $role = htmlentities($_POST['role']);
        
        $data_login = [$username, $password, $id_member, $role];
        $sql_login = 'INSERT INTO login (user, pass, id_member, role) VALUES (?, ?, ?, ?)';
        $row_login = $config->prepare($sql_login);
        $row_login->execute($data_login);
        
        echo '<script>window.location="../../index.php?page=pengguna&success=tambah-data"</script>';
    }

    if (!empty($_GET['barang'])) {
        $id = htmlentities($_POST['id']);
        $kategori = htmlentities($_POST['kategori']);
        $nama = htmlentities($_POST['nama']);
        $merk = htmlentities($_POST['merk']);
        $beli = htmlentities($_POST['beli']);
        $jual = htmlentities($_POST['jual']);
        $satuan = htmlentities($_POST['satuan']);
        $stok = htmlentities($_POST['stok']);
        $tgl = htmlentities($_POST['tgl']);
        $gambar = htmlentities($_POST['gambar']);


        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['gambar']['tmp_name'];
            $fileName = $_FILES['gambar']['name'];
            $uploadFileDir = '../../assets/img/barang/';
            $destPath = $uploadFileDir . $fileName;
        
            // Pindahkan file yang diunggah ke direktori tujuan
            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $gambar = $fileName; // Simpan nama file di database
            } else {
                echo 'Error saat mengunggah file';
                $gambar = ''; 
            }
        }
    
        // Masukkan data ke array untuk query
        $data[] = $id;
        $data[] = $kategori;
        $data[] = $nama;
        $data[] = $merk;
        $data[] = $beli;
        $data[] = $jual;
        $data[] = $satuan;
        $data[] = $stok;
        $data[] = $tgl;
        $data[] = $gambar; 
    
        
        $sql = 'INSERT INTO barang (id_barang, id_kategori, nama_barang, merk, harga_beli, harga_jual, satuan_barang, stok, tgl_input, gambar) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $row = $config->prepare($sql);
        $row->execute($data);
    
        echo '<script>window.location="../../index.php?page=barang&success=tambah-data"</script>';
    }
    
    
    if (!empty($_GET['jual'])) {
        $id = $_GET['id'];

        // get tabel barang id_barang
        $sql = 'SELECT * FROM barang WHERE id_barang = ?';
        $row = $config->prepare($sql);
        $row->execute(array($id));
        $hsl = $row->fetch();

        if ($hsl['stok'] > 0) {
            $kasir =  $_GET['id_kasir'];
            $jumlah = 1;
            $total = $hsl['harga_jual'];
            $tgl = date("j F Y, G:i");

            $data1[] = $id;
            $data1[] = $kasir;
            $data1[] = $jumlah;
            $data1[] = $total;
            $data1[] = $tgl;

            $sql1 = 'INSERT INTO penjualan (id_barang,id_member,jumlah,total,tanggal_input) VALUES (?,?,?,?,?)';
            $row1 = $config -> prepare($sql1);
            $row1 -> execute($data1);

            echo '<script>window.location="../../index.php?page=jual&success=tambah-data"</script>';
        } else {
            echo '<script>alert("Stok Barang Anda Telah Habis !");
					window.location="../../index.php?page=jual#keranjang"</script>';
        }
    }
}

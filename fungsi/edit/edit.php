<link rel="stylesheet" href="sb-admin\css\main.css">
<?php
session_start();
if (!empty($_SESSION['admin']) || !empty($_SESSION['kasir'])) {
    require '../../config.php';
    if (!empty($_GET['pengaturan'])) {
        $nama= htmlentities($_POST['namatoko']);
        $alamat = htmlentities($_POST['alamat']);
        $kontak = htmlentities($_POST['kontak']);
        $pemilik = htmlentities($_POST['pemilik']);
        $id = '1';

        $data[] = $nama;
        $data[] = $alamat;
        $data[] = $kontak;
        $data[] = $pemilik;
        $data[] = $id;
        $sql = 'UPDATE toko SET nama_toko=?, alamat_toko=?, tlp=?, nama_pemilik=? WHERE id_toko = ?';
        $row = $config -> prepare($sql);
        $row -> execute($data);
        echo '<script>window.location="../../index.php?page=pengaturan&success=edit-data"</script>';
    }


    if (!empty($_GET['pengguna'])) {
        $id_member = htmlentities($_POST['id_member']);
        $nm_member = htmlentities($_POST['nm_member']);
        $alamat_member = htmlentities($_POST['alamat_member']);
        $telepon = htmlentities($_POST['telepon']);
        $email = htmlentities($_POST['email']);
        $nik = htmlentities($_POST['nik']);
        $gambar = htmlentities($_POST['gambar_lama']); // Gunakan gambar lama jika tidak diubah
    
        // Handle upload gambar jika ada gambar baru yang diunggah
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['gambar']['tmp_name'];
            $fileName = time() . $_FILES['gambar']['name'];
            $uploadFileDir = '../../assets/img/member/';
            $destPath = $uploadFileDir . $fileName;
            
            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $gambar = $fileName;
            } else {
                echo '<script>alert("Error saat mengunggah file!");</script>';
            }
        }
    
        // Update data member
        $data_member = [$nm_member, $alamat_member, $telepon, $email, $gambar, $nik, $id_member];
        $sql_member = 'UPDATE member SET nm_member = ?, alamat_member = ?, telepon = ?, email = ?, gambar = ?, nik = ? 
                       WHERE id_member = ?';
        $row_member = $config->prepare($sql_member);
        $row_member->execute($data_member);
    
        // Update data login jika username atau password diperbarui
        if (!empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['role'])) {
            $username = htmlentities($_POST['username']);
            $password = md5(htmlentities($_POST['password'])); // Enkripsi password dengan MD5
            $role = htmlentities($_POST['role']);
            
            $data_login = [$username, $password, $role, $id_member];
            $sql_login = 'UPDATE login SET user = ?, pass = ?, role = ? WHERE id_member = ?';
            $row_login = $config->prepare($sql_login);
            $row_login->execute($data_login);
        }
    
        echo '<script>window.location="../../index.php?page=pengguna/edit&success=edit-data"</script>';
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
            $uploadFileDir = '../../assets/img/user/';
            $destPath = $uploadFileDir . $fileName;
        
            // Pindahkan file yang diunggah ke direktori tujuan
            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $gambar = $fileName; // Simpan nama file di database
            } else {
                echo 'Error saat mengunggah file';
                $gambar = ''; // Jika gagal, kosongkan nama file
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
    
        // Tambahkan kolom gambar_barang pada query INSERT
        $sql = 'INSERT INTO barang (id_barang, id_kategori, nama_barang, merk, harga_beli, harga_jual, satuan_barang, stok, tgl_input, gambar) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $row = $config->prepare($sql);
        $row->execute($data);
    
        echo '<script>window.location="../../index.php?page=barang&success=tambah-data"</script>';
    }


    if (!empty($_GET['kategori'])) {
        $nama= htmlentities($_POST['kategori']);
        $id= htmlentities($_POST['id']);
        $data[] = $nama;
        $data[] = $id;
        $sql = 'UPDATE kategori SET  nama_kategori=? WHERE id_kategori=?';
        $row = $config -> prepare($sql);
        $row -> execute($data);
        echo '<script>window.location="../../index.php?page=kategori&uid='.$id.'&success-edit=edit-data"</script>';
    }

    if (!empty($_GET['stok'])) {
        $restok = htmlentities($_POST['restok']);
        $id = htmlentities($_POST['id']);
        $dataS[] = $id;
        $sqlS = 'select*from barang WHERE id_barang=?';
        $rowS = $config -> prepare($sqlS);
        $rowS -> execute($dataS);
        $hasil = $rowS -> fetch();

        $stok = $restok + $hasil['stok'];

        $data[] = $stok;
        $data[] = $id;
        $sql = 'UPDATE barang SET stok=? WHERE id_barang=?';
        $row = $config -> prepare($sql);
        $row -> execute($data);
        echo '<script>window.location="../../index.php?page=barang&success-stok=stok-data"</script>';
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

        
        
      
    
        $data[] = $kategori;
        $data[] = $nama;
        $data[] = $merk;
        $data[] = $beli;
        $data[] = $jual;
        $data[] = $satuan;
        $data[] = $stok;
        $data[] = $tgl;
        $data[] = $gambar;
        $data[] = $id;
    
        // Perbaikan query SQL dengan koma yang hilang
        $sql = 'UPDATE barang SET id_kategori=?, nama_barang=?, merk=?, harga_beli=?, harga_jual=?, satuan_barang=?, stok=?, tgl_update=?, gambar=? WHERE id_barang=?';
        $row = $config->prepare($sql);
        $row->execute($data);
    
        echo '<script>window.location="../../index.php?page=barang/edit&barang='.$id.'&success=edit-data"</script>';
    }

    
    

    if (!empty($_GET['gambar'])) {
        $id = htmlentities($_POST['id']);
        set_time_limit(0);
        $allowedImageType = array("image/gif", "image/JPG", "image/jpeg", "image/pjpeg", "image/png", "image/x-png", 'image/webp');
        $filepath = $_FILES['foto']['tmp_name'];
        $fileSize = filesize($filepath);
        $fileinfo = finfo_open(FILEINFO_MIME_TYPE);
        $filetype = finfo_file($fileinfo, $filepath);
        $allowedTypes = [
            'image/png'   => 'png',
            'image/jpeg'  => 'jpg',
            'image/gif'   => 'gif',
            'image/jpg'   => 'jpeg',
            'image/webp'  => 'webp'
        ];
        if(!in_array($filetype, array_keys($allowedTypes))) {
            echo '<script>alert("You can only upload JPG, PNG and GIF file");window.location="../../index.php?page=user"</script>';
            exit;
        }else if ($_FILES['foto']["error"] > 0) {
            echo '<script>alert("You can only upload JPG, PNG and GIF file");window.location="../../index.php?page=user"</script>';
            exit;
        } elseif (!in_array($_FILES['foto']["type"], $allowedImageType)) {
            // echo "You can only upload JPG, PNG and GIF file";
            // echo "<font face='Verdana' size='2' ><BR><BR><BR>
            // 		<a href='../../index.php?page=user'>Back to upform</a><BR>";
            echo '<script>alert("You can only upload JPG, PNG and GIF file");window.location="../../index.php?page=user"</script>';
            exit;
        } elseif (round($_FILES['foto']["size"] / 1024) > 4096) {
            // echo "WARNING !!! Besar Gambar Tidak Boleh Lebih Dari 4 MB";
            // echo "<font face='Verdana' size='2' ><BR><BR><BR>
            // 		<a href='../../index.php?page=user'>Back to upform</a><BR>";
            echo '<script>alert("WARNING !!! Besar Gambar Tidak Boleh Lebih Dari 4 MB");window.location="../../index.php?page=user"</script>';
            exit;
        } else {
            $dir = '../../assets/img/user/';
            $tmp_name = $_FILES['foto']['tmp_name'];
            $name = time().basename($_FILES['foto']['name']);
            if (move_uploaded_file($tmp_name, $dir.$name)) {
                //post foto lama
                $foto2 = $_POST['foto2'];
                //remove foto di direktori
                unlink('../../assets/img/user/'.$foto2.'');
                //input foto
                $id = $_POST['id'];
                $data[] = $name;
                $data[] = $id;
                $sql = 'UPDATE member SET gambar=?  WHERE member.id_member=?';
                $row = $config -> prepare($sql);
                $row -> execute($data);
                echo '<script>window.location="../../index.php?page=user&success=edit-data"</script>';
            } else {
                echo '<script>alert("Masukan Gambar !");window.location="../../index.php?page=user"</script>';
                exit;
            }
        }
    }

    if (!empty($_GET['profil'])) {
        $id = htmlentities($_POST['id']);
        $nama = htmlentities($_POST['nama']);
        $alamat = htmlentities($_POST['alamat']);
        $tlp = htmlentities($_POST['tlp']);
        $email = htmlentities($_POST['email']);
        $nik = htmlentities($_POST['nik']);

        $data[] = $nama;
        $data[] = $alamat;
        $data[] = $tlp;
        $data[] = $email;
        $data[] = $nik;
        $data[] = $id;
        $sql = 'UPDATE member SET nm_member=?,alamat_member=?,telepon=?,email=?,NIK=? WHERE id_member=?';
        $row = $config -> prepare($sql);
        $row -> execute($data);
        echo '<script>window.location="../../index.php?page=user&success=edit-data"</script>';
    }
    
    if (!empty($_GET['pass'])) {
        $id = htmlentities($_POST['id']);
        $user = htmlentities($_POST['user']);
        $pass = htmlentities($_POST['pass']);

        $data[] = $user;
        $data[] = $pass;
        $data[] = $id;
        $sql = 'UPDATE login SET user=?,pass=md5(?) WHERE id_member=?';
        $row = $config -> prepare($sql);
        $row -> execute($data);
        echo '<script>window.location="../../index.php?page=user&success=edit-data"</script>';
    }

    if (!empty($_GET['jual'])) {
        $id = htmlentities($_POST['id']);
        $id_barang = htmlentities($_POST['id_barang']);
        $jumlah = htmlentities($_POST['jumlah']);

        $sql_tampil = "select *from barang where barang.id_barang=?";
        $row_tampil = $config -> prepare($sql_tampil);
        $row_tampil -> execute(array($id_barang));
        $hasil = $row_tampil -> fetch();

        if ($hasil['stok'] > $jumlah) {
            $jual = $hasil['harga_jual'];
            $total = $jual * $jumlah;
            $data1[] = $jumlah;
            $data1[] = $total;
            $data1[] = $id;
            $sql1 = 'UPDATE penjualan SET jumlah=?,total=? WHERE id_penjualan=?';
            $row1 = $config -> prepare($sql1);
            $row1 -> execute($data1);
            echo '<script>window.location="../../index.php?page=jual#keranjang"</script>';
        } else {
            echo '<script>alert("Keranjang Melebihi Stok Barang Anda !");
					window.location="../../index.php?page=jual#keranjang"</script>';
        }
    }

    
    
    
if (!empty($_GET['cari_barang'])) {
    $cari = trim(strip_tags($_POST['keyword']));
    if ($cari != '') {
        $sql = "SELECT barang.*, kategori.id_kategori, kategori.nama_kategori
                FROM barang 
                INNER JOIN kategori ON barang.id_kategori = kategori.id_kategori
                WHERE barang.id_barang LIKE :cari 
                OR barang.nama_barang LIKE :cari 
                OR barang.merk LIKE :cari";
        
        $row = $config->prepare($sql);
        $row->execute(['cari' => '%' . $cari . '%']);
        $hasil = $row->fetchAll();
        
        if ($hasil) {
            ?>
            <div class="row">
            <?php foreach ($hasil as $isi) { ?>
                <div class="col-md-3 mb-3">
                    <div class="card h-100" id="card-product">
                        <?php if (!empty($isi['gambar'])) { ?>
                            <img src="../../assets/img/barang/<?php echo $isi['gambar']; ?>" 
                                 class="card-img-top"
                                 alt="<?php echo $isi['nama_barang']; ?>">
                        <?php } else { ?>
                            <div class="text-center p-3 bg-light">
                                <span>Tidak ada gambar</span>
                            </div>
                        <?php } ?>
                        
                        <div class="card-body" >
                            <h6 class="card-title"><?php echo $isi['nama_barang']; ?></h6>
                            <p class="card-text mb-1">ID: <?php echo $isi['id_barang']; ?></p>
                            <p class="card-text mb-1">Merk: <?php echo $isi['merk']; ?></p>
                            <p class="card-text mb-2 fw-bold">
                                Rp<?php echo number_format($isi['harga_jual']); ?>
                            </p>
                            
                            
                                <?php if (!empty($_SESSION['kasir'])) { ?>
                                    <a href="fungsi/tambah/tambah.php?jual=jual&id=<?php echo $isi['id_barang']; ?>&id_kasir=<?php echo $_SESSION['kasir']['id_member']; ?>" 
                                       class="btn btn-success btn-sm">
                                        <i class="fa fa-shopping-cart"></i>
                                    </a>
                                <?php } elseif (!empty($_SESSION['admin'])) { ?>
                                    <a href="fungsi/tambah/tambah.php?jual=jual&id=<?php echo $isi['id_barang']; ?>&id_kasir=<?php echo $_SESSION['admin']['id_member']; ?>" 
                                       class="btn btn-success btn-sm">
                                        <i class="fa fa-shopping-cart"></i>
                                    </a>
                                <?php } ?>
                            
                        </div>
                        
                        <?php if ($isi['stok'] <= 5) { ?>
                            <div class="position-absolute top-0 end-0 p-2">
                                <span class="badge bg-danger">
                                    Stok: <?php echo $isi['stok']; ?>
                                </span>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
            </div>
        <?php } else { ?>
            <div class="alert alert-warning">
                Tidak ditemukan data dengan kata kunci: "<?php echo htmlspecialchars($cari); ?>"
            </div>
        <?php }
    }
}

}

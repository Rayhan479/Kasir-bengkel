<h4>Data Pengguna</h4>
        <br />
        <?php if(isset($_GET['success'])){?>
        <div class="alert alert-success">
            <p>Tambah Data Berhasil !</p>
        </div>
        <?php }?>
        <?php if(isset($_GET['remove'])){?>
        <div class="alert alert-danger">
            <p>Hapus Data Berhasil !</p>
        </div>
        <?php }?>
        
        <!-- view pengguna -->
        <div class="card card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-sm" id="example1">
                    <thead>
                        <tr style="background:#DFF0D8;color:#333;">
                            <th>No.</th>
                            <th>ID Member</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>Telepon</th>
                            <th>Email</th>
                            <th>NIK</th>
                            <th>Foto</th>
                            <th>Role</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $hasil = $lihat->member();
                        $no=1;
                        foreach($hasil as $isi) {
                        ?>
                        <tr>
                            <td><?php echo $no;?></td>
                            <td><?php echo $isi['id_member'];?></td>
                            <td><?php echo $isi['nm_member'];?></td>
                            <td><?php echo $isi['alamat_member'];?></td>
                            <td><?php echo $isi['telepon'];?></td>
                            <td><?php echo $isi['email'];?></td>
                            <td><?php echo $isi['NIK'];?></td>
                            <td>
                                <?php if (!empty($isi['gambar'])): ?>
                                    <img src="assets/img/user/<?php echo $isi['gambar']; ?>" alt="Foto Member" width="60">
                                <?php else: ?>
                                    Tidak ada foto
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php 
                                    $id_member = $isi['id_member'];
                                    $sql = "SELECT role FROM login WHERE id_member = ?";
                                    $row = $config->prepare($sql);
                                    $row->execute(array($id_member));
                                    $user = $row->fetch();
                                    echo $user['role'];
                                ?>
                            </td>
                            <td>
                                <a href="index.php?page=pengguna/edit&member=<?php echo $isi['id_member'];?>">
                                    <button class="btn btn-warning btn-xs">Edit</button>
                                </a>
                                <a href="fungsi/hapus/hapus.php?member=hapus&id=<?php echo $isi['id_member'];?>"
                                    onclick="javascript:return confirm('Hapus Data Pengguna ?');">
                                    <button class="btn btn-danger btn-xs">Hapus</button>
                                </a>
                            </td>
                        </tr>
                        <?php 
                            $no++; 
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- end view pengguna -->
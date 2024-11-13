<?php 
    $id = isset($_GET['member']) ? $_GET['member'] : '';
    $hasil = !empty($id) ? $lihat->member_edit($id) : null;
    $is_edit = !empty($hasil);
?>
<h4><?php echo $is_edit ? 'Edit' : 'Tambah' ?> Pengguna</h4>
<?php if(isset($_GET['success'])){?>
<div class="alert alert-success">
    <p>Data Berhasil <?php echo $is_edit ? 'Diubah' : 'Ditambahkan' ?>!</p>
</div>
<?php }?>
<?php if(isset($_GET['remove'])){?>
<div class="alert alert-danger">
    <p>Hapus Data Berhasil !</p>
</div>
<?php }?>
<div class="card card-body">
    <div class="table-responsive">
        <table class="table table-striped">
            <form action="fungsi/tambah/tambah.php?pengguna=tambah" 
                  method="POST" 
                  enctype="multipart/form-data">
                <?php if($is_edit){ ?>
                <tr>
                    <td>ID Pengguna</td>
                    <td><input type="text" readonly="readonly" class="form-control" 
                             value="<?php echo $is_edit ? $hasil['id_member'] : ''; ?>"
                             name="id_member"></td>
                </tr>
                <?php } ?>
                
                <tr>
                    <td>Nama Lengkap</td>
                    <td><input type="text" class="form-control" required
                             value="<?php echo $is_edit ? $hasil['nm_member'] : ''; ?>" 
                             name="nm_member"></td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td><input type="text" class="form-control" required
                             value="<?php echo $is_edit ? $hasil['alamat_member'] : ''; ?>" 
                             name="alamat_member"></td>
                </tr>
                <tr>
                    <td>No Handphone</td>
                    <td><input type="tel" class="form-control" required
                             value="<?php echo $is_edit ? $hasil['telepon'] : ''; ?>" 
                             name="telepon"></td>
                </tr>
                <tr>
                    <td>E-Mail</td>
                    <td><input type="email" class="form-control" required
                             value="<?php echo $is_edit ? $hasil['email'] : ''; ?>" 
                             name="email"></td>
                </tr>
                <tr>
                    <td>NIK</td>
                    <td><input type="text" class="form-control" required
                             value="<?php echo $is_edit ? $hasil['NIK'] : ''; ?>" 
                             name="nik"></td>
                </tr>

                <tr>
                    <td>Foto</td>
                    <td>
                        <input type="file" accept="image/*" name="gambar" <?php echo $is_edit ? '' : ''; ?>>
                        <?php if ($is_edit && !empty($hasil['gambar'])) { ?>
                            <div class="mt-2">
                                <img src="assets/img/member/<?php echo $hasil['gambar']; ?>" 
                                     alt="Current Image" width="100">
                                <input type="hidden" name="gambar_lama" 
                                       value="<?php echo $hasil['gambar']; ?>">
                            </div>
                        <?php } ?>
                    </td>
                </tr>

                <?php if(!$is_edit){ ?>
                <tr>
                    <td>Username</td>
                    <td><input type="text" class="form-control" required name="username"></td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td><input type="password" class="form-control" required name="password"></td>
                </tr>
                <tr>
                    <td>Role</td>
                    <td>
                        <select name="role" class="form-control" required>
                            <option value="">- Pilih Role -</option>
                            <option value="admin">Admin</option>
                            <option value="kasir">Kasir</option>
                        </select>
                    </td>
                </tr>
                <?php } ?>
                
                <tr>
                    <td>Tanggal <?php echo $is_edit ? 'Update' : 'Input' ?></td>
                    <td><input type="text" readonly="readonly" class="form-control" 
                             value="<?php echo date("j F Y, G:i");?>" name="tgl"></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <button class="btn btn-primary">
                            <i class="fa fa-<?php echo $is_edit ? 'edit' : 'plus' ?>"></i> 
                            <?php echo $is_edit ? 'Update' : 'Tambah' ?> Data
                        </button>
                    </td>
                </tr>
            </form>
        </table>
    </div>
</div>
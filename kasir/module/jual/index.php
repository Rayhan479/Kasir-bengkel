<?php 
    $id = $_SESSION['kasir']['id_member'];
    $hasil = $lihat->member_edit($id);
?>
<h4>Keranjang Penjualan</h4>
<br>
<?php if(isset($_GET['success'])){?>
<div class="alert alert-success" role="alert">
    <p>Edit Data Berhasil!</p>
</div>
<?php }?>
<?php if(isset($_GET['remove'])){?>
<div class="alert alert-danger" role="alert">
    <p>Hapus Data Berhasil!</p>
</div>
<?php }?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5><i class="fa fa-shopping-cart"></i> KASIR
                    <a class="btn btn-danger float-end" 
                        onclick="javascript:return confirm('Apakah anda ingin reset keranjang ?');" href="fungsi/hapus/hapus.php?penjualan=jual">
                        <b>RESET KERANJANG</b></a>
                </h5>
            </div>
            <div class="card-body">
                <div id="keranjang" class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <td><b>Tanggal</b></td>
                            <td><input type="text" readonly class="form-control" value="<?php echo date("j F Y, G:i");?>" name="tgl"></td>
                        </tr>
                    </table>
                    <table class="table table-bordered w-100" id="example1">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th style="width:10%;">Jumlah</th>
                                <th style="width:20%;">Total</th>
                                <th>Kasir</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $total_bayar=0; $no=1; $hasil_penjualan = $lihat->penjualan();?>
                            <?php foreach($hasil_penjualan as $isi){?>
                            <tr>
                                <td><?php echo $no;?></td>
                                <td><?php echo $isi['nama_barang'];?></td>
                                <td>
                                    <form method="POST" action="fungsi/edit/edit.php?jual=jual">
                                        <input type="number" name="jumlah" value="<?php echo $isi['jumlah'];?>" class="form-control">
                                        <input type="hidden" name="id" value="<?php echo $isi['id_penjualan'];?>" class="form-control">
                                        <input type="hidden" name="id_barang" value="<?php echo $isi['id_barang'];?>" class="form-control">
                                </td>
                                <td>Rp.<?php echo number_format($isi['total']);?>,-</td>
                                <td><?php echo $isi['nm_member'];?></td>
                                <td>
                                    <button type="submit" class="btn btn-warning">Update</button>
                                    </form>
                                    <a href="fungsi/hapus/hapus.php?jual=jual&id=<?php echo $isi['id_penjualan'];?>&brg=<?php echo $isi['id_barang'];?>&jml=<?php echo $isi['jumlah']; ?>" class="btn btn-danger"><i class="fa fa-times"></i></a>
                                </td>
                            </tr>
                            <?php $no++; $total_bayar += $isi['total']; }?>
                        </tbody>
                    </table>
                    <br>
                    <div id="kasirnya">
                        <table class="table table-striped">
                        <?php
							// proses bayar dan ke nota
							if(!empty($_GET['nota'] == 'yes')) {
								$total = $_POST['total'];
								$bayar = $_POST['bayar'];
								if(!empty($bayar))
								{
									$hitung = $bayar - $total;
									if($bayar >= $total)
									{
										$id_barang = $_POST['id_barang'];
										$id_member = $_POST['id_member'];
										$jumlah = $_POST['jumlah'];
										$total = $_POST['total1'];
										$tgl_input = $_POST['tgl_input'];
										$periode = $_POST['periode'];
										$jumlah_dipilih = count($id_barang);
										
										for($x=0;$x<$jumlah_dipilih;$x++){

											$d = array($id_barang[$x],$id_member[$x],$jumlah[$x],$total[$x],$tgl_input[$x],$periode[$x]);
											$sql = "INSERT INTO nota (id_barang,id_member,jumlah,total,tanggal_input,periode) VALUES(?,?,?,?,?,?)";
											$row = $config->prepare($sql);
											$row->execute($d);

											// ubah stok barang
											$sql_barang = "SELECT * FROM barang WHERE id_barang = ?";
											$row_barang = $config->prepare($sql_barang);
											$row_barang->execute(array($id_barang[$x]));
											$hsl = $row_barang->fetch();
											
											$stok = $hsl['stok'];
											$idb  = $hsl['id_barang'];

											$total_stok = $stok - $jumlah[$x];
											// echo $total_stok;
											$sql_stok = "UPDATE barang SET stok = ? WHERE id_barang = ?";
											$row_stok = $config->prepare($sql_stok);
											$row_stok->execute(array($total_stok, $idb));
										}
										echo '<script>alert("Belanjaan Berhasil Di Bayar !");</script>';
									}else{
										echo '<script>alert("Uang Kurang ! Rp.'.$hitung.'");</script>';
									}
								}
							}
							?>
                            <!-- form pembayaran -->
                            <form method="POST" action="index.php?page=jual&nota=yes#kasirnya">
                                <?php foreach($hasil_penjualan as $isi){?>
                                    <input type="hidden" name="id_barang[]" value="<?php echo $isi['id_barang'];?>">
                                    <input type="hidden" name="id_member[]" value="<?php echo $isi['id_member'];?>">
                                    <input type="hidden" name="jumlah[]" value="<?php echo $isi['jumlah'];?>">
                                    <input type="hidden" name="total1[]" value="<?php echo $isi['total'];?>">
                                    <input type="hidden" name="tgl_input[]" value="<?php echo $isi['tanggal_input'];?>">
                                    <input type="hidden" name="periode[]" value="<?php echo date('m-Y');?>">
                                <?php }?>
                                <tr>
									<td>Total Semua  </td>
									<td><input type="text" class="form-control" name="total" value="<?php echo $total_bayar;?>"></td>
								
									<td>Bayar  </td>
									<td><input type="text" class="form-control" name="bayar" value="<?php echo $bayar;?>"></td>
									<td><button class="btn btn-success"><i class="fa fa-shopping-cart"></i> Bayar</button>
									<?php  if(!empty($_GET['nota'] == 'yes')) {?>
										<a class="btn btn-danger" href="fungsi/hapus/hapus.php?penjualan=jual">
										<b>RESET</b></a></td><?php }?></td>
								</tr>
                            </form>
                            <tr>
                                <td>Kembali</td>
                                <td><input type="text" class="form-control" value="<?php echo isset($hitung) ? $hitung : ''; ?>"></td>
                                <td></td>
                                <td>
                                    <a href="print.php?nm_member=<?php echo $_SESSION['kasir']['nm_member'];?>&bayar=<?php echo isset($bayar) ? $bayar : ''; ?>&kembali=<?php echo isset($hitung) ? $hitung : ''; ?>" target="_blank">
                                        <button class="btn btn-secondary"><i class="fa fa-print"></i> Print Untuk Bukti Pembayaran</button>
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

    

<!-- Bagian HTML tetap sama sampai card-body -->
<div class="col-md-12 mt-4">
    <div class="card mb-3">
        <div class="card-header bg-primary text-white">
            <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item">
                    <h5><i class="fa fa-list"></i> Daftar barang</h5>
                </li>
                <li class="nav-item ms-auto">
                    <input type="text" id="cari" class="form-control" name="cari" 
                           placeholder="Masukkan: Kode / Nama Barang  [ENTER]">
                </li>
            </ul>
        </div>
        <div class="card-body">
            

            <!-- Tambahkan div untuk grid produk default -->
            <div id="default-products" class="row">
                <?php
                // Inisialisasi variabel
                $totalJual = 0;
                $totalStok = 0;
                
                // Pengecekan data hasil query
                if (!empty($hasil) && is_array($hasil)) {
                    // Menentukan sumber data berdasarkan parameter stok
                    $hasil = isset($_GET['stok']) && $_GET['stok'] == 'yes' ? 
                            $lihat->barang_stok() : 
                            $lihat->barang();

                    // Loop untuk menampilkan setiap barang
                    foreach ($hasil as $isi) :
                ?>
                    <div class="col-md-3 mb-3">
                        <div class="card" id="card-product">
                            <!-- Konten card sama seperti sebelumnya -->
                            <?php if (!empty($isi['gambar'])) : ?>
                                <img src="../../assets/img/barang/<?php echo htmlspecialchars($isi['gambar']); ?>" 
                                     alt="Gambar <?php echo htmlspecialchars($isi['nama_barang']); ?>">
                            <?php else : ?>
                                <div class="text-center p-3">Tidak ada gambar</div>
                            <?php endif; ?>

                            <div class="card-body">
                                <!-- Konten card-body sama seperti sebelumnya -->
                                <h5 class="card-title"><?php echo htmlspecialchars($isi['nama_barang']); ?></h5>
                                <p class="card-text">Harga Jual: Rp. <?php echo number_format($isi['harga_jual']); ?></p>
                                <p class="card-text">Stok: <?php echo htmlspecialchars($isi['stok']); ?></p>

                                <?php
                                $user_id = '';
                                if (!empty($_SESSION['kasir'])) {
                                    $user_id = $_SESSION['kasir']['id_member'];
                                } elseif (!empty($_SESSION['admin'])) {
                                    $user_id = $_SESSION['admin']['id_member'];
                                }

                                if ($user_id) :
                                ?>
                                    <a href="fungsi/tambah/tambah.php?jual=jual&id=<?php echo $isi['id_barang']; ?>&id_kasir=<?php echo $user_id; ?>" 
                                       class="btn btn-success">
                                        <i class="fa fa-shopping-cart"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php 
                    endforeach;
                } else {
                ?>
                    <div class="col-12">
                        <p class="text-center">Tidak ada data barang yang tersedia.</p>
                    </div>
                <?php 
                } 
                ?>
            </div>

            

            <!-- Navigasi Pagination -->
            <div class="pagination justify-content-center mt-4">
                <?php if ($totalPages > 1): ?>
                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                            <?php if ($currentPage > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $currentPage - 1; ?>" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?php echo ($i === $currentPage) ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($currentPage < $totalPages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $currentPage + 1; ?>" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>

            <div class="table-responsive">
                <div id="hasil_cari"></div>
                <div id="tunggu"></div>
            </div>
        </div>
    </div>
</div>

<!-- Script JavaScript yang dimodifikasi -->
<script>
document.getElementById('cari').addEventListener('keyup', function(e) {
    const defaultProducts = document.getElementById('default-products');
    const hasilCari = document.getElementById('hasil_cari');
    const tunggu = document.getElementById('tunggu');
    
    if (e.key === 'Enter') {
        let keyword = this.value;
        
        if (keyword !== '') {
            // Sembunyikan grid produk default
            defaultProducts.style.display = 'none';
            tunggu.innerHTML = 'Tunggu Sebentar...';
            
            fetch('fungsi/edit/edit.php?cari_barang=yes', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'keyword=' + encodeURIComponent(keyword)
            })
            .then(response => response.text())
            .then(data => {
                hasilCari.innerHTML = data;
                tunggu.innerHTML = '';
                hasilCari.style.display = 'block';
            })
            .catch(error => {
                console.error('Error:', error);
                tunggu.innerHTML = 'Terjadi kesalahan dalam pencarian';
            });
        } else {
            // Jika keyword kosong, tampilkan kembali grid produk default
            defaultProducts.style.display = 'flex';
            hasilCari.style.display = 'none';
            hasilCari.innerHTML = '';
        }
    }
});

// Tambahkan event listener untuk reset pencarian ketika input dikosongkan
document.getElementById('cari').addEventListener('input', function(e) {
    const defaultProducts = document.getElementById('default-products');
    const hasilCari = document.getElementById('hasil_cari');
    
    if (this.value === '') {
        defaultProducts.style.display = 'flex';
        hasilCari.style.display = 'none';
        hasilCari.innerHTML = '';
    }
});
</script>







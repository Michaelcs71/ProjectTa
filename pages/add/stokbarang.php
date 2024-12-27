<div class="modal fade" id="insertModalStokBarang" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Form Data Tambah Stok Barang Jadi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="webservice/insert.php" enctype="multipart/form-data">
                    <!-- Informasi Umum -->

                    <div class="mb-3">
                        <label for="nama_pekerja" class="form-label">Nama Pekerja</label>
                        <select class="form-select" name="nama_pekerja" required>
                            <option selected disabled>Pilih Nama</option>
                            <?php
                            $queryGetNama = "SELECT * FROM master_pekerja";
                            $getNama = mysqli_query($koneksi, $queryGetNama);
                            while ($nama = mysqli_fetch_assoc($getNama)) {
                            ?>
                                <option value="<?= $nama['id_pekerja'] ?>"><?= $nama['nama_pekerja'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Akun</label>
                        <select data-trigger class="form-select" name="nama_akun" id="namaakun" required>
                            <option selected disabled>Masukkan Nama</option>
                            <?php
                            $queryGetNama = "SELECT * FROM master_akun";
                            $getNama = mysqli_query($koneksi, $queryGetNama);
                            while ($nama = mysqli_fetch_assoc($getNama)) {
                            ?>
                                <option value="<?= $nama['id_akun'] ?>">
                                    <?= $nama['nama_akun'] ?>
                                </option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="tanggal" class="form-label">Tanggal Setor</label>
                            <input type="date" class="form-control" name="tanggal" required>
                        </div>
                        <div class="col-md-6">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <input type="text" class="form-control" name="keterangan" required>
                        </div>
                    </div>

                    <hr>
                    <!-- Tambah Barang -->

                    <label class="form-label"><strong>Detail Barang</strong></label>
                    <div id="formBarangJadiMasuk">
                        <div class="row mb-3 barang-item">
                            <div class="col-md-3">
                                <select class="form-control " name="nama_barang[]" required>
                                    <option selected disabled>Pilih Material</option>
                                    <?php
                                    $queryGetNama = "SELECT * FROM master_barang_jadi";
                                    $getNama = mysqli_query($koneksi, $queryGetNama);
                                    while ($nama = mysqli_fetch_assoc($getNama)) {
                                    ?>
                                        <option value="<?= $nama['id_barang_jadi'] ?>">
                                            <?= $nama['nama_barang'] ?>
                                        </option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="number" class="form-control jumlah-barang" name="jumlah_barang[]" placeholder="Jumlah" required>
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-danger btn-sm hapus-barang">X</button>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="tambahBarangJadiMasuk" class="btn btn-success btn-sm mb-3">+ Tambah</button>
                    <hr>
                    <!-- Tombol Simpan -->
                    <div class="mb-3 d-flex justify-content-end">
                        <button name="insert_stokbarangjadi" type="submit" class="btn btn-primary">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Pastikan hanya bekerja di dalam modal ini
        const modalID = "insertModalStokBarang";
        const tambahBarangButton = document.querySelector(`#${modalID} #tambahBarangJadiMasuk`);
        const formBarangJadiMasuk = document.querySelector(`#${modalID} #formBarangJadiMasuk`);

        if (tambahBarangButton && formBarangJadiMasuk) {
            // Tambah Baris
            tambahBarangButton.addEventListener("click", function() {
                const newBarangJadi = document.createElement("div");
                newBarangJadi.classList.add("row", "mb-3", "barang-item");
                newBarangJadi.innerHTML = `
                <div class="col-md-3">
                                <select class="form-control " name="nama_barang[]" required>
                                    <option selected disabled>Pilih Material</option>
                                    <?php
                                    $queryGetNama = "SELECT * FROM master_barang_jadi";
                                    $getNama = mysqli_query($koneksi, $queryGetNama);
                                    while ($nama = mysqli_fetch_assoc($getNama)) {
                                    ?>
                                        <option value="<?= $nama['id_barang_jadi'] ?>">
                                            <?= $nama['nama_barang'] ?>
                                        </option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="number" class="form-control jumlah-barang" name="jumlah_barang[]" placeholder="Jumlah" required>
                            </div>
                           
            `;
                formBarangJadiMasuk.appendChild(newBarangJadi);
                attachDeleteHandler(newBarangJadi);
            });

            // Hapus Baris
            function attachDeleteHandler(row) {
                const deleteButton = row.querySelector(".hapus-barang");
                deleteButton.addEventListener("click", function() {
                    row.remove();
                });
            }

            // Pasang Listener ke Baris Awal
            document.querySelectorAll(`#${modalID} .barang-item`).forEach(attachDeleteHandler);
        }
    });
</script>
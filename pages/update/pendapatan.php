<div class="modal fade" id="updateModalPendapatan" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Update Status Pekerja</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="webservice/update.php" enctype="multipart/form-data">
                    <div class="modal-body">
                        <form method="POST" action="webservice/update.php" enctype="multipart/form-data">
                            <input name="id_pendapatan" type="hidden" class="form-control" id="id_bhn_splr">
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Platform</label>
                                <select data-trigger class="form-select" name="nama_platform" id="nmplat" required>
                                    <option selected disabled>Masukkan Nama</option>
                                    <?php
                                    $queryGetNama = "SELECT * FROM master_platform";
                                    $getNama = mysqli_query($koneksi, $queryGetNama);
                                    while ($nama = mysqli_fetch_assoc($getNama)) {
                                    ?>
                                        <option value="<?= $nama['id_platform'] ?>">
                                            <?= $nama['nama_platform'] ?>
                                        </option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Total Pendapatan</label>
                                <input type="number" class="form-control" name="total_pendapatan" id="totalpend" required>
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Tanggal Pendapatan</label>
                                <input type="date" class="form-control" name="tanggal_pendapatan" id="tglpend" required>
                            </div>
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Akun</label>
                                <select data-trigger class="form-select" name="id_akun" id="nmakun" required>
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
                            <div class="mb-3">
                                <button name="update_pendapatan" type="submit" class="btn btn-primary">Simpan Data</button>
                            </div>
                        </form>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
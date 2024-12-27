<div class="modal fade" id="insertModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Form Tambah Pendapatan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="webservice/insert.php" enctype="multipart/form-data">
                    <!-- Informasi Umum -->

                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Platform</label>
                        <select data-trigger class="form-select" name="nama_platform" id="nama" required>
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
                        <input type="number" class="form-control" name="total_pendapatan" id="" required>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Tanggal Pendapatan</label>
                        <input type="date" class="form-control" name="tanggal_pendapatan" placeholder="Masukkan tanggal terakhir pada bulan yang dipilih" id="" required>
                    </div>
                    <div class="mb-3" hidden>
                        <label for="nama" class="form-label">Nama Akun</label>
                        <select data-trigger class="form-select" name="nama_akun" id="namaakun" required>
                            <option selected disabled>Masukkan Nama</option>
                            <?php
                            $queryGetNama = "SELECT * FROM master_akun";
                            $getNama = mysqli_query($koneksi, $queryGetNama);
                            while ($nama = mysqli_fetch_assoc($getNama)) {
                                // Menambahkan logika untuk memilih kode akun 401
                                $selected = ($nama['id_akun'] == 401) ? 'selected' : '';
                            ?>
                                <option value="<?= $nama['id_akun'] ?>" <?= $selected ?>>
                                    <?= $nama['nama_akun'] ?>
                                </option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Tambah Barang -->
                    <label class="form-label"><strong>Detail Barang Terjual</strong></label>
                    <div id="form-barang">
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
                                <input type="number" class="form-control total-barang" name="total_barang[]" placeholder="total" required>
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-danger btn-sm remove-barang">X</button>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="tambah-barang" class="btn btn-success btn-sm mb-3">+ Tambah</button>


                    <!-- Tombol Simpan -->
                    <div class="mb-3 d-flex justify-content-end">
                        <button name="insert_pendapatan" type="submit" class="btn btn-primary">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function hitungSubtotal(row) {
        const total = row.querySelector('.total-barang').value || 0;
        const harga = row.querySelector('.harga-barang').value || 0;
        const subtotal = row.querySelector('.subtotal');

        subtotal.value = total * harga;
        hitungTotalKeseluruhan();
    }

    function hitungTotalKeseluruhan() {
        const subtotals = document.querySelectorAll('.subtotal');
        let total = 0;
        subtotals.forEach(sub => {
            total += parseFloat(sub.value) || 0;
        });
        document.getElementById('total-pembelian').value = total;
    }

    // Tambah Baris Barang
    document.getElementById('tambah-barang').addEventListener('click', function() {
        const formBarang = document.getElementById('form-barang');
        const newBarang = document.createElement('div');
        newBarang.classList.add('row', 'mb-3', 'barang-item');
        newBarang.innerHTML = `
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
                <input type="number" class="form-control total-barang" name="total_barang[]" placeholder="total" required>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger btn-sm remove-barang">X</button>
            </div>
        `;
        formBarang.appendChild(newBarang);
        attachEventsToRow(newBarang);
    });

    function attachEventsToRow(row) {
        row.querySelector('.total-barang').addEventListener('input', () => hitungSubtotal(row));
        row.querySelector('.harga-barang').addEventListener('input', () => hitungSubtotal(row));
        row.querySelector('.remove-barang').addEventListener('click', () => {
            row.remove();
            hitungTotalKeseluruhan();
        });
    }

    // Attach initial events
    document.querySelectorAll('.barang-item').forEach(attachEventsToRow);
</script>
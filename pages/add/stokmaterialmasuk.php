<div class="modal fade" id="insertModalMasuk" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Form Data Pembelian Material</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="webservice/insert.php" enctype="multipart/form-data">
                    <!-- Informasi Umum -->
                    <div class="mb-3">
                        <label for="nama_supplier" class="form-label">Nama Supplier</label>
                        <select class="form-select" name="nama_supplier" required>
                            <option selected disabled>Pilih Nama</option>
                            <?php
                            $queryGetNama = "SELECT * FROM master_supplier WHERE status = 'Aktif'";
                            $getNama = mysqli_query($koneksi, $queryGetNama);
                            while ($nama = mysqli_fetch_assoc($getNama)) {
                            ?>
                                <option value="<?= $nama['id_supplier'] ?>"><?= $nama['nama_supplier'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_pembelian" class="form-label">Tanggal Pembelian</label>
                        <input type="date" class="form-control" name="tanggal_pembelian" required>
                    </div>

                    <!-- Tambah Barang -->
                    <label class="form-label"><strong>Detail Pembelian</strong></label>
                    <div id="form-barang">
                        <div class="row mb-3 barang-item">
                            <div class="col-md-3">
                                <select class="form-control nama-bahan-material" name="nama_bahan_material[]" required>
                                    <option selected disabled>Pilih Material</option>
                                    <?php
                                    $queryGetNama = "SELECT * FROM master_bahan_material WHERE status = 'Aktif'";
                                    $getNama = mysqli_query($koneksi, $queryGetNama);
                                    while ($nama = mysqli_fetch_assoc($getNama)) {
                                    ?>
                                        <option value="<?= $nama['id_bahan_material'] ?>">
                                            <?= $nama['nama_bahan_material'] ?>
                                        </option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="number" class="form-control jumlah-barang" name="jumlah_barang[]" placeholder="Jumlah" required>
                            </div>
                            <div class="col-md-2">
                                <input type="number" class="form-control harga-barang" name="harga_barang[]" placeholder="Harga" required>
                            </div>
                            <div class="col-md-2">
                                <input type="number" class="form-control subtotal" name="subtotal[]" placeholder="Subtotal" readonly>
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-danger btn-sm remove-barang-masuk">X</button>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="tambah-barang" class="btn btn-success btn-sm mb-3">+ Tambah</button>

                    <!-- Total Keseluruhan -->
                    <div class="mb-3">
                        <label for="total_pembelian" class="form-label"><strong>Total Pembelian</strong></label>
                        <input type="number" id="total-pembelian" class="form-control" name="total_pembelian" readonly>
                    </div>

                    <!-- Tombol Simpan -->
                    <div class="mb-3 d-flex justify-content-end">
                        <button name="insert_stokmasuk" type="submit" class="btn btn-primary">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fungsi untuk menghitung subtotal per baris
        function hitungSubtotal(row) {
            const jumlahInput = row.querySelector('.jumlah-barang');
            const hargaInput = row.querySelector('.harga-barang');
            const subtotalInput = row.querySelector('.subtotal');

            const jumlah = parseFloat(jumlahInput.value) || 0;
            const harga = parseFloat(hargaInput.value) || 0;

            subtotalInput.value = jumlah * harga;
        }

        // Fungsi untuk menghitung total keseluruhan
        function hitungTotalKeseluruhan() {
            const subtotals = document.querySelectorAll('.subtotal');
            let total = 0;

            subtotals.forEach(sub => {
                total += parseFloat(sub.value) || 0;
            });

            document.getElementById('total-pembelian').value = total;
        }

        // Fungsi untuk menambahkan event ke baris baru
        function attachEventsToRow(row) {
            const jumlahInput = row.querySelector('.jumlah-barang');
            const hargaInput = row.querySelector('.harga-barang');
            const removeButton = row.querySelector('.remove-barang-masuk');

            jumlahInput.addEventListener('input', () => {
                hitungSubtotal(row);
                hitungTotalKeseluruhan(); // Perbarui total pembelian saat subtotal berubah
            });
            hargaInput.addEventListener('input', () => {
                hitungSubtotal(row);
                hitungTotalKeseluruhan(); // Perbarui total pembelian saat subtotal berubah
            });

            // Tombol X untuk menghapus baris
            removeButton.addEventListener('click', () => {
                row.remove();
                hitungTotalKeseluruhan();
            });
        }

        // Menambahkan baris baru
        document.getElementById('tambah-barang').addEventListener('click', function() {
            const formBarang = document.getElementById('form-barang');
            const newBarang = document.createElement('div');
            newBarang.classList.add('row', 'mb-3', 'barang-item');

            newBarang.innerHTML = `
                <div class="col-md-3">
                    <select class="form-control nama-bahan-material" name="nama_bahan_material[]" required>
                        <option selected disabled>Pilih Material</option>
                        <?php
                        $queryGetNama = "SELECT * FROM master_bahan_material WHERE status = 'Aktif'";
                        $getNama = mysqli_query($koneksi, $queryGetNama);
                        while ($nama = mysqli_fetch_assoc($getNama)) {
                        ?>
                            <option value="<?= $nama['id_bahan_material'] ?>">
                                <?= $nama['nama_bahan_material'] ?>
                            </option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="number" class="form-control jumlah-barang" name="jumlah_barang[]" placeholder="Jumlah" required>
                </div>
                
                <div class="col-md-2">
                    <input type="number" class="form-control harga-barang" name="harga_barang[]" placeholder="Harga" required>
                </div>
                <div class="col-md-2">
                    <input type="number" class="form-control subtotal" name="subtotal[]" placeholder="Subtotal" readonly>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger btn-sm remove-barang-masuk">X</button>
                </div>
            `;

            formBarang.appendChild(newBarang);
            attachEventsToRow(newBarang);
        });

        // Menambahkan event ke baris awal yang ada di dalam form
        document.querySelectorAll('.barang-item').forEach(attachEventsToRow);
    });
</script>
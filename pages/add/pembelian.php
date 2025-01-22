<div class="modal fade" id="insertModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Form Data Pembelian Peralatan</h5>
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
                    <div class="col-md-3 mt-2">
                        <label for="tanggal_pembelian" class="form-label">Tanggal Pembelian</label>
                        <input type="date" class="form-control tanggal-pembelian" name="tanggal_pembelian[]" placeholder="Tanggal Pembelian" required>
                    </div>
                    <div class="mb-3" style="display: none;">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" name="status" id="idstatus">
                            <option value="Aktif" selected>Aktif</option>
                            <option value="Tidak Aktif">Tidak Aktif</option>
                        </select>
                    </div>

                    <!-- Tambah Barang -->
                    <hr>
                    <label class="form-label"><strong>Detail Pembelian</strong></label>
                    <div id="form-barang">
                        <div class="row mb-3 barang-item">
                            <!-- Baris Atas -->

                            <div class="col-md-4">
                                <label for="nama-barang-1" class="form-label">Nama Barang</label>
                                <select class="form-select" name="nama_peralatan[]" required>
                                    <option selected disabled>Pilih Nama</option>
                                    <?php
                                    $queryGetNama = "SELECT * FROM master_peralatan WHERE status = 'Aktif'";
                                    $getNama = mysqli_query($koneksi, $queryGetNama);
                                    while ($nama = mysqli_fetch_assoc($getNama)) {
                                    ?>
                                        <option value="<?= $nama['id_peralatan'] ?>"><?= $nama['nama_peralatan'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="jumlah-barang-1" class="form-label">Jumlah</label>
                                <input type="number" class="form-control jumlah-barang" name="jumlah_barang[]" placeholder="Jumlah" required>
                            </div>

                            <div class="col-md-2">
                                <label for="harga-barang-1" class="form-label">Harga</label>
                                <input type="number" id="harga-barang-1" class="form-control harga-barang" name="harga_barang[]" placeholder="Harga" required>
                            </div>
                            <div class="col-md-2">
                                <label for="subtotal-1" class="form-label">Subtotal</label>
                                <input type="number" id="subtotal-1" class="form-control subtotal" name="subtotal[]" placeholder="Subtotal" readonly>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" class="btn btn-danger btn-sm remove-barang w-100">Hapus</button>
                            </div>

                            <!-- Baris Bawah -->
                            <div class="col-md-3 mt-3">
                                <label for="bulan-ekonomis-1" class="form-label">Bulan Ekonomis</label>
                                <input type="number" id="bulan-ekonomis-1" class="form-control" name="bulan_ekonomis[]" placeholder="Bulan Ekonomis" required>
                            </div>
                            <div class="col-md-3 mt-3">
                                <label for="rekomendasi-penyusutan-1" class="form-label">Rekomendasi Penyusutan</label>
                                <input type="text" id="rekomendasi-penyusutan-1" class="form-control rekomendasi-penyusutan" name="rekomendasi_penyusutan[]" placeholder="Rekomendasi Penyusutan" readonly>
                            </div>
                            <div class="col-md-3 mt-3">
                                <label for="tanggal-akhir-1" class="form-label">Tanggal Akhir Periode</label>
                                <input type="text" id="tanggal-akhir-1" class="form-control tanggal-akhir" name="tanggal_akhir[]" placeholder="Tanggal Akhir Periode" readonly>
                            </div>
                            <div class="col-md-4 mt-3">
                                <label for="nilai-penyusutan-1" class="form-label">Nilai Penyusutan</label>
                                <input type="number" id="nilai-penyusutan-1" class="form-control input-penyusutan" name="nilai_penyusutan[]" placeholder="Nilai Penyusutan" required>
                            </div>
                        </div>
                    </div>
                    <hr>

                    <button type="button" id="tambah-barang" class="btn btn-success btn-sm mb-3">+ Tambah</button>

                    <!-- Total Keseluruhan -->
                    <div class="mb-3">
                        <label for="total_pembelian" class="form-label"><strong>Total Pembelian</strong></label>
                        <input type="number" id="total-pembelian" class="form-control" name="total_pembelian" readonly>
                    </div>

                    <!-- Tombol Simpan -->
                    <div class="mb-3 d-flex justify-content-end">
                        <button name="insert_pembelian" type="submit" class="btn btn-primary">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function hitungSubtotal(row) {
        const jumlah = parseFloat(row.querySelector('.jumlah-barang').value) || 0;
        const harga = parseFloat(row.querySelector('.harga-barang').value) || 0;
        const subtotal = row.querySelector('.subtotal');
        subtotal.value = jumlah * harga;

        hitungTotalKeseluruhan();
        hitungRekomendasiPenyusutan(row);
    }

    function hitungRekomendasiPenyusutan(row) {
        const harga = parseFloat(row.querySelector('.harga-barang').value) || 0;
        const bulan = parseFloat(row.querySelector('input[name="bulan_ekonomis[]"]').value) || 0;
        const rekomendasi = row.querySelector('.rekomendasi-penyusutan');

        if (bulan > 0) {
            rekomendasi.value = (harga / bulan).toFixed(2);
        } else {
            rekomendasi.value = '';
        }
    }

    function hitungTotalKeseluruhan() {
        const subtotals = document.querySelectorAll('.subtotal');
        let total = 0;
        subtotals.forEach(sub => {
            total += parseFloat(sub.value) || 0;
        });
        document.getElementById('total-pembelian').value = total;
    }

    function hitungTanggalAkhirPeriode(row) {
        const tanggalPembelianInput = document.querySelector('.tanggal-pembelian').value;
        const bulanEkonomis = parseFloat(row.querySelector('input[name="bulan_ekonomis[]"]').value) || 0;
        const tanggalAkhirInput = row.querySelector('.tanggal-akhir');

        if (tanggalPembelianInput && bulanEkonomis > 0) {
            const tanggalPembelian = new Date(tanggalPembelianInput);
            tanggalPembelian.setMonth(tanggalPembelian.getMonth() + bulanEkonomis);
            const tanggalAkhir = tanggalPembelian.toISOString().split('T')[0];
            tanggalAkhirInput.value = tanggalAkhir;
        } else {
            tanggalAkhirInput.value = '';
        }
    }

    // Event Listener untuk tanggal pembelian global
    document.querySelectorAll('.tanggal-pembelian').forEach(input => {
        input.addEventListener('change', () => {
            document.querySelectorAll('.barang-item').forEach(row => {
                hitungTanggalAkhirPeriode(row);
            });
        });
    });


    // Tambah Baris Barang
    document.getElementById('tambah-barang').addEventListener('click', function() {
        const formBarang = document.getElementById('form-barang');
        const newBarang = document.createElement('div');
        newBarang.classList.add('row', 'mb-3', 'barang-item');
        newBarang.innerHTML = `
            <hr>
            <div class="col-md-4">
                <label for="nama-barang-1" class="form-label">Nama Barang</label>
                <select class="form-select" name="nama_peralatan[]" required>
                    <option selected disabled>Pilih Nama</option>
                    <?php
                    $queryGetNama = "SELECT * FROM master_peralatan WHERE status = 'Aktif'";
                    $getNama = mysqli_query($koneksi, $queryGetNama);
                    while ($nama = mysqli_fetch_assoc($getNama)) {
                    ?>
                        <option value="<?= $nama['id_peralatan'] ?>"><?= $nama['nama_peralatan'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-md-2">
                <label for="jumlah-barang-1" class="form-label">Jumlah</label>
                <input type="number" class="form-control jumlah-barang" name="jumlah_barang[]" placeholder="Jumlah" required>
            </div>
            <div class="col-md-2">
                <label for="harga-barang-1" class="form-label">Harga</label>
                <input type="number" id="harga-barang-1" class="form-control harga-barang" name="harga_barang[]" placeholder="Harga" required>
            </div>
            <div class="col-md-2">
                <label for="subtotal-1" class="form-label">Subtotal</label>
                <input type="number" id="subtotal-1" class="form-control subtotal" name="subtotal[]" placeholder="Subtotal" readonly>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-danger btn-sm remove-barang w-100">Hapus</button>
            </div>
            
            <div class="col-md-3 mt-3">
                <label for="bulan-ekonomis-1" class="form-label">Bulan Ekonomis</label>
                <input type="number" id="bulan-ekonomis-1" class="form-control" name="bulan_ekonomis[]" placeholder="Bulan Ekonomis" required>
            </div>
            <div class="col-md-3 mt-3">
                <label for="rekomendasi-penyusutan-1" class="form-label">Rekomendasi Penyusutan</label>
                <input type="text" id="rekomendasi-penyusutan-1" class="form-control rekomendasi-penyusutan" name="rekomendasi_penyusutan[]" placeholder="Rekomendasi Penyusutan" readonly>
            </div>
            <div class="col-md-3 mt-3">
                <label for="tanggal-akhir-1" class="form-label">Tanggal Akhir Periode</label>
                <input type="text" id="tanggal-akhir-1" class="form-control tanggal-akhir" name="tanggal_akhir[]" placeholder="Tanggal Akhir Periode" readonly>
            </div>
            <div class="col-md-4 mt-3">
                <label for="nilai-penyusutan-1" class="form-label">Nilai Penyusutan</label>
                <input type="number" id="nilai-penyusutan-1" class="form-control input-penyusutan" name="nilai_penyusutan[]" placeholder="Nilai Penyusutan" required>
            </div>
        `;
        formBarang.appendChild(newBarang);
        attachEventsToRow(newBarang);
    });

    function attachEventsToRow(row) {
        row.querySelector('.jumlah-barang').addEventListener('input', () => hitungSubtotal(row));
        row.querySelector('.harga-barang').addEventListener('input', () => hitungSubtotal(row));
        row.querySelector('input[name="bulan_ekonomis[]"]').addEventListener('input', () => {
            hitungRekomendasiPenyusutan(row);
            hitungTanggalAkhirPeriode(row);
        });
        row.querySelector('.remove-barang').addEventListener('click', () => {
            row.remove();
            hitungTotalKeseluruhan();
        });
    }

    // Attach initial events to rows
    document.querySelectorAll('.barang-item').forEach(attachEventsToRow);
</script>
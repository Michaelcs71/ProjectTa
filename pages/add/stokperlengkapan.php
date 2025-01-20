<div class="modal fade" id="insertModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Form Data Pembelian</h5>
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
                    <div id="form-perlengkapan">
                        <div class="row mb-3 barang-item">
                            <div class="col-md-3">

                                <select class="form-select" name="nama_perlengkapan[]" required>
                                    <option selected disabled>Pilih Nama</option>
                                    <?php
                                    $queryGetNama = "SELECT * FROM master_perlengkapan WHERE status = 'Aktif'";
                                    $getNama = mysqli_query($koneksi, $queryGetNama);
                                    while ($nama = mysqli_fetch_assoc($getNama)) {
                                    ?>
                                        <option value="<?= $nama['id_perlengkapan'] ?>"><?= $nama['nama_perlengkapan'] ?></option>
                                    <?php } ?>
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
                                <button type="button" class="btn btn-danger btn-sm remove-perlengkapan">X</button>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="tambah-perlengkapan" class="btn btn-success btn-sm mb-3">+ Tambah</button>

                    <!-- Total Keseluruhan -->
                    <div class="mb-3">
                        <label for="total_pembelian" class="form-label"><strong>Total Pembelian</strong></label>
                        <input type="number" id="total-pembelian" class="form-control" name="total_pembelian" readonly>
                    </div>

                    <!-- Tombol Simpan -->
                    <div class="mb-3 d-flex justify-content-end">
                        <button name="insert_pembelian_perlengkapan" type="submit" class="btn btn-primary">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function hitungSubtotal(row) {
        const jumlah = row.querySelector('.jumlah-barang').value || 0;
        const harga = row.querySelector('.harga-barang').value || 0;
        const subtotal = row.querySelector('.subtotal');

        subtotal.value = jumlah * harga;
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
    document.getElementById('tambah-perlengkapan').addEventListener('click', function() {
        const formBarang = document.getElementById('form-perlengkapan');
        const newPerlengkapan = document.createElement('div');
        newPerlengkapan.classList.add('row', 'mb-3', 'barang-item');
        newPerlengkapan.innerHTML = `
            <div class="col-md-3">
                <select class="form-select" name="nama_perlengkapan[]" required>
                    <option selected disabled>Pilih Nama</option>
                    <?php
                    $queryGetNama = "SELECT * FROM master_perlengkapan WHERE status = 'Aktif'";
                    $getNama = mysqli_query($koneksi, $queryGetNama);
                    while ($nama = mysqli_fetch_assoc($getNama)) {
                    ?>
                        <option value="<?= $nama['id_perlengkapan'] ?>"><?= $nama['nama_perlengkapan'] ?></option>
                    <?php } ?>
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
                <button type="button" class="btn btn-danger btn-sm remove-perlengkapan">X</button>
            </div>
        `;
        formBarang.appendChild(newPerlengkapan);
        attachEventsToRow(newPerlengkapan);
    });

    function attachEventsToRow(row) {
        row.querySelector('.jumlah-barang').addEventListener('input', () => hitungSubtotal(row));
        row.querySelector('.harga-barang').addEventListener('input', () => hitungSubtotal(row));
        row.querySelector('.remove-perlengkapan').addEventListener('click', () => {
            row.remove();
            hitungTotalKeseluruhan();
        });
    }

    // Attach initial events
    document.querySelectorAll('.barang-item').forEach(attachEventsToRow);
</script>
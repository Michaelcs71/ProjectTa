<div class="modal fade" id="insertModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Form Data Pengeluar Overhead</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="webservice/insert.php" enctype="multipart/form-data">


                    <div class="mb-3">
                        <label for="tanggal_pembelian" class="form-label">Tanggal Pembelian</label>
                        <input type="date" class="form-control" name="tanggal_pembelian" required>
                    </div>
                    <div class="mb-3" hidden>
                        <label for="pengeluaran" class="form-label">Status</label>
                        <select data-trigger class="form-select" name="status" id="status">
                            <option disabled>Pilih Status</option>
                            <option value="Aktif" selected>Aktif</option>
                            <option value="Tidak Aktif">Tidak Aktif</option>
                        </select>
                    </div>

                    <!-- Tambah Barang -->
                    <label class="form-label"><strong>Detail Pembelian</strong></label>
                    <div id="form-overhead">
                        <div class="row mb-3 barang-item">
                            <div class="col-md-3">
                                <select class="form-select" name="nama_overhead[]" required>
                                    <option selected disabled>Pilih Nama</option>
                                    <?php
                                    $queryGetNama = "SELECT * FROM master_overhead WHERE status = 'Aktif' "; // Menambahkan kondisi status = 1
                                    $getNama = mysqli_query($koneksi, $queryGetNama);
                                    while ($nama = mysqli_fetch_assoc($getNama)) {
                                    ?>
                                        <option value="<?= $nama['id_overhead'] ?>"><?= $nama['nama_overhead'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <input type="number" class="form-control harga-barang" name="harga_barang[]" placeholder="Harga" required>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="keterangan[]" placeholder="Keterangan">
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-danger btn-sm remove-overhead">X</button>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="tambah-overhead" class="btn btn-success btn-sm mb-3">+ Tambah</button>

                    <!-- Total Keseluruhan -->
                    <div class="mb-3">
                        <label for="total_pembelian" class="form-label"><strong>Total Pembelian</strong></label>
                        <input type="number" id="total-pembelian" class="form-control" name="total_pembelian" readonly>
                    </div>

                    <!-- Tombol Simpan -->
                    <div class="mb-3 d-flex justify-content-end">
                        <button name="insert_pembelian_overhead" type="submit" class="btn btn-primary">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function hitungTotalKeseluruhan() {
        const hargaBarang = document.querySelectorAll('.harga-barang');
        let total = 0;
        hargaBarang.forEach(harga => {
            total += parseFloat(harga.value) || 0;
        });
        document.getElementById('total-pembelian').value = total;
    }

    // Tambah Baris Barang
    document.getElementById('tambah-overhead').addEventListener('click', function() {
        const formBarang = document.getElementById('form-overhead');
        const newoverhead = document.createElement('div');
        newoverhead.classList.add('row', 'mb-3', 'barang-item');
        newoverhead.innerHTML = `
            <div class="col-md-3">
                <select class="form-select" name="nama_overhead[]" required>
                    <option selected disabled>Pilih Nama</option>
                    <?php
                    $queryGetNama = "SELECT * FROM master_overhead WHERE status = 'Aktif'";
                    $getNama = mysqli_query($koneksi, $queryGetNama);
                    while ($nama = mysqli_fetch_assoc($getNama)) {
                    ?>
                        <option value="<?= $nama['id_overhead'] ?>"><?= $nama['nama_overhead'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-md-3">
                <input type="number" class="form-control harga-barang" name="harga_barang[]" placeholder="Harga" required>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" name="keterangan[]" placeholder="Keterangan">
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger btn-sm remove-overhead">X</button>
            </div>
        `;
        formBarang.appendChild(newoverhead);
        attachEventsToRow(newoverhead);
    });

    function attachEventsToRow(row) {
        row.querySelector('.harga-barang').addEventListener('input', () => hitungTotalKeseluruhan());
        row.querySelector('.remove-overhead').addEventListener('click', () => {
            row.remove();
            hitungTotalKeseluruhan();
        });
    }

    // Attach initial events
    document.querySelectorAll('.barang-item').forEach(attachEventsToRow);
</script>
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
                            <div class="col-md-4">
                                <select class="form-control nama-barang" name="nama_barang[]" required>
                                    <option selected disabled>Pilih Material</option>
                                    <?php
                                    $queryGetNama = "SELECT * FROM master_barang_jadi";
                                    $getNama = mysqli_query($koneksi, $queryGetNama);
                                    while ($nama = mysqli_fetch_assoc($getNama)) {
                                    ?>
                                        <option value="<?= $nama['id_barang_jadi'] ?>"
                                            data-harga="<?= $nama['harga_terendah'] ?>"
                                            data-upah="<?= $nama['persentase_upah'] ?>">
                                            <?= $nama['nama_barang'] ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="number" class="form-control jumlah-barang" name="jumlah_barang[]" placeholder="Jumlah" required>
                            </div>
                            <div class="col-md-3">
                                <input type="number" class="form-control subtotal" name="subtotal[]" placeholder="Subtotal" readonly>
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-danger btn-sm hapus-barang">X</button>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="tambahBarangJadiMasuk" class="btn btn-success btn-sm mb-3">+ Tambah</button>
                    <hr>
                    <div class="mb-3">
                        <label for="total_upah" class="form-label"><strong>Total Upah</strong></label>
                        <input type="number" id="total-pembelian" class="form-control" name="total_upah" readonly>
                    </div>
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
        const modalID = "insertModalStokBarang";
        const formBarangJadiMasuk = document.querySelector(`#${modalID} #formBarangJadiMasuk`);
        const totalPembelianInput = document.querySelector(`#${modalID} #total-pembelian`);

        // Tambah Baris Barang
        document.querySelector(`#${modalID} #tambahBarangJadiMasuk`).addEventListener("click", function() {
            const newBarangJadi = document.createElement("div");
            newBarangJadi.classList.add("row", "mb-3", "barang-item");
            newBarangJadi.innerHTML = `
                <div class="col-md-4">
                    <select class="form-control nama-barang" name="nama_barang[]" required>
                        <option selected disabled>Pilih Material</option>
                        <?php
                        $queryGetNama = "SELECT * FROM master_barang_jadi";
                        $getNama = mysqli_query($koneksi, $queryGetNama);
                        while ($nama = mysqli_fetch_assoc($getNama)) {
                        ?>
                            <option value="<?= $nama['id_barang_jadi'] ?>" 
                                    data-harga="<?= $nama['harga_terendah'] ?>" 
                                    data-upah="<?= $nama['persentase_upah'] ?>">
                                <?= $nama['nama_barang'] ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="number" class="form-control jumlah-barang" name="jumlah_barang[]" placeholder="Jumlah" required>
                </div>
                <div class="col-md-3">
                    <input type="number" class="form-control subtotal" name="subtotal[]" placeholder="Subtotal" readonly>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger btn-sm hapus-barang">X</button>
                </div>
            `;
            formBarangJadiMasuk.appendChild(newBarangJadi);
            attachHandlers(newBarangJadi);
        });

        // Fungsi Menghitung Subtotal dan Total
        function calculateSubtotal(row) {
            const selectBarang = row.querySelector(".nama-barang");
            const jumlahInput = row.querySelector(".jumlah-barang");
            const subtotalInput = row.querySelector(".subtotal");

            const harga = parseFloat(selectBarang.options[selectBarang.selectedIndex].getAttribute("data-harga")) || 0;
            const upah = parseFloat(selectBarang.options[selectBarang.selectedIndex].getAttribute("data-upah")) || 0;
            const jumlah = parseFloat(jumlahInput.value) || 0;

            const subtotal = jumlah * harga * (upah / 100);
            subtotalInput.value = subtotal.toFixed(2);

            calculateTotal();
        }

        function calculateTotal() {
            let total = 0;
            formBarangJadiMasuk.querySelectorAll(".subtotal").forEach(function(input) {
                total += parseFloat(input.value) || 0;
            });
            totalPembelianInput.value = total.toFixed(2);
        }

        // Tambahkan Event Listener
        function attachHandlers(row) {
            const selectBarang = row.querySelector(".nama-barang");
            const jumlahInput = row.querySelector(".jumlah-barang");

            selectBarang.addEventListener("change", function() {
                calculateSubtotal(row);
            });

            jumlahInput.addEventListener("input", function() {
                calculateSubtotal(row);
            });

            const deleteButton = row.querySelector(".hapus-barang");
            deleteButton.addEventListener("click", function() {
                row.remove();
                calculateTotal();
            });
        }

        // Pasang Event Handler ke Baris Awal
        formBarangJadiMasuk.querySelectorAll(".barang-item").forEach(attachHandlers);
    });
</script>
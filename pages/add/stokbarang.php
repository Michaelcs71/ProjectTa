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
                        <select class="form-select" name="nama_pekerja" id="nama-pekerja" required>
                            <option selected disabled>Pilih Nama</option>
                            <?php
                            $queryGetNama = "SELECT * FROM master_pekerja WHERE status = 'Aktif'";
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
                                    $queryGetNama = "SELECT * FROM master_barang_jadi WHERE status = 'Aktif'";
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
                            <div class="col-md-2">
                                <input type="number" class="form-control target-jumlah" name="target_jumlah[]" placeholder="Target" readonly>
                            </div>
                            <div class="col-md-2">
                                <input type="number" class="form-control jumlah-barang" name="jumlah_barang[]" placeholder="Jumlah" required>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control subtotal" name="subtotal_display[]" placeholder="Subtotal" readonly>
                                <input type="hidden" class="subtotal-hidden" name="subtotal[]">
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
                        <input type="text" id="total-pembelian" class="form-control" readonly>
                        <input type="hidden" name="total_upah" id="totalUpahClean">
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
        const totalUpahHiddenInput = document.querySelector(`#${modalID} #totalUpahClean`);

        // Fungsi Format Rupiah
        function formatRupiah(angka) {
            return new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR",
                minimumFractionDigits: 2,
            }).format(angka);
        }

        // Fungsi Menghitung Subtotal dan Total
        function calculateSubtotal(row) {
            const selectBarang = row.querySelector(".nama-barang");
            const jumlahInput = row.querySelector(".jumlah-barang");
            const subtotalInput = row.querySelector(".subtotal");
            const hiddenSubtotalInput = row.querySelector(".subtotal-hidden"); // Input tersembunyi

            // Ambil data harga dan upah dari atribut HTML
            const harga = parseFloat(selectBarang.options[selectBarang.selectedIndex]?.getAttribute("data-harga")) || 0;
            const upah = parseFloat(selectBarang.options[selectBarang.selectedIndex]?.getAttribute("data-upah")) || 0;
            const jumlah = parseFloat(jumlahInput.value) || 0;

            // Hitung subtotal
            const subtotal = parseFloat((jumlah * harga * (upah / 100)).toFixed(2));

            hiddenSubtotalInput.value = subtotal; // Simpan nilai asli ke input hidden
            subtotalInput.value = formatRupiah(subtotal);

            calculateTotal();
        }

        function calculateTotal() {
            let total = 0;
            formBarangJadiMasuk.querySelectorAll(".subtotal-hidden").forEach(function(input) {
                total += parseFloat(input.value) || 0; // Gunakan nilai dari input hidden
            });

            totalPembelianInput.dataset.value = total.toFixed(2);
            totalPembelianInput.value = formatRupiah(total);
            totalUpahHiddenInput.value = total; // Simpan nilai asli ke input hidden
        }

        // Tambahkan Event Listener untuk menampilkan target
        function attachHandlers(row) {
            const selectBarang = row.querySelector(".nama-barang");
            const jumlahInput = row.querySelector(".jumlah-barang");
            const targetInput = row.querySelector(".target-jumlah");

            selectBarang.addEventListener("change", function() {
                const pekerjaId = document.querySelector(`#${modalID} #nama-pekerja`).value;
                const barangId = this.value;

                if (pekerjaId && barangId) {
                    // Fetch Target Jumlah
                    fetch(`webservice/api/targetbarang.php?id_pekerja=${pekerjaId}&id_barang=${barangId}`)
                        .then(response => response.json())
                        .then(data => {
                            targetInput.value = data.target_jumlah || 0;
                        })
                        .catch(() => {
                            targetInput.value = 0;
                        });
                }

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

        formBarangJadiMasuk.querySelectorAll(".barang-item").forEach(attachHandlers);

        document.querySelector(`#${modalID} #tambahBarangJadiMasuk`).addEventListener("click", function() {
            const newBarangJadi = document.createElement("div");
            newBarangJadi.classList.add("row", "mb-3", "barang-item");
            newBarangJadi.innerHTML = `
            <div class="col-md-4">
                <select class="form-control nama-barang" name="nama_barang[]" required>
                    <option selected disabled>Pilih Material</option>
                    <?php
                    $queryGetNama = "SELECT * FROM master_barang_jadi WHERE status = 'Aktif'";
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
            <div class="col-md-2">
                <input type="number" class="form-control target-jumlah" name="target_jumlah[]" placeholder="Target" readonly>
            </div>
            <div class="col-md-2">
                <input type="number" class="form-control jumlah-barang" name="jumlah_barang[]" placeholder="Jumlah" required>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control subtotal" name="subtotal_display[]" placeholder="Subtotal" readonly>
                <input type="hidden" class="subtotal-hidden" name="subtotal[]" />
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger btn-sm hapus-barang">X</button>
            </div>
        `;
            formBarangJadiMasuk.appendChild(newBarangJadi);
            attachHandlers(newBarangJadi);
        });

        document.querySelector(`#${modalID} #nama-pekerja`).addEventListener("change", function() {
            formBarangJadiMasuk.querySelectorAll(".barang-item").forEach(row => {
                row.querySelector(".target-jumlah").value = 0;
                row.querySelector(".subtotal").value = "";
                row.querySelector(".jumlah-barang").value = "";
                row.querySelector(".subtotal-hidden").value = 0;
            });
            totalPembelianInput.value = formatRupiah(0);
            totalUpahHiddenInput.value = 0;
        });
    });
</script>
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
                        <select class="form-select" id="nama-pekerja" name="nama_pekerja" required>
                            <option selected disabled>Pilih Nama</option>
                            <?php
                            $queryGetNama = "SELECT * FROM master_pekerja";
                            $getNama = mysqli_query($koneksi, $queryGetNama);
                            while ($nama = mysqli_fetch_assoc($getNama)) {
                                echo "<option value=\"{$nama['id_pekerja']}\">{$nama['nama_pekerja']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div id="formBarangJadiMasuk">
                        <div class="row mb-3 barang-item">
                            <div class="col-md-4">
                                <select class="form-control nama-barang" name="nama_barang[]" required>
                                    <option selected disabled>Pilih Material</option>
                                    <?php
                                    $queryGetBarang = "SELECT * FROM master_barang_jadi";
                                    $getBarang = mysqli_query($koneksi, $queryGetBarang);
                                    while ($barang = mysqli_fetch_assoc($getBarang)) {
                                        echo "<option value=\"{$barang['id_barang_jadi']}\" data-harga=\"{$barang['harga']}\" data-upah=\"{$barang['upah']}\">{$barang['nama_barang']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="number" class="form-control target-jumlah" name="target_jumlah[]" placeholder="Target" readonly>
                            </div>
                            <div class="col-md-3">
                                <input type="number" class="form-control jumlah-barang" name="jumlah_barang[]" placeholder="Jumlah" required>
                            </div>
                            <div class="col-md-2">
                                <input type="number" class="form-control subtotal" name="subtotal[]" placeholder="Subtotal" readonly>
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-danger btn-sm hapus-barang">X</button>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="tambahBarangJadiMasuk" class="btn btn-success btn-sm mb-3">+ Tambah</button>
                    <div class="mb-3">
                        <label for="total_upah" class="form-label"><strong>Total Upah</strong></label>
                        <input type="number" id="total-pembelian" class="form-control" name="total_upah" readonly>
                    </div>
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
                <div class="col-md-2">
                    <input type="number" class="form-control target-jumlah" name="target_jumlah[]" placeholder="Target" readonly>
                </div>
                <div class="col-md-3">
                    <input type="number" class="form-control jumlah-barang" name="jumlah_barang[]" placeholder="Jumlah" required>
                </div>
                <div class="col-md-2">
                    <input type="number" class="form-control subtotal" name="subtotal[]" placeholder="Subtotal" readonly>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger btn-sm hapus-barang">X</button>
                </div>
            `;
            formBarangJadiMasuk.appendChild(newBarangJadi);
            attachHandlers(newBarangJadi);
        });

        // Fungsi Hitung Subtotal dan Total
        function calculateSubtotal(row) {
            const selectBarang = row.querySelector(".nama-barang");
            const jumlahInput = row.querySelector(".jumlah-barang");
            const subtotalInput = row.querySelector(".subtotal");

            const harga = parseFloat(selectBarang.options[selectBarang.selectedIndex]?.dataset.harga) || 0;
            const upah = parseFloat(selectBarang.options[selectBarang.selectedIndex]?.dataset.upah) || 0;
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

        // Pasang Event Handler untuk Tiap Baris
        function attachHandlers(row) {
            const selectBarang = row.querySelector(".nama-barang");
            const jumlahInput = row.querySelector(".jumlah-barang");
            const targetInput = row.querySelector(".target-jumlah");

            // Saat Barang Diganti
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

                // Hitung Ulang Subtotal
                calculateSubtotal(row);
            });

            // Saat Jumlah Diubah
            jumlahInput.addEventListener("input", function() {
                calculateSubtotal(row);
            });

            // Hapus Baris
            const deleteButton = row.querySelector(".hapus-barang");
            deleteButton.addEventListener("click", function() {
                row.remove();
                calculateTotal();
            });
        }

        // Pasang Handler untuk Baris Awal
        formBarangJadiMasuk.querySelectorAll(".barang-item").forEach(attachHandlers);

        // Reset Semua Data Saat Pekerja Diubah
        document.querySelector(`#${modalID} #nama-pekerja`).addEventListener("change", function() {
            formBarangJadiMasuk.querySelectorAll(".barang-item").forEach(row => {
                row.querySelector(".target-jumlah").value = 0;
                row.querySelector(".subtotal").value = "";
                row.querySelector(".jumlah-barang").value = "";
            });
            totalPembelianInput.value = 0;
        });
    });
</script>
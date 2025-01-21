<div class="modal fade" id="updateModalMaterialKeluar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Update Status Stok Material Keluar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="webservice/update.php" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="id_penggunaan_material" class="form-label">ID Penggunaan Material</label>
                        <select class="form-select" name="id_penggunaan_material" id="id_penggunaan_material" onchange="fetchMaterialDataKeluar()">
                            <option value="" disabled selected>Pilih ID</option>
                            <!-- ID akan diisi melalui JavaScript -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="pekerja" class="form-label">Pekerja</label>
                        <input type="text" id="pekerja" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="barang" class="form-label">Barang</label>
                        <input type="text" id="barang" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_ambil" class="form-label">Tanggal Pengambilan</label>
                        <input type="text" id="tanggal_ambil" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_selesai" class="form-label">Estimasi Tanggal Selesai</label>
                        <input type="text" id="tanggal_selesai" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" name="status" id="idstatus">
                            <option value="" disabled selected>Pilih Status</option>
                            <option value="Aktif">Aktif</option>
                            <option value="Tidak Aktif">Tidak Aktif</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <button name="update_status_material_keluar" type="submit" class="btn btn-primary">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        fetch("webservice/api/stokmaterialkeluar.php")
            .then((response) => response.json())
            .then((data) => {
                const select = document.getElementById("id_penggunaan_material");
                data.forEach((item) => {
                    const option = document.createElement("option");
                    option.textContent = item.id_penggunaan_material;
                    select.appendChild(option);

                });
            })
            .catch((error) => console.error("Error fetching IDs:", error));
    });

    function fetchMaterialDataKeluar() {
        const id = document.getElementById("id_penggunaan_material").value;

        if (!id) {
            console.error("ID tidak valid");
            return;
        }

        fetch(`webservice/api/stokmaterialkeluar.php?id=${id}`)
            .then((response) => response.json())
            .then((data) => {
                if (data.error) {
                    console.error(data.error);
                    return;
                }

                // Isi input lainnya
                document.getElementById("pekerja").value = data.id_pekerja || "";
                document.getElementById("barang").value = data.id_barang_jadi || "";
                document.getElementById("tanggal_ambil").value = data.tanggal_pengambilan || "";
                document.getElementById("tanggal_selesai").value = data.estimasi_tanggal_selesai || "";

                // Atur dropdown status
                const statusDropdown = document.getElementById("idstatus");

                // Reset semua opsi
                Array.from(statusDropdown.options).forEach((option) => {
                    option.selected = false;
                });

                // Cari opsi yang cocok dengan nilai dari API
                const statusValue = data.status || ""; // Nilai `status` dari API
                let isMatched = false;

                Array.from(statusDropdown.options).forEach((option) => {
                    if (option.value === statusValue) {
                        option.selected = true; // Pilih opsi yang cocok
                        isMatched = true;
                    }
                });

                // Debugging: Jika tidak ada opsi yang cocok, log ke konsol
                if (!isMatched) {
                    console.warn("Status dari API tidak cocok dengan opsi dropdown:", statusValue);
                }
            })
            .catch((error) => console.error("Error fetching material data:", error));
    }
</script>
<div class="modal fade" id="updateModalMaterialMasuk" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Update Status Stok Material Masuk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="webservice/update.php" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="id_pembelian_material" class="form-label">ID Pembelian Material</label>
                        <select class="form-select" name="id_pembelian_material" id="id_pembelian_material" onchange="fetchMaterialData()">
                            <option value="" disabled selected>Pilih ID</option>
                            <!-- ID akan diisi melalui JavaScript -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="supplier" class="form-label">Supplier</label>
                        <input type="text" id="supplier" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal" class="form-label">Tanggal</label>
                        <input type="text" id="tanggal" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="total_biaya" class="form-label">Total Biaya</label>
                        <input type="text" id="total_biaya" class="form-control" readonly>
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
                        <button name="update_status_material_masuk" type="submit" class="btn btn-primary">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        fetch("webservice/api/stokmaterialmasuk.php")
            .then((response) => response.json())
            .then((data) => {
                const select = document.getElementById("id_pembelian_material");
                data.forEach((item) => {
                    const option = document.createElement("option");
                    option.textContent = item.id_pembelian_material;
                    select.appendChild(option);
                });
            })
            .catch((error) => console.error("Error fetching IDs:", error));
    });

    function fetchMaterialData() {
        const id = document.getElementById("id_pembelian_material").value;

        if (!id) {
            console.error("ID tidak valid");
            return;
        }

        fetch(`webservice/api/stokmaterialmasuk.php?id=${id}`)
            .then((response) => response.json())
            .then((data) => {
                if (data.error) {
                    console.error(data.error);
                    return;
                }

                // Isi input lainnya
                document.getElementById("supplier").value = data.id_supplier || "";
                document.getElementById("tanggal").value = data.tanggal || "";
                document.getElementById("total_biaya").value = data.total_biaya || "";

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
<div class="modal fade" id="insertModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Form Data Akun</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="webservice/insert.php" enctype="multipart/form-data">
                    <div class="modal-body">
                        <form>
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nomor Akun</label>
                                <input type="number" class="form-control" name="id_akun" id="no_akun" required>
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Nama Akun</label>
                                <input type="text" class="form-control" name="nama_akun" id="" required>
                            </div>
                            <div class="mb-3" hidden>
                                <label for="" class="form-label">Status</label>
                                <select data-trigger class="form-select" name="status" id="status">
                                    <option disabled>Pilih Status</option>
                                    <option value="Aktif" selected>Aktif</option>
                                    <option value="Tidak Aktif">Tidak Aktif</option>
                                </select>
                            </div>
                            <br>
                            <div class="mb-3 d-flex flex-column">
                                <button name="insert_akun" type="submit" class="btn btn-primary">Simpan Data</button>
                            </div>
                        </form>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
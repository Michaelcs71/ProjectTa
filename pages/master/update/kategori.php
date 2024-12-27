<div class="modal fade" id="updateModalkategori" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Update Status Pekerja</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="webservice/update.php" enctype="multipart/form-data">
                    <div class="modal-body">
                        <form method="POST" action="webservice/update.php" enctype="multipart/form-data">
                            <input name="id_kategori" type="hidden" class="form-control" id="id_bhn_splr">
                            <div class="mb-3" hidden>
                                <label for="nm_splr" class="form-label">Nama kategori</label>
                                <input type="text" class="form-control" name="nama_kategori" id="nm_splr" required>
                            </div>
                            <div class="mb-3" hidden>
                                <label for="eemaaill" class="form-label">Deskripsi</label>
                                <input type="deskripsi" class="form-control" name="deskripsi" id="eemaaill" required>
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" name="status" id="status">
                                    <option disabled>Pilih Status</option>
                                    <option value="Aktif">Aktif</option>
                                    <option value="Tidak Aktif">Tidak Aktif</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <button name="update_kategori" type="submit" class="btn btn-primary">Simpan Data</button>
                            </div>
                        </form>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
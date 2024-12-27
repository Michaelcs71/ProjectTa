<div class="modal fade" id="updateModalperlengkapan" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Update Status Perlengkapan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="webservice/update.php" enctype="multipart/form-data">
                    <input type="hidden" name="id_perlengkapan" id="id_perlengkapan">
                    <div class="mb-3" hidden>
                        <label for="nama_perlengkapan" class="form-label">Nama perlengkapan</label>
                        <input type="text" class="form-control" name="nama_perlengkapan" id="nm_prkja">
                    </div>
                    <div class="mb-3">
                        <label for="nama_satuan" class="form-label">Nama Satuan</label>
                        <input type="text" class="form-control" name="nama_satuan" id="nm_satuan">
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" name="status" id="status">
                            <option selected disabled>Pilih Status</option>
                            <option value="Aktif">Aktif</option>
                            <option value="Tidak Aktif">Tidak Aktif</option>
                        </select>
                    </div>
                    <button name="update_perlengkapan" type="submit" class="btn btn-primary">Simpan Data</button>
                </form>
            </div>
        </div>
    </div>
</div>
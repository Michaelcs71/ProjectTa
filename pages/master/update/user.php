<div class="modal fade" id="updateModalUser" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Update Status Pekerja</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="webservice/update.php" enctype="multipart/form-data">
                    <div class="modal-body">
                        <form>
                            <div class="md-3">
                                <input name="id_user" type="hidden" class="form-control" id="iduserr">
                            </div>

                            <div class="mb-3">
                                <label for="pengeluaran" class="form-label">Status</label>
                                <select data-trigger class="form-select" name="status" id="status">
                                    <option selected disabled>Pilih Status</option>
                                    <option value="Aktif">Aktif</option>
                                    <option value="Tidak Aktif">Tidak Aktif</option>
                                </select>
                            </div>
                            <div class="mb-3 d-flex flex-column">
                                <button name="update_user" type="submit" class="btn btn-primary">Simpan Data</button>
                            </div>
                        </form>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
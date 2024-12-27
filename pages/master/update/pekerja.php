<div class="modal fade" id="updateModalPekerja" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                <input name="id_pekerja" type="hidden" class="form-control" id="id_pekerja">
                            </div>
                            <div class="mb-3" hidden>
                                <label for="nama_pekerja" class="form-label">Nama Pekerja</label>
                                <input type="text" class="form-control" name="nama_pekerja" id="nm_prkja">
                            </div>
                            <div class="mb-3" hidden>
                                <label for="notelpn" class="form-label">No Telpon</label>
                                <input type="text" class="form-control" name="no_telpon" id="notel">
                            </div>
                            <div class="mb-3" hidden>
                                <label for="alamat_pekerja" class="form-label">Alamat</label>
                                <input type="text" class="form-control" name="alamat" id="almtpeker" placeholder="Kota/Kab, Rt../Rw..">
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
                                <button name="update_statuspekerja" type="submit" class="btn btn-primary">Simpan Data</button>
                            </div>
                        </form>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
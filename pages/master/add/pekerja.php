<div class="modal fade" id="insertModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Form Data pekerja</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="webservice/insert.php" enctype="multipart/form-data">
                    <div class="modal-body">
                        <form>
                            <div class="mb-3">
                                <label for="nama_pekerja" class="form-label">Nama Pekerja</label>
                                <input type="text" class="form-control" name="nama_pekerja" id="nama_pekerja" required>
                            </div>
                            <div class="mb-3">
                                <label for="notelpn" class="form-label">No Telpon</label>
                                <input type="number" class="form-control" name="notelp" id="notelpn" required>
                            </div>
                            <div class="mb-3">
                                <label for="alamat_pekerja" class="form-label">Alamat</label>
                                <input type="text" class="form-control" name="alamat" id="alamat_pekerja" placeholder="Kota/Kab, Rt../Rw.." required>
                            </div>
                            <div class="mb-3">
                                <label for="pengeluaran" class="form-label">Status</label>
                                <select data-trigger class="form-select" name="status" id="status">
                                    <option selected disabled>Pilih Status</option>
                                    <option value="Aktif">Aktif</option>
                                    <option value="Tidak Aktif">Tidak Aktif</option>
                                </select>
                            </div>
                            <br>
                            <div class="mb-3 d-flex flex-column">
                                <button name="insert_pekerja" type="submit" class="btn btn-primary">Simpan Data</button>
                            </div>
                        </form>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/webservice/config.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/lib/function.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/pages/master/add/pekerja.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/pages/master/update/pekerja.php";
// Debugging to ensure file includes are correct
if (function_exists('Tampil_Data')) {
    echo "Function Tampil_Data exists.";
} else {
    echo "Function Tampil_Data does not exist.";
}

$data = Tampil_Data('pesananbarang');

// Debugging to ensure data fetch is correct
if ($data === null) {
    echo "Data is null.";
} else {
    echo "Data fetched successfully.";
}
?>

<div class="main-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title font-size-18">Data Tenaga Pekerja</h4>
                    </div>
                    <div class="card-body">
                        <button type="button" class="btn btn-primary mb-sm-2" data-bs-toggle="modal"
                            data-bs-target="#insertModal">Tambah Data</button>

                        <table id="datatable"
                            class="table table-bordered dt-responsive nowrap w-100 table-striped table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Nomor</th>
                                    <th>Nama Pekerja</th>
                                    <th>No Telepon</th>
                                    <th>Alamat</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $data = Tampil_Data("pekerja");
                                $no = 1;
                                if ($data !== null) {
                                    foreach ($data as $j) {
                                        $idpekerja = $j->id_pekerja;
                                        $namapekerja = $j->nama_pekerja;
                                        $notelp = $j->no_telpon;
                                        $alamat = $j->alamat;
                                        $status = $j->status;
                                ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $namapekerja ?></td>
                                            <td><?= $notelp ?></td>
                                            <td><?= $alamat ?></td>
                                            <td><?= $status ?></td>
                                            <td>
                                                <button type="button" class="btn btn-primary" id="updateModal"
                                                    data-bs-toggle="modal" data-bs-target="#updateModalPekerja"
                                                    data-idpkrja="<?= $idpekerja ?>" data-nmpkrja="<?= $namapekerja ?>"
                                                    data-notel="<?= $notelp ?>" data-almt="<?= $alamat ?>"
                                                    data-stts="<?= $status ?>">Update</button>

                                            </td>
                                        </tr>
                                <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- end card -->
            </div> <!-- end col -->
        </div> <!-- end row -->
    </div> <!-- container-fluid -->

</div>


<script>
    $(document).ready(function() {
        $(document).on('click', '#updateModal', function() {
            var varidpekerja = $(this).data('idpkrja');
            var varnamaperkerja = $(this).data('nmpkrja');
            var varnotelpon = $(this).data('notel');
            var varalamat = $(this).data('almt');
            var varstatus = $(this).data('stts');

            $('#id_pekerja').val(varidpekerja);
            $('#nm_prkja').val(varnamaperkerja);
            $('#notel').val(varnotelpon);
            $('#almtpeker').val(varalamat);
            $('#status').val(varstatus);




        });

    });
</script>
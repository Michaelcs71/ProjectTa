<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/webservice/config.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/lib/function.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/pages/master/add/perlengkapan.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/pages/master/update/perlengkapan.php";
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
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Data perlengkapan</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Data perlengkapan</h4>
                        </div>
                        <div class="card-body">
                            <button type="button" class="btn btn-primary mb-sm-2" data-bs-toggle="modal"
                                data-bs-target="#insertModal">Tambah Data</button>

                            <table id="datatable-buttons"
                                class="table table-bordered dt-responsive nowrap w-100 table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nomor</th>
                                        <th>Nama perlengkapan</th>
                                        <th>Satuan</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $data = Tampil_Data("perlengkapan");
                                    $no = 1;
                                    if ($data !== null) {
                                        foreach ($data as $j) {
                                            $idperlengkapan = $j->id_perlengkapan;
                                            $namaperlengkapan = $j->nama_perlengkapan;
                                            $namasatuan = $j->nama_satuan;
                                            $status = $j->status;
                                    ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= $namaperlengkapan ?></td>
                                                <td><?= $namasatuan ?></td>
                                                <td><?= $status ?></td>
                                                <td>
                                                    <button type="button" class="btn btn-primary" id="updateModal"
                                                        data-bs-toggle="modal" data-bs-target="#updateModalperlengkapan"
                                                        data-idpkrja="<?= $idperlengkapan ?>" data-nmpkrja="<?= $namaperlengkapan ?>"
                                                        data-nmstn="<?= $namasatuan ?>" data-stts="<?= $status ?>">Update</button>

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
</div>


<script>
    $(document).ready(function() {
        $(document).on('click', '#updateModal', function() {
            var varidperlengkapan = $(this).data('idpkrja');
            var varnamaperkerja = $(this).data('nmpkrja');
            var varnamasatuan = $(this).data('nmstn');
            var varstatus = $(this).data('stts');

            $('#id_perlengkapan').val(varidperlengkapan); // Update sesuai ID
            $('#nm_prkja').val(varnamaperkerja);
            $('#nm_satuan').val(varnamasatuan);
            $('#status').val(varstatus);
        });
    });
</script>
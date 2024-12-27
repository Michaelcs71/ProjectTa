<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/webservice/config.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/lib/function.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/pages/master/add/supplier.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/pages/master/update/supplier.php";
// Debugging to ensure file includes are correct
if (function_exists('Tampil_Data')) {
    echo "Function Tampil_Data exists.";
} else {
    echo "Function Tampil_Data does not exist.";
}



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
                        <h4 class="mb-sm-0 font-size-18">Data Supplier</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Data Supplier</h4>
                        </div>
                        <div class="card-body">
                            <button type="button" class="btn btn-primary mb-sm-2" data-bs-toggle="modal"
                                data-bs-target="#insertModal">Tambah Data</button>

                            <table id="datatable-buttons"
                                class="table table-bordered dt-responsive nowrap w-100 table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nomor</th>
                                        <th>Nama Supplier</th>
                                        <th>No Telepon</th>
                                        <th>Alamat</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $data = Tampil_Data("supplier");
                                    $no = 1;
                                    if ($data !== null) {
                                        foreach ($data as $j) {
                                            $idsupplier = $j->id_supplier;
                                            $namasupplier = $j->nama_supplier;
                                            $notelpon = $j->no_telepon;
                                            $alamat = $j->alamat;
                                            $email = $j->email;
                                            $status = $j->status;
                                    ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= $namasupplier ?></td>
                                                <td><?= $notelpon ?></td>
                                                <td><?= $alamat ?></td>
                                                <td><?= $email ?></td>
                                                <td><?= $status ?></td>
                                                <td>
                                                    <button type="button" class="btn btn-primary" id="updateModal"
                                                        data-bs-toggle="modal" data-bs-target="#updateModalsupplier"
                                                        data-idpkrja="<?= $idsupplier ?>" data-nmsuplier="<?= $namasupplier ?>" data-notelepon="<?= $notelpon ?>"
                                                        data-almt="<?= $alamat ?>" data-email="<?= $email ?>"
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
</div>


<script>
    $(document).ready(function() {
        $(document).on('click', '#updateModal', function() {
            var varidsupplier = $(this).data('idpkrja');
            var varnamasupplier = $(this).data('nmsuplier');
            var vartelepon = $(this).data('notelepon');
            var varalamat = $(this).data('almt');
            var varemail = $(this).data('email');
            var varstatus = $(this).data('stts');

            $('#id_bhn_splr').val(varidsupplier);
            $('#nm_splr').val(varnamasupplier);
            $('#notelpn').val(vartelepon);
            $('#almttt').val(varalamat);
            $('#eemaaill').val(varemail);
            $('#status').val(varstatus);



        });

    });
</script>
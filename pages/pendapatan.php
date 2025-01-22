<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/webservice/config.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/lib/function.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/pages/add/pendapatan.php";

// Fetch data from database
$data = Tampil_Data("pendapatan");

// Get selected month and year from request
$selectedMonth = isset($_POST['month']) ? $_POST['month'] : '';
$selectedYear = isset($_POST['year']) ? $_POST['year'] : '';

// Ensure $data is an array
if (!is_array($data)) {
    $data = []; // Default to empty array if $data is null or not an array
}

// Filter data if month and year are selected
if ($selectedMonth && $selectedYear) {
    $filteredData = array_filter($data, function ($item) use ($selectedMonth, $selectedYear) {
        $date = DateTime::createFromFormat('Y-m-d', $item->tanggal_pendapatan);
        return $date && $date->format('m') === $selectedMonth && $date->format('Y') === $selectedYear;
    });
} else {
    $filteredData = $data; // Show all data if no filter is applied
}

// Debugging to ensure data fetch is correct
if (empty($data)) {
    echo "Data is empty or could not be fetched.";
} else {
    echo "Data fetched successfully.";
}


?>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <!-- Start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Data Pendapatan</h4>
                    </div>
                </div>
            </div>
            <!-- End page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">

                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <form method="POST">
                                        <div class="input-group">
                                            <select name="month" class="form-select">
                                                <option value="">Pilih Bulan</option>
                                                <?php for ($m = 1; $m <= 12; $m++) { ?>
                                                    <option value="<?= str_pad($m, 2, '0', STR_PAD_LEFT) ?>" <?= $selectedMonth == str_pad($m, 2, '0', STR_PAD_LEFT) ? 'selected' : '' ?>><?= date("F", mktime(0, 0, 0, $m, 10)) ?></option>
                                                <?php } ?>
                                            </select>
                                            <select name="year" class="form-select">
                                                <option value="">Pilih Tahun</option>
                                                <?php for ($y = date("Y") - 10; $y <= date("Y"); $y++) { ?>
                                                    <option value="<?= $y ?>" <?= $selectedYear == $y ? 'selected' : '' ?>><?= $y ?></option>
                                                <?php } ?>
                                            </select>
                                            <button class="btn btn-primary" type="submit">Filter</button>
                                            <a href="" class="btn btn-secondary">Reset</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <?php if ($_SESSION['level'] === "super admin") { ?>
                                <button type="button" class="btn btn-primary mb-sm-2" data-bs-toggle="modal" data-bs-target="#insertModal">Tambah Data</button>
                            <?php } ?>

                            <table id="datatable"
                                class="table table-bordered dt-responsive nowrap w-100 table-striped table-hover text-center">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nomor</th>
                                        <th>Nama Platform</th>
                                        <th>Bulan Pendapatan</th>
                                        <th>Total Pendapatan</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    if (!empty($filteredData)) {
                                        foreach ($filteredData as $j) {
                                            $idpendapatan = $j->id_pendapatan;
                                            $namaplatform = $j->nama_platform;
                                            $tanggal = $j->tanggal_pendapatan;
                                            $totalpendapatan = $j->total_pendapatan;
                                            $status = $j->status;
                                    ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= $namaplatform ?></td>
                                                <td><?= $tanggal ?></td>
                                                <td class="text-end">Rp. <?= number_format($totalpendapatan, 2, ',', '.') ?></td>
                                                <td><?= $status ?></td>
                                                <td>
                                                    <button type="button" class="btn btn-primary" id="detailModal"
                                                        data-bs-toggle="modal" data-bs-target="#detailModalPendapatan"
                                                        data-idpkrja="<?= $idpendapatan ?>">Detail</button>

                                                    <?php if ($_SESSION['level'] === "super admin") { ?>
                                                        <button type="button" class="btn btn-primary" id="updateModal"
                                                            data-bs-toggle="modal" data-bs-target="#updateModalPendapatan"
                                                            data-idpkrja="<?= $idpendapatan ?>" data-stts="<?= $status ?>">Update</button>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="6" class="text-center">Tidak ada data.</td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- End card -->
                </div> <!-- End col -->
            </div> <!-- End row -->
        </div> <!-- Container-fluid -->
    </div>
</div>

<!-- Modal Status -->
<div class="modal fade" id="updateModalPendapatan" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Update Status Akun</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="webservice/update.php" enctype="multipart/form-data">
                    <div class="modal-body">
                        <form method="POST" action="webservice/update.php" enctype="multipart/form-data">
                            <input name="id_pendapatan" type="hidden" class="form-control" id="id_pdpn">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" name="status" id="idstatus">
                                    <option disabled>Pilih Status</option>
                                    <option value="Aktif">Aktif</option>
                                    <option value="Tidak Aktif">Tidak Aktif</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <button name="update_status_pendapatan" type="submit" class="btn btn-primary">Simpan Data</button>
                            </div>
                        </form>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Detail Modal Pendapatan -->
<div class="modal fade" id="detailModalPendapatan" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Data Pembelian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nomor</th>
                            <th>Nama</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody id="detail_data_pendapatan">
                        <!-- Data akan diisi oleh AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $(document).on('click', '#updateModal', function() {
            var varidpendapatan = $(this).data('idpkrja');
            var varstatus = $(this).data('stts');

            $('#id_pdpn').val(varidpendapatan);
            $('#idstatus').val(varstatus);

        });

        $(document).on('click', '#detailModal', function() {
            var varidpendapatan = $(this).data('idpkrja'); // Ambil data ID


            // Mengambil detail transaksi berdasarkan ID
            $.ajax({
                url: 'webservice/api/detailpendapatan.php',
                type: 'GET',
                data: {
                    id: varidpendapatan // Kirim ID ke API
                },
                success: function(response) {
                    console.log(response); // Debug respon dari API
                    var data = JSON.parse(response);
                    var rows = '';
                    data.forEach(function(item, index) {
                        rows += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${item.nama_barang}</td>
                        <td>${item.total_barang}</td>
                    </tr>
                `;
                    });
                    $('#detail_data_pendapatan').html(rows);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                }
            });
        });


    });
</script>
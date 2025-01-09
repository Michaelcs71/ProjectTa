<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/webservice/config.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/lib/function.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/pages/add/stokmaterialmasuk.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/pages/add/stokmaterialkeluar.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/pages/update/stokmaterial.php";

if (function_exists('Tampil_Data')) {
    echo "Function Tampil_Data exists.";
} else {
    echo "Function Tampil_Data does not exist.";
}

// Fetch data
$data = Tampil_Data("labarugi");

// Debugging to ensure data fetch is correct
if ($data === null) {
    echo "Data is null.";
} else {
    echo "Data fetched successfully.";
}

// Initialize variables for calculating total
$totalPendapatan = 0;
$totalBiaya = 0;

// Calculate total pendapatan and total biaya
if ($data !== null) {
    foreach ($data as $j) {
        if (strtolower($j->keterangan) === 'total pendapatan') {
            $totalPendapatan += $j->total;
        } else {
            $totalBiaya += $j->total;
        }
    }
}

// Calculate laba/rugi
$totalLabaRugi = $totalPendapatan - $totalBiaya;

// Determine status (Laba/Rugi)
$statusLabaRugi = $totalLabaRugi >= 0 ? "Laba" : "Rugi";
?>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Data stokmaterial</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Data Stok Bahan Material</h4>
                        </div>
                        <div class="card-body">
                            <table id="datatable-buttons"
                                class="table table-bordered dt-responsive nowrap w-100 table-striped table-hover">
                                <thead class="table-light">
                                    <tr class="text-center">
                                        <th>Nomor</th>
                                        <th>Keterangan</th>
                                        <th>Total </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    if ($data !== null) {
                                        foreach ($data as $j) {
                                    ?>
                                            <tr>
                                                <td class="text-center"><?= $no++ ?></td>
                                                <td><?= $j->keterangan ?></td>
                                                <td class="text-end"><?= number_format($j->total, 2, ',', '.') ?></td>
                                            </tr>
                                    <?php
                                        }
                                    }
                                    ?>
                                    <!-- Add Total Laba/Rugi row -->
                                    <tr>
                                        <td colspan="2" class="text-center"><strong>Total <?= $statusLabaRugi ?></strong></td>
                                        <td class="text-end"><strong><?= number_format($totalLabaRugi, 2, ',', '.') ?></strong></td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end row -->
        </div> <!-- container-fluid -->
    </div>
</div>
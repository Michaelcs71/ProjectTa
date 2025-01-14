<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/webservice/config.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/lib/function.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/pages/cetaklabarugi.php";


// Fetch data
$data = Tampil_Data("labarugi");

// Ambil bulan dan tahun dari form
$selectedMonth = isset($_POST['month']) ? $_POST['month'] : '';
$selectedYear = isset($_POST['year']) ? $_POST['year'] : '';

// Filter data berdasarkan bulan dan tahun
if ($selectedMonth && $selectedYear) {
    $filteredData = array_filter($data, function ($item) use ($selectedMonth, $selectedYear) {
        // Pastikan periode dalam format 'Y-m'
        $periode = DateTime::createFromFormat('Y-m', $item->periode);
        if ($periode) { // Validasi format tanggal
            return $periode->format('m') === $selectedMonth && $periode->format('Y') === $selectedYear;
        }
        return false; // Jika format salah, abaikan
    });
} else {
    $filteredData = []; // Jangan tampilkan data sebelum filter dipilih
}

// Initialize variables for calculating total
$totalPendapatan = 0;
$totalBiaya = 0;

// Calculate total pendapatan and total biaya
if ($filteredData) {
    foreach ($filteredData as $j) {
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

            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Laporan Rugi Laba</h4>
                        </div>

                        <div class="card-body">
                            <form method="POST">
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <select name="month" class="form-select">
                                            <option value="">Pilih Bulan</option>
                                            <?php for ($m = 1; $m <= 12; $m++) { ?>
                                                <option value="<?= str_pad($m, 2, '0', STR_PAD_LEFT) ?>" <?= $selectedMonth == str_pad($m, 2, '0', STR_PAD_LEFT) ? 'selected' : '' ?>>
                                                    <?= date("F", mktime(0, 0, 0, $m, 10)) ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <select name="year" class="form-select">
                                            <option value="">Pilih Tahun</option>
                                            <?php for ($y = date("Y") - 10; $y <= date("Y"); $y++) { ?>
                                                <option value="<?= $y ?>" <?= $selectedYear == $y ? 'selected' : '' ?>><?= $y ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <button class="btn btn-primary" type="submit">Filter</button>
                                        <a href="" class="btn btn-secondary">Reset</a>
                                    </div>
                                </div>
                            </form>

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
                                    if (!empty($filteredData)) {
                                        foreach ($filteredData as $j) {
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
                                    <?php if (!empty($filteredData)) { ?>
                                        <tr>
                                            <td colspan="2" class="text-center"><strong>Total <?= $statusLabaRugi ?></strong></td>
                                            <td class="text-end"><strong><?= number_format($totalLabaRugi, 2, ',', '.') ?></strong></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#cetakModalLabaRugi">Cetak / Download PDF</button>

                        </div>
                    </div>
                </div>
            </div>
            <!-- end row -->
        </div> <!-- container-fluid -->
    </div>
</div>

<script>
    function printReport() {
        const printContents = document.getElementById('printArea').innerHTML;
        const originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>
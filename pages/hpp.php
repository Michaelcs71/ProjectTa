<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/webservice/config.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/lib/function.php";

// Get selected month and year from request
$selectedMonth = isset($_POST['month']) ? $_POST['month'] : '';
$selectedYear = isset($_POST['year']) ? $_POST['year'] : '';

// Fetch data using the general function
$data = Tampil_Data("hpp");

// Filter data based on selected month and year if filter is applied
if ($selectedMonth && $selectedYear) {
    $filteredData = array_filter($data, function ($item) use ($selectedMonth, $selectedYear) {
        $date = DateTime::createFromFormat('Y-m', $item->periode); // Pastikan format tanggal
        if ($date) { // Validasi parsing tanggal berhasil
            return $date->format('m') === $selectedMonth && $date->format('Y') === $selectedYear;
        }
        return false; // Abaikan data dengan format tanggal salah
    });
} else {
    $filteredData = $data; // Show all data if no filter is applied
}
?>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Laporan Harga Pokok Produks</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Laporan Harga Pokok Produks</h4>
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

                            <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100 table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nomor</th>
                                        <th>Tanggal</th>
                                        <th>Biaya Bahan Baku</th>
                                        <th>Biaya Overhead</th>
                                        <th>Biaya Tenaga Kerja</th>
                                        <th>Total Barang Jadi</th>
                                        <th>Total HPP</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    if (!empty($filteredData)) {
                                        foreach ($filteredData as $j) {
                                            $tanggal = $j->periode;
                                            $totalBahanBaku = $j->total_bahan_baku;
                                            $totalOverhead = $j->total_overhead;
                                            $totalTenagaKerja = $j->total_tenaga_kerja;
                                            $totalBarangJadi = $j->total_barang_jadi;
                                            $totalHpp = $j->total_hpp;
                                    ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= $tanggal ?></td>
                                                <td><?= $totalBahanBaku ?></td>
                                                <td><?= $totalOverhead ?></td>
                                                <td><?= $totalTenagaKerja ?></td>
                                                <td><?= $totalBarangJadi ?></td>
                                                <td><?= $totalHpp ?></td>

                                            </tr>
                                    <?php
                                        }
                                    } else {
                                        echo "<tr><td colspan='7' class='text-center'>Tidak ada data untuk bulan dan tahun yang dipilih.</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
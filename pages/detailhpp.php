<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/webservice/config.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/lib/function.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/pages/cetakdetailhpp.php";


// Get selected month and year from request
$selectedMonth = isset($_POST['month']) ? $_POST['month'] : '';
$selectedYear = isset($_POST['year']) ? $_POST['year'] : '';

// Fetch data using the general function
$data = Tampil_Data("detailhpp");

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

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Laporan Harga Pokok Produksi Per Produk</h4>
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


                            <table id="table" class="table table-bordered nowrap w-100 table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nomor</th>
                                        <th>Tanggal</th>
                                        <th>Nama Barang</th>
                                        <th>Total Biaya Bahan Baku</th>
                                        <th>Total Biaya Overhead</th>
                                        <th>Total Biaya Tenaga Kerja</th>
                                        <th>Total Barang Jadi</th>
                                        <th>Total HPP Per Unit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    if (!empty($filteredData)) {
                                        foreach ($filteredData as $j) {
                                            $tanggal = $j->periode;
                                            $namabarang = $j->nama_barang;
                                            $totalBahanBaku = $j->total_bahan_baku;
                                            $totalOverhead = $j->total_overhead;
                                            $totalTenagaKerja = $j->total_upah;
                                            $totalBarangJadi = $j->jumlah_produksi;
                                            $totalHpp = $j->hpp_per_unit;
                                    ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= $tanggal ?></td>
                                                <td><?= $namabarang ?></td>
                                                <td class="text-end">Rp. <?= number_format(!empty($j->total_bahan_baku) ? $j->total_bahan_baku : 0, 2, ',', '.') ?></td>
                                                <td class="text-end">Rp. <?= number_format(!empty($j->total_overhead) ? $j->total_overhead : 0, 2, ',', '.') ?></td>
                                                <td class="text-end">Rp. <?= number_format(!empty($j->total_upah) ? $j->total_upah : 0, 2, ',', '.') ?></td>
                                                <td><?= $totalBarangJadi ?></td>
                                                <td class="text-end">Rp. <?= number_format(!empty($j->hpp_per_unit) ? $j->hpp_per_unit : 0, 2, ',', '.') ?></td>


                                            </tr>
                                    <?php
                                        }
                                    } else {
                                        echo "<tr><td colspan='8' class='text-center'>Tidak ada data untuk bulan dan tahun yang dipilih.</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <!-- <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#cetakModal">Cetak / Download PDF</button> -->

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function printReport() {
        var printContents = document.getElementById("printArea").innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;

        // Pastikan halaman direfresh setelah cetak untuk menghindari masalah rendering
        window.location.reload();
    }
</script>
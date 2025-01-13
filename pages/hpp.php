<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/webservice/config.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/lib/function.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/pages/cetakhpp.php";


// Ambil bulan dan tahun dari form
$selectedMonth = isset($_POST['month']) ? $_POST['month'] : '';
$selectedYear = isset($_POST['year']) ? $_POST['year'] : '';

// Ambil data dari fungsi
$data = Tampil_Data("hpp");

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
    $filteredData = $data; // Tampilkan semua data jika filter kosong
}
?>


<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <!-- Filter Form -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Laporan Harga Pokok Produksi</h4>
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

                    <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100 table-striped table-hover text-center">
                        <thead class="table-light">
                            <tr>
                                <th>Nomor</th>
                                <th>Tanggal</th>
                                <th>Total Barang Jadi</th>
                                <th>Biaya Bahan Baku</th>
                                <th>Biaya Overhead</th>
                                <th>Biaya Tenaga Kerja</th>
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
                                        <td><?= $totalBarangJadi ?></td>
                                        <td class="text-end">Rp. <?= number_format($totalBahanBaku, 2, ',', '.') ?></td>
                                        <td class="text-end">Rp. <?= number_format($totalOverhead, 2, ',', '.') ?></td>
                                        <td class="text-end">Rp. <?= number_format($totalTenagaKerja, 2, ',', '.') ?></td>
                                        <td class="text-end">Rp. <?= number_format($totalHpp, 2, ',', '.') ?></td>
                                    </tr>
                            <?php
                                }
                            } else {
                                echo "<tr><td colspan='7' class='text-center'>Tidak ada data untuk bulan dan tahun yang dipilih.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>


                    <!-- Tombol Cetak -->
                    <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#cetakModal">Cetak / Download PDF</button>
                </div>
            </div>
        </div>
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
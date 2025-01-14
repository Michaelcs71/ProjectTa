<?php
// Ambil bulan dan tahun dari form
// Ambil bulan dan tahun dari form
$selectedMonth = isset($_POST['month']) ? $_POST['month'] : '';
$selectedYear = isset($_POST['year']) ? $_POST['year'] : '';

// Ambil data dari fungsi
$data = Tampil_Data("labarugi");

// Check if $data is an array before proceeding
if (!is_array($data)) {
    $data = []; // If not an array, set $data to an empty array
}

// Filter data berdasarkan bulan dan tahun
$filteredData = [];
if ($selectedMonth && $selectedYear) {
    $filteredData = array_filter($data, function ($item) use ($selectedMonth, $selectedYear) {
        $periode = DateTime::createFromFormat('Y-m', $item->periode);
        return $periode && $periode->format('m') === $selectedMonth && $periode->format('Y') === $selectedYear;
    });
} else {
    $filteredData = $data;
}

// Initialize totals for each category
$totalPendapatan = 0;
$totalBahanBaku = 0;
$totalUpah = 0;
$totalOverhead = 0;

// Calculate totals
foreach ($filteredData as $item) {
    if ($item->keterangan === 'Total Pendapatan') {
        $totalPendapatan += $item->total;
    } elseif ($item->keterangan === 'Biaya Bahan Baku') {
        $totalBahanBaku += $item->total;
    } elseif ($item->keterangan === 'Biaya Tenaga Kerja') {
        $totalUpah += $item->total;
    } elseif ($item->keterangan === 'Biaya Overhead') {
        $totalOverhead += $item->total;
    }
}

// Calculate Total HPP
$totalHPP = $totalBahanBaku + $totalUpah + $totalOverhead;

?>

<div class="modal fade" id="cetakModalLabaRugi" tabindex="-1" aria-labelledby="cetakModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Laporan Harga Pokok Produksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="printArea">
                <h4 class="text-center">PT. Sevenshop</h4>
                <h5 class="text-center">Laporan Harga Pokok Produksi</h5>
                <h6 class="text-center">
                    Periode: <?= htmlspecialchars(($selectedYear ?? '') . '-' . ($selectedMonth ?? ''), ENT_QUOTES, 'UTF-8') ?>
                </h6>
                <div id="printArea" style="border: 1px solid #ccc; padding: 20px; background-color: #f9f9f9;">
                    <style>
                        .text-currency {
                            display: inline-flex;
                            align-items: baseline;
                            /* Ensures "Rp." and the number are horizontally aligned */
                        }

                        .text-currency span {
                            margin-right: 12px;
                            /* Increase the gap between "Rp." and the amount */
                            font-weight: bold;
                            vertical-align: middle;
                            /* Ensures vertical alignment */
                        }

                        .text-end {
                            text-align: right;
                        }

                        .text-center {
                            text-align: center;
                        }

                        .table-container {
                            width: 100%;
                            border-collapse: collapse;
                            font-size: 14px;
                        }

                        .table-container td {
                            padding: 12px;
                            /* Increased padding for better spacing */
                        }

                        .table-container td:first-child {
                            text-align: left;
                        }

                        .table-container td:last-child {
                            text-align: center;
                            /* Center the values for Biaya Bahan Baku, Tenaga Kerja, and Overhead */
                        }

                        /* Adjust specific rows to have different alignment (centered for expenses) */
                        .center-align {
                            text-align: center;
                        }
                    </style>

                    <table class="table-container">
                        <tbody>
                            <!-- Total Pendapatan -->
                            <tr>
                                <td colspan="3" style="font-weight: bold;">Total Pendapatan</td>
                                <td class="text-end" style="font-weight: bold;">
                                    <div class="text-currency">
                                        <span>Rp.</span>
                                        <?= number_format($totalPendapatan, 2, ',', '.') ?>
                                    </div>
                                </td>
                            </tr>

                            <!-- Biaya Bahan Baku -->
                            <tr>
                                <td colspan="2">Biaya Bahan Baku</td>
                                <td class="center-align" style="font-weight: bold;">
                                    <div class="text-currency">
                                        <span>Rp.</span>
                                        <?= number_format($totalBahanBaku, 2, ',', '.') ?>
                                    </div>
                                </td>
                            </tr>

                            <!-- Biaya Tenaga Kerja -->
                            <tr>
                                <td colspan="2">Biaya Tenaga Kerja</td>
                                <td class="center-align" style="font-weight: bold;">
                                    <div class="text-currency">
                                        <span>Rp.</span>
                                        <?= number_format($totalUpah, 2, ',', '.') ?>
                                    </div>
                                </td>
                            </tr>

                            <!-- Biaya Overhead -->
                            <tr>
                                <td colspan="2">Biaya Overhead</td>
                                <td class="center-align" style="font-weight: bold;">
                                    <div class="text-currency">
                                        <span>Rp.</span>
                                        <?= number_format($totalOverhead, 2, ',', '.') ?>
                                    </div>
                                </td>
                            </tr>

                            <!-- Harga Pokok Produksi -->
                            <tr>
                                <td colspan="3"><strong>Total HPP</strong></td>
                                <td class="text-end" style="font-weight: bold;">
                                    <div class="text-currency">
                                        <span>Rp.</span>
                                        <?= number_format($totalHPP, 2, ',', '.') ?>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="3"><strong>Total Laba</strong></td>
                                <td class="text-end" style="font-weight: bold;">
                                    <div class=" text-currency">
                                        <span>Rp.</span>
                                        <?= number_format($totalPendapatan - $totalHPP, 2, ',', '.') ?>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>




                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" onclick="printReport()">Cetak</button>
                </div>
            </div>
        </div>
    </div>
</div>
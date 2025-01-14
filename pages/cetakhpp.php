<?php
// Ambil bulan dan tahun dari form
$selectedMonth = isset($_POST['month']) ? $_POST['month'] : '';
$selectedYear = isset($_POST['year']) ? $_POST['year'] : '';

// Ambil data dari fungsi
$data = Tampil_Data("cetakhpp");

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

// Hitung total biaya hanya dari bahan baku
$totalBahanBaku = array_reduce($filteredData, function ($carry, $item) {
    if (isset($item->total_biaya) && is_numeric($item->total_biaya) && $item->total_biaya > 0 && isset($item->id_bahan_material) && $item->id_bahan_material !== null) {
        $carry += $item->total_biaya;
    }
    return $carry;
}, 0);

// Hitung total biaya hanya dari overhead
$totalOverhead = array_reduce($filteredData, function ($carry, $item) {
    if (isset($item->total_biaya) && is_numeric($item->total_biaya) && $item->total_biaya > 0 && isset($item->id_overhead) && $item->id_overhead !== null) {
        $carry += $item->total_biaya;
    }
    return $carry;
}, 0);

// Hitung total upah tenaga kerja
$totalUpah = array_reduce($filteredData, function ($carry, $item) {
    if (isset($item->total_upah) && is_numeric($item->total_upah) && $item->total_upah > 0 && isset($item->id_pekerja) && $item->id_pekerja !== null) {
        $carry += $item->total_upah;
    }
    return $carry;
}, 0);
?>

<div class="modal fade" id="cetakModal" tabindex="-1" aria-labelledby="cetakModalLabel" aria-hidden="true">
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
                        }

                        .text-currency span {
                            margin-right: 4px;
                            font-weight: bold;
                        }
                    </style>
                    <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                        <tbody>
                            <!-- Kategori Bahan Baku -->
                            <tr>
                                <td colspan="3" style="font-weight: bold;">Bahan Baku</td>
                            </tr>
                            <?php if (!empty($filteredData)): ?>
                                <?php foreach ($filteredData as $item): ?>
                                    <?php if (isset($item->total_jumlah) && isset($item->total_biaya) && $item->total_biaya > 0 && isset($item->id_bahan_material)): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($item->nama_bahan_material ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                            <td class="text-end">
                                                <div class="text-currency">
                                                    <span>Rp.</span>
                                                    <?= number_format($item->total_biaya ?? 0, 2, ',', '.') ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <tr>
                                    <td colspan="2" style="font-weight: bold;">Total Bahan Baku</td>
                                    <td class="text-end" style="font-weight: bold;">
                                        <div class="text-currency">
                                            <span>Rp.</span>
                                            <?= number_format($totalBahanBaku, 2, ',', '.') ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center">Tidak ada data untuk bulan dan tahun yang dipilih.</td>
                                </tr>
                            <?php endif; ?>

                            <!-- Kategori Overhead -->
                            <tr>
                                <td colspan="3" style="font-weight: bold;">Overhead</td>
                            </tr>
                            <?php if (!empty($filteredData)): ?>
                                <?php foreach ($filteredData as $item): ?>
                                    <?php if (isset($item->total_biaya) && $item->total_biaya > 0 && isset($item->id_overhead)): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($item->nama_overhead ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                            <td class="text-end">
                                                <div class="text-currency">
                                                    <span>Rp.</span>
                                                    <?= number_format($item->total_biaya ?? 0, 2, ',', '.') ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <tr>
                                    <td colspan="2" style="font-weight: bold;">Total Overhead</td>
                                    <td class="text-end" style="font-weight: bold;">
                                        <div class="text-currency">
                                            <span>Rp.</span>
                                            <?= number_format($totalOverhead, 2, ',', '.') ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center">Tidak ada data untuk bulan dan tahun yang dipilih.</td>
                                </tr>
                            <?php endif; ?>

                            <!-- Total Upah -->
                            <tr>
                                <td colspan="3" style="font-weight: bold;">Upah Tenaga Kerja</td>
                            </tr>
                            <?php if (!empty($filteredData)): ?>
                                <?php foreach ($filteredData as $item): ?>
                                    <?php if (isset($item->total_upah) && $item->total_upah > 0 && isset($item->id_pekerja)): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($item->nama_pekerja ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                            <td class="text-end">
                                                <div class="text-currency">
                                                    <span>Rp.</span>
                                                    <?= number_format($item->total_upah ?? 0, 2, ',', '.') ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <tr>
                                    <td colspan="2" style="font-weight: bold;">Total Upah</td>
                                    <td class="text-end" style="font-weight: bold;">
                                        <div class="text-currency">
                                            <span>Rp.</span>
                                            <?= number_format($totalUpah, 2, ',', '.') ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center">Tidak ada data untuk bulan dan tahun yang dipilih.</td>
                                </tr>
                            <?php endif; ?>

                            <!-- Harga Pokok Produksi -->
                            <tr>
                                <td colspan="2"><strong>Harga Pokok Produksi</strong></td>
                                <td class="text-end" style="font-weight: bold;">
                                    <div class=" text-currency">
                                        <span>Rp.</span>
                                        <?= number_format($totalBahanBaku + $totalOverhead + $totalUpah, 2, ',', '.') ?>
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
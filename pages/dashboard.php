<div class="main-content bg">
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Dashboard</h4>
                    </div>
                </div>
            </div>
            <div class="marquee">
                <p>Selamat Datang di Sistem Keuangan Sevenshop</p>
            </div>
            <div class="row">

                <div class="col-3-5">
                    <div class="small-box bg-green text-white shadow-primary">
                        <div class="inner">
                            <h1 class="text-white" id="totalPendapatan">Rp 0</h1>
                            <p>Total Pendapatan</p>
                        </div>
                        <a class="small-box-footer text-white bg-footer" href="#">
                            Selengkapnya <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <div class="col-3-5">
                    <div class="small-box bg-red text-white shadow-primary">
                        <div class="inner">
                            <h1 class="text-white" id="totalHpp">Rp 0</h1>
                            <p>Total Biaya Produksi</p>
                        </div>
                        <a class="small-box-footer text-white bg-footer" href="#">
                            Selengkapnya <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>



                <div class="col-3-5">
                    <div class="small-box bg-yellow text-white shadow-primary">
                        <div class="inner">
                            <h1 class="text-white" id="totalLabaRugi">Loading...</h1>
                            <p>Jumlah Laba Rugi</p>
                        </div>
                        <a class="small-box-footer text-white text-center bg-footer" data-bs-toggle="modal"
                            data-bs-target="#modalViewKelahiran">Selengkapnya
                            <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Bar Chart</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="barChart"></canvas>
                        </div>
                    </div><!--end card-->
                </div>


                <!-- end row -->
            </div> <!-- container-fluid -->
        </div>
    </div>
</div>



<script>
    $(document).ready(function() {
        $.ajax({
            url: "webservice/api/totalpendapatan.php",
            type: "GET",
            dataType: "json",
            success: function(response) {
                const totalPendapatan = response.data[0]?.total_pendapatan || 0; // Ambil total_pendapatan
                const formattedPendapatan = formatRupiah(totalPendapatan); // Format angka ke Rupiah
                $("#totalPendapatan").text(formattedPendapatan); // Tampilkan hasil
            },
            error: function() {
                $("#totalPendapatan").text("Gagal mengambil data");
            }
        });

        // Fungsi untuk memformat angka ke Rupiah
        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR'
            }).format(angka);
        }
    });
</script>

<script>
    $(document).ready(function() {
        $.ajax({
            url: "webservice/api/totalhpp.php",
            type: "GET",
            dataType: "json",
            success: function(response) {
                const totalHpp = response.data[0]?.total_hpp || 0; // Ambil total_pendapatan
                const formattedHpp = formatRupiah(totalHpp); // Format angka ke Rupiah
                $("#totalHpp").text(formattedHpp); // Tampilkan hasil
            },
            error: function() {
                $("#totalHpp").text("Gagal mengambil data");
            }
        });

        // Fungsi untuk memformat angka ke Rupiah
        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR'
            }).format(angka);
        }
    });
</script>

<script>
    $(document).ready(function() {
        $.ajax({
            url: "webservice/api/totallabarugi.php",
            type: "GET",
            dataType: "json",
            success: function(response) {
                const totalLabaRugi = response.data[0]?.totalRg || 0; // Ambil total_pendapatan
                const formattedHpp = formatRupiah(totalLabaRugi); // Format angka ke Rupiah
                $("#totalLabaRugi").text(formattedHpp); // Tampilkan hasil
            },
            error: function() {
                $("#totalLabaRugi").text("Gagal mengambil data");
            }
        });

        // Fungsi untuk memformat angka ke Rupiah
        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR'
            }).format(angka);
        }
    });
</script>

<script>
    const apiUrl = "http://localhost/ProjectTa/webservice/api/hppproduk.php";

    // Ambil data dari API
    fetch(apiUrl)
        .then(response => response.json())
        .then(data => {
            // Proses data
            const labels = data.map(item => item.nama_barang); // Nama barang sebagai label
            const hppData = data.map(item => item.hpp_per_unit); // HPP per unit

            // Buat Bar Chart
            const ctx = document.getElementById('barChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'HPP Per Unit (Rp)',
                        data: hppData,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Produk'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'HPP (Rp)'
                            },
                            ticks: {
                                callback: function(value) {
                                    return new Intl.NumberFormat('id-ID', {
                                        style: 'currency',
                                        currency: 'IDR'
                                    }).format(value);
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return new Intl.NumberFormat('id-ID', {
                                        style: 'currency',
                                        currency: 'IDR'
                                    }).format(tooltipItem.raw);
                                }
                            }
                        }
                    }
                }
            });
        })
        .catch(error => {
            console.error('Error fetching data:', error);
        });
</script>













<link rel="stylesheet" href="assets/libs/glightbox/css/glightbox.min.css">
<script src="assets/libs/glightbox/js/glightbox.min.js"></script>
<script src="assets/js/pages/lightbox.init.js"></script>
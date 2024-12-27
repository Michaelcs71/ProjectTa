<?php

$configPath = $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/webservice/config.php";
if (file_exists($configPath)) {
    include $configPath;
} else {
    die("Config file not found: " . $configPath);
}

$user = @$_POST['username'];
$pass = @$_POST['password'];
$login = @$_POST['login'];

if (isset($login)) {
    $sql = mysqli_query($koneksi, "SELECT * FROM user WHERE username = '$user' AND pass = '$pass'") or die(mysqli_error($koneksi));
    $data = mysqli_fetch_array($sql);
    $cek = mysqli_num_rows($sql);

    if ($cek > 0) {
        if ($data['level'] == "admin") {
            $_SESSION['admin'] = $data['id'];
            header("Location: index.php?link=dashboard");
        } elseif ($data['level'] == "manager") {
            $_SESSION['manager'] = $data['id'];
            header("Location: index.php?link=dashboard");
        } elseif ($data['level'] == "accounting") {
            $_SESSION['accounting'] = $data['id'];
            header("Location: index.php?link=dashboard");
        }
    } else {
        echo "<script type='text/javascript'>alert('Username / password salah');window.location.href='index.php?link=login';</script>";
    }
}
?>

<div class="auth-page">
    <div class="container-fluid p-0">
        <div class="row g-0">
            <div class="col-xxl-3 col-lg-4 col-md-5">
                <div class="auth-full-page-content d-flex p-sm-5 p-4">
                    <div class="w-100">
                        <div class="d-flex flex-column h-100">
                            <div class="mb-4 mb-md-5 text-center">
                                <a href="login.php" class="d-block auth-logo">
                                    <img src="../assets/images/icon.png" alt="" height="28" />
                                    <span class="logo-txt">Sistem Informasi Desa Purworejo</span>
                                </a>
                            </div>
                            <div class="auth-content my-auto">
                                <!-- <div class="text-center">
                                    <h5 class="mb-0">Selamat datang di Sistem Informasi Akuntansi Laundry</h5>
                                </div> -->
                                <form class="mt-4 pt-2" method="POST">
                                    <?php
                                    if (isset($_SESSION['register'])) {
                                        echo "<div class='alert alert-success' id='tes'>" . $_SESSION['register'] . "</div>";
                                        unset($_SESSION['register']);
                                    }
                                    if (isset($_SESSION['error'])) {
                                        echo "<div class='alert alert-danger' id='gagal_login'>" . $_SESSION['error'] . "</div>";
                                        unset($_SESSION['error']);
                                    }
                                    ?>

                                    <div class="mb-3">
                                        <label class="form-label">Username</label>
                                        <input type="text" class="form-control" name="username" id="username" placeholder="Masukkan username" required />
                                    </div>
                                    <div class="mb-3">
                                        <div class="d-flex align-items-start">
                                            <div class="flex-grow-1">
                                                <label class="form-label">Password</label>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <div class="">
                                                    <a href="auth-recoverpw.html" class="text-muted">Lupa password?</a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="input-group auth-pass-inputgroup">
                                            <input name="password" type="password" class="form-control" placeholder="Masukkan password" aria-label="Password" aria-describedby="password-addon" required />
                                            <button class="btn btn-light shadow-none ms-0" type="button" id="password-addon">
                                                <i class="mdi mdi-eye-outline"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col">
                                            <div class="form-check">
                                                <input class="form-check-input" name="remember" type="checkbox" id="remember-check" />
                                                <label class="form-check-label" for="remember-check">
                                                    Ingat Saya
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <button class="btn btn-primary w-100 waves-effect waves-light" name="login" type="submit" ;>
                                            Log In
                                        </button>
                                    </div>
                                </form>

                                <div class="mt-5 text-center">
                                    <p class="text-muted mb-0">
                                        Belum punya akun?
                                        <a href="register.php" class="text-primary fw-semibold">
                                            Daftar sekarang
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end auth full page content -->
            </div>
            <!-- end col -->
            <div class="col-xxl-9 col-lg-8 col-md-7">
                <div class=" pt-md-5 p-4 d-flex">
                    <div class="bg-overlay bg-primary"></div>
                    <ul class="bg-bubbles">
                        <li></li>
                        <li></li>
                        <li></li>
                        <li></li>
                        <li></li>
                        <li></li>
                        <li></li>
                        <li></li>
                        <li></li>
                        <li></li>
                    </ul>
                    <!-- end bubble effect -->
                    <div class="row justify-content-center align-items-center">
                        <div class="col-xl-7">
                            <div class="p-0 p-sm-4 px-xl-0">
                                <div id="reviewcarouselIndicators" class="carousel slide" data-bs-ride="carousel">
                                    <!-- end carouselIndicators -->
                                    <div class="carousel-inner">
                                        <div class="carousel-item active">
                                            <div class="testi-contain text-white">
                                                <i class="bx bxs-quote-alt-left text-success display-6"></i>
                                                <h4 class="mt-4 fw-medium lh-base text-white">
                                                </h4>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end carousel-inner -->
                                </div>
                                <!-- end review carousel -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end col -->
        </div>
        <!-- end row -->
    </div>
    <!-- end container fluid -->
</div>
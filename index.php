<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "mikj2431_mikada-laundry";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM reviews ORDER BY created_at DESC";
    $result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Mikada Laundry</title>

    <!-- favicon -->
    <link rel="icon" href="../manage/assets/img/Laundry.png" type="image/x-icon" />

    <!-- google font -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet" />
    <!-- tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- mycss -->
    <link rel="stylesheet" href="asset/style.css" />
    <!-- flowbite-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <!-- font awesome : icons -->
    <script src="https://kit.fontawesome.com/22f19496c5.js" crossorigin="anonymous"></script>
    <!-- sweetalert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="font-[Poppins]">

    <!-- Start Navbar -->
    <?php session_start(); ?>
    <nav class="bg-white bg-opacity-75 backdrop-blur-sm fixed w-full z-40 top-0 start-0 shadow-md">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <!-- Logo -->
            <a href="index.php" class="flex items-center space-x-3 rtl:space-x-reverse">
                <img src="../manage/assets/img/Laundry.png" class="h-8" alt="Logo" loading="lazy" />
                <span class="self-center text-2xl font-semibold whitespace-nowrap">Mikada Laundry</span>
            </a>

            <!-- Toggle Button -->
            <button
                data-collapse-toggle="navbar-sticky"
                type="button"
                class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200"
                aria-controls="navbar-sticky"
                aria-expanded="false"
            >
                <span class="sr-only">Open main menu</span>
                <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
            </button>

            <!-- Navigation Links -->
            <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-sticky">
                <ul class="flex flex-col p-4 mt-4 font-medium border rounded-lg md:flex-row md:space-x-8 md:mt-0 md:border-0">
                    <li><a href="index.php#" class="block py-2 px-3 text-lg font-bold text-gray-900 hover:bg-gray-100 md:hover:bg-transparent md:hover:text-sky-700">Home</a></li>
                    <li><a href="index.php#layanan" class="block py-2 px-3 text-lg font-bold text-gray-900 hover:bg-gray-100 md:hover:bg-transparent md:hover:text-sky-700">Services</a></li>
                    <li><a href="index.php#how" class="block py-2 px-3 text-lg font-bold text-gray-900 hover:bg-gray-100 md:hover:bg-transparent md:hover:text-sky-700">How</a></li>
                    <li><a href="index.php#alamat" class="block py-2 px-3 text-lg font-bold text-gray-900 hover:bg-gray-100 md:hover:bg-transparent md:hover:text-sky-700">Address</a></li>
                    <li><a href="index.php#benefit" class="block py-2 px-3 text-lg font-bold text-gray-900 hover:bg-gray-100 md:hover:bg-transparent md:hover:text-sky-700">Benefit</a></li>

                    <!-- User Action Buttons -->
                    <?php if (isset($_SESSION['username'])): ?>
                        <li>
                            <a href="./logout/" class="block py-2 px-3 text-lg font-bold text-red-700 hover:bg-gray-100 md:hover:bg-transparent md:hover:text-red-700" onclick="return confirm('Apakah anda yakin ingin keluar?');">LOGOUT</a>
                        </li>
                        <?php if ($_SESSION['role'] == 'admin'): ?>
                            <li><a href="./manage/admin/" class="block py-2 px-3 text-lg font-bold text-sky-700 hover:bg-gray-100 md:hover:bg-transparent md:hover:text-sky-700">DASHBOARD ADMIN</a></li>
                        <?php elseif ($_SESSION['role'] == 'user'): ?>
                            <li><a href="./manage/user/" class="block py-2 px-3 text-lg font-bold text-sky-700 hover:bg-gray-100 md:hover:bg-transparent md:hover:text-sky-700">DASHBOARD USER</a></li>
                        <?php endif; ?>
                    <?php else: ?>
                        <li><a href="./login/" class="block py-2 px-3 text-lg font-bold text-sky-700 hover:bg-gray-100 md:hover:bg-transparent md:hover:text-sky-700">SIGN IN</a></li>
                        <li><a href="./register/" class="block py-2 px-3 text-lg font-bold text-gray-700 bg-white border-2 border-gray-500 hover:bg-gray-100 md:hover:bg-transparent md:hover:text-gray-700">SIGN UP</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <script>
        document.querySelector('[data-collapse-toggle]').addEventListener('click', function () {
            const nav = document.getElementById('navbar-sticky');
            nav.classList.toggle('hidden');
        });
    </script>
    <!-- End Navbar -->

    <!-- start jumbotron -->
    <section class="bg-center bg-no-repeat bg-[url('asset/img/bg-mikadalaundry.jpg')] bg-gray-700 bg-blend-multiply bg-cover min-h-screen">
        <div class="max-w-screen-xl px-4 py-24 mx-auto text-center lg:py-56">
            <h1 class="mb-4 text-4xl font-extrabold leading-none tracking-tight text-white md:text-5xl lg:text-6xl"><span class="text-cyan-500">Mikada Laundry</span>, Shuttle Every Day</h1>
            <p class="mb-8 text-lg font-normal text-gray-300 lg:text-xl sm:px-16 lg:px-48">
                <span class="underline">Mikada Laundry</span> adalah sebuah platform yang menyediakan jasa laundry. Kami menyediakan berbagai layanan laundry yang dapat anda pilih.
            </p>
            <div class="flex flex-col space-y-4 sm:flex-row sm:justify-center sm:space-y-0">
                <a href="#layanan" class="inline-flex items-center justify-center px-5 py-3 text-base font-medium text-center text-white rounded-lg bg-sky-700 hover:bg-sky-800 focus:ring-4 focus:ring-sky-300 dark:focus:ring-sky-900">
                    Laundry Now
                    <svg class="w-3.5 h-3.5 ms-2 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
                    </svg>
                </a>
                <a href="#how" class="inline-flex items-center justify-center px-5 py-3 text-base font-medium text-center text-white border border-white rounded-lg hover:text-gray-900 sm:ms-4 hover:bg-gray-100 focus:ring-4 focus:ring-gray-400">
                    See More
                </a>
            </div>
        </div>
    </section>
    <!-- end jumbotron -->

    <!-- start layanan -->
    <?php
        function getRating($paket) {
            global $conn;
            $query = "SELECT AVG(bintang) AS rata_rata_bintang FROM reviews WHERE paket = '$paket'";
            $result = $conn->query($query);
            if ($result) {
                $row = $result->fetch_assoc();
                return round($row['rata_rata_bintang']);
            } else {
                return 0;
            }
        }

        $rating_normal = getRating('normal');
        $rating_besok_ambil = getRating('besok_ambil');
        $rating_1_hari_selesai = getRating('1_hari_selesai');
    ?>

    <!-- HTML untuk Menampilkan Paket tanpa Rating Bintang -->
    <section id="layanan" class="px-8 py-20 mx-auto text-center max-w-7xl">
        <div class="max-w-xl m-auto mb-8 text-center">
            <h1 class="text-xl font-extrabold text-sky-800 md:text-5xl">Layanan Kami</h1>
            <p class="text-sm font-light text-slate-600 md:font-normal"><span class="font-semibold text-sky-500">Mikada Laundry</span> punya 3 layanan yang dapat anda pilih. Sesuaikan layanan dengan kebutuhan anda ya😁</p>
        </div>

        <div class="flex flex-wrap justify-center gap-3">
            <!-- Paket Normal -->
            <div class="flex flex-col justify-between w-full p-3 border border-gray-200 rounded-lg shadow md:w-1/4 bg-slate-200 dark:bg-gray-800 dark:border-gray-700">
                <div>
                    <button>
                        <img class="rounded-t-lg lg:h-72" src="asset/img/bersih.jpg" alt="product image" loading="lazy" />
                    </button>
                    <div class="px-5 py-5">
                        <h5 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-white md:text-3xl">Paket Normal</h5>
                        <p class="text-xs font-light text-white">Paket Normal adalah layanan yang memberikan kenyamanan mencuci tanpa harus terburu-buru. Cukup antar pagi, dan pakaian Anda akan siap diambil pada sore hari. Cocok bagi Anda yang tidak terburu-buru dan ingin menjaga anggaran.</p>
                    </div>
                </div>
            </div>

            <!-- Paket Besok Ambil -->
            <div class="flex flex-col justify-between w-full p-3 border border-gray-200 rounded-lg shadow md:w-1/4 bg-slate-200 dark:bg-gray-800 dark:border-gray-700">
                <div>
                    <button>
                        <img class="object-cover rounded-t-lg lg:h-72" src="asset/img/cuci.jpg" alt="product image" loading="lazy" />
                    </button>
                    <div class="px-5 py-5">
                        <h5 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-white md:text-3xl">Paket Besok Ambil</h5>
                        <p class="text-xs font-light text-white">Paket Besok Ambil adalah layanan praktis bagi Anda yang ingin pakaian dicuci dan dikembalikan keesokan harinya. Antar pagi, dan kami akan mengantarkan kembali pakaian Anda pada sore harinya. Pilihan sempurna bagi Anda yang membutuhkan layanan cepat dengan waktu yang efisien.</p>
                    </div>
                </div>
            </div>

            <!-- Paket 1 Hari Selesai -->
            <div class="flex flex-col justify-between w-full p-3 border border-gray-200 rounded-lg shadow md:w-1/4 bg-slate-200 dark:bg-gray-800 dark:border-gray-700">
                <div>
                    <button>
                        <img class="object-cover rounded-t-lg lg:h-72" src="asset/img/setrika.jpg" alt="product image" loading="lazy" />
                    </button>
                    <div class="px-5 py-5">
                        <h5 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-white md:text-3xl">Paket 1 Hari Selesai</h5>
                        <p class="text-xs font-light text-white">Paket 1 Hari Selesai adalah layanan terbaik kami untuk Anda yang membutuhkan pakaian cepat kembali. Antar pagi dan pakaian Anda akan siap diambil pada sore harinya, dalam kondisi bersih, wangi, dan siap pakai. Layanan ini sangat cocok bagi Anda yang memerlukan kecepatan tanpa mengorbankan kualitas.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- end layanan -->

    <!-- start about -->
    <section id="how" class="flex items-center justify-center gap-5 px-8 py-20">
        <div class="w-full md:w-1/4">
            <h1 class="py-8 text-xl font-extrabold tracking-wide text-center text-slate-900 md:text-2xl">Bagaimana Cara Kerjanya?</h1>
            <div class="flex items-center justify-center py-5 md:py-3">
                <div class="w-1/3">
                    <i class="text-6xl fa-solid fa-cart-plus text-sky-500"></i>
                </div>
                <div class="w-1/2">
                    <h3 class="text-lg font-semibold md:text-xl">Pesan Laundry Anda</h3>
                    <p class="text-sm font-light text-justify text-slate-700">Pesan laundry dengan mudah melalui platform kami. Dijamin <span class="font-bold text-cyan-300">100%</span> aman.</p>
                </div>
            </div>
            <div class="flex items-center justify-center py-5 md:py-3">
                <div class="w-1/3">
                    <i class="text-6xl fa-solid fa-wallet text-sky-500"></i>
                </div>
                <div class="w-1/2">
                    <h3 class="text-lg font-semibold md:text-xl">Lakukan Pembayaran</h3>
                    <p class="text-sm font-light text-justify text-slate-700">Pembayaran mudah dengan berbagai jenis metode pembayaran.</p>
                </div>
            </div>
            <div class="flex items-center justify-center py-5 md:py-3">
                <div class="w-1/3">
                    <i class="text-6xl fa-solid fa-box text-sky-500"></i>
                </div>
                <div class="w-1/2">
                    <h3 class="text-lg font-semibold md:text-xl">Pesanan Siap Diambil</h3>
                    <p class="text-sm font-light text-justify text-slate-700">Pesanan akan segera diproses dan kami akan mengonfirmasi kepada Anda. Proses paling lama hanya <span class="font-bold text-cyan-300">1 jam</span> saja.</p>
                </div>
            </div>
        </div>
    </section>
    <!-- end about -->

    <!-- start map -->
    <section id="alamat" class="relative px-8 py-36 md:py-48 bg-sky-900">
        <!-- wave -->
        <div class="custom-shape-divider-top-1709721751">
            <!-- SVG wave -->
        </div>
        <div class="mb-8 text-center">
            <p class="text-sm font-light text-white md:font-normal">Temukan lokasi toko kami dengan mudah!</p>
            <h1 class="text-xl font-extrabold text-sky-200 md:text-5xl">Lokasi Toko</h1>
        </div>

        <div class="relative w-full h-96">
            <!-- Google Maps Embed -->
            <iframe 
                class="w-full h-full rounded-lg shadow-md"
                src="https://www.google.com/maps/embed/v1/place?key=AIzaSyDCd7Q7C1OeWIXlSzX3iw8Y3ps9M2ES1ak&q=Mikada+LAUNDRY+KILOAN,Jl. Minangkabau Dalam II No.4 9, RT.9/RW.6, Menteng Atas, Kecamatan Setiabudi, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12960" 
                frameborder="0"
                allowfullscreen 
                aria-hidden="false" 
                tabindex="0">
            </iframe>
        </div>

        <!-- wave -->
        <div class="custom-shape-divider-bottom-1709721933">
            <!-- SVG wave -->
        </div>
    </section>
    <!-- end map -->

    <!-- start benefit -->
    <section id="benefit" class="px-8 py-20 mx-auto text-center max-w-7xl">
        <h1 class="text-xl font-extrabold md:text-4xl">Benefit Laundry di <span class="text-sky-500">Mikada Laundry</span></h1>
        <p class="text-sm font-light text-slate-700 md:font-normal">Kamu ingin tau apa aja yang bakal kamu dapatkan dari laundry kami?</p>
        <div class="flex flex-wrap items-start justify-center py-10 md:gap-3">
            <!-- first -->
            <div class="w-2/3 my-3 border-2 rounded-md shadow-md bg-slate-100 border-slate-300 md:w-1/4">
                <img src="asset/img/bersih.jpg" alt="1" class="object-cover" loading="lazy" />
                <div class="p-3 md:px-10">
                    <h3 class="py-3 text-lg font-bold leading-5 md:text-xl">Kebersihan Terjamin untuk Setiap <span class="text-sky-800">Pakaian</span></h3>
                    <p class="text-sm font-extralight text-slate-700">Kami menjamin setiap pakaian anda akan kembali dengan keadaan bersih setiap bagiannya.</p>
                </div>
            </div>

            <!-- second -->
            <div class="w-2/3 my-3 border-2 rounded-md shadow-md bg-slate-100 border-slate-300 md:w-1/4">
                <img src="asset/img/bersih.jpg" alt="1" class="object-cover" loading="lazy" />
                <div class="p-3 md:px-10">
                    <h3 class="py-3 text-lg font-bold leading-5 md:text-xl">Layanan Paket Besok Ambil & 1 Hari Selesai - Pilihan Lebih Cepat dan Hemat Waktu!</h3>
                    <p class="text-sm font-extralight text-slate-700">Dengan paket Besok Ambil atau 1 Hari Selesai, Anda bisa menikmati kemudahan layanan yang cepat tanpa mengorbankan kualitas. Antar pagi, ambil sore! </p>
                </div>
            </div>

            <!-- third -->
            <div class="w-2/3 my-3 border-2 rounded-md shadow-md bg-slate-100 border-slate-300 md:w-1/4">
                <img src="asset/img/bersih.jpg" alt="1" class="object-cover" />
                <div class="p-3 md:px-10">
                    <h3 class="py-3 text-lg font-bold leading-5 md:text-xl">Pengalaman Layanan <span class="text-sky-800">Terbaik</span></h3>
                    <p class="text-sm font-extralight text-slate-700">Nikmati pengalaman layanan terbaik dari setiap layanan kami, termasuk pesanan dengan berbagai macam metode pembayaran yang cepat dan mudah.</p>
                </div>
            </div>
        </div>
    </section>
    <!-- end benefit -->

    <!-- start footer -->
    <footer class="bg-sky-950 ">
        <div class="w-full max-w-screen-xl p-4 py-6 mx-auto lg:py-8">
            <div class="md:flex md:justify-between">
                <div class="mb-6 md:mb-0">
                    <a href="#" class="flex items-center">
                        <img src="../manage/assets/img/Laundry.png" class="h-8 me-3" alt="FlowBite Logo" loading="lazy" />
                        <span class="self-center text-2xl font-semibold whitespace-nowrap text-sky-200">Mikada Laundry</span>
                    </a>
                </div>
                <div class="grid grid-cols-2 gap-8 sm:gap-6 sm:grid-cols-3">
                    <div>
                        <h2 class="mb-6 text-sm font-semibold text-white uppercase">Follow us</h2>
                        <ul class="font-medium text-gray-500 :text-gray-400">
                            <li>
                                <a href="#" target="_blank" class="hover:underline">Instagram</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <hr class="my-6 border-gray-200 sm:mx-auto :border-gray-700 lg:my-8" />
            <div class="sm:flex sm:items-center sm:justify-between">
                <span class="text-sm text-gray-500 sm:text-center :text-gray-400">© 2024 <a href="#" class="hover:underline">Mikada Laundry™</a>. All Rights Reserved.
                </span>

            </div>
        </div>
    </footer>
    <!-- end footer -->

    <!-- script flowbite -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <!-- my script -->
    <script src="asset/navbar.js"></script>
    <script src="asset/script.js"></script>
</body>
</html>
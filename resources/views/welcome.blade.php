<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Arsip Akreditasi - Universitas Muhammadiyah Lampung</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .bg-primary {
            background-color: #004d26;
        }

        .bg-secondary {
            background-color: #006633;
        }

        .bg-accent {
            background-color: #00a651;
        }

        .text-primary {
            color: #004d26;
        }

        .text-secondary {
            color: #006633;
        }

        .text-accent {
            color: #00a651;
        }

        .hover\:bg-primary-dark:hover {
            background-color: #003319;
        }

        .hover\:bg-secondary-dark:hover {
            background-color: #004d26;
        }

        .hover\:text-primary-dark:hover {
            color: #003319;
        }

        .focus\:outline-primary:focus {
            outline-color: #00a651;
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 77, 38, 0.15);
        }

        /* Slider Styles */
        .slider-container {
            position: relative;
            overflow: hidden;
            border-radius: 12px;
        }

        .slider-track {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }

        .slider-slide {
            min-width: 100%;
            position: relative;
        }

        .slider-indicators {
            position: absolute;
            bottom: 20px;
            left: 0;
            right: 0;
            display: flex;
            justify-content: center;
            gap: 8px;
        }

        .slider-indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .slider-indicator.active {
            background-color: white;
            width: 30px;
            border-radius: 5px;
        }

        /* Login button styling */
        .login-button {
            background-color: #00a651;
            color: white;
            font-weight: 600;
            padding: 0.5rem 1.5rem;
            border-radius: 0.375rem;
            transition: background-color 0.2s;
        }

        .login-button:hover {
            background-color: #008c46;
        }

        .login-button:focus {
            outline: 2px solid #00a651;
            outline-offset: 2px;
        }

        .register-button {
            background-color: transparent;
            color: #006633;
            font-weight: 600;
            padding: 0.5rem 1.5rem;
            border-radius: 0.375rem;
            border: 2px solid #006633;
            margin-left: 1rem;
            transition: all 0.2s;
        }

        .register-button:hover {
            background-color: #006633;
            color: white;
        }

        .dashboard-button {
            color: #006633;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            transition: color 0.2s;
        }

        .dashboard-button:hover {
            color: #004d26;
        }

        .dark .login-button {
            color: #9ca3af;
        }

        .dark .login-button:hover {
            color: white;
        }

        .dark .register-button {
            color: #9ca3af;
            border-color: #9ca3af;
        }

        .dark .register-button:hover {
            background-color: #9ca3af;
            color: #1f2937;
        }

        .dark .dashboard-button {
            color: #9ca3af;
        }

        .dark .dashboard-button:hover {
            color: white;
        }

        /* Logo styling */
        .logo-container {
            background-color: white;
            border-radius: 0.5rem;
            padding: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-img {
            height: 3rem;
            width: auto;
            object-fit: contain;
        }

        .footer-logo {
            height: 2rem;
            width: auto;
            object-fit: contain;
        }
    </style>
</head>

<body class="antialiased bg-gray-50">
    <!-- Header/Navbar -->
    <header class="bg-primary text-white shadow-lg">
        <div class="container mx-auto px-4 py-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <!-- Logo & Judul -->
                <div class="flex items-center space-x-3 mb-4 md:mb-0">
                    <div class="logo-container">
                        <img class="logo-img"
                            src="https://uml.ac.id/web/Universitas%20Muhammadiyah%20Lampung_1755961227.png"
                            alt="Logo Universitas Muhammadiyah Lampung">
                    </div>
                    <div>
                        <h1 class="text-xl font-bold">ARSIP AKREDITASI</h1>
                        <p class="text-sm text-gray-200">Universitas Muhammadiyah Lampung</p>
                    </div>
                </div>

                <!-- Menu Navigasi -->
                <nav class="flex flex-wrap justify-center gap-4 md:gap-6 mb-4 md:mb-0">
                    <a href="#" class="font-medium hover:text-gray-200 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Beranda
                    </a>
                    <a href="#" class="font-medium hover:text-gray-200 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        Dokumen
                    </a>
                    <a href="#" class="font-medium hover:text-gray-200 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Sertifikat
                    </a>
                    <a href="#" class="font-medium hover:text-gray-200 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Informasi
                    </a>
                </nav>

                <!-- Login Section - Laravel Blade Integration -->
                @if (Route::has('login'))
                    <div class="mt-4 md:mt-0 text-right z-10">
                        @auth
                            <a href="{{ url('/dashboard') }}"
                                class="dashboard-button focus:outline focus:outline-2 focus:rounded-sm focus:outline-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                                class="login-button focus:outline focus:outline-2 focus:rounded-sm focus:outline-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                </svg>
                                Log in
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="register-button focus:outline focus:outline-2 focus:rounded-sm focus:outline-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                    </svg>
                                    Register
                                </a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
        </div>
    </header>

    <!-- Slider Gambar -->
    <section class="container mx-auto px-4 py-6">
        <div class="slider-container bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="slider-track" id="sliderTrack">
                <!-- Slide 1 - Akreditasi Institusi -->
                <div class="slider-slide">
                    <div class="relative h-64 md:h-80">
                        <div class="absolute inset-0 bg-gradient-to-r from-primary to-secondary"></div>
                        <div class="absolute inset-0 flex items-center">
                            <div class="container mx-auto px-8">
                                <div class="max-w-2xl text-white">
                                    <h2 class="text-3xl md:text-4xl font-bold mb-3">Akreditasi Institusi Unggul</h2>
                                    <p class="text-lg mb-6">Akses dokumen lengkap akreditasi institusi Universitas
                                        Muhammadiyah Lampung, termasuk borang, laporan evaluasi diri, dan sertifikat.
                                    </p>
                                    <a href="#"
                                        class="inline-block bg-white text-primary font-medium py-2 px-6 rounded-lg hover:bg-gray-100 transition-colors focus:outline focus:outline-2 focus:outline-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Lihat Dokumen
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide 2 - Borang Akreditasi Prodi -->
                <div class="slider-slide">
                    <div class="relative h-64 md:h-80">
                        <div class="absolute inset-0 bg-gradient-to-r from-secondary to-accent"></div>
                        <div class="absolute inset-0 flex items-center">
                            <div class="container mx-auto px-8">
                                <div class="max-w-2xl text-white">
                                    <h2 class="text-3xl md:text-4xl font-bold mb-3">Borang Akreditasi Program Studi
                                    </h2>
                                    <p class="text-lg mb-6">Kumpulan borang akreditasi seluruh program studi di
                                        lingkungan Universitas Muhammadiyah Lampung.</p>
                                    <a href="#"
                                        class="inline-block bg-white text-secondary font-medium py-2 px-6 rounded-lg hover:bg-gray-100 transition-colors focus:outline focus:outline-2 focus:outline-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Telusuri Prodi
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide 3 - Sertifikat Akreditasi -->
                <div class="slider-slide">
                    <div class="relative h-64 md:h-80">
                        <div class="absolute inset-0 bg-gradient-to-r from-accent to-green-600"></div>
                        <div class="absolute inset-0 flex items-center">
                            <div class="container mx-auto px-8">
                                <div class="max-w-2xl text-white">
                                    <h2 class="text-3xl md:text-4xl font-bold mb-3">Sertifikat Akreditasi Terkini</h2>
                                    <p class="text-lg mb-6">Unduh sertifikat akreditasi institusi dan program studi
                                        dengan peringkat Unggul, Baik Sekali, dan Baik.</p>
                                    <a href="#"
                                        class="inline-block bg-white text-accent font-medium py-2 px-6 rounded-lg hover:bg-gray-100 transition-colors focus:outline focus:outline-2 focus:outline-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        Unduh Sertifikat
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide 4 - Laporan Evaluasi Diri -->
                <div class="slider-slide">
                    <div class="relative h-64 md:h-80">
                        <div class="absolute inset-0 bg-gradient-to-r from-green-600 to-primary"></div>
                        <div class="absolute inset-0 flex items-center">
                            <div class="container mx-auto px-8">
                                <div class="max-w-2xl text-white">
                                    <h2 class="text-3xl md:text-4xl font-bold mb-3">Laporan Evaluasi Diri</h2>
                                    <p class="text-lg mb-6">Dokumen lengkap Evaluasi Diri (LED) untuk keperluan
                                        akreditasi, sebagai bahan referensi peningkatan mutu.</p>
                                    <a href="#"
                                        class="inline-block bg-white text-green-600 font-medium py-2 px-6 rounded-lg hover:bg-gray-100 transition-colors focus:outline focus:outline-2 focus:outline-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Baca LED
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slider Navigation -->
            <button id="prevBtn"
                class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-80 hover:bg-opacity-100 text-primary w-10 h-10 rounded-full flex items-center justify-center focus:outline focus:outline-2 focus:outline-primary transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <button id="nextBtn"
                class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-80 hover:bg-opacity-100 text-primary w-10 h-10 rounded-full flex items-center justify-center focus:outline focus:outline-2 focus:outline-primary transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>

            <!-- Slider Indicators -->
            <div class="slider-indicators">
                <div class="slider-indicator active" data-index="0"></div>
                <div class="slider-indicator" data-index="1"></div>
                <div class="slider-indicator" data-index="2"></div>
                <div class="slider-indicator" data-index="3"></div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar Kategori Akreditasi -->
            <aside class="lg:w-1/4">
                <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                    <h2 class="text-lg font-bold text-primary mb-4 pb-2 border-b border-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                        </svg>
                        Kategori Dokumen Akreditasi
                    </h2>
                    <ul class="space-y-2">
                        <li class="rounded-lg hover:bg-gray-50 transition-colors">
                            <a href="#"
                                class="block p-3 text-gray-700 hover:text-primary focus:outline focus:outline-2 focus:rounded-sm focus:outline-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21v-2a4 4 0 00-4-4H9a4 4 0 00-4 4v2" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 11a4 4 0 100-8 4 4 0 000 8z" />
                                </svg>
                                Sertifikat Akreditasi
                            </a>
                        </li>
                        <li class="rounded-lg hover:bg-gray-50 transition-colors">
                            <a href="#"
                                class="block p-3 text-gray-700 hover:text-primary focus:outline focus:outline-2 focus:rounded-sm focus:outline-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                Borang Akreditasi
                            </a>
                        </li>
                        <li class="rounded-lg hover:bg-gray-50 transition-colors">
                            <a href="#"
                                class="block p-3 text-gray-700 hover:text-primary focus:outline focus:outline-2 focus:rounded-sm focus:outline-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                Sertifikat Akreditasi
                            </a>
                        </li>
                        <li class="rounded-lg hover:bg-gray-50 transition-colors">
                            <a href="#"
                                class="block p-3 text-gray-700 hover:text-primary focus:outline focus:outline-2 focus:rounded-sm focus:outline-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Laporan Evaluasi Diri (LED)
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Info Statistik Akreditasi -->
                <div class="bg-secondary text-white rounded-xl shadow-md p-6">
                    <h2 class="text-lg font-bold mb-4 pb-2 border-b border-green-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Statistik Akreditasi
                    </h2>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span>Total Dokumen Akreditasi</span>
                            <span class="font-bold">1.284</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span>Program Studi Terakreditasi</span>
                            <span class="font-bold">32</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span>Peringkat Unggul / A</span>
                            <span class="font-bold">12</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span>Peringkat Baik Sekali / B</span>
                            <span class="font-bold">18</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span>Sertifikat Aktif</span>
                            <span class="font-bold">27</span>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Konten Utama -->
            <div class="lg:w-3/4">
                <!-- Pencarian Dokumen Akreditasi -->
                {{-- <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                    <h1 class="text-2xl font-bold text-primary mb-2">Cari Dokumen Akreditasi</h1>
                    <p class="text-gray-600 mb-6">Temukan borang, sertifikat, LED, dan dokumen akreditasi program studi
                        atau institusi</p>

                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1 relative">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text"
                                placeholder="Cari berdasarkan nama prodi, tahun akreditasi, peringkat..."
                                class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent">
                        </div>
                        <select
                            class="border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent">
                            <option value="">Semua Kategori</option>
                            <option value="institusi">Akreditasi Institusi</option>
                            <option value="borang">Borang Akreditasi</option>
                            <option value="sertifikat">Sertifikat Akreditasi</option>
                            <option value="led">Laporan Evaluasi Diri</option>
                        </select>
                        <button
                            class="bg-primary hover:bg-primary-dark text-white font-medium py-3 px-6 rounded-lg transition-colors focus:outline focus:outline-2 focus:outline-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Cari
                        </button>
                    </div>
                </div> --}}

                <!-- Dokumen Akreditasi Terbaru - Tabel -->
                <div class="mb-8">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Dokumen Akreditasi Terbaru
                        </h2>
                        {{-- <a href="#"
                            class="text-secondary hover:text-primary font-medium focus:outline focus:outline-2 focus:rounded-sm focus:outline-primary">
                            Lihat Semua
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline ml-1" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </a> --}}
                    </div>

                 <!-- Pencarian Dokumen Akreditasi -->
<div class="bg-white rounded-xl shadow-md p-6 mb-8">
    <h1 class="text-2xl font-bold text-primary mb-2">Cari Dokumen Akreditasi</h1>
    <p class="text-gray-600 mb-6">Temukan borang, sertifikat, LED, dan dokumen akreditasi program studi atau institusi</p>

    <form method="GET" action="{{ url('/') }}" class="flex flex-col md:flex-row gap-4">
        <div class="flex-1 relative">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berdasarkan judul atau deskripsi..." class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent">
        </div>
        <button type="submit" class="bg-primary hover:bg-primary-dark text-white font-medium py-3 px-6 rounded-lg transition-colors focus:outline focus:outline-2 focus:outline-primary">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            Cari
        </button>
        @if(request('search'))
            <a href="{{ url('/') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-3 px-6 rounded-lg transition-colors">Reset</a>
        @endif
    </form>
</div>

<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tahun</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fakultas</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prodi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($arsipPublik as $arsip)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $arsip->judul }}</div>
                            <div class="text-sm text-gray-500 line-clamp-1">{{ $arsip->deskripsi ?: '-' }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $arsip->created_at->format('Y') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $arsip->fakultas?->nama_fakultas ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $arsip->prodi?->nama_prodi ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm font-medium">
                            {{-- <a href="{{ route('arsip.show', $arsip->id) }}" class="text-accent hover:text-primary">Lihat</a> --}}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            Belum ada arsip publik.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination Links -->
<div class="mt-6">
    {{ $arsipPublik->appends(['search' => request('search')])->links() }}
</div>

                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-primary text-white mt-12">
        <div class="container mx-auto px-4 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Tentang -->
                <div>
                    <div class="flex items-center mb-4">
                        <div class="logo-container mr-3">
                            <img class="footer-logo"
                                src="https://uml.ac.id/web/Universitas%20Muhammadiyah%20Lampung_1755961227.png"
                                alt="Logo Universitas Muhammadiyah Lampung">
                        </div>
                        <h3 class="font-bold text-lg">Arsip Akreditasi</h3>
                    </div>
                    <p class="text-sm text-gray-300 mb-4">
                        Sistem arsip digital khusus dokumen akreditasi institusi dan program studi Universitas
                        Muhammadiyah Lampung. Menyediakan borang, sertifikat, LED, dan perangkat akreditasi lainnya.
                    </p>
                </div>

                <!-- Kontak -->
                <div>
                    <h4 class="font-bold text-lg mb-4">Kontak Layanan Akreditasi</h4>
                    <ul class="space-y-3 text-sm text-gray-300">
                        <li class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 mt-1 text-accent"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Jl. Hi. Zainal Abidin Pagar Alam No.20, Bandar Lampung
                        </li>
                        <li class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-accent" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            (0721) 123456
                        </li>
                        <li class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-accent" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            akreditasi@uml.ac.id
                        </li>
                    </ul>
                </div>

                <!-- Newsletter -->
                <div>
                    <h4 class="font-bold text-lg mb-4">Info Akreditasi Terkini</h4>
                    <p class="text-sm text-gray-300 mb-4">Dapatkan pemberitahuan terbaru tentang dokumen akreditasi dan
                        jadwal reakreditasi.</p>
                    <div class="flex">
                        <input type="email" placeholder="Email Anda"
                            class="flex-1 px-4 py-2 text-gray-800 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-accent">
                        <button
                            class="bg-accent hover:bg-green-600 px-4 py-2 rounded-r-lg focus:outline focus:outline-2 focus:outline-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="border-t border-green-800 mt-8 pt-6 text-center text-sm text-gray-400">
                <p>&copy; {{ date('Y') }} Universitas Muhammadiyah Lampung. Hak Cipta Dilindungi. Arsip Akreditasi
                    Resmi.</p>
            </div>
        </div>
    </footer>

    <!-- Tombol Kembali ke Atas -->
    <button id="backToTop"
        class="fixed bottom-6 right-6 bg-primary hover:bg-primary-dark text-white p-3 rounded-full shadow-lg transition-all opacity-0 focus:outline focus:outline-2 focus:outline-primary">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
        </svg>
    </button>

    <script>
        // Slider Functionality
        const sliderTrack = document.getElementById('sliderTrack');
        const slides = document.querySelectorAll('.slider-slide');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const indicators = document.querySelectorAll('.slider-indicator');

        let currentSlide = 0;
        const totalSlides = slides.length;

        function updateSlider() {
            sliderTrack.style.transform = `translateX(-${currentSlide * 100}%)`;
            indicators.forEach((indicator, index) => {
                if (index === currentSlide) {
                    indicator.classList.add('active');
                } else {
                    indicator.classList.remove('active');
                }
            });
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % totalSlides;
            updateSlider();
        }

        function prevSlide() {
            currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
            updateSlider();
        }

        let slideInterval = setInterval(nextSlide, 5000);

        nextBtn.addEventListener('click', () => {
            nextSlide();
            clearInterval(slideInterval);
            slideInterval = setInterval(nextSlide, 5000);
        });

        prevBtn.addEventListener('click', () => {
            prevSlide();
            clearInterval(slideInterval);
            slideInterval = setInterval(nextSlide, 5000);
        });

        indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => {
                currentSlide = index;
                updateSlider();
                clearInterval(slideInterval);
                slideInterval = setInterval(nextSlide, 5000);
            });
        });

        // Back to top button
        const backToTopButton = document.getElementById('backToTop');

        window.addEventListener('scroll', () => {
            if (window.scrollY > 300) {
                backToTopButton.classList.remove('opacity-0');
                backToTopButton.classList.add('opacity-100');
            } else {
                backToTopButton.classList.remove('opacity-100');
                backToTopButton.classList.add('opacity-0');
            }
        });

        backToTopButton.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Hover effect for cards (tidak dipakai karena sudah tidak ada card, tapi tetap aman)
        const cards = document.querySelectorAll('.card-hover');
        cards.forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-5px)';
            });
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0)';
            });
        });
    </script>
</body>

</html>
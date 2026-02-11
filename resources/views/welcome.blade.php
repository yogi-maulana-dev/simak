<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Arsip Digital - Universitas Muhammadiyah Lampung</title>
    
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
                        <img class="logo-img" src="https://uml.ac.id/web/Universitas%20Muhammadiyah%20Lampung_1755961227.png" alt="Logo Universitas Muhammadiyah Lampung">
                    </div>
                    <div>
                        <h1 class="text-xl font-bold">ARSIP DIGITAL</h1>
                        <p class="text-sm text-gray-200">Universitas Muhammadiyah Lampung</p>
                    </div>
                </div>
                
                <!-- Menu Navigasi -->
                <nav class="flex flex-wrap justify-center gap-4 md:gap-6 mb-4 md:mb-0">
                    <a href="#" class="font-medium hover:text-gray-200 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Beranda
                    </a>
                    <a href="#" class="font-medium hover:text-gray-200 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        Arsip
                    </a>
                    <a href="#" class="font-medium hover:text-gray-200 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        Koleksi
                    </a>
                    <a href="#" class="font-medium hover:text-gray-200 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Tentang
                    </a>
                </nav>
                
                <!-- Login Section - Laravel Blade Integration -->
                @if (Route::has('login'))
                    <div class="mt-4 md:mt-0 text-right z-10">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="dashboard-button focus:outline focus:outline-2 focus:rounded-sm focus:outline-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="login-button focus:outline focus:outline-2 focus:rounded-sm focus:outline-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                </svg>
                                Log in
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="register-button focus:outline focus:outline-2 focus:rounded-sm focus:outline-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
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
                <!-- Slide 1 -->
                <div class="slider-slide">
                    <div class="relative h-64 md:h-80">
                        <div class="absolute inset-0 bg-gradient-to-r from-primary to-secondary"></div>
                        <div class="absolute inset-0 flex items-center">
                            <div class="container mx-auto px-8">
                                <div class="max-w-2xl text-white">
                                    <h2 class="text-3xl md:text-4xl font-bold mb-3">Sistem Arsip Digital</h2>
                                    <p class="text-lg mb-6">Temukan ribuan dokumen akademik, penelitian, dan publikasi dari Universitas Muhammadiyah Lampung dalam satu platform terintegrasi.</p>
                                    <a href="#" class="inline-block bg-white text-primary font-medium py-2 px-6 rounded-lg hover:bg-gray-100 transition-colors focus:outline focus:outline-2 focus:outline-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                        Jelajahi Arsip
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Slide 2 -->
                <div class="slider-slide">
                    <div class="relative h-64 md:h-80">
                        <div class="absolute inset-0 bg-gradient-to-r from-secondary to-accent"></div>
                        <div class="absolute inset-0 flex items-center">
                            <div class="container mx-auto px-8">
                                <div class="max-w-2xl text-white">
                                    <h2 class="text-3xl md:text-4xl font-bold mb-3">Koleksi Skripsi & Tesis</h2>
                                    <p class="text-lg mb-6">Akses lebih dari 8.700 karya ilmiah mahasiswa sebagai referensi penelitian dan akademik Anda.</p>
                                    <a href="#" class="inline-block bg-white text-secondary font-medium py-2 px-6 rounded-lg hover:bg-gray-100 transition-colors focus:outline focus:outline-2 focus:outline-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                        </svg>
                                        Lihat Koleksi
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Slide 3 -->
                <div class="slider-slide">
                    <div class="relative h-64 md:h-80">
                        <div class="absolute inset-0 bg-gradient-to-r from-accent to-green-600"></div>
                        <div class="absolute inset-0 flex items-center">
                            <div class="container mx-auto px-8">
                                <div class="max-w-2xl text-white">
                                    <h2 class="text-3xl md:text-4xl font-bold mb-3">Jurnal Ilmiah Terkini</h2>
                                    <p class="text-lg mb-6">Temukan publikasi penelitian terbaru dari dosen dan peneliti Universitas Muhammadiyah Lampung.</p>
                                    <a href="#" class="inline-block bg-white text-accent font-medium py-2 px-6 rounded-lg hover:bg-gray-100 transition-colors focus:outline focus:outline-2 focus:outline-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                        </svg>
                                        Baca Jurnal
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Slide 4 -->
                <div class="slider-slide">
                    <div class="relative h-64 md:h-80">
                        <div class="absolute inset-0 bg-gradient-to-r from-green-600 to-primary"></div>
                        <div class="absolute inset-0 flex items-center">
                            <div class="container mx-auto px-8">
                                <div class="max-w-2xl text-white">
                                    <h2 class="text-3xl md:text-4xl font-bold mb-3">Unggah Karya Ilmiah</h2>
                                    <p class="text-lg mb-6">Dosen dan mahasiswa dapat mengunggah karya ilmiah untuk diarsipkan secara digital dan dibaca oleh publik.</p>
                                    <a href="#" class="inline-block bg-white text-green-600 font-medium py-2 px-6 rounded-lg hover:bg-gray-100 transition-colors focus:outline focus:outline-2 focus:outline-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                                        </svg>
                                        Unggah Sekarang
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Slider Navigation -->
            <button id="prevBtn" class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-80 hover:bg-opacity-100 text-primary w-10 h-10 rounded-full flex items-center justify-center focus:outline focus:outline-2 focus:outline-primary transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <button id="nextBtn" class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-80 hover:bg-opacity-100 text-primary w-10 h-10 rounded-full flex items-center justify-center focus:outline focus:outline-2 focus:outline-primary transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
            <!-- Sidebar Kategori -->
            <aside class="lg:w-1/4">
                <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                    <h2 class="text-lg font-bold text-primary mb-4 pb-2 border-b border-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                        </svg>
                        Kategori Arsip
                    </h2>
                    <ul class="space-y-2">
                        <li class="rounded-lg hover:bg-gray-50 transition-colors">
                            <a href="#" class="block p-3 text-gray-700 hover:text-primary focus:outline focus:outline-2 focus:rounded-sm focus:outline-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                </svg>
                                Skripsi & Tesis
                            </a>
                        </li>
                        <li class="rounded-lg hover:bg-gray-50 transition-colors">
                            <a href="#" class="block p-3 text-gray-700 hover:text-primary focus:outline focus:outline-2 focus:rounded-sm focus:outline-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                </svg>
                                Jurnal Ilmiah
                            </a>
                        </li>
                        <li class="rounded-lg hover:bg-gray-50 transition-colors">
                            <a href="#" class="block p-3 text-gray-700 hover:text-primary focus:outline focus:outline-2 focus:rounded-sm focus:outline-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                Buku & Modul
                            </a>
                        </li>
                        <li class="rounded-lg hover:bg-gray-50 transition-colors">
                            <a href="#" class="block p-3 text-gray-700 hover:text-primary focus:outline focus:outline-2 focus:rounded-sm focus:outline-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Laporan Penelitian
                            </a>
                        </li>
                    </ul>
                </div>
                
                <!-- Info Statistik -->
                <div class="bg-secondary text-white rounded-xl shadow-md p-6">
                    <h2 class="text-lg font-bold mb-4 pb-2 border-b border-green-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Statistik Arsip
                    </h2>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span>Total Dokumen</span>
                            <span class="font-bold">12.458</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span>Skripsi/Tesis</span>
                            <span class="font-bold">8.742</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span>Jurnal Ilmiah</span>
                            <span class="font-bold">2.156</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span>Buku & Modul</span>
                            <span class="font-bold">1.560</span>
                        </div>
                    </div>
                </div>
            </aside>
            
            <!-- Konten Utama -->
            <div class="lg:w-3/4">
                <!-- Pencarian -->
                <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                    <h1 class="text-2xl font-bold text-primary mb-2">Cari Arsip Digital</h1>
                    <p class="text-gray-600 mb-6">Temukan dokumen akademik, penelitian, dan publikasi dari Universitas Muhammadiyah Lampung</p>
                    
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1 relative">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text" placeholder="Kata kunci, judul, penulis, atau tahun..." class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent">
                        </div>
                        <select class="border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent">
                            <option value="">Semua Kategori</option>
                            <option value="skripsi">Skripsi & Tesis</option>
                            <option value="jurnal">Jurnal Ilmiah</option>
                            <option value="buku">Buku & Modul</option>
                        </select>
                        <button class="bg-primary hover:bg-primary-dark text-white font-medium py-3 px-6 rounded-lg transition-colors focus:outline focus:outline-2 focus:outline-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Cari
                        </button>
                    </div>
                </div>
                
                <!-- Arsip Terbaru -->
                <div class="mb-8">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Arsip Terbaru
                        </h2>
                        <a href="#" class="text-secondary hover:text-primary font-medium focus:outline focus:outline-2 focus:rounded-sm focus:outline-primary">Lihat Semua 
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Card Arsip 1 -->
                        <div class="bg-white rounded-xl shadow-md overflow-hidden card-hover">
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <span class="bg-green-100 text-secondary text-xs font-medium px-3 py-1 rounded-full">Skripsi</span>
                                    <span class="text-gray-500 text-sm">2023</span>
                                </div>
                                <h3 class="font-bold text-lg text-gray-800 mb-2">Pengaruh Pembelajaran Daring terhadap Hasil Belajar Mahasiswa</h3>
                                <p class="text-gray-600 text-sm mb-4">Penelitian mengenai efektivitas pembelajaran daring di masa pandemi pada mahasiswa Fakultas Keguruan dan Ilmu Pendidikan.</p>
                                <div class="flex justify-between items-center text-sm text-gray-500">
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        Ahmad Fauzi
                                    </span>
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        245
                                    </span>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-6 py-3 border-t border-gray-100">
                                <a href="#" class="text-accent hover:text-primary font-medium text-sm focus:outline focus:outline-2 focus:rounded-sm focus:outline-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                                    </svg>
                                    Unduh Dokumen
                                </a>
                            </div>
                        </div>
                        
                        <!-- Card Arsip 2 -->
                        <div class="bg-white rounded-xl shadow-md overflow-hidden card-hover">
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <span class="bg-green-100 text-secondary text-xs font-medium px-3 py-1 rounded-full">Jurnal</span>
                                    <span class="text-gray-500 text-sm">2023</span>
                                </div>
                                <h3 class="font-bold text-lg text-gray-800 mb-2">Analisis Ekonomi Syariah di Era Digital</h3>
                                <p class="text-gray-600 text-sm mb-4">Studi tentang perkembangan ekonomi syariah dalam menghadapi transformasi digital di Indonesia.</p>
                                <div class="flex justify-between items-center text-sm text-gray-500">
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        Dr. Siti Aminah
                                    </span>
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        189
                                    </span>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-6 py-3 border-t border-gray-100">
                                <a href="#" class="text-accent hover:text-primary font-medium text-sm focus:outline focus:outline-2 focus:rounded-sm focus:outline-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                                    </svg>
                                    Unduh Dokumen
                                </a>
                            </div>
                        </div>
                        
                        <!-- Card Arsip 3 -->
                        <div class="bg-white rounded-xl shadow-md overflow-hidden card-hover">
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <span class="bg-green-100 text-secondary text-xs font-medium px-3 py-1 rounded-full">Laporan</span>
                                    <span class="text-gray-500 text-sm">2022</span>
                                </div>
                                <h3 class="font-bold text-lg text-gray-800 mb-2">Implementasi Teknologi Informasi dalam Administrasi Kampus</h3>
                                <p class="text-gray-600 text-sm mb-4">Laporan penelitian tentang efektivitas sistem informasi dalam meningkatkan pelayanan administrasi di lingkungan kampus.</p>
                                <div class="flex justify-between items-center text-sm text-gray-500">
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        Tim IT Kampus
                                    </span>
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        312
                                    </span>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-6 py-3 border-t border-gray-100">
                                <a href="#" class="text-accent hover:text-primary font-medium text-sm focus:outline focus:outline-2 focus:rounded-sm focus:outline-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                                    </svg>
                                    Unduh Dokumen
                                </a>
                            </div>
                        </div>
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
                            <img class="footer-logo" src="https://uml.ac.id/web/Universitas%20Muhammadiyah%20Lampung_1755961227.png" alt="Logo Universitas Muhammadiyah Lampung">
                        </div>
                        <h3 class="font-bold text-lg">Arsip Digital</h3>
                    </div>
                    <p class="text-sm text-gray-300 mb-4">
                        Sistem arsip digital Universitas Muhammadiyah Lampung yang menyimpan karya ilmiah, penelitian, dan publikasi akademik.
                    </p>
                </div>
                
                <!-- Kontak -->
                <div>
                    <h4 class="font-bold text-lg mb-4">Kontak Kami</h4>
                    <ul class="space-y-3 text-sm text-gray-300">
                        <li class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 mt-1 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Jl. Hi. Zainal Abidin Pagar Alam No.20, Bandar Lampung
                        </li>
                        <li class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            (0721) 123456
                        </li>
                        <li class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            arsip@uml.ac.id
                        </li>
                    </ul>
                </div>
                
                <!-- Newsletter -->
                <div>
                    <h4 class="font-bold text-lg mb-4">Berlangganan Info</h4>
                    <p class="text-sm text-gray-300 mb-4">Dapatkan informasi terbaru tentang arsip dan publikasi kampus.</p>
                    <div class="flex">
                        <input type="email" placeholder="Email Anda" class="flex-1 px-4 py-2 text-gray-800 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-accent">
                        <button class="bg-accent hover:bg-green-600 px-4 py-2 rounded-r-lg focus:outline focus:outline-2 focus:outline-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-green-800 mt-8 pt-6 text-center text-sm text-gray-400">
                <p>&copy; {{ date('Y') }} Universitas Muhammadiyah Lampung. Hak Cipta Dilindungi.</p>
            </div>
        </div>
    </footer>

    <!-- Tombol Kembali ke Atas -->
    <button id="backToTop" class="fixed bottom-6 right-6 bg-primary hover:bg-primary-dark text-white p-3 rounded-full shadow-lg transition-all opacity-0 focus:outline focus:outline-2 focus:outline-primary">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
        
        // Update slider position
        function updateSlider() {
            sliderTrack.style.transform = `translateX(-${currentSlide * 100}%)`;
            
            // Update indicators
            indicators.forEach((indicator, index) => {
                if (index === currentSlide) {
                    indicator.classList.add('active');
                } else {
                    indicator.classList.remove('active');
                }
            });
        }
        
        // Next slide
        function nextSlide() {
            currentSlide = (currentSlide + 1) % totalSlides;
            updateSlider();
        }
        
        // Previous slide
        function prevSlide() {
            currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
            updateSlider();
        }
        
        // Auto slide every 5 seconds
        let slideInterval = setInterval(nextSlide, 5000);
        
        // Event listeners
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
        
        // Indicator click
        indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => {
                currentSlide = index;
                updateSlider();
                clearInterval(slideInterval);
                slideInterval = setInterval(nextSlide, 5000);
            });
        });
        
        // Tombol kembali ke atas
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
        
        // Efek hover untuk kartu
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
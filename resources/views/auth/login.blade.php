<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Bearing Shop') }} - Login</title>

    <!-- Font -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet"
        integrity="sha512-Avb2QiuDEEvB4bZJYdft2mNjVShBftLdPG8FJ0V7irTLQ8Uo0qcPxh4Plq7G5tGm0rU+1SPhVotteLpBERwTkw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Custom CSS: Hide Browser Default Password Icons -->
    <style>
        /* Chrome, Safari, Edge */
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear {
            display: none !important;
        }

        /* Firefox */
        input[type="password"]::-moz-reveal {
            display: none !important;
        }

        /* Chrome/Safari */
        input[type="password"]::-webkit-contacts-auto-fill-button {
            display: none !important;
        }

        input[type="password"]::-webkit-credentials-auto-fill-button {
            display: none !important;
        }
    </style>

    <!-- Skrip -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full bg-linear-to-br from-primary-50 via-white to-gray-50">
    <div class="min-h-full flex">
        <!-- Sisi Kiri - Gambar/Branding -->
        <div
            class="hidden lg:flex lg:w-1/2 bg-linear-to-br from-primary-600 via-primary-700 to-primary-900 relative overflow-hidden">
            <!-- Elemen Dekoratif -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-20 left-20 w-72 h-72 bg-white rounded-full blur-3xl"></div>
                <div class="absolute bottom-20 right-20 w-96 h-96 bg-white rounded-full blur-3xl"></div>
            </div>

            <!-- Konten -->
            <div class="relative z-10 flex flex-col justify-center px-12 text-white">
                <!-- Logo & Brand -->
                <div class="mb-8">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-16 h-16 bg-white rounded-xl flex items-center justify-center shadow-xl">
                            <i class="fas fa-cog text-primary-600 text-3xl"></i>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold">Bearing Shop</h1>
                            <p class="text-primary-200 text-sm">Premium Quality Bearings</p>
                        </div>
                    </div>
                </div>

                <!-- Fitur -->
                <div class="space-y-6 max-w-md">
                    <div class="flex items-start space-x-4">
                        <div
                            class="w-12 h-12 bg-primary-500 bg-opacity-30 rounded-lg flex items-center justify-center shrink-0">
                            <i class="fas fa-shipping-fast text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg mb-1">Pengiriman Cepat</h3>
                            <p class="text-primary-100 text-sm">Pengiriman ke seluruh Indonesia dengan jaminan aman dan
                                cepat</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4">
                        <div
                            class="w-12 h-12 bg-primary-500 bg-opacity-30 rounded-lg flex items-center justify-center shrink-0">
                            <i class="fas fa-shield-alt text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg mb-1">Produk Original</h3>
                            <p class="text-primary-100 text-sm">100% produk bearing original dari brand ternama dunia
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4">
                        <div
                            class="w-12 h-12 bg-primary-500 bg-opacity-30 rounded-lg flex items-center justify-center shrink-0">
                            <i class="fas fa-headset text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg mb-1">Customer Support 24/7</h3>
                            <p class="text-primary-100 text-sm">Tim support siap membantu Anda kapan saja</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4">
                        <div
                            class="w-12 h-12 bg-primary-500 bg-opacity-30 rounded-lg flex items-center justify-center shrink-0">
                            <i class="fas fa-tags text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg mb-1">Harga Kompetitif</h3>
                            <p class="text-primary-100 text-sm">Dapatkan harga terbaik untuk bearing berkualitas tinggi
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Statistik -->
                <div class="grid grid-cols-3 gap-6 mt-12 pt-12 border-t border-primary-500 border-opacity-30">
                    <div>
                        <div class="text-3xl font-bold mb-1">5000+</div>
                        <div class="text-primary-200 text-sm">Produk Bearing</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold mb-1">10K+</div>
                        <div class="text-primary-200 text-sm">Pelanggan Puas</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold mb-1">24/7</div>
                        <div class="text-primary-200 text-sm">Support</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sisi Kanan - Form -->
        <div class="flex-1 flex flex-col justify-center px-6 py-12 lg:px-20 xl:px-24">
            <!-- Logo Mobile -->
            <div class="lg:hidden mb-8 text-center">
                <div class="inline-flex items-center space-x-3">
                    <div class="w-12 h-12 bg-primary-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-cog text-white text-2xl"></i>
                    </div>
                    <div class="text-left">
                        <h1 class="text-2xl font-bold text-gray-900">Bearing Shop</h1>
                        <p class="text-gray-600 text-xs">Premium Quality Bearings</p>
                    </div>
                </div>
            </div>

            <!-- Konten Form -->
            <div class="mx-auto w-full max-w-md">
                <div class="bg-white rounded-2xl shadow-xl p-8 lg:p-10">
                    <!-- Header -->
                    <div class="mb-8">
                        <h2 class="text-3xl font-bold text-gray-900 mb-2">Selamat Datang Kembali!</h2>
                        <p class="text-gray-600">Masuk ke akun Anda untuk melanjutkan belanja bearing</p>
                    </div>

                    <!-- Pesan Error -->
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-exclamation-circle text-red-600 mr-2"></i>
                                <span class="text-red-800 font-medium text-sm">Terjadi kesalahan:</span>
                            </div>
                            <ul class="text-red-700 text-sm space-y-1 ml-6">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-green-600 mr-2"></i>
                                <span class="text-green-800 text-sm">{{ session('success') }}</span>
                            </div>
                        </div>
                    @endif

                    <!-- Form Login -->
                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf

                        <!-- Alamat Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-envelope mr-2 text-gray-400"></i>Email
                            </label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                                autocomplete="username"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                                placeholder="contoh@email.com">
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-lock mr-2 text-gray-400"></i>Password
                            </label>
                            <div class="relative">
                                <input id="password" type="password" name="password" required
                                    autocomplete="current-password"
                                    class="w-full px-4 py-3 pr-10 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all [&::-ms-reveal]:hidden [&::-ms-clear]:hidden"
                                    placeholder="Masukkan password Anda">
                                <button type="button" onclick="togglePassword()"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-eye" id="toggleIcon"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Ingat Saya & Lupa Password -->
                        <div class="flex items-center justify-between">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="remember"
                                    class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500 focus:ring-2">
                                <span class="ml-2 text-sm text-gray-700">Ingat saya</span>
                            </label>

                            <a href="{{ route('password.request') }}"
                                class="text-sm text-primary-600 hover:text-primary-700 font-medium hover:underline">
                                Lupa password?
                            </a>
                        </div>

                        <!-- Tombol Submit -->
                        <button type="submit"
                            class="w-full bg-primary-600 text-white py-3 rounded-lg font-medium hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                            <i class="fas fa-sign-in-alt mr-2"></i>Masuk
                        </button>

                        <!-- Pembatas -->
                        <div class="relative my-6">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-300"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-4 bg-white text-gray-500">Atau</span>
                            </div>
                        </div>

                        <!-- Link Daftar -->
                        <div class="text-center">
                            <p class="text-sm text-gray-600">
                                Belum punya akun?
                                <a href="{{ route('register') }}"
                                    class="text-primary-600 hover:text-primary-700 font-medium hover:underline ml-1">
                                    Daftar sekarang
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
                <footer class="mt-8 text-center text-sm text-gray-600 pb-6">
                    <p>&copy; {{ date('Y') }} Bearing Shop. All rights reserved.</p>
                </footer>
            </div>
        </div>
    </div>
</body>
<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }

    // Prefill email from localStorage if exists
    document.addEventListener('DOMContentLoaded', function () {
        const savedEmail = localStorage.getItem('remembered_email');
        if (savedEmail && !document.getElementById('email').value) {
            document.getElementById('email').value = savedEmail;
        }

        // Save email to localStorage when remember is checked and form is submitted
        document.querySelector('form').addEventListener('submit', function () {
            const rememberChecked = document.querySelector('input[name="remember"]').checked;
            const emailValue = document.getElementById('email').value;
            if (rememberChecked && emailValue) {
                localStorage.setItem('remembered_email', emailValue);
            } else {
                localStorage.removeItem('remembered_email');
            }
        });
    });
</script>

</html>
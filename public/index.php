<?php
require_once __DIR__ . '/auth_check.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Dashboard Aset Harian</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
    
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #fff1f2;
    }
    
    .navbar {
      background: linear-gradient(135deg, #fbcfe8 0%, #f9a8d4 100%);
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    
    .logo {
      transition: all 0.3s ease;
    }
    
    .logo:hover {
      transform: rotate(-10deg) scale(1.1);
    }
    
    .nav-links a {
      transition: all 0.3s ease;
      position: relative;
    }
    
    .nav-links a::after {
      content: '';
      position: absolute;
      width: 0;
      height: 2px;
      bottom: 0;
      left: 0;
      background-color: #ec4899;
      transition: width 0.3s ease;
    }
    
    .nav-links a:hover::after {
      width: 100%;
    }
    
    .nav-links a.active {
      font-weight: 500;
    }
    
    .nav-links a.active::after {
      width: 100%;
    }
    
    .btn-lengkap {
      transition: all 0.3s ease;
      transform-origin: left center;
    }
    
    .btn-lengkap:hover {
      transform: scale(1.05);
    }
    
    .error-box {
      transition: all 0.3s ease;
    }
    
    .mobile-menu {
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.3s ease-out;
    }
    
    .mobile-menu.open {
      max-height: 500px;
      transition: max-height 0.5s ease-in;
    }
    
    @keyframes pulse {
      0%, 100% {
        opacity: 0.6;
      }
      50% {
        opacity: 1;
      }
    }
  </style>
</head>
<body class="min-h-screen flex flex-col">
  <!-- Header -->
  <header class="sticky top-0 z-50">
    <div class="navbar text-pink-900">
      <div class="container mx-auto px-6 py-3 flex justify-between items-center">
        <div class="brand flex items-center space-x-3">
          <h1 class="text-2xl font-bold">Vermont Store</h1>
        </div>
        
        <!-- Desktop Navigation -->
        <nav class="nav-links hidden md:flex items-center space-x-6">
          <a href="index.php" class="flex items-center space-x-1 text-pink-700 active">
            <span>📊</span>
            <span>Dashboard</span>
          </a>
          <a href="tambah_transaksi.php" class="flex items-center space-x-1 hover:text-pink-700">
            <span>💰</span>
            <span>Transaksi</span>
          </a>
          <a href="tambah_hutang.php" class="flex items-center space-x-1 hover:text-pink-700">
            <span>📋</span>
            <span>Hutang</span>
          </a>
          <a href="tambah_saldo.php" class="flex items-center space-x-1 hover:text-pink-700">
            <span>💳</span>
            <span>Saldo</span>
          </a>
          <a href="tambah_penjualan.php" class="flex items-center space-x-1 hover:text-pink-700">
            <span>🛒</span>
            <span>Penjualan</span>
          </a>
          <a href="logout.php" class="flex items-center space-x-1 text-red-600 font-semibold hover:text-red-800">
    🚪 Logout
</a>

        </nav>
        
        <!-- Mobile Menu Button -->
        <button id="mobileMenuButton" class="md:hidden text-2xl focus:outline-none">
          ☰
        </button>
      </div>
      
      <!-- Mobile Navigation -->
      <div id="mobileMenu" class="mobile-menu md:hidden bg-pink-50">
        <div class="container mx-auto px-6 py-3 flex flex-col space-y-4">
          <a href="index.php" class="flex items-center space-x-2 text-pink-700 active py-2">
            <span>📊</span>
            <span>Dashboard</span>
          </a>
          <a href="tambah_transaksi.php" class="flex items-center space-x-2 hover:text-pink-700 py-2">
            <span>💰</span>
            <span>Transaksi</span>
          </a>
          <a href="tambah_hutang.php" class="flex items-center space-x-2 hover:text-pink-700 py-2">
            <span>📋</span>
            <span>Hutang</span>
          </a>
          <a href="tambah_saldo.php" class="flex items-center space-x-2 hover:text-pink-700 py-2">
            <span>💳</span>
            <span>Saldo</span>
          </a>
          <a href="tambah_penjualan.php" class="flex items-center space-x-2 hover:text-pink-700 py-2">
            <span>🛒</span>
            <span>Penjualan</span>
          </a>
          <a href="logout.php" class="flex items-center space-x-2 py-2 text-red-600 font-medium hover:text-red-800">
    <span>🚪</span>
    <span>Logout</span>
</a>

        </div>
      </div>
    </div>
  </header>

  <!-- Main Content -->
  <main class="container mx-auto px-6 py-8 flex-grow">
    <h2 class="text-3xl font-bold text-pink-800 mb-8 animate__animated animate__fadeIn">💰 Dashboard Aset</h2>

    <div id="aset" class="bg-white rounded-xl shadow-md p-6 mb-8 animate__animated animate__fadeInUp">
      <div class="space-y-4">
        <p class="text-lg">
          <strong class="text-pink-700">Saldo Digital:</strong> 
          <span id="saldo" class="font-medium text-green-600 animate-pulse">Loading...</span>
        </p>
        <p class="text-lg">
          <strong class="text-pink-700">Piutang:</strong> 
          <span id="piutang" class="font-medium text-blue-600 animate-pulse">Loading...</span>
        </p>
        <p class="text-lg">
          <strong class="text-pink-700">Total Aset:</strong> 
          <span id="total" class="font-medium text-purple-600 animate-pulse">Loading...</span>
        </p>
      </div>
    </div>

    <!-- Error box (hidden by default) -->
    <div id="error" class="error-box bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow-md hidden animate__animated animate__fadeIn">
      <p>Gagal mengambil data dari server.</p>
    </div>

    <!-- Tombol Navigasi -->
    <div class="button-container grid grid-cols-1 md:grid-cols-3 gap-4 animate__animated animate__fadeInUp">
      <a href="list_penjualan.php" class="btn-lengkap flex items-center justify-center px-4 py-3 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition-all duration-300">
        <span class="mr-2">📋</span> Lihat Daftar Penjualan
      </a>
      <a href="list_hutang.php" class="btn-lengkap flex items-center justify-center px-4 py-3 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition-all duration-300">
        <span class="mr-2">➡️</span> Lihat Daftar Hutang
      </a>
      <a href="daftar_transaksi.php" class="btn-lengkap flex items-center justify-center px-4 py-3 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition-all duration-300">
        <span class="mr-2">💰</span> Lihat Daftar Transaksi
      </a>
      <a href="daftar_saldo.php" class="btn-lengkap flex items-center justify-center px-4 py-3 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition-all duration-300">
        <span class="mr-2">💳</span> Lihat Daftar Saldo
      </a>
      <a href="akun.php" class="btn-lengkap flex items-center justify-center px-4 py-3 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition-all duration-300">
    <span class="mr-2">👥</span> Lihat Daftar Akun
</a>

    </div>
  </main>

  <!-- Footer -->
  <footer class="mt-auto">
    <div class="container mx-auto px-6 py-4 text-center text-pink-900">
      <p>&copy; 2025 Vermont Store • All Rights Reserved</p>
    </div>
  </footer>

  <!-- JS -->
  <script>
    // Mobile menu toggle
    document.getElementById('mobileMenuButton').addEventListener('click', function() {
      const mobileMenu = document.getElementById('mobileMenu');
      mobileMenu.classList.toggle('open');
      
      // Change icon based on menu state
      if (mobileMenu.classList.contains('open')) {
        this.textContent = '✕';
      } else {
        this.textContent = '☰';
      }
    });

    // Close mobile menu when clicking on a link
    document.querySelectorAll('#mobileMenu a').forEach(link => {
      link.addEventListener('click', function() {
        document.getElementById('mobileMenu').classList.remove('open');
        document.getElementById('mobileMenuButton').textContent = '☰';
      });
    });
  </script>
  <script src="assets/js/dashboard.js"></script>
</body>
</html>
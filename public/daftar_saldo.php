<?php
require_once __DIR__ . '/auth_check.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Saldo Digital - Vermont Store</title>
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
      color: #831843;
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
      color: #831843;
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
    
    .table-container {
      animation: fadeInUp 0.5s ease;
    }
    
    #saldoTable {
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
      width: calc(100% - 2rem);
      margin: 0 auto;
      background-color: white;
      border-radius: 0.5rem;
      overflow: hidden;
    }
    
    #saldoTable th {
      background-color: #fbcfe8;
      font-weight: 600;
      letter-spacing: 0.5px;
      padding: 1rem 1.25rem;
      color: #831843;
    }
    
    #saldoTable tr {
      transition: all 0.3s ease;
    }
    
    #saldoTable tr:not(:last-child) {
      border-bottom: 1px solid #fce7f3;
    }
    
    #saldoTable tr:hover {
      background-color: #fdf2f8;
      transform: translateX(3px);
      box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    #saldoTable td {
      padding: 1rem 1.25rem;
      vertical-align: middle;
      color: #831843;
    }
    
    .badge {
      display: inline-block;
      padding: 0.35rem 0.65rem;
      border-radius: 9999px;
      font-size: 0.75rem;
      font-weight: 600;
      text-transform: capitalize;
    }
    
    .badge-gopay {
      background-color: #E5F7EF;
      color: #00AA5B;
    }
    
    .badge-qris {
      background-color: #E5F0FF;
      color: #0066CC;
    }
    
    .badge-dana {
      background-color: #F0E5FF;
      color: #6E00CC;
    }
    
    .badge-spay {
      background-color: #FFE5E5;
      color: #CC0000;
    }
    
    .badge-ovo {
      background-color: #FFF2E5;
      color: #CC5C00;
    }
    
    .badge-bca {
      background-color: #E5F7F7;
      color: #006B6B;
    }
    
    .loading-row {
      animation: pulse 2s infinite;
    }
    
    .empty-state {
      animation: fadeIn 1s ease;
    }
    
    .empty-state-icon {
      animation: bounce 2s infinite;
    }
    
    footer {
      background: linear-gradient(135deg, #f9a8d4 0%, #fbcfe8 100%);
      box-shadow: 0 -4px 6px -1px rgba(0, 0, 0, 0.1), 0 -2px 4px -1px rgba(0, 0, 0, 0.06);
      color: #831843;
    }
    
    .mobile-menu {
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.3s ease-out;
      background: linear-gradient(135deg, #fbcfe8 0%, #f9a8d4 100%);
    }
    
    .mobile-menu.open {
      max-height: 500px;
      transition: max-height 0.5s ease-in;
    }
    
    .mobile-menu-button {
      display: none;
      cursor: pointer;
      padding: 0.5rem;
    }
    
    .mobile-menu-button span {
      display: block;
      width: 25px;
      height: 3px;
      background-color: #831843;
      margin: 5px 0;
      transition: all 0.3s;
    }
    
    @keyframes pulse {
      0%, 100% {
        opacity: 0.6;
      }
      50% {
        opacity: 1;
      }
    }
    
    @keyframes bounce {
      0%, 100% {
        transform: translateY(0);
      }
      50% {
        transform: translateY(-10px);
      }
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
      .container {
        padding-left: 1rem;
        padding-right: 1rem;
      }
      
      .desktop-nav {
        display: none;
      }
      
      .mobile-menu-button {
        display: block;
      }
      
      #saldoTable th, 
      #saldoTable td {
        padding: 0.75rem;
        font-size: 0.875rem;
      }
    }

    @media (max-width: 480px) {
      .brand h1 {
        font-size: 1.25rem;
      }
    }
  </style>
</head>
<body class="min-h-screen flex flex-col">
  <!-- Header Section -->
  <header class="sticky top-0 z-50">
    <div class="navbar">
      <div class="container mx-auto px-6 py-3 flex justify-between items-center">
        <div class="brand flex items-center space-x-3">
          <h1 class="text-2xl font-bold">Vermont Store</h1>
        </div>
        
        <!-- Desktop Navigation -->
        <nav class="nav-links hidden md:flex items-center space-x-6">
          <a href="index.php" class="flex items-center space-x-1 hover:text-pink-700">
            <span>📊</span>
            <span>Dashboard</span>
          </a>
          <a href="daftar_transaksi.php" class="flex items-center space-x-1 hover:text-pink-700">
            <span>💰</span>
            <span>Transaksi Dompet</span>
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
          <a href="daftar_saldo.php" class="flex items-center space-x-1 text-pink-700 active">
            <span>📊</span>
            <span>Daftar Saldo</span>
          </a>

          <a href="logout.php" class="flex items-center space-x-1 text-red-600 hover:text-red-800">
    <span>🚪</span>
    <span>Logout</span>
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
          <a href="index.php" class="flex items-center space-x-2 py-2 hover:text-pink-700">
            <span>📊</span>
            <span>Dashboard</span>
          </a>
          <a href="daftar_transaksi.php" class="flex items-center space-x-2 py-2 hover:text-pink-700">
            <span>💰</span>
            <span>Transaksi Dompet</span>
          </a>
          <a href="tambah_hutang.php" class="flex items-center space-x-2 py-2 hover:text-pink-700">
            <span>📋</span>
            <span>Hutang</span>
          </a>
          <a href="tambah_saldo.php" class="flex items-center space-x-2 py-2 hover:text-pink-700">
            <span>💳</span>
            <span>Saldo</span>
          </a>
          <a href="tambah_penjualan.php" class="flex items-center space-x-2 py-2 hover:text-pink-700">
            <span>🛒</span>
            <span>Penjualan</span>
          </a>
          <a href="daftar_saldo.php" class="flex items-center space-x-2 py-2 text-pink-700 font-medium">
            <span>📊</span>
            <span>Daftar Saldo</span>
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
    <h2 class="text-3xl font-bold text-pink-800 mb-6 animate__animated animate__fadeIn">💳 Daftar Saldo Digital</h2>

    <!-- Navigation Button -->
    <div class="button-container mb-6">
      <a href="index.php" class="btn-lengkap inline-flex items-center px-4 py-2 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition-all duration-300">
        ⬅️ Kembali ke Dashboard
      </a>
    </div>

    <!-- Saldo Table -->
    <div class="table-container animate__animated animate__fadeInUp">
      <div class="overflow-x-auto bg-white rounded-xl shadow-md px-4 py-2">
        <table id="saldoTable" class="w-full">
          <thead class="text-left">
            <tr>
              <th class="px-4 py-3">Metode</th>
              <th class="px-4 py-3">Jumlah (Rp)</th>
            </tr>
          </thead>
          <tbody id="saldoTableBody" class="divide-y divide-pink-100">
            <tr class="loading-row">
              <td colspan="2" class="px-4 py-3 text-center text-pink-600">Memuat data...</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Empty State (hidden by default) -->
    <div class="empty-state text-center py-12 hidden" id="emptyState">
      <div class="empty-state-icon text-6xl mb-4 text-pink-400">💸</div>
      <h3 class="text-xl font-semibold text-pink-800 mb-2">Belum Ada Data Saldo</h3>
      <p class="text-pink-600">Data saldo Anda akan ditampilkan di sini setelah dimuat.</p>
    </div>
  </main>

  <!-- Footer -->
  <footer class="mt-auto">
    <div class="container mx-auto px-6 py-4 text-center">
      <p>&copy; 2025 Vermont Store • Sistem Manajemen Keuangan</p>
    </div>
  </footer>

  <!-- JavaScript -->
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
  <script src="assets/js/daftar_saldo.js"></script>
</body>
</html>
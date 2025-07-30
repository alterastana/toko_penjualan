<?php
require_once __DIR__ . '/auth_check.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Transaksi Dompet - Vermont Store</title>
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
    
    .search-input {
      transition: all 0.3s ease;
    }
    
    .search-input:focus {
      box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.2);
    }
    
    .search-icon {
      transition: all 0.3s ease;
    }
    
    .search-box:hover .search-icon {
      transform: scale(1.2);
    }
    
    .filter-select {
      transition: all 0.3s ease;
    }
    
    .filter-select:hover {
      transform: translateY(-2px);
    }
    
    .table-container {
      animation: fadeInUp 0.5s ease;
    }
    
    #transaksiTable {
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
      width: calc(100% - 2rem);
      margin: 0 auto;
    }
    
    #transaksiTable th {
      background-color: #fbcfe8;
      font-weight: 600;
      letter-spacing: 0.5px;
      padding: 1rem 1.25rem;
    }
    
    #transaksiTable tr {
      transition: all 0.3s ease;
    }
    
    #transaksiTable tr:not(:last-child) {
      border-bottom: 1px solid #fce7f3;
    }
    
    #transaksiTable tr:hover {
      background-color: #fdf2f8;
      transform: translateX(3px);
      box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    #transaksiTable td {
      padding: 1rem 1.25rem;
      vertical-align: middle;
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
    
    .badge-pemasukan {
      background-color: #bbf7d0;
      color: #166534;
    }
    
    .badge-pengeluaran {
      background-color: #fecaca;
      color: #991b1b;
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
    
    @keyframes bounce {
      0%, 100% {
        transform: translateY(0);
      }
      50% {
        transform: translateY(-10px);
      }
    }
  </style>
</head>
<body class="min-h-screen flex flex-col">
  <!-- Header Section -->
  <header class="sticky top-0 z-50">
    <div class="navbar text-pink-900">
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
          <a href="daftar_transaksi.php" class="flex items-center space-x-1 text-pink-700 active">
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
          <a href="index.php" class="flex items-center space-x-2 py-2 hover:text-pink-700">
            <span>📊</span>
            <span>Dashboard</span>
          </a>
          <a href="daftar_transaksi.php" class="flex items-center space-x-2 py-2 text-pink-700 font-medium">
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
    <h2 class="text-3xl font-bold text-pink-800 mb-6 animate__animated animate__fadeIn">💰 Daftar Transaksi Dompet</h2>

    <!-- Navigation Button -->
    <div class="button-container mb-6">
      <a href="index.php" class="btn-lengkap inline-flex items-center px-4 py-2 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition-all duration-300">
        ⬅️ Kembali ke Dashboard
      </a>
    </div>

    <!-- Controls Section -->
    <div class="controls-section bg-white rounded-xl p-4 shadow-md mb-6 animate__animated animate__fadeInUp">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0 md:space-x-4">
        <div class="search-box relative flex-grow">
          <input 
            type="text" 
            class="search-input w-full px-4 py-2 border border-pink-300 rounded-lg focus:outline-none focus:border-pink-500 transition-all duration-300" 
            id="searchInput"
            placeholder="Cari berdasarkan keterangan atau keperluan..."
            autocomplete="off"
          >
          <span class="search-icon absolute right-3 top-2.5 text-pink-400">🔍</span>
        </div>
        
        <div class="filter-group flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
          <select class="filter-select px-3 py-2 border border-pink-300 rounded-lg focus:outline-none focus:border-pink-500 bg-white transition-all duration-300" id="filterMethod">
            <option value="">Semua Metode</option>
            <option value="Gopay">Gopay</option>
            <option value="QRIS">QRIS</option>
            <option value="Dana">Dana</option>
            <option value="Spay">ShopeePay</option>
            <option value="OVO">OVO</option>
            <option value="BCA">BCA</option>
          </select>
          
          <select class="filter-select px-3 py-2 border border-pink-300 rounded-lg focus:outline-none focus:border-pink-500 bg-white transition-all duration-300" id="filterType">
            <option value="">Semua Jenis</option>
            <option value="masuk">Pemasukan</option>
            <option value="keluar">Pengeluaran</option>
          </select>
          
          <input type="date" class="filter-select px-3 py-2 border border-pink-300 rounded-lg focus:outline-none focus:border-pink-500 bg-white transition-all duration-300" id="filterDate">
        </div>
      </div>
    </div>

    <!-- Transaction Table -->
    <div class="table-container animate__animated animate__fadeInUp">
      <div class="overflow-x-auto bg-white rounded-xl shadow-md px-4 py-2">
        <table id="transaksiTable" class="w-full">
          <thead class="text-left">
            <tr>
              <th class="px-4 py-3 text-pink-700">ID</th>
              <th class="px-4 py-3 text-pink-700">📅 Tanggal</th>
              <th class="px-4 py-3 text-pink-700">💳 Metode</th>
              <th class="px-4 py-3 text-pink-700">📊 Jenis</th>
              <th class="px-4 py-3 text-pink-700">💰 Jumlah</th>
              <th class="px-4 py-3 text-pink-700">📝 Nama</th>
              <th class="px-4 py-3 text-pink-700">🏷️ Keperluan</th>
            </tr>
          </thead>
          <tbody id="transaksiTableBody" class="divide-y divide-pink-100">
            <tr class="hover:bg-pink-50">
              <td class="px-4 py-3 font-medium text-pink-900">14</td>
              <td class="px-4 py-3">2025-07-19</td>
              <td class="px-4 py-3">
                <span class="badge badge-gopay">Gopay</span>
              </td>
              <td class="px-4 py-3">
                <span class="badge badge-pemasukan">masuk</span>
              </td>
              <td class="px-4 py-3 font-medium text-green-600">Rp 15,000</td>
              <td class="px-4 py-3 text-pink-800">iman</td>
              <td class="px-4 py-3 text-pink-800">beli akun</td>
            </tr>
            <tr class="hover:bg-pink-50">
              <td class="px-4 py-3 font-medium text-pink-900">13</td>
              <td class="px-4 py-3">2025-07-16</td>
              <td class="px-4 py-3">
                <span class="badge badge-gopay">Gopay</span>
              </td>
              <td class="px-4 py-3">
                <span class="badge badge-pemasukan">masuk</span>
              </td>
              <td class="px-4 py-3 font-medium text-green-600">Rp 55,000</td>
              <td class="px-4 py-3 text-pink-800">-</td>
              <td class="px-4 py-3 text-pink-800">-</td>
            </tr>
            <tr class="hover:bg-pink-50">
              <td class="px-4 py-3 font-medium text-pink-900">12</td>
              <td class="px-4 py-3">2025-07-16</td>
              <td class="px-4 py-3">
                <span class="badge badge-gopay">Gopay</span>
              </td>
              <td class="px-4 py-3">
                <span class="badge badge-pengeluaran">keluar</span>
              </td>
              <td class="px-4 py-3 font-medium text-red-600">Rp 5,000</td>
              <td class="px-4 py-3 text-pink-800">-</td>
              <td class="px-4 py-3 text-pink-800">-</td>
            </tr>
            <tr class="hover:bg-pink-50">
              <td class="px-4 py-3 font-medium text-pink-900">11</td>
              <td class="px-4 py-3">2025-07-16</td>
              <td class="px-4 py-3">
                <span class="badge badge-gopay">Gopay</span>
              </td>
              <td class="px-4 py-3">
                <span class="badge badge-pemasukan">masuk</span>
              </td>
              <td class="px-4 py-3 font-medium text-green-600">Rp 10,000</td>
              <td class="px-4 py-3 text-pink-800">-</td>
              <td class="px-4 py-3 text-pink-800">-</td>
            </tr>
            <tr class="hover:bg-pink-50">
              <td class="px-4 py-3 font-medium text-pink-900">10</td>
              <td class="px-4 py-3">2025-07-16</td>
              <td class="px-4 py-3">
                <span class="badge badge-gopay">Gopay</span>
              </td>
              <td class="px-4 py-3">
                <span class="badge badge-pemasukan">masuk</span>
              </td>
              <td class="px-4 py-3 font-medium text-green-600">Rp 10,000</td>
              <td class="px-4 py-3 text-pink-800">-</td>
              <td class="px-4 py-3 text-pink-800">-</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <!-- Show More Button -->
    <div class="text-center mt-6">
      <button id="showMoreBtn" class="px-6 py-3 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition-all duration-300 shadow-md hover:shadow-lg">
        Tampilkan Lebih Banyak
      </button>
    </div>

    <!-- Empty State (hidden by default) -->
    <div class="empty-state text-center py-12 hidden" id="emptyState">
      <div class="empty-state-icon text-6xl mb-4 text-pink-400">📋</div>
      <h3 class="text-xl font-semibold text-pink-800 mb-2">Belum Ada Transaksi</h3>
      <p class="text-pink-600">Transaksi Anda akan ditampilkan di sini setelah data dimuat.</p>
    </div>
  </main>

  <!-- Footer -->
  <footer class="mt-auto">
    <div class="container mx-auto px-6 py-4 text-center text-pink-900">
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
  <script src="assets/js/daftar_transaksiv2.js"></script>
</body>
</html>
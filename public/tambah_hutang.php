<?php
require_once __DIR__ . '/auth_check.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Tambah Hutang / Piutang</title>
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
    
    .btn-submit {
      transition: all 0.3s ease;
      transform-origin: center;
    }
    
    .btn-submit:hover {
      transform: scale(1.03);
    }
    
    .success-message {
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
    
    input:focus, select:focus {
      border-color: #f9a8d4 !important;
      box-shadow: 0 0 0 1px #f9a8d4 !important;
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
          <a href="index.php" class="flex items-center space-x-1 hover:text-pink-700">
            <span>📊</span>
            <span>Dashboard</span>
          </a>
          <a href="tambah_transaksi.php" class="flex items-center space-x-1 hover:text-pink-700">
            <span>💰</span>
            <span>Transaksi</span>
          </a>
          <a href="tambah_hutang.php" class="flex items-center space-x-1 text-pink-700 active">
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
          <a href="index.php" class="flex items-center space-x-2 hover:text-pink-700 py-2">
            <span>📊</span>
            <span>Dashboard</span>
          </a>
          <a href="tambah_transaksi.php" class="flex items-center space-x-2 hover:text-pink-700 py-2">
            <span>💰</span>
            <span>Transaksi</span>
          </a>
          <a href="tambah_hutang.php" class="flex items-center space-x-2 text-pink-700 active py-2">
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
    🚪 <span>Logout</span>
</a>

        </div>
      </div>
    </div>
  </header>

  <!-- Main Content -->
  <main class="container mx-auto px-6 py-8 flex-grow">
    <h2 class="text-3xl font-bold text-pink-800 mb-8 animate__animated animate__fadeIn">📋 Tambah Piutang / Pengembalian</h2>

    <div class="bg-white rounded-xl shadow-md p-6 animate__animated animate__fadeInUp">
      <form id="hutangForm" class="space-y-6">
        <!-- Nama Peminjam -->
        <div>
          <label for="nama" class="block text-sm font-medium text-pink-700 mb-2">Nama Peminjam:</label>
          <input type="text" id="nama" name="nama" placeholder="Contoh: Budi" required 
                 class="w-full px-4 py-2 border border-pink-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-pink-300" />
        </div>

        <!-- Sisa Hutang -->
        <div>
          <label for="sisa_hutang" class="block text-sm font-medium text-pink-700 mb-2">Sisa Hutang:</label>
          <input type="text" id="sisa_hutang" name="sisa_hutang" readonly placeholder="Sisa hutang akan muncul di sini" 
                 class="w-full px-4 py-2 border border-pink-200 rounded-lg bg-pink-50" />
        </div>

        <!-- Jenis Transaksi -->
        <div>
          <label for="jenis_hutang" class="block text-sm font-medium text-pink-700 mb-2">Jenis Transaksi:</label>
          <select id="jenis_hutang" name="jenis_hutang" required 
                  class="w-full px-4 py-2 border border-pink-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-pink-300">
            <option value="pinjam">Piutangkan ke orang</option>
            <option value="bayar">Orang mengembalikan</option>
          </select>
          <p class="text-xs text-pink-500 mt-2 italic">
            Pilih "Piutangkan" jika kamu meminjamkan uang. Pilih "Mengembalikan" jika orangnya membayar kembali.
          </p>
        </div>

        <!-- Jumlah -->
        <div>
          <label for="jumlah_hutang" class="block text-sm font-medium text-pink-700 mb-2">Jumlah (Rp):</label>
          <input type="number" id="jumlah_hutang" name="jumlah" min="1" required 
                 class="w-full px-4 py-2 border border-pink-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-pink-300" />
        </div>

        <!-- Metode Pembayaran -->
        <div>
          <label for="metode_hutang" class="block text-sm font-medium text-pink-700 mb-2">Metode Pembayaran:</label>
          <select id="metode_hutang" name="metode" required 
                  class="w-full px-4 py-2 border border-pink-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-pink-300">
            <option value="Gopay">Gopay</option>
            <option value="Dana">Dana</option>
            <option value="OVO">OVO</option>
            <option value="BCA">BCA</option>
            <option value="Spay">ShopeePay</option>
            <option value="QRIS">QRIS</option>
          </select>
        </div>

        <!-- Keterangan -->
        <div>
          <label for="keterangan_hutang" class="block text-sm font-medium text-pink-700 mb-2">Keterangan (opsional):</label>
          <input type="text" id="keterangan_hutang" name="keterangan" placeholder="Misal: untuk beli pulsa" 
                 class="w-full px-4 py-2 border border-pink-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-pink-300" />
        </div>

        <!-- Submit Button -->
        <button type="submit" 
                class="w-full btn-submit px-6 py-3 bg-gradient-to-r from-pink-500 to-pink-600 text-white font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-300">
          Simpan Transaksi
        </button>
      </form>

      <!-- Success Message -->
      <div id="hutangStatus" class="success-message mt-6 p-4 bg-pink-100 border-l-4 border-pink-500 text-pink-700 rounded-lg hidden animate__animated animate__fadeIn"></div>
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
  <script src="assets/js/hutangv4.js"></script>
</body>
</html>
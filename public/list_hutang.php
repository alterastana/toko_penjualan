<?php
require_once __DIR__ . '/auth_check.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Hutang / Piutang - Vermont Store</title>
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
    
    .data-table {
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
      width: calc(100% - 2rem);
      margin: 0 auto;
    }
    
    .data-table th {
      background-color: #fbcfe8;
      font-weight: 600;
      letter-spacing: 0.5px;
      padding: 1rem 1.25rem;
    }
    
    .data-table tr {
      transition: all 0.3s ease;
    }
    
    .data-table tr:not(:last-child) {
      border-bottom: 1px solid #fce7f3;
    }
    
    .data-table tr:hover {
      background-color: #fdf2f8;
      transform: translateX(3px);
      box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    .data-table td {
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
    
    .badge-hutang {
      background-color: #fecaca;
      color: #991b1b;
    }
    
    .badge-piutang {
      background-color: #bbf7d0;
      color: #166534;
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
    
    .action-btn {
      transition: all 0.2s ease;
    }
    
    .action-btn:hover {
      transform: scale(1.1);
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
          <a href="tambah_hutang.php" class="flex items-center space-x-1 hover:text-pink-700">
            <span>➕</span>
            <span>Tambah/Edit Hutang</span>
          </a>
          <a href="list_hutang.php" class="flex items-center space-x-1 text-pink-700 active">
            <span>📋</span>
            <span>Daftar Hutang</span>
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
          <a href="tambah_hutang.php" class="flex items-center space-x-2 py-2 hover:text-pink-700">
            <span>➕</span>
            <span>Tambah/Edit Hutang</span>
          <a href="list_hutang.php" class="flex items-center space-x-2 py-2 text-pink-700 font-medium">
            <span>📋</span>
            <span>Daftar Hutang</span>
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
    <h2 class="text-3xl font-bold text-pink-800 mb-6 animate__animated animate__fadeIn">📋 Data Hutang & Piutang</h2>

    <!-- Search Box -->
    <div class="search-box bg-white rounded-xl shadow-md p-4 mb-6 animate__animated animate__fadeInUp">
      <div class="relative flex items-center">
        <svg class="absolute left-3 text-pink-400 w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
          <path d="M10,2A8,8,0,1,0,18,10,8,8,0,0,0,10,2Zm0,14A6,6,0,1,1,16,10,6,6,0,0,1,10,16Z"/>
          <path d="M21.71,20.29,17.31,15.9a8.94,8.94,0,0,1-1.42,1.42l4.39,4.39a1,1,0,0,0,1.42-1.42Z"/>
        </svg>
        <input 
          type="text" 
          class="search-input w-full pl-10 pr-4 py-2 border border-pink-300 rounded-lg focus:outline-none focus:border-pink-500 transition-all duration-300" 
          id="searchInput"
          placeholder="Cari nama, metode, atau keterangan..."
          autocomplete="off"
        >
      </div>
    </div>

    <!-- Transaction Table -->
    <div class="table-container animate__animated animate__fadeInUp">
      <div class="overflow-x-auto bg-white rounded-xl shadow-md px-4 py-2">
        <table class="data-table w-full">
          <thead class="text-left">
            <tr>
              <th class="px-4 py-3 text-pink-700">Tanggal</th>
              <th class="px-4 py-3 text-pink-700">Nama</th>
              <th class="px-4 py-3 text-pink-700">Jenis</th>
              <th class="px-4 py-3 text-pink-700">Jumlah/Sisa Hutang</th>
              <th class="px-4 py-3 text-pink-700">Metode</th>
              <th class="px-4 py-3 text-pink-700">Keterangan</th>
              <th class="px-4 py-3 text-pink-700">Aksi</th>
            </tr>
          </thead>
          <tbody id="tabel-hutang" class="divide-y divide-pink-100">
            <!-- Sample Data - Will be replaced by JavaScript -->
            <tr class="hover:bg-pink-50">
              <td class="px-4 py-3">2025-07-20</td>
              <td class="px-4 py-3 font-medium text-pink-900">Budi</td>
              <td class="px-4 py-3">
                <span class="badge badge-hutang">Hutang</span>
              </td>
              <td class="px-4 py-3 font-medium text-red-600">Rp 150,000</td>
              <td class="px-4 py-3">Transfer</td>
              <td class="px-4 py-3 text-pink-800">Pinjam modal usaha</td>
              <td class="px-4 py-3">
                <a href="ubah_hutang.php?id=1" class="action-btn text-blue-500 hover:text-blue-700">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                  </svg>
                </a>
              </td>
            </tr>
            <tr class="hover:bg-pink-50">
              <td class="px-4 py-3">2025-07-18</td>
              <td class="px-4 py-3 font-medium text-pink-900">Ani</td>
              <td class="px-4 py-3">
                <span class="badge badge-piutang">Piutang</span>
              </td>
              <td class="px-4 py-3 font-medium text-green-600">Rp 75,000</td>
              <td class="px-4 py-3">Cash</td>
              <td class="px-4 py-3 text-pink-800">Belanja bahan baku</td>
              <td class="px-4 py-3">
                <a href="ubah_hutang.php?id=2" class="action-btn text-blue-500 hover:text-blue-700">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                  </svg>
                </a>
              </td>
            </tr>
            <tr class="hover:bg-pink-50">
              <td class="px-4 py-3">2025-07-15</td>
              <td class="px-4 py-3 font-medium text-pink-900">Rudi</td>
              <td class="px-4 py-3">
                <span class="badge badge-hutang">Hutang</span>
              </td>
              <td class="px-4 py-3 font-medium text-red-600">Rp 200,000</td>
              <td class="px-4 py-3">Gopay</td>
              <td class="px-4 py-3 text-pink-800">Pinjam urgent</td>
              <td class="px-4 py-3">
                <a href="ubah_hutang.php?id=3" class="action-btn text-blue-500 hover:text-blue-700">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                  </svg>
                </a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Error box (hidden by default) -->
    <div id="error" class="error-box bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow-md hidden animate__animated animate__fadeIn">
      <p>Gagal mengambil data hutang.</p>
    </div>

    <!-- Empty State (hidden by default) -->
    <div class="empty-state text-center py-12 hidden" id="emptyState">
      <div class="empty-state-icon text-6xl mb-4 text-pink-400">📋</div>
      <h3 class="text-xl font-semibold text-pink-800 mb-2">Belum Ada Data Hutang</h3>
      <p class="text-pink-600">Data hutang akan ditampilkan di sini setelah dimuat.</p>
    </div>
  </main>

  <!-- Footer -->
  <footer class="mt-auto">
    <div class="container mx-auto px-6 py-4 text-center text-pink-900">
      <p>&copy; 2025 Vermont Store • Dibuat oleh Kamu</p>
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

    // Search functionality
    document.getElementById('searchInput').addEventListener('input', function() {
      const searchTerm = this.value.toLowerCase();
      const rows = document.querySelectorAll('#tabel-hutang tr');
      
      rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
      });
    });
  </script>
  <script src="assets/js/hutang_list3.js"></script>
</body>
</html>
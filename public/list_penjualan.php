<?php
require_once __DIR__ . '/auth_check.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Daftar Penjualan</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
    
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #fff1f2;
      overflow-x: hidden;
      color: #4c0519;
    }

    .navbar {
      background: linear-gradient(135deg, #fbcfe8 0%, #f9a8d4 100%);
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
      position: relative;
      z-index: 40;
    }

    .table-wrapper {
      width: 100%;
      overflow-x: auto;
      -webkit-overflow-scrolling: touch;
      background-color: white;
      border-radius: 0.75rem;
      box-shadow: 0 4px 6px rgba(0,0,0,0.05);
      margin-bottom: 1.5rem;
      border: 1px solid #fce7f3;
    }

    .table-container {
      width: 100%;
      min-width: fit-content;
    }

    .data-table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
      font-family: 'Poppins', sans-serif;
      background-color: white;
      border-radius: 0.75rem;
      overflow: hidden;
    }

    .data-table th {
      background-color: #fdf2f8;
      color: #831843;
      font-weight: 600;
      text-transform: uppercase;
      font-size: 0.75rem;
      letter-spacing: 0.5px;
      position: sticky;
      top: 0;
      padding: 0.75rem 1rem;
      border-bottom: 1px solid #fce7f3;
      white-space: nowrap;
      text-align: left;
    }

    .data-table td {
      padding: 0.75rem 1rem;
      border-bottom: 1px solid #fce7f3;
      white-space: nowrap;
      transition: all 0.2s ease;
      color: #4c0519;
      font-size: 0.875rem;
    }

    .data-table tr:last-child td {
      border-bottom: none;
    }

    .data-table tr:hover td {
      background-color: #fdf2f8;
    }

    /* Column specific styling */
    .data-table td:nth-child(1),  /* ID */
    .data-table td:nth-child(2),  /* Tanggal */
    .data-table td:nth-child(4),  /* Durasi/Jumlah */
    .data-table td:nth-child(7),  /* No Customer */
    .data-table td:nth-child(8),  /* Status */
    .data-table td:nth-child(15), /* Proof */
    .data-table td:nth-child(16) { /* Aksi */
      padding-left: 1.25rem;
      padding-right: 1.25rem;
    }

    /* Columns with long content */
    .data-table td:nth-child(3),  /* Produk */
    .data-table td:nth-child(12) { /* Catatan */
      min-width: 180px;
      white-space: normal;
    }

    /* Numeric columns */
    .data-table td:nth-child(5),  /* Harga Beli */
    .data-table td:nth-child(6),  /* Harga Jual */
    .data-table td:nth-child(9),  /* Total Untung */
    .data-table td:nth-child(10), /* Pengeluaran */
    .data-table td:nth-child(11) { /* Untung */
      text-align: right;
      padding-right: 1.5rem;
      font-family: 'Poppins', sans-serif;
      font-weight: 500;
    }

    /* Status column styling */
    .data-table td:nth-child(8) {
      font-weight: 500;
      text-transform: capitalize;
    }

    .status-pending {
      color: #f59e0b;
      background-color: #fef3c7;
      padding: 0.25rem 0.5rem;
      border-radius: 0.375rem;
      display: inline-block;
    }

    .status-completed {
      color: #10b981;
      background-color: #d1fae5;
      padding: 0.25rem 0.5rem;
      border-radius: 0.375rem;
      display: inline-block;
    }

    .status-cancelled {
      color: #ef4444;
      background-color: #fee2e2;
      padding: 0.25rem 0.5rem;
      border-radius: 0.375rem;
      display: inline-block;
    }

    /* Proof styling */
    .proof-sudah {
      color: #10b981;
      background-color: #d1fae5;
      padding: 0.25rem 0.5rem;
      border-radius: 0.375rem;
      display: inline-block;
    }

    .proof-belum {
      color: #ef4444;
      background-color: #fee2e2;
      padding: 0.25rem 0.5rem;
      border-radius: 0.375rem;
      display: inline-block;
    }

    /* Action buttons */
    .action-group {
      display: flex;
      gap: 0.5rem;
      flex-wrap: wrap;
    }

    .action-btn {
      padding: 0.5rem 0.75rem;
      border-radius: 0.5rem;
      transition: all 0.3s ease;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      font-size: 0.75rem;
      font-weight: 500;
      min-width: 70px;
      box-shadow: 0 1px 2px rgba(0,0,0,0.05);
      border: none;
      cursor: pointer;
    }

    .edit-btn {
      background-color: #f9a8d4;
      color: #831843;
    }

    .edit-btn:hover {
      background-color: #f472b6;
      color: white;
      transform: translateY(-1px);
    }

    .delete-btn {
      background-color: #fecdd3;
      color: #be123c;
    }

    .delete-btn:hover {
      background-color: #fda4af;
      color: white;
      transform: translateY(-1px);
    }

    /* Modal */
    .modal {
      display: none;
      position: fixed;
      z-index: 100;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0,0,0,0.5);
      animation: fadeIn 0.3s;
    }

    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    .modal-content {
      background-color: white;
      margin: 5% auto;
      padding: 1.5rem;
      border-radius: 0.75rem;
      width: 90%;
      max-width: 500px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.15);
      animation: slideDown 0.3s;
    }

    @keyframes slideDown {
      from { transform: translateY(-20px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }

    .close {
      float: right;
      font-size: 1.5rem;
      cursor: pointer;
      transition: color 0.2s;
      color: #9d174d;
    }

    .close:hover {
      color: #831843;
    }

    #searchInput {
      width: 100%;
      padding: 0.75rem 1rem;
      border: 1px solid #fbcfe8;
      border-radius: 0.5rem;
      margin-bottom: 1.5rem;
      background-color: white;
      transition: all 0.2s;
      font-family: 'Poppins', sans-serif;
      color: #4c0519;
    }

    #searchInput:focus {
      border-color: #f9a8d4;
      box-shadow: 0 0 0 2px #fce7f3;
      outline: none;
    }

    .error-box {
      background-color: #fee2e2;
      color: #b91c1c;
      padding: 1rem;
      border-radius: 0.5rem;
      border-left: 4px solid #ef4444;
      margin-top: 1rem;
      display: none;
      font-family: 'Poppins', sans-serif;
    }

    .btn-show-more {
      background-color: #ec4899;
      color: white;
      padding: 0.5rem 1.5rem;
      border-radius: 0.375rem;
      transition: all 0.2s;
      font-weight: 500;
      font-family: 'Poppins', sans-serif;
      border: none;
      cursor: pointer;
    }

    .btn-show-more:hover {
      background-color: #db2777;
      transform: translateY(-1px);
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    /* Mobile Navbar */
    .mobile-menu-button {
      display: none;
      cursor: pointer;
      padding: 0.5rem;
      z-index: 50;
    }

    .mobile-menu-button span {
      display: block;
      width: 25px;
      height: 3px;
      background-color: #831843;
      margin: 5px 0;
      transition: all 0.3s;
    }

    .mobile-menu {
      display: none;
      position: fixed;
      top: 0;
      right: 0;
      width: 280px;
      height: 100vh;
      background: linear-gradient(135deg, #fbcfe8 0%, #f9a8d4 100%);
      z-index: 45;
      transform: translateX(100%);
      transition: transform 0.3s ease-out;
      box-shadow: -5px 0 15px rgba(0,0,0,0.1);
    }

    .mobile-menu.active {
      transform: translateX(0);
      display: block;
    }

    .mobile-menu-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1rem 1.5rem;
      border-bottom: 1px solid rgba(249, 168, 212, 0.5);
    }

    .mobile-menu-title {
      font-weight: bold;
      color: #831843;
      font-size: 1.25rem;
    }

    .mobile-menu-close {
      font-size: 1.5rem;
      cursor: pointer;
      color: #831843;
    }

    .mobile-menu-content {
      padding: 1rem 0;
      height: calc(100% - 60px);
      overflow-y: auto;
    }

    .mobile-menu a {
      display: block;
      padding: 1rem 1.5rem;
      color: #831843;
      border-bottom: 1px solid rgba(249, 168, 212, 0.5);
      font-weight: 500;
      transition: background-color 0.2s;
    }

    .mobile-menu a:hover {
      background-color: rgba(255, 255, 255, 0.2);
    }

    .mobile-menu-overlay {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0,0,0,0.5);
      z-index: 40;
    }

    .mobile-menu-overlay.active {
      display: block;
    }

    /* Form elements */
    .form-group {
      margin-bottom: 1rem;
    }

    .form-group label {
      display: block;
      margin-bottom: 0.5rem;
      font-size: 0.875rem;
      font-weight: 500;
      color: #4c0519;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
      width: 100%;
      padding: 0.5rem 0.75rem;
      border: 1px solid #fbcfe8;
      border-radius: 0.375rem;
      font-family: 'Poppins', sans-serif;
      font-size: 0.875rem;
      color: #4c0519;
      transition: all 0.2s;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
      border-color: #f472b6;
      box-shadow: 0 0 0 2px #fce7f3;
      outline: none;
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
      
      .data-table th, .data-table td {
        padding: 0.5rem;
        font-size: 0.75rem;
      }
      
      h2 {
        font-size: 1.5rem;
      }
      
      .action-btn {
        padding: 0.375rem 0.5rem;
        font-size: 0.6875rem;
        min-width: 60px;
      }

      .action-group {
        flex-direction: column;
        gap: 0.25rem;
      }
    }

    @media (max-width: 480px) {
      .brand h1 {
        font-size: 1.25rem;
      }
      
      h2 {
        font-size: 1.25rem;
      }
      
      .modal-content {
        width: 95%;
        padding: 1rem;
      }
      
      .mobile-menu {
        width: 80%;
      }

      .data-table td:nth-child(3),
      .data-table td:nth-child(12) {
        min-width: 120px;
      }
    }
  </style>
</head>
<body class="min-h-screen flex flex-col">
  <header class="sticky top-0 z-40">
    <div class="navbar text-pink-900">
      <div class="container mx-auto px-6 py-3 flex justify-between items-center">
        <div class="brand flex items-center space-x-3">
          <h1 class="text-2xl font-bold">Vermont Store</h1>
        </div>
        
        <!-- Desktop Navigation -->
        <nav class="desktop-nav hidden md:flex space-x-6">
          <a href="index.php" class="hover:text-pink-700">📊 Dashboard</a>
          <a href="tambah_penjualan.php" class="hover:text-pink-700">➕ Tambah Penjualan</a>
          <a href="list_penjualan.php" class="text-pink-700 font-semibold">📋 Daftar Penjualan</a>
          <a href="logout.php" class="text-red-600 font-semibold hover:text-red-800">🚪 Logout</a>

        </nav>
        
        <!-- Mobile Menu Button -->
        <div class="mobile-menu-button md:hidden">
          <span></span>
          <span></span>
          <span></span>
        </div>
      </div>
    </div>
    
    <!-- Mobile Menu -->
    <div class="mobile-menu">
      <div class="mobile-menu-header">
        <div class="mobile-menu-title">Vermont Store</div>
        <div class="mobile-menu-close">&times;</div>
      </div>
      <div class="mobile-menu-content">
        <a href="index.php" class="hover:text-pink-700">📊 Dashboard</a>
        <a href="tambah_penjualan.php" class="hover:text-pink-700">➕ Tambah Penjualan</a>
              <a href="list_penjualan.php" class="text-pink-700 font-semibold">📋 Daftar Penjualan</a>
        <a href="logout.php" class="text-red-600 font-semibold hover:text-red-800">🚪 Logout</a>

      </div>
    </div>
    
    <!-- Mobile Menu Overlay -->
    <div class="mobile-menu-overlay"></div>
  </header>

  <main class="container mx-auto px-6 py-8 flex-grow">
    <h2 class="text-3xl font-bold text-pink-800 mb-6 text-center">📋 Daftar Penjualan</h2>
    <input type="text" id="searchInput" placeholder="🔍 Cari penjualan berdasarkan produk, customer, atau status...">

    <div class="table-wrapper">
      <div class="table-container">
        <table class="data-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Tanggal</th>
              <th>Produk</th>
              <th>Durasi/Jumlah</th>
              <th>Harga Beli</th>
              <th>Harga Jual</th>
              <th>No Customer</th>
              <th>Status</th>
              <th>Total Untung</th>
              <th>Pengeluaran</th>
              <th>Untung</th>
              <th>Catatan</th>
              <th>Metode Customer</th>
              <th>Metode Modal</th>
              <th>Proof</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody id="tabel-penjualan">
            <tr><td colspan="16" class="text-center py-4 text-pink-600">Memuat data...</td></tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Tombol Show More -->
    <div class="flex justify-center mt-4">
      <button id="btnShowMore" class="btn-show-more">
        Tampilkan Lebih Banyak
      </button>
    </div>

    <div id="error" class="error-box">
      Gagal mengambil data penjualan. Silakan coba lagi.
    </div>
  </main>

  <!-- Modal Edit -->
  <div id="editModal" class="modal">
    <div class="modal-content">
      <span id="closeModal" class="close">&times;</span>
      <h3 class="text-xl font-semibold mb-4 text-pink-800">✏️ Edit Penjualan</h3>
      <form id="editForm" class="space-y-4">
        <input type="hidden" id="editId">
        
        <div class="form-group">
          <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk</label>
          <input type="text" id="editNamaProduk" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500">
        </div>
        
        <div class="form-group">
          <label class="block text-sm font-medium text-gray-700 mb-1">Durasi/Jumlah</label>
          <input type="number" id="editDurasiJumlah" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500">
        </div>
        
        <div class="grid grid-cols-2 gap-4">
          <div class="form-group">
            <label class="block text-sm font-medium text-gray-700 mb-1">Harga Beli</label>
            <input type="number" id="editHargaBeli" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500">
          </div>
          
          <div class="form-group">
            <label class="block text-sm font-medium text-gray-700 mb-1">Harga Jual</label>
            <input type="number" id="editHargaJual" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500">
          </div>
        </div>
        
        <div class="grid grid-cols-2 gap-4">
          <div class="form-group">
            <label class="block text-sm font-medium text-gray-700 mb-1">Metode Customer</label>
            <select id="editMetodeCustomer" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500">
              <option value="Transfer">Transfer</option>
              <option value="Cash">Cash</option>
              <option value="QRIS">QRIS</option>
            </select>
          </div>
          
          <div class="form-group">
            <label class="block text-sm font-medium text-gray-700 mb-1">Metode Modal</label>
            <select id="editMetodeModal" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500">
              <option value="Transfer">Transfer</option>
              <option value="Cash">Cash</option>
              <option value="QRIS">QRIS</option>
            </select>
          </div>
        </div>
        
        <div class="form-group">
          <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
          <select id="editStatus" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500">
            <option value="Pending">Pending</option>
            <option value="Completed">Completed</option>
            <option value="Cancelled">Cancelled</option>
          </select>
        </div>
        
        <div class="form-group">
          <label class="block text-sm font-medium text-gray-700 mb-1">Proof</label>
          <select id="editProof" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500">
            <option value="sudah">Sudah</option>
            <option value="belum">Belum</option>
          </select>
        </div>
        
        <div class="form-group">
          <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
          <textarea id="editCatatan" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500"></textarea>
        </div>
        
        <div class="flex justify-end space-x-3 pt-2">
          <button type="button" id="cancelEdit" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Batal</button>
          <button type="submit" class="px-4 py-2 bg-pink-600 text-white rounded-md hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>

  <footer class="mt-auto">
    <div class="container mx-auto px-6 py-4 text-center text-pink-900">
      <p>&copy; 2025 Vermont Store • All Rights Reserved</p>
    </div>
  </footer>

  <script>
    // Mobile Menu Functionality
    document.addEventListener('DOMContentLoaded', function() {
      const menuButton = document.querySelector('.mobile-menu-button');
      const mobileMenu = document.querySelector('.mobile-menu');
      const overlay = document.querySelector('.mobile-menu-overlay');
      const closeButton = document.querySelector('.mobile-menu-close');
      
      function toggleMenu() {
        menuButton.classList.toggle('active');
        mobileMenu.classList.toggle('active');
        overlay.classList.toggle('active');
        document.body.style.overflow = menuButton.classList.contains('active') ? 'hidden' : '';
        
        // Animate hamburger icon
        const spans = menuButton.querySelectorAll('span');
        if (menuButton.classList.contains('active')) {
          spans[0].style.transform = 'rotate(45deg) translate(5px, 5px)';
          spans[1].style.opacity = '0';
          spans[2].style.transform = 'rotate(-45deg) translate(7px, -6px)';
        } else {
          spans[0].style.transform = '';
          spans[1].style.opacity = '';
          spans[2].style.transform = '';
        }
      }
      
      menuButton.addEventListener('click', toggleMenu);
      overlay.addEventListener('click', toggleMenu);
      closeButton.addEventListener('click', toggleMenu);
      
      // Close mobile menu when clicking on links
      document.querySelectorAll('.mobile-menu-content a').forEach(link => {
        link.addEventListener('click', toggleMenu);
      });
    });
  </script>
  
  <script src="assets/js/penjualan_listv4.js"></script>
</body>
</html>
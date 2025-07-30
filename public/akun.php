<?php
require_once __DIR__ . '/auth_check.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manajemen Akun - Vermont Store</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #fff1f2;
    }
    
    .navbar {
      background: linear-gradient(135deg, #fbcfe8 0%, #f9a8d4 100%);
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
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
    
    .logo {
      transition: all 0.3s ease;
    }
    
    .logo:hover {
      transform: rotate(-10deg) scale(1.1);
    }
    
    .form-container {
      background-color: white;
      border-radius: 0.75rem;
      box-shadow: 0 4px 6px rgba(0,0,0,0.05);
      border: 1px solid #fce7f3;
    }
    
    .input-field {
      border: 1px solid #fbcfe8;
      transition: all 0.2s ease;
    }
    
    .input-field:focus {
      border-color: #f472b6;
      box-shadow: 0 0 0 2px #fce7f3;
      outline: none;
    }
    
    .submit-btn {
      background: linear-gradient(135deg, #f9a8d4 0%, #ec4899 100%);
      transition: all 0.3s ease;
    }
    
    .submit-btn:hover {
      background: linear-gradient(135deg, #ec4899 0%, #db2777 100%);
      transform: translateY(-1px);
      box-shadow: 0 4px 8px rgba(236, 72, 153, 0.2);
    }
    
    .table-container {
      background-color: white;
      border-radius: 0.75rem;
      box-shadow: 0 4px 6px rgba(0,0,0,0.05);
      border: 1px solid #fce7f3;
      overflow-x: auto;
    }
    
    .table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
    }
    
    .table th {
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
    }
    
    .table td {
      padding: 0.75rem 1rem;
      border-bottom: 1px solid #fce7f3;
      color: #4c0519;
      font-size: 0.875rem;
    }
    
    .table tr:last-child td {
      border-bottom: none;
    }
    
    .table tr:hover td {
      background-color: #fdf2f8;
    }
    
    .action-btn {
      padding: 0.375rem 0.75rem;
      border-radius: 0.375rem;
      font-size: 0.75rem;
      font-weight: 500;
      transition: all 0.2s ease;
    }
    
    .edit-btn {
      background-color: #f9a8d4;
      color: #831843;
    }
    
    .edit-btn:hover {
      background-color: #f472b6;
      color: white;
    }
    
    .delete-btn {
      background-color: #fecdd3;
      color: #be123c;
    }
    
    .delete-btn:hover {
      background-color: #fda4af;
      color: white;
    }
    
    .modal {
      background-color: rgba(0,0,0,0.5);
      animation: fadeIn 0.3s;
    }
    
    .modal-content {
      background-color: white;
      border-radius: 0.75rem;
      box-shadow: 0 4px 20px rgba(0,0,0,0.15);
      animation: slideDown 0.3s;
    }
    
    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }
    
    @keyframes slideDown {
      from { transform: translateY(-20px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
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
    
    @media (max-width: 768px) {
      .form-grid {
        grid-template-columns: 1fr;
      }
      
      .table th, .table td {
        padding: 0.5rem;
        font-size: 0.75rem;
      }
      
      .action-group {
        flex-direction: column;
        gap: 0.25rem;
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
          <h1 class="text-xl font-bold">Vermont Store</h1>
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
          <a href="manajemen_akun.php" class="flex items-center space-x-1 text-pink-700 active">
            <span>👥</span>
            <span>Akun</span>
          </a>
          <a href="logout.php" class="flex items-center space-x-1 text-red-600 font-semibold hover:text-red-800">
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
          <a href="index.php" class="flex items-center space-x-2 hover:text-pink-700 py-2">
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
          <a href="manajemen_akun.php" class="flex items-center space-x-2 text-pink-700 py-2">
            <span>👥</span>
            <span>Akun</span>
          </a>
          <a href="logout.php" class="flex items-center space-x-2 py-2 text-red-600 font-medium hover:text-red-800">
            <span>🚪</span>
            <span>Logout</span>
          </a>
        </div>
      </div>
    </div>
  </header>

  <main class="flex-grow container mx-auto p-4 md:p-6">
    <h2 class="text-2xl font-bold text-pink-800 mb-6 animate__animated animate__fadeIn">👥 Manajemen Akun</h2>
    
    <div class="form-container p-4 md:p-6 mb-8 animate__animated animate__fadeInUp">
      <h3 class="text-xl font-semibold text-pink-700 mb-4">Tambah Akun Baru</h3>
      <form id="akunForm" class="space-y-4">
        <div class="grid gap-4 md:grid-cols-2">
          <div>
            <label class="block text-sm font-medium text-pink-800 mb-1">Username</label>
            <input type="text" id="username" placeholder="Masukkan username" required class="input-field w-full p-2 rounded-lg">
          </div>
          <div>
            <label class="block text-sm font-medium text-pink-800 mb-1">Password</label>
            <input type="password" id="password" placeholder="Masukkan password" required class="input-field w-full p-2 rounded-lg">
          </div>
          <div>
            <label class="block text-sm font-medium text-pink-800 mb-1">Jadwal Promosi</label>
            <select id="jadwal_promosi" class="input-field w-full p-2 rounded-lg">
              <option value="">Pilih Jadwal</option>
              <option value="pagi sore">Pagi-Sore</option>
              <option value="sore malam">Sore-Malam</option>
              <option value="malam close">Malam-Close</option>
              <option value="on hold">On Hold</option>
              <option value="sus">SUS</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-pink-800 mb-1">Status</label>
            <select id="status" class="input-field w-full p-2 rounded-lg">
              <option value="">Pilih Status</option>
              <option value="active">Active</option>
              <option value="on hold">On Hold</option>
              <option value="sus">SUS</option>
            </select>
          </div>
        </div>
        <button type="submit" class="submit-btn text-white px-4 py-2 rounded-lg mt-4 w-full md:w-auto">
          Tambah Akun
        </button>
      </form>
    </div>

    <div class="animate__animated animate__fadeInUp">
      <h3 class="text-xl font-semibold text-pink-700 mb-4">Daftar Akun</h3>
      <div class="table-container">
        <table class="table" id="akunTable">
          <thead>
            <tr>
              <th>ID</th>
              <th>Username</th>
              <th>Password</th>
              <th>Jadwal Promosi</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody id="akunTableBody">
            <tr>
              <td colspan="6" class="text-center p-6 text-pink-600">Memuat data...</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </main>

  <!-- Modal Edit Akun -->
  <div id="editModal" class="modal hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="modal-content p-6 w-full max-w-md">
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-xl font-bold text-pink-800">✏️ Edit Akun</h3>
        <button onclick="closeEditModal()" class="close text-2xl text-pink-800 hover:text-pink-600">&times;</button>
      </div>
      <form id="editAkunForm" class="space-y-4">
        <input type="hidden" id="edit_id">
        <div>
          <label class="block text-sm font-medium text-pink-800 mb-1">Username</label>
          <input type="text" id="edit_username" class="input-field w-full p-2 rounded-lg">
        </div>
        <div>
          <label class="block text-sm font-medium text-pink-800 mb-1">Password</label>
          <input type="text" id="edit_password" class="input-field w-full p-2 rounded-lg">
        </div>
        <div>
          <label class="block text-sm font-medium text-pink-800 mb-1">Jadwal Promosi</label>
          <select id="edit_jadwal_promosi" class="input-field w-full p-2 rounded-lg">
            <option value="pagi sore">Pagi-Sore</option>
            <option value="sore malam">Sore-Malam</option>
            <option value="malam close">Malam-Close</option>
            <option value="on hold">On Hold</option>
            <option value="sus">SUS</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-pink-800 mb-1">Status</label>
          <select id="edit_status" class="input-field w-full p-2 rounded-lg">
            <option value="active">Active</option>
            <option value="on hold">On Hold</option>
            <option value="sus">SUS</option>
          </select>
        </div>
        <div class="flex justify-end space-x-3 pt-4">
          <button type="button" onclick="closeEditModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
            Batal
          </button>
          <button type="submit" class="submit-btn text-white px-4 py-2 rounded-lg">
            Simpan Perubahan
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Footer -->
  <footer class="mt-auto">
    <div class="container mx-auto px-6 py-4 text-center text-pink-900">
      <p>&copy; 2025 Vermont Store • All Rights Reserved</p>
    </div>
  </footer>

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
  <script src="assets/js/akun.js"></script>
</body>
</html>
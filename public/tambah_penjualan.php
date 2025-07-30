<?php
require_once __DIR__ . '/auth_check.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Tambah Penjualan</title>
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
    
    input:focus, select:focus, textarea:focus {
      border-color: #f9a8d4 !important;
      box-shadow: 0 0 0 1px #f9a8d4 !important;
    }
    
    /* Enhanced Autocomplete Styles */
    .autocomplete-container {
      position: relative;
    }
    
    .autocomplete-dropdown {
      position: absolute;
      top: calc(100% + 0.25rem);
      left: 0;
      right: 0;
      background: white;
      border-radius: 0.75rem;
      box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
      z-index: 50;
      max-height: 350px;
      overflow-y: auto;
      display: none;
      border: 1px solid #fbcfe8;
      padding: 0.5rem;
    }
    
    .autocomplete-dropdown.show {
      display: block;
      animation: fadeInSlide 0.25s ease-out;
    }
    
    .autocomplete-category {
      padding: 0.5rem 0.75rem;
      font-size: 0.8rem;
      font-weight: 600;
      color: #9d174d;
      background-color: #fce7f3;
      border-radius: 0.375rem;
      margin-bottom: 0.25rem;
      position: sticky;
      top: 0;
      z-index: 10;
    }
    
    .autocomplete-item {
      padding: 0.75rem 1rem;
      cursor: pointer;
      transition: all 0.2s;
      border-radius: 0.5rem;
      display: flex;
      align-items: center;
      margin-bottom: 0.25rem;
      position: relative;
    }
    
    .autocomplete-item:last-child {
      margin-bottom: 0;
    }
    
    .autocomplete-item:hover {
      background-color: #fdf2f8;
      transform: translateX(2px);
    }
    
    .autocomplete-item.active {
      background-color: #fce7f3;
      box-shadow: 0 0 0 2px #f9a8d4;
    }
    
    .autocomplete-item .product-icon {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      background-color: #fce7f3;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 0.75rem;
      flex-shrink: 0;
      color: #ec4899;
      font-size: 1rem;
    }
    
    .autocomplete-item .product-content {
      flex-grow: 1;
      min-width: 0;
    }
    
    .autocomplete-item .product-name {
      font-weight: 500;
      color: #831843;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    
    .autocomplete-item .product-details {
      display: flex;
      justify-content: space-between;
      margin-top: 0.25rem;
    }
    
    .autocomplete-item .product-duration {
      font-size: 0.75rem;
      color: #9d174d;
      background-color: #fce7f3;
      padding: 0.15rem 0.5rem;
      border-radius: 1rem;
    }
    
    .autocomplete-item .product-price {
      font-weight: 600;
      color: #ec4899;
      font-size: 0.9rem;
    }
    
    .no-results {
      padding: 1.5rem;
      text-align: center;
      color: #9d174d;
      font-size: 0.9rem;
    }
    
    @keyframes fadeInSlide {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    /* Custom scrollbar */
    .autocomplete-dropdown::-webkit-scrollbar {
      width: 6px;
    }
    
    .autocomplete-dropdown::-webkit-scrollbar-track {
      background: #fce7f3;
      border-radius: 10px;
    }
    
    .autocomplete-dropdown::-webkit-scrollbar-thumb {
      background: #f9a8d4;
      border-radius: 10px;
    }
    
    .autocomplete-dropdown::-webkit-scrollbar-thumb:hover {
      background: #ec4899;
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
          <a href="tambah_hutang.php" class="flex items-center space-x-1 hover:text-pink-700">
            <span>📋</span>
            <span>Hutang</span>
          </a>
          <a href="tambah_saldo.php" class="flex items-center space-x-1 hover:text-pink-700">
            <span>💳</span>
            <span>Saldo</span>
          </a>
          <a href="tambah_penjualan.php" class="flex items-center space-x-1 text-pink-700 active">
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
          <a href="tambah_hutang.php" class="flex items-center space-x-2 hover:text-pink-700 py-2">
            <span>📋</span>
            <span>Hutang</span>
          </a>
          <a href="tambah_saldo.php" class="flex items-center space-x-2 hover:text-pink-700 py-2">
            <span>💳</span>
            <span>Saldo</span>
          </a>
          <a href="tambah_penjualan.php" class="flex items-center space-x-2 text-pink-700 active py-2">
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
    <h2 class="text-3xl font-bold text-pink-800 mb-8 animate__animated animate__fadeIn">🛒 Tambah Data Penjualan</h2>

    <div class="bg-white rounded-xl shadow-md p-6 animate__animated animate__fadeInUp">
      <form id="penjualanForm" class="space-y-6">
        <!-- Nama Produk with Enhanced Autocomplete -->
        <div class="autocomplete-container">
          <label for="nama_produk" class="block text-sm font-medium text-pink-700 mb-2">Nama Produk:</label>
          <input type="text" id="nama_produk" autocomplete="off" required
                 class="w-full px-4 py-3 border border-pink-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-300 text-pink-800" 
                 placeholder="Cari produk..." />
          <div id="produkDropdown" class="autocomplete-dropdown"></div>
        </div>

        <!-- Durasi/Jumlah -->
        <div>
          <label for="durasi" class="block text-sm font-medium text-pink-700 mb-2">Durasi / Jumlah:</label>
          <input type="number" id="durasi" required
                 class="w-full px-4 py-2 border border-pink-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-pink-300" />
        </div>

        <!-- Harga Beli -->
        <div>
          <label for="harga_beli" class="block text-sm font-medium text-pink-700 mb-2">Harga Beli (modal):</label>
          <input type="number" id="harga_beli" required
                 class="w-full px-4 py-2 border border-pink-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-pink-300" />
        </div>

        <!-- Metode Pembayaran Modal -->
        <div>
          <label for="metode_modal" class="block text-sm font-medium text-pink-700 mb-2">Metode Pembayaran Modal:</label>
          <select id="metode_modal" required
                  class="w-full px-4 py-2 border border-pink-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-pink-300">
            <option value="Gopay">Gopay</option>
            <option value="Dana">Dana</option>
            <option value="OVO">OVO</option>
            <option value="BCA">BCA</option>
            <option value="Spay">ShopeePay</option>
          </select>
        </div>

        <!-- Harga Jual -->
        <div>
          <label for="harga_jual" class="block text-sm font-medium text-pink-700 mb-2">Harga Jual:</label>
          <input type="number" id="harga_jual" required
                 class="w-full px-4 py-2 border border-pink-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-pink-300" />
        </div>

        <!-- Metode Pembayaran Customer -->
        <div>
          <label for="metode_customer" class="block text-sm font-medium text-pink-700 mb-2">Metode Pembayaran Customer:</label>
          <select id="metode_customer" required
                  class="w-full px-4 py-2 border border-pink-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-pink-300">
            <option value="Gopay">Gopay</option>
            <option value="Dana">Dana</option>
            <option value="OVO">OVO</option>
            <option value="BCA">BCA</option>
            <option value="QRIS">QRIS</option>
            <option value="Spay">ShopeePay</option>
          </select>
        </div>

        <!-- No. Customer -->
        <div>
          <label for="no_customer" class="block text-sm font-medium text-pink-700 mb-2">No. Customer (opsional):</label>
          <input type="text" id="no_customer"
                 class="w-full px-4 py-2 border border-pink-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-pink-300" />
        </div>

        <!-- Status -->
        <div>
          <label for="status" class="block text-sm font-medium text-pink-700 mb-2">Status:</label>
          <select id="status"
                  class="w-full px-4 py-2 border border-pink-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-pink-300">
            <option value="lunas">Lunas</option>
            <option value="belum">Belum Bayar</option>
          </select>
        </div>

        <!-- Catatan -->
        <div>
          <label for="catatan" class="block text-sm font-medium text-pink-700 mb-2">Catatan (opsional):</label>
          <textarea id="catatan" rows="3"
                    class="w-full px-4 py-2 border border-pink-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-pink-300"></textarea>
        </div>

        <!-- Submit Button -->
        <button type="submit" 
                class="w-full btn-submit px-6 py-3 bg-gradient-to-r from-pink-500 to-pink-600 text-white font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-300">
          Simpan Penjualan
        </button>
      </form>

      <!-- Status Message -->
      <div id="penjualanStatus" class="success-message mt-6 p-4 bg-pink-100 border-l-4 border-pink-500 text-pink-700 rounded-lg hidden animate__animated animate__fadeIn"></div>
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

    // Initialize autocomplete functionality
    function initAutocomplete() {
      const produkInput = document.getElementById('nama_produk');
      const dropdown = document.getElementById('produkDropdown');
      
      // Function to render products in dropdown
      function renderProducts(products) {
        dropdown.innerHTML = '';
        
        if (!products || products.length === 0) {
          dropdown.innerHTML = '<div class="no-results">Produk tidak ditemukan</div>';
          return;
        }
        
        // Group products by category
        const categories = {};
        products.forEach(product => {
          if (!categories[product.category]) {
            categories[product.category] = [];
          }
          categories[product.category].push(product);
        });
        
        // Render each category
        Object.entries(categories).forEach(([category, items]) => {
          const categoryHeader = document.createElement('div');
          categoryHeader.className = 'autocomplete-category';
          categoryHeader.textContent = category;
          dropdown.appendChild(categoryHeader);
          
          items.forEach(item => {
            const itemElement = document.createElement('div');
            itemElement.className = 'autocomplete-item';
            itemElement.innerHTML = `
              <div class="product-icon">${item.icon || '🛒'}</div>
              <div class="product-content">
                <div class="product-name">${item.name}</div>
                <div class="product-details">
                  ${item.duration ? `<span class="product-duration">${item.duration}</span>` : ''}
                  ${item.price ? `<span class="product-price">${item.price}</span>` : ''}
                </div>
              </div>
            `;
            
            itemElement.addEventListener('click', function() {
              produkInput.value = `${item.name} ${item.duration || ''}`.trim();
              dropdown.classList.remove('show');
              
              // Auto-fill other fields if needed
              if (item.price) {
                document.getElementById('harga_jual').value = item.price.replace(/\D/g, '');
              }
              if (item.duration) {
                document.getElementById('durasi').value = item.duration.replace(/\D/g, '') || '1';
              }
            });
            
            dropdown.appendChild(itemElement);
          });
        });
      }

      // Debounce function to limit API calls
      let debounceTimer;
      function debounce(callback, delay) {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(callback, delay);
      }

      // Input event handler
      produkInput.addEventListener('input', function() {
        const searchTerm = this.value.trim();
        
        if (searchTerm.length < 1) {
          dropdown.classList.remove('show');
          return;
        }
        
        debounce(async () => {
          try {
            // Call the function from penjualanv3.js to get products
            if (typeof getProducts === 'function') {
              const products = await getProducts(searchTerm);
              renderProducts(products);
              dropdown.classList.add('show');
            } else {
              console.error('getProducts function not found in penjualanv3.js');
            }
          } catch (error) {
            console.error('Error fetching products:', error);
            dropdown.innerHTML = '<div class="no-results">Gagal memuat produk</div>';
            dropdown.classList.add('show');
          }
        }, 300);
      });

      // Close dropdown when clicking outside
      document.addEventListener('click', function(e) {
        if (!produkInput.contains(e.target)) {
          dropdown.classList.remove('show');
        }
      });

      // Keyboard navigation
      produkInput.addEventListener('keydown', function(e) {
        const items = dropdown.querySelectorAll('.autocomplete-item');
        let activeItem = dropdown.querySelector('.autocomplete-item.active');
        
        if (e.key === 'ArrowDown') {
          e.preventDefault();
          if (!activeItem) {
            items[0]?.classList.add('active');
          } else {
            activeItem.classList.remove('active');
            const next = activeItem.nextElementSibling;
            if (next && !next.classList.contains('autocomplete-category')) {
              next.classList.add('active');
              next.scrollIntoView({ block: 'nearest' });
            } else {
              items[0]?.classList.add('active');
            }
          }
        } else if (e.key === 'ArrowUp') {
          e.preventDefault();
          if (activeItem) {
            activeItem.classList.remove('active');
            let prev = activeItem.previousElementSibling;
            
            // Skip category headers
            while (prev && prev.classList.contains('autocomplete-category')) {
              prev = prev.previousElementSibling;
            }
            
            if (prev) {
              prev.classList.add('active');
              prev.scrollIntoView({ block: 'nearest' });
            } else {
              items[items.length - 1]?.classList.add('active');
            }
          }
        } else if (e.key === 'Enter' && activeItem) {
          e.preventDefault();
          const name = activeItem.querySelector('.product-name').textContent;
          const duration = activeItem.querySelector('.product-duration')?.textContent || '';
          produkInput.value = `${name} ${duration}`.trim();
          dropdown.classList.remove('show');
        }
      });
    }

    // Initialize form submission
    function initFormSubmission() {
      document.getElementById('penjualanForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = {
          produk: document.getElementById('nama_produk').value,
          durasi: document.getElementById('durasi').value,
          harga_beli: document.getElementById('harga_beli').value,
          metode_modal: document.getElementById('metode_modal').value,
          harga_jual: document.getElementById('harga_jual').value,
          metode_customer: document.getElementById('metode_customer').value,
          no_customer: document.getElementById('no_customer').value,
          status: document.getElementById('status').value,
          catatan: document.getElementById('catatan').value
        };
        
        try {
          // Call the function from penjualanv3.js to save the sale
          if (typeof saveSale === 'function') {
            const result = await saveSale(formData);
            
            // Show success message
            const statusDiv = document.getElementById('penjualanStatus');
            statusDiv.textContent = 'Penjualan berhasil disimpan!';
            statusDiv.classList.remove('hidden');
            
            // Reset form
            this.reset();
            
            // Hide message after 3 seconds
            setTimeout(() => {
              statusDiv.classList.add('hidden');
            }, 3000);
          } else {
            throw new Error('saveSale function not found in penjualanv3.js');
          }
        } catch (error) {
          console.error('Error:', error);
          const statusDiv = document.getElementById('penjualanStatus');
          statusDiv.textContent = error.message || 'Gagal menyimpan penjualan';
          statusDiv.classList.remove('hidden');
        }
      });
    }

    // Initialize when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
      initAutocomplete();
      initFormSubmission();
    });
  </script>
  
  <!-- Your existing penjualanv3.js file -->
  <script src="assets/js/penjualanv3.js"></script>
</body>
</html>
console.log('✅ penjualan.js loaded');

document.addEventListener('DOMContentLoaded', () => {
  const namaInput = document.getElementById('nama_produk');
  const dropdown = document.getElementById('produkDropdown');
  const hargaJualInput = document.getElementById('harga_jual');

  let timeout = null;

  // Autocomplete produk
  namaInput.addEventListener('input', () => {
    const keyword = namaInput.value.trim();
    clearTimeout(timeout);

    if (keyword.length === 0) {
      dropdown.innerHTML = '';
      dropdown.classList.remove('show');
      return;
    }

    timeout = setTimeout(async () => {
      try {
        const response = await fetch(`/toko_penjualan/public/get_produk.php?keyword=${encodeURIComponent(keyword)}`);
        const data = await response.json();

        console.log('Data produk:', data);

        dropdown.innerHTML = '';
        dropdown.classList.remove('show');

        if (data.length === 0) {
          dropdown.innerHTML = '<div>Tidak ditemukan</div>';
          dropdown.classList.add('show');
          return;
        }

        data.forEach((produk) => {
          const item = document.createElement('div');
          item.textContent = `${produk.nama} (Rp ${produk.harga_jual})`;
          item.dataset.nama = produk.nama;
          item.dataset.harga = produk.harga_jual;

          item.addEventListener('click', () => {
            namaInput.value = produk.nama;
            hargaJualInput.value = produk.harga_jual;
            dropdown.innerHTML = '';
            dropdown.classList.remove('show');
          });

          dropdown.appendChild(item);
        });

        dropdown.classList.add('show');

      } catch (err) {
        console.error('Gagal mengambil produk:', err);
        dropdown.innerHTML = '<div>Terjadi kesalahan</div>';
        dropdown.classList.add('show');
      }
    }, 300);
  });

  document.addEventListener('click', (e) => {
    if (!dropdown.contains(e.target) && e.target !== namaInput) {
      dropdown.innerHTML = '';
      dropdown.classList.remove('show');
    }
  });

  // Handle submit form penjualan
  document.getElementById('penjualanForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    const input = {
      nama_produk: namaInput.value,
      durasi_atau_jumlah: parseInt(document.getElementById('durasi').value),
      harga_beli: parseFloat(document.getElementById('harga_beli').value),
      harga_jual: parseFloat(hargaJualInput.value),
      metode_customer: document.getElementById('metode_customer').value,
      metode_modal: document.getElementById('metode_modal').value,
      no_customer: document.getElementById('no_customer').value || null,
      status: document.getElementById('status').value || 'lunas',
      catatan: document.getElementById('catatan').value || null
    };

    const query = `
      mutation InsertPenjualan(
        $nama_produk: String!,
        $durasi_atau_jumlah: Int!,
        $harga_beli: Float!,
        $harga_jual: Float!,
        $metode_customer: String!,
        $metode_modal: String!,
        $no_customer: String,
        $status: String,
        $catatan: String
      ) {
        insertPenjualan(
          nama_produk: $nama_produk,
          durasi_atau_jumlah: $durasi_atau_jumlah,
          harga_beli: $harga_beli,
          harga_jual: $harga_jual,
          metode_customer: $metode_customer,
          metode_modal: $metode_modal,
          no_customer: $no_customer,
          status: $status,
          catatan: $catatan
        ) {
          success
          message
        }
      }
    `;

    try {
      const response = await fetch("graphql/index.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ query, variables: input })
      });

      const result = await response.json();
      console.log('Response:', result);

      const data = result.data?.insertPenjualan;
      const statusElem = document.getElementById("penjualanStatus");

      if (data?.success) {
        statusElem.textContent = data.message;
        statusElem.style.color = "green";
        e.target.reset();
        hargaJualInput.value = '';
      } else {
        statusElem.textContent = data?.message || "Gagal menyimpan penjualan.";
        statusElem.style.color = "red";
      }

    } catch (error) {
      console.error("Gagal kirim data penjualan:", error);
      document.getElementById("penjualanStatus").textContent = "Terjadi kesalahan koneksi.";
    }
  });
});

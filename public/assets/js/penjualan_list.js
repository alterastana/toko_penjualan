document.addEventListener('DOMContentLoaded', loadPenjualan);

function formatRupiah(angka) {
  return 'Rp ' + Number(angka).toLocaleString('id-ID');
}

function loadPenjualan() {
  fetch('graphql/index.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      query: `
        query {
          getAllPenjualan {
            tanggal
            nama_produk
            durasi_atau_jumlah
            harga_beli
            harga_jual
            status
          }
        }
      `
    })
  })
    .then(response => response.json())
    .then(result => {
      if (result.errors) throw new Error(result.errors[0].message);

      const data = result.data.getAllPenjualan;
      const tbody = document.getElementById('tabel-penjualan');

      if (!data.length) {
        tbody.innerHTML = '<tr><td colspan="6">Belum ada data penjualan.</td></tr>';
        return;
      }

      const rows = data.map(item => `
        <tr>
          <td>${item.tanggal}</td>
          <td>${item.nama_produk}</td>
          <td>${item.durasi_atau_jumlah}</td>
          <td>${formatRupiah(item.harga_beli)}</td>
          <td>${formatRupiah(item.harga_jual)}</td>
          <td>${item.status}</td>
        </tr>
      `).join('');

      tbody.innerHTML = rows;
    })
    .catch(error => {
      console.error('Gagal ambil data penjualan:', error);
      const errorBox = document.getElementById('error');
      errorBox.style.display = 'block';
      errorBox.textContent = 'Gagal memuat data: ' + error.message;
    });
}

let semuaPenjualan = [];
let limit = 5;
let offset = 0;
let habisData = false;

document.addEventListener('DOMContentLoaded', () => {
  loadPenjualan();

  // Event pencarian
  document.getElementById('searchInput').addEventListener('input', (e) => {
    const keyword = e.target.value.toLowerCase().trim();
    const hasil = semuaPenjualan.filter(item => {
      const idString = item.id_penjualan ? item.id_penjualan.toString() : '';
      const nama = item.nama_produk ? item.nama_produk.toLowerCase() : '';
      const noCustomer = item.no_customer ? item.no_customer.toLowerCase() : '';
      const status = item.status ? item.status.toLowerCase() : '';
      const tanggal = item.tanggal ? item.tanggal.toLowerCase() : '';

      // Jika keyword hanya angka, cari ID yang sama persis
      if (/^\d+$/.test(keyword)) {
        return idString === keyword;
      }

      // Pencarian umum
      return (
        idString.includes(keyword) ||
        nama.includes(keyword) ||
        noCustomer.includes(keyword) ||
        status.includes(keyword) ||
        tanggal.includes(keyword)
      );
    });

    renderTabel(hasil, keyword);
  });

  // Tutup modal
  document.getElementById('closeModal').addEventListener('click', () => {
    document.getElementById('editModal').style.display = 'none';
  });

  // Submit edit form
  document.getElementById('editForm').addEventListener('submit', simpanEdit);

  // Tombol Show More
  document.getElementById('btnShowMore').addEventListener('click', () => {
    loadPenjualan(true);
  });
});

function formatRupiah(angka) {
  return 'Rp ' + Number(angka).toLocaleString('id-ID');
}

function loadPenjualan(showMore = false) {
  if (habisData) return;

  fetch('graphql/index.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      query: `
        query ($limit: Int!, $offset: Int!) {
          getPenjualanLimit(limit: $limit, offset: $offset) {
            id_penjualan
            tanggal
            nama_produk
            durasi_atau_jumlah
            harga_beli
            harga_jual
            no_customer
            status
            total_untung
            pengeluaran
            catatan
            untung
            metode_customer
            metode_modal
            proof
          }
        }
      `,
      variables: { limit, offset }
    })
  })
    .then(res => res.json())
    .then(result => {
      if (result.errors) throw new Error(result.errors[0].message);
      const dataBaru = result.data.getPenjualanLimit;

      if (dataBaru.length < limit) {
        habisData = true;
        document.getElementById('btnShowMore').style.display = 'none';
      }

      offset += dataBaru.length;

      if (showMore) {
        semuaPenjualan = [...semuaPenjualan, ...dataBaru];
      } else {
        semuaPenjualan = dataBaru;
      }

      renderTabel(semuaPenjualan);
    })
    .catch(error => {
      console.error('Gagal ambil data penjualan:', error);
      document.getElementById('error').style.display = 'block';
      document.getElementById('error').textContent = 'Gagal memuat data: ' + error.message;
    });
}

function renderTabel(data, keyword = '') {
  const tbody = document.getElementById('tabel-penjualan');
  if (!data.length) {
    tbody.innerHTML = '<tr><td colspan="16" class="text-center py-4 text-pink-600">Tidak ada data penjualan.</td></tr>';
    return;
  }

  tbody.innerHTML = data.map(item => {
    const highlightClass = keyword && (
      item.id_penjualan.toString() === keyword ||
      item.nama_produk?.toLowerCase().includes(keyword) ||
      item.no_customer?.toLowerCase().includes(keyword) ||
      item.status?.toLowerCase().includes(keyword) ||
      item.tanggal?.toLowerCase().includes(keyword)
    ) ? ' style="background-color: #fce7f3;"' : '';

    return `
      <tr${highlightClass}>
        <td>${item.id_penjualan}</td>
        <td>${item.tanggal}</td>
        <td>${item.nama_produk}</td>
        <td>${item.durasi_atau_jumlah}</td>
        <td>${formatRupiah(item.harga_beli)}</td>
        <td>${formatRupiah(item.harga_jual)}</td>
        <td>${item.no_customer || '-'}</td>
        <td>${item.status || '-'}</td>
        <td>${formatRupiah(item.total_untung)}</td>
        <td>${formatRupiah(item.pengeluaran)}</td>
        <td>${formatRupiah(item.untung)}</td>
        <td>${item.catatan || '-'}</td>
        <td>${item.metode_customer}</td>
        <td>${item.metode_modal}</td>
        <td>
          <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
            item.proof === 'sudah' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'
          }">
            ${item.proof || 'belum'}
          </span>
        </td>
        <td>
          <button class="action-btn edit-btn" onclick="bukaEdit('${item.id_penjualan}')">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            Edit
          </button>
        </td>
      </tr>
    `;
  }).join('');
}

function bukaEdit(id) {
  const data = semuaPenjualan.find(p => p.id_penjualan.toString() === id.toString());
  if (!data) return;

  document.getElementById('editId').value = data.id_penjualan;
  document.getElementById('editNamaProduk').value = data.nama_produk;
  document.getElementById('editDurasiJumlah').value = data.durasi_atau_jumlah;
  document.getElementById('editHargaBeli').value = data.harga_beli;
  document.getElementById('editHargaJual').value = data.harga_jual;
  document.getElementById('editMetodeCustomer').value = data.metode_customer;
  document.getElementById('editMetodeModal').value = data.metode_modal;
  document.getElementById('editStatus').value = data.status || '';
  document.getElementById('editProof').value = data.proof || 'belum';
  document.getElementById('editCatatan').value = data.catatan || '';

  document.getElementById('editModal').style.display = 'block';
}

function simpanEdit(e) {
  e.preventDefault();

  const id = parseInt(document.getElementById('editId').value);
  const input = {
    id,
    nama_produk: document.getElementById('editNamaProduk').value,
    durasi_atau_jumlah: parseInt(document.getElementById('editDurasiJumlah').value),
    harga_beli: parseFloat(document.getElementById('editHargaBeli').value),
    harga_jual: parseFloat(document.getElementById('editHargaJual').value),
    metode_customer: document.getElementById('editMetodeCustomer').value,
    metode_modal: document.getElementById('editMetodeModal').value,
    status: document.getElementById('editStatus').value,
    proof: document.getElementById('editProof').value,
    catatan: document.getElementById('editCatatan').value
  };

  const query = `
    mutation UpdatePenjualan($id: Int!, $nama_produk: String!, $durasi_atau_jumlah: Int!, $harga_beli: Float!, $harga_jual: Float!, $metode_customer: String!, $metode_modal: String!, $status: String, $proof: String, $catatan: String) {
      updatePenjualan(id: $id, nama_produk: $nama_produk, durasi_atau_jumlah: $durasi_atau_jumlah, harga_beli: $harga_beli, harga_jual: $harga_jual, metode_customer: $metode_customer, metode_modal: $metode_modal, status: $status, proof: $proof, catatan: $catatan) {
        success
        message
      }
    }
  `;

  fetch('graphql/index.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ query, variables: input })
  })
    .then(res => res.json())
    .then(result => {
      alert(result.data?.updatePenjualan?.message || 'Perubahan disimpan.');
      document.getElementById('editModal').style.display = 'none';
      offset = 0;
      habisData = false;
      loadPenjualan(); // refresh tabel
    })
    .catch(err => {
      console.error('Gagal update:', err);
      alert('Gagal memperbarui data.');
    });
}

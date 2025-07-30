document.addEventListener('DOMContentLoaded', loadHutangList);

let hutangData = []; // untuk simpan semua data

function formatRupiah(angka) {
  return 'Rp ' + Number(angka).toLocaleString('id-ID');
}

function applyFilter() {
  const search = document.getElementById('searchInput').value.toLowerCase();
  const tbody = document.getElementById('tabel-hutang');

  const filtered = hutangData.filter(h =>
    h.nama.toLowerCase().includes(search) ||
    h.metode.toLowerCase().includes(search) ||
    (h.keterangan && h.keterangan.toLowerCase().includes(search))
  );

  if (!filtered.length) {
    tbody.innerHTML = '<tr><td colspan="6">Tidak ada data yang cocok.</td></tr>';
    return;
  }

  tbody.innerHTML = filtered.map(h => `
    <tr>
      <td>${h.tanggal}</td>
      <td>${h.nama}</td>
      <td>${h.jenis}</td>
      <td>${formatRupiah(h.jumlah)}</td>
      <td>${h.metode}</td>
      <td>${h.keterangan ?? '-'}</td>
    </tr>
  `).join('');
}

function loadHutangList() {
  fetch('graphql/index.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      query: `
        query {
          getAllHutang {
            tanggal
            nama
            jenis
            jumlah
            metode
            keterangan
          }
        }
      `
    })
  })
    .then(res => res.json())
    .then(result => {
      if (result.errors) throw new Error(result.errors[0].message);

      hutangData = result.data.getAllHutang;
      applyFilter(); // tampilkan semua data pertama kali
    })
    .catch(err => {
      const errorBox = document.getElementById('error');
      errorBox.style.display = 'block';
      errorBox.textContent = 'Gagal memuat data: ' + err.message;
    });
}

document.addEventListener('input', e => {
  if (e.target.id === 'searchInput') applyFilter();
});

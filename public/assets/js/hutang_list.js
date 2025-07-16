document.addEventListener('DOMContentLoaded', loadHutangList);

function formatRupiah(angka) {
  return 'Rp ' + Number(angka).toLocaleString('id-ID');
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

      const data = result.data.getAllHutang;
      const tbody = document.getElementById('tabel-hutang');

      if (!data.length) {
        tbody.innerHTML = '<tr><td colspan="6">Belum ada data hutang.</td></tr>';
        return;
      }

      const rows = data.map(h => `
        <tr>
          <td>${h.tanggal}</td>
          <td>${h.nama}</td>
          <td>${h.jenis}</td>
          <td>${formatRupiah(h.jumlah)}</td>
          <td>${h.metode}</td>
          <td>${h.keterangan ?? '-'}</td>
        </tr>
      `).join('');

      tbody.innerHTML = rows;
    })
    .catch(err => {
      const errorBox = document.getElementById('error');
      errorBox.style.display = 'block';
      errorBox.textContent = 'Gagal memuat data: ' + err.message;
    });
}

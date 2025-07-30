document.addEventListener("DOMContentLoaded", () => {
  const tableBody = document.getElementById("transaksiTableBody");
  const searchInput = document.getElementById("searchInput");
  const filterMethod = document.getElementById("filterMethod");
  const filterType = document.getElementById("filterType");
  const filterDate = document.getElementById("filterDate");
  const showMoreBtn = document.getElementById("showMoreBtn");

  let allData = [];
  let displayedCount = 0;
  const itemsPerPage = 10;

  // Fetch data dari server
  fetch("graphql/index.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      query: `
        query {
          getAllTransaksiDompet {
            id_transaksi
            tanggal
            metode
            jenis
            jumlah
            keterangan
            keperluan
          }
        }
      `
    })
  })
    .then(res => res.json())
    .then(data => {
      allData = (data.data?.getAllTransaksiDompet || []).sort((a, b) => {
        if (a.tanggal === b.tanggal) {
          return b.id_transaksi - a.id_transaksi; // ID terbaru dulu jika tanggal sama
        }
        return new Date(b.tanggal) - new Date(a.tanggal); // Tanggal terbaru dulu
      });
      displayedCount = 0;
      tableBody.innerHTML = "";
      if (allData.length === 0) {
        tableBody.innerHTML = `<tr><td colspan="7" class="text-center py-4 text-pink-500">Tidak ada data.</td></tr>`;
        return;
      }
      renderTable();
    })
    .catch(err => {
      console.error("Error fetching transaksi:", err);
      tableBody.innerHTML = `<tr><td colspan="7" class="text-center py-4 text-pink-500">Gagal memuat data.</td></tr>`;
    });

  function renderTable() {
    const searchTerm = searchInput.value.toLowerCase();
    const methodFilter = filterMethod.value;
    const typeFilter = filterType.value;
    const dateFilter = filterDate.value;

    const filtered = allData.filter(trx => {
      const matchesSearch =
        trx.id_transaksi.toString().includes(searchTerm) ||
        (trx.keterangan && trx.keterangan.toLowerCase().includes(searchTerm)) ||
        (trx.keperluan && trx.keperluan.toLowerCase().includes(searchTerm));
      const matchesMethod = !methodFilter || trx.metode === methodFilter;
      const matchesType = !typeFilter || trx.jenis === typeFilter;
      const matchesDate = !dateFilter || trx.tanggal === dateFilter;

      return matchesSearch && matchesMethod && matchesType && matchesDate;
    });

    const toShow = filtered.slice(0, displayedCount + itemsPerPage);
    displayedCount = toShow.length;

    tableBody.innerHTML = "";
    if (toShow.length === 0) {
      tableBody.innerHTML = `<tr><td colspan="7" class="text-center py-4 text-pink-500">Tidak ada data yang cocok.</td></tr>`;
    } else {
      toShow.forEach(trx => {
        const row = `
          <tr class="hover:bg-pink-50">
            <td class="px-6 py-4">${trx.id_transaksi}</td>
            <td class="px-6 py-4">${trx.tanggal}</td>
            <td class="px-6 py-4">${trx.metode}</td>
            <td class="px-6 py-4">${trx.jenis}</td>
            <td class="px-6 py-4">Rp ${parseFloat(trx.jumlah).toLocaleString()}</td>
            <td class="px-6 py-4">${trx.keterangan || "-"}</td>
            <td class="px-6 py-4">${trx.keperluan || "-"}</td>
          </tr>
        `;
        tableBody.insertAdjacentHTML("beforeend", row);
      });
    }

    if (filtered.length > displayedCount) {
      showMoreBtn.classList.remove("hidden");
    } else {
      showMoreBtn.classList.add("hidden");
    }
  }

  searchInput.addEventListener("input", () => {
    displayedCount = 0;
    renderTable();
  });
  filterMethod.addEventListener("change", () => {
    displayedCount = 0;
    renderTable();
  });
  filterType.addEventListener("change", () => {
    displayedCount = 0;
    renderTable();
  });
  filterDate.addEventListener("change", () => {
    displayedCount = 0;
    renderTable();
  });

  showMoreBtn.addEventListener("click", () => {
    renderTable();
  });
});

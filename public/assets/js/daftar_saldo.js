async function loadSaldoDigital() {
  const query = `
    query {
      getAllSaldoDigital {
        metode
        jumlah
      }
    }
  `;

  try {
    const response = await fetch("graphql/index.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ query })
    });

    const result = await response.json();
    const tbody = document.querySelector("#saldoTable tbody");

    if (result.errors) {
      tbody.innerHTML = `<tr><td colspan="2">Gagal memuat data</td></tr>`;
      return;
    }

    const data = result.data?.getAllSaldoDigital || [];
    if (data.length === 0) {
      tbody.innerHTML = `<tr><td colspan="2">Tidak ada data saldo</td></tr>`;
      return;
    }

    tbody.innerHTML = "";
    data.forEach(item => {
      const tr = document.createElement("tr");
      tr.innerHTML = `
        <td>${item.metode}</td>
        <td>Rp ${Number(item.jumlah).toLocaleString("id-ID")}</td>
      `;
      tbody.appendChild(tr);
    });
  } catch (err) {
    console.error("Fetch error:", err);
    document.querySelector("#saldoTable tbody").innerHTML = `<tr><td colspan="2">Gagal memuat data</td></tr>`;
  }
}

window.addEventListener("DOMContentLoaded", loadSaldoDigital);

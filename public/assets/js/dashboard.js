async function fetchAset() {
  try {
    const response = await fetch("graphql/index.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        query: `
          query {
            totalAset {
              total_saldo
              total_piutang
              total_aset
            }
          }
        `
      })
    });

    const result = await response.json();
    const aset = result.data?.totalAset;

    if (!aset) throw new Error("Data kosong");

    document.getElementById("saldo").textContent = `Rp ${parseFloat(aset.total_saldo).toLocaleString("id-ID")}`;
    document.getElementById("piutang").textContent = `Rp ${parseFloat(aset.total_piutang).toLocaleString("id-ID")}`;
    document.getElementById("total").textContent = `Rp ${parseFloat(aset.total_aset).toLocaleString("id-ID")}`;
  } catch (err) {
    document.getElementById("error").classList.add("show");
    console.error("Gagal memuat aset:", err);
  }
}

// Jalankan saat halaman selesai dimuat
fetchAset();

document.getElementById('penjualanForm').addEventListener('submit', async (e) => {
  e.preventDefault();

  // Ambil nilai dari form
  const input = {
    nama_produk: document.getElementById('nama_produk').value,
    durasi_atau_jumlah: parseInt(document.getElementById('durasi').value),
    harga_beli: parseFloat(document.getElementById('harga_beli').value),
    harga_jual: parseFloat(document.getElementById('harga_jual').value),
    metode_customer: document.getElementById('metode_customer').value,
    metode_modal: document.getElementById('metode_modal').value,
    no_customer: document.getElementById('no_customer').value || null,
    status: document.getElementById('status').value || 'lunas',
    catatan: document.getElementById('catatan').value || null
  };

  const query = `
    mutation InsertPenjualan($input: PenjualanInput!) {
      insertPenjualan(input: $input) {
        success
        message
      }
    }
  `;

  try {
    const response = await fetch("graphql/index.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify({
        query,
        variables: { input }
      })
    });

    const result = await response.json();
    const data = result.data?.insertPenjualan;

    const statusElem = document.getElementById("penjualanStatus");

    if (data?.success) {
      statusElem.textContent = data.message;
      statusElem.style.color = "green";
    } else {
      statusElem.textContent = data?.message || "Gagal menyimpan penjualan.";
      statusElem.style.color = "red";
    }

  } catch (error) {
    console.error("Gagal kirim data penjualan:", error);
    document.getElementById("penjualanStatus").textContent = "Terjadi kesalahan koneksi.";
  }
});

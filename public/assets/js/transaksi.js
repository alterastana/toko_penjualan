document.getElementById('transaksiForm').addEventListener('submit', async (e) => {
  e.preventDefault();

  const formData = {
    metode: document.getElementById('metode').value,
    jenis: document.getElementById('jenis').value,
    jumlah: parseFloat(document.getElementById('jumlah').value),
    keterangan: document.getElementById('keterangan').value,
    keperluan: document.getElementById('keperluan').value
  };

  const query = `
    mutation InsertTransaksi(
      $metode: String!
      $jenis: String!
      $jumlah: Float!
      $keterangan: String
      $keperluan: String
    ) {
      insertTransaksi(
        metode: $metode
        jenis: $jenis
        jumlah: $jumlah
        keterangan: $keterangan
        keperluan: $keperluan
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
      body: JSON.stringify({
        query,
        variables: formData
      })
    });

    const result = await response.json();
    const statusEl = document.getElementById("formStatus");

    if (result.errors) {
      statusEl.textContent = "Error: " + result.errors[0].message;
      statusEl.style.color = "red";
    } else {
      statusEl.textContent = result.data?.insertTransaksi?.message || "Transaksi berhasil.";
      statusEl.style.color = "green";
    }
  } catch (error) {
    document.getElementById("formStatus").textContent = "Gagal mengirim data.";
    console.error("Gagal:", error);
  }
});

 document.getElementById('piutangForm').addEventListener('submit', async (e) => {
  e.preventDefault();

  const input = {
    nama: document.getElementById('nama_piutang').value,
    jumlah: parseFloat(document.getElementById('jumlah_piutang').value),
    metode: document.getElementById('metode_piutang').value,
    keterangan: document.getElementById('keterangan_piutang').value || '',
    jenis: "pinjam"
  };

  const query = `
    mutation InsertHutang($input: HutangInput!) {
      insertHutang(input: $input) {
        success
        message
      }
    }
  `;

  try {
    const response = await fetch("graphql/index.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ query, variables: { input } })
    });

    const result = await response.json();
    const data = result.data?.insertHutang;

    const statusElem = document.getElementById("piutangStatus");
    statusElem.textContent = data?.message || "Terjadi kesalahan saat menyimpan.";
    statusElem.style.color = data?.success ? "green" : "red";
  } catch (err) {
    document.getElementById("piutangStatus").textContent = "Gagal menghubungi server.";
  }
});

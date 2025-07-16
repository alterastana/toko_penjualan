document.addEventListener('DOMContentLoaded', () => {
  const today = new Date().toISOString().split('T')[0];
  document.getElementById('tanggal_aset').value = today;
});

document.getElementById('asetForm').addEventListener('submit', async (e) => {
  e.preventDefault();

  const tanggal = document.getElementById('tanggal_aset').value;
  const total_aset = parseFloat(document.getElementById('jumlah_aset').value);

  const query = `
    mutation SetAsetHarian($tanggal: String!, $total_aset: Float!) {
      setAsetHarian(tanggal: $tanggal, total_aset: $total_aset) {
        success
        message
      }
    }
  `;

  try {
    const response = await fetch("graphql/index.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ query, variables: { tanggal, total_aset } })
    });

    const result = await response.json();
    const data = result.data?.setAsetHarian;

    const statusElem = document.getElementById("asetStatus");
    statusElem.textContent = data?.message || "Terjadi kesalahan saat menyimpan aset.";
    statusElem.style.color = data?.success ? "green" : "red";

  } catch (err) {
    document.getElementById("asetStatus").textContent = "Gagal menghubungi server.";
  }
});

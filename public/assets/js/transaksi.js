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
    mutation InsertTransaksi($input: TransaksiInput!) {
      insertTransaksi(input: $input) {
        success
        message
      }
    }
  `;

  const response = await fetch("graphql/index.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      query,
      variables: { input: formData }
    })
  });

  const result = await response.json();
  document.getElementById("formStatus").textContent =
    result.data?.insertTransaksi?.message || "Gagal menyimpan transaksi.";
});

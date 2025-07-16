document.getElementById('saldoForm').addEventListener('submit', async (e) => {
  e.preventDefault();

  const formData = {
    metode: document.getElementById('saldo_metode').value,
    jumlah: parseFloat(document.getElementById('saldo_jumlah').value),
    aksi: document.querySelector('input[name="aksi"]:checked').value
  };

  const query = `
    mutation InsertSaldo($metode: String!, $jumlah: Float!, $aksi: String!) {
      insertSaldo(metode: $metode, jumlah: $jumlah, aksi: $aksi) {
        success
        message
      }
    }
  `;

  const response = await fetch("graphql/index.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ query, variables: formData })
  });

  const result = await response.json();
  document.getElementById("saldoStatus").textContent =
    result.data?.insertSaldo?.message || "Gagal memproses saldo.";
});

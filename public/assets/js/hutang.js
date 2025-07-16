document.getElementById('hutangForm').addEventListener('submit', async (e) => {
  e.preventDefault();

  const input = {
    nama: document.getElementById("nama").value,
    jenis: document.getElementById("jenis_hutang").value,
    jumlah: parseFloat(document.getElementById("jumlah_hutang").value),
    metode: document.getElementById("metode_hutang").value,
    keterangan: document.getElementById("keterangan_hutang").value || null
  };

  const query = `
    mutation InsertHutang($input: HutangInput!) {
      insertHutang(input: $input) {
        success
        message
      }
    }`;

  try {
    const response = await fetch("graphql/index.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ query, variables: { input } })
    });

    const result = await response.json();
    const message = result.data?.insertHutang?.message || "Gagal menyimpan hutang.";
    const success = result.data?.insertHutang?.success === true;

    const statusElem = document.getElementById("hutangStatus");
    statusElem.textContent = message;
    statusElem.style.color = success ? "green" : "red";

    if (success) e.target.reset();

  } catch (err) {
    console.error("Error saat mengirim data:", err);
    const statusElem = document.getElementById("hutangStatus");
    statusElem.textContent = "Terjadi kesalahan koneksi.";
    statusElem.style.color = "red";
  }
});

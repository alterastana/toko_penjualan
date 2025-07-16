document.getElementById('hutangForm').addEventListener('submit', async (e) => {
  e.preventDefault();

  const variables = {
    nama: document.getElementById("nama").value,
    jenis: document.getElementById("jenis_hutang").value,
    jumlah: parseFloat(document.getElementById("jumlah_hutang").value),
    metode: document.getElementById("metode_hutang").value,
    keterangan: document.getElementById("keterangan_hutang").value || null
  };

  const query = `
    mutation InsertHutang(
      $nama: String!
      $jenis: String!
      $jumlah: Float!
      $metode: String!
      $keterangan: String
    ) {
      insertHutang(
        nama: $nama
        jenis: $jenis
        jumlah: $jumlah
        metode: $metode
        keterangan: $keterangan
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
      body: JSON.stringify({ query, variables })
    });

    const result = await response.json();
    const statusElem = document.getElementById("hutangStatus");

    if (result.errors) {
      statusElem.textContent = "Error: " + result.errors[0].message;
      statusElem.style.color = "red";
    } else {
      const success = result.data?.insertHutang?.success;
      const message = result.data?.insertHutang?.message || "Gagal menyimpan hutang.";

      statusElem.textContent = message;
      statusElem.style.color = success ? "green" : "red";

      if (success) e.target.reset();
    }
  } catch (error) {
    const statusElem = document.getElementById("hutangStatus");
    statusElem.textContent = "Gagal terhubung ke server.";
    statusElem.style.color = "red";
    console.error("Fetch error:", error);
  }
});

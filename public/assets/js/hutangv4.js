// Event submit form hutang
document.getElementById('hutangForm').addEventListener('submit', async (e) => {
  e.preventDefault();

  const namaInput = document.getElementById("nama");
  const nama = namaInput.value; // biar nama tetap ada setelah submit

  const variables = {
    nama: nama,
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

      if (success) {
        // Reset hanya field selain nama
        document.getElementById("jumlah_hutang").value = "";
        document.getElementById("keterangan_hutang").value = "";
        document.getElementById("jenis_hutang").selectedIndex = 0;
        document.getElementById("metode_hutang").selectedIndex = 0;

        // update sisa hutang untuk nama yang sama
        await updateSisaHutang(nama);
      }
    }
  } catch (error) {
    const statusElem = document.getElementById("hutangStatus");
    statusElem.textContent = "Gagal terhubung ke server.";
    statusElem.style.color = "red";
    console.error("Fetch error:", error);
  }
});

// Fungsi untuk mengambil sisa hutang
async function updateSisaHutang(nama) {
  const sisaElem = document.getElementById("sisa_hutang");

  if (!nama) {
    sisaElem.value = "";
    return;
  }

  const query = `
    query GetHutangByNama($nama: String!) {
      getHutangByNama(nama: $nama) {
        jumlah
      }
    }
  `;

  try {
    const response = await fetch("graphql/index.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ query, variables: { nama } })
    });

    const result = await response.json();

    if (result.errors) {
      sisaElem.value = "Gagal memuat sisa hutang";
      return;
    }

    const hutang = result.data?.getHutangByNama;
    if (hutang && hutang.jumlah > 0) {
      sisaElem.value = `Rp ${Number(hutang.jumlah).toLocaleString("id-ID")}`;
    } else {
      sisaElem.value = "Tidak ada hutang";
    }
  } catch (err) {
    console.error("Fetch error:", err);
    sisaElem.value = "Gagal memuat data";
  }
}

// Event untuk menampilkan sisa hutang saat selesai input nama (blur)
document.getElementById("nama").addEventListener("blur", async (e) => {
  const nama = e.target.value.trim();
  await updateSisaHutang(nama);
});

// Auto-load sisa hutang saat halaman dibuka jika nama sudah terisi
window.addEventListener("DOMContentLoaded", async () => {
  const nama = document.getElementById("nama").value.trim();
  if (nama) {
    await updateSisaHutang(nama);
  }
});

const API_URL = "http://localhost/toko_penjualan/public/graphql/index.php";

// Ambil elemen DOM
const akunTableBody = document.getElementById("akunTableBody");
const akunForm = document.getElementById("akunForm");
const editModal = document.getElementById("editModal");
const editAkunForm = document.getElementById("editAkunForm");

// ===================
// Load Data Akun
// ===================
async function loadAkun() {
  akunTableBody.innerHTML = `<tr><td colspan="6" class="text-center p-4">Memuat data...</td></tr>`;
  try {
    const query = `
      query {
        getAllAkun {
          id
          created_at
          username
          password
          jadwal_promosi
          status
        }
      }
    `;
    const res = await fetch(API_URL, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ query }),
    });
    const { data, errors } = await res.json();
    if (errors) throw new Error(errors[0].message);

    akunTableBody.innerHTML = "";
    if (!data.getAllAkun || data.getAllAkun.length === 0) {
      akunTableBody.innerHTML = `<tr><td colspan="6" class="text-center p-4">Belum ada akun</td></tr>`;
      return;
    }

    data.getAllAkun.forEach((akun) => {
      const row = document.createElement("tr");
      row.innerHTML = `
        <td class="p-2">${akun.id}</td>
        <td class="p-2">${akun.username}</td>
        <td class="p-2">${akun.password}</td>
        <td class="p-2">${akun.jadwal_promosi}</td>
        <td class="p-2">${akun.status}</td>
        <td class="p-2 space-x-2">
          <button class="bg-yellow-400 text-white px-2 py-1 rounded" onclick="openEditModal(${akun.id}, '${akun.username}', '${akun.password}', '${akun.jadwal_promosi}', '${akun.status}')">Edit</button>
          <button class="bg-red-500 text-white px-2 py-1 rounded" onclick="deleteAkun(${akun.id})">Hapus</button>
        </td>
      `;
      akunTableBody.appendChild(row);
    });
  } catch (err) {
    akunTableBody.innerHTML = `<tr><td colspan="6" class="text-center p-4 text-red-500">Gagal memuat data akun: ${err.message}</td></tr>`;
  }
}

// ===================
// Tambah Akun
// ===================
akunForm.addEventListener("submit", async (e) => {
  e.preventDefault();
  const username = document.getElementById("username").value;
  const password = document.getElementById("password").value;
  const jadwal_promosi = document.getElementById("jadwal_promosi").value;
  const status = document.getElementById("status").value;

  try {
    const mutation = `
      mutation {
        createAkun(username: "${username}", password: "${password}", jadwal_promosi: "${jadwal_promosi}", status: "${status}") {
          success
          message
        }
      }
    `;
    const res = await fetch(API_URL, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ query: mutation }),
    });
    const { data, errors } = await res.json();
    if (errors) throw new Error(errors[0].message);
    if (!data.createAkun.success) throw new Error(data.createAkun.message);

    akunForm.reset();
    loadAkun();
  } catch (err) {
    alert("Gagal tambah akun: " + err.message);
  }
});

// ===================
// Edit Akun
// ===================
function openEditModal(id, username, password, jadwal_promosi, status) {
  document.getElementById("edit_id").value = id;
  document.getElementById("edit_username").value = username;
  document.getElementById("edit_password").value = password;
  document.getElementById("edit_jadwal_promosi").value = jadwal_promosi;
  document.getElementById("edit_status").value = status;

  editModal.classList.remove("hidden");
}

function closeEditModal() {
  editModal.classList.add("hidden");
}

editAkunForm.addEventListener("submit", async (e) => {
  e.preventDefault();
  const id = document.getElementById("edit_id").value;
  const username = document.getElementById("edit_username").value;
  const password = document.getElementById("edit_password").value;
  const jadwal_promosi = document.getElementById("edit_jadwal_promosi").value;
  const status = document.getElementById("edit_status").value;

  try {
    const mutation = `
      mutation {
        updateAkun(id: ${id}, username: "${username}", password: "${password}", jadwal_promosi: "${jadwal_promosi}", status: "${status}") {
          success
          message
        }
      }
    `;
    const res = await fetch(API_URL, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ query: mutation }),
    });
    const { data, errors } = await res.json();
    if (errors) throw new Error(errors[0].message);
    if (!data.updateAkun.success) throw new Error(data.updateAkun.message);

    closeEditModal();
    loadAkun();
  } catch (err) {
    alert("Gagal update akun: " + err.message);
  }
});

// ===================
// Hapus Akun
// ===================
async function deleteAkun(id) {
  if (!confirm("Yakin ingin menghapus akun ini?")) return;
  try {
    const mutation = `
      mutation {
        deleteAkun(id: ${id}) {
          success
          message
        }
      }
    `;
    const res = await fetch(API_URL, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ query: mutation }),
    });
    const { data, errors } = await res.json();
    if (errors) throw new Error(errors[0].message);
    if (!data.deleteAkun.success) throw new Error(data.deleteAkun.message);

    loadAkun();
  } catch (err) {
    alert("Gagal hapus akun: " + err.message);
  }
}

// Load data pertama kali
document.addEventListener("DOMContentLoaded", loadAkun);

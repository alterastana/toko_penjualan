<?php
session_start();
require_once __DIR__ . '/../src/database/db.php';

// Jika sudah login
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username && $password) {
        $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && hash('sha256', $password) === $admin['password']) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            header("Location: index.php");
            exit;
        } else {
            $_SESSION['login_error'] = "Username atau password salah.";
        }
    } else {
        $_SESSION['login_error'] = "Mohon isi semua field.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Admin - Vermont Store</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #fff1f2;
    }
    
    .login-container {
      background: white;
      border-radius: 0.75rem;
      box-shadow: 0 4px 20px rgba(249, 168, 212, 0.2);
      border: 1px solid #fce7f3;
    }
    
    .input-field {
      border: 1px solid #fbcfe8;
      transition: all 0.3s ease;
    }
    
    .input-field:focus {
      border-color: #f472b6;
      box-shadow: 0 0 0 2px #fce7f3;
      outline: none;
    }
    
    .login-btn {
      background: linear-gradient(135deg, #f9a8d4 0%, #ec4899 100%);
      transition: all 0.3s ease;
      letter-spacing: 0.5px;
      font-weight: 500;
    }
    
    .login-btn:hover {
      background: linear-gradient(135deg, #ec4899 0%, #db2777 100%);
      transform: translateY(-1px);
      box-shadow: 0 4px 8px rgba(236, 72, 153, 0.2);
    }
    
    .error-message {
      background-color: #fee2e2;
      border-left: 4px solid #ef4444;
      animation: fadeIn 0.3s ease-in-out;
    }
    
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-5px); }
      to { opacity: 1; transform: translateY(0); }
    }
    
    @media (max-width: 640px) {
      .login-container {
        margin: 0 1rem;
        padding: 1.5rem;
      }
      
      .login-title {
        font-size: 1.5rem;
      }
    }
  </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">
  <div class="login-container p-8 w-full max-w-md">
    <div class="text-center mb-6">
      <h1 class="login-title text-2xl font-bold text-pink-800">Vermont Store</h1>
      <h2 class="text-xl font-semibold text-pink-700 mt-2">Login Admin</h2>
    </div>
    
    <?php if (isset($_SESSION['login_error'])): ?>
      <div class="error-message text-red-600 p-3 rounded mb-4 text-sm">
        <?php echo htmlspecialchars($_SESSION['login_error']); unset($_SESSION['login_error']); ?>
      </div>
    <?php endif; ?>
    
    <form method="POST" class="space-y-5">
      <div>
        <label class="block text-sm font-medium text-pink-800 mb-1">Username</label>
        <input 
          type="text" 
          name="username" 
          required 
          class="input-field w-full px-4 py-2 rounded-lg focus:ring-2 focus:ring-pink-300"
          placeholder="Masukkan username"
        >
      </div>
      
      <div>
        <label class="block text-sm font-medium text-pink-800 mb-1">Password</label>
        <input 
          type="password" 
          name="password" 
          required 
          class="input-field w-full px-4 py-2 rounded-lg focus:ring-2 focus:ring-pink-300"
          placeholder="Masukkan password"
        >
      </div>
      
      <button 
        type="submit" 
        class="login-btn w-full py-2.5 rounded-lg text-white font-medium text-sm"
      >
        Masuk
      </button>
      
      <div class="text-center text-sm text-pink-600 mt-4">
        <a href="#" class="hover:text-pink-800 hover:underline">Lupa password?</a>
      </div>
    </form>
  </div>
</body>
</html>

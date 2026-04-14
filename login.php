<?php
require_once __DIR__ . '/config.php';
if (isset($_SESSION['username'])) {
  header("Location: index.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $stmt = $pdo->prepare("SELECT password_hash FROM users WHERE username = ?");
  $stmt->execute([$username]);

  if ($row = $stmt->fetch()) {
    if (password_verify($password, $row['password_hash'])) {
      $_SESSION['username'] = $username;
      header("Location: index.php");
      exit();
    }
  }
  $error = "Authentication Failed";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>WOW | Login</title>
  <link href="<?php echo ROOT_URL; ?>/assets/css/output.css" rel="stylesheet">
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">

  <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
  <link rel="icon" type="image/x-icon" href="<?php echo ROOT_URL; ?>/assets/images/favicon.webp" />
</head>
<body class="flex items-center justify-center h-screen antialiased">
  <div class="w-full max-w-md p-10 glass rounded-[3rem] shadow-2xl mx-4">
    <div class="flex flex-col items-center mb-10">
      <div class="w-16 h-16 bg-gradient-to-br from-cyan-400 via-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-xl rotate-3">
        <span class="text-white font-black text-3xl">W</span>
      </div>
      <h1 class="text-4xl font-black mt-6 tracking-tighter text-slate-800 uppercase">Wow<span class="text-indigo-600">.</span></h1>
      <p class="text-slate-500 font-bold text-[10px] uppercase tracking-widest mt-2">Unified Visual Interface</p>
    </div>

    <?php if (isset($error)): ?>
      <div class="bg-red-500/20 border border-red-500/50 text-red-700 p-4 rounded-2xl mb-6 text-xs text-center font-black uppercase tracking-tighter">
        <?php echo $error; ?>
      </div>
    <?php endif; ?>

    <form method="POST" class="space-y-4">
      <input type="text" name="username" placeholder="Username" required 
        class="w-full bg-white/40 border border-white/60 rounded-2xl px-6 py-4 focus:ring-4 focus:ring-indigo-500/20 outline-none transition-all placeholder-slate-500 text-slate-800 font-medium">
      
      <input type="password" name="password" placeholder="Password" required 
        class="w-full bg-white/40 border border-white/60 rounded-2xl px-6 py-4 focus:ring-4 focus:ring-indigo-500/20 outline-none transition-all placeholder-slate-500 text-slate-800 font-medium">
      
      <button type="submit" 
        class="w-full bg-gradient-to-r from-cyan-500 via-indigo-600 to-purple-600 text-white font-black py-4 rounded-2xl hover:opacity-90 transition-all active:scale-[0.98] shadow-xl shadow-indigo-500/40 uppercase tracking-widest text-sm">
        Access System
      </button>
    </form>
  </div>
</body>
</html>

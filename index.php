<?php
require_once __DIR__ . '/config.php';
if (!isset($_SESSION['username'])) {
  header("Location: login");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>WOW | Expand Your View</title>
  <link href="<?php echo ROOT_URL; ?>/assets/css/output.css" rel="stylesheet">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">

  <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
  <link rel="icon" type="image/x-icon" href="<?php echo ROOT_URL; ?>/assets/images/favicon.webp" />
</head>
<body class="antialiased max-w-full overflow-x-hidden">

  <div class="flex flex-col gap-6">
    <nav class="w-[94%] mx-auto glass rounded-[2rem] px-8 py-4 flex justify-between items-center shadow-xl shadow-neutral-500 mt-4 lg:mt-8">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 bg-gradient-to-br from-cyan-400 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-neutral-500">
          <span class="text-white font-black text-xl">W</span>
        </div>
        <div>
          <h1 class="text-2xl font-black tracking-tighter text-slate-800 leading-none">WOW<span class="text-indigo-600">.</span></h1>
          <p class="text-[10px] uppercase tracking-[0.2em] font-bold text-slate-500">Spatial Sync Engine</p>
        </div>
      </div>
      <div class="flex items-center gap-6">
        <div class="hidden md:flex flex-col items-end">
          <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Active Operator</span>
          <span class="text-sm font-black text-slate-800"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
        </div>
        <a href="logout.php" class="bg-white/40 hover:bg-red-500 hover:text-white text-red-600 px-6 py-2 rounded-full text-xs font-bold transition-all border border-white/50">Disconnect</a>
      </div>
    </nav>

    <main class="min-h-screen w-[94%] mx-auto flex-1 flex gap-6">
      <aside class="w-80 flex flex-col gap-6">
        <div class="glass rounded-[2.5rem] p-6 shadow-lg shadow-neutral-500">
          <h3 class="text-xs font-black text-indigo-600 uppercase tracking-widest mb-4">Network Discovery</h3>
          <button id="add-screen-btn" class="w-full bg-gradient-to-r from-cyan-500 to-indigo-600 text-white font-bold py-4 rounded-2xl shadow-lg shadow-indigo-500/30 hover:scale-[1.02] transition active:scale-95">
            Sync Current Viewport
          </button>
        </div>

        <div class="glass rounded-[2.5rem] p-6 flex-1 flex flex-col shadow-lg shadow-neutral-500">
          <h3 class="text-xs font-black text-indigo-600 uppercase tracking-widest mb-4">Linked Nodes</h3>
          <div id="device-list" class="space-y-3 overflow-y-auto pr-2 flex-1">
            <div class="p-4 bg-white/40 rounded-2xl border border-white/60 flex items-center justify-between">
              <div>
                <p class="text-xs font-black text-slate-800">Master Node</p>
                <p class="text-[10px] text-slate-500"><?php echo $_SERVER['HTTP_USER_AGENT']; ?></p>
              </div>
              <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
            </div>
          </div>
        </div>
      </aside>

      <div class="flex-1 flex flex-col gap-6">
        <div class="glass rounded-[3rem] flex-1 relative shadow-2xl shadow-neutral-500 flex items-center justify-center group">
          <video id="main-video" class="max-w-none pointer-events-none" src="content.mp4" playsinline loop></video>
          <div class="absolute bottom-6 left-6 flex gap-3">
            <div class="glass px-4 py-2 rounded-xl text-[10px] font-bold text-slate-700">COORD: <span id="coord-display">0,0</span></div>
            <div class="glass px-4 py-2 rounded-xl text-[10px] font-bold text-slate-700">STATUS: <span class="text-green-600 uppercase">Synced</span></div>
          </div>
        </div>

        <div class="glass rounded-[2rem] p-4 flex items-center justify-between shadow-xl shadow-neutral-500">
          <div class="flex gap-2">
            <button id="play-btn" class="w-12 h-12 glass rounded-full flex items-center justify-center hover:bg-indigo-500 hover:text-white transition-all">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M6.3 2.841A1.5 1.5 0 004 4.11v11.78a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/></svg>
            </button>
            <button id="pause-btn" class="w-12 h-12 glass rounded-full flex items-center justify-center hover:bg-indigo-500 hover:text-white transition-all">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a1 1 0 00-1 1v10a1 1 0 001 1h3a1 1 0 001-1V5a1 1 0 00-1-1H5zm7 0a1 1 0 00-1 1v10a1 1 0 001 1h3a1 1 0 001-1V5a1 1 0 00-1-1h-3z"/></svg>
            </button>
          </div>
          <div class="flex-1 px-8">
            <div class="h-1 bg-white/30 rounded-full overflow-hidden">
              <div id="progress-bar" class="h-full bg-gradient-to-r from-cyan-400 to-purple-600 w-0 transition-all"></div>
            </div>
          </div>
          <div class="flex items-center gap-4 px-4">
            <input type="range" id="volume" class="w-24 accent-indigo-600">
          </div>
        </div>
      </div>
    </main>

    <footer class="w-[94%] mx-auto glass mt-auto rounded-t-[2rem] px-8 py-4 border-t-0 shadow-[0_-10px_40px_rgba(0,0,0,0.1)]">
      <div class="flex flex-col md:flex-row justify-between items-center gap-4">
        <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.3em]">
          &copy; 2026 WOW Spatial Sync Engine <span class="mx-2 text-indigo-400">|</span> v1.0.4
        </p>
        <div class="flex gap-6">
          <div class="flex items-center gap-2">
            <span class="w-1.5 h-1.5 bg-cyan-400 rounded-full animate-pulse"></span>
            <span class="text-[10px] font-bold text-slate-600 uppercase tracking-widest">Encrypted</span>
          </div>
          <div class="flex items-center gap-2">
            <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full"></span>
            <span class="text-[10px] font-bold text-slate-600 uppercase tracking-widest">Low Latency</span>
          </div>
        </div>
      </div>
    </footer>
  </div>

  <script src="<?php echo ROOT_URL; ?>/assets/js/main.js"></script>
</body>
</html>

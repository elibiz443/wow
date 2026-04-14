const pusher = new Pusher('4897cc9f7b819e98ba4f', { cluster: 'ap2' });
const userHash = '<?php echo md5($_SESSION['username']); ?>';
const channel = pusher.subscribe('wow-' + userHash);
const video = document.getElementById('main-video');
const deviceId = Math.random().toString(36).substring(7);

document.getElementById('add-screen-btn').addEventListener('click', () => {
  fetch('app/api.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      action: 'register',
      deviceId: deviceId,
      width: window.innerWidth,
      name: navigator.platform
    })
  });
});

channel.bind('state-change', (data) => {
  const myConfig = data.devices.find(d => d.device_id === deviceId);
  if (myConfig && myConfig.is_combined == 1) {
    video.style.width = data.totalWidth + 'px';
    video.style.transform = `translateX(-${myConfig.x_offset}px)`;
    document.getElementById('coord-display').innerText = `${myConfig.x_offset},0`;
  } else {
    video.style.width = '100vw';
    video.style.transform = 'translateX(0)';
    document.getElementById('coord-display').innerText = `0,0`;
  }
});

channel.bind('playback-trigger', (data) => {
  if (data.action === 'play') {
    video.currentTime = data.time;
    video.play();
  } else {
    video.pause();
  }
});

document.getElementById('play-btn').addEventListener('click', () => {
  fetch('api.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ action: 'play', time: video.currentTime }) });
});

document.getElementById('pause-btn').addEventListener('click', () => {
  fetch('api.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ action: 'pause' }) });
});

video.addEventListener('timeupdate', () => {
  const pct = (video.currentTime / video.duration) * 100;
  document.getElementById('progress-bar').style.width = pct + '%';
});

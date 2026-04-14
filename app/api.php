<?php
require __DIR__ . '/../vendor/autoload.php';
session_start();

$data = json_decode(file_get_contents('php://input'), true);
$email = $_SESSION['user_email'];

$options = array('cluster' => 'ap2', 'useTLS' => true);
$pusher = new Pusher\Pusher(
  '4897cc9f7b819e98ba4f',
  '95058a5edf3cc7fc023e',
  '2140845',
  $options
);

$db = new mysqli("localhost", "db_user", "db_pass", "wow_db");

if ($data['action'] === 'register') {
  $dId = $db->real_escape_string($data['deviceId']);
  $w = (int)$data['width'];
  $n = $db->real_escape_string($data['name']);
  
  $db->query("INSERT INTO active_sessions (user_email, device_id, device_name, screen_width, is_combined) VALUES ('$email', '$dId', '$n', $w, 1)");
  
  $res = $db->query("SELECT * FROM active_sessions WHERE user_email = '$email' AND is_combined = 1 ORDER BY id ASC");
  $totalWidth = 0;
  $updateData = [];
  
  while ($row = $res->fetch_assoc()) {
    $db->query("UPDATE active_sessions SET x_offset = $totalWidth WHERE id = " . $row['id']);
    $row['x_offset'] = $totalWidth;
    $totalWidth += $row['screen_width'];
    $updateData[] = $row;
  }

  $pusher->trigger('wow-' . md5($email), 'state-change', [
    'totalWidth' => $totalWidth,
    'devices' => $updateData
  ]);
}

if ($data['action'] === 'play' || $data['action'] === 'pause') {
  $pusher->trigger('wow-' . md5($email), 'playback-trigger', [
    'action' => $data['action'],
    'time' => $data['time'] ?? 0
  ]);
}
?>

<?php
declare(strict_types=1);

function db_init(PDO $pdo): void {
  $pdo->exec("
    CREATE TABLE IF NOT EXISTS users (
      id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      username VARCHAR(190) NOT NULL UNIQUE,
      password_hash VARCHAR(255) NOT NULL,
      created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
  ");

  $pdo->exec("
    CREATE TABLE IF NOT EXISTS active_sessions (
      id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      username VARCHAR(190) NOT NULL,
      device_id VARCHAR(100) NOT NULL,
      device_name VARCHAR(100) NOT NULL,
      screen_width INT NOT NULL,
      x_offset INT DEFAULT 0,
      is_combined TINYINT(1) DEFAULT 0,
      last_seen TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      INDEX idx_username (username),
      INDEX idx_device_id (device_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
  ");

  $pdo->exec("
    CREATE TABLE IF NOT EXISTS playback_state (
      username VARCHAR(190) PRIMARY KEY,
      video_url TEXT,
      `current_time` FLOAT DEFAULT 0,
      `is_playing` TINYINT(1) DEFAULT 0,
      updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
  ");
}

function db_seed_default_user(PDO $pdo): void {
  $username = 'super_admin';
  $password = 'secure345';

  $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? LIMIT 1");
  $stmt->execute([$username]);

  if ($stmt->fetch()) return;

  $hash = password_hash($password, PASSWORD_DEFAULT);
  
  $insert = $pdo->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
  $insert->execute([$username, $hash]);
}

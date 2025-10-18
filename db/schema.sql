CREATE TABLE `users` (
  `id` int NOT NULL,
  `google_sub` varchar(64) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `picture` text,
  `status` enum('enabled','disabled') NOT NULL DEFAULT 'enabled',
  `membership_tier` enum('free','premium','agency') NOT NULL DEFAULT 'free',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


CREATE TABLE sessions (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id BIGINT UNSIGNED NOT NULL,
  refresh_token_hash CHAR(64) NOT NULL,
  created_at DATETIME NOT NULL,
  last_used_at DATETIME NOT NULL,
  expires_at DATETIME NOT NULL,
  revoked_at DATETIME NULL,
  user_agent VARCHAR(255) NULL,
  ip_hash CHAR(64) NULL,
  INDEX idx_user (user_id),
  INDEX idx_token (refresh_token_hash)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

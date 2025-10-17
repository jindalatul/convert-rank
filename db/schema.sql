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

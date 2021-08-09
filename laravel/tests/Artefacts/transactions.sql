CREATE TABLE `transactions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


INSERT INTO `transactions` (`id`, `code`, `amount`, `user_id`, `created_at`, `updated_at`)
VALUES
	(1,'T_218_ljydmgebx','8617.19',375,'2020-01-19 16:08:59','2020-01-19 16:08:59'),
	(2,'T_335_wmhrbjxld','6502.72',1847,'2020-01-19 16:08:59','2020-01-19 16:08:59');

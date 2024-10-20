CREATE TABLE `trigger_sessions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `automation2_id` int unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trigger_sessions_automation2_id_foreign` (`automation2_id`),
  CONSTRAINT `trigger_sessions_automation2_id_foreign` FOREIGN KEY (`automation2_id`) REFERENCES `automation2s` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci

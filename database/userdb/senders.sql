CREATE TABLE `senders` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `uid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` int unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `sending_server_id` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `senders_customer_id_foreign` (`customer_id`),
  KEY `senders_sending_server_id_foreign` (`sending_server_id`),
  CONSTRAINT `senders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `senders_sending_server_id_foreign` FOREIGN KEY (`sending_server_id`) REFERENCES `sending_servers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci

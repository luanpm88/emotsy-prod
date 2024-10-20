CREATE TABLE `mail_lists_sending_servers` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `sending_server_id` int unsigned NOT NULL,
  `mail_list_id` int unsigned NOT NULL,
  `fitness` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mlss_sending_server_id_fk` (`sending_server_id`),
  KEY `mlss_mail_list_id_fk` (`mail_list_id`),
  CONSTRAINT `mlss_mail_list_id_fk` FOREIGN KEY (`mail_list_id`) REFERENCES `mail_lists` (`id`) ON DELETE CASCADE,
  CONSTRAINT `mlss_sending_server_id_fk` FOREIGN KEY (`sending_server_id`) REFERENCES `sending_servers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `forms` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` int unsigned NOT NULL,
  `mail_list_id` int unsigned DEFAULT NULL,
  `template_id` int unsigned DEFAULT NULL,
  `metadata` longtext COLLATE utf8mb4_unicode_ci,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `forms_template_id_foreign` (`template_id`),
  KEY `forms_mail_list_id_foreign` (`mail_list_id`),
  KEY `forms_customer_id_foreign` (`customer_id`),
  CONSTRAINT `forms_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `forms_mail_list_id_foreign` FOREIGN KEY (`mail_list_id`) REFERENCES `mail_lists` (`id`) ON DELETE CASCADE,
  CONSTRAINT `forms_template_id_foreign` FOREIGN KEY (`template_id`) REFERENCES `templates` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci

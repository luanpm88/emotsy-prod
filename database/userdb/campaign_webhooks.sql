CREATE TABLE `campaign_webhooks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `endpoint` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `campaign_id` int unsigned NOT NULL,
  `campaign_link_id` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `campaign_webhooks_campaign_id_foreign` (`campaign_id`),
  KEY `campaign_webhooks_campaign_link_id_foreign` (`campaign_link_id`),
  CONSTRAINT `campaign_webhooks_campaign_id_foreign` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`id`) ON DELETE CASCADE,
  CONSTRAINT `campaign_webhooks_campaign_link_id_foreign` FOREIGN KEY (`campaign_link_id`) REFERENCES `campaign_links` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci

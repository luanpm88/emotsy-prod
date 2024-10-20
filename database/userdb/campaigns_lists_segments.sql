CREATE TABLE `campaigns_lists_segments` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `campaign_id` int unsigned NOT NULL,
  `mail_list_id` int unsigned NOT NULL,
  `segment_id` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cls_campaign_id_fk` (`campaign_id`),
  KEY `cls_mail_list_id_fk` (`mail_list_id`),
  KEY `cls_segment_id_fk` (`segment_id`),
  CONSTRAINT `cls_campaign_id_fk` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cls_mail_list_id_fk` FOREIGN KEY (`mail_list_id`) REFERENCES `mail_lists` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cls_segment_id_fk` FOREIGN KEY (`segment_id`) REFERENCES `segments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci

/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `meals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `meals` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `event` varchar(255) DEFAULT NULL,
  `promoted` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `meal_timestamp` datetime NOT NULL,
  `locked_timestamp` datetime NOT NULL,
  `capacity` int(11) DEFAULT NULL,
  `uuid` char(36) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `registrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `registrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `handicap` varchar(255) DEFAULT NULL,
  `meal_id` int(10) unsigned NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `salt` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `confirmed` tinyint(1) NOT NULL DEFAULT 0,
  `user_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `uuid` char(36) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `registrations_meal_id_foreign` (`meal_id`),
  CONSTRAINT `registrations_meal_id_foreign` FOREIGN KEY (`meal_id`) REFERENCES `meals` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `handicap` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `blocked` tinyint(1) NOT NULL DEFAULT 0,
  `email` varchar(255) DEFAULT NULL,
  `is_board` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `vacations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vacations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `start` date NOT NULL,
  `end` date NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (1,'2013_11_03_211114_create_existing_database',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (2,'2014_03_01_174033_SoftDeleteModels',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (3,'2015_04_27_181742_AddUserToRegistration',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (4,'2015_05_14_213627_AddClosingTime',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (5,'2015_05_16_225357_UsersCanHaveLocalData',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (6,'2015_06_06_235613_NonMembersMustConfirmOverEmail',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (7,'2015_06_07_152323_UserFullModel',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (8,'2015_07_01_171038_userscanbeblocked',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (9,'2015_07_02_195703_store_users_email_address',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (10,'2015_08_29_214048_ClosingDatesOnASpecificDate',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (11,'2015_08_31_133843_create_session_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (12,'2015_09_03_160248_UnifiedTimestamps',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (13,'2015_09_16_210306_registration_does_not_require_a_user',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (14,'2015_11_03_211845_remove_obsolete_columns',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (15,'2015_11_05_223445_who_has_subscribed_whom',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (16,'2015_12_26_133050_new_session_database_driver',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (17,'2016_01_20_204417_use_file_sessions',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (18,'2018_11_09_215008_create_vacations_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (19,'2018_11_18_211500_fix_timestamp_column_defaults',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (20,'2019_12_14_000001_create_personal_access_tokens_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (21,'2021_09_05_201221_meals_can_have_capacity',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (22,'2022_10_14_145225_store_authorizations_on_user',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (23,'2022_12_01_235055_api_identifiers',1);

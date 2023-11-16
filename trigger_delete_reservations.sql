/*CREATE TABLE `reservations_mirror` (
  `id` bigint unsigned NOT NULL,
  `quota` int NOT NULL,
  `confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `reservation_date` datetime NOT NULL,
  `confirmation_date` datetime DEFAULT NULL,
  `state` tinyint(1) NOT NULL DEFAULT '1',
  `user_id` bigint unsigned DEFAULT NULL,
  `programming_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

*/

DELIMITER //

CREATE TRIGGER after_delete_reservations
AFTER DELETE ON reservations
FOR EACH ROW
BEGIN
  -- Insert the deleted row into the mirror table
  INSERT INTO noel.reservations_mirror
  SELECT * FROM reservations WHERE id = OLD.id;
END //

DELIMITER ;
USE smart_hostal;

CREATE TABLE IF NOT EXISTS `complaints` (
  `id`             int(11)          NOT NULL AUTO_INCREMENT,
  `TG_no`          varchar(10)      NOT NULL,
  `room`           int(11)          NOT NULL,
  `complaint`      text             NOT NULL,
  `complaint_type` enum('Bulk','Special') NOT NULL DEFAULT 'Bulk',
  `anonymous`      int(1)       NOT NULL DEFAULT 0,
  `status`         enum('Pending','Fixed') NOT NULL DEFAULT 'Pending',
  `submitted_at`   timestamp        NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


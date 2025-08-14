-- Schema
CREATE TABLE IF NOT EXISTS `groups` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `person` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `base_id` int(11) NOT NULL,
    `name` varchar(255) NOT NULL,
    `surname` varchar(255) NOT NULL,
    `group_id` int(11) NOT NULL,
    `valid_from` datetime NOT NULL,
    PRIMARY KEY (`id`),
    KEY `base_id` (`base_id`),
    CONSTRAINT `fk_person_group` 
        FOREIGN KEY (`group_id`) 
        REFERENCES `groups` (`id`)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,
    CONSTRAINT `fk_person_base` 
        FOREIGN KEY (`base_id`) 
        REFERENCES `person` (`id`)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `posts` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `person_base_id` int(11) NOT NULL,
    `content` text NOT NULL,
    `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `created_at` (`created_at`)
    -- WHY reference base_id here?????
    -- CONSTRAINT `fk_post_person` 
    --   FOREIGN KEY (`person_base_id`) 
    --   REFERENCES `person` (`base_id`)
    --   ON DELETE CASCADE
    --   ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Populate tables
INSERT INTO `groups` (`id`, `name`) VALUES
    (1, 'Administrators'),
    (2, 'Moderators'),
    (3, 'Users'),
    (4, 'Guests')
;

INSERT INTO `person` (`id`, `base_id`, `name`, `surname`, `group_id`, `valid_from`) VALUES
    (1, 1, 'John', 'Doe', 1, '2025-07-01 08:00:00'),
    (2, 2, 'Jane', 'Smith', 2, '2025-07-02 09:30:00'),
    (3, 3, 'Bob', 'Johnson', 3, '2025-07-03 10:15:00'),
    (4, 4, 'Alice', 'Williams', 4, '2025-07-04 11:45:00'),
    (5, 1, 'John', 'Doe', 2, '2025-08-05 08:30:00'),
    (6, 6, 'Charlie', 'Brown', 3, '2025-08-05 09:00:00'),
    (7, 7, 'Diana', 'Prince', 1, '2025-08-05 10:30:00'),
    (8, 8, 'Edward', 'Norton', 2, '2025-08-05 11:15:00'),
    (9, 9, 'Fiona', 'Apple', 4, '2025-08-06 12:00:00'),
    (10, 10, 'George', 'Miller', 3, '2025-08-08 14:20:00'),
    (11, 11, 'Hannah', 'Montana', 2, '2025-08-08 15:45:00'),
    (12, 12, 'Ian', 'Curtis', 1, '2025-08-09 16:30:00')
;

INSERT INTO `posts` (`id`, `person_base_id`, `content`, `created_at`) VALUES
    (1, 1, 'This is my first post!', '2025-08-01 10:00:00'),
    (2, 2, 'Hello everyone!', '2025-08-02 11:00:00'),
    (3, 3, 'Just checking in.', '2025-08-03 12:00:00'),
    (4, 4, 'New to the platform.', '2025-08-04 13:00:00'),
    (5, 1, 'Second post from John', '2025-08-05 14:00:00'),
    (6, 1, 'John changed groups!', '2025-08-05 09:00:00'),
    (7, 6, 'Charlie joins the discussion.', '2025-08-06 15:00:00'),
    (8, 7, 'Diana posting as admin.', '2025-08-07 08:30:00'),
    (9, 8, 'Edward says hello!', '2025-08-08 10:15:00'),
    (10, 9, 'Fiona is exploring.', '2025-08-09 12:45:00'),
    (11, 10, 'George shares thoughts.', '2025-08-10 14:00:00'),
    (12, 11, 'Hannah posts a tip.', '2025-08-11 16:20:00'),
    (13, 12, 'Ian announces update.', '2025-08-12 18:30:00'),
    (14, 3, 'Bob adds another post.', '2025-08-13 19:00:00'),
    (15, 2, 'Jane writes again!', '2025-08-14 20:00:00')
;

-- Quin Tech SQLite -> MariaDB export
SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
    `key` VARCHAR(255) NOT NULL,
    `value` LONGTEXT NOT NULL,
    `expiration` BIGINT NOT NULL,
    PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
    `key` VARCHAR(255) NOT NULL,
    `owner` VARCHAR(255) NOT NULL,
    `expiration` BIGINT NOT NULL,
    PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `contact_submissions`;
CREATE TABLE `contact_submissions` (
    `id` BIGINT NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(255),
    `subject` VARCHAR(255),
    `message` LONGTEXT NOT NULL,
    `is_read` TINYINT(1) NOT NULL,
    `ip_address` VARCHAR(255),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
    `id` BIGINT NOT NULL,
    `uuid` VARCHAR(255) NOT NULL,
    `connection` VARCHAR(255) NOT NULL,
    `queue` VARCHAR(255) NOT NULL,
    `payload` LONGTEXT NOT NULL,
    `exception` LONGTEXT NOT NULL,
    `failed_at` DATETIME NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `faqs`;
CREATE TABLE `faqs` (
    `id` BIGINT NOT NULL,
    `question` VARCHAR(255) NOT NULL,
    `answer` LONGTEXT NOT NULL,
    `category` VARCHAR(255),
    `sort_order` BIGINT NOT NULL,
    `is_active` TINYINT(1) NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `faqs` (`id`, `question`, `answer`, `category`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES ('1', 'How long does a project take?', 'Most projects run 6–12 weeks depending on scope.', NULL, '0', '1', '2026-08-21 10:45:05', '2026-08-21 10:45:05');
INSERT INTO `faqs` (`id`, `question`, `answer`, `category`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES ('2', 'Do you offer support after launch?', 'Yes — we offer ongoing maintenance and support plans.', NULL, '1', '1', '2026-08-21 10:45:05', '2026-08-21 10:45:05');
INSERT INTO `faqs` (`id`, `question`, `answer`, `category`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES ('3', 'Where are you based?', 'We are based in Lagos, Nigeria, and work with clients worldwide.', NULL, '2', '1', '2026-08-21 10:45:05', '2026-08-21 10:45:05');

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
    `id` VARCHAR(255) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `total_jobs` BIGINT NOT NULL,
    `pending_jobs` BIGINT NOT NULL,
    `failed_jobs` BIGINT NOT NULL,
    `failed_job_ids` LONGTEXT NOT NULL,
    `options` LONGTEXT,
    `cancelled_at` BIGINT,
    `created_at` BIGINT NOT NULL,
    `finished_at` BIGINT,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
    `id` BIGINT NOT NULL,
    `queue` VARCHAR(255) NOT NULL,
    `payload` LONGTEXT NOT NULL,
    `attempts` BIGINT NOT NULL,
    `reserved_at` BIGINT,
    `available_at` BIGINT NOT NULL,
    `created_at` BIGINT NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `legal_pages`;
CREATE TABLE `legal_pages` (
    `id` BIGINT NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL,
    `content` LONGTEXT,
    `is_active` TINYINT(1) NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `legal_pages` (`id`, `title`, `slug`, `content`, `is_active`, `created_at`, `updated_at`) VALUES ('1', 'Privacy Policy', 'privacy-policy', '<p>This is placeholder text. Edit this policy from the admin panel under Legal.</p>', '1', '2026-08-21 10:45:05', '2026-08-21 10:45:05');
INSERT INTO `legal_pages` (`id`, `title`, `slug`, `content`, `is_active`, `created_at`, `updated_at`) VALUES ('2', 'Terms of Service', 'terms-of-service', '<p>This is placeholder text. Edit this policy from the admin panel under Legal.</p>', '1', '2026-08-21 10:45:05', '2026-08-21 10:45:05');
INSERT INTO `legal_pages` (`id`, `title`, `slug`, `content`, `is_active`, `created_at`, `updated_at`) VALUES ('3', 'Cookie Policy', 'cookie-policy', '<p>This is placeholder text. Edit this policy from the admin panel under Legal.</p>', '1', '2026-08-21 10:45:05', '2026-08-21 10:45:05');
INSERT INTO `legal_pages` (`id`, `title`, `slug`, `content`, `is_active`, `created_at`, `updated_at`) VALUES ('4', 'Refund Policy', 'refund-policy', '<p>This is placeholder text. Edit this policy from the admin panel under Legal.</p>', '1', '2026-08-21 10:45:05', '2026-08-21 10:45:05');

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
    `id` BIGINT NOT NULL,
    `migration` VARCHAR(255) NOT NULL,
    `batch` BIGINT NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('1', '0001_01_01_000000_create_users_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('2', '0001_01_01_000001_create_cache_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('3', '0001_01_01_000002_create_jobs_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('4', '2026_07_03_000001_create_legal_pages_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('5', '2026_07_03_133435_create_pages_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('6', '2026_07_03_133435_create_sections_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('7', '2026_07_03_133435_create_services_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('8', '2026_07_03_133435_create_settings_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('9', '2026_07_03_133436_create_post_categories_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('10', '2026_07_03_133436_create_posts_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('11', '2026_07_03_133436_create_projects_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('12', '2026_07_03_133436_create_team_members_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('13', '2026_07_03_133437_create_contact_submissions_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('14', '2026_07_03_133437_create_faqs_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('15', '2026_07_03_143100_create_permission_tables', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('16', '2026_07_03_150000_add_seo_columns', '1');

DROP TABLE IF EXISTS `model_has_permissions`;
CREATE TABLE `model_has_permissions` (
    `permission_id` BIGINT NOT NULL,
    `model_type` VARCHAR(255) NOT NULL,
    `model_id` BIGINT NOT NULL,
    PRIMARY KEY (`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `model_has_roles`;
CREATE TABLE `model_has_roles` (
    `role_id` BIGINT NOT NULL,
    `model_type` VARCHAR(255) NOT NULL,
    `model_id` BIGINT NOT NULL,
    PRIMARY KEY (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES ('1', 'App\Models\User', '1');

DROP TABLE IF EXISTS `pages`;
CREATE TABLE `pages` (
    `id` BIGINT NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL,
    `is_active` TINYINT(1) NOT NULL,
    `show_in_nav` TINYINT(1) NOT NULL,
    `sort_order` BIGINT NOT NULL,
    `is_system` TINYINT(1) NOT NULL,
    `meta_title` VARCHAR(255),
    `meta_description` VARCHAR(255),
    `meta_keywords` VARCHAR(255),
    `og_image` VARCHAR(255),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `pages` (`id`, `title`, `slug`, `is_active`, `show_in_nav`, `sort_order`, `is_system`, `meta_title`, `meta_description`, `meta_keywords`, `og_image`, `created_at`, `updated_at`) VALUES ('1', 'Home', 'home', '1', '1', '0', '1', NULL, 'Quin Tech — Home.', NULL, NULL, '2026-08-21 10:45:05', '2026-08-21 10:45:05');
INSERT INTO `pages` (`id`, `title`, `slug`, `is_active`, `show_in_nav`, `sort_order`, `is_system`, `meta_title`, `meta_description`, `meta_keywords`, `og_image`, `created_at`, `updated_at`) VALUES ('2', 'About', 'about', '1', '1', '1', '1', 'About', 'Quin Tech — About.', NULL, NULL, '2026-08-21 10:45:05', '2026-08-21 10:45:05');
INSERT INTO `pages` (`id`, `title`, `slug`, `is_active`, `show_in_nav`, `sort_order`, `is_system`, `meta_title`, `meta_description`, `meta_keywords`, `og_image`, `created_at`, `updated_at`) VALUES ('3', 'Services', 'services', '1', '1', '2', '1', 'Services', 'Quin Tech — Services.', NULL, NULL, '2026-08-21 10:45:05', '2026-08-21 10:45:05');
INSERT INTO `pages` (`id`, `title`, `slug`, `is_active`, `show_in_nav`, `sort_order`, `is_system`, `meta_title`, `meta_description`, `meta_keywords`, `og_image`, `created_at`, `updated_at`) VALUES ('4', 'Projects', 'projects', '1', '1', '3', '1', 'Projects', 'Quin Tech — Projects.', NULL, NULL, '2026-08-21 10:45:05', '2026-08-21 10:45:05');
INSERT INTO `pages` (`id`, `title`, `slug`, `is_active`, `show_in_nav`, `sort_order`, `is_system`, `meta_title`, `meta_description`, `meta_keywords`, `og_image`, `created_at`, `updated_at`) VALUES ('5', 'Team', 'team', '1', '1', '4', '1', 'Team', 'Quin Tech — Team.', NULL, NULL, '2026-08-21 10:45:05', '2026-08-21 10:45:05');
INSERT INTO `pages` (`id`, `title`, `slug`, `is_active`, `show_in_nav`, `sort_order`, `is_system`, `meta_title`, `meta_description`, `meta_keywords`, `og_image`, `created_at`, `updated_at`) VALUES ('6', 'Blog', 'blog', '1', '1', '5', '1', 'Blog', 'Quin Tech — Blog.', NULL, NULL, '2026-08-21 10:45:05', '2026-08-21 10:45:05');
INSERT INTO `pages` (`id`, `title`, `slug`, `is_active`, `show_in_nav`, `sort_order`, `is_system`, `meta_title`, `meta_description`, `meta_keywords`, `og_image`, `created_at`, `updated_at`) VALUES ('7', 'FAQ', 'faq', '1', '1', '6', '1', 'FAQ', 'Quin Tech — FAQ.', NULL, NULL, '2026-08-21 10:45:05', '2026-08-21 10:45:05');
INSERT INTO `pages` (`id`, `title`, `slug`, `is_active`, `show_in_nav`, `sort_order`, `is_system`, `meta_title`, `meta_description`, `meta_keywords`, `og_image`, `created_at`, `updated_at`) VALUES ('8', 'Contact', 'contact', '1', '1', '7', '1', 'Contact', 'Quin Tech — Contact.', NULL, NULL, '2026-08-21 10:45:05', '2026-08-21 10:45:05');

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
    `email` VARCHAR(255) NOT NULL,
    `token` VARCHAR(255) NOT NULL,
    `created_at` DATETIME,
    PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
    `id` BIGINT NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `guard_name` VARCHAR(255) NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES ('1', 'view pages', 'web', '2026-08-21 14:01:41', '2026-08-21 14:01:41');
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES ('2', 'view services', 'web', '2026-08-21 14:01:41', '2026-08-21 14:01:41');
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES ('3', 'view projects', 'web', '2026-08-21 14:01:41', '2026-08-21 14:01:41');
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES ('4', 'view blog', 'web', '2026-08-21 14:01:41', '2026-08-21 14:01:41');
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES ('5', 'view team', 'web', '2026-08-21 14:01:41', '2026-08-21 14:01:41');
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES ('6', 'view faqs', 'web', '2026-08-21 14:01:41', '2026-08-21 14:01:41');
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES ('7', 'view messages', 'web', '2026-08-21 14:01:41', '2026-08-21 14:01:41');
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES ('8', 'manage blog', 'web', '2026-08-21 14:01:41', '2026-08-21 14:01:41');
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES ('9', 'manage projects', 'web', '2026-08-21 14:01:41', '2026-08-21 14:01:41');
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES ('10', 'manage services', 'web', '2026-08-21 14:01:41', '2026-08-21 14:01:41');
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES ('11', 'manage team', 'web', '2026-08-21 14:01:41', '2026-08-21 14:01:41');
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES ('12', 'manage pages', 'web', '2026-08-21 14:01:41', '2026-08-21 14:01:41');
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES ('13', 'manage settings', 'web', '2026-08-21 14:01:41', '2026-08-21 14:01:41');

DROP TABLE IF EXISTS `post_categories`;
CREATE TABLE `post_categories` (
    `id` BIGINT NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `post_categories` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES ('1', 'Insights', 'insights', '2026-08-21 10:45:05', '2026-08-21 10:45:05');

DROP TABLE IF EXISTS `posts`;
CREATE TABLE `posts` (
    `id` BIGINT NOT NULL,
    `post_category_id` BIGINT,
    `user_id` BIGINT,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL,
    `excerpt` VARCHAR(255),
    `body` LONGTEXT,
    `featured_image` VARCHAR(255),
    `status` VARCHAR(255) NOT NULL,
    `published_at` DATETIME,
    `meta_title` VARCHAR(255),
    `meta_description` VARCHAR(255),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `meta_keywords` VARCHAR(255),
    `og_image` VARCHAR(255),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `posts` (`id`, `post_category_id`, `user_id`, `title`, `slug`, `excerpt`, `body`, `featured_image`, `status`, `published_at`, `meta_title`, `meta_description`, `created_at`, `updated_at`, `meta_keywords`, `og_image`) VALUES ('1', '1', NULL, 'Digital age', 'digital-age', 'How we build lasting client relationships.', '<p>How we build lasting client relationships.</p>', NULL, 'published', '2026-08-21 10:45:05', NULL, NULL, '2026-08-21 10:45:05', '2026-08-21 10:45:05', NULL, NULL);
INSERT INTO `posts` (`id`, `post_category_id`, `user_id`, `title`, `slug`, `excerpt`, `body`, `featured_image`, `status`, `published_at`, `meta_title`, `meta_description`, `created_at`, `updated_at`, `meta_keywords`, `og_image`) VALUES ('2', '1', NULL, 'Shipping fast without breaking things', 'shipping-fast-without-breaking-things', 'Our approach to reliable releases.', '<p>Our approach to reliable releases.</p>', NULL, 'published', '2026-08-20 10:45:05', NULL, NULL, '2026-08-21 10:45:05', '2026-08-21 10:45:05', NULL, NULL);
INSERT INTO `posts` (`id`, `post_category_id`, `user_id`, `title`, `slug`, `excerpt`, `body`, `featured_image`, `status`, `published_at`, `meta_title`, `meta_description`, `created_at`, `updated_at`, `meta_keywords`, `og_image`) VALUES ('3', '1', NULL, 'Designing for Nigerian users', 'designing-for-nigerian-users', 'Lessons from the field.', '<p>Lessons from the field.</p>', NULL, 'published', '2026-08-19 10:45:05', NULL, NULL, '2026-08-21 10:45:05', '2026-08-21 10:45:05', NULL, NULL);

DROP TABLE IF EXISTS `projects`;
CREATE TABLE `projects` (
    `id` BIGINT NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL,
    `category` VARCHAR(255),
    `client` VARCHAR(255),
    `excerpt` VARCHAR(255),
    `body` LONGTEXT,
    `featured_image` VARCHAR(255),
    `gallery` LONGTEXT,
    `project_url` VARCHAR(255),
    `completed_at` DATE,
    `is_featured` TINYINT(1) NOT NULL,
    `sort_order` BIGINT NOT NULL,
    `is_active` TINYINT(1) NOT NULL,
    `meta_title` VARCHAR(255),
    `meta_description` VARCHAR(255),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `meta_keywords` VARCHAR(255),
    `og_image` VARCHAR(255),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `projects` (`id`, `title`, `slug`, `category`, `client`, `excerpt`, `body`, `featured_image`, `gallery`, `project_url`, `completed_at`, `is_featured`, `sort_order`, `is_active`, `meta_title`, `meta_description`, `created_at`, `updated_at`, `meta_keywords`, `og_image`) VALUES ('1', 'PayFlow dashboard', 'payflow-dashboard', 'Fintech', NULL, 'A real-time payments dashboard.', NULL, NULL, NULL, NULL, NULL, '1', '0', '1', NULL, NULL, '2026-08-21 10:45:05', '2026-08-21 10:45:05', NULL, NULL);
INSERT INTO `projects` (`id`, `title`, `slug`, `category`, `client`, `excerpt`, `body`, `featured_image`, `gallery`, `project_url`, `completed_at`, `is_featured`, `sort_order`, `is_active`, `meta_title`, `meta_description`, `created_at`, `updated_at`, `meta_keywords`, `og_image`) VALUES ('2', 'SwiftMove app', 'swiftmove-app', 'Logistics', NULL, 'Last-mile delivery tracking.', NULL, NULL, NULL, NULL, NULL, '1', '1', '1', NULL, NULL, '2026-08-21 10:45:05', '2026-08-21 10:45:05', NULL, NULL);
INSERT INTO `projects` (`id`, `title`, `slug`, `category`, `client`, `excerpt`, `body`, `featured_image`, `gallery`, `project_url`, `completed_at`, `is_featured`, `sort_order`, `is_active`, `meta_title`, `meta_description`, `created_at`, `updated_at`, `meta_keywords`, `og_image`) VALUES ('3', 'CareLink portal', 'carelink-portal', 'Health', NULL, 'Patient records, simplified.', NULL, NULL, NULL, NULL, NULL, '1', '2', '1', NULL, NULL, '2026-08-21 10:45:05', '2026-08-21 10:45:05', NULL, NULL);

DROP TABLE IF EXISTS `role_has_permissions`;
CREATE TABLE `role_has_permissions` (
    `permission_id` BIGINT NOT NULL,
    `role_id` BIGINT NOT NULL,
    PRIMARY KEY (`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES ('1', '1');
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES ('2', '1');
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES ('3', '1');
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES ('4', '1');
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES ('5', '1');
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES ('6', '1');
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES ('7', '1');
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES ('8', '1');
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES ('9', '1');
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES ('10', '1');
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES ('11', '1');
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES ('12', '1');
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES ('13', '1');

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
    `id` BIGINT NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `guard_name` VARCHAR(255) NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES ('1', 'admin', 'web', '2026-08-21 10:49:20', '2026-08-21 10:49:20');

DROP TABLE IF EXISTS `sections`;
CREATE TABLE `sections` (
    `id` BIGINT NOT NULL,
    `page_id` BIGINT NOT NULL,
    `type` VARCHAR(255) NOT NULL,
    `name` VARCHAR(255),
    `content` LONGTEXT,
    `sort_order` BIGINT NOT NULL,
    `is_active` TINYINT(1) NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `sections` (`id`, `page_id`, `type`, `name`, `content`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES ('1', '1', 'hero', 'Hero', '{"badge_label":"What''s new","badge_text":"Q3 case studies","heading":"Software solutions, powered by engineering.","subheading":"We design, build, and ship web platforms, mobile apps, and cloud systems for ambitious teams across Nigeria.","primary_label":"Get a quote","secondary_label":"Read more","secondary_url":"\/about","image":null}', '0', '1', '2026-08-21 10:45:05', '2026-08-21 10:45:05');
INSERT INTO `sections` (`id`, `page_id`, `type`, `name`, `content`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES ('2', '1', 'services', 'Services', '{"heading":"What we do","subheading":"Six service areas, each editable and toggleable from the admin panel.","limit":6}', '1', '1', '2026-08-21 10:45:05', '2026-08-21 10:45:05');
INSERT INTO `sections` (`id`, `page_id`, `type`, `name`, `content`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES ('3', '1', 'stats', 'Stats', '{"items":[{"value":"120+","label":"Projects delivered"},{"value":"60+","label":"Happy clients"},{"value":"8","label":"Years running"},{"value":"15","label":"Specialists"}]}', '2', '1', '2026-08-21 10:45:05', '2026-08-21 10:45:05');
INSERT INTO `sections` (`id`, `page_id`, `type`, `name`, `content`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES ('4', '1', 'features', 'Features', '{"heading":"Powerful capabilities","subheading":"Explore what sets Quin Tech apart.","left_heading":"Top management, to help you see the bigger picture","left_body":"From first call to launch, we handle the build so you can focus on the business.","left_cta_label":"See our work","left_cta_url":"\/projects","checks":["Custom architecture for every project.","Design tuned to your brand.","Scalable, maintainable code."],"rows":[{"icon":"layers","title":"Discovery","text":"Scope, goals, and a clear plan."},{"icon":"chat","title":"Design sprint","text":"Prototypes you can click."},{"icon":"rocket","title":"Launch","text":"Ship, monitor, iterate."}]}', '3', '1', '2026-08-21 10:45:05', '2026-08-21 10:45:05');
INSERT INTO `sections` (`id`, `page_id`, `type`, `name`, `content`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES ('5', '1', 'projects', 'Projects', '{"heading":"Featured projects","subheading":"Pulled live from the admin Projects module.","limit":3}', '4', '1', '2026-08-21 10:45:05', '2026-08-21 10:45:05');
INSERT INTO `sections` (`id`, `page_id`, `type`, `name`, `content`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES ('6', '1', 'blog_teaser', 'Insights', '{"heading":"Insights & inspiration","subheading":"Articles, case studies, and lessons from building software in Nigeria.","limit":3}', '5', '1', '2026-08-21 10:45:05', '2026-08-21 10:45:05');
INSERT INTO `sections` (`id`, `page_id`, `type`, `name`, `content`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES ('7', '1', 'cta', 'CTA', '{"heading":"Have a project in mind? Let''s build it.","button_label":"Book a free consultation"}', '6', '1', '2026-08-21 10:45:05', '2026-08-21 10:45:05');

DROP TABLE IF EXISTS `services`;
CREATE TABLE `services` (
    `id` BIGINT NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL,
    `icon` VARCHAR(255),
    `excerpt` VARCHAR(255),
    `body` LONGTEXT,
    `image` VARCHAR(255),
    `sort_order` BIGINT NOT NULL,
    `is_active` TINYINT(1) NOT NULL,
    `meta_title` VARCHAR(255),
    `meta_description` VARCHAR(255),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `meta_keywords` VARCHAR(255),
    `og_image` VARCHAR(255),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `services` (`id`, `title`, `slug`, `icon`, `excerpt`, `body`, `image`, `sort_order`, `is_active`, `meta_title`, `meta_description`, `created_at`, `updated_at`, `meta_keywords`, `og_image`) VALUES ('1', 'Web development', 'web-development', 'code', 'Fast, scalable websites and web apps.', NULL, NULL, '0', '1', NULL, NULL, '2026-08-21 10:45:05', '2026-08-21 10:45:05', NULL, NULL);
INSERT INTO `services` (`id`, `title`, `slug`, `icon`, `excerpt`, `body`, `image`, `sort_order`, `is_active`, `meta_title`, `meta_description`, `created_at`, `updated_at`, `meta_keywords`, `og_image`) VALUES ('2', 'Mobile apps', 'mobile-apps', 'mobile', 'iOS and Android, built to last.', NULL, NULL, '1', '1', NULL, NULL, '2026-08-21 10:45:05', '2026-08-21 10:45:05', NULL, NULL);
INSERT INTO `services` (`id`, `title`, `slug`, `icon`, `excerpt`, `body`, `image`, `sort_order`, `is_active`, `meta_title`, `meta_description`, `created_at`, `updated_at`, `meta_keywords`, `og_image`) VALUES ('3', 'Cloud & DevOps', 'cloud-devops', 'cloud', 'Deploy, scale, and monitor with ease.', NULL, NULL, '2', '1', NULL, NULL, '2026-08-21 10:45:05', '2026-08-21 10:45:05', NULL, NULL);
INSERT INTO `services` (`id`, `title`, `slug`, `icon`, `excerpt`, `body`, `image`, `sort_order`, `is_active`, `meta_title`, `meta_description`, `created_at`, `updated_at`, `meta_keywords`, `og_image`) VALUES ('4', 'UI / UX design', 'ui-ux-design', 'design', 'Interfaces people love to use.', NULL, NULL, '3', '1', NULL, NULL, '2026-08-21 10:45:05', '2026-08-21 10:45:05', NULL, NULL);
INSERT INTO `services` (`id`, `title`, `slug`, `icon`, `excerpt`, `body`, `image`, `sort_order`, `is_active`, `meta_title`, `meta_description`, `created_at`, `updated_at`, `meta_keywords`, `og_image`) VALUES ('5', 'API & integrations', 'api-integrations', 'layers', 'Connect the tools your business runs on.', NULL, NULL, '4', '1', NULL, NULL, '2026-08-21 10:45:05', '2026-08-21 10:45:05', NULL, NULL);
INSERT INTO `services` (`id`, `title`, `slug`, `icon`, `excerpt`, `body`, `image`, `sort_order`, `is_active`, `meta_title`, `meta_description`, `created_at`, `updated_at`, `meta_keywords`, `og_image`) VALUES ('6', 'Maintenance & support', 'maintenance-support', 'shield', 'We keep everything running smoothly.', NULL, NULL, '5', '1', NULL, NULL, '2026-08-21 10:45:05', '2026-08-21 10:45:05', NULL, NULL);

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
    `id` VARCHAR(255) NOT NULL,
    `user_id` BIGINT,
    `ip_address` VARCHAR(255),
    `user_agent` LONGTEXT,
    `payload` LONGTEXT NOT NULL,
    `last_activity` BIGINT NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
    `id` BIGINT NOT NULL,
    `key` VARCHAR(255) NOT NULL,
    `value` LONGTEXT,
    `group` VARCHAR(255) NOT NULL,
    `type` VARCHAR(255) NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('1', 'site_name', 'Quin Tech', 'general', 'text', '2026-08-21 10:45:05', '2026-08-21 10:45:05');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('2', 'tagline', 'Software solutions, powered by engineering.', 'general', 'text', '2026-08-21 10:45:05', '2026-08-21 10:45:05');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('3', 'accent_color', '#ff6a00', 'appearance', 'color', '2026-08-21 10:45:05', '2026-08-21 10:45:05');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('4', 'logo', NULL, 'appearance', 'image', '2026-08-21 10:45:05', '2026-08-21 10:45:05');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('5', 'nav_cta_label', 'Get a quote', 'general', 'text', '2026-08-21 10:45:05', '2026-08-21 10:45:05');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('6', 'footer_tagline', 'Building digital products in Lagos since 2018.', 'general', 'textarea', '2026-08-21 10:45:05', '2026-08-21 10:45:05');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('7', 'meta_description', 'Quin Tech is a Nigerian software agency building web platforms, mobile apps, and cloud systems for ambitious teams.', 'seo', 'textarea', '2026-08-21 10:45:05', '2026-08-21 10:45:05');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('8', 'contact_email', 'hello@quintechnigeria.com', 'contact', 'text', '2026-08-21 10:45:05', '2026-08-21 10:45:05');
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES ('9', 'contact_phone', '+234 800 000 0000', 'contact', 'text', '2026-08-21 10:45:05', '2026-08-21 10:45:05');

DROP TABLE IF EXISTS `team_members`;
CREATE TABLE `team_members` (
    `id` BIGINT NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `role` VARCHAR(255),
    `bio` LONGTEXT,
    `photo` VARCHAR(255),
    `socials` LONGTEXT,
    `sort_order` BIGINT NOT NULL,
    `is_active` TINYINT(1) NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `team_members` (`id`, `name`, `role`, `bio`, `photo`, `socials`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES ('1', 'Ada Okeke', 'Founder & CEO', NULL, NULL, NULL, '0', '1', '2026-08-21 10:45:05', '2026-08-21 10:45:05');
INSERT INTO `team_members` (`id`, `name`, `role`, `bio`, `photo`, `socials`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES ('2', 'Emeka Obi', 'Lead Engineer', NULL, NULL, NULL, '1', '1', '2026-08-21 10:45:05', '2026-08-21 10:45:05');
INSERT INTO `team_members` (`id`, `name`, `role`, `bio`, `photo`, `socials`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES ('3', 'Zainab Bello', 'Head of Design', NULL, NULL, NULL, '2', '1', '2026-08-21 10:45:05', '2026-08-21 10:45:05');

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
    `id` BIGINT NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `email_verified_at` DATETIME,
    `password` VARCHAR(255) NOT NULL,
    `remember_token` VARCHAR(255),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES ('1', 'Quin Tech Admin', 'admin@quintechnigeria.com', NULL, '$2y$12$Yl20qzzdEMglVtxLyBW8QONLmPynDVqpORxmBLMkzRRhRWHVVn6gC', '5jhOaJvFUvzjGyLvxnJGQRGGWrkFRimujCoscvym3xLsaqrcgAg1XWknbk6m', '2026-08-21 10:48:44', '2026-08-21 10:49:20');

SET FOREIGN_KEY_CHECKS=1;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 21, 2024 at 08:16 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lan`
--

-- --------------------------------------------------------

--
-- Table structure for table `contact_info`
--

CREATE TABLE `contact_info` (
  `id` int(11) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `facebook_url` varchar(255) DEFAULT NULL,
  `instagram_url` varchar(255) DEFAULT NULL,
  `linkedin_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_info`
--

INSERT INTO `contact_info` (`id`, `phone_number`, `email`, `facebook_url`, `instagram_url`, `linkedin_url`, `created_at`, `updated_at`) VALUES
(1, '+1234567890', 'example@example.com', 'https://facebook.com/yourprofile', 'https://instagram.com/yourprofile', 'https://linkedin.com/in/yourprofile', '2024-08-11 07:09:33', '2024-08-11 07:09:33');

-- --------------------------------------------------------

--
-- Table structure for table `contact_us`
--

CREATE TABLE `contact_us` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_us`
--

INSERT INTO `contact_us` (`id`, `name`, `email`, `subject`, `message`, `created_at`) VALUES
(3, 'fasdf', 'fadsf@gmail.com', 'dasd', 'adfasf', '2024-08-10 12:00:44'),
(4, 'asfd', 'dafs@gmail.com', 'ffdasf', 'fads', '2024-08-10 12:10:22'),
(5, 'Phone Wai Yan Moe', 'wyan40653@gmail.com', 'hMU HMU', 'Hmu hmu ko chit dl', '2024-08-11 06:19:02');

-- --------------------------------------------------------

--
-- Table structure for table `content_management`
--

CREATE TABLE `content_management` (
  `id` int(11) NOT NULL,
  `type` enum('carousel_image','title','text') NOT NULL,
  `content` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `position` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `content_management`
--

INSERT INTO `content_management` (`id`, `type`, `content`, `image_path`, `position`, `created_at`) VALUES
(3, 'title', 'Shaping your future OK?', NULL, 1, '2024-08-08 16:40:46'),
(4, 'text', 'Our platform offers innovative solutions for your business needs. !!!!!!! Thank you', NULL, 2, '2024-08-08 16:40:46'),
(24, 'carousel_image', '', 'Lan-Sa-main/web/images/carousel/4.jpeg', 1, '2024-08-09 15:07:07');

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `name`, `image_path`) VALUES
(8, 'BUuu', 'Lan-Sa-main/web/images/countries/about.jpeg'),
(9, 'Thai', 'Lan-Sa-main/web/images/countries/thai.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `general_info`
--

CREATE TABLE `general_info` (
  `id` int(11) NOT NULL,
  `key_name` varchar(50) NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `general_info`
--

INSERT INTO `general_info` (`id`, `key_name`, `content`) VALUES
(1, 'big_sentence', 'This is it'),
(2, 'slogan', 'Yes'),
(3, 'section_heading', 'Min Galr pr');

-- --------------------------------------------------------

--
-- Table structure for table `logo_settings`
--

CREATE TABLE `logo_settings` (
  `id` int(11) NOT NULL,
  `logo_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `logo_settings`
--

INSERT INTO `logo_settings` (`id`, `logo_path`) VALUES
(1, 'images/Logo/Lo.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `country_id`, `service_name`, `image_path`, `price`) VALUES
(4, 0, 'Visa', 'images/about.jpeg', 100.00),
(6, 0, 'Visa', 'uploads/services/about.jpeg', 1000.00),
(7, 0, 'Visa', 'Lan-Sa-main/web/images/services/4.jpeg', 100.00),
(8, 0, 'Visa', 'Lan-Sa-main/web/images/services/about.jpeg', 100.00),
(11, 9, 'Visa', 'Lan-Sa-main/web/images/services/thai.jpeg', 100.00);

-- --------------------------------------------------------

--
-- Table structure for table `service_counselors`
--

CREATE TABLE `service_counselors` (
  `id` int(11) NOT NULL,
  `service_time_id` int(11) NOT NULL,
  `counselor_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_counselors`
--

INSERT INTO `service_counselors` (`id`, `service_time_id`, `counselor_name`) VALUES
(1, 1, 'hello');

-- --------------------------------------------------------

--
-- Table structure for table `service_dates`
--

CREATE TABLE `service_dates` (
  `id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `available_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_dates`
--

INSERT INTO `service_dates` (`id`, `service_id`, `available_date`) VALUES
(1, 2, '2024-08-21');

-- --------------------------------------------------------

--
-- Table structure for table `service_times`
--

CREATE TABLE `service_times` (
  `id` int(11) NOT NULL,
  `service_date_id` int(11) NOT NULL,
  `available_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_times`
--

INSERT INTO `service_times` (`id`, `service_date_id`, `available_time`) VALUES
(1, 1, '20:05:07');

-- --------------------------------------------------------

--
-- Table structure for table `site_design`
--

CREATE TABLE `site_design` (
  `id` int(11) NOT NULL,
  `background_color` varchar(7) NOT NULL,
  `font_color` varchar(7) NOT NULL,
  `header_background_color` varchar(7) NOT NULL,
  `header_font_color` varchar(7) NOT NULL,
  `about_background_color` varchar(7) NOT NULL,
  `about_font_color` varchar(7) NOT NULL,
  `footer_background_color` varchar(7) NOT NULL,
  `footer_font_color` varchar(7) NOT NULL,
  `header_image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `site_design`
--

INSERT INTO `site_design` (`id`, `background_color`, `font_color`, `header_background_color`, `header_font_color`, `about_background_color`, `about_font_color`, `footer_background_color`, `footer_font_color`, `header_image`) VALUES
(1, '#F1D2A5', '#3B5A98', '#8C6B48', '#ECC94B', '#F1D2A5', '#3B5A98', '#8C6B48', '#ECC94B', 'header.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `team_members`
--

CREATE TABLE `team_members` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `role` varchar(100) NOT NULL,
  `image` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `team` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `team_members`
--

INSERT INTO `team_members` (`id`, `name`, `role`, `image`, `description`, `team`) VALUES
(1, 'Alice Johnson', 'CEO', 'Lan-Sa-main/web/images/team_members/4.jpeg', 'GOOD', 'Management'),
(2, 'Hmu Hmu', 'PM', 'Lan-Sa-main/web/images/team_members/3.jpeg', 'Project Manager\r\n', 'Management'),
(3, 'Carol White', 'IT Specialist', 'images/team/carol.jpg', 'IT team member.', 'IT'),
(4, 'Dave Brown', 'IT Support', 'images/team/dave.jpg', 'Provides IT support.', 'IT'),
(5, 'Emily Davis', 'Chief Marketing Officer', 'images/team/emily.jpg', 'Oversees all marketing operations.', 'Management'),
(7, 'Grace Lee', 'Chief Operations Officer', 'images/team/grace.jpg', 'Ensures smooth operational processes.', 'Management'),
(8, 'Alice Johnson', 'CEO', 'Lan-Sa-main/web/images/about_us/2.jpeg', 'GOOD', 'Management'),
(9, 'Alice Johnson', 'CEO', 'Lan-Sa-main/web/images/about_us/1.jpeg', 'GOOD', 'Management'),
(10, 'Phone Wai Yan Moe', 'IT', 'Lan-Sa-main/web/images/team_members/4.jpeg', 'Goood', 'IT');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `otp` varchar(6) NOT NULL,
  `profile_picture` varchar(255) DEFAULT 'uploads/default_image.svg',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_confirmed` tinyint(1) DEFAULT 0,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `otp`, `profile_picture`, `created_at`, `is_confirmed`, `is_admin`) VALUES
(1, 'phyo', 'phyotheingi850@gmail.com', '1243', '$2y$10$Zn84GDuV6dlbdhyJleVlFuptaImGAspUyYQChM9A9PGRxm8GttkGi', '', 'uploads/default_image.svg', '2024-08-10 10:29:45', 1, 0),
(2, 'Phone Wai Yan Moe', 'wyan40653@gmail.com', '9420065505', '$2y$10$rzM9CjYCt2m2Uaa9UOxreea9V7HSVD3llCV4fIV28rO5HwWRvzWMC', '', 'uploads/about.jpeg', '2024-08-11 05:47:59', 1, 0),
(6, 'Lann Sa', 'lannsa.org@gmail.com', '0373723592', '$2y$10$MmMuSRojA0Q9Pr9e7a/0SuP25EmY2Vd8KGOUHiKb5oAMoRrYHy1Ea', '', 'uploads/default_image.svg', '2024-08-20 15:18:22', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_ratings`
--

CREATE TABLE `user_ratings` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `rating` tinyint(1) NOT NULL,
  `review_text` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_ratings`
--

INSERT INTO `user_ratings` (`id`, `user_id`, `rating`, `review_text`, `created_at`) VALUES
(4, 1, 5, 'fda', '2024-08-10 10:31:51'),
(5, 1, 4, 'fsdasdfaohiwesfoia[sfhldfaheeakldf\r\ndfadshoiwhe', '2024-08-10 10:44:15'),
(7, 1, 4, 'fast', '2024-08-10 11:03:06'),
(8, 1, 5, 'Very good hmu hmu', '2024-08-11 05:58:45'),
(9, 1, 1, 'Hmu gmu', '2024-08-11 05:59:38'),
(10, 2, 4, 'Hmuhmu', '2024-08-11 06:06:01'),
(11, 2, 5, 'Pretty good website and i love it\r\n', '2024-08-15 10:20:07');

-- --------------------------------------------------------

--
-- Table structure for table `webinars`
--

CREATE TABLE `webinars` (
  `id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `webinars`
--

INSERT INTO `webinars` (`id`, `image_path`, `link`) VALUES
(3, 'Lan-Sa-main/web/images/webinar/1.jpeg', 'https://www.youtube.com/');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contact_info`
--
ALTER TABLE `contact_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_us`
--
ALTER TABLE `contact_us`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `content_management`
--
ALTER TABLE `content_management`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `general_info`
--
ALTER TABLE `general_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logo_settings`
--
ALTER TABLE `logo_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `country_id` (`country_id`);

--
-- Indexes for table `service_counselors`
--
ALTER TABLE `service_counselors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_time_id` (`service_time_id`);

--
-- Indexes for table `service_dates`
--
ALTER TABLE `service_dates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `service_times`
--
ALTER TABLE `service_times`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_date_id` (`service_date_id`);

--
-- Indexes for table `site_design`
--
ALTER TABLE `site_design`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `team_members`
--
ALTER TABLE `team_members`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_ratings`
--
ALTER TABLE `user_ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_id` (`user_id`);

--
-- Indexes for table `webinars`
--
ALTER TABLE `webinars`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contact_info`
--
ALTER TABLE `contact_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contact_us`
--
ALTER TABLE `contact_us`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `content_management`
--
ALTER TABLE `content_management`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `general_info`
--
ALTER TABLE `general_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `logo_settings`
--
ALTER TABLE `logo_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `service_counselors`
--
ALTER TABLE `service_counselors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `service_dates`
--
ALTER TABLE `service_dates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `service_times`
--
ALTER TABLE `service_times`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `site_design`
--
ALTER TABLE `site_design`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `team_members`
--
ALTER TABLE `team_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user_ratings`
--
ALTER TABLE `user_ratings`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `webinars`
--
ALTER TABLE `webinars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_ibfk_1` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `service_counselors`
--
ALTER TABLE `service_counselors`
  ADD CONSTRAINT `service_counselors_ibfk_1` FOREIGN KEY (`service_time_id`) REFERENCES `service_times` (`id`);

--
-- Constraints for table `service_times`
--
ALTER TABLE `service_times`
  ADD CONSTRAINT `service_times_ibfk_1` FOREIGN KEY (`service_date_id`) REFERENCES `service_dates` (`id`);

--
-- Constraints for table `user_ratings`
--
ALTER TABLE `user_ratings`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

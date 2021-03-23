-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 23, 2021 at 05:48 PM
-- Server version: 10.2.37-MariaDB
-- PHP Version: 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `spotterg_owner`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `community_id` int(11) NOT NULL,
  `commenter_id` int(11) NOT NULL,
  `comment_text` text DEFAULT NULL,
  `comment_time` datetime NOT NULL DEFAULT current_timestamp(),
  `comment_thumbs` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `post_id`, `community_id`, `commenter_id`, `comment_text`, `comment_time`, `comment_thumbs`) VALUES
(28, 13, 1, 15, 'FPS FPS FPS YES 144 YES 60 NO', '2021-03-23 15:16:32', 0),
(29, 22, 9, 15, 'Wow so cool!', '2021-03-23 15:21:53', 0),
(31, 13, 1, 6, 'test comment', '2021-03-23 15:26:15', 0);

-- --------------------------------------------------------

--
-- Table structure for table `communities`
--

CREATE TABLE `communities` (
  `community_id` tinyint(4) NOT NULL,
  `community_name` varchar(20) NOT NULL,
  `community_description` varchar(200) NOT NULL,
  `community_last_post_id` int(11) DEFAULT NULL,
  `community_last_commenter_id` int(11) DEFAULT NULL,
  `community_posts` int(11) NOT NULL DEFAULT 0,
  `community_image` varchar(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `communities`
--

INSERT INTO `communities` (`community_id`, `community_name`, `community_description`, `community_last_post_id`, `community_last_commenter_id`, `community_posts`, `community_image`) VALUES
(1, 'Gaming', '', 25, 15, 4, 'img_1.png'),
(2, 'Setups', '', 23, 15, 3, 'img_2.png'),
(3, 'CustomPC', '', 26, 15, 3, 'img_3.png'),
(4, 'Laptops', '', 16, 15, 1, 'img_4.png'),
(5, 'Phone', '', 17, 15, 1, 'img_5.png'),
(6, 'Deals', '', 19, 15, 1, 'img_6.png'),
(7, 'News', '', 20, 15, 1, 'img_7.png'),
(8, 'Coding', '', 21, 15, 5, 'img_8.png'),
(9, 'DIY', '', 22, 15, 7, 'img_9.png');

-- --------------------------------------------------------

--
-- Table structure for table `flairs`
--

CREATE TABLE `flairs` (
  `flair_id` tinyint(4) NOT NULL,
  `flair_text` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL,
  `community_id` tinyint(4) NOT NULL,
  `user_poster_id` int(11) NOT NULL,
  `post_type` bit(1) NOT NULL,
  `post_comments` int(11) DEFAULT 0,
  `post_subject` varchar(150) DEFAULT NULL,
  `post_text` text DEFAULT NULL,
  `post_media` varchar(50) DEFAULT NULL,
  `post_thumbs` int(11) DEFAULT 0,
  `post_flair_id` tinyint(4) DEFAULT NULL,
  `post_creation_date` datetime NOT NULL DEFAULT current_timestamp(),
  `post_last_commenter_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `community_id`, `user_poster_id`, `post_type`, `post_comments`, `post_subject`, `post_text`, `post_media`, `post_thumbs`, `post_flair_id`, `post_creation_date`, `post_last_commenter_id`) VALUES
(26, 3, 15, b'0', 0, 'This is a post!', 'Hello world again!', '', 0, NULL, '2021-03-23 15:24:17', NULL),
(25, 1, 15, b'0', 0, 'I LOVE GAMING', 'GAMING YES', '', 0, NULL, '2021-03-23 15:16:06', NULL),
(24, 3, 15, b'0', 0, 'What specs should I choose for my custom PC', 'Intel or Ryzen?', '', 0, NULL, '2021-03-23 15:15:47', NULL),
(23, 2, 15, b'0', 0, 'RGB, yes or no?', '*controversial opinion*', '', 0, NULL, '2021-03-23 15:14:56', NULL),
(20, 7, 15, b'0', 0, 'In turn of events, fact check website was fact-checked', 'Link --> www.legitnewswebsite.com/clickbait', '', 0, NULL, '2021-03-23 15:11:10', NULL),
(22, 9, 15, b'1', 1, 'This is a cool PC case I made', '', 'https://i.imgur.com/LfkO94O.jpg', 0, NULL, '2021-03-23 15:13:15', 15),
(19, 6, 15, b'1', 0, 'ScamBox Series S(cam) - $299', '', 'https://i.imgur.com/qLyqxQP.jpg', 0, NULL, '2021-03-23 15:10:07', NULL),
(17, 5, 15, b'1', 0, 'The coolest phone you can\'t buy (Huawei P40 Pro)', '', 'https://i.imgur.com/DlskELe.jpg', 0, NULL, '2021-03-23 15:09:45', NULL),
(16, 4, 15, b'1', 0, '#RIPINTEL', 'Look at Intel\'s demise!', 'https://i.imgur.com/o0iAtKM.jpg', 0, NULL, '2021-03-23 15:08:51', NULL),
(13, 1, 6, b'1', 2, 'Why 144Hz is better than 60Hz', 'Because reasons', 'https://i.imgur.com/swDE0o6.jpeg', 0, NULL, '2021-03-23 14:27:34', 6),
(14, 1, 6, b'1', 0, 'visual tearing', 'why u do this?', 'https://i.imgur.com/yAtoATL.png', 0, NULL, '2021-03-23 14:28:54', NULL),
(21, 8, 15, b'1', 0, 'Look at this funny meme', '', 'https://i.imgur.com/2gzX3bI.jpg', 0, NULL, '2021-03-23 15:12:30', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(40) NOT NULL,
  `user_email` varchar(254) NOT NULL,
  `user_ip` varchar(128) NOT NULL,
  `user_password` varchar(128) NOT NULL,
  `user_registerDate` datetime NOT NULL DEFAULT current_timestamp(),
  `user_timezone` tinyint(4) DEFAULT 0,
  `user_post_count` int(11) DEFAULT 0,
  `user_avatar` varchar(40) DEFAULT NULL,
  `user_thumbs` int(11) DEFAULT 0,
  `user_subbed_communities` smallint(6) DEFAULT 0,
  `user_role_id` tinyint(4) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `user_email`, `user_ip`, `user_password`, `user_registerDate`, `user_timezone`, `user_post_count`, `user_avatar`, `user_thumbs`, `user_subbed_communities`, `user_role_id`) VALUES
(3, 'test', 'test@test.com', '6e56a36505c6ee07ad05ff76eb3a6684', '$2y$10$IJUAApYJ9Rm23kwhfRwEjuI0WRshfNztQxblacMEZLiv5MXczBXC6', '2021-03-07 19:21:17', 0, 0, NULL, 0, 0, 0),
(4, 'testuser', 'testuser@email.com', '6e56a36505c6ee07ad05ff76eb3a6684', '$2y$10$h5bfOWzt66Q/Syocjnb1h.fmAmRF7ZDp5Db/ahYaSzHW9.6MUXiy2', '2021-03-07 19:53:19', 0, 0, NULL, 0, 0, 0),
(6, 'admin', 'admin@admin.com', '6e56a36505c6ee07ad05ff76eb3a6684', '$2y$10$G9UpnNdFADRjcY4CW9TBOe4SMSsdJFomED45nGWOEMJw2MtokSMnW', '2021-03-07 20:50:57', 0, 0, NULL, 3, 0, 1),
(7, 'admintwo', 'admin2@admin.com', '6e56a36505c6ee07ad05ff76eb3a6684', '$2y$10$FH8EzuM2LwgQFlZ9DCVgFuShZLXVHAiiaSpIXP1FXVqFyIZ5jcvQS', '2021-03-08 23:56:37', 0, 0, NULL, 0, 0, 0),
(8, 'adminthree', 'admin3@admin.com', '6e56a36505c6ee07ad05ff76eb3a6684', '$2y$10$S74Wdg9W42NBwXQqKUCNH.2m89Z6OT71gM6jjiADl6xTyroaMs8ce', '2021-03-08 23:57:23', 0, 0, NULL, 0, 0, 0),
(9, 'adminfour', 'admin4@admin.com', '6e56a36505c6ee07ad05ff76eb3a6684', '$2y$10$prGezubeTz.8sm9DMy9ytuV1TNKCIeEy7Zxvo.hABhfGBYcZszWzy', '2021-03-09 14:12:19', 0, 0, NULL, 0, 0, 0),
(10, 'testusertwo', 'testuser2@email.com', '6e56a36505c6ee07ad05ff76eb3a6684', '$2y$10$LwJToQsX63IJF2b/ISfvMeOVLp1fz/UZpmJqK3gCCwHXfDbzhlJ7m', '2021-03-09 14:17:14', 0, 0, NULL, 0, 0, 0),
(11, 'jshmo', 'benchadwick87@gmail.com', 'ac885be0752da2f7eb6d8438e695eadf', '$2y$10$bF8bHn8.5ZinK0VYw663r.jRDNbpn2NB7pqhdaG4XMuC45cgFAHuS', '2021-03-09 14:36:09', 0, 0, NULL, 0, 0, 0),
(12, 'hmonatt', 'hjmonatt@yahoo.com', '9638780ffcf2085a1d9364eac2b910a7', '$2y$10$2o0TnHx862XQib/ObPj/IebP5xTk6m9scD7byFhW24KmnOkOqQC7C', '2021-03-09 14:36:15', 0, 0, NULL, 0, 0, 0),
(13, 'hahaha', 'haha@haha.com', '71a43e09b9bdc6aa90b9a8418e3e20bb', '$2y$10$GMRJ/95wBHYcsOT7k7hVZeEQTTbATjL8xC9oZo/WrSdTLBKrxbRly', '2021-03-09 15:30:11', 0, 0, NULL, 0, 0, 0),
(14, 'spotter', 'spotter@mail.greenriver.edu', '6e56a36505c6ee07ad05ff76eb3a6684', '$2y$10$bWr0iNk8RcU6abR2jCG8COdYM61dZDy1pOUwDQZUnnb4Vm86ALZJ.', '2021-03-23 00:02:51', 0, 0, NULL, 0, 0, 0),
(15, 'gmcmullen', 'gmcmullen@mail.greenriver.edu', 'cdde12326535f96a3b12a515fd594de0', '$2y$10$TaaE7PPK6vVHJVAhEu7i0Ox1FEJnscqGtjYBoLs.RckP7yrhy93sW', '2021-03-23 04:17:36', 0, 0, NULL, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `role_id` tinyint(4) NOT NULL,
  `role_name` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_roles`
--

INSERT INTO `user_roles` (`role_id`, `role_name`) VALUES
(0, 'user'),
(1, 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `community_id` (`community_id`),
  ADD KEY `commenter_id` (`commenter_id`);

--
-- Indexes for table `communities`
--
ALTER TABLE `communities`
  ADD PRIMARY KEY (`community_id`),
  ADD KEY `community_last_post_id` (`community_last_post_id`),
  ADD KEY `community_last_commenter_id` (`community_last_commenter_id`);

--
-- Indexes for table `flairs`
--
ALTER TABLE `flairs`
  ADD PRIMARY KEY (`flair_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `user_poster_id` (`user_poster_id`),
  ADD KEY `community_id` (`community_id`),
  ADD KEY `post_flair_id` (`post_flair_id`),
  ADD KEY `post_last_commenter_id` (`post_last_commenter_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `user_role_id` (`user_role_id`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `communities`
--
ALTER TABLE `communities`
  MODIFY `community_id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `flairs`
--
ALTER TABLE `flairs`
  MODIFY `flair_id` tinyint(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

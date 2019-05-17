-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 27, 2018 at 05:04 PM
-- Server version: 5.7.14
-- PHP Version: 7.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `birthdaybuzz`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` bigint(20) NOT NULL,
  `priviledges` varchar(50) NOT NULL,
  `gifts` longtext NOT NULL,
  `last_modified` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `priviledges`, `gifts`, `last_modified`) VALUES
(1, 'SUPER', '["love","celebration","idea","dog","unicorn","butterfly","cat"]', '2018-03-25 11:03:26');

-- --------------------------------------------------------

--
-- Table structure for table `blog`
--

CREATE TABLE `blog` (
  `blog_id` bigint(20) NOT NULL,
  `image` longtext NOT NULL,
  `title` varchar(50) NOT NULL,
  `post` longtext NOT NULL,
  `visitors` bigint(20) NOT NULL,
  `comments` longtext NOT NULL,
  `date` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `blog`
--

INSERT INTO `blog` (`blog_id`, `image`, `title`, `post`, `visitors`, `comments`, `date`) VALUES
(1, 'images/blog/A.jpg', 'The Ferry Twin', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla sed dui felis. Vivamus vitae pharetra nisl, eget fringilla elit. Ut nec est sapien. Aliquam dignissim velit sed nunc imperdiet cursus. Proin arcu diam, tempus ac vehicula a, dictum quis nibh. Maecenas vitae quam ac mi venenatis', 0, '[]', '2018-03-27 14:33:43'),
(2, 'images/blog/B.jpg', 'The Male Gift', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla sed dui felis. Vivamus vitae pharetra nisl, eget fringilla elit. Ut nec est sapien. Aliquam dignissim velit sed nunc imperdiet cursus. Proin arcu diam, tempus ac vehicula a, dictum quis nibh. Maecenas vitae quam ac mi venenatis', 0, '[]', '2018-03-27 14:33:43'),
(3, 'images/blog/C.jpg', 'That Special Day', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla sed dui felis. Vivamus vitae pharetra nisl, eget fringilla elit. Ut nec est sapien. Aliquam dignissim velit sed nunc imperdiet cursus. Proin arcu diam, tempus ac vehicula a, dictum quis nibh. Maecenas vitae quam ac mi venenatis', 0, '[]', '2018-03-27 14:33:43');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` bigint(20) NOT NULL,
  `receiver_id` bigint(20) NOT NULL,
  `sender_id` bigint(20) NOT NULL,
  `comment` longtext NOT NULL,
  `images` longtext NOT NULL,
  `date` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `receiver_id`, `sender_id`, `comment`, `images`, `date`) VALUES
(36, 1, 1, 'swddedede\r\n', '[{"url":"users/comments/36/e2e8c025443fb366c0fd83add78f20c9.png","caption":""}]', '2018-03-27 15:47:37'),
(32, 1, 1, 'jdjdjd\r\n', '[{"url":"users/comments/32/e2e8c025443fb366c0fd83add78f20c9.png","caption":""},{"url":"users/comments/32/6d8998f38d0bfa8fc43f49e844c48a9a.png","caption":""},{"url":"users/comments/32/b6edac5550b78e913a23034cf63e518b.png","caption":""},{"url":"users/comments/32/ec32b03abd19aeb0ff3891df0d9e1dd2.png","caption":""},{"url":"users/comments/32/77fb1f8c19738a5c1bef258465cb0240.png","caption":""}]', '2018-03-24 15:50:39'),
(35, 1, 1, 'jdjdjd\r\n', '[{"url":"users/comments/32/e2e8c025443fb366c0fd83add78f20c9.png","caption":""},{"url":"users/comments/32/6d8998f38d0bfa8fc43f49e844c48a9a.png","caption":""},{"url":"users/comments/32/b6edac5550b78e913a23034cf63e518b.png","caption":""},{"url":"users/comments/32/ec32b03abd19aeb0ff3891df0d9e1dd2.png","caption":""}]', '2018-03-24 15:50:39');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` bigint(20) NOT NULL,
  `username` varchar(100) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `images` longtext NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` longtext NOT NULL,
  `dob` datetime NOT NULL,
  `dob_set` varchar(5) NOT NULL,
  `gifts` longtext NOT NULL,
  `buzz` longtext NOT NULL,
  `online` varchar(1) NOT NULL,
  `status` varchar(1) NOT NULL,
  `time` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `date` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `firstname`, `lastname`, `images`, `mobile`, `email`, `password`, `dob`, `dob_set`, `gifts`, `buzz`, `online`, `status`, `time`, `date`) VALUES
(1, 'nnachijioke', 'Chijioke', 'Nna', '{"profile":"users/accounts/1/profile-image.png","header":"users/accounts/1/header-image.png"}', '08081772922', 'nnachijioke@yahoo.com', '953e90ea6b7f611826fddb0e3e331049', '1994-06-08 00:00:00', 'true', '{"sent":[{"type":"love","to":"1","date":1522064117},{"type":"cat","to":"1","date":1522065125},{"type":"cat","to":"1","date":1522065159},{"type":"dog","to":"1","date":1522065234},{"type":"dog","to":"1","date":1522065241}],"received":[{"type":"love","from":"1","date":1522064117},{"type":"cat","from":"1","date":1522065125},{"type":"cat","from":"1","date":1522065159},{"type":"dog","from":"1","date":1522065234},{"type":"dog","from":"1","date":1522065241}]}', '{"sent":[{"to":"1","date":1522066864}],"received":[{"from":"1","date":1522066864}]}', '0', '1', '2018-03-27 09:38:13', '2018-03-22 17:41:32'),
(3, 'nnachijiokes', 'Chijiokese', 'Nna', '{"profile":"users/accounts/1/profile-image.png","header":"users/accounts/1/header-image.png"}', '08081772923', 'nnachijioke@yahoo.coms', '953e90ea6b7f611826fddb0e3e331049', '1994-06-08 00:00:00', 'true', '{"sent":[{"type":"love","to":"1","date":1522064117},{"type":"cat","to":"1","date":1522065125},{"type":"cat","to":"1","date":1522065159},{"type":"dog","to":"1","date":1522065234},{"type":"dog","to":"1","date":1522065241}],"received":[{"type":"love","from":"1","date":1522064117},{"type":"cat","from":"1","date":1522065125},{"type":"cat","from":"1","date":1522065159},{"type":"dog","from":"1","date":1522065234},{"type":"dog","from":"1","date":1522065241}]}', '{"sent":[{"to":"1","date":1522066864}],"received":[{"from":"1","date":1522066864}]}', '0', '1', '2018-03-27 09:38:20', '2018-03-22 17:41:32');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `blog`
--
ALTER TABLE `blog`
  ADD PRIMARY KEY (`blog_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `mobile` (`mobile`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `blog`
--
ALTER TABLE `blog`
  MODIFY `blog_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

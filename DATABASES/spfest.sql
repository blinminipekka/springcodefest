-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 17, 2025 at 10:26 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `spfest`
--

-- --------------------------------------------------------

--
-- Table structure for table `music_files`
--

CREATE TABLE `music_files` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `song_title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`) VALUES
(1, 'blinhalimi', 'halimiblin@gmail.com', '$2y$10$qU2Q8b/TfFLKDMqUoDsciuSENsaSltQuwbGnECgjUr7C6CB2NU14y'),
(2, '', '', '$2y$10$DBpXe/anDDZbgfYGTWz00.zrume.xzruVtL8RSt70E85vmL6Zh47O'),
(3, 'blinhalimi1', 'blinhalimi4@gmail.com', '$2y$10$eNz.uS.wVcoWZ7Ysnot6b.OjHLGNpmALkwIiU7QuCi6MHGnL/OI6q'),
(4, 'blinhalimi14', 'halimialbion027@gmail.com', '$2y$10$fqqYQl4Lx/BZQ69kskA0.ue45TdDpYcKNHkpSWI0cK5DhyJ8Cy6tu'),
(5, 'blin.halimi1', 'blinhalimi14@gmail.com', '$2y$10$Wh.YZCLzFVwJHX.ONbrjxe2oOIMOThwRkQUXK6AYM.T2Wtinhx66e'),
(6, 'blin.halimi1213', 'blinhalimi14123@gmail.com', '$2y$10$ZqlkL/tuEq/.JJB3NNL4DO2uvtEAEzKDbkmvqNU1KqlbFsxaQrG4y'),
(7, 'halimiblin@gmail.com', 'awkdjwd@gmail.com', '$2y$10$sOgdEt.Gdog0YrF5TUltN.VKW7M4jndQ1t01SSm2J.XLy3.rlf2LO'),
(8, 'blniandnwa', 'awd@gmail.com', '$2y$10$T85GprmAciSlZU7pLyTHguajVDstk5m2PhEsZO2FdFTSwO3UkY33a'),
(9, 'blniandnwa1', 'awd1@gmail.com', '$2y$10$FceFbaowRAJRbqWiprjaDePmjt9eTsbFtsDJp3oUXpw5AYVtNGtsy'),
(10, 'bliawnawmdawnd', 'awdawd@gmail.com', '$2y$10$r0QNZlhnkB64su79dv46Pevr/9durRi8Z/eUCVVDSCITQissKq8TG'),
(11, 'awdkawkdakwdka', 'awndanwd@gmail.com', '$2y$10$HNFU/861HHWbCKn9lxgvZeK/O6OK/MJUH7grKwWhVmdUBNRQoZA8K'),
(12, 'awdawda2da2', 'ad2a@gmail.com', '$2y$10$L3pT1kQqhllQLWe4P50JGOBaFmCuKFRGmurwum0J2e1eaaPFSELBq'),
(13, 'bjasjjdasdas', 'awdawd123123@gmail.com', '$2y$10$6CT9lGqKxEKty7Qh2glU3OU8ydl2VoqhICFH4xFlg0A44qZY2lNnK'),
(14, 'awdawd', 'awdawda@gmail.com', '$2y$10$NdI3lkD3RN2x1IJOF41gfe3TywkVZI1.bSVa49TdCLwqM3kjMsQ22'),
(15, 'adwawda', 'mawdmawd@gmail.com', '$2y$10$DIoq9vASl8szfzwcOsXEv.Bwtgx5nO0gzgUUnXhS6P4I0bZIqNvuK'),
(16, 'adawdawdawdawdawd', 'aw@gmail.com', '$2y$10$DqyNwz7LeFV7KBob3qqoF.SJJQXdw5j6trn.RzIQUed9/c0ojBGGy'),
(17, 'awdawd1231231', 'asndanw@gmail.com', '$2y$10$wiSCpNdHG6nm9rlcXcK8nefH/95QFxGLPKIRKjGg85mGrKwq/3YyK'),
(18, 'dwaaas', 'blinhalimi11114@gmail.com', '$2y$10$hFtTP99mutSU6.WOvfnhY.mzF7fSUZ5c9C2bqwrGS1J56NMT/10QW'),
(19, 'asdawe21d12d', 'awda1231231231wd23@gmail.com1', '$2y$10$QirBayuV8YHd0s6DlheH/ezKCTZMSaA9deJCSYUaxplF.JtcsZH1K'),
(20, 'blinhalimi123123123', '123123123@gmail.com', '$2y$10$.OMcYsG2gErAuB1OXWl/se9Phm3m2obe0Pu/oTUSHru6Gclvy8Sgy'),
(21, 'blinhalimi123123', 'halimialbion12307@gmail.com', '$2y$10$kcDPqyv0x.XhtRq6/H.Bt.F905rAuBYMTX/6ynrj3TTM5/luXUJ4q'),
(22, 'awdawdawd', 'awdawdawd@gmail.com', '$2y$10$jSpgras/QhBy48ZuyscTjuig5wzTPVtvDpCOg8HSYy7PVDHkRu5ou');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `music_files`
--
ALTER TABLE `music_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `music_files`
--
ALTER TABLE `music_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `music_files`
--
ALTER TABLE `music_files`
  ADD CONSTRAINT `music_files_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

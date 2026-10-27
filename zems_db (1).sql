-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 25, 2026 at 07:05 AM
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
-- Database: `zems_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `id` varchar(191) NOT NULL,
  `userId` varchar(191) NOT NULL,
  `type` varchar(191) NOT NULL,
  `provider` varchar(191) NOT NULL,
  `providerAccountId` varchar(191) NOT NULL,
  `refresh_token` text DEFAULT NULL,
  `access_token` text DEFAULT NULL,
  `expires_at` int(11) DEFAULT NULL,
  `token_type` varchar(191) DEFAULT NULL,
  `scope` varchar(191) DEFAULT NULL,
  `id_token` text DEFAULT NULL,
  `session_state` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auditlog`
--

CREATE TABLE `auditlog` (
  `id` varchar(191) NOT NULL,
  `userId` varchar(191) DEFAULT NULL,
  `userEmail` varchar(191) DEFAULT NULL,
  `action` varchar(191) NOT NULL,
  `resourceType` varchar(191) NOT NULL,
  `resourceId` varchar(191) DEFAULT NULL,
  `details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`details`)),
  `createdAt` datetime(3) NOT NULL DEFAULT current_timestamp(3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `auditlog`
--

INSERT INTO `auditlog` (`id`, `userId`, `userEmail`, `action`, `resourceType`, `resourceId`, `details`, `createdAt`) VALUES
('01KHTBJTJ3X88AQ6NENTSGKP6Y', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'LOGIN', 'user', 'cmlert9gm00006kl4owxyxwrq', NULL, '2026-02-19 07:07:14.000'),
('01KHV191H8GJ7N00K7B2VA2WE8', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'LOGIN', 'user', 'cmlert9gm00006kl4owxyxwrq', NULL, '2026-02-19 13:26:22.000'),
('01KHVHP91701D89SX4FZ63H4AD', NULL, 'teacher@wilgamuwa.com', 'LOGIN', 'user', 'cmleupv0z000412ziio0zdoh3', NULL, '2026-02-19 18:13:13.000'),
('01KHVHQFK7Y9S5EJY7S7WF03DG', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'LOGIN', 'user', 'cmlert9gm00006kl4owxyxwrq', NULL, '2026-02-19 18:13:52.000'),
('01KHWPNY1CYY8NDN080821GA57', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'LOGIN', 'user', 'cmlert9gm00006kl4owxyxwrq', NULL, '2026-02-20 04:59:39.000'),
('01KHWXVCMA77E6N777N9KZM467', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'LOGIN', 'user', 'cmlert9gm00006kl4owxyxwrq', NULL, '2026-02-20 07:04:58.000'),
('01KHXP5Q4E0XX10CHS27XANH8W', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'LOGIN', 'user', 'cmlert9gm00006kl4owxyxwrq', NULL, '2026-02-20 14:10:02.000'),
('01KJ22RB1NE1GXX0608NTW89X8', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'LOGIN', 'user', 'cmlert9gm00006kl4owxyxwrq', NULL, '2026-02-22 07:06:53.000'),
('01KJ2HA4T9WKGQJ0YREHSC39FB', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'LOGIN', 'user', 'cmlert9gm00006kl4owxyxwrq', NULL, '2026-02-22 11:21:16.000'),
('01KJ2SG08MJ31N9FT2CA2M83FZ', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'LOGIN', 'user', 'cmlert9gm00006kl4owxyxwrq', NULL, '2026-02-22 13:44:17.000'),
('01KJ32145PJZK2Q7SG46MW2ZSW', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'DELETE_TEACHER', 'teacher', 'cmleupv1q000612zigfa0ybak', '{\"name\":\"A. B. Teacher\",\"reason\":\"Transfer out of Zone\",\"effective_date\":\"2026-02-21\"}', '2026-02-22 16:13:27.000'),
('01KJ33SCA645YR8PMS3T68SB8B', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'LOGIN', 'user', 'cmlert9gm00006kl4owxyxwrq', NULL, '2026-02-22 16:44:10.000'),
('01KJ37ZZETMJ3RZ3C5BE2HFX60', 'cmleupuwj000312zi7vedpiop', 'school@wilgamuwa.com', 'LOGIN', 'user', 'cmleupuwj000312zi7vedpiop', NULL, '2026-02-22 17:57:40.000'),
('01KJ4DF403X65FR9C4KHGSZXRN', 'cmleupuwj000312zi7vedpiop', 'school@wilgamuwa.com', 'LOGIN', 'user', 'cmleupuwj000312zi7vedpiop', NULL, '2026-02-23 04:52:34.000'),
('01KJ4NTMG9YE765BXF6WATMNE6', 'cmleupuwj000312zi7vedpiop', 'school@wilgamuwa.com', 'LOGIN', 'user', 'cmleupuwj000312zi7vedpiop', NULL, '2026-02-23 07:18:40.000'),
('01KJ4SXYD5ANPTYA3WCGWK97HN', 'cmleupuwj000312zi7vedpiop', 'school@wilgamuwa.com', 'LOGIN', 'user', 'cmleupuwj000312zi7vedpiop', NULL, '2026-02-23 08:30:23.000'),
('01KJ59S61HE1GJC6VCRAFP1P7E', 'cmleupuwj000312zi7vedpiop', 'school@wilgamuwa.com', 'LOGIN', 'user', 'cmleupuwj000312zi7vedpiop', NULL, '2026-02-23 13:07:24.000'),
('01KJ5XM3SX0ZPKCHP2GZXYSNER', 'cmleupuwj000312zi7vedpiop', 'school@wilgamuwa.com', 'LOGIN', 'user', 'cmleupuwj000312zi7vedpiop', NULL, '2026-02-23 18:54:09.000'),
('01KJ5ZHC272AM2Y8G96VM4PN3H', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'LOGIN', 'user', 'cmlert9gm00006kl4owxyxwrq', NULL, '2026-02-23 19:27:37.000'),
('01KJ5ZR5BY40CT0T0X7JMQ24VF', 'cmleupuwj000312zi7vedpiop', 'school@wilgamuwa.com', 'LOGIN', 'user', 'cmleupuwj000312zi7vedpiop', NULL, '2026-02-23 19:31:19.000'),
('01KJ9JT85STVXYE8KC5G0T5DZ2', 'cmleupuwj000312zi7vedpiop', 'school@wilgamuwa.com', 'LOGIN', 'user', 'cmleupuwj000312zi7vedpiop', NULL, '2026-02-25 05:02:14.000'),
('01KJ9JXDHJR26R9SXJ7DF74583', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'LOGIN', 'user', 'cmlert9gm00006kl4owxyxwrq', NULL, '2026-02-25 05:03:58.000'),
('01KJ9MFTSKMQ17NRNTBTPZ4RP7', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'LOGIN', 'user', 'cmlert9gm00006kl4owxyxwrq', NULL, '2026-02-25 05:31:29.000'),
('audit_6995eccdd5cf14.48418185', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'UPDATE_TEACHER', 'Teacher', 'cmlf6hmor000dppskcd9j3vet', '{\"nic\":\"199612501230\",\"name\":\"W.R.M.K.G.U.B.U.B. Konage\"}', '2026-02-18 16:46:05.000'),
('audit_6995ece706fdc0.95175428', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'UPDATE_TEACHER', 'Teacher', 'cmlf6hmor000dppskcd9j3vet', '{\"nic\":\"199612501230\",\"name\":\"W.R.M.K.G.U.B.U.B. Konage\"}', '2026-02-18 16:46:31.000'),
('cmlexzak40004mhak4g4r2l9w', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'UPDATE_SCHOOL', 'School', 'cmlewler3000g6g5wj7r66ybo', '{\"name\":\"Agrabodhi Junior School\",\"censusNo\":\"04129\"}', '2026-02-09 09:00:39.604'),
('cmlezieqy0007mhakclbmkjgg', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'UPDATE_TEACHER', 'Teacher', 'cmlexzajo0002mhaka2ieiaji', '{\"nic\":\"177062763958\",\"name\":\"H.M.K.G.V.U.K. Bandara\"}', '2026-02-09 09:43:31.114'),
('cmlezlnt5000amhakhfctm549', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'UPDATE_TEACHER', 'Teacher', 'cmlexzajo0002mhaka2ieiaji', '{\"nic\":\"177062763958\",\"name\":\"H.M.K.G.V.U.K. Bandara\"}', '2026-02-09 09:46:02.825'),
('cmlf0kenq000emhakk51dbp29', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf0kemb000cmhakpk6cxbgu', '{\"name\":\"ප‍්‍රාථමික පොදු — Primary General\"}', '2026-02-09 10:13:03.926'),
('cmlf0tmdp000mmhakhbey0dpz', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf0tmcr000kmhakoiv0tkpc', '{\"name\":\"ප‍්‍රාථමික ඉංග්රීසි — Primary English\"}', '2026-02-09 10:20:13.837'),
('cmlf19yom000rmhakmt6mljf0', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'DELETE_SUBJECT', 'Subject', 'cmlf0tmcr000kmhakoiv0tkpc', '{}', '2026-02-09 10:32:56.279'),
('cmlf1a0fn000umhakx3qiec6n', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'DELETE_SUBJECT', 'Subject', 'cmlf0kemb000cmhakpk6cxbgu', '{}', '2026-02-09 10:32:58.547'),
('cmlf1ahhi000ymhak4x5w7tf7', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf1ahg3000wmhakwa4klgxf', '{\"name\":\"ප‍්‍රාථමික ඉංග්රීසි — Primary English\"}', '2026-02-09 10:33:20.646'),
('cmlf1etjq0011mhakuyd8d8z9', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'DELETE_SUBJECT', 'Subject', 'cmlf1ahg3000wmhakwa4klgxf', '{}', '2026-02-09 10:36:42.902'),
('cmlf1f2rj0015mhakpqbmjjtg', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf1f2r70013mhakyqqm7h13', '{\"name\":\"ප‍්‍රාථමික පොදු — Primary General\"}', '2026-02-09 10:36:54.847'),
('cmlf20zzp0019mhakmqopzgvm', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf20zz70017mhak3y4z5suy', '{\"name\":\"අභ්යන්තර නිර්මාණකරණය — Interior Designing\"}', '2026-02-09 10:53:57.686'),
('cmlf2125f001dmhak327ziwdn', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2124j001bmhak2twrmmhs', '{\"name\":\"අලුමිනියම් සැකසුම් අධ්යයනය — Aluminum Fabrication Studies\"}', '2026-02-09 10:54:00.484'),
('cmlf213td001hmhaka8mm8czi', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf213sy001fmhak2z758ryg', '{\"name\":\"ආහාර සැකසුම් අධ්යයනය — Food Processing Studies\"}', '2026-02-09 10:54:02.641'),
('cmlf215i0001lmhakqhx3uzig', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf215ho001jmhaki4xz32a5', '{\"name\":\"අරාබි — Arabic\"}', '2026-02-09 10:54:04.824'),
('cmlf2173a001pmhakhs43z3ug', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf21732001nmhakkwvaf2ax', '{\"name\":\"ආගම — Religion\"}', '2026-02-09 10:54:06.886'),
('cmlf218uv001tmhakvltib82d', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf218um001rmhak7uj664ov', '{\"name\":\"ආහාර හා තාක්ෂණවේදය — Food & Technology\"}', '2026-02-09 10:54:09.175'),
('cmlf21etl001xmhakwtcb26tn', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf21etc001vmhak9ahpb3cd', '{\"name\":\"අරාබි (උ.පෙළ) — Arabic (A/L)\"}', '2026-02-09 10:54:16.906'),
('cmlf21grg0021mhak47s3m5yw', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf21gpu001zmhakxgsfrm8b', '{\"name\":\"ආර්ථික විද්යාව — Economics\"}', '2026-02-09 10:54:19.420'),
('cmlf21iwq0025mhak94qc78cy', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf21iwb0023mhakp282mp6o', '{\"name\":\"ඉංග්රීසි — English\"}', '2026-02-09 10:54:22.202'),
('cmlf2g76n0029mhak4ll5cd01', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2g75r0027mhaks8z0x7ye', '{\"name\":\"ඉංග්රීසි (උ.පෙළ) — English (A/L)\"}', '2026-02-09 11:05:46.847'),
('cmlf2g8sy002dmhakfr2ogpzp', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2g8sw002bmhaka37wm24e', '{\"name\":\"ඉංජිනේරු තාක්ෂණවේදය — Engineering Technology\"}', '2026-02-09 11:05:48.947'),
('cmlf2gafk002hmhak1xb3tg0d', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2gadh002fmhaktltc62re', '{\"name\":\"ඉතිහාසය — History\"}', '2026-02-09 11:05:51.056'),
('cmlf2gw2z002lmhak2gisplc5', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2gw22002jmhakg13c2tqr', '{\"name\":\"ඉතිහාසය (ඉන්දියා / යුරෝපා / සමකාලීන ලෝක) — History (Indian / European / Contemporary World)\"}', '2026-02-09 11:06:19.115'),
('cmlf2gxpl002pmhak8sgqf9ay', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2gxok002nmhakfx6izasn', '{\"name\":\"ඉදිකිරීම් අධ්යයනය — Construction Studies\"}', '2026-02-09 11:06:21.225'),
('cmlf2gzh9002tmhak1phn9fnp', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2gz7j002rmhak1tvzi7ce', '{\"name\":\"ඉවෙන්ට් කළමනාකරණය — Event Management\"}', '2026-02-09 11:06:23.518'),
('cmlf2h12w002xmhakkbqa5e7f', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2h11v002vmhak7pj047x3', '{\"name\":\"ඉස්ලාම් — Islam\"}', '2026-02-09 11:06:25.592'),
('cmlf2h2o10031mhakvj1ubujk', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2h2nm002zmhak453ey18z', '{\"name\":\"ඉස්ලාම් ශිෂ්ටාචාරය — Islam Civilization\"}', '2026-02-09 11:06:27.650'),
('cmlf2h4hu0035mhakpmqg6ejf', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2h4g00033mhakdo4je42q', '{\"name\":\"උද්යානය හා උද්භිද විද්යාව — Applied Horticultural Studies\"}', '2026-02-09 11:06:30.018'),
('cmlf2h6z30039mhak7azejsvn', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2h6x10037mhak2argryc3', '{\"name\":\"උසස් ගණිතය — Higher Mathematics / A/L Mathematics\"}', '2026-02-09 11:06:33.231'),
('cmlf2h8zu003dmhakxj4ag27j', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2h8yy003bmhak2bsev0ha', '{\"name\":\"කලා / චිත්ර (උ.පෙළ) — Art (A/L)\"}', '2026-02-09 11:06:35.850'),
('cmlf2hal7003hmhakani20cwc', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2haj1003fmhak2hka4u3k', '{\"name\":\"කර්ණාටක සංගීතය — Karnatic Music\"}', '2026-02-09 11:06:37.915'),
('cmlf2hcko003lmhak5xt6xo44', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2hcjn003jmhakmfbxjw3d', '{\"name\":\"කලා හා නිර්මාණකරණය — Art & Designing\"}', '2026-02-09 11:06:40.488'),
('cmlf2he7z003pmhakb7kxdns5', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2he6t003nmhak5i42f761', '{\"name\":\"කර්ණාටක සංගීතය (උ.පෙළ) — Karnatic Music (A/L)\"}', '2026-02-09 11:06:42.623'),
('cmlf2hfv3003tmhakcheu9j7h', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2hftq003rmhakuy3eqwyn', '{\"name\":\"කලා හා හස්තකලා — Art & Craft\"}', '2026-02-09 11:06:44.751'),
('cmlf2hrqx003xmhak8q0xb8jf', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2hrqc003vmhakf7cx6fxi', '{\"name\":\"කෘෂි තාක්ෂණවේදය — Agricultural Technology\"}', '2026-02-09 11:07:00.153'),
('cmlf2hthq0041mhakxj3pgtxx', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2hthl003zmhak9jum7yzf', '{\"name\":\"කෘෂි විද්යාව — Agricultural Science\"}', '2026-02-09 11:07:02.414'),
('cmlf2hv2y0045mhakrpd4936h', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2hv2s0043mhaknkct8p18', '{\"name\":\"කෘෂි හා ආහාර තාක්ෂණය — Agriculture & Food Technology\"}', '2026-02-09 11:07:04.474'),
('cmlf2hwt00049mhakfo2x2mqb', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2hwsx0047mhakovx4ljo9', '{\"name\":\"කොරියානු — Korean\"}', '2026-02-09 11:07:06.709'),
('cmlf2hz08004dmhakx3bwed14', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2hyzx004bmhakt61836td', '{\"name\":\"ක්රිස්තියානි ධර්මය — Christianity\"}', '2026-02-09 11:07:09.560'),
('cmlf2i0k4004hmhakeiefk38c', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2i0ji004fmhakn1xl5lbe', '{\"name\":\"ක්රිස්තියානි ශිෂ්ටාචාරය — Christian Civilization\"}', '2026-02-09 11:07:11.573'),
('cmlf2i2ki004lmhak6ym93o5c', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2i2kc004jmhakop88t6n5', '{\"name\":\"ගණිතය — Mathematics\"}', '2026-02-09 11:07:14.178'),
('cmlf2i48q004pmhakuj7kxc9f', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2i48j004nmhake4co68hb', '{\"name\":\"ගිණුම්කරණය — Accountancy\"}', '2026-02-09 11:07:16.346'),
('cmlf2i5zp004tmhaktxqsuguk', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2i5zi004rmhakh263i470', '{\"name\":\"ගෘහ ආර්ථික විද්යාව — Home Economics\"}', '2026-02-09 11:07:18.613'),
('cmlf2i7mk004xmhak4fb2gh7w', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2i7md004vmhakyujscw2l', '{\"name\":\"ගෘහ ආර්ථික විද්යාව (උ.පෙළ) — Home Economics (A/L)\"}', '2026-02-09 11:07:20.733'),
('cmlf2i9vo0051mhakv2lpmn6z', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2i9t4004zmhakpwsknxxc', '{\"name\":\"ග්රැෆික් නිර්මාණකරණය — Graphic Design\"}', '2026-02-09 11:07:23.652'),
('cmlf2ic6j0055mhakd8tj8bl0', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2ic660053mhakd5s4b6t7', '{\"name\":\"ග්රීක හා රෝම ශිෂ්ටාචාරය — Greek & Roman Civilization\"}', '2026-02-09 11:07:26.635'),
('cmlf2ie030059mhak6xfoej61', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2idzw0057mhakurxllvgi', '{\"name\":\"චිත්ර / කලා — Art\"}', '2026-02-09 11:07:28.995'),
('cmlf2ifcp005dmhak3ygi264l', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2ifce005bmhakrelgl17x', '{\"name\":\"චීන — Chinese\"}', '2026-02-09 11:07:30.746'),
('cmlf2ihdc005hmhakelevurpy', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2ihd4005fmhakixyzxqmf', '{\"name\":\"චීන (උ.පෙළ) — Chinese (A/L)\"}', '2026-02-09 11:07:33.361'),
('cmlf2ij0w005lmhaknpzuayja', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2ij0h005jmhak2sfbtzf6', '{\"name\":\"ජපන් — Japanese\"}', '2026-02-09 11:07:35.505'),
('cmlf2ilx6005pmhakte8h6qff', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2ilwi005nmhakbyar4x5u', '{\"name\":\"ජපන් (උ.පෙළ) — Japanese (A/L)\"}', '2026-02-09 11:07:39.259'),
('cmlf2ingi005tmhak2md97uhd', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2ingb005rmhak191ku5pv', '{\"name\":\"ජර්මන් — German\"}', '2026-02-09 11:07:41.251'),
('cmlf2ip1n005xmhak0yugchqz', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2ip1h005vmhak4lx9sgmq', '{\"name\":\"ජලජ ජෛව සම්පත් තාක්ෂණය — Aquatic Bioresources Technology\"}', '2026-02-09 11:07:43.308'),
('cmlf2irbb0061mhakfwh00q0c', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2irb4005zmhak1alxya4a', '{\"name\":\"ජලජ සම්පත් අධ්යයනය — Aquatic Resource Studies\"}', '2026-02-09 11:07:46.248'),
('cmlf2isw30065mhakoa1q55pv', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2isvv0063mhakib99zkwp', '{\"name\":\"ජර්මන් (උ.පෙළ) — German (A/L)\"}', '2026-02-09 11:07:48.291'),
('cmlf2iur80069mhak4rozguco', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2iur10067mhakhrdq94y7', '{\"name\":\"ජීව විද්යාව — Biology\"}', '2026-02-09 11:07:50.709'),
('cmlf2iwcr006dmhak15jljg7i', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2iwc7006bmhakkz474dw1', '{\"name\":\"ජෛව පද්ධති තාක්ෂණවේදය — Bio System Technology\"}', '2026-02-09 11:07:52.780'),
('cmlf2ixwz006hmhakda4nhbic', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2ixws006fmhakf27slsae', '{\"name\":\"ජෛව සම්පත් තාක්ෂණවේදය — Bio Resources Technology\"}', '2026-02-09 11:07:54.804'),
('cmlf2izjf006lmhaksea9g13o', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2izj6006jmhaky0ndxlqm', '{\"name\":\"තර්ක ශාස්ත්රය හා විද්යාත්මක ක්රමය — Logic & Scientific Method\"}', '2026-02-09 11:07:56.907'),
('cmlf2j14r006pmhak9t7nswzu', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2j14j006nmhakyvnabq7j', '{\"name\":\"තාක්ෂණවේදය සඳහා විද්යාව — Science for Technology\"}', '2026-02-09 11:07:58.971'),
('cmlf2j49b006tmhak7u3ek9so', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2j491006rmhakfcr06re3', '{\"name\":\"තොරතුරු හා සන්නිවේදන තාක්ෂණය — Information & Communication Technology (ICT)\"}', '2026-02-09 11:08:03.023'),
('cmlf2j5vc006xmhak0kxdeafb', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2j5v4006vmhak1qkv6gos', '{\"name\":\"දෙමළ (උ.පෙළ) — Tamil (A/L)\"}', '2026-02-09 11:08:05.113'),
('cmlf2j7hd0071mhak4ee8ejhl', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2j7h7006zmhak1t88u4z6', '{\"name\":\"දෙවන භාෂාව ලෙස දෙමළ — Tamil as a Second Language\"}', '2026-02-09 11:08:07.201'),
('cmlf2j9540075mhakqnnt09yt', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2j94g0073mhakzo6mwiso', '{\"name\":\"දෙවන භාෂාව ලෙස සිංහල — Sinhala as a Second Language\"}', '2026-02-09 11:08:09.352'),
('cmlf2jbtd0079mhakly1cbhi8', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2jbt40077mhakdjjj4r19', '{\"name\":\"දේශපාලන විද්යාව — Political Science\"}', '2026-02-09 11:08:12.818'),
('cmlf2jdlz007dmhakuloa892f', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2jdlf007bmhakvpfjgepc', '{\"name\":\"නාට්ය හා රංග කලාව — Drama & Theatre\"}', '2026-02-09 11:08:15.143'),
('cmlf2jf8c007hmhakcar42edc', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2jf88007fmhakr3byypab', '{\"name\":\"නාට්ය හා රංග කලාව (උ.පෙළ) — Drama & Theatre (A/L)\"}', '2026-02-09 11:08:17.244'),
('cmlf2jgte007lmhak8emjo3hz', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2jgt5007jmhak5cdlul7p', '{\"name\":\"නැටුම් — Dancing\"}', '2026-02-09 11:08:19.299'),
('cmlf2jiij007pmhakj2b84ae1', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2jihk007nmhakg2zwv5gz', '{\"name\":\"නැටුම් (දේශීය - උ.පෙළ) — Dancing (Traditional - A/L)\"}', '2026-02-09 11:08:21.499'),
('cmlf2jk9x007tmhak3gzanhet', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2jk9o007rmhakifx350cy', '{\"name\":\"නැටුම් (දේශීය) — Dancing (Traditional)\"}', '2026-02-09 11:08:23.781'),
('cmlf2jlpj007xmhakazgydtcb', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2jlpa007vmhakc1l878ru', '{\"name\":\"නැටුම් (භරත - උ.පෙළ) — Dancing (Bharata - A/L)\"}', '2026-02-09 11:08:25.639'),
('cmlf2jncv0081mhak4k37ydwo', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2jnc3007zmhaka7n4zp9k', '{\"name\":\"නැටුම් (භරත) — Dancing (Bharata)\"}', '2026-02-09 11:08:27.775'),
('cmlf2jp4g0085mhakkywtxzkn', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2jp470083mhak3nq5t4l2', '{\"name\":\"නිර්මාණකරණය හා තාක්ෂණවේදය — Design & Technology\"}', '2026-02-09 11:08:30.064'),
('cmlf2jsil0089mhakmwlwyeek', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2jsic0087mhakfx34il7s', '{\"name\":\"නිෂ්පාදන අධ්යයනය — Manufacturing\"}', '2026-02-09 11:08:34.462'),
('cmlf2ju50008dmhakqelcxlzn', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2ju4q008bmhakw2vaic3l', '{\"name\":\"පරිගණක දෘඩාංග හා ජාලකරණය — Computer Hardware & Networking\"}', '2026-02-09 11:08:36.564'),
('cmlf2jwac008hmhakat44tcz3', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2jwaa008fmhake29naaa1', '{\"name\":\"පරිසර අධ්යයනය — Environmental Studies\"}', '2026-02-09 11:08:39.349'),
('cmlf2jyq8008lmhakemz4iq7c', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2jypy008jmhakv2nfkz6x', '{\"name\":\"පුරවැසි අධ්යයනය හා ප්රජා පාලනය — Civic Studies & Governance\"}', '2026-02-09 11:08:42.512'),
('cmlf2k0bg008pmhakkc6h38yl', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2k0bd008nmhakm64ar065', '{\"name\":\"පාලි (උ.පෙළ) — Pali (A/L)\"}', '2026-02-09 11:08:44.573'),
('cmlf2k1zt008tmhakh14wiua9', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2k1yo008rmhakefoobzpi', '{\"name\":\"පාලි — Pali\"}', '2026-02-09 11:08:46.745'),
('cmlf2k3y5008xmhakceybly08', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2k3xv008vmhakkkbwaqad', '{\"name\":\"පෙරදිග සංගීතය — Eastern Music\"}', '2026-02-09 11:08:49.277'),
('cmlf2k5n10091mhakk84e3ugr', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2k5m5008zmhakvjurfpt1', '{\"name\":\"පෙරදිග සංගීතය (උ.පෙළ) — Eastern Music (A/L)\"}', '2026-02-09 11:08:51.469'),
('cmlf2k8590095mhak2uuz10wj', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2k8570093mhak21hkiuyc', '{\"name\":\"ප්රංශ — French\"}', '2026-02-09 11:08:54.718'),
('cmlf2k9xe0099mhak11ypzdbk', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2k9x40097mhakwtv3s4q0', '{\"name\":\"ප්රංශ (උ.පෙළ) — French (A/L)\"}', '2026-02-09 11:08:57.027'),
('cmlf2kbm0009dmhaks905p6h2', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2kblp009bmhako5157dv8', '{\"name\":\"ප්රාථමික ඉංග්රීසි — Primary English\"}', '2026-02-09 11:08:59.209'),
('cmlf2l682009gmhak6tbkk0cv', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'DELETE_SUBJECT', 'Subject', 'cmlf1f2r70013mhakyqqm7h13', '{}', '2026-02-09 11:09:38.883'),
('cmlf2l7s2009kmhak54tu5fn4', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2l7rk009imhak184ogyab', '{\"name\":\"ප්රාථමික පොදු — Primary General\"}', '2026-02-09 11:09:40.899'),
('cmlf2lv4w009omhakcxazb61j', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2lv47009mmhak6bmx2018', '{\"name\":\"ප්රායෝගික හා තාක්ෂණික කුසලතා — Practical & Technical Skills\"}', '2026-02-09 11:10:11.168'),
('cmlf2lwty009smhakavpk62f2', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2lwt7009qmhaknos5jnpd', '{\"name\":\"ප්රාසාංගික කලාව — Performing Arts\"}', '2026-02-09 11:10:13.366'),
('cmlf2lyhd009wmhakkpxrgmkq', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2lygn009umhakyt4urt1m', '{\"name\":\"බටහිර සංගීතය — Western Music\"}', '2026-02-09 11:10:15.505'),
('cmlf2m14p00a0mhak51pnhkay', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2m13i009ymhakcidggymp', '{\"name\":\"බටහිර සංගීතය (උ.පෙළ) — Western Music (A/L)\"}', '2026-02-09 11:10:18.937'),
('cmlf2m2ub00a4mhakoh0wtc51', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2m2tf00a2mhak58875osp', '{\"name\":\"බුද්ධ ධර්මය — Buddhism\"}', '2026-02-09 11:10:21.156'),
('cmlf2m4hd00a8mhakocyj8puz', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2m4go00a6mhakzxms26pk', '{\"name\":\"බෞද්ධ ශිෂ්ටාචාරය — Buddhist Civilization\"}', '2026-02-09 11:10:23.282'),
('cmlf2m6rf00acmhako4tc4uij', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2m6qp00aamhakewml3m4z', '{\"name\":\"භූගෝල විද්යාව — Geography\"}', '2026-02-09 11:10:26.235'),
('cmlf2m8iu00agmhak36zn96j9', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2m8i400aemhak0t0gpq6x', '{\"name\":\"භූගෝල විද්යාව (උ.පෙළ) — A/L Geography\"}', '2026-02-09 11:10:28.518'),
('cmlf2mago00akmhakjopdjy9a', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2mafx00aimhak2zlpm4pc', '{\"name\":\"භූමි අලංකරණය — Landscaping\"}', '2026-02-09 11:10:31.032'),
('cmlf2mcg200aomhakt0km3pau', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2mcfc00ammhak7pdub8ts', '{\"name\":\"භෞතික විද්යාව — Physics\"}', '2026-02-09 11:10:33.602'),
('cmlf2meaz00asmhak0jrvajni', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2meax00aqmhakejx2merc', '{\"name\":\"මව්බස (දෙමළ) — Mother Language (Tamil)\"}', '2026-02-09 11:10:36.011'),
('cmlf2mgy300awmhakrj7zbtt5', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2mgv000aumhakiud6rshf', '{\"name\":\"මව්බස (සිංහල) — Mother Language (Sinhala)\"}', '2026-02-09 11:10:39.435'),
('cmlf2mjw200b0mhakej5arhmz', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2mjvc00aymhak1l8x278y', '{\"name\":\"මැලේ — Malay\"}', '2026-02-09 11:10:43.250'),
('cmlf2mlr200b4mhakqrz4jb7n', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2mlqv00b2mhakqybb16fn', '{\"name\":\"මෘදුකාංග සංවර්ධනය — Software Development\"}', '2026-02-09 11:10:45.662'),
('cmlf2mnjb00b8mhakaa32aoxt', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2mnij00b6mhak0em1hez1', '{\"name\":\"මෝටර් රථ අධ්යයනය — Automobile Studies\"}', '2026-02-09 11:10:47.975'),
('cmlf2mpbx00bcmhakg62dyico', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2mpb700bamhakxejm64of', '{\"name\":\"යාන්ත්රික තාක්ෂණවේදය — Mechanical Technology\"}', '2026-02-09 11:10:50.301'),
('cmlf2mr2800bgmhak1tdgwuji', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2mr1s00bemhakwxr1ci1r', '{\"name\":\"රසායන විද්යාව — Chemistry\"}', '2026-02-09 11:10:52.544'),
('cmlf2mt2d00bkmhakjnd8i30m', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2mt1x00bimhakvhhjsj10', '{\"name\":\"රුසියානු — Russian\"}', '2026-02-09 11:10:55.142'),
('cmlf2mush00bomhaktl9qb720', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2murp00bmmhak2nl0enex', '{\"name\":\"රුසියානු (උ.පෙළ) — Russian (A/L)\"}', '2026-02-09 11:10:57.377'),
('cmlf2mwl100bsmhak9nsmmlza', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2mwka00bqmhakpuhrr912', '{\"name\":\"ලෝහ සැකසුම් අධ්යයනය — Metal Fabrication Studies\"}', '2026-02-09 11:10:59.701'),
('cmlf2myaw00bwmhakvaf624z6', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2mya500bumhakq2vg6u1x', '{\"name\":\"වගාභෝග නිෂ්පාදනය — Plantation Product Studies\"}', '2026-02-09 11:11:01.929'),
('cmlf2n08a00c0mhak5yfw4mqa', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2n07i00bymhak0icdr883', '{\"name\":\"වස්ත්ර හා ඇඟළුම් අධ්යයනය — Textile & Apparel Studies\"}', '2026-02-09 11:11:04.426'),
('cmlf2n2cv00c4mhakn1vw1thj', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2n2ce00c2mhakax01p4ui', '{\"name\":\"විදුලි හා ඉලෙක්ට්රොනික අධ්යයනය — Electrical & Electronic Studies\"}', '2026-02-09 11:11:07.183'),
('cmlf2n4wt00c8mhake7i0z2cz', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2n4w200c6mhakyd0hzbf8', '{\"name\":\"විදුලි, ඉලෙක්ට්රොනික හා තොරතුරු තාක්ෂණවේදය — Electrical, Electronic & Information Technology\"}', '2026-02-09 11:11:10.494'),
('cmlf2n97d00ccmhakfq93emln', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2n96m00camhaki58rgxs0', '{\"name\":\"විද්යාව — Science\"}', '2026-02-09 11:11:16.058'),
('cmlf2nb6600cgmhakjcxxj5o4', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2nb4x00cemhak8yjiog29', '{\"name\":\"විද්යුත් ලේඛනකරණය හා ලඝුලේඛනය — Electronic Typing & Shorthand\"}', '2026-02-09 11:11:18.607'),
('cmlf2nd4200ckmhakbvxv2rao', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2nd3a00cimhakcs1oe5c4', '{\"name\":\"විලාසිතා නිර්මාණකරණය — Fashion Designing\"}', '2026-02-09 11:11:21.122'),
('cmlf2nes700comhaka6b6loph', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2nerf00cmmhak07prxhkk', '{\"name\":\"ව්යවසායකත්ව අධ්යයනය — Entrepreneurship Studies\"}', '2026-02-09 11:11:23.288'),
('cmlf2ngqc00csmhak7aw0ce6w', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2ngpw00cqmhaky53mwl71', '{\"name\":\"ව්යාපාර අධ්යයනය — Business Studies\"}', '2026-02-09 11:11:25.812'),
('cmlf2nidf00cwmhaksp0or1am', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2nicn00cumhakkui2ymio', '{\"name\":\"ව්යාපාර සංඛ්යානය — Business Statistics\"}', '2026-02-09 11:11:27.940'),
('cmlf2nlel00d0mhakl7qntjye', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2nldt00cymhak49nwl2a2', '{\"name\":\"ව්යාපාර හා ගිණුම්කරණ අධ්යයනය — Business & Accountancy Studies\"}', '2026-02-09 11:11:31.869'),
('cmlf2nnc200d4mhak1093xtvp', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2nnba00d2mhak9a9ysj8z', '{\"name\":\"ශාරීරික අධ්යාපනය හා ක්රීඩා — Physical Education & Sports\"}', '2026-02-09 11:11:34.370'),
('cmlf2np5600d8mhak064271hk', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2np4c00d6mhakguprs1la', '{\"name\":\"සංගීතය — Music\"}', '2026-02-09 11:11:36.714'),
('cmlf2nqvr00dcmhakmvqznip7', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2nquj00damhak2ocqmez2', '{\"name\":\"සංචරණය හා ආගන්තුක සත්කාර — Tourism & Hospitality\"}', '2026-02-09 11:11:38.968'),
('cmlf2nstl00dgmhake62ac2r0', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2nst600demhak4mqlgmtg', '{\"name\":\"සත්ව සම්පත් නිෂ්පාදනය — Livestock Product Studies\"}', '2026-02-09 11:11:41.481'),
('cmlf2nuqs00dkmhakzqq5dvjn', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2nuqq00dimhak6qeng2zf', '{\"name\":\"සන්නිවේදනය හා මාධ්ය අධ්යයනය — Communication & Media Studies\"}', '2026-02-09 11:11:43.973'),
('cmlf2nwl500domhaknz0w7t1o', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2nwl200dmmhakx110nc8d', '{\"name\":\"සංයුක්ත ගණිතය — Combined Mathematics\"}', '2026-02-09 11:11:46.361'),
('cmlf2nydi00dsmhakuok6dl1y', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2nycp00dqmhaktcm0rzw2', '{\"name\":\"සංස්කෘත — Sanskrit\"}', '2026-02-09 11:11:48.678'),
('cmlf2o09b00dwmhakek3aac08', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2o07900dumhak9gu5hdd9', '{\"name\":\"සංස්කෘත (උ.පෙළ) — Sanskrit (A/L)\"}', '2026-02-09 11:11:51.120'),
('cmlf2o1yk00e0mhakvdklfln1', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2o1yi00dymhak5wp18da8', '{\"name\":\"සාමාන්ය ඉංග්රීසි — General English\"}', '2026-02-09 11:11:53.325'),
('cmlf2o3vg00e4mhakdgudqcia', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2o3ve00e2mhak53yt8yb0', '{\"name\":\"සාමාන්ය තොරතුරු තාක්ෂණය (GIT) — General Information Technology\"}', '2026-02-09 11:11:55.805'),
('cmlf2o5lg00e8mhak9kh3jlbo', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2o5lb00e6mhakkkt0cwrc', '{\"name\":\"සාහිත්ය රසාස්වාදය — Appreciation of Literary\"}', '2026-02-09 11:11:58.037'),
('cmlf2o7au00ecmhakfn1tu0x3', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2o79n00eamhak9uxoi2a9', '{\"name\":\"සාහිත්ය රසාස්වාදය (අරාබි) — Appreciation of Literary (Arabic)\"}', '2026-02-09 11:12:00.246'),
('cmlf2o93g00egmhakfw0cdp91', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2o93e00eemhakvns4luls', '{\"name\":\"සිවිල් තාක්ෂණවේදය — Civil Technology\"}', '2026-02-09 11:12:02.572'),
('cmlf2oaq700ekmhakudnwmh1i', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2oaq400eimhak9807kd62', '{\"name\":\"සිංහල (උ.පෙළ) — Sinhala (A/L)\"}', '2026-02-09 11:12:04.688'),
('cmlf2ocpu00eomhak7zxz7wbc', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2ocps00emmhakaep3hstn', '{\"name\":\"සෞඛ්ය හා ශාරීරික අධ්යාපනය — Health & Physical Education\"}', '2026-02-09 11:12:07.267'),
('cmlf2oec500esmhakbcw6xiqd', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2oec100eqmhaksokxpx8d', '{\"name\":\"හින්දු ධර්මය — Hinduism\"}', '2026-02-09 11:12:09.366'),
('cmlf2og4h00ewmhak2brz3rwq', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2og4e00eumhakp1rie3jf', '{\"name\":\"සෞඛ්ය හා සමාජ ආරක්ෂණය — Health & Social Care\"}', '2026-02-09 11:12:11.681'),
('cmlf2oi0q00f0mhakatjrapy1', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2oi0o00eymhakzolbwjbd', '{\"name\":\"හින්දු ශිෂ්ටාචාරය — Hindu Civilization\"}', '2026-02-09 11:12:14.138'),
('cmlf2ojts00f4mhakvi5vtd9j', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf2ojtp00f2mhakg11zhopd', '{\"name\":\"ළමා මනෝවිද්යාව හා ආරක්ෂණය — Child Psychology & Care\"}', '2026-02-09 11:12:16.480'),
('cmlf3htzn0003ppsk70kftc0n', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf3htzj0001ppskdsta1yk9', '{\"name\":\"හින්දි — Hindi\"}', '2026-02-09 11:35:02.675'),
('cmlf3i5w80007ppskwk7iwv9u', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_SUBJECT', 'Subject', 'cmlf3i5w40005ppskwwet1y0f', '{\"name\":\"bambu\"}', '2026-02-09 11:35:18.104'),
('cmlf47mll000appskc688vbhz', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'DELETE_SUBJECT', 'Subject', 'cmlf3i5w40005ppskwwet1y0f', '{}', '2026-02-09 11:55:06.154'),
('cmlf6hmoy000fppskoem30fpc', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'CREATE_TEACHER', 'Teacher', 'cmlf6hmor000dppskcd9j3vet', '{\"nic\":\"199612501230\",\"name\":\"W.R.M.K.G.U.B.U.B. Konage\"}', '2026-02-09 12:58:52.066'),
('cmlf6j6fh000jppskxs7n9mm5', 'cmlert9gm00006kl4owxyxwrq', 'admin@wilgamuwa.com', 'UPDATE_TEACHER', 'Teacher', 'cmlf6hmor000dppskcd9j3vet', '{\"nic\":\"199612501230\",\"name\":\"W.R.M.K.G.U.B.U.B. Konage\"}', '2026-02-09 13:00:04.301');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_02_18_163931_add_tic_form_fields_to_teacher_table', 1),
(5, '2026_02_18_164451_modify_gender_column_in_teacher_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `profileeditrequest`
--

CREATE TABLE `profileeditrequest` (
  `id` varchar(191) NOT NULL,
  `teacherId` varchar(191) NOT NULL,
  `schoolId` varchar(191) DEFAULT NULL,
  `fieldName` varchar(191) NOT NULL,
  `fieldLabel` varchar(191) NOT NULL,
  `currentValue` varchar(191) DEFAULT NULL,
  `requestedValue` varchar(191) DEFAULT NULL,
  `reason` varchar(191) DEFAULT NULL,
  `status` varchar(191) NOT NULL DEFAULT 'PENDING',
  `adminRemarks` varchar(191) DEFAULT NULL,
  `processedBy` varchar(191) DEFAULT NULL,
  `processedDate` datetime(3) DEFAULT NULL,
  `createdAt` datetime(3) NOT NULL DEFAULT current_timestamp(3),
  `updatedAt` datetime(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `salaryrequest`
--

CREATE TABLE `salaryrequest` (
  `id` varchar(191) NOT NULL,
  `teacherId` varchar(191) NOT NULL,
  `schoolId` varchar(191) DEFAULT NULL,
  `documentType` varchar(191) NOT NULL,
  `periodType` varchar(191) NOT NULL,
  `periodDetails` varchar(191) DEFAULT NULL,
  `status` varchar(191) NOT NULL DEFAULT 'PENDING',
  `remarks` varchar(191) DEFAULT NULL,
  `requestDate` datetime(3) NOT NULL DEFAULT current_timestamp(3),
  `processedDate` datetime(3) DEFAULT NULL,
  `processedBy` varchar(191) DEFAULT NULL,
  `createdAt` datetime(3) NOT NULL DEFAULT current_timestamp(3),
  `updatedAt` datetime(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `school`
--

CREATE TABLE `school` (
  `id` varchar(191) NOT NULL,
  `name` varchar(191) NOT NULL,
  `censusNo` varchar(191) DEFAULT NULL,
  `address` varchar(191) DEFAULT NULL,
  `contactNumber` varchar(191) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `schoolType` varchar(191) DEFAULT NULL,
  `eduZone` varchar(191) DEFAULT 'Wilgamuwa',
  `eduDivision` varchar(191) DEFAULT NULL,
  `gradeStart` int(11) NOT NULL DEFAULT 1,
  `gradeEnd` int(11) NOT NULL DEFAULT 13,
  `sanctionedPositions` int(11) NOT NULL DEFAULT 0,
  `principalName` varchar(191) DEFAULT NULL,
  `principalNic` varchar(191) DEFAULT NULL,
  `principalMobile` varchar(191) DEFAULT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT 1,
  `createdAt` datetime(3) NOT NULL DEFAULT current_timestamp(3),
  `updatedAt` datetime(3) NOT NULL,
  `category` varchar(191) DEFAULT NULL,
  `nameSinhala` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `school`
--

INSERT INTO `school` (`id`, `name`, `censusNo`, `address`, `contactNumber`, `email`, `schoolType`, `eduZone`, `eduDivision`, `gradeStart`, `gradeEnd`, `sanctionedPositions`, `principalName`, `principalNic`, `principalMobile`, `isActive`, `createdAt`, `updatedAt`, `category`, `nameSinhala`) VALUES
('cmleupurx000112ziobcpbmjw', 'Wilgamuwa Central College', '12345', 'Wilgamuwa, Matale', '0662255888', 'school@wilgamuwa.com', '1AB', 'Wilgamuwa', 'Wilgamuwa', 1, 13, 0, 'Mr. Principal', '198012345678', '0712345678', 1, '2026-02-09 07:29:20.398', '2026-02-09 08:56:37.288', NULL, NULL),
('cmlewleod00016g5w5rtq4tyj', 'Hathota Amuna Maha Vidyalaya', '04143', 'Hathota Amuna, Matale', '0770635363', NULL, 'Maha', 'Wilgamuwa', 'Laggala', 1, 13, 31, 'R.M. Palitha Rajasinghe', NULL, '0770635363', 1, '2026-02-09 08:21:52.142', '2026-02-11 00:34:44.000', NULL, NULL),
('cmlewlepl00046g5w3iuvlimk', 'Mahalakotuwa Maha Vidyalaya', '04152', 'Ilukkumbura, Matale', '0776331615', NULL, 'Maha', 'Wilgamuwa', 'Laggala', 1, 13, 22, 'P.G.Y.B. Dissanayake', NULL, '0776331615', 1, '2026-02-09 08:21:52.185', '2026-02-11 00:34:44.000', NULL, NULL),
('cmlewleq100076g5wbc47l2it', 'Minipura Vijaya Maha Vidyalaya', '04118', 'Dasgiriya, Hathota Amuna', '0714497932', NULL, 'Maha', 'Wilgamuwa', 'Laggala', 1, 13, 25, 'G.O.L. Alwis', NULL, '0714497932', 1, '2026-02-09 08:21:52.201', '2026-02-11 00:34:44.000', NULL, NULL),
('cmlewleqf000a6g5w6jlikbga', 'Pallegama Maha Vidyalaya', '04120', 'Laggala, Pallegama', '0715909339', NULL, 'Maha', 'Wilgamuwa', 'Laggala', 1, 13, 31, 'P.G. Ananda', NULL, '0715909339', 1, '2026-02-09 08:21:52.215', '2026-02-11 00:34:44.000', NULL, NULL),
('cmlewleqr000d6g5w4mly70ys', 'Ma Oya Junior School', '04349', 'Hathota Amuna', '0772490128', NULL, 'Junior', 'Wilgamuwa', 'Laggala', 1, 13, 16, 'R.A.D.W. Ranasinghe', NULL, '0772490128', 1, '2026-02-09 08:21:52.228', '2026-02-11 00:34:44.000', NULL, NULL),
('cmlewler3000g6g5wj7r66ybo', 'Agrabodhi Junior School', '04129', 'Hathota Amuna', '0729510720', NULL, 'Junior', 'Wilgamuwa', 'Laggala', 1, 13, 21, 'H.M.K.G.V.U.K. Bandara', NULL, '0729510720', 1, '2026-02-09 08:21:52.239', '2026-02-11 00:34:44.000', NULL, NULL),
('cmlewlers000j6g5wljj875v8', 'Puwakpitiya Dammantanna Junior School', '04153', 'Laggala, Puwakpitiya', '0767480760', NULL, 'Junior', 'Wilgamuwa', 'Laggala', 1, 13, 13, 'H.M.B.J. Chandrarathna Bandara', NULL, '0767480760', 1, '2026-02-09 08:21:52.264', '2026-02-11 00:34:44.000', NULL, NULL),
('cmlewles6000m6g5wyjsel5an', 'Ranamure Junior School', '04125', 'Laggala, Pallegama', '0764373436', NULL, 'Junior', 'Wilgamuwa', 'Laggala', 1, 13, 16, 'W.A.G. Gunasekara', NULL, '0764373436', 1, '2026-02-09 08:21:52.278', '2026-02-11 00:34:44.000', NULL, NULL),
('cmlewlesg000p6g5w4it9vjnf', 'Hathota Amuna Vidyartha Model Primary School', '04330', 'Hathota Amuna', '0764159006', NULL, 'Primary', 'Wilgamuwa', 'Laggala', 1, 13, 10, 'W.G.R.W. Kumari', NULL, '0764159006', 1, '2026-02-09 08:21:52.289', '2026-02-11 00:34:44.000', NULL, NULL),
('cmlewlet7000s6g5w4okz4try', 'Himbiliyakada Primary School', '04148', 'Handungamuwa, Matale', '0751884525', NULL, 'Primary', 'Wilgamuwa', 'Laggala', 1, 13, 5, 'P.G. Wasantha Kumara', NULL, '0751884525', 1, '2026-02-09 08:21:52.315', '2026-02-11 00:34:44.000', NULL, NULL),
('cmlewletl000v6g5w4tahsh62', 'Kiwulewadiya Primary School', '04130', 'Laggala, Pallegama', '0714385008', NULL, 'Primary', 'Wilgamuwa', 'Laggala', 1, 13, 7, 'R.G.S.S. Ekanayake', NULL, '0714385008', 1, '2026-02-09 08:21:52.329', '2026-02-11 00:34:44.000', NULL, NULL),
('cmlewletw000y6g5wk5gfihsq', 'Leloya Primary School', '04141', 'Hathota Amuna', '0701194332', NULL, 'Primary', 'Wilgamuwa', 'Laggala', 1, 13, 6, 'A.M.H.K. Agarathna', NULL, '0701194332', 1, '2026-02-09 08:21:52.341', '2026-02-11 00:34:44.000', NULL, NULL),
('cmlewleub00116g5w47yhq1zd', 'Narangamuwa Primary School', '04134', 'Laggala, Pallegama', '0716518258', NULL, 'Primary', 'Wilgamuwa', 'Laggala', 1, 13, 5, 'U.G.N.K. Manike', NULL, '0716518258', 1, '2026-02-09 08:21:52.355', '2026-02-11 00:34:44.000', NULL, NULL),
('cmlewleur00146g5wkmz0zaua', 'Rambukoluwa Primary School', '04139', 'Laggala, Pallegama', '0761239386', NULL, 'Primary', 'Wilgamuwa', 'Laggala', 1, 13, 5, 'M.G.S.H. Bandara', NULL, '0761239386', 1, '2026-02-09 08:21:52.371', '2026-02-11 00:34:44.000', NULL, NULL),
('cmlewlev200176g5w5374tm0t', 'Sulugune Primary School', '04315', 'Sulugune, Nugagolla', '0712880448', NULL, 'Primary', 'Wilgamuwa', 'Laggala', 1, 13, 7, 'E.M.R.L.B. Ekanayake', NULL, '0712880448', 1, '2026-02-09 08:21:52.382', '2026-02-11 00:34:44.000', NULL, NULL),
('cmlewlevi001a6g5wrzgp6oal', 'Nava Guruvela Primary School', '04348', 'Laggala, Pallegama', '0711888416', NULL, 'Primary', 'Wilgamuwa', 'Laggala', 1, 13, 6, 'A.M.I.G. Agarathna', NULL, '0711888416', 1, '2026-02-09 08:21:52.398', '2026-02-11 00:34:44.000', NULL, NULL),
('cmlewlevv001d6g5wpnzl66u3', 'Seewali Model Primary School', '04350', 'Kandepitawala, Ma Oya', '0773708548', NULL, 'Primary', 'Wilgamuwa', 'Laggala', 1, 13, 6, 'R.M.A.K. Rajasinghe', NULL, '0773708548', 1, '2026-02-09 08:21:52.411', '2026-02-11 00:34:44.000', NULL, NULL),
('cmlewlewj001g6g5wy39cittq', 'Naminioya Maha Vidyalaya', '04119', 'Hettipola, Wilgamuwa', '0717408033', NULL, 'Maha', 'Wilgamuwa', 'Wilgamuwa', 1, 13, 70, 'I.B.P.W.N.S. Bandaranayake', NULL, '0717408033', 1, '2026-02-09 08:21:52.435', '2026-02-11 00:34:44.000', NULL, NULL),
('cmlewlewv001j6g5w7xd5nyt5', 'Nugagolla Maha Vidyalaya', '04123', 'Nugagolla, Matale', '0702092005', NULL, 'Maha', 'Wilgamuwa', 'Wilgamuwa', 1, 13, 38, 'W.M.C. Weerasekara', NULL, '0702092005', 1, '2026-02-09 08:21:52.448', '2026-02-11 00:34:44.000', NULL, NULL),
('cmlewlex6001m6g5w9rzi6u4b', 'Maraka Maha Vidyalaya', '04121', 'Maraka, Matale', '0714427386', NULL, 'Maha', 'Wilgamuwa', 'Wilgamuwa', 1, 13, 39, 'Y.G. Wedathilaka', NULL, '0714427386', 1, '2026-02-09 08:21:52.459', '2026-02-11 00:34:44.000', NULL, NULL),
('cmlewlexh001p6g5wjnu9u9vd', 'Handungamuwa Maha Vidyalaya', '04122', 'Handungamuwa, Matale', '0714060341', NULL, 'Maha', 'Wilgamuwa', 'Wilgamuwa', 1, 13, 28, 'R.D.S. Siriwardhana', NULL, '0714060341', 1, '2026-02-09 08:21:52.470', '2026-02-11 00:34:44.000', NULL, NULL),
('cmlewlext001s6g5wobvimc0o', 'Bogahawewa Junior School', '04135', 'Devagiriya, Matale', '0775196436', NULL, 'Junior', 'Wilgamuwa', 'Wilgamuwa', 1, 13, 7, 'R.W.M.M.R. Kumarihamy', NULL, '0775196436', 1, '2026-02-09 08:21:52.481', '2026-02-11 00:34:44.000', NULL, NULL),
('cmlewley6001v6g5w6uojl4os', 'Kekalathenna Junior School', '04149', 'Naminigama, Perakanatta', '0717938663', NULL, 'Junior', 'Wilgamuwa', 'Wilgamuwa', 1, 13, 18, 'U.G.E.M. Nawaratne', NULL, '0717938663', 1, '2026-02-09 08:21:52.493', '2026-02-11 00:34:44.000', NULL, NULL),
('cmlewleyg001y6g5wfscmz346', 'Medakanda Junior School', '04144', 'Dunuvilapitiya', '0712548689', NULL, 'Junior', 'Wilgamuwa', 'Wilgamuwa', 1, 13, 20, 'N.G.W.M.A.B. Wijesekara', NULL, '0712548689', 1, '2026-02-09 08:21:52.504', '2026-02-11 00:34:44.000', NULL, NULL),
('cmlewleyq00216g5w8yue33ep', 'Weheragalayaya Junior School', '04124', 'Handungamuwa, Matale', '0714410196', NULL, 'Junior', 'Wilgamuwa', 'Wilgamuwa', 1, 13, 23, 'W.P.G.C.J. Wijesooriya', NULL, '0714410196', 1, '2026-02-09 08:21:52.515', '2026-02-11 00:34:44.000', NULL, NULL),
('cmlewlez100246g5w9tamvq66', 'Sonuththara Junior School', '04319', 'Sonuththara, Nugagolla', '0779592938', NULL, 'Junior', 'Wilgamuwa', 'Wilgamuwa', 1, 13, 22, 'G.G.K. Bandara', NULL, '0779592938', 1, '2026-02-09 08:21:52.525', '2026-02-11 00:34:44.000', NULL, NULL),
('cmlewlezb00276g5woeb8dwno', 'Guruvelayaya Junior School', '04126', 'Devagiriya, Matale', '0714730083', NULL, 'Junior', 'Wilgamuwa', 'Wilgamuwa', 1, 13, 5, 'A.G.A.B. Jayasinghe', NULL, '0714730083', 1, '2026-02-09 08:21:52.535', '2026-02-11 00:34:44.000', NULL, NULL),
('cmlewlezk002a6g5w0tw2ezbt', 'Nuwarayaya Primary School', '04145', 'Maraka, Matale', '0716512624', NULL, 'Primary', 'Wilgamuwa', 'Wilgamuwa', 1, 13, 5, 'Y.M.W.G. Aberathna Bandara', NULL, '0716512624', 1, '2026-02-09 08:21:52.544', '2026-02-11 00:34:44.000', NULL, NULL),
('cmlewlezt002d6g5wmnwa0q6o', 'Dharmapala Primary School', '04322', 'Goda Ulpatha, Wilgamuwa', '0712578881', NULL, 'Primary', 'Wilgamuwa', 'Wilgamuwa', 1, 13, 4, 'R.M.P.P. Kumara', NULL, '0712578881', 1, '2026-02-09 08:21:52.554', '2026-02-11 00:34:44.000', NULL, NULL),
('cmlewlf02002g6g5wm34g3tvp', 'Egodadevagiriya Primary School', '04325', 'Devagiriya, Matale', '0712922292', NULL, 'Primary', 'Wilgamuwa', 'Wilgamuwa', 1, 13, 3, 'A.M.J.J.N.K.S. Bandara', NULL, '0712922292', 1, '2026-02-09 08:21:52.563', '2026-02-11 00:34:44.000', NULL, NULL),
('cmlewlf0b002j6g5w8sd0b78f', 'Dharmapradeepa Primary School', '04342', 'Nugagolla, Matale', '0710656973', NULL, 'Primary', 'Wilgamuwa', 'Wilgamuwa', 1, 13, 12, 'A.M.M.K. Aththanayake', NULL, '0710656973', 1, '2026-02-09 08:21:52.571', '2026-02-11 00:34:44.000', NULL, NULL),
('cmlewlf0p002m6g5w169i1x4a', 'Dutugemunu Primary School', '04343', 'Maraka, Matale', '0703825312', NULL, 'Primary', 'Wilgamuwa', 'Wilgamuwa', 1, 13, 13, 'K.M.D.G. Dharmarathna', NULL, '0703825312', 1, '2026-02-09 08:21:52.585', '2026-02-11 00:34:44.000', NULL, NULL),
('cmlewlf14002p6g5wrcgdgmq8', 'Imaduwayaya Primary School', '04128', 'Perakanatta, Matale', '0715661663', NULL, 'Primary', 'Wilgamuwa', 'Wilgamuwa', 1, 13, 4, 'K.P.G.G. Jayathilaka', NULL, '0715661663', 1, '2026-02-09 08:21:52.601', '2026-02-11 00:34:44.000', NULL, NULL),
('cmlewlf1e002s6g5wni4nejdw', 'Kumbukandana Primary School', '04131', 'Maraka, Matale', '0706005585', NULL, 'Primary', 'Wilgamuwa', 'Wilgamuwa', 1, 13, 6, 'D.M.P.G. Bandaranayake', NULL, '0706005585', 1, '2026-02-09 08:21:52.610', '2026-02-11 00:34:44.000', NULL, NULL),
('cmlewlf1o002v6g5wm5rb4yo5', 'Lediyangala Primary School', '04140', 'Devagiriya, Matale', '0714471376', NULL, 'Primary', 'Wilgamuwa', 'Wilgamuwa', 1, 13, 5, 'A.V. Wijithanandana', NULL, '0714471376', 1, '2026-02-09 08:21:52.621', '2026-02-11 00:34:44.000', NULL, NULL),
('cmlewlf21002y6g5wl75rf244', 'Mahawathenna Primary School', '04137', 'Perakanatta, Matale', '0766061304', NULL, 'Primary', 'Wilgamuwa', 'Wilgamuwa', 1, 13, 8, 'K.M.A.P. Kumari', NULL, '0766061304', 1, '2026-02-09 08:21:52.633', '2026-02-11 00:34:44.000', NULL, NULL),
('cmlewlf2c00316g5w4292hfnb', 'Malgammana Primary School', '04323', 'Malgammana, Maraka', '0712344903', NULL, 'Primary', 'Wilgamuwa', 'Wilgamuwa', 1, 13, 5, 'M.G.N.B. Gunathunga', NULL, '0712344903', 1, '2026-02-09 08:21:52.645', '2026-02-11 00:34:44.000', NULL, NULL),
('cmlewlf2m00346g5wvuh7w5ij', 'Moragaha Ulpatha Primary School', '04138', 'Wilgamuwa, Matale', '0705011058', NULL, 'Primary', 'Wilgamuwa', 'Wilgamuwa', 1, 13, 8, 'W.P.G.B. Jayamaha', NULL, '0705011058', 1, '2026-02-09 08:21:52.654', '2026-02-11 00:34:44.000', NULL, NULL),
('cmlewlf2x00376g5wml4jn7hp', 'Naminioya Monitigopallawa Primary School', '04329', 'Hettipola, Wilgamuwa', '0717339853', NULL, 'Primary', 'Wilgamuwa', 'Wilgamuwa', 1, 13, 18, 'I.G. Ariyasinghe', NULL, '0717339853', 1, '2026-02-09 08:21:52.665', '2026-02-11 00:34:44.000', NULL, NULL),
('cmlewlf39003a6g5wcrwptekp', 'Palupitiya Primary School', '04321', 'Handungamuwa, Matale', '0785807574', NULL, 'Primary', 'Wilgamuwa', 'Wilgamuwa', 1, 13, 5, 'M.R.G. Chandresna', NULL, '0785807574', 1, '2026-02-09 08:21:52.677', '2026-02-11 00:34:44.000', NULL, NULL),
('cmlewlf3k003d6g5w16w0j1yw', 'Parakrama Primary School', '04318', 'Nugagolla, Matale', '0767658156', NULL, 'Primary', 'Wilgamuwa', 'Wilgamuwa', 1, 13, 6, 'U.G. Senaviratne Bandara', NULL, '0767658156', 1, '2026-02-09 08:21:52.688', '2026-02-11 00:34:44.000', NULL, NULL),
('cmlewlf3u003g6g5wnx4ccf7f', 'Pussellayaya Primary School', '04146', 'Handungamuwa, Matale', '0768874041', NULL, 'Primary', 'Wilgamuwa', 'Wilgamuwa', 1, 13, 4, 'D.M.R.U. Dissanayake', NULL, '0768874041', 1, '2026-02-09 08:21:52.699', '2026-02-11 00:34:44.000', NULL, NULL),
('cmlewlf43003j6g5wr3kwsmf5', 'Radunnawewa Primary School', '04147', 'Radunnawewa, Maraka', '0718726048', NULL, 'Primary', 'Wilgamuwa', 'Wilgamuwa', 1, 13, 6, 'K.G.M.A.K. Jayalath', NULL, '0718726048', 1, '2026-02-09 08:21:52.707', '2026-02-11 00:34:44.000', NULL, NULL),
('cmlewlf4c003m6g5whvuo0h15', 'Wilgamuwa Primary School', '04142', 'Wilgamuwa, Matale', '0726452838', NULL, 'Primary', 'Wilgamuwa', 'Wilgamuwa', 1, 13, 6, 'I.H.G.C.N. Samarajeewa', NULL, '0726452838', 1, '2026-02-09 08:21:52.716', '2026-02-11 00:34:44.000', NULL, NULL),
('cmlewlf4m003p6g5wgjnh38tr', 'Handungamuwa Primary School', '04345', 'Handungamuwa, Matale', '0763905902', NULL, 'Primary', 'Wilgamuwa', 'Wilgamuwa', 1, 13, 10, 'H.A.A. Kumara', NULL, '0763905902', 1, '2026-02-09 08:21:52.726', '2026-02-11 00:34:44.000', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `session`
--

CREATE TABLE `session` (
  `id` varchar(191) NOT NULL,
  `sessionToken` varchar(191) NOT NULL,
  `userId` varchar(191) NOT NULL,
  `expires` datetime(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `studentcount`
--

CREATE TABLE `studentcount` (
  `id` varchar(191) NOT NULL,
  `schoolId` varchar(191) NOT NULL,
  `grade` varchar(191) NOT NULL,
  `academicYear` varchar(191) NOT NULL,
  `maleCount` int(11) NOT NULL DEFAULT 0,
  `femaleCount` int(11) NOT NULL DEFAULT 0,
  `classCount` int(11) NOT NULL DEFAULT 0,
  `classes` varchar(191) DEFAULT NULL,
  `classDetails` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`classDetails`)),
  `createdAt` datetime(3) NOT NULL DEFAULT current_timestamp(3),
  `updatedAt` datetime(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `studentcount`
--

INSERT INTO `studentcount` (`id`, `schoolId`, `grade`, `academicYear`, `maleCount`, `femaleCount`, `classCount`, `classes`, `classDetails`, `createdAt`, `updatedAt`) VALUES
('01KJ5G6PR25XNDHXX9C7Y047AJ', 'cmleupurx000112ziobcpbmjw', 'Grade 1', '2026', 44, 32, 3, 'A, B, C', '[{\"name\":\"A\",\"maleCount\":15,\"femaleCount\":13},{\"name\":\"B\",\"maleCount\":6,\"femaleCount\":3},{\"name\":\"C\",\"maleCount\":23,\"femaleCount\":16}]', '2026-02-23 14:59:38.000', '2026-02-23 19:09:28.000'),
('01KJ5G6PSGFG8E7TPGE78NYCF1', 'cmleupurx000112ziobcpbmjw', 'Grade 2', '2026', 0, 0, 0, NULL, '[]', '2026-02-23 14:59:38.000', '2026-02-23 14:59:38.000'),
('01KJ5G6PSPQR6XXJBZRWJE2JPY', 'cmleupurx000112ziobcpbmjw', 'Grade 3', '2026', 0, 0, 0, NULL, '[]', '2026-02-23 14:59:38.000', '2026-02-23 14:59:38.000'),
('01KJ5G6PST7Q30BZAZEWEEX1NF', 'cmleupurx000112ziobcpbmjw', 'Grade 4', '2026', 0, 0, 0, NULL, '[]', '2026-02-23 14:59:38.000', '2026-02-23 14:59:38.000'),
('01KJ5G6PSWX3WAE0WTMJS2D52C', 'cmleupurx000112ziobcpbmjw', 'Grade 5', '2026', 0, 0, 0, NULL, '[]', '2026-02-23 14:59:38.000', '2026-02-23 14:59:38.000'),
('01KJ5G6PT0Y93PVQ3CV186GKGT', 'cmleupurx000112ziobcpbmjw', 'Grade 6', '2026', 0, 0, 0, NULL, '[]', '2026-02-23 14:59:38.000', '2026-02-23 14:59:38.000'),
('01KJ5G6PT2130X8J49W6V5QVX5', 'cmleupurx000112ziobcpbmjw', 'Grade 7', '2026', 0, 0, 0, NULL, '[]', '2026-02-23 14:59:38.000', '2026-02-23 14:59:38.000'),
('01KJ5G6PT5HGNJHS28451T1Z25', 'cmleupurx000112ziobcpbmjw', 'Grade 8', '2026', 0, 0, 0, NULL, '[]', '2026-02-23 14:59:38.000', '2026-02-23 14:59:38.000'),
('01KJ5G6PT9WA4DSZH58MZFK6NH', 'cmleupurx000112ziobcpbmjw', 'Grade 9', '2026', 0, 0, 0, NULL, '[]', '2026-02-23 14:59:38.000', '2026-02-23 14:59:38.000'),
('01KJ5G6PTBQFGTGD493JCWP8NF', 'cmleupurx000112ziobcpbmjw', 'Grade 10', '2026', 0, 0, 0, NULL, '[]', '2026-02-23 14:59:38.000', '2026-02-23 14:59:38.000'),
('01KJ5G6PTEQP73EGZFKQ9XXBDY', 'cmleupurx000112ziobcpbmjw', 'Grade 11', '2026', 0, 0, 0, NULL, '[]', '2026-02-23 14:59:38.000', '2026-02-23 14:59:38.000'),
('01KJ5G6PTH3TBXDVY0W3E5NG53', 'cmleupurx000112ziobcpbmjw', 'Grade 12', '2026', 0, 0, 0, NULL, '[]', '2026-02-23 14:59:38.000', '2026-02-23 14:59:38.000'),
('01KJ5G6PTMK0SPGJF5XFVNPTZM', 'cmleupurx000112ziobcpbmjw', 'Grade 13', '2026', 0, 0, 0, NULL, '[]', '2026-02-23 14:59:38.000', '2026-02-23 14:59:38.000');

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `id` varchar(191) NOT NULL,
  `name` varchar(191) NOT NULL,
  `category` varchar(191) DEFAULT NULL,
  `createdAt` datetime(3) NOT NULL DEFAULT current_timestamp(3),
  `nameSinhala` varchar(191) DEFAULT NULL,
  `updatedAt` datetime(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`id`, `name`, `category`, `createdAt`, `nameSinhala`, `updatedAt`) VALUES
('cmlf20zz70017mhak3y4z5suy', 'Interior Designing', 'Vocational', '2026-02-09 10:53:57.668', 'අභ්යන්තර නිර්මාණකරණය', '2026-02-25 05:32:04.000'),
('cmlf2124j001bmhak2twrmmhs', 'අලුමිනියම් සැකසුම් අධ්යයනය — Aluminum Fabrication Studies', 'Vocational', '2026-02-09 10:54:00.451', NULL, '2026-02-09 10:54:00.451'),
('cmlf213sy001fmhak2z758ryg', 'ආහාර සැකසුම් අධ්යයනය — Food Processing Studies', 'Vocational', '2026-02-09 10:54:02.627', NULL, '2026-02-09 10:54:02.627'),
('cmlf215ho001jmhaki4xz32a5', 'අරාබි — Arabic', 'Basket I', '2026-02-09 10:54:04.813', NULL, '2026-02-09 10:54:04.813'),
('cmlf21732001nmhakkwvaf2ax', 'ආගම — Religion', 'Secondary', '2026-02-09 10:54:06.879', NULL, '2026-02-09 10:54:06.879'),
('cmlf218um001rmhak7uj664ov', 'ආහාර හා තාක්ෂණවේදය — Food & Technology', 'A/L Technology', '2026-02-09 10:54:09.166', NULL, '2026-02-09 10:54:09.166'),
('cmlf21etc001vmhak9ahpb3cd', 'අරාබි (උ.පෙළ) — Arabic (A/L)', 'A/L Language', '2026-02-09 10:54:16.897', NULL, '2026-02-09 10:54:16.897'),
('cmlf21gpu001zmhakxgsfrm8b', 'ආර්ථික විද්යාව — Economics', 'A/L Arts', '2026-02-09 10:54:19.363', NULL, '2026-02-09 10:54:19.363'),
('cmlf21iwb0023mhakp282mp6o', 'ඉංග්රීසි — English', 'Secondary', '2026-02-09 10:54:22.187', NULL, '2026-02-09 10:54:22.187'),
('cmlf2g75r0027mhaks8z0x7ye', 'ඉංග්රීසි (උ.පෙළ) — English (A/L)', 'A/L Language', '2026-02-09 11:05:46.815', NULL, '2026-02-09 11:05:46.815'),
('cmlf2g8sw002bmhaka37wm24e', 'ඉංජිනේරු තාක්ෂණවේදය — Engineering Technology', 'A/L Technology', '2026-02-09 11:05:48.944', NULL, '2026-02-09 11:05:48.944'),
('cmlf2gadh002fmhaktltc62re', 'ඉතිහාසය — History', 'Secondary', '2026-02-09 11:05:50.982', NULL, '2026-02-09 11:05:50.982'),
('cmlf2gw22002jmhakg13c2tqr', 'ඉතිහාසය (ඉන්දියා / යුරෝපා / සමකාලීන ලෝක) — History (Indian / European / Contemporary World)', 'A/L Arts', '2026-02-09 11:06:19.083', NULL, '2026-02-09 11:06:19.083'),
('cmlf2gxok002nmhakfx6izasn', 'ඉදිකිරීම් අධ්යයනය — Construction Studies', 'Vocational', '2026-02-09 11:06:21.188', NULL, '2026-02-09 11:06:21.188'),
('cmlf2gz7j002rmhak1tvzi7ce', 'ඉවෙන්ට් කළමනාකරණය — Event Management', 'Vocational', '2026-02-09 11:06:23.167', NULL, '2026-02-09 11:06:23.167'),
('cmlf2h11v002vmhak7pj047x3', 'ඉස්ලාම් — Islam', 'A/L Religion', '2026-02-09 11:06:25.555', NULL, '2026-02-09 11:06:25.555'),
('cmlf2h2nm002zmhak453ey18z', 'ඉස්ලාම් ශිෂ්ටාචාරය — Islam Civilization', 'A/L Culture', '2026-02-09 11:06:27.635', NULL, '2026-02-09 11:06:27.635'),
('cmlf2h4g00033mhakdo4je42q', 'උද්යානය හා උද්භිද විද්යාව — Applied Horticultural Studies', 'Vocational', '2026-02-09 11:06:29.952', NULL, '2026-02-09 11:06:29.952'),
('cmlf2h6x10037mhak2argryc3', 'උසස් ගණිතය — Higher Mathematics / A/L Mathematics', 'A/L Science', '2026-02-09 11:06:33.157', NULL, '2026-02-09 11:06:33.157'),
('cmlf2h8yy003bmhak2bsev0ha', 'කලා / චිත්ර (උ.පෙළ) — Art (A/L)', 'A/L Language', '2026-02-09 11:06:35.818', NULL, '2026-02-09 11:06:35.818'),
('cmlf2haj1003fmhak2hka4u3k', 'කර්ණාටක සංගීතය — Karnatic Music', 'Basket II', '2026-02-09 11:06:37.837', NULL, '2026-02-09 11:06:37.837'),
('cmlf2hcjn003jmhakmfbxjw3d', 'කලා හා නිර්මාණකරණය — Art & Designing', 'Vocational', '2026-02-09 11:06:40.451', NULL, '2026-02-09 11:06:40.451'),
('cmlf2he6t003nmhak5i42f761', 'කර්ණාටක සංගීතය (උ.පෙළ) — Karnatic Music (A/L)', 'A/L Language', '2026-02-09 11:06:42.582', NULL, '2026-02-09 11:06:42.582'),
('cmlf2hftq003rmhakuy3eqwyn', 'කලා හා හස්තකලා — Art & Craft', 'Basket III', '2026-02-09 11:06:44.702', NULL, '2026-02-09 11:06:44.702'),
('cmlf2hrqc003vmhakf7cx6fxi', 'කෘෂි තාක්ෂණවේදය — Agricultural Technology', 'A/L Technology', '2026-02-09 11:07:00.132', NULL, '2026-02-09 11:07:00.132'),
('cmlf2hthl003zmhak9jum7yzf', 'කෘෂි විද්යාව — Agricultural Science', 'A/L Science', '2026-02-09 11:07:02.409', NULL, '2026-02-09 11:07:02.409'),
('cmlf2hv2s0043mhaknkct8p18', 'කෘෂි හා ආහාර තාක්ෂණය — Agriculture & Food Technology', 'Basket III', '2026-02-09 11:07:04.468', NULL, '2026-02-09 11:07:04.468'),
('cmlf2hwsx0047mhakovx4ljo9', 'කොරියානු — Korean', 'Basket I', '2026-02-09 11:07:06.706', NULL, '2026-02-09 11:07:06.706'),
('cmlf2hyzx004bmhakt61836td', 'ක්රිස්තියානි ධර්මය — Christianity', 'A/L Religion', '2026-02-09 11:07:09.549', NULL, '2026-02-09 11:07:09.549'),
('cmlf2i0ji004fmhakn1xl5lbe', 'ක්රිස්තියානි ශිෂ්ටාචාරය — Christian Civilization', 'A/L Culture', '2026-02-09 11:07:11.551', NULL, '2026-02-09 11:07:11.551'),
('cmlf2i2kc004jmhakop88t6n5', 'ගණිතය — Mathematics', 'Secondary', '2026-02-09 11:07:14.172', NULL, '2026-02-09 11:07:14.172'),
('cmlf2i48j004nmhake4co68hb', 'ගිණුම්කරණය — Accountancy', 'A/L Commerce', '2026-02-09 11:07:16.340', NULL, '2026-02-09 11:07:16.340'),
('cmlf2i5zi004rmhakh263i470', 'ගෘහ ආර්ථික විද්යාව — Home Economics', 'Basket III', '2026-02-09 11:07:18.606', NULL, '2026-02-09 11:07:18.606'),
('cmlf2i7md004vmhakyujscw2l', 'ගෘහ ආර්ථික විද්යාව (උ.පෙළ) — Home Economics (A/L)', 'A/L Language', '2026-02-09 11:07:20.726', NULL, '2026-02-09 11:07:20.726'),
('cmlf2i9t4004zmhakpwsknxxc', 'ග්රැෆික් නිර්මාණකරණය — Graphic Design', 'Vocational', '2026-02-09 11:07:23.560', NULL, '2026-02-09 11:07:23.560'),
('cmlf2ic660053mhakd5s4b6t7', 'ග්රීක හා රෝම ශිෂ්ටාචාරය — Greek & Roman Civilization', 'A/L Culture', '2026-02-09 11:07:26.622', NULL, '2026-02-09 11:07:26.622'),
('cmlf2idzw0057mhakurxllvgi', 'චිත්ර / කලා — Art', 'Basket II', '2026-02-09 11:07:28.988', NULL, '2026-02-09 11:07:28.988'),
('cmlf2ifce005bmhakrelgl17x', 'චීන — Chinese', 'Basket I', '2026-02-09 11:07:30.734', NULL, '2026-02-09 11:07:30.734'),
('cmlf2ihd4005fmhakixyzxqmf', 'චීන (උ.පෙළ) — Chinese (A/L)', 'A/L Language', '2026-02-09 11:07:33.353', NULL, '2026-02-09 11:07:33.353'),
('cmlf2ij0h005jmhak2sfbtzf6', 'ජපන් — Japanese', 'Basket I', '2026-02-09 11:07:35.490', NULL, '2026-02-09 11:07:35.490'),
('cmlf2ilwi005nmhakbyar4x5u', 'ජපන් (උ.පෙළ) — Japanese (A/L)', 'A/L Language', '2026-02-09 11:07:39.234', NULL, '2026-02-09 11:07:39.234'),
('cmlf2ingb005rmhak191ku5pv', 'ජර්මන් — German', 'Basket I', '2026-02-09 11:07:41.244', NULL, '2026-02-09 11:07:41.244'),
('cmlf2ip1h005vmhak4lx9sgmq', 'ජලජ ජෛව සම්පත් තාක්ෂණය — Aquatic Bioresources Technology', 'Basket III', '2026-02-09 11:07:43.301', NULL, '2026-02-09 11:07:43.301'),
('cmlf2irb4005zmhak1alxya4a', 'ජලජ සම්පත් අධ්යයනය — Aquatic Resource Studies', 'Vocational', '2026-02-09 11:07:46.241', NULL, '2026-02-09 11:07:46.241'),
('cmlf2isvv0063mhakib99zkwp', 'ජර්මන් (උ.පෙළ) — German (A/L)', 'A/L Language', '2026-02-09 11:07:48.284', NULL, '2026-02-09 11:07:48.284'),
('cmlf2iur10067mhakhrdq94y7', 'ජීව විද්යාව — Biology', 'A/L Science', '2026-02-09 11:07:50.702', NULL, '2026-02-09 11:07:50.702'),
('cmlf2iwc7006bmhakkz474dw1', 'ජෛව පද්ධති තාක්ෂණවේදය — Bio System Technology', 'A/L Technology', '2026-02-09 11:07:52.759', NULL, '2026-02-09 11:07:52.759'),
('cmlf2ixws006fmhakf27slsae', 'ජෛව සම්පත් තාක්ෂණවේදය — Bio Resources Technology', 'A/L Technology', '2026-02-09 11:07:54.796', NULL, '2026-02-09 11:07:54.796'),
('cmlf2izj6006jmhaky0ndxlqm', 'තර්ක ශාස්ත්රය හා විද්යාත්මක ක්රමය — Logic & Scientific Method', 'A/L Arts', '2026-02-09 11:07:56.898', NULL, '2026-02-09 11:07:56.898'),
('cmlf2j14j006nmhakyvnabq7j', 'තාක්ෂණවේදය සඳහා විද්යාව — Science for Technology', 'A/L Technology', '2026-02-09 11:07:58.963', NULL, '2026-02-09 11:07:58.963'),
('cmlf2j491006rmhakfcr06re3', 'තොරතුරු හා සන්නිවේදන තාක්ෂණය — Information & Communication Technology (ICT)', 'Basket III', '2026-02-09 11:08:03.013', NULL, '2026-02-09 11:08:03.013'),
('cmlf2j5v4006vmhak1qkv6gos', 'දෙමළ (උ.පෙළ) — Tamil (A/L)', 'A/L Language', '2026-02-09 11:08:05.105', NULL, '2026-02-09 11:08:05.105'),
('cmlf2j7h7006zmhak1t88u4z6', 'දෙවන භාෂාව ලෙස දෙමළ — Tamil as a Second Language', 'Basket I', '2026-02-09 11:08:07.196', NULL, '2026-02-09 11:08:07.196'),
('cmlf2j94g0073mhakzo6mwiso', 'දෙවන භාෂාව ලෙස සිංහල — Sinhala as a Second Language', 'Basket I', '2026-02-09 11:08:09.328', NULL, '2026-02-09 11:08:09.328'),
('cmlf2jbt40077mhakdjjj4r19', 'දේශපාලන විද්යාව — Political Science', 'A/L Arts', '2026-02-09 11:08:12.809', NULL, '2026-02-09 11:08:12.809'),
('cmlf2jdlf007bmhakvpfjgepc', 'නාට්ය හා රංග කලාව — Drama & Theatre', 'Basket II', '2026-02-09 11:08:15.123', NULL, '2026-02-09 11:08:15.123'),
('cmlf2jf88007fmhakr3byypab', 'නාට්ය හා රංග කලාව (උ.පෙළ) — Drama & Theatre (A/L)', 'A/L Language', '2026-02-09 11:08:17.241', NULL, '2026-02-09 11:08:17.241'),
('cmlf2jgt5007jmhak5cdlul7p', 'නැටුම් — Dancing', 'Basket II', '2026-02-09 11:08:19.290', NULL, '2026-02-09 11:08:19.290'),
('cmlf2jihk007nmhakg2zwv5gz', 'නැටුම් (දේශීය - උ.පෙළ) — Dancing (Traditional - A/L)', NULL, '2026-02-09 11:08:21.465', NULL, '2026-02-09 11:08:21.465'),
('cmlf2jk9o007rmhakifx350cy', 'නැටුම් (දේශීය) — Dancing (Traditional)', 'Basket II', '2026-02-09 11:08:23.773', NULL, '2026-02-09 11:08:23.773'),
('cmlf2jlpa007vmhakc1l878ru', 'නැටුම් (භරත - උ.පෙළ) — Dancing (Bharata - A/L)', NULL, '2026-02-09 11:08:25.630', NULL, '2026-02-09 11:08:25.630'),
('cmlf2jnc3007zmhaka7n4zp9k', 'නැටුම් (භරත) — Dancing (Bharata)', 'Basket II', '2026-02-09 11:08:27.748', NULL, '2026-02-09 11:08:27.748'),
('cmlf2jp470083mhak3nq5t4l2', 'නිර්මාණකරණය හා තාක්ෂණවේදය — Design & Technology', 'Basket III', '2026-02-09 11:08:30.055', NULL, '2026-02-09 11:08:30.055'),
('cmlf2jsic0087mhakfx34il7s', 'නිෂ්පාදන අධ්යයනය — Manufacturing', 'Vocational', '2026-02-09 11:08:34.452', NULL, '2026-02-09 11:08:34.452'),
('cmlf2ju4q008bmhakw2vaic3l', 'පරිගණක දෘඩාංග හා ජාලකරණය — Computer Hardware & Networking', 'Vocational', '2026-02-09 11:08:36.554', NULL, '2026-02-09 11:08:36.554'),
('cmlf2jwaa008fmhake29naaa1', 'පරිසර අධ්යයනය — Environmental Studies', 'Vocational', '2026-02-09 11:08:39.347', NULL, '2026-02-09 11:08:39.347'),
('cmlf2jypy008jmhakv2nfkz6x', 'පුරවැසි අධ්යයනය හා ප්රජා පාලනය — Civic Studies & Governance', 'Basket I', '2026-02-09 11:08:42.503', NULL, '2026-02-09 11:08:42.503'),
('cmlf2k0bd008nmhakm64ar065', 'පාලි (උ.පෙළ) — Pali (A/L)', 'A/L Language', '2026-02-09 11:08:44.570', NULL, '2026-02-09 11:08:44.570'),
('cmlf2k1yo008rmhakefoobzpi', 'පාලි — Pali', 'Basket I', '2026-02-09 11:08:46.705', NULL, '2026-02-09 11:08:46.705'),
('cmlf2k3xv008vmhakkkbwaqad', 'පෙරදිග සංගීතය — Eastern Music', 'Basket II', '2026-02-09 11:08:49.268', NULL, '2026-02-09 11:08:49.268'),
('cmlf2k5m5008zmhakvjurfpt1', 'පෙරදිග සංගීතය (උ.පෙළ) — Eastern Music (A/L)', 'A/L Language', '2026-02-09 11:08:51.437', NULL, '2026-02-09 11:08:51.437'),
('cmlf2k8570093mhak21hkiuyc', 'ප්රංශ — French', 'Basket I', '2026-02-09 11:08:54.716', NULL, '2026-02-09 11:08:54.716'),
('cmlf2k9x40097mhakwtv3s4q0', 'ප්රංශ (උ.පෙළ) — French (A/L)', 'A/L Language', '2026-02-09 11:08:57.017', NULL, '2026-02-09 11:08:57.017'),
('cmlf2kblp009bmhako5157dv8', 'ප්රාථමික ඉංග්රීසි — Primary English', NULL, '2026-02-09 11:08:59.197', NULL, '2026-02-09 11:08:59.197'),
('cmlf2l7rk009imhak184ogyab', 'ප්රාථමික පොදු — Primary General', NULL, '2026-02-09 11:09:40.880', NULL, '2026-02-09 11:09:40.880'),
('cmlf2lv47009mmhak6bmx2018', 'ප්රායෝගික හා තාක්ෂණික කුසලතා — Practical & Technical Skills', 'Basket III', '2026-02-09 11:10:11.144', NULL, '2026-02-09 11:10:11.144'),
('cmlf2lwt7009qmhaknos5jnpd', 'ප්රාසාංගික කලාව — Performing Arts', 'Vocational', '2026-02-09 11:10:13.340', NULL, '2026-02-09 11:10:13.340'),
('cmlf2lygn009umhakyt4urt1m', 'බටහිර සංගීතය — Western Music', 'Basket II', '2026-02-09 11:10:15.479', NULL, '2026-02-09 11:10:15.479'),
('cmlf2m13i009ymhakcidggymp', 'බටහිර සංගීතය (උ.පෙළ) — Western Music (A/L)', 'A/L Language', '2026-02-09 11:10:18.895', NULL, '2026-02-09 11:10:18.895'),
('cmlf2m2tf00a2mhak58875osp', 'බුද්ධ ධර්මය — Buddhism', 'A/L Religion', '2026-02-09 11:10:21.123', NULL, '2026-02-09 11:10:21.123'),
('cmlf2m4go00a6mhakzxms26pk', 'බෞද්ධ ශිෂ්ටාචාරය — Buddhist Civilization', 'A/L Culture', '2026-02-09 11:10:23.256', NULL, '2026-02-09 11:10:23.256'),
('cmlf2m6qp00aamhakewml3m4z', 'භූගෝල විද්යාව — Geography', 'Basket I', '2026-02-09 11:10:26.209', NULL, '2026-02-09 11:10:26.209'),
('cmlf2m8i400aemhak0t0gpq6x', 'භූගෝල විද්යාව (උ.පෙළ) — A/L Geography', 'A/L Language', '2026-02-09 11:10:28.493', NULL, '2026-02-09 11:10:28.493'),
('cmlf2mafx00aimhak2zlpm4pc', 'භූමි අලංකරණය — Landscaping', 'Vocational', '2026-02-09 11:10:31.005', NULL, '2026-02-09 11:10:31.005'),
('cmlf2mcfc00ammhak7pdub8ts', 'භෞතික විද්යාව — Physics', 'A/L Science', '2026-02-09 11:10:33.576', NULL, '2026-02-09 11:10:33.576'),
('cmlf2meax00aqmhakejx2merc', 'මව්බස (දෙමළ) — Mother Language (Tamil)', 'Secondary', '2026-02-09 11:10:36.009', NULL, '2026-02-09 11:10:36.009'),
('cmlf2mgv000aumhakiud6rshf', 'මව්බස (සිංහල) — Mother Language (Sinhala)', 'Secondary', '2026-02-09 11:10:39.324', NULL, '2026-02-09 11:10:39.324'),
('cmlf2mjvc00aymhak1l8x278y', 'මැලේ — Malay', NULL, '2026-02-09 11:10:43.224', NULL, '2026-02-09 11:10:43.224'),
('cmlf2mlqv00b2mhakqybb16fn', 'මෘදුකාංග සංවර්ධනය — Software Development', 'Vocational', '2026-02-09 11:10:45.655', NULL, '2026-02-09 11:10:45.655'),
('cmlf2mnij00b6mhak0em1hez1', 'මෝටර් රථ අධ්යයනය — Automobile Studies', 'Vocational', '2026-02-09 11:10:47.948', NULL, '2026-02-09 11:10:47.948'),
('cmlf2mpb700bamhakxejm64of', 'යාන්ත්රික තාක්ෂණවේදය — Mechanical Technology', 'A/L Technology', '2026-02-09 11:10:50.275', NULL, '2026-02-09 11:10:50.275'),
('cmlf2mr1s00bemhakwxr1ci1r', 'රසායන විද්යාව — Chemistry', 'A/L Science', '2026-02-09 11:10:52.528', NULL, '2026-02-09 11:10:52.528'),
('cmlf2mt1x00bimhakvhhjsj10', 'රුසියානු — Russian', 'Basket I', '2026-02-09 11:10:55.126', NULL, '2026-02-09 11:10:55.126'),
('cmlf2murp00bmmhak2nl0enex', 'රුසියානු (උ.පෙළ) — Russian (A/L)', 'A/L Language', '2026-02-09 11:10:57.349', NULL, '2026-02-09 11:10:57.349'),
('cmlf2mwka00bqmhakpuhrr912', 'ලෝහ සැකසුම් අධ්යයනය — Metal Fabrication Studies', 'Vocational', '2026-02-09 11:10:59.674', NULL, '2026-02-09 11:10:59.674'),
('cmlf2mya500bumhakq2vg6u1x', 'වගාභෝග නිෂ්පාදනය — Plantation Product Studies', 'Vocational', '2026-02-09 11:11:01.902', NULL, '2026-02-09 11:11:01.902'),
('cmlf2n07i00bymhak0icdr883', 'වස්ත්ර හා ඇඟළුම් අධ්යයනය — Textile & Apparel Studies', 'Vocational', '2026-02-09 11:11:04.399', NULL, '2026-02-09 11:11:04.399'),
('cmlf2n2ce00c2mhakax01p4ui', 'විදුලි හා ඉලෙක්ට්රොනික අධ්යයනය — Electrical & Electronic Studies', 'Vocational', '2026-02-09 11:11:07.166', NULL, '2026-02-09 11:11:07.166'),
('cmlf2n4w200c6mhakyd0hzbf8', 'විදුලි, ඉලෙක්ට්රොනික හා තොරතුරු තාක්ෂණවේදය — Electrical, Electronic & Information Technology', 'A/L Technology', '2026-02-09 11:11:10.466', NULL, '2026-02-09 11:11:10.466'),
('cmlf2n96m00camhaki58rgxs0', 'විද්යාව — Science', 'Secondary', '2026-02-09 11:11:16.030', NULL, '2026-02-09 11:11:16.030'),
('cmlf2nb4x00cemhak8yjiog29', 'විද්යුත් ලේඛනකරණය හා ලඝුලේඛනය — Electronic Typing & Shorthand', 'Basket III', '2026-02-09 11:11:18.561', NULL, '2026-02-09 11:11:18.561'),
('cmlf2nd3a00cimhakcs1oe5c4', 'විලාසිතා නිර්මාණකරණය — Fashion Designing', 'Vocational', '2026-02-09 11:11:21.095', NULL, '2026-02-09 11:11:21.095'),
('cmlf2nerf00cmmhak07prxhkk', 'ව්යවසායකත්ව අධ්යයනය — Entrepreneurship Studies', 'Basket I', '2026-02-09 11:11:23.259', NULL, '2026-02-09 11:11:23.259'),
('cmlf2ngpw00cqmhaky53mwl71', 'ව්යාපාර අධ්යයනය — Business Studies', 'A/L Commerce', '2026-02-09 11:11:25.796', NULL, '2026-02-09 11:11:25.796'),
('cmlf2nicn00cumhakkui2ymio', 'ව්යාපාර සංඛ්යානය — Business Statistics', 'A/L Commerce', '2026-02-09 11:11:27.912', NULL, '2026-02-09 11:11:27.912'),
('cmlf2nldt00cymhak49nwl2a2', 'ව්යාපාර හා ගිණුම්කරණ අධ්යයනය — Business & Accountancy Studies', 'Basket I', '2026-02-09 11:11:31.842', NULL, '2026-02-09 11:11:31.842'),
('cmlf2nnba00d2mhak9a9ysj8z', 'ශාරීරික අධ්යාපනය හා ක්රීඩා — Physical Education & Sports', 'Vocational', '2026-02-09 11:11:34.342', NULL, '2026-02-09 11:11:34.342'),
('cmlf2np4c00d6mhakguprs1la', 'සංගීතය — Music', 'Basket II', '2026-02-09 11:11:36.684', NULL, '2026-02-09 11:11:36.684'),
('cmlf2nquj00damhak2ocqmez2', 'සංචරණය හා ආගන්තුක සත්කාර — Tourism & Hospitality', 'Vocational', '2026-02-09 11:11:38.923', NULL, '2026-02-09 11:11:38.923'),
('cmlf2nst600demhak4mqlgmtg', 'සත්ව සම්පත් නිෂ්පාදනය — Livestock Product Studies', 'Vocational', '2026-02-09 11:11:41.467', NULL, '2026-02-09 11:11:41.467'),
('cmlf2nuqq00dimhak6qeng2zf', 'සන්නිවේදනය හා මාධ්ය අධ්යයනය — Communication & Media Studies', 'Basket III', '2026-02-09 11:11:43.970', NULL, '2026-02-09 11:11:43.970'),
('cmlf2nwl200dmmhakx110nc8d', 'සංයුක්ත ගණිතය — Combined Mathematics', 'A/L Science', '2026-02-09 11:11:46.358', NULL, '2026-02-09 11:11:46.358'),
('cmlf2nycp00dqmhaktcm0rzw2', 'සංස්කෘත — Sanskrit', 'Basket I', '2026-02-09 11:11:48.649', NULL, '2026-02-09 11:11:48.649'),
('cmlf2o07900dumhak9gu5hdd9', 'සංස්කෘත (උ.පෙළ) — Sanskrit (A/L)', 'A/L Language', '2026-02-09 11:11:51.046', NULL, '2026-02-09 11:11:51.046'),
('cmlf2o1yi00dymhak5wp18da8', 'සාමාන්ය ඉංග්රීසි — General English', 'A/L Common', '2026-02-09 11:11:53.322', NULL, '2026-02-09 11:11:53.322'),
('cmlf2o3ve00e2mhak53yt8yb0', 'සාමාන්ය තොරතුරු තාක්ෂණය (GIT) — General Information Technology', 'A/L Common', '2026-02-09 11:11:55.802', NULL, '2026-02-09 11:11:55.802'),
('cmlf2o5lb00e6mhakkkt0cwrc', 'සාහිත්ය රසාස්වාදය — Appreciation of Literary', 'Basket II', '2026-02-09 11:11:58.031', NULL, '2026-02-09 11:11:58.031'),
('cmlf2o79n00eamhak9uxoi2a9', 'සාහිත්ය රසාස්වාදය (අරාබි) — Appreciation of Literary (Arabic)', 'Basket II', '2026-02-09 11:12:00.203', NULL, '2026-02-09 11:12:00.203'),
('cmlf2o93e00eemhakvns4luls', 'සිවිල් තාක්ෂණවේදය — Civil Technology', 'A/L Technology', '2026-02-09 11:12:02.570', NULL, '2026-02-09 11:12:02.570'),
('cmlf2oaq400eimhak9807kd62', 'සිංහල (උ.පෙළ) — Sinhala (A/L)', 'A/L Language', '2026-02-09 11:12:04.685', NULL, '2026-02-09 11:12:04.685'),
('cmlf2ocps00emmhakaep3hstn', 'සෞඛ්ය හා ශාරීරික අධ්යාපනය — Health & Physical Education', 'Basket III', '2026-02-09 11:12:07.264', NULL, '2026-02-09 11:12:07.264'),
('cmlf2oec100eqmhaksokxpx8d', 'හින්දු ධර්මය — Hinduism', 'A/L Religion', '2026-02-09 11:12:09.361', NULL, '2026-02-09 11:12:09.361'),
('cmlf2og4e00eumhakp1rie3jf', 'සෞඛ්ය හා සමාජ ආරක්ෂණය — Health & Social Care', 'Vocational', '2026-02-09 11:12:11.679', NULL, '2026-02-09 11:12:11.679'),
('cmlf2oi0o00eymhakzolbwjbd', 'හින්දු ශිෂ්ටාචාරය — Hindu Civilization', 'A/L Culture', '2026-02-09 11:12:14.136', NULL, '2026-02-09 11:12:14.136'),
('cmlf2ojtp00f2mhakg11zhopd', 'ළමා මනෝවිද්යාව හා ආරක්ෂණය — Child Psychology & Care', 'Vocational', '2026-02-09 11:12:16.477', NULL, '2026-02-09 11:12:16.477'),
('cmlf3htzj0001ppskdsta1yk9', 'හින්දි — Hindi', 'Basket I', '2026-02-09 11:35:02.671', NULL, '2026-02-09 11:35:02.671');

-- --------------------------------------------------------

--
-- Table structure for table `subjectstatistic`
--

CREATE TABLE `subjectstatistic` (
  `id` varchar(191) NOT NULL,
  `schoolId` varchar(191) NOT NULL,
  `subject` varchar(191) NOT NULL,
  `grade` int(11) NOT NULL,
  `gender` varchar(191) NOT NULL,
  `medium` varchar(191) NOT NULL,
  `studentCount` int(11) NOT NULL,
  `academicYear` varchar(191) NOT NULL,
  `createdAt` datetime(3) NOT NULL DEFAULT current_timestamp(3),
  `updatedAt` datetime(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `teacher`
--

CREATE TABLE `teacher` (
  `id` varchar(191) NOT NULL,
  `schoolId` varchar(191) DEFAULT NULL,
  `nic` varchar(191) NOT NULL,
  `nameWithInitials` varchar(191) NOT NULL,
  `fullName` varchar(191) NOT NULL,
  `dateOfBirth` datetime(3) DEFAULT NULL,
  `gender` varchar(50) DEFAULT NULL,
  `address` varchar(191) DEFAULT NULL,
  `phone` varchar(191) DEFAULT NULL,
  `whatsapp` varchar(191) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `maritalStatus` enum('Married','Unmarried') DEFAULT NULL,
  `spouseIsTeacherInWilgamuwa` tinyint(1) NOT NULL DEFAULT 0,
  `spouseNameWithInitials` varchar(191) DEFAULT NULL,
  `spouseNic` varchar(191) DEFAULT NULL,
  `spouseSchoolId` varchar(191) DEFAULT NULL,
  `appointmentDate` datetime(3) DEFAULT NULL,
  `appointmentType` varchar(191) DEFAULT NULL,
  `appointmentMedium` varchar(191) DEFAULT NULL,
  `serviceGrade` varchar(191) DEFAULT NULL,
  `currentPosition` varchar(191) DEFAULT NULL,
  `joinedCurrentSchoolDate` datetime(3) DEFAULT NULL,
  `fileNumber` varchar(191) DEFAULT NULL,
  `salaryNumber` varchar(191) DEFAULT NULL,
  `appointmentSubject` varchar(191) DEFAULT NULL,
  `trainingStatus` varchar(191) DEFAULT NULL,
  `natureOfAppointment` varchar(191) DEFAULT NULL,
  `salaryPayingSchool` varchar(191) DEFAULT NULL,
  `undergraduateDegrees` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`undergraduateDegrees`)),
  `undergraduateDegreesOther` varchar(191) DEFAULT NULL,
  `postgraduateDiplomas` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`postgraduateDiplomas`)),
  `postgraduateDiplomasOther` varchar(191) DEFAULT NULL,
  `postgraduateDegrees` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`postgraduateDegrees`)),
  `postgraduateDegreesOther` varchar(191) DEFAULT NULL,
  `additionalTeachingQualifications` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`additionalTeachingQualifications`)),
  `pastServiceDetails` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`pastServiceDetails`)),
  `appointmentLetterUrl` varchar(191) DEFAULT NULL,
  `createdByUserId` varchar(191) DEFAULT NULL,
  `updatedByUserId` varchar(191) DEFAULT NULL,
  `createdAt` datetime(3) NOT NULL DEFAULT current_timestamp(3),
  `updatedAt` datetime(3) NOT NULL,
  `isLockedBySchool` tinyint(1) NOT NULL DEFAULT 0,
  `isLockedByZonal` tinyint(1) NOT NULL DEFAULT 0,
  `serviceGradeAppointedDate` datetime(3) DEFAULT NULL,
  `userId` varchar(191) DEFAULT NULL,
  `positionStartDate` date DEFAULT NULL,
  `teacherStartDate` date DEFAULT NULL,
  `teachingDetails` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`teachingDetails`)),
  `otherTeachableSubjects` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`otherTeachableSubjects`)),
  `principalRecommendationDate` date DEFAULT NULL,
  `officialRecommendationDate` date DEFAULT NULL,
  `spouseSchool` varchar(255) DEFAULT NULL,
  `spouseIsTeacher` tinyint(1) NOT NULL DEFAULT 0,
  `spouseName` varchar(255) DEFAULT NULL,
  `civilStatus` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `teacher`
--

INSERT INTO `teacher` (`id`, `schoolId`, `nic`, `nameWithInitials`, `fullName`, `dateOfBirth`, `gender`, `address`, `phone`, `whatsapp`, `email`, `maritalStatus`, `spouseIsTeacherInWilgamuwa`, `spouseNameWithInitials`, `spouseNic`, `spouseSchoolId`, `appointmentDate`, `appointmentType`, `appointmentMedium`, `serviceGrade`, `currentPosition`, `joinedCurrentSchoolDate`, `fileNumber`, `salaryNumber`, `appointmentSubject`, `trainingStatus`, `natureOfAppointment`, `salaryPayingSchool`, `undergraduateDegrees`, `undergraduateDegreesOther`, `postgraduateDiplomas`, `postgraduateDiplomasOther`, `postgraduateDegrees`, `postgraduateDegreesOther`, `additionalTeachingQualifications`, `pastServiceDetails`, `appointmentLetterUrl`, `createdByUserId`, `updatedByUserId`, `createdAt`, `updatedAt`, `isLockedBySchool`, `isLockedByZonal`, `serviceGradeAppointedDate`, `userId`, `positionStartDate`, `teacherStartDate`, `teachingDetails`, `otherTeachableSubjects`, `principalRecommendationDate`, `officialRecommendationDate`, `spouseSchool`, `spouseIsTeacher`, `spouseName`, `civilStatus`) VALUES
('cmlexzajo0002mhaka2ieiaji', 'cmlewler3000g6g5wj7r66ybo', '177062763958', 'H.M.K.G.V.U.K. Bandara', 'H.M.K.G.V.U.K. Bandara', '1987-02-12 00:00:00.000', 'Male', NULL, '0729510720', NULL, NULL, NULL, 0, NULL, NULL, NULL, '2022-01-19 00:00:00.000', 'Principal', NULL, 'SLPS Grade III', 'Principal', '2025-02-12 00:00:00.000', NULL, NULL, NULL, NULL, NULL, NULL, '[]', NULL, '[]', NULL, '[]', NULL, '[]', '[]', NULL, NULL, 'cmlert9gm00006kl4owxyxwrq', '2026-02-09 09:00:39.588', '2026-02-09 09:46:02.814', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL),
('cmlf6hmor000dppskcd9j3vet', 'cmlewleod00016g5w5rtq4tyj', '199612501230', 'W.R.M.K.G.U.B.U.B. Konage', 'Wijaya Rathnayaka Mudiyanselage Konage Gedara Bhathiya Udara Bandara Konage', '1996-05-04 00:00:00.000', 'Male', '35/2, Nilwala, Ududeniya, Nalanda', '0718793622', '0718793622', 'bhathiyabub@gmail.com', 'Married', 0, NULL, NULL, NULL, '2022-05-04 00:00:00.000', 'විද්‍යාපීඨ', 'ඉංග්‍රීසි', '3-I (අ)', 'ගුරු', '2022-05-04 00:00:00.000', NULL, NULL, 'තොරතුරු තාක්ෂණය', 'පුහුණු', 'ස්ථිර', NULL, '[\"BSc (Science)\"]', NULL, '[]', NULL, '[]', NULL, '[]', '[]', NULL, 'cmlert9gm00006kl4owxyxwrq', 'cmlert9gm00006kl4owxyxwrq', '2026-02-09 12:58:52.059', '2026-02-18 16:46:05.000', 0, 0, '2022-05-04 00:00:00.000', NULL, '2022-05-04', '2022-05-04', '[{\"subject1\":null,\"grade1\":null,\"periods1\":null},{\"subject1\":null,\"grade1\":null,\"periods1\":null},{\"subject1\":null,\"grade1\":null,\"periods1\":null}]', '[\"\\u0d9c\\u0dab\\u0dd2\\u0dad\\u0dba\"]', '2026-02-18', '2026-02-18', NULL, 0, NULL, 'විවාහක');

-- --------------------------------------------------------

--
-- Table structure for table `teachersubject`
--

CREATE TABLE `teachersubject` (
  `id` varchar(191) NOT NULL,
  `teacherId` varchar(191) NOT NULL,
  `subjectId` varchar(191) NOT NULL,
  `createdAt` datetime(3) NOT NULL DEFAULT current_timestamp(3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `teachersubject`
--

INSERT INTO `teachersubject` (`id`, `teacherId`, `subjectId`, `createdAt`) VALUES
('cmlf6j6fm000kppskybe6mvjs', 'cmlf6hmor000dppskcd9j3vet', 'cmlf2i2kc004jmhakop88t6n5', '2026-02-09 13:00:04.306');

-- --------------------------------------------------------

--
-- Table structure for table `transferrequest`
--

CREATE TABLE `transferrequest` (
  `id` varchar(191) NOT NULL,
  `teacherId` varchar(191) NOT NULL,
  `fromSchoolId` varchar(191) DEFAULT NULL,
  `toSchoolId` varchar(191) DEFAULT NULL,
  `reason` varchar(191) DEFAULT NULL,
  `status` varchar(191) NOT NULL DEFAULT 'PENDING',
  `createdAt` datetime(3) NOT NULL DEFAULT current_timestamp(3),
  `updatedAt` datetime(3) NOT NULL,
  `decidedAt` datetime(3) DEFAULT NULL,
  `decidedBy` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` varchar(191) NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `emailVerified` datetime(3) DEFAULT NULL,
  `image` varchar(191) DEFAULT NULL,
  `password` varchar(191) DEFAULT NULL,
  `role` enum('ZONAL_ADMIN','SCHOOL_ADMIN','TEACHER') NOT NULL DEFAULT 'TEACHER',
  `schoolId` varchar(191) DEFAULT NULL,
  `createdAt` datetime(3) NOT NULL DEFAULT current_timestamp(3),
  `updatedAt` datetime(3) NOT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `email`, `emailVerified`, `image`, `password`, `role`, `schoolId`, `createdAt`, `updatedAt`, `isActive`) VALUES
('cmlert9gm00006kl4owxyxwrq', 'Zonal Admin', 'admin@wilgamuwa.com', NULL, NULL, '$2y$12$xJRI8euaGmI81xo/Yw06J.FP4P86QcSFoI332OeV7VTRA.oxmAQUa', 'ZONAL_ADMIN', NULL, '2026-02-09 06:08:00.550', '2026-02-22 07:04:56.000', 1),
('cmleupuwj000312zi7vedpiop', 'School Admin', 'school@wilgamuwa.com', NULL, NULL, '$2y$12$W6Dinbtv.9WKp9zHFZy8SeVYxsxi0otK51FrIhtko/QHd.4SPXIcy', 'SCHOOL_ADMIN', 'cmleupurx000112ziobcpbmjw', '2026-02-09 07:29:20.545', '2026-02-22 16:47:01.000', 1),
('cmlewlepc00036g5wzek93not', 'R.M. Palitha Rajasinghe', 'principal.04143@wilgamuwa.com', NULL, NULL, '$2y$12$keXg7ktWScJI2zNjGT70v.PFpKHJbcGl35XO0NTctFfiFbxSXQ8im', 'SCHOOL_ADMIN', 'cmlewleod00016g5w5rtq4tyj', '2026-02-09 08:21:52.176', '2026-02-22 07:06:03.000', 1),
('cmlewlepo00066g5w158mrhd6', 'P.G.Y.B. Dissanayake', 'principal.04152@wilgamuwa.com', NULL, NULL, '$2y$12$a/TNrG76sKBDPaNFWt8GhOgS0Q.v1RpLlQElnwAGV5nmixemL/qNK', 'SCHOOL_ADMIN', 'cmlewlepl00046g5w3iuvlimk', '2026-02-09 08:21:52.189', '2026-02-22 07:06:03.000', 1),
('cmlewleq900096g5wf5vwnghd', 'G.O.L. Alwis', 'principal.04118@wilgamuwa.com', NULL, NULL, '$2y$12$xw7Xi5PmsfJhvBU5nrpWkedg4zXNHx8HeG066yHXP4tyuMO/wzEEG', 'SCHOOL_ADMIN', 'cmlewleq100076g5wbc47l2it', '2026-02-09 08:21:52.210', '2026-02-22 07:06:04.000', 1),
('cmlewleqk000c6g5ww90cpo85', 'P.G. Ananda', 'principal.04120@wilgamuwa.com', NULL, NULL, '$2y$12$SiLd25xG4qyHHCaX42jGH.28TPiNCwlMkSd2DhBWYiQyGMarxHoT.', 'SCHOOL_ADMIN', 'cmlewleqf000a6g5w6jlikbga', '2026-02-09 08:21:52.220', '2026-02-22 07:06:04.000', 1),
('cmlewleqw000f6g5wb2dmfxnh', 'R.A.D.W. Ranasinghe', 'principal.04349@wilgamuwa.com', NULL, NULL, '$2y$12$LVqO5TfnyyGE/gTsc5BDcu51fuB.EE1je/bqpt/pxW3SQSkJTWQKa', 'SCHOOL_ADMIN', 'cmlewleqr000d6g5w4mly70ys', '2026-02-09 08:21:52.232', '2026-02-22 07:06:04.000', 1),
('cmlewlerd000i6g5wk4ebs227', 'H.M.K.G.V.U.K. Bandara', 'principal.04129@wilgamuwa.com', NULL, NULL, '$2y$12$GGJZOMcU/G.0eFfCidZCf.XlKD9f4MkjNKJt17jZbsAxVG8/5dgee', 'SCHOOL_ADMIN', 'cmlewler3000g6g5wj7r66ybo', '2026-02-09 08:21:52.249', '2026-02-22 07:06:05.000', 1),
('cmlewlerz000l6g5w5iplf85s', 'H.M.B.J. Chandrarathna Bandara', 'principal.04153@wilgamuwa.com', NULL, NULL, '$2y$12$pejt0Cnq5HBwVaG3r6m6seqzVbf/lZytKEX5u47WVtBBxDBXZgqri', 'SCHOOL_ADMIN', 'cmlewlers000j6g5wljj875v8', '2026-02-09 08:21:52.271', '2026-02-22 07:06:05.000', 1),
('cmlewlesb000o6g5wplh99jsj', 'W.A.G. Gunasekara', 'principal.04125@wilgamuwa.com', NULL, NULL, '$2y$12$4msDTRyoznuh/zmtpmqLNe9HMuh5V3pR5Qh6eo2crx2XDhnR7uEiW', 'SCHOOL_ADMIN', 'cmlewles6000m6g5wyjsel5an', '2026-02-09 08:21:52.283', '2026-02-22 07:06:05.000', 1),
('cmlewlesx000r6g5whizl9pw5', 'W.G.R.W. Kumari', 'principal.04330@wilgamuwa.com', NULL, NULL, '$2y$12$yvVLTdBdmhwxbvaRoqBlBuaQFKw/Ch/qanFqoVNl52Yzj1Qx1JjcS', 'SCHOOL_ADMIN', 'cmlewlesg000p6g5w4it9vjnf', '2026-02-09 08:21:52.305', '2026-02-22 07:06:06.000', 1),
('cmlewlete000u6g5wr7ni26fx', 'P.G. Wasantha Kumara', 'principal.04148@wilgamuwa.com', NULL, NULL, '$2y$12$fvOjowImipLgciZp/9C9/uF8GrEtDJ3vOUyKXk5CFnOkSs993yu3a', 'SCHOOL_ADMIN', 'cmlewlet7000s6g5w4okz4try', '2026-02-09 08:21:52.322', '2026-02-22 07:06:06.000', 1),
('cmlewletq000x6g5w2trp3r64', 'R.G.S.S. Ekanayake', 'principal.04130@wilgamuwa.com', NULL, NULL, '$2y$12$fIyylTJdFJyd/sprONZDOOCJvYNrTYAZUP748vuHg81GG4yVHnlJa', 'SCHOOL_ADMIN', 'cmlewletl000v6g5w4tahsh62', '2026-02-09 08:21:52.334', '2026-02-22 07:06:06.000', 1),
('cmlewleu500106g5w6yuri2b5', 'A.M.H.K. Agarathna', 'principal.04141@wilgamuwa.com', NULL, NULL, '$2y$12$z1Y56WNN0dctI1RY5pzPXeRgOQ.NbzSipd3lOnI.Uq3BG6Q56/hJS', 'SCHOOL_ADMIN', 'cmlewletw000y6g5wk5gfihsq', '2026-02-09 08:21:52.349', '2026-02-22 07:06:07.000', 1),
('cmlewleuk00136g5w2cp9ji0u', 'U.G.N.K. Manike', 'principal.04134@wilgamuwa.com', NULL, NULL, '$2y$12$yjxIPDWu86ytQBQQwutI..jJkffXI38yl1h2pxYJmuVX44YNBlLLy', 'SCHOOL_ADMIN', 'cmlewleub00116g5w47yhq1zd', '2026-02-09 08:21:52.364', '2026-02-22 07:06:07.000', 1),
('cmlewleux00166g5wjyce92am', 'M.G.S.H. Bandara', 'principal.04139@wilgamuwa.com', NULL, NULL, '$2y$12$d856ApD4hu6y9Bj/TWtI/eEzc9BqFneoF8bWpEQ/kdzwG/wYb5n/e', 'SCHOOL_ADMIN', 'cmlewleur00146g5wkmz0zaua', '2026-02-09 08:21:52.377', '2026-02-22 07:06:07.000', 1),
('cmlewlev700196g5w9umuijne', 'E.M.R.L.B. Ekanayake', 'principal.04315@wilgamuwa.com', NULL, NULL, '$2y$12$rQCCyxdu3UwL/dd8/P2wOu7.7WTAjzdtjcGEMNLC8Tcvne7AUA/Ue', 'SCHOOL_ADMIN', 'cmlewlev200176g5w5374tm0t', '2026-02-09 08:21:52.387', '2026-02-22 07:06:08.000', 1),
('cmlewlevp001c6g5wrwrrb1d4', 'A.M.I.G. Agarathna', 'principal.04348@wilgamuwa.com', NULL, NULL, '$2y$12$IQXH3vTXXvJ2M3QwNeOumO355YrztmJ4j8C3ZO.2GqP8Ke8YceZue', 'SCHOOL_ADMIN', 'cmlewlevi001a6g5wrzgp6oal', '2026-02-09 08:21:52.405', '2026-02-22 07:06:08.000', 1),
('cmlewlewe001f6g5wyaflid60', 'R.M.A.K. Rajasinghe', 'principal.04350@wilgamuwa.com', NULL, NULL, '$2y$12$HA.vbDvLkDvdEDWHLOGoxuptkdA/5EHaiFgwVv5z08lxFBSZwpIMq', 'SCHOOL_ADMIN', 'cmlewlevv001d6g5wpnzl66u3', '2026-02-09 08:21:52.430', '2026-02-22 07:06:08.000', 1),
('cmlewlewo001i6g5w7ykkr8na', 'I.B.P.W.N.S. Bandaranayake', 'principal.04119@wilgamuwa.com', NULL, NULL, '$2y$12$1no0mt7N.bAtk7og/T/utuYgjoeuKYEwOz4G4SukRdIjdmqJCkdvO', 'SCHOOL_ADMIN', 'cmlewlewj001g6g5wy39cittq', '2026-02-09 08:21:52.440', '2026-02-22 07:06:09.000', 1),
('cmlewlex0001l6g5wkfeen5vu', 'W.M.C. Weerasekara', 'principal.04123@wilgamuwa.com', NULL, NULL, '$2y$12$7igV9YhAx/LajntVXLm4tud.p3g6Yp43x2VjNislaDypgsHCQCIX2', 'SCHOOL_ADMIN', 'cmlewlewv001j6g5w7xd5nyt5', '2026-02-09 08:21:52.452', '2026-02-22 07:06:09.000', 1),
('cmlewlexd001o6g5ww0zwfzxe', 'Y.G. Wedathilaka', 'principal.04121@wilgamuwa.com', NULL, NULL, '$2y$12$6VvacjQ2qBHDRBlZHLdgJuxP.Raa6In2yfNm68/xllZccwg4Rj7.u', 'SCHOOL_ADMIN', 'cmlewlex6001m6g5w9rzi6u4b', '2026-02-09 08:21:52.465', '2026-02-22 07:06:09.000', 1),
('cmlewlexn001r6g5wyu2q67mb', 'R.D.S. Siriwardhana', 'principal.04122@wilgamuwa.com', NULL, NULL, '$2y$12$IvSvSkk9XGTXEN8k2QU/0u2MrVNpzJnPotFl8EHG1gOya1iA1WL6K', 'SCHOOL_ADMIN', 'cmlewlexh001p6g5wjnu9u9vd', '2026-02-09 08:21:52.476', '2026-02-22 07:06:10.000', 1),
('cmlewlexy001u6g5whe1tjfu6', 'R.W.M.M.R. Kumarihamy', 'principal.04135@wilgamuwa.com', NULL, NULL, '$2y$12$Mj53rM1U/2DoFNHjSl4z.Oyhg7lpNH0VmYye0/qCHB5weop2svMw.', 'SCHOOL_ADMIN', 'cmlewlext001s6g5wobvimc0o', '2026-02-09 08:21:52.487', '2026-02-22 07:06:10.000', 1),
('cmlewleyb001x6g5wozc7ysll', 'U.G.E.M. Nawaratne', 'principal.04149@wilgamuwa.com', NULL, NULL, '$2y$12$zIJb6hCmLi5xZVyFLJqaW.qMj0BaOBMoCwHFWth10FCQjmAnwr2L2', 'SCHOOL_ADMIN', 'cmlewley6001v6g5w6uojl4os', '2026-02-09 08:21:52.499', '2026-02-22 07:06:10.000', 1),
('cmlewleym00206g5wcweeuot7', 'N.G.W.M.A.B. Wijesekara', 'principal.04144@wilgamuwa.com', NULL, NULL, '$2y$12$eknM/7elflF9M212UMBQouZ65Go/ofMoTTunYqQxHnBLBLI/fd0i.', 'SCHOOL_ADMIN', 'cmlewleyg001y6g5wfscmz346', '2026-02-09 08:21:52.510', '2026-02-22 07:06:11.000', 1),
('cmlewleyv00236g5wq4go3z0h', 'W.P.G.C.J. Wijesooriya', 'principal.04124@wilgamuwa.com', NULL, NULL, '$2y$12$ZANFyqKU//YcHHlJypSgTugyA.qnS0mVrKpymOGV4mIo6jVmbXMHG', 'SCHOOL_ADMIN', 'cmlewleyq00216g5w8yue33ep', '2026-02-09 08:21:52.519', '2026-02-22 07:06:11.000', 1),
('cmlewlez600266g5wd4ynfy3n', 'G.G.K. Bandara', 'principal.04319@wilgamuwa.com', NULL, NULL, '$2y$12$2/JsyQFXLmfQvPLLc02Gt.qcle4aGMRy8QvFpFn7GFyTntUqW3OnW', 'SCHOOL_ADMIN', 'cmlewlez100246g5w9tamvq66', '2026-02-09 08:21:52.530', '2026-02-22 07:06:12.000', 1),
('cmlewlezf00296g5wkp4dxi09', 'A.G.A.B. Jayasinghe', 'principal.04126@wilgamuwa.com', NULL, NULL, '$2y$12$fDjHJOBlkk7apaLR4wskdOLB/vgHSFEKaaopoG3nGv7bnZauTZYYO', 'SCHOOL_ADMIN', 'cmlewlezb00276g5woeb8dwno', '2026-02-09 08:21:52.539', '2026-02-22 07:06:12.000', 1),
('cmlewlezo002c6g5wz118ej2o', 'Y.M.W.G. Aberathna Bandara', 'principal.04145@wilgamuwa.com', NULL, NULL, '$2y$12$qXYQuw0Apj8nhn/0vzTfL.d4L1XxIrdMal8rIUusJcXTYrK1HxDGC', 'SCHOOL_ADMIN', 'cmlewlezk002a6g5w0tw2ezbt', '2026-02-09 08:21:52.548', '2026-02-22 07:06:12.000', 1),
('cmlewlezx002f6g5wijqg3efo', 'R.M.P.P. Kumara', 'principal.04322@wilgamuwa.com', NULL, NULL, '$2y$12$eoO2kUdxraaTTQZZqQBn3ehOmU9fYje798u8dD.FGa2GEE.kiKTsm', 'SCHOOL_ADMIN', 'cmlewlezt002d6g5wmnwa0q6o', '2026-02-09 08:21:52.558', '2026-02-22 07:06:13.000', 1),
('cmlewlf07002i6g5w8yrdf3lp', 'A.M.J.J.N.K.S. Bandara', 'principal.04325@wilgamuwa.com', NULL, NULL, '$2y$12$eSR.bxBExKbgEFYr3R5Hf.HQY5P4ka8SRE59QdwUNt2Dm1E/Z/AbW', 'SCHOOL_ADMIN', 'cmlewlf02002g6g5wm34g3tvp', '2026-02-09 08:21:52.568', '2026-02-22 07:06:13.000', 1),
('cmlewlf0h002l6g5wylbrglit', 'A.M.M.K. Aththanayake', 'principal.04342@wilgamuwa.com', NULL, NULL, '$2y$12$qMH5lPuHVXF9h27OpxzWCOlJLCFr8ElfD37B3Vnpcrmq.qy7Gbm12', 'SCHOOL_ADMIN', 'cmlewlf0b002j6g5w8sd0b78f', '2026-02-09 08:21:52.577', '2026-02-22 07:06:13.000', 1),
('cmlewlf0y002o6g5w1gho6pb8', 'K.M.D.G. Dharmarathna', 'principal.04343@wilgamuwa.com', NULL, NULL, '$2y$12$CuBMDcfBln8J4DBAjgL67ebRfxzh9w7T1FI9aIxU6d1hkOwpksPeG', 'SCHOOL_ADMIN', 'cmlewlf0p002m6g5w169i1x4a', '2026-02-09 08:21:52.594', '2026-02-22 07:06:14.000', 1),
('cmlewlf19002r6g5wecgoutas', 'K.P.G.G. Jayathilaka', 'principal.04128@wilgamuwa.com', NULL, NULL, '$2y$12$BXlQeSuJXLIoQbKnEVzzxeZb3Cu40srukdPdd/k80jfFIrf/fxbyq', 'SCHOOL_ADMIN', 'cmlewlf14002p6g5wrcgdgmq8', '2026-02-09 08:21:52.605', '2026-02-22 07:06:14.000', 1),
('cmlewlf1j002u6g5wvw3apbs9', 'D.M.P.G. Bandaranayake', 'principal.04131@wilgamuwa.com', NULL, NULL, '$2y$12$kJP34J9Hlt4FHbj4Ick1DOoQTR7JwaXWYDk2o4NyBFyRHg42hsIne', 'SCHOOL_ADMIN', 'cmlewlf1e002s6g5wni4nejdw', '2026-02-09 08:21:52.615', '2026-02-22 07:06:15.000', 1),
('cmlewlf1t002x6g5wyg7m2rgg', 'A.V. Wijithanandana', 'principal.04140@wilgamuwa.com', NULL, NULL, '$2y$12$dglrfwT2syBrmwtEPVu6Kereqp0nrLwYA2wjxrVzfAaG5mWo0nkoS', 'SCHOOL_ADMIN', 'cmlewlf1o002v6g5wm5rb4yo5', '2026-02-09 08:21:52.626', '2026-02-22 07:06:15.000', 1),
('cmlewlf2800306g5wqkjqu9rm', 'K.M.A.P. Kumari', 'principal.04137@wilgamuwa.com', NULL, NULL, '$2y$12$q74zvn6yEhik3O1MQMvlO.3ypfyyVN1PuBND70UGNTxhZMt2hj2lm', 'SCHOOL_ADMIN', 'cmlewlf21002y6g5wl75rf244', '2026-02-09 08:21:52.640', '2026-02-22 07:06:15.000', 1),
('cmlewlf2h00336g5wm45wnygh', 'M.G.N.B. Gunathunga', 'principal.04323@wilgamuwa.com', NULL, NULL, '$2y$12$G6wUsj9dwwRZD0.T2js7UOkF.Ru7LpZ4Ji1WAn7hvwMtl1pZvco9.', 'SCHOOL_ADMIN', 'cmlewlf2c00316g5w4292hfnb', '2026-02-09 08:21:52.650', '2026-02-22 07:06:16.000', 1),
('cmlewlf2s00366g5wh872dcab', 'W.P.G.B. Jayamaha', 'principal.04138@wilgamuwa.com', NULL, NULL, '$2y$12$50OldjNTPW7AO1yCzOP/R.7Bp5ujPCvcQMDEM8/3agp9693bHg5D.', 'SCHOOL_ADMIN', 'cmlewlf2m00346g5wvuh7w5ij', '2026-02-09 08:21:52.660', '2026-02-22 07:06:16.000', 1),
('cmlewlf3300396g5wje8d7hwt', 'I.G. Ariyasinghe', 'principal.04329@wilgamuwa.com', NULL, NULL, '$2y$12$3TYsFCSufr9C90OxDfpF..3M436coxEFmkRuao81WsA2clP.2e.Xu', 'SCHOOL_ADMIN', 'cmlewlf2x00376g5wml4jn7hp', '2026-02-09 08:21:52.671', '2026-02-22 07:06:17.000', 1),
('cmlewlf3e003c6g5wr3y69yez', 'M.R.G. Chandresna', 'principal.04321@wilgamuwa.com', NULL, NULL, '$2y$12$hTbmgFIq7IrStnoOWMS5VelxusYERkZMiFifp1R5ifkMCgsfgAoKm', 'SCHOOL_ADMIN', 'cmlewlf39003a6g5wcrwptekp', '2026-02-09 08:21:52.682', '2026-02-22 07:06:17.000', 1),
('cmlewlf3p003f6g5w041qo35a', 'U.G. Senaviratne Bandara', 'principal.04318@wilgamuwa.com', NULL, NULL, '$2y$12$cU0V5iqeGJTJzv4p3jhDP.YjcvsNNm6WCkb1HMz0thJ1Dax6jIeCq', 'SCHOOL_ADMIN', 'cmlewlf3k003d6g5w16w0j1yw', '2026-02-09 08:21:52.693', '2026-02-22 07:06:18.000', 1),
('cmlewlf3y003i6g5wqjo1ng8l', 'D.M.R.U. Dissanayake', 'principal.04146@wilgamuwa.com', NULL, NULL, '$2y$12$8oW2Fry2PE3M7mIOdea0NeLeHA.wMQgXREBSLyqE0HaNI9v9GM4cy', 'SCHOOL_ADMIN', 'cmlewlf3u003g6g5wnx4ccf7f', '2026-02-09 08:21:52.702', '2026-02-22 07:06:18.000', 1),
('cmlewlf47003l6g5wywv6hw7a', 'K.G.M.A.K. Jayalath', 'principal.04147@wilgamuwa.com', NULL, NULL, '$2y$12$pz5sdirun6ny84UqD78vpuBiVuOdYCxD4LUEg7aZQzSZHAPgUtS1W', 'SCHOOL_ADMIN', 'cmlewlf43003j6g5wr3kwsmf5', '2026-02-09 08:21:52.711', '2026-02-22 07:06:19.000', 1),
('cmlewlf4h003o6g5wvws4rkoh', 'I.H.G.C.N. Samarajeewa', 'principal.04142@wilgamuwa.com', NULL, NULL, '$2y$12$vumIbCs3FbwpZ7iAAahXAuWPFvwY5OC0Pb6kOS.wLKA6NYAWwMY7O', 'SCHOOL_ADMIN', 'cmlewlf4c003m6g5whvuo0h15', '2026-02-09 08:21:52.721', '2026-02-22 07:06:19.000', 1),
('cmlewlf4q003r6g5wrcoh3jld', 'H.A.A. Kumara', 'principal.04345@wilgamuwa.com', NULL, NULL, '$2y$12$5V8thH.Z0MzGk6feC/qGQO9O/I8Tx48bsOJ4fn9YN8I2hYIRdNGlG', 'SCHOOL_ADMIN', 'cmlewlf4m003p6g5wgjnh38tr', '2026-02-09 08:21:52.730', '2026-02-22 07:06:19.000', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `verificationtoken`
--

CREATE TABLE `verificationtoken` (
  `identifier` varchar(191) NOT NULL,
  `token` varchar(191) NOT NULL,
  `expires` datetime(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Account_provider_providerAccountId_key` (`provider`,`providerAccountId`),
  ADD KEY `Account_userId_fkey` (`userId`);

--
-- Indexes for table `auditlog`
--
ALTER TABLE `auditlog`
  ADD PRIMARY KEY (`id`),
  ADD KEY `AuditLog_userId_fkey` (`userId`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `profileeditrequest`
--
ALTER TABLE `profileeditrequest`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ProfileEditRequest_teacherId_fkey` (`teacherId`),
  ADD KEY `ProfileEditRequest_schoolId_fkey` (`schoolId`);

--
-- Indexes for table `salaryrequest`
--
ALTER TABLE `salaryrequest`
  ADD PRIMARY KEY (`id`),
  ADD KEY `SalaryRequest_teacherId_fkey` (`teacherId`),
  ADD KEY `SalaryRequest_schoolId_fkey` (`schoolId`);

--
-- Indexes for table `school`
--
ALTER TABLE `school`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `School_censusNo_key` (`censusNo`);

--
-- Indexes for table `session`
--
ALTER TABLE `session`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Session_sessionToken_key` (`sessionToken`),
  ADD KEY `Session_userId_fkey` (`userId`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `studentcount`
--
ALTER TABLE `studentcount`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `StudentCount_schoolId_grade_academicYear_key` (`schoolId`,`grade`,`academicYear`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Subject_name_key` (`name`);

--
-- Indexes for table `subjectstatistic`
--
ALTER TABLE `subjectstatistic`
  ADD PRIMARY KEY (`id`),
  ADD KEY `SubjectStatistic_schoolId_academicYear_idx` (`schoolId`,`academicYear`);

--
-- Indexes for table `teacher`
--
ALTER TABLE `teacher`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Teacher_nic_key` (`nic`),
  ADD UNIQUE KEY `Teacher_userId_key` (`userId`),
  ADD KEY `Teacher_schoolId_fkey` (`schoolId`),
  ADD KEY `Teacher_spouseSchoolId_fkey` (`spouseSchoolId`),
  ADD KEY `Teacher_createdByUserId_fkey` (`createdByUserId`),
  ADD KEY `Teacher_updatedByUserId_fkey` (`updatedByUserId`);

--
-- Indexes for table `teachersubject`
--
ALTER TABLE `teachersubject`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `TeacherSubject_teacherId_subjectId_key` (`teacherId`,`subjectId`),
  ADD KEY `TeacherSubject_subjectId_fkey` (`subjectId`);

--
-- Indexes for table `transferrequest`
--
ALTER TABLE `transferrequest`
  ADD PRIMARY KEY (`id`),
  ADD KEY `TransferRequest_teacherId_fkey` (`teacherId`),
  ADD KEY `TransferRequest_fromSchoolId_fkey` (`fromSchoolId`),
  ADD KEY `TransferRequest_toSchoolId_fkey` (`toSchoolId`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `User_email_key` (`email`),
  ADD KEY `User_schoolId_fkey` (`schoolId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `verificationtoken`
--
ALTER TABLE `verificationtoken`
  ADD UNIQUE KEY `VerificationToken_token_key` (`token`),
  ADD UNIQUE KEY `VerificationToken_identifier_token_key` (`identifier`,`token`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `account`
--
ALTER TABLE `account`
  ADD CONSTRAINT `Account_userId_fkey` FOREIGN KEY (`userId`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `auditlog`
--
ALTER TABLE `auditlog`
  ADD CONSTRAINT `AuditLog_userId_fkey` FOREIGN KEY (`userId`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `profileeditrequest`
--
ALTER TABLE `profileeditrequest`
  ADD CONSTRAINT `ProfileEditRequest_schoolId_fkey` FOREIGN KEY (`schoolId`) REFERENCES `school` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `ProfileEditRequest_teacherId_fkey` FOREIGN KEY (`teacherId`) REFERENCES `teacher` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `salaryrequest`
--
ALTER TABLE `salaryrequest`
  ADD CONSTRAINT `SalaryRequest_schoolId_fkey` FOREIGN KEY (`schoolId`) REFERENCES `school` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `SalaryRequest_teacherId_fkey` FOREIGN KEY (`teacherId`) REFERENCES `teacher` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `session`
--
ALTER TABLE `session`
  ADD CONSTRAINT `Session_userId_fkey` FOREIGN KEY (`userId`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `studentcount`
--
ALTER TABLE `studentcount`
  ADD CONSTRAINT `StudentCount_schoolId_fkey` FOREIGN KEY (`schoolId`) REFERENCES `school` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `subjectstatistic`
--
ALTER TABLE `subjectstatistic`
  ADD CONSTRAINT `SubjectStatistic_schoolId_fkey` FOREIGN KEY (`schoolId`) REFERENCES `school` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `teacher`
--
ALTER TABLE `teacher`
  ADD CONSTRAINT `Teacher_createdByUserId_fkey` FOREIGN KEY (`createdByUserId`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `Teacher_schoolId_fkey` FOREIGN KEY (`schoolId`) REFERENCES `school` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `Teacher_spouseSchoolId_fkey` FOREIGN KEY (`spouseSchoolId`) REFERENCES `school` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `Teacher_updatedByUserId_fkey` FOREIGN KEY (`updatedByUserId`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `Teacher_userId_fkey` FOREIGN KEY (`userId`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `teachersubject`
--
ALTER TABLE `teachersubject`
  ADD CONSTRAINT `TeacherSubject_subjectId_fkey` FOREIGN KEY (`subjectId`) REFERENCES `subject` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `TeacherSubject_teacherId_fkey` FOREIGN KEY (`teacherId`) REFERENCES `teacher` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transferrequest`
--
ALTER TABLE `transferrequest`
  ADD CONSTRAINT `TransferRequest_fromSchoolId_fkey` FOREIGN KEY (`fromSchoolId`) REFERENCES `school` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `TransferRequest_teacherId_fkey` FOREIGN KEY (`teacherId`) REFERENCES `teacher` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `TransferRequest_toSchoolId_fkey` FOREIGN KEY (`toSchoolId`) REFERENCES `school` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `User_schoolId_fkey` FOREIGN KEY (`schoolId`) REFERENCES `school` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

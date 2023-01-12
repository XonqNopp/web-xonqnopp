-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--

--
-- Tabellenstruktur für Tabelle `NavList`
--

CREATE TABLE `NavList` (
  `id` int(100) NOT NULL,
  `name` text NOT NULL,
  `plane` int(100) DEFAULT NULL,

  `variation` int(3) NOT NULL,

  `FrontMass` int(3) DEFAULT NULL,

  `Rear0Mass` int(3) DEFAULT NULL,
  `Rear1Mass` int(3) DEFAULT NULL,

  `Luggage0Mass` int(3) DEFAULT NULL,
  `Luggage1Mass` int(3) DEFAULT NULL,
  `Luggage2Mass` int(3) DEFAULT NULL,
  `Luggage3Mass` int(3) DEFAULT NULL,

  `comment` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Before:
-- use b13d3_xonqnopp_ch;
-- describe NavList;
--
-- Commands applied for the transition (see: DESCRIBE `NavList`;)
-- ALTER TABLE `NavList` DROP COLUMN `MapUsed`;
-- ALTER TABLE `NavList` DROP COLUMN `Power`;
-- ALTER TABLE `NavList` DROP COLUMN `PowerManifold`;
-- ALTER TABLE `NavList` DROP COLUMN `PowerManifoldUnit`;
-- ALTER TABLE `NavList` DROP COLUMN `PowerRPM`;
-- ALTER TABLE `NavList` DROP COLUMN `altitude`;

-- ALTER TABLE `NavList` RENAME COLUMN `RearMass` TO `Rear0Mass`;
-- ALTER TABLE `NavList` ADD COLUMN `Rear1Mass` int(3) DEFAULT NULL AFTER `Rear0Mass`;
-- ALTER TABLE `NavList` RENAME COLUMN `LuggageMass` TO `Luggage0Mass`;
-- ALTER TABLE `NavList` ADD COLUMN `Luggage1Mass` int(3) DEFAULT NULL AFTER `Luggage0Mass`;
-- ALTER TABLE `NavList` ADD COLUMN `Luggage2Mass` int(3) DEFAULT NULL AFTER `Luggage1Mass`;
-- ALTER TABLE `NavList` ADD COLUMN `Luggage3Mass` int(3) DEFAULT NULL AFTER `Luggage2Mass`;

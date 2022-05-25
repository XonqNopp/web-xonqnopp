-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--

--
-- Tabellenstruktur für Tabelle `aircrafts`
--

CREATE TABLE `aircrafts` (
  `id` int(100) NOT NULL,
  `PlaneType` varchar(10) NOT NULL,
  `PlaneID` varchar(10) NOT NULL,
  `PlanningSpeed` int(3) NOT NULL,
  `ClimbSpeed` int(3) DEFAULT NULL,
  `FuelCons` int(3) NOT NULL,
  `FuelUnit` varchar(5) NOT NULL,
  `UnusableFuel` int(11) NOT NULL DEFAULT 0,
  `DryMass` int(10) NOT NULL,
  `DryMassUnit` varchar(10) NOT NULL,
  `DryMoment` float NOT NULL,
  `DryMomentUnit` varchar(10) NOT NULL,
  `DryTimestamp` date NOT NULL,
  `ArmUnit` varchar(10) NOT NULL,
  `FrontArm` float NOT NULL,
  `RearArm` float DEFAULT NULL,
  `LuggageArm` float NOT NULL,
  `FuelArm` float NOT NULL,
  `MTOW` int(10) NOT NULL,
  `minGC` float DEFAULT NULL,
  `maxGC` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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

  `MassUnit` varchar(10) NOT NULL,
  `ArmUnit` varchar(10) NOT NULL,
  `MomentUnit` varchar(10) NOT NULL,

  `DryEmptyTimestamp` date NOT NULL,
  `DryEmptyMass` int(10) NOT NULL,
  `DryEmptyMoment` float NOT NULL,

  `MTOW` int(10) NOT NULL,
  `MLDGW` int(10) DEFAULT NULL,

  `GCmin` float DEFAULT NULL,
  `GCmax` float DEFAULT NULL,

  `FrontArm` float NOT NULL,

  `Rear0Arm` float DEFAULT NULL,
  `Rear1Arm` float DEFAULT NULL,

  `Luggage0Arm` float NOT NULL,
  `Luggage0MaxMass` float DEFAULT NULL,
  `Luggage1Arm` float DEFAULT NULL,
  `Luggage1MaxMass` float DEFAULT NULL,
  `Luggage2Arm` float DEFAULT NULL,
  `Luggage2MaxMass` float DEFAULT NULL,
  `Luggage3Arm` float DEFAULT NULL,
  `Luggage3MaxMass` float DEFAULT NULL,

  `LuggageMaxTotalMass` float DEFAULT NULL,

  `Fuel0Arm` float NOT NULL,
  `Fuel0TotalCapacity` int(5) NOT NULL,
  `Fuel0Unusable` int(11) NOT NULL DEFAULT 0,
  `Fuel0AllOrNothing` tinyint(1) NOT NULL,

  `Fuel1Arm` float DEFAULT NULL,
  `Fuel1TotalCapacity` int(5) DEFAULT NULL,
  `Fuel1Unusable` int(11) DEFAULT NULL,
  `Fuel1AllOrNothing` tinyint(1) DEFAULT NULL,

  `Fuel2Arm` float DEFAULT NULL,
  `Fuel2TotalCapacity` int(5) DEFAULT NULL,
  `Fuel2Unusable` int(11) DEFAULT NULL,
  `Fuel2AllOrNothing` tinyint(1) DEFAULT NULL,

  `Fuel3Arm` float DEFAULT NULL,
  `Fuel3TotalCapacity` int(5) DEFAULT NULL,
  `Fuel3Unusable` int(11) DEFAULT NULL,
  `Fuel3AllOrNothing` tinyint(1) DEFAULT NULL,

  `FuelCons` int(3) NOT NULL,
  `FuelUnit` varchar(5) NOT NULL,
  `FuelType` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `FrontMaxMass` float NOT NULL,

  `Rear0Arm` float DEFAULT NULL,
  `Rear0MaxMass` float DEFAULT NULL,
  `Rear1Arm` float DEFAULT NULL,
  `Rear1MaxMass` float DEFAULT NULL,

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
  `Fuel0Unusable` int(5) NOT NULL,
  `Fuel0AllOrNothing` tinyint(1) NOT NULL,

  `Fuel1Arm` float DEFAULT NULL,
  `Fuel1TotalCapacity` int(5) DEFAULT NULL,
  `Fuel1Unusable` int(5) DEFAULT NULL,
  `Fuel1AllOrNothing` tinyint(1) DEFAULT NULL,

  `Fuel2Arm` float DEFAULT NULL,
  `Fuel2TotalCapacity` int(5) DEFAULT NULL,
  `Fuel2Unusable` int(5) DEFAULT NULL,
  `Fuel2AllOrNothing` tinyint(1) DEFAULT NULL,

  `Fuel3Arm` float DEFAULT NULL,
  `Fuel3TotalCapacity` int(5) DEFAULT NULL,
  `Fuel3Unusable` int(5) DEFAULT NULL,
  `Fuel3AllOrNothing` tinyint(1) DEFAULT NULL,

  `FuelCons` int(3) NOT NULL,
  `FuelUnit` varchar(5) NOT NULL,
  `FuelType` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Before:
-- use b13d3_xonqnopp_ch;
-- describe aircrafts;
--
-- Commands applied for the transition (see: DESCRIBE `aircrafts`;)
-- ADD:    alter table `aircrafts` add column `foo` int(1) default null after `bar`;
-- MOVE:   alter table `aircrafts` modify `foo` int(1) default null after `bar`;
-- RENAME: alter table `aircrafts` rename column `foo` to `bar`;
-- RENAME AND MOVE: alter table `aircraft` change column `foo` `bar` int(1) default null [after `baz`];
--
-- template:
-- ALTER TABLE `aircrafts`
--
-- ALTER TABLE `aircrafts` MODIFY `FuelCons` int(3) NOT NULL AFTER `maxGC`;
-- ALTER TABLE `aircrafts` MODIFY `FuelUnit` varchar(5) NOT NULL AFTER `FuelCons`;
-- ALTER TABLE `aircrafts` CHANGE COLUMN `DryMassUnit` `MassUnit` NOT NULL AFTER `ClimbSpeed`;
-- ALTER TABLE `aircrafts` MODIFY `ArmUnit` varchar(10) NOT NULL AFTER `MassUnit`;
-- ALTER TABLE `aircrafts` MODIFY COLUMN `DryMomentUnit` `MomentUnit` VARCHAR(10) NOT NULL AFTER `ArmUnit`;
-- ALTER TABLE `aircrafts` CHANGE `DryTimestamp` `DryEmptyTimestamp` date NOT NULL AFTER `MomentUnit`;
-- ALTER TABLE `aircrafts` CHANGE COLUMN `DryMass` `DryEmptyMass` INT(10) NOT NULL AFTER `DryEmptyTimestamp`;
-- ALTER TABLE `aircrafts` CHANGE COLUMN `DryMoment` `DryEmptyMoment` FLOAT NOT NULL AFTER `DryEmptyMass`;
-- ALTER TABLE `aircrafts` MODIFY `MTOW` int(10) NOT NULL AFTER `DryEmptyMoment`;
-- ALTER TABLE `aircrafts` ADD COLUMN `MLDGW` int(10) DEFAULT NULL AFTER `MTOW`;
-- ALTER TABLE `aircrafts` CHANGE COLUMN `minGC` `GCmin` float NOT NULL AFTER `MLDGW`;
-- ALTER TABLE `aircrafts` CHANGE COLUMN `maxGC` `GCmax` float NOT NULL AFTER `GCmin`;
-- ALTER TABLE `aircrafts` ADD COLUMN `FrontMaxMass` float DEFAULT NULL AFTER `FrontArm`;
-- ALTER TABLE `aircrafts` RENAME COLUMN `RearArm` TO `Rear0Arm`;
-- ALTER TABLE `aircrafts` ADD COLUMN `Rear0MaxMass` float DEFAULT NULL AFTER `Rear0Arm`;
-- ALTER TABLE `aircrafts` ADD COLUMN `Rear1Arm` float DEFAULT NULL AFTER `Rear0MaxMass`;
-- ALTER TABLE `aircrafts` ADD COLUMN `Rear1MaxMass` float DEFAULT NULL AFTER `Rear1Arm`;
-- ALTER TABLE `aircrafts` RENAME COLUMN `LuggageArm` TO `Luggage0Arm`;
-- ALTER TABLE `aircrafts` ADD COLUMN `Luggage0MaxMass` float DEFAULT NULL AFTER `Luggage0Arm`;
-- ALTER TABLE `aircrafts` ADD COLUMN `Luggage1Arm` float DEFAULT NULL AFTER `Luggage0MaxMass`;
-- ALTER TABLE `aircrafts` ADD COLUMN `Luggage1MaxMass` float DEFAULT NULL AFTER `Luggage1Arm`;
-- ALTER TABLE `aircrafts` ADD COLUMN `Luggage2Arm` float DEFAULT NULL AFTER `Luggage1MaxMass`;
-- ALTER TABLE `aircrafts` ADD COLUMN `Luggage2MaxMass` float DEFAULT NULL AFTER `Luggage2Arm`;
-- ALTER TABLE `aircrafts` ADD COLUMN `Luggage3Arm` float DEFAULT NULL AFTER `Luggage2MaxMass`;
-- ALTER TABLE `aircrafts` ADD COLUMN `Luggage3MaxMass` float DEFAULT NULL AFTER `Luggage3Arm`;
-- ALTER TABLE `aircrafts` ADD COLUMN `LuggageMaxTotalMass` float DEFAULT NULL AFTER `Luggage3MaxMass`;
-- ALTER TABLE `aircrafts` RENAME COLUMN `FuelArm` TO `Fuel0Arm`;
-- ALTER TABLE `aircrafts` ADD COLUMN `Fuel0TotalCapacity` int(5) NOT NULL AFTER `Fuel0Arm`;
-- ALTER TABLE `aircrafts` CHANGE COLUMN `UnusableFuel` `Fuel0Unusable` int(5) NOT NULL AFTER `Fuel0TotalCapacity`;
-- ALTER TABLE `aircrafts` ADD COLUMN `Fuel0AllOrNothing` tinyint(1) NOT NULL AFTER `Fuel0Unusable`;
-- ALTER TABLE `aircrafts` ADD COLUMN `Fuel1Arm` float DEFAULT NULL AFTER `Fuel0AllOrNothing`;
-- ALTER TABLE `aircrafts` ADD COLUMN `Fuel1TotalCapacity` int(5) DEFAULT NULL AFTER `Fuel1Arm`;
-- ALTER TABLE `aircrafts` ADD COLUMN `Fuel1Unusable` int(5) DEFAULT NULL AFTER `Fuel1TotalCapacity`;
-- ALTER TABLE `aircrafts` ADD COLUMN `Fuel1AllOrNothing` tinyint(1) DEFAULT NULL AFTER `Fuel1Unusable`;
-- ALTER TABLE `aircrafts` ADD COLUMN `Fuel2Arm` float DEFAULT NULL AFTER `Fuel1AllOrNothing`;
-- ALTER TABLE `aircrafts` ADD COLUMN `Fuel2TotalCapacity` int(5) DEFAULT NULL AFTER `Fuel2Arm`;
-- ALTER TABLE `aircrafts` ADD COLUMN `Fuel2Unusable` int(5) DEFAULT NULL AFTER `Fuel2TotalCapacity`;
-- ALTER TABLE `aircrafts` ADD COLUMN `Fuel2AllOrNothing` tinyint(1) DEFAULT NULL AFTER `Fuel2Unusable`;
-- ALTER TABLE `aircrafts` ADD COLUMN `Fuel3Arm` float DEFAULT NULL AFTER `Fuel2AllOrNothing`;
-- ALTER TABLE `aircrafts` ADD COLUMN `Fuel3TotalCapacity` int(5) DEFAULT NULL AFTER `Fuel3Arm`;
-- ALTER TABLE `aircrafts` ADD COLUMN `Fuel3Unusable` int(5) DEFAULT NULL AFTER `Fuel3TotalCapacity`;
-- ALTER TABLE `aircrafts` ADD COLUMN `Fuel3AllOrNothing` tinyint(1) DEFAULT NULL AFTER `Fuel3Unusable`;
-- ALTER TABLE `aircrafts` ADD COLUMN `FuelType` varchar(10) NOT NULL AFTER `FuelUnit`;

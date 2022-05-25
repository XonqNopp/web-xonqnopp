-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/

--
-- Tabellenstruktur für Tabelle `comco`
--

CREATE TABLE `comco` (
  `id` int(100) NOT NULL,
  `company` int(100) NOT NULL,
  `timestamp` datetime NOT NULL,
  `who` text DEFAULT NULL,
  `media` text DEFAULT NULL,
  `way` text DEFAULT NULL,
  `kind` text DEFAULT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

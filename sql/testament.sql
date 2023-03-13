-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/

--
-- Tabellenstruktur für Tabelle `testament`
--

CREATE TABLE `testament` (
  `id` int(100) NOT NULL,
  `duedate` date NOT NULL,
  `lastwarning` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

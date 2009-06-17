-- phpMyAdmin SQL Dump
-- version 2.10.1
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Erstellungszeit: 02. Juli 2008 um 13:30
-- Server Version: 5.0.41
-- PHP-Version: 5.2.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

DROP TABLE IF EXISTS `transactions`;
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `topic` varchar(10) collate utf8_unicode_ci NOT NULL,
  `label` text collate utf8_unicode_ci NOT NULL,
  `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  KEY `key` (`topic`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `transactions` (`id`, `topic`, `label`, `timestamp`) VALUES 
(1, 'start', '1210847252', '2008-05-15 12:27:32');

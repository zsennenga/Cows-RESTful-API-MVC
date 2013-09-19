-- --------------------------------------------------------

--
-- Table structure for table `cows_keys`
--

DROP TABLE IF EXISTS `cows_keys`;
CREATE TABLE IF NOT EXISTS `cows_keys` (
  `publicKey` varchar(512) NOT NULL,
  `privateKey` varchar(512) NOT NULL,
  `comment` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`publicKey`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cows_log`
--

DROP TABLE IF EXISTS `cows_log`;
CREATE TABLE IF NOT EXISTS `cows_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip` varchar(64) NOT NULL,
  `publicKey` varchar(128) NOT NULL,
  `route` varchar(128) NOT NULL,
  `method` varchar(16) NOT NULL,
  `params` varchar(1024) NOT NULL,
  `response` varchar(4096) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `cows_session`
--

DROP TABLE IF EXISTS `cows_session`;
CREATE TABLE IF NOT EXISTS `cows_session` (
  `publicKey` varchar(512) NOT NULL,
  `siteId` varchar(64) NOT NULL,
  `cookieFile` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`publicKey`,`siteId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cows_session`
--
ALTER TABLE `cows_session`
  ADD CONSTRAINT `cows_session_ibfk_1` FOREIGN KEY (`publicKey`) REFERENCES `cows_keys` (`publicKey`) ON DELETE NO ACTION ON UPDATE NO ACTION;

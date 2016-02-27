-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uri` varchar(30) NOT NULL DEFAULT '',
  `tag_name` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uri` (`uri`),
  UNIQUE KEY `name` (`tag_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=33 ;

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `news_id` int(11) NOT NULL AUTO_INCREMENT,
  `news_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `news_author` varchar(15) NOT NULL DEFAULT '',
  `news_header` varchar(80) NOT NULL DEFAULT '',
  `news_text` text NOT NULL,
  PRIMARY KEY (`news_id`),
  UNIQUE KEY `news_date` (`news_date`),
  FULLTEXT KEY `news_text` (`news_text`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 COMMENT='News information' AUTO_INCREMENT=317 ;

-- --------------------------------------------------------

--
-- Table structure for table `news_tags`
--

CREATE TABLE IF NOT EXISTS `news_tags` (
  `nid` int(11) NOT NULL DEFAULT '0',
  `tid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`nid`,`tid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


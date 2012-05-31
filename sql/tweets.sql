SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `tweets`
-- ----------------------------
DROP TABLE IF EXISTS `tweets`;
CREATE TABLE `tweets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `screen_name` text NOT NULL,
  `user_id` text NOT NULL,
  `tweets` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
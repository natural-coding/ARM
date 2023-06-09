CREATE TABLE `protocol_table` (
  `proto_ordnum` int(10) unsigned NOT NULL,
  `test_date` date DEFAULT NULL,
  `employee` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `values_ok_flag` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`proto_ordnum`),
  UNIQUE KEY `PROTO_ORDNUM_UNIQUE` (`proto_ordnum`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

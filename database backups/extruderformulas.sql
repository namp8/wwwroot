CREATE TABLE `extruder_formulas` (
  `extruder_formula_id` int(11) NOT NULL AUTO_INCREMENT,
  `material_id` int(11) NOT NULL,
  `percentage` double(10,2) NOT NULL,
  `from` date NOT NULL,
  `to` date DEFAULT NULL,
  `actual` tinyint(1) NOT NULL DEFAULT '1',
  `remarks` varchar(150) NOT NULL DEFAULT ' ',
  PRIMARY KEY (`extruder_formula_id`),
  KEY `extruder_formulas_ibfk_1` (`material_id`),
  CONSTRAINT `extruder_formulas_ibfk_1` FOREIGN KEY (`material_id`) REFERENCES `materials` (`material_id`)
) 
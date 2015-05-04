ALTER TABLE `0_users` ADD `show_hints` TINYINT(1) DEFAULT '0' NOT NULL AFTER `show_codes` ;



-- 
-- ALTER TABLE for ccd
-- 

ALTER TABLE `0_salesman`  ADD `loc_code` VARCHAR(5) NOT NULL DEFAULT 'DEF';

ALTER TABLE `0_sales_orders`  ADD `salesman_code` INT(11) NOT NULL DEFAULT '1' AFTER `from_stk_loc`;


--
-- Table structure for table `0_loc_refs`
--

CREATE TABLE IF NOT EXISTS `0_loc_refs` (
  `trans_type` int(11) NOT NULL,
  `loc_code` varchar(5) NOT NULL,
  `next_reference` varchar(100) NOT NULL,
  PRIMARY KEY (`trans_type`,`loc_code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


ALTER TABLE `0_debtor_trans`  ADD `loc_code` VARCHAR(5) NOT NULL AFTER `type`;


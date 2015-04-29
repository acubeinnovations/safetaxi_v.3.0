
-----------------29/04/2015-------------------------------------------
ALTER TABLE `trips`  ADD `invoice_no` INT(11) NOT NULL AFTER `remarks`;
ALTER TABLE `customers`  ADD `fa_customer_id` INT(11) NOT NULL AFTER `customer_status_id`;

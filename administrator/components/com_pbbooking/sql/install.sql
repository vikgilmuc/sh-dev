#
# Encoding: Unicode (UTF-8)
#


DROP TABLE IF EXISTS `#__pbbooking_block_days`;
DROP TABLE IF EXISTS `#__pbbooking_cals`;
DROP TABLE IF EXISTS `#__pbbooking_config`;
DROP TABLE IF EXISTS `#__pbbooking_customfields`;
DROP TABLE IF EXISTS `#__pbbooking_customfields_data`;
DROP TABLE IF EXISTS `#__pbbooking_events`;
DROP TABLE IF EXISTS `#__pbbooking_pending`;
DROP TABLE IF EXISTS `#__pbbooking_treatments`;
DROP TABLE IF EXISTS `#__pbbooking_logs`;


CREATE TABLE `#__pbbooking_cals` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `in_cal` int(11) NOT NULL,
  `out_cal` int(11) NOT NULL,
  `name` varchar(80) NOT NULL,
  `hours` text,
  `email` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `#__pbbooking_block_days` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `block_start_date` varchar(10) NOT NULL,
  `block_end_date` varchar(10) NOT NULL,
  `block_note` varchar(255) DEFAULT NULL,
  `calendars` varchar(255) DEFAULT NULL,
  `r_int` int(11) DEFAULT '0',
  `r_freq` varchar(128) DEFAULT NULL,
  `r_end` date DEFAULT NULL,
  PRIMARY KEY (`id`)
);


CREATE TABLE `#__pbbooking_config` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email_body` text NOT NULL,
  `trading_hours` text,
  `email_subject` varchar(255) NOT NULL,
  `site_url` varchar(80) NOT NULL,
  `timezone` varchar(80) NOT NULL,
  `block_same_day` int(11) NOT NULL,
  `calendar_message` text,
  `create_message` text,
  `consolidated_view` int(1) DEFAULT NULL,
  `show_link` int(1) DEFAULT NULL,
  `date_format_heading` varchar(255) DEFAULT NULL,
  `date_format_message` varchar(255) DEFAULT NULL,
  `date_format_cell` varchar(255) DEFAULT NULL,
  `allow_subscribe` tinyint(1) DEFAULT '0',
  `subscribe_secret` varchar(128) DEFAULT NULL,
  `publish_username` varchar(80) DEFAULT NULL,
  `publish_password` varchar(128) DEFAULT NULL,
  `allow_publish` tinyint(1) DEFAULT '0',
  `use_pb_pub_sec` tinyint(1) DEFAULT '0',
  `prevent_overbooking` tinyint(1) DEFAULT '0',
  `time_groupings` text,
  `time_increment` int(11) DEFAULT '30',
  `use_freeflow` tinyint(1) NOT NULL DEFAULT '1',
  `show_prices` tinyint(1) DEFAULT '1',
  `bcc_admin` tinyint(1) DEFAULT '1',
  `validation` varchar(128) DEFAULT 'client',
  `calendar_start_day` tinyint(1) NOT NULL DEFAULT '0',
  `show_busy_frontend` tinyint(1) NOT NULL DEFAULT '0',
  `enable_logging` tinyint(1) NOT NULL DEFAULT '0',
  `auto_validated_appt_body` TEXT DEFAULT NULL,
  `auto_validated_appt_email_subject` TEXT DEFAULT NULL,
  `manage_fields` TEXT DEFAULT NULL,
  `enable_shifts` TINYINT(1) DEFAULT '1',
  `currency_symbol_before` TINYINT(1) DEFAULT '1',
  `admin_validation_pending_email_subject` VARCHAR(256) DEFAULT NULL,
  `admin_validation_pending_email_body` TEXT DEFAULT NULL,
  `admin_validation_confirmed_email_subject` VARCHAR(256) DEFAULT NULL,
  `admin_validation_confirmed_email_body` TEXT DEFAULT NULL,
  PRIMARY KEY (`id`)
);


CREATE TABLE `#__pbbooking_customfields` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `fieldname` varchar(80) DEFAULT NULL,
  `fieldtype` varchar(80) DEFAULT NULL,
  `varname` varchar(80) DEFAULT NULL,
  `size` int(11) DEFAULT NULL,
  `is_email` tinyint(1) DEFAULT NULL,
  `is_required` tinyint(1) DEFAULT NULL,
  `is_first_name` tinyint(1) DEFAULT '0',
  `is_last_name` tinyint(1) DEFAULT '0',
  `values` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
);


CREATE TABLE `#__pbbooking_customfields_data` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `customfield_id` int(11) DEFAULT NULL,
  `pending_id` int(11) DEFAULT NULL,
  `data` varchar(256) DEFAULT NULL,
  `is_email` tinyint(1) DEFAULT NULL,
  `is_required` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
);


CREATE TABLE `#__pbbooking_events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cal_id` int(11) NOT NULL,
  `summary` text NOT NULL,
  `dtend` datetime DEFAULT NULL,
  `dtstart` datetime DEFAULT NULL,
  `description` text NOT NULL,
  `uid` varchar(80) DEFAULT NULL,
  `service_id` int(11) DEFAULT '0',
  `r_int` int(11) DEFAULT NULL,
  `r_freq` VARCHAR(255) DEFAULT NULL,
  `r_end` datetime DEFAULT NULL,
  `customfields_data` text,
  `email` varchar(128) DEFAULT NULL,
  `deposit_paid` tinyint(1) DEFAULT '0',
  `amount_paid` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id`)
);

CREATE TABLE `#__pbbooking_pending` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(80) NOT NULL,
  `date` varchar(80) NOT NULL,
  `service` int(11) NOT NULL,
  `verified` int(11) NOT NULL,
  `cal_id` int(11) DEFAULT NULL,
  `dtstart` datetime DEFAULT NULL,
  `token` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `#__pbbooking_treatments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(512) NOT NULL,
  `duration` int(11) NOT NULL,
  `price` float NOT NULL DEFAULT '0',
  `calendar` varchar(128) DEFAULT '0',
  `require_payment` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`)
);

CREATE TABLE `#__pbbooking_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `datetime` datetime DEFAULT NULL,
  `component` varchar(128) DEFAULT NULL,
  `message` text,
  PRIMARY KEY (`id`)
);




INSERT INTO `#__pbbooking_cals` (`id`, `in_cal`, `out_cal`, `name`, `hours`) VALUES (1, 1, 1, 'Massage Therapist 1', '[{"status":"open","open_time":"1000","close_time":"2000"},{"status":"open","open_time":"1000","close_time":"2000"},{"status":"open","open_time":"1000","close_time":"2000"},{"status":"open","open_time":"1000","close_time":"2000"},{"status":"open","open_time":"1000","close_time":"2000"},{"status":"open","open_time":"0900","close_time":"1700"},{"status":"closed"}]'), (2, 1, 1, 'Beauty Therapist 1', '[{"status":"closed"},{"status":"closed"},{"status":"closed"},{"status":"open","open_time":"1000","close_time":"2000"},{"status":"open","open_time":"1000","close_time":"2000"},{"status":"open","open_time":"0900","close_time":"1700"},{"status":"closed"}]'), (3, 1, 1, 'Naturopath 1', '[{"status":"open","open_time":"1000","close_time":"2000"},{"status":"open","open_time":"1000","close_time":"2000"},{"status":"open","open_time":"1000","close_time":"2000"},{"status":"open","open_time":"1000","close_time":"2000"},{"status":"open","open_time":"1000","close_time":"2000"},{"status":"open","open_time":"0900","close_time":"1700"},{"status":"closed"}]'), (4, 1, 1, 'Acupuncturist 1', '[{"status":"open","open_time":"1000","close_time":"2000"},{"status":"open","open_time":"1000","close_time":"2000"},{"status":"open","open_time":"1000","close_time":"2000"},{"status":"open","open_time":"1000","close_time":"2000"},{"status":"open","open_time":"1000","close_time":"2000"},{"status":"open","open_time":"0900","close_time":"1700"},{"status":"closed"}]');


INSERT INTO `#__pbbooking_config` (`id`, `email_body`, `trading_hours`, `email_subject`, `site_url`, `timezone`, `block_same_day`, `calendar_message`, `create_message`, `consolidated_view`, `show_link`, `date_format_heading`, `date_format_message`, `date_format_cell`, `allow_subscribe`, `subscribe_secret`, `publish_username`, `publish_password`, `allow_publish`, `use_pb_pub_sec`, `prevent_overbooking`, `time_groupings`, `time_increment`, `use_freeflow`, `show_prices`, `bcc_admin`, `validation`, `calendar_start_day`) VALUES (1, '<p>Hi |*firstname*| |*lastname*|</p>



<p>Thank you for choosing us for your next treatment.  Please click the below link to validate your appointment with us.</p><p>|*URL*|</p>



<p>You\'re booking details are</p><p>|*booking_details*|</p>', '[{"status":"open","open_time":"1000","close_time":"2000"},{"status":"open","open_time":"1000","close_time":"2000"},{"status":"open","open_time":"1000","close_time":"2000"},{"status":"open","open_time":"1000","close_time":"2000"},{"status":"open","open_time":"1000","close_time":"2000"},{"status":"open","open_time":"0900","close_time":"1700"},{"status":"closed"}]', 'Your Treatment Verification', 'http://127.0.0.1/joomla', 'Australia/Brisbane', 0, '<b>change this <i>calendar message</i> to something more appropriate</b>', '<b>change this <i>create message</i> to something more appropriate</b>', 0, 1, 'l d F', 'd/m/Y', 'd/m', 0, 'top secret', 'username', 'password', 0, 0, 0, 'morning=1000-1200|afternoon=1330-1700|evening=1700-1930', 30, 1, 1, 1, 'client', 1);


INSERT INTO `#__pbbooking_customfields` (`id`, `fieldname`, `fieldtype`, `varname`, `size`, `is_email`, `is_required`, `is_first_name`, `is_last_name`, `values`) VALUES (1, 'First Name', 'text', 'firstname', 60, NULL, 1, 1, 0, NULL), (2, 'Last Name', 'text', 'lastname', 60, NULL, 1, 0, 1, NULL), (3, 'Email', 'text', 'email', 60, 1, 1, 0, 0, NULL), (4, 'Mobile', 'text', 'mobile', 60, NULL, 1, 0, 0, NULL), (5, 'Gender', 'radio', 'gender', 60, 0, 1, 0, 0, 'Male|Female');


INSERT INTO `#__pbbooking_treatments` (`id`, `name`, `duration`, `price`, `calendar`) VALUES (1, '30 minute relaxaton massage take 2', 30, 40, '1,2'), (2, '60 minute relexation massage', 60, 60, '1,2'), (3, '30 minute remedial massage', 30, 40, '1'), (4, '60 minute remedial massage', 60, 70, '1'), (5, 'Express Facial', 30, 60, '2');


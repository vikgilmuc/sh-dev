<?php
/**
* @package		PurpleBeanie.PBBooking
* @license		GNU General Public License version 2 or later; see LICENSE.txt
* @link		http://www.purplebeanie.com
*/
 
// No direct access
 
defined( '_JEXEC' ) or die( 'Restricted access' );


class com_pbbookingInstallerScript
{
	function preflight($type,$parent) {
	}


	function update($parent)
	{

		$db = JFactory::getDbo();

		$extension = $db->setQuery('select * from #__extensions where element = "com_pbbooking"')->loadObject();
		if ($extension) {
			$manifest_cache = json_decode($extension->manifest_cache,true);
			if ($manifest_cache['version'] == '2.2.1') {
				//execute SQL statements here
				$db->setQuery('ALTER TABLE `#__pbbooking_block_days` ADD COLUMN (`calendars` VARCHAR(255) DEFAULT null,`r_int` INT(11) DEFAULT 0,`r_freq` VARCHAR(128) DEFAULT null,`r_end` DATE DEFAULT null);')->query();				
				$db->setQuery('ALTER TABLE `#__pbbooking_cals` DROP COLUMN `path`;')->query();
				$db->setQuery('ALTER TABLE `#__pbbooking_cals` ADD COLUMN (`email` VARCHAR(128) DEFAULT null);')->query();
				$db->setQuery('ALTER TABLE `#__pbbooking_config` ADD COLUMN (`validation` VARCHAR(128) DEFAULT \'client\',`calendar_start_day` TINYINT(1) DEFAULT 0,`show_busy_frontend` TINYINT(1) DEFAULT 0,`enable_logging` TINYINT(1) DEFAULT 0);')->query();
				$db->setQuery('ALTER TABLE `#__pbbooking_events` ADD COLUMN (`r_int` INT(11) DEFAULT null,`r_freq` VARCHAR(255) DEFAULT null,`r_end` DATETIME DEFAULT null,`customfields_data` TEXT default null,`email` VARCHAR(128) DEFAULT null,`deposit_paid` TINYINT(1) DEFAULT 0,`amount_paid` DECIMAL(10,2) DEFAULT 0.00);')->query();
				$db->setQuery('DROP TABLE IF EXISTS `#__pbbooking_logs`;')->query();
				$db->setQuery('CREATE TABLE `#__pbbooking_logs` (`id` INT(11) NOT NULL AUTO_INCREMENT,`datetime` DATETIME DEFAULT null,`component` VARCHAR(128) DEFAULT null,`message` TEXT DEFAULT null,PRIMARY KEY(`id`));')->query();
				$db->setQuery('ALTER TABLE `#__pbbooking_pending` ADD COLUMN (`token` VARCHAR(128) DEFAULT null);')->query();
				$db->setQuery('ALTER TABLE `#__pbbooking_treatments` CHANGE COLUMN `calendar` `calendar` VARCHAR(128) DEFAULT 0;')->query();
				$db->setQuery('ALTER TABLE `#__pbbooking_treatments` ADD COLUMN (`require_payment` TINYINT(1) DEFAULT 0);')->query();
				$db->setQuery('ALTER TABLE `#__pbbooking_config` ADD COLUMN(`auto_validated_appt_email_subject` VARCHAR(256) DEFAULT null);')->query();
				$db->setQuery('ALTER TABLE `#__pbbooking_config` ADD COLUMN(`auto_validated_appt_body` TEXT DEFAULT null);')->query();
				$db->setQuery('ALTER TABLE `#__pbbooking_config` ADD COLUMN(`manage_fields` TEXT DEFAULT null);')->query();
				$db->setQuery('ALTER TABLE `#__pbbooking_config` ADD COLUMN(`enable_shifts` TINYINT(1) DEFAULT \'1\');')->query();
				$db->setQuery('ALTER TABLE `#__pbbooking_config` ADD COLUMN (`currency_symbol_before` TINYINT(1) DEFAULT \'1\');')->query();
				$db->setQuery('ALTER TABLE `#__pbbooking_config` ADD COLUMN(`admin_validation_pending_email_subject` VARCHAR(256) DEFAULT NULL);')->query();
				$db->setQuery('ALTER TABLE `#__pbbooking_config` ADD COLUMN(`admin_validation_pending_email_body` TEXT DEFAULT NULL);')->query();
				$db->setQuery('ALTER TABLE `#__pbbooking_config` ADD COLUMN(`admin_validation_confirmed_email_subject` VARCHAR(256) DEFAULT NULL);')->query();
				$db->setQuery('ALTER TABLE `#__pbbooking_config` ADD COLUMN(`admin_validation_confirmed_email_body` TEXT DEFAULT NULL);')->query();
				$db->setQuery('ALTER TABLE `#__pbbooking_customfields_data` MODIFY `data` VARCHAR(256) DEFAULT NULL;')->query();
			}
		}
	}

	function postflight($type,$parent)
	{
		$db = JFactory::getDbo();

		$extension = $db->setQuery('select * from #__extensions where element = "com_pbbooking"')->loadObject();
		if ($extension) {
			$manifest_cache = json_decode($extension->manifest_cache,true);
			if ($manifest_cache['version'] == '2.2.1' || $manifest_cache['version'] == '2.3' || $manifest_cache['version'] == '2.3.1') {
				//upgrade the time groupings in the dbase.
				//morning=1000-1200|afternoon=1330-1700|evening=1700-1930
				//{"morning":{"display_label":"morning","shift_start":"1000","shift_end":"1200"},"afternoon":{"display_label":"afternoon","shift_start":"1300","shift_end":"1700"},"evening":{"display_label":"evening","shift_start":"1800","shift_end":"2000"}}
				$config = $db->setQuery('select * from #__pbbooking_config')->loadObject();
				$shifts = array();
				error_log('upgrade dbase');
				foreach (explode('|',$config->time_groupings) as $grouping) {
					//morning=1000-1200
					$label_times_arr = explode('=',$grouping);
					$times_arr = explode('-',$label_times_arr[1]);
					$shifts[$label_times_arr[0]] = array('display_label'=>$label_times_arr[0],'shift_start'=>$times_arr[0],'shift_end'=>$times_arr[1]);
				}
				$config->time_groupings = json_encode($shifts);
				$db->updateObject('#__pbbooking_config',new JObject($config),'id');
			}
		}
	}
}


?>
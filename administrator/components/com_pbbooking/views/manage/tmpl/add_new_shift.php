<?php 

/**
* @package		PurpleBeanie.PBBooking
* @license		GNU General Public License version 2 or later; see LICENSE.txt
* @link		http://www.purplebeanie.com
*/

?>

<div class="bootstrap-wrap">

<h1><?php echo JText::_('COM_PBBOOKING_SHIFT_TIMES');?></h1>


<form class="adminForm" action="index.php" method="post" name="adminForm">

	<fieldset>
		<legend><?php echo JText::_('COM_PBBOOKING_SHIFT_TIMES');?></legend>
		<table>
			<tr>
				<th><?php echo JText::_('COM_PBBOOKING_SHIFT_LABEL');?></th>
				<td><input type="text" size="80" name="shift" value=""/></td>
			</tr>
			<tr>
				<th><?php echo JText::_('COM_PBBOOKING_SHIFT_DISPLAY_LABEL');?></th>
				<td><input type="text" size="80" name="shift_label" value=""/></td>
			</tr>
			<tr>
				<th><?php echo JText::_('COM_PBBOOKING_SHIFT_START');?></th>
				<td><input type="text" size="40" name="shift_start" value=""/></td>
			</tr>
			<tr>
				<th><?php echo JText::_('COM_PBBOOKING_SHIFT_END');?></th>
				<td><input type="text" size="40" name="shift_end" value=""/></td>
			</tr>

			
		</table>
	</fieldset>

	<input type="hidden" name="option" value="com_pbbooking"/>
	<input type="hidden" name="controller" value="manage"/>
	<input type="hidden" name="task" value="save_new_shift"/>
</form>
</div>
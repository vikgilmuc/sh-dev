<?php 

/**
* @package		PurpleBeanie.PBBooking
* @version		$Id: pbbooking.php
* @license		GNU General Public License version 2 or later; see LICENSE.txt
* @link		http://www.purplebeanie.com
*/

defined('_JEXEC') or die('Restricted access'); 

?>


<div class="bootstrap-wrap">

	<form action="index.php" method="get" name="adminForm" id="adminForm">
	<div class="row-fluid">



		<div class="span12">
	    <table class="table table-stripe" style="">
	    <thead>
	        <tr>
				<th width="20"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->customfields ); ?>)"></th>
	            <th width="5">
	                <?php echo JText::_( 'ID' ); ?>
	            </th>
	            <th>
	                <?php echo JText::_( 'COM_PBBOOKING_FIELDS_NAME' ); ?></th>
	            <th><?php echo JText::_( 'COM_PBBOOKING_FIELDS_TYPE' ); ?></th>
	            <th><?php echo JText::_( 'COM_PBBOOKING_FIELDS_VARNAME' ); ?></th>
	            <th><?php echo JText::_( 'COM_PBBOOKING_FIELDS_SIZE' ); ?></th>
	            <th><?php echo JText::_('COM_PBBOOKING_IS_EMAIL');?></th>
	            <th><?php echo JText::_('COM_PBBOOKING_IS_FIRST_NAME');?></th>
	            <th><?php echo JText::_('COM_PBBOOKING_IS_LAST_NAME');?></th>
	            <th><?php echo JText::_('COM_PBBOOKING_IS_REQUIRED');?></th>
	            <th><?php echo JText::_('COM_PBBOOKING_FIELD_VALUES');?></th>
	        </tr>            
	    </thead>
		<?php
		$k = 0;
	   for ($i=0, $n=count( $this->customfields ); $i < $n; $i++)
	    {
	        $row =& $this->customfields[$i];
	        $checked    = JHTML::_( 'grid.id', $i, $row['id'] );
	        $link = JRoute::_( 'index.php?option=com_pbbooking&controller=customfields&task=edit&cid[]='. $row['id'] );

	        ?>
	        <tr class="<?php echo "row$k"; ?>">
	            <td><?php echo $checked;?></td>
				<td>
	                <?php echo $row['id']; ?>
	            </td>
	       
	                <td><?php echo $row['fieldname']; ?></td>
	<td><?php echo $row['fieldtype']; ?></td>
	<td><?php echo $row['varname']; ?></td>
	<td><?php echo $row['size']; ?></td>
	<td>
		<input type="checkbox" name="is_email" 
		<?php if ($row['is_email']) :?>
			checked
		<?php endif;?>
		/>
	</td>
	<td>
		<input type="checkbox" name="is_first_name" 
		<?php if ($row['is_first_name']) :?>
			checked
		<?php endif;?>
		/>
	</td>
	<td>
		<input type="checkbox" name="is_last_name" 
		<?php if ($row['is_last_name']) :?>
			checked
		<?php endif;?>
		/>
	</td>
	
	<td>
		<input type="checkbox" name="is_required" 
		<?php if ($row['is_required']) :?>
			checked
		<?php endif;?>
		/>
	</td>
	
	<td>
		<?php echo $row['values'];?>
	</td>
	        
	        </tr>
	        <?php
	        $k = 1 - $k;
	    }
	    ?>
	    </table>
	</div>
	</div>

	<input type="hidden" name="option" value="com_pbbooking" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="controller" value="customfields" />

	</form>
</div>
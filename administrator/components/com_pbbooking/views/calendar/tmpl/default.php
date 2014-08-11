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

		<div class="row-fluid">


			<div class="span12">

				<div id="editcell">
				    <table class="table table-striped">
				    <thead>
				        <tr>
							<th width="20"><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this)" ></th>
				            <th width="5">
				                <?php echo JText::_( 'ID' ); ?>
				            </th>
				            <th><?php echo JText::_( 'COM_PBBOOKING_CAL_NAME' ); ?></th>
				            <th><?php echo JText::_('COM_PBBOOKING_CAL_OVERRIDE_EMAIL');?></th>
				        </tr>            
				    </thead>
					<?php
					$k = 0;
				   for ($i=0, $n=count( $this->calendars ); $i < $n; $i++)
				    {
				        $row =& $this->calendars[$i];
				        $checked    = JHTML::_( 'grid.id', $i, $row['id'] );
				        $link = JRoute::_( 'index.php?option=com_pbbooking&controller=calendar&task=edit&cid[]='. $row['id'] );

				        ?>
				        <tr class="<?php echo "row$k"; ?>">
				            <td><?php echo $checked;?></td>
							<td><?php echo $row['id']; ?></td>
				       		<td><?php echo $row['name']; ?></td>
				       		<td><?php echo (isset($row['email'])) ? $row['email'] : '';?></td>
				        </tr>
				        <?php
				        $k = 1 - $k;
				    }
				    ?>
				    </table>
				</div>

				<input type="hidden" name="option" value="com_pbbooking" />
				<input type="hidden" name="task" value="" />
				<input type="hidden" name="boxchecked" value="0" />
				<input type="hidden" name="controller" value="calendar" />
			</div>

		</div>

	</div>

</form>

</div>
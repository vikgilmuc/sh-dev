<?php 

defined('_JEXEC') or die('Restricted access');

/**
* @package		PurpleBeanie.PBBooking
* @version		$Id: pbbooking.php
* @license		GNU General Public License version 2 or later; see LICENSE.txt
* @link		http://www.purplebeanie.com
*/

?>

<div class="bootstrap-wrap">
	<form action="index.php" method="get" name="adminForm">
	<div id="editcell">
	    <table class="adminlist">
	    <thead>
	        <tr>
				<th width="20"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->calendars ); ?>)"></th>
	            <th width="5">
	                <?php echo JText::_( 'ID' ); ?>
	            </th>
	            <th>
	                <?php echo JText::_( 'Path' ); ?></th>
	            <th><?php echo JText::_( 'In Cal' ); ?></th>
	            <th><?php echo JText::_( 'Out Cal' ); ?></th>
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
				<td>
	                <?php echo $row['id']; ?>
	            </td>
	       
	                <td><?php echo $row['path']; ?></td>
	<td><?php echo $row['in_cal']; ?></td>
	<td><?php echo $row['out_cal']; ?></td>

	        
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

	</form>
</div>
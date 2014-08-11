<?php 

/**
* @package		PurpleBeanie.PBBooking
* @license		GNU General Public License version 2 or later; see LICENSE.txt
* @link		http://www.purplebeanie.com
*/

defined('_JEXEC') or die('Restricted access'); 

?>

<div class="bootstrap-wrap">

	<form action="index.php" method="get" name="adminForm" id="adminForm">
		<div class="row-fluid">

			<div class="span12">

			    <table class="table table-stripe">
			    <thead>
			        <tr>
						<th width="20"><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this)"></th>
			            <th width="5">
			                <?php echo JText::_( 'ID' ); ?>
			            </th>
			            <th>
			                <?php echo JText::_( 'COM_PBBOOKING_SERVICE_NAME' ); ?></th>
			            <th><?php echo JText::_( 'COM_PBBOOKING_SERVICE_DURATION' ); ?></th>
			            <th><?php echo JText::_( 'COM_PBBOOKING_SERVICE_PRICE' ); ?></th>
			        </tr>            
			    </thead>
				<?php
				$k = 0;
			   for ($i=0, $n=count( $this->treatments ); $i < $n; $i++)
			    {
			        $row =& $this->treatments[$i];
			        $checked    = JHTML::_( 'grid.id', $i, $row['id'] );
			        $link = JRoute::_( 'index.php?option=com_pbbooking&controller=treatment&task=edit&cid[]='. $row['id'] );

			        ?> 
			        <tr class="<?php echo "row$k"; ?>">
			            <td><?php echo $checked;?></td>
						<td>
			                <?php echo $row['id']; ?>
			            </td>
			       
		                <td><?php echo $row['name']; ?></td>
						<td><?php echo $row['duration']; ?></td>
						<td><?php echo $row['price']; ?></td>

			        
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
		<input type="hidden" name="controller" value="treatment" />

	</form>

	</div>
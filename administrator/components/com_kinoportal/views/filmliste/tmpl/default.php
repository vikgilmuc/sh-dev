<?php defined('_JEXEC') or die; 
JHtml::_('behavior.multiselect');
?>

<form action="<?php echo JRoute::_('index.php?option=com_kinoportal'); ?>" method="post" name="adminForm" id="adminForm">
	
	<?php echo $this->seiten->getLimitBox(); ?>
	
	<table class="table table-striped" >
		<!-- Kopfzeile -->
		<thead>
			<tr>
				<th> 
					<input type="checkbox" name="checkall-toggle" value="" onclick="Joomla.checkAll(this)" />
				</th>
				<th><?php echo JText::_('COM_KINOPORTAL_FILMLISTE_FILM'); ?></th>
				<th><?php echo JText::_('COM_KINOPORTAL_FILMLISTE_KOSTEN'); ?></th>
				<th><?php echo JText::_('COM_KINOPORTAL_FILMLISTE_ID'); ?></th>
			</tr>
		</thead>

		<!-- Hauptbereich mit den eigentlichen Inhalten -->
		<tbody>
			<?php foreach($this->filme as $i => $film): ?>
				<tr class="row<?php echo $i % 2; ?>">
					<td>
						<?php echo JHtml::_('grid.id', $i, $film->id); ?>
					</td>
					<td>
						<?php echo $film->name; ?>
					</td>
					<td>
						<?php echo $film->kosten; ?>
					</td>
					<td>
						<?php echo $film->id; ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	
		<!-- Fußzeile -->
		<tfoot>
			<tr>
				<td>
					<?php echo $this->seiten->getListFooter(); ?>	
				</td>
			</tr>
		</tfoot>
	</table>
	
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo JHtml::_('form.token'); ?>
</form>
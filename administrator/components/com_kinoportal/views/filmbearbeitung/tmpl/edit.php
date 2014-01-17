<?php defined('_JEXEC') or die; 

JHtml::_('behavior.tooltip'); // Tooltips in Kästchen anzeigen
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		Joomla.submitform(task, document.getElementById('kinoportal-form'));
	}
</script>
<form action="<?php echo JRoute::_('index.php?option=com_kinoportal&layout=edit&id='.(int) $this->film->id); ?>" method="post" name="adminForm" id="kinoportal-form">

	<fieldset>
		<legend><?php echo JText::_( 'COM_KINOPORTAL_FILMBEARBEITUNG_LEGENDE' ); ?></legend>	
		<ul>
			<li>
				<?php echo $this->form->getLabel('name'); ?>
				<?php echo $this->form->getInput('name'); ?>
			</li>
			<li>
				<?php echo $this->form->getLabel('kosten'); ?>
				<?php echo $this->form->getInput('kosten'); ?>
			</li>	
		</ul>
	</fieldset>
	
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>

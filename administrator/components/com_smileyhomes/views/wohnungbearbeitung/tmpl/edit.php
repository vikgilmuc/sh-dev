<?php defined('_JEXEC') or die; 

JHtml::_('behavior.tooltip'); // Tooltips in Kästchen anzeigen
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		Joomla.submitform(task, document.getElementById('smileyhomes-form'));
	}
</script>
<form action="<?php echo JRoute::_('index.php?option=com_smileyhomes&layout=edit&id='.(int) $this->wohnung->id); ?>" method="post" name="adminForm" id="smileyhomes-form">

	<fieldset>
		<legend><?php echo JText::_( 'COM_SMILEYHOMES_WOHNUNGBEARBEITUNG_LEGENDE' ); ?></legend>	
		<ul>
			<li>
				<?php echo $this->form->getLabel('name'); ?>
				<?php echo $this->form->getInput('name'); ?>
			</li>
			<li>
				<?php echo $this->form->getLabel('meter'); ?>
				<?php echo $this->form->getInput('meter'); ?>
			</li>	
		</ul>
	</fieldset>
	
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>

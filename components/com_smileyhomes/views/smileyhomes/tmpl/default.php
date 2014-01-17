<?php defined('_JEXEC') or die; ?>



<div id="wohnungen">
<div class="row">
<div class="col-md-10 col-md-offset-2" style="min-height:500px">

<h1><?php echo JText::_('Wohnungen'); ?></h1>
<table>
	<tr>
		<td><h1><?php echo JText::_('Wohnung'); ?></h1></td>
		<td><h1><?php echo JText::_('Meter'); ?></h1></td>
	</tr>
	
	<?php
	foreach($this->allewohnungen as $wohnung)
	{
		echo '<tr>';
		echo '<td><h2>' . $wohnung->name . '</h2></td>';
		echo '<td><h2>' . $wohnung->meter . '</h2></td>';
		echo '</tr>';
	}
	?>
</h1>
</table>
</div>
</div>
</div>

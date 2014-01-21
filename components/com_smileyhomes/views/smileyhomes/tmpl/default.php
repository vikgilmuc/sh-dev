<?php defined('_JEXEC') or die; ?>



<div id="wohnungen">
<div class="row" >
<div class="col-md-10" style="min-height:500px; float: none; margin: 0 auto; padding:20px">

<h1><?php echo JText::_(''); ?></h1>
<table class="table-striped table-bordered" >
	<tr>
		<td><h1><?php echo JText::_('Wohnung'); ?></h1></td>
		<td><h1><?php echo JText::_('Meter'); ?></h1></td>
		<td><h1><?php echo JText::_('Garten'); ?></h1></td>
		<td><h1><?php echo JText::_('Balkon'); ?></h1></td>
		<td><h1><?php echo JText::_('Terrasse'); ?></h1></td>
		<td><h1><?php echo JText::_('Plan'); ?></h1></td>
		<td><h1><?php echo JText::_('Fotos'); ?></h1></td>
		<td><h1><?php echo JText::_('Frei'); ?></h1></td>
	</tr>
	
	<?php
	foreach($this->allewohnungen as $wohnung)
	{
		echo '<tr>';
		echo '<td><h2>' . $wohnung->name . '</h2></td>';
		echo '<td><h2>' . $wohnung->meter . '</h2></td>';
		echo '<td><h2>';if($wohnung->G=='1'){echo'<img src='.(JURI::base()).'images/tick.gif>';}echo'</h2></td>';
		echo '<td><h2>';if($wohnung->B=='1'){echo'<img src='.(JURI::base()).'images/tick.gif>';}echo'</h2></td>';
		echo '<td><h2>';if($wohnung->T=='1'){echo'<img src='.(JURI::base()).'images/tick.gif>';}echo'</h2></td>';
		echo '<td><h2><a href='.(JURI::base()).'images/plaene/'. $wohnung->name .'.jpg>plan</a></h2></td>';
		echo '<td><h2><a href=' . $wohnung->name . '>fotos</a></td>';
		echo '<td><h2>';if($wohnung->frei=='1'){echo'<img src='.(JURI::base()).'images/tick.gif>';}echo'</h2></td>';
		echo '</tr>';
	}
	?>
</h1>
</table>
</div>
</div>
</div>

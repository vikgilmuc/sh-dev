<?php defined('_JEXEC') or die; ?>



<div id="wohnungen">
<div class="row" >
<div class="col-md-12" style="min-height:500px; float: none; margin: 0 auto; padding:20px">

<h1><?php echo JText::_(''); ?></h1>
<table class="table-striped table-bordered" >
	<tr>
		<td><h3><?php echo JText::_('Wohnung'); ?></h3></td>
		<td><h3><?php echo JText::_('Typ'); ?></h3></td>
		<td><h3><?php echo JText::_('Ebene'); ?></h3></td>
		<td><h3><?php echo JText::_('Meter'); ?></h3></td>
		<td><h3><?php echo JText::_('Garten'); ?></h3></td>
		<td><h3><?php echo JText::_('Balkon'); ?></h3></td>
		<td><h3><?php echo JText::_('Terrasse'); ?></h3></td>
		<td><h3><?php echo JText::_('Frei'); ?></h3></td>
	</tr>
	
	<?php
	foreach($this->allewohnungen as $wohnung)
	{
		echo '<tr>';
		echo '<td><p>' . $wohnung->name . '</p></td>';
		echo '<td><p>' . $wohnung->typ . '</p></td>';
		echo '<td><p>' . $wohnung->ebene . '</p></td>';
		echo '<td><p>' . $wohnung->meter . '</p></td>';
		echo '<td><p>';if($wohnung->G=='1'){echo'<img src='.(JURI::base()).'images/tick.gif>';}echo'</p></td>';
		echo '<td><p>';if($wohnung->B=='1'){echo'<img src='.(JURI::base()).'images/tick.gif>';}echo'</p></td>';
		echo '<td><p>';if($wohnung->T=='1'){echo'<img src='.(JURI::base()).'images/tick.gif>';}echo'</p></td>';
		//echo '<td><p><a href='.(JURI::base()).'images/plaene/'. $wohnung->name .'.jpg>plan</a></p></td>';
		//echo '<td><p><a href=' . $wohnung->name . '>fotos</a></td>';
		echo '<td><p>';if($wohnung->frei=='1'){echo'<img src='.(JURI::base()).'images/tick.gif>';}echo'</p></td>';
		echo '</tr>';
	}
	?>
</h1>
</table>
</div>
</div>
</div>

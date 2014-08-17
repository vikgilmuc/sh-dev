			<?php defined('_JEXEC') or die; ?>



<div id="wohnungen">
<div class="row" >
<div class="col-md-12" style="min-height:500px; float: none; margin: 0 auto; padding:20px">

<h1><?php echo JText::_(''); ?></h1>
<table class="table-striped table-bordered" >
	<tr>
		<td><h3 class="centriert"><?php echo JText::_('Wohnung'); ?></h3></td>
		<td><h3 class="centriert"><?php echo JText::_('Typ'); ?></h3></td>
		<td><h3 class="centriert"><?php echo JText::_('Grundis-')?><br/><?php echo JText::_('plan'); ?></h3></td>
		<td><h3><?php echo JText::_('Stock'); ?></h3></td>
		<td><h3 class="centriert"><?php echo JText::_('Qm'); ?></h3></td>
		<td><h3><?php echo JText::_('Garten')?><br/><?php echo JText::_('/QM'); ?></h3></td>
		<td><h3>Balkon<br/>/QM</h3></td>	
		<td><h3>Terrasse<br/>/QM</h3></td>
		<td><h3><?php echo JText::_('Frei'); ?></h3></td>
	</tr>
	<?php 
$user = JFactory::getUser();
$status = $user->guest;

if($status == 1){
echo("guest");
}
else
{ 
echo("registered");
}   
?>
	<?php
	foreach($this->allewohnungen as $wohnung)
	{
		echo '<tr>';
		echo '<td><p>' . $wohnung->name . '</p></td>';
		echo '<td><p>' . $wohnung->typ . '</p></td>';
		echo '<td>';
		if (file_exists ('images/plaene/'.$wohnung->name.'.jpg')) {
		if($status == 1){echo '<p class="centriert"><a class="plan" href="images/plaene/'.$wohnung->name.'.jpg" rel="fancied">register to see</a>';}
        else 
        {echo '<p class="centriert"><a class="plan" href="images/plaene/'.$wohnung->name.'.jpg" rel="fancied">link</a>';
        }};
		echo '</p></td>';
		echo '<td><p>' . $wohnung->ebene . '</p></td>';
		echo '<td><p>' . $wohnung->meter . '</p></td>';
		//echo '<td><p>' . $wohnung->meter . '</p></td>';
		echo '<td><p class="centriert">';if($wohnung->G=='1'){echo'<img src='.(JURI::base()).'images/tick.png>';}if($wohnung->G=='1'){echo($wohnung->garten_meter);}echo'</p></td>';
		echo '<td><p class="centriert">';if($wohnung->B=='1'){echo'<img src='.(JURI::base()).'images/tick.png>';}if($wohnung->B=='1'){echo($wohnung->balkon_meter);}echo'</p></td>';
	
		echo '<td><p class="centriert">';if($wohnung->T=='1'){echo'<img src='.(JURI::base()).'images/tick.png>';}if($wohnung->T=='1'){echo($wohnung->terrasse_meter);}echo'</p></td>';
		//echo '<td><p><a href='.(JURI::base()).'images/plaene/'. $wohnung->name .'.jpg>plan</a></p></td>';
		//echo '<td><p><a href=' . $wohnung->name . '>fotos</a></td>';
		echo '<td><p class="centriert">';if($wohnung->frei=='1'){echo'<img src='.(JURI::base()).'images/tick.png>';}echo'</p></td>';
		echo '</tr>'; 
	}
	?>
</h1>
</table>
</div>
</div>
</div>

<?php defined('_JEXEC') or die; ?>

<h1><?php echo JText::_('COM_KINOPORTAL_TITEL'); ?></h1>
<p>
	<?php echo JText::_('COM_KINOPORTAL_UNTERTITEL'); ?>
	<a href="http://en.wikipedia.org/wiki/List_of_most_expensive_films">Wikipedia</a>
</p>

<table>
	<tr>
		<td><?php echo JText::_('COM_KINOPORTAL_FILMNAME'); ?></td>
		<td><?php echo JText::_('COM_KINOPORTAL_KOSTEN'); ?></td>
	</tr>
	
	<?php
	foreach($this->allefilme as $film)
	{
		echo '<tr>';
		echo '<td>' . $film->name . '</td>';
		echo '<td>' . $film->kosten . '</td>';
		echo '</tr>';
	}
	?>

</table>

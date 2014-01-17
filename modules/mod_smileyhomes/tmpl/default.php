<?php 
defined('_JEXEC') or die;
?>
<div id="wohnungen">
<div class="row">
<div class="col-md-12 col-md-offset-2">

<?php 
// Jeden Wohnungen in einer Liste ausgeben:
echo "<ul>";
if ($allewohnungen)
{
	foreach ($allewohnungen as $einwohnung)
	{
		?><li><h2><a href="index.php?option=com_smileyhomes"><?php
		echo $einwohnung->name;?>-----<?php
		echo $einwohnung->meter;
		?></a></h2></li><?php
	}
}
echo "</ul>";?>
</div>
</div>
</div>


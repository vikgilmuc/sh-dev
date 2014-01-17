<?php
/**
 * @copyright	submit-templates.com
 * @license		GNU General Public License version 2 or later;
 */

// no direct access
defined('_JEXEC') or die;

$id = uniqid();
$document = JFactory::getDocument();
$document->addScriptDeclaration('
	jQuery.noConflict();
	(function($){
		$(document).ready(function(){
			$(".st-content-tabs ul.nav li a:first").tab("show");	
		});
	})(jQuery);
');
?>
<div class="accordion st-content-tabs" id="<?php echo $id; ?>">
	<ul class="nav nav-tabs">
	<?php foreach($list as $k => $item): ?>
		<li><a href="#<?php echo $id.$k ?>" data-toggle="tab"><?php echo $item->title; ?></a></li>
	<?php endforeach; ?>
	</ul>
	<div class="tab-content">
	<?php foreach($list as $k => $item): ?>
		<div class="tab-pane" id="<?php echo $id.$k ?>">
			<div class="content"><?php echo ($params->get('introtext_length') > 0) ? substr(strip_tags($item->introtext), 0 , $params->get('introtext_length')) : $item->introtext; ?></div>
		</div>
	<?php endforeach; ?>
	</div>
</div>
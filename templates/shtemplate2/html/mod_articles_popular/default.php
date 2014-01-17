<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_popular
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<div class="mostread<?php echo $moduleclass_sfx; ?>">
<?php foreach ($list as $item) : ?>
	<div>
		<a href="<?php echo $item->link; ?>">
			<?php echo $item->title; ?></a>
	</div>
<?php endforeach; ?>
</div>

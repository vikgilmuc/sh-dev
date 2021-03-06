<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;
JLoader::register('TagsHelperRoute', JPATH_BASE . '/components/com_tags/helpers/route.php');
$blockPosition = $displayData['params']->get('info_block_position', 0);

?>
	<dl class="article-info  muted">

		<?php if ($displayData['position'] == 'above' && ($blockPosition == 0 || $blockPosition == 2)
				|| $displayData['position'] == 'below' && ($blockPosition == 1)
				) : ?>
			
			<?php if ($displayData['params']->get('show_create_date')) : ?>
				<dd class="create">
					<?php echo JText::sprintf(JHtml::_('date', $displayData['item']->created, JText::_('DATE_FORMAT_LC3'))); ?>
					<span class="separator">&#47;</span>
				</dd>
			<?php endif; ?>


			<?php if ($displayData['params']->get('show_author') && !empty($displayData['item']->author )) : ?>
				
				<dd class="createdby">
					<?php $author = $displayData['item']->author; ?>
					<?php $author = ($displayData['item']->created_by_alias ? $displayData['item']->created_by_alias : $author); ?>
					<?php if (!empty($displayData['item']->contactid ) && $displayData['params']->get('link_author') == true) : ?>
						<?php
						echo JText::sprintf('by ',
							JHtml::_('link', JRoute::_('index.php?option=com_contact&view=contact&id='.$displayData['item']->contactid), $author)
						); ?>
					<?php else :?>
						<?php echo 'by '.$author; ?>
					<?php endif; ?>
					<span class="separator">&#47;</span>
				</dd>
				
					
			<?php endif; ?>

			<?php if ($displayData['params']->get('show_parent_category') && !empty($displayData['item']->parent_slug)) : ?>
				<?php echo JLayoutHelper::render('joomla.content.info_block.parent_category', $displayData); ?>
			<?php endif; ?>

			<?php if ($displayData['params']->get('show_category')) : ?>
				<?php echo JLayoutHelper::render('joomla.content.info_block.category', $displayData); ?>
			<?php endif; ?>

			<?php if ($displayData['params']->get('show_publish_date')) : ?>
				<?php echo JLayoutHelper::render('joomla.content.info_block.publish_date', $displayData); ?>
			<?php endif; ?>
		<?php endif; ?>

		<?php if ($displayData['position'] == 'above' && ($blockPosition == 0)
				|| $displayData['position'] == 'below' && ($blockPosition == 1 || $blockPosition == 2)
				) : ?>
			

			<?php if ($displayData['params']->get('show_modify_date')) : ?>
				<?php echo JLayoutHelper::render('joomla.content.info_block.modify_date', $displayData); ?>
			<?php endif; ?>

			<?php if ($displayData['params']->get('show_hits')) : ?>
				<?php echo JLayoutHelper::render('joomla.content.info_block.hits', $displayData); ?>
			<?php endif; ?>
			
			
	
			<!-- Show tags in blog -->
			
			
			
		<?php endif; ?>
	</dl>

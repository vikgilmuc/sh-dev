<?php 

/**
* @package		PurpleBeanie.PBBooking
* @license		GNU General Public License version 2 or later; see LICENSE.txt
* @link		http://www.purplebeanie.com
*/

defined('_JEXEC') or die('Restricted access'); 

?>


<div class="bootstrap-wrap">

<form action="<?php echo JRoute::_('index.php?option=com_content&view=articles');?>" method="post" name="adminForm" id="adminForm">
	<div class="row-fluid">
		
		<!-- Begin Content -->
		<div class="span6">
			<div class="well well-small">
				<div class="module-title nav-header"><?php echo JText::_('COM_PBBOOKING_DASHBOARD_UPCOMING_BOOKINGS');?></div>
				<div class="row-striped">
					<?php if (count($this->upcoming_events)>0) :?>
						<?php foreach ($this->upcoming_events as $event) :?>
							<div class="row-fluid">
								<div class="span9"><strong class="row-title"><a href="<?php echo JURI::root(false);?>administrator/index.php?option=com_pbbooking&controller=manage&task=edit&id=<?php echo $event->id;?>"><?php echo $event->summary;?></a></strong></div>
								<div class="span3"><i class="icon-calendar"></i>&nbsp;<?php echo JHtml::_('date',date_create($event->dtstart,new DateTimeZone(PBBOOKING_TIMEZONE))->format(DATE_ATOM),JText::_('COM_PBBOOKING_DASHBOARD_DTFORMAT'));?></div>
							</div>
						<?php endforeach;?>
					<?php else:?>
						<div class="row-fluid">
							<div class="span12"><strong class="row-title"><?php echo JText::_('COM_PBBOOKING_DASHBOARD_NOTHING_FOUND');?></strong></div>
						</div>	
					<?php endif;?>
				</div>
			</div>

			
		</div>


		<div class="span6">
			<div class="well well-small">
				<div class="module-title nav-header"><?php echo JText::_('COM_PBBOOKING_DASHBOARD_LATEST_PENDING_BOOKINGS');?></div>
				<div class="row-striped">
					<?php foreach ($this->pending_events as $event):?>
						<div class="row-fluid">
							<div class="span5"><?php echo $event->email;?> - <?php echo JText::_('COM_PBBOOKING_PENDING_EVENT_ID');?> <?php echo $event->id;?></div>
							<div class="span3"><i class="icon-calendar"></i> <?php echo JHtml::_('date',date_create($event->dtstart,new DateTimeZone(PBBOOKING_TIMEZONE))->format(DATE_ATOM),JText::_('COM_PBBOOKING_DASHBOARD_DTFORMAT'));?></div>
							<div class="span4"><?php echo $event->name;?></div>
						</div>
					<?php endforeach;?>
				</div>
			</div>

			
		</div>

		<!-- End Content -->
	</div>



	<div class="row-fluid">
		<div class="span6">
			<div class="well well-small">
				<div class="module-title nav-header"><?php echo JText::_('COM_PBBOOKING_DASHBOARD_CALENDAR_UTILIZATION_CURRENT_WEEK');?></div>

				<div class="row-striped">
					<?php foreach ($this->cals as $cal) :?>
						<div class="row-fluid">
							<div class="span9"><?php echo $cal->name;?></div>
							<div class="span3"><?php echo sprintf('%0.2f',$cal->get_calendar_utilization($this->dtstart,$this->dtend));?>%
</div>
						</div>
					<?php endforeach;?>
				</div>
			</div>
		</div>
	</div>


	<div class="row-fluid">
		<div class="span12">
			<div class="well well-small">
				<div class="module-title nav-header"><?php echo JText::_('COM_PBBOOKING_DASHBOARD_ANNOUNCEMENTS');?></div>
					<div class="row-striped">
					<?php foreach ($this->announcements as $announcement) :?>
					<div class="row-fluid">
						<div class="span12">
							<strong><?php echo $announcement->get_title();?></strong>
						</div>
					</div>
					<div class="row-fluid">
						<div class="span12">
							<?php echo $announcement->get_description();?>
							<p>
								<hr style="width:90%;margin:0px auto;color:#F0F0EE;"/>
							</p>
							<i><?php echo JText::_('COM_PBBOOKING_DASHBOARD_ANNOUNCEMENT_POSTED');?> <?php echo $announcement->get_date();?></i>
						</div>
					</div>

					<?php endforeach;?>
				</div>
			</div>
		</div>
	</div>




	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php //echo $listOrder; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php //echo $listDirn; ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>

</div>
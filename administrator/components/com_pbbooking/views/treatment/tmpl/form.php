<?php 

/**
* @package      PurpleBeanie.PBBooking
* @license      GNU General Public License version 2 or later; see LICENSE.txt
* @link     http://www.purplebeanie.com
*/

defined('_JEXEC') or die('Restricted access'); 

?>

<style>

.error-message {
    border:1px solid red;
    background-color:#FBE6F2;
    width:250px;
    margin: 10px auto 10px auto;
    padding:5px;
    text-align:center;
    clear:both;
}

</style>

<script><!--

window.addEvent('domready',function(){
    
    $('adminForm').addEvent('submit',function(evt){
        
        if ($$('select[name=linked_calendar[]]').getProperty('value') == 0 && $$('input[name=task]').getProperty('value') != 'display') {

            $('select-linked_calendar-error').setProperty('html','<p><?php echo JText::_("COM_PBBOOKING_SERVICE_MISSING_CALENDAR");?><p>').addClass('error-message');

            evt.stop();    
        }
    })


})

//--></script>


<div class="bootstrap-wrap">

 
<div class="row-fluid">


    <div class="span12">

        <ul class="nav nav-tabs">
            <li class="active"><a href="#service-details" data-toggle="tab"><?php echo Jtext::_('COM_PBBOOKING_DETAILS');?></a></li>
        </ul>
        <div class="tab-content">

            <form action="index.php" method="GET" name="adminForm" id="adminForm" class="form-horizontal">
                <div class="tab-pane active" id="service-details">
                    <div class="control-group">
                        <label class="control-label"><?php echo JText::_( 'COM_PBBOOKING_SERVICE_NAME' ); ?>:</label>
                        <div class="controls"><input class="input-xlarge" type="text" name="name" id="name" size="32" maxlength="250" value="<?php echo $this->treatment['name'];?>" /></div>
                    </div>
                    <div class="control-group">
                        <label class="control-label"><?php echo JText::_( 'COM_PBBOOKING_SERVICE_DURATION' ); ?>:</label>
                        <div class="controls"><input class="input-xlarge" type="text" name="duration" id="duration" size="32" maxlength="250" value="<?php echo $this->treatment['duration'];?>" /></div>
                    </div>
                    <div class="control-group">
                        <label class="control-label"><?php echo JText::_( 'COM_PBBOOKING_SERVICE_PRICE' ); ?>:</label>
                        <div class="controls"><input class="input-xlarge" type="text" name="price" id="price" size="32" maxlength="250" value="<?php echo $this->treatment['price'];?>" /></div>
                    </div>
                 
            </div>
             
            <div class="clr"></div>
             
            <input type="hidden" name="option" value="com_pbbooking" />
            <input type="hidden" name="id" value="<?php echo $this->treatment['id']; ?>" />
            <input type="hidden" name="task" value="save" />
            <input type="hidden" name="controller" value="treatment" />
            </form>
        </div>

    </div>


</div>

</div>
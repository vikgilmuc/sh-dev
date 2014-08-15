<?php 

defined('_JEXEC') or die('Restricted access'); 

/**
* @package      PurpleBeanie.PBBooking
* @license      GNU General Public License version 2 or later; see LICENSE.txt
* @link     http://www.purplebeanie.com
*/

?> 



<div class="bootstrap-wrap">



<form action="index.php" method="GET" name="adminForm" id="adminForm">
<div class="row-fluid">





    <div class="span12">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#customfield-details" data-toggle="tab"><?php echo Jtext::_('COM_PBBOOKING_DETAILS');?></a></li>
    </ul>



    <div class="tab-content">
        <div class="tab-pane active" id="customfield-details">
        <table class="admintable">
        <tr>
            <td width="100" align="right" class="key">
                <label for="fieldname">
                    <?php echo JText::_( 'COM_PBBOOKING_FIELDS_NAME' ); ?>:
                </label>
            </td>
            <td>
                <input class="text_area" type="text" name="fieldname" id="fieldname" size="64" maxlength="250" value="<?php echo $this->customfield['fieldname'];?>" />
            </td>
        </tr>
        <tr>
            <td width="100" align="right" class="key">
                <label for="fieldtype">
                    <?php echo JText::_( 'COM_PBBOOKING_FIELDS_TYPE' ); ?>:
                </label>
            </td>
            <td>
                <select name="fieldtype" id="fieldtype">
                    <option value="0"><?php echo JText::_('COM_PBBOOKING_SELECT_FIELD');?></option>
                    <?php foreach (array('text'=>'Textfield','select'=>'Selectbox','checkbox'=>'Checkbox','radio'=>'Radio','textarea'=>'Textarea') as $k=>$v) :?>
                        <option value="<?php echo $k;?>" <?php echo ($this->customfield['fieldtype'] == $k) ? 'selected' : '';?> ><?php echo $v;?></option>
                    <?php endforeach;?>
                </select>
            </td>
        </tr>
        <tr>
            <td width="100" align="right" class="key">
                <label for="varname">
                    <?php echo JText::_( 'COM_PBBOOKING_FIELDS_VARNAME' ); ?>:
                </label>
            </td>
            <td>
                <input class="text_area" type="text" name="varname" id="varname" size="32" maxlength="250" value="<?php echo $this->customfield['varname'];?>" />
            </td>
        </tr>

        <tr>
            <td width="100" align="right" class="key">
                <label for="size">
                    <?php echo JText::_( 'COM_PBBOOKING_FIELDS_SIZE' ); ?>:
                </label>
            </td>
            <td>
                <input class="text_area" type="text" name="size" id="size" size="32" maxlength="250" value="<?php echo $this->customfield['size'];?>" />
            </td>
        </tr>
        <tr>
            <td width="100" align="right" class="key">
                <label for="is_email">
                    <?php echo JText::_( 'COM_PBBOOKING_IS_EMAIL' ); ?>:
                </label>
            </td>
            <td>
                <input type="checkbox"  name="is_email" id="size" size="32" maxlength="250" value="1" <?php if ($this->customfield['is_email'] == 1) : ?>checked <?php endif; ?> />
            </td>
        </tr>
        
        <tr>
            <td width="100" align="right" class="key">
                <label for="is_first_name">
                    <?php echo JText::_( 'COM_PBBOOKING_IS_FIRST_NAME' ); ?>:
                </label>
            </td>
            <td>
                <input type="checkbox"  name="is_first_name" id="size" size="32" maxlength="250" value="1" <?php if ($this->customfield['is_first_name'] == 1) : ?>checked <?php endif; ?> />
            </td>
        </tr>
        
        
        
        <tr>
            <td width="100" align="right" class="key">
                <label for="is_last_name">
                    <?php echo JText::_( 'COM_PBBOOKING_IS_LAST_NAME' ); ?>:
                </label>
            </td>
            <td>
                <input type="checkbox"  name="is_last_name" id="size" size="32" maxlength="250" value="1" <?php if ($this->customfield['is_last_name'] == 1) : ?>checked <?php endif; ?> />
            </td>
        </tr>
        
        <tr>
            <td width="100" align="right" class="key">
                <label for="is_required">
                    <?php echo JText::_( 'COM_PBBOOKING_IS_REQUIRED' ); ?>:
                </label>
            </td>
            <td>
                <input type="checkbox"  name="is_required" id="size" size="32" maxlength="250" value="1" <?php if ($this->customfield['is_required'] == 1) : ?>checked <?php endif; ?> />
            </td>
        </tr>
        
        <tr>
            <td width="100" align="right" class="key">
                <label for="values">
                    <?php echo JText::_( 'COM_PBBOOKING_FIELD_VALUES' ); ?>:
                </label>
            </td>
            <td>
                <textarea  name="values" id="values"><?php echo $this->customfield['values'];?></textarea>
            </td>
        </tr>
    </table>
</div>
</div>
</div>
 
<div class="clr"></div>
 
<input type="hidden" name="option" value="com_pbbooking" />
<input type="hidden" name="id" value="<?php echo $this->customfield['id']; ?>" />
<input type="hidden" name="task" value="save" />
<input type="hidden" name="controller" value="customfields" />
</form>


</div>
<?php
/**
* @Copyright Copyright (C) 2010- xml/swf
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/
?>

<?php defined('_JEXEC') or die('Restricted access'); ?>
 <?php

if (!function_exists('chIfColor')) {
function chIfColor($color_str, $only_digit = true)
{
	if ($only_digit) {
		if (strlen($color_str) == 6 && strspn($color_str, "0123456789abcdefABCDEF") == 6) {
			return true;
		} else {
			return false;
		}
	} else {
		if (strlen($color_str) == 7 && strspn($color_str, "#0123456789abcdefABCDEF") == 7) {
			return true;
		} else {
			return false;
		}
	}
}
}

$version = new JVersion;
$jver = $version->getShortVersion();

$document =& JFactory::getDocument();
$j_root = JURI::root();
$sb_path = $j_root.'components/com_dreamworkgallery/sb/';
$document->addScript($sb_path . 'jqmin.js');
$document->addStyleSheet($j_root .'administrator/components/com_dreamworkgallery/libs/bootstrap/css/bootstrap.min.css', 'text/css');

$tpl_tab_styles = '<style type="text/css">
ul#tmpltabs{
	margin: 0px;
	padding: 0px;
}
ul#tmpltabs li{
	display: inline-block;
	width:45%;
	height: 32px;
	text-align: center;
	vertical-align: middle;
	font-size: 16px;
	margin: 0px;
	padding: 0px;
}
ul#tmpltabs li span{
	vertical-align: middle;
	margin: 0px;
	padding: 0px;
}
li.atab {
	background-color: #F4F4F4;
	color: #333333;
	border-top-style:solid;
}

li.natab {
	background-color:#888888;
	color: #000000;
	cursor: pointer;
	border-style:solid;
	border-width:1px;
}
</style>';
$document->addCustomTag($tpl_tab_styles);
$tpl_tab_js = '<script type="text/javascript">
var startPTRzero = 0;
function PrepareTrsp(litm_id)
{
	if ((litm_id==0 && startPTRzero==0) || litm_id > 0) {
	startPTRzero = 1;
	if (litm_id == 0) {
		jQuery("#preptrspid") . html("'.JText::_('Please wait until PicSell prepare all thumbs').' <img src=\\"'.$sb_path.'misc/preload_img.gif\\" />");
	}
	jQuery.get("'.$j_root.'administrator/", {option: "com_dreamworkgallery", controller: "templates", task: "prepthrsp", litmid: litm_id},
	   function(data){
	   if (/^\+?[1-9]\d*$/.test(data)) {
		PrepareTrsp(data);
	   } else {
		jQuery("#preptrspid") . html(data);
	   }
	   });
	}
}
</script>';
$document->addCustomTag($tpl_tab_js);

if(version_compare(JVERSION, '3.0.0', 'ge')) {
	$params_comg = JComponentHelper::getParams('com_dreamworkgallery', true);
} else {
	$componentstr = &JComponentHelper::getComponent('com_dreamworkgallery');
	$params_comg = new JParameter($componentstr->params);
}

?>

<div id="tdiv2" style="background-color: #F4F4F4;">
<form action="index.php" method="post" enctype="multipart/form-data" name="adminForm" id="adminForm">
<br/>
<br/>
<div class="col100">
        <table class="admintable" width="100%">
<tr>
<td width="100%" align="right" class="key" colspan="2">
<?php echo JText::_( 'Please before to click this button first save Responsive Thumbnail Base Width and Height final values.' ); ?>
</td>
</tr>
<tr>
  <td width="75%" align="right" class="key">

  </td>
  <td>
   <?php
$settings_fields = '<input type="button" value="'.JText::_( 'Prepare the thumbs for Responsive template' ).'" onclick="PrepareTrsp(0);" />';
echo $settings_fields;
   ?>
  </td>
</tr>
<tr>
<td width="100%" align="right" class="key" colspan="2">
<span id="preptrspid">
<?php 
$rsplastwh = $params_comg->get('rsplastwh', '');
if ($rsplastwh != $params_comg->get('thumbnailBaseWidth', '180')."x".$params_comg->get('thumbnailBaseHeight', '180')) {
	echo JText::_( '<font color=\'ff9999\'>Responsive settings for thumb width-height were changed. Please click to prepare thumbs again.</font>' );
} else {
	echo JText::_( 'If the thumbs are not properly showed on the front end, please, click the button.' );
}
?>
</span>
</td>
</tr>
		<tr>
            <td width="75%" align="right" class="key">
                <label for="activate_resp_tpl">
                    <?php echo JText::_( 'Activate Responsive Template' ); ?>:
                </label>
            </td>
            <td>
                <?php
$activate_resp_tpl = (isset($_POST['activate_resp_tpl']) && in_array($_POST['activate_resp_tpl'], array("yes", "no"))) ? $_POST['activate_resp_tpl'] : $params_comg->get('activate_resp_tpl', 'yes');
$settings_fields = '
<select id="activate_resp_tpl" class="inputbox" size="1" name="activate_resp_tpl">
	<option '.(("yes" == $activate_resp_tpl) ? 'selected="selected"' : '').' value="yes" >'.JText::_('Yes').'</option>
	<option '.(("no" == $activate_resp_tpl) ? 'selected="selected"' : '').' value="no" >'.JText::_('No').'</option>
</select>
';
echo $settings_fields;
				?>
            </td>
        </tr>
		
		<tr>
            <td width="75%" align="right" class="key">
                <label for="resize_type" title="<?php echo JText::_( 'Image is resized to the thumb sizes given in Thumbnail Base Width/Height. If one size of generated thumb is bigger than the value in PicSell parameters(due to different w/h ratio of image and thumb) an additional resize is applied: (Truncate- cutting equal number of pixels from both sides to fit the PicSell value for thumb size)(Distort- The image ratio is not preserved and image is distorted.)' ); ?>">
                    <?php echo JText::_( 'Thumbs resize type' ); ?>:
                </label>
            </td>
            <td>
                <?php
$resize_type = (isset($_POST['resize_type']) && in_array($_POST['resize_type'], array("1", "2"))) ? $_POST['resize_type'] : $params_comg->get('resize_type', '2');
$settings_fields = '
<select id="resize_type" class="inputbox" size="1" name="resize_type">
	<option '.(("1" == $resize_type) ? 'selected="selected"' : '').' value="1" >'.JText::_('Distort').'</option>
	<option '.(("2" == $resize_type) ? 'selected="selected"' : '').' value="2" >'.JText::_('Truncate').'</option>
</select>
';
echo $settings_fields;
				?>
            </td>
        </tr>
<tr>
            <td width="75%" align="right" class="key">
                <label for="thumbnailBaseWidth">
                    <?php echo JText::_( 'Thumbnail Base Width' ); ?>:
                </label>
            </td>
            <td>
                <?php
$thumbnailBaseWidth = (isset($_POST['thumbnailBaseWidth']) && is_numeric($_POST['thumbnailBaseWidth'])) ? $_POST['thumbnailBaseWidth'] : $params_comg->get('thumbnailBaseWidth', '180');
$settings_fields = '<input type="text" name="thumbnailBaseWidth" id="thumbnailBaseWidth" size="8" maxlength="8" value="'.$thumbnailBaseWidth.'" />';
echo $settings_fields;
				?>
            </td>
</tr>

<tr>
  <td width="75%" align="right" class="key">
   <label for="thumbnailBaseHeight">
    <?php echo JText::_( 'Thumbnail Base Height' ); ?>:
   </label>
  </td>
  <td>
   <?php
$thumbnailBaseHeight = (isset($_POST['thumbnailBaseHeight']) && is_numeric($_POST['thumbnailBaseHeight'])) ? $_POST['thumbnailBaseHeight'] : $params_comg->get('thumbnailBaseHeight', "180");
$settings_fields = '<input type="text" name="thumbnailBaseHeight" id="thumbnailBaseHeight" size="8" maxlength="6" value="'.$thumbnailBaseHeight.'" />';
echo $settings_fields;
   ?>
  </td>
</tr>

<tr>
  <td width="75%" align="right" class="key">
  </td>
  <td><input type="submit" value="<?php echo JText::_('Save Changes'); ?>" style="font-size:18px;" /></td>
</tr>	
    </table>
</div>
</div>
<input type="hidden" name="option" value="com_dreamworkgallery" />
<input type="hidden" name="controller" value="templates" />
<input type="hidden" name="task" value="save" />
<input type="hidden" name="tplid" value="2" />
</form>
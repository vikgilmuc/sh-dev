<?php
/**
* @Copyright Copyright (C) 2011 - xml/swf
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/
defined('_JEXEC') or die('Restricted access');
if(!defined('DS')){
	define('DS',DIRECTORY_SEPARATOR);//J3 compatibility
}
/**Mobile Gallery**/
	// for joomla
	if(strstr($_SERVER['HTTP_USER_AGENT'],"iPad") || strstr($_SERVER['HTTP_USER_AGENT'],"iPhone")|| stristr($_SERVER['HTTP_USER_AGENT'], 'Android')) {
		define('PLUGINNAME', 'mod_xmlswf_gallery_dreamwork');
		require_once('mobile/mobile-joomla.php');
	}
/******************/
$params_comg = &JComponentHelper::getParams('com_dreamworkgallery');
//slide parameters
$bannerWidth           = intval($params->get( 'bannerWidth', 710 ));
$bannerHeight          = intval($params->get( 'bannerHeight', 630 ));
$backgroundColor       = intval($params->get( 'base_color', '#cccccc' ));
$wmode                 = trim($params->get( 'wmode', 'transparent' ));
//print_r($params);
if(!function_exists('GetHColor')) {
    function GetHColor($params, $tag_name, $curr_h_val = 'FFFFFF', $curr_h_sym = '#')
    {
        $curr_pinput = $params->get($tag_name, $curr_h_sym . $curr_h_val);
        if (strtolower(substr($curr_pinput, 0, 2)) == '0x') {
            $curr_hex = substr($curr_pinput, 2);
        } elseif (substr($curr_pinput, 0, 1) == '#') {
            $curr_hex = substr($curr_pinput, 1);
        } else {
            $curr_hex = $curr_pinput;
        }
        if (strspn($curr_hex, "0123456789abcdefABCDEF") == 6 && strlen($curr_hex) == 6) {
            $curr_pinput = $curr_h_sym . $curr_hex;
        } else {
            $curr_pinput = $curr_h_sym . $curr_h_val;
        }
        return $curr_pinput;
    }
}

$xml_fname    = trim($params->get( 'xml_fname', 'data' ));
$catppv_id = $xml_fname;
$modId=$module->id;
$modId = 'gal'.$modId;

if (!function_exists('create_galldreamwork_xml_file')) {
    function create_galldreamwork_xml_file($params, &$catppv_id, &$onlyvm_flag,$modId)
    {
    $database = & JFactory::getDBO();//new ps_DB();
    $cat_ids                = trim($params->get( 'category_id', '0' ));
    $add_query = '';
    if ($cat_ids && $cat_ids != 0) {
        $ids  = explode(",", $cat_ids);
        if (is_array($ids)) {
            foreach ($ids as $curr_id) {
                $add_query_arr[] = " id = " . $curr_id . " ";
            }
            $add_query = " AND " . '(' . implode("OR", $add_query_arr) . ')';
        }
    }

    if (1 == trim($params->get( 'catppv_flag', '2' ))) {
        $catppv_id .= str_replace(',', '_', $cat_ids);
    }
    $module_path = dirname(__FILE__).DS;
    if (!file_exists($module_path . 'mod_xmlswf_gallery_dreamwork'. $catppv_id . '.swf') ) {
        copy($module_path . 'mod_xmlswf_gallery_dreamwork.swf', $module_path . 'mod_xmlswf_gallery_dreamwork'. $catppv_id . '.swf');

        ///////// set chmod 0644 for creating .swf file  if server is not windows
        $os_string = php_uname('s');
        $cnt = substr_count($os_string, 'Windows');
        if($cnt =='0'){
            @chmod($module_path . 'mod_xmlswf_gallery_dreamwork'. $catppv_id . '.swf', 0644);
        }



    }

    $query = "Select * FROM #__dreamworkgalleryc  WHERE publish = 1 " . $add_query . " ORDER BY ordnum ASC " ;
    $database->setQuery($query);
    $cat_q_res = $database->loadObjectList();

    $xml_categories_filename = $module_path.'data'.$catppv_id.'.xml';
    $defaultSelectedCategory = $params->get( 'defaultSelectedCategory', '0' );
    if ($defaultSelectedCategory == 0) {
        if(count($cat_q_res)> 0){
            $defaultSelectedCategory = $cat_q_res[0]->id;
        }else{
                    echo "<b style='color:#FF0000;'>Please Add categories and data using Dreamwork Component</b><br/><br/>";
                }
    }
    $category_thumbscale = $params->get( 'catthumb_scale', 'yes');
    if($category_thumbscale =="yes"){
         $generate_catthumb_scale = "true";
        }else{
         $generate_catthumb_scale = "false";
    }

    $show_control = $params->get( 'show_control', 'yes');
    if($show_control =="yes"){
         $generate_show_control = "true";
        }else{
            $generate_show_control = "false";
    }
        //print_r($params);
    $xml_data_data = '<?xml version="1.0" encoding="iso-8859-1"?>
    <data>
        <mobileSettings>
            <currencySymbol>'.$params->get( 'currency', '$').'</currencySymbol>
            <width>'.$params->get( 'bannerWidth', '710').'</width>
            <height>'.$params->get( 'bannerHeight', '630').'</height>
            <containerid>'.$modId.'</containerid>
            <heightManual>'.$params->get('heightManual', '').'</heightManual>
            <mheight1>'.$params->get( 'bannerHeight1', '').'</mheight1>
            <mheight2>'.$params->get( 'bannerHeight2', '').'</mheight2>
            <mheight3>'.$params->get( 'bannerHeight3', '').'</mheight3>
            <mheight4>'.$params->get( 'bannerHeight4', '').'</mheight4>
        </mobileSettings>
        <settings>';

    $xml_data_data .= '<background>
        <color code="#'.$params->get( 'backgroundColor', 'FFFFFF').'" alpha="'.$params->get( 'bgcolor_alpha', '1').'" />	</background><viewer>
        <align_image_in_fullscreen>'.$params->get( 'imgalign_fullscreen', 'center').'</align_image_in_fullscreen>
        <base><color code="#'.$params->get( 'base_color', 'cccccc').'" alpha="'.$params->get( 'basecolor_alpha', '1').'" /></base>
            <effect>
                <type>'.$params->get( 'effect_type', '1').'</type>
                <time>'.$params->get( 'effect_time', '2').'</time>
                <closingType>'.$params->get( 'closing_type', 'default').'</closingType>
                    <preloader>
                        <color code="'.$params->get( 'preloader_color', '#202020').'" alpha="'.$params->get( 'preloader_alpha', '1').'" />
                    </preloader>
            </effect>

            <description position="'.$params->get( 'desc_position', 'top').'">

                    <effect>'.$params->get( 'desc_effect', '1').'</effect>

                    <base>
                        <color code="#'.$params->get( 'desc_basecolor', '020202').'" alpha="'.$params->get( 'desc_alpha', '0.7').'" />
                    </base>

                    <text>
                        <size>'.$params->get( 'desctext_size', '12').'</size>
                        <color code="#'.$params->get( 'desctext_color', 'FFFFFF').'" alpha="'.$params->get( 'desctext_alpha', '1').'" />
                    </text>
            </description>

            <priceTag enabled="'.trim($params->get( 'show_price', 'yes')).'">

                    <tag width="'.trim($params->get( 'price_imgwidth', '120')).'" height="'.trim($params->get( 'price_imgheight','120')).'">'.JURI::root().'components/com_dreamworkgallery/price_images/'.trim($params->get( 'price_img', 'flower_green.png')).'</tag>

                    <position>'.trim($params->get( 'price_location', 'BL')).'</position>

                    <symbol>'.trim($params->get( 'currency', '$')).'</symbol>

                    <label>
                        <size>'.trim($params->get( 'price_size', '20')).'</size>
                        <color code="#'.trim($params->get( 'price_color', 'FFFFFF')).'" alpha="'.trim($params->get( 'price_color_alpha', '1')).'" />
                    </label>

                </priceTag>';

                $xml_data_data .= '<controls position="'.$params->get( 'control_position', 'bottom').'" show="'.$generate_show_control.'">

                    <base>
                        <color code="#'.$params->get( 'control_basecolor', '524B46').'" alpha="'.$params->get( 'control_basecoloralpha', '0.75').'" />
                    </base>

                    <arrow>

                        <base>
                            <up>
                                <color code="#'.$params->get( 'arrow_baseup_color', 'FBFBFB').'" alpha="'.$params->get( 'arrow_baseup_alpha', '0').'" />
                            </up>
                            <over>
                                <color code="#'.$params->get( 'arrow_baseover_color', 'FBFBFB').'" alpha="'.$params->get( 'arrow_baseover_alpha', '0.6').'" />
                            </over>
                            <down>
                                <color code="#'.$params->get( 'arrow_baseover_color', 'FBFBFB').'" alpha="'.$params->get( 'arrow_baseover_alpha', '0.6').'" />
                            </down>
                        </base>

                        <arrow>
                            <up>
                                <color code="#'.$params->get( 'arrow_up_color', 'FBFBFB').'" alpha="'.$params->get( 'arrow_up_alpha', '1').'" />
                            </up>
                            <over>
                                <color code="#'.$params->get( 'arrow_over_color', '020202').'" alpha="'.$params->get( 'arrow_over_alpha', '1').'" />
                            </over>
                            <down>
                                <color code="#'.$params->get( 'arrow_over_color', '020202').'" alpha="'.$params->get( 'arrow_over_alpha', '1').'" />
                            </down>
                        </arrow>

                    </arrow>';

            $xml_data_data .= ' <category>

                        <base>
                            <up>
                                <color code="#'.$params->get( 'cat_up_color', 'FBFBFB').'" alpha="'.$params->get( 'cat_up_color_alpha', '0').'" />
                            </up>
                            <over>
                                <color code="#'.$params->get( 'cat_over_color', 'FBFBFB').'" alpha="'.$params->get( 'cat_over_color_alpha', '0.6').'" />
                            </over>
                            <down>
                                <color code="#'.$params->get( 'cat_down_color', 'FBFBFB').'" alpha="'.$params->get( 'cat_over_color_alpha', '0.3').'" />
                            </down>
                        </base>

                        <symbol>
                            <up>
                                <color code="#'.$params->get( 'cat_symup_color', 'FBFBFB').'" alpha="'.$params->get( 'cat_symup_color_alpha', '1').'" />
                            </up>
                            <over>
                                <color code="#'.$params->get( 'cat_symover_color', '020202').'" alpha="'.$params->get( 'cat_symover_color_alpha', '1').'" />
                            </over>
                            <down>
                                <color code="#'.$params->get( 'cat_symdown_color', '020202').'" alpha="'.$params->get( 'cat_symdown_color_alpha', '0.5').'" />
                            </down>
                        </symbol>

                    </category>';

            $xml_data_data .= ' <display>

                        <base>
                            <up>
                                <color code="#'.$params->get( 'display_baseup_color', 'FBFBFB').'" alpha="'.$params->get( 'display_baseup_alpha', '0').'" />
                            </up>
                            <over>
                                <color code="#'.$params->get( 'display_baseover_color', 'FBFBFB').'" alpha="'.$params->get( 'display_baseover_alpha', '0.6').'" />
                            </over>
                            <down>
                                <color code="#'.$params->get( 'display_baseover_color', 'FBFBFB').'" alpha="'.$params->get( 'circle_up_alpha', '1').'" />
                            </down>
                        </base>

                        <circle>
                            <up>
                                <color code="#'.$params->get( 'circle_up_color', 'FBFBFB').'" alpha="'.$params->get( 'circle_up_alpha', '1').'" />
                            </up>
                            <over>
                                <color code="#'.$params->get( 'circle_over_color', '020202').'" alpha="'.$params->get( 'circle_over_alpha', '1').'" />
                            </over>
                            <down>
                                <color code="#'.$params->get( 'circle_over_color', '020202').'" alpha="'.$params->get( 'circle_over_alpha', '1').'" />
                            </down>
                        </circle>

                        <symbol>
                            <up>
                                <color code="#'.$params->get( 'symbal_up_color', '020202').'" alpha="'.$params->get( 'symbal_up_alpha', '1').'" />
                            </up>
                            <over>
                                <color code="#'.$params->get( 'symbal_over_color', 'FBFBFB').'" alpha="'.$params->get( 'symbal_over_alpha', '1').'" />
                            </over>
                            <down>
                                <color code="#'.$params->get( 'symbal_over_color', 'FBFBFB').'" alpha="'.$params->get( 'symbal_over_alpha', '1').'" />
                            </down>
                        </symbol>

                    </display>

                    <autoplay displayTime="'.$params->get( 'autoplay_display_time', '10').'" default="'.$params->get( 'autoplay', 'on').'">

                        <base>
                            <up>
                                <color code="#'.$params->get( 'autoplay_baseup_color', 'FBFBFB').'" alpha="'.$params->get( 'autoplay_baseup_alpha', '0').'" />
                            </up>
                            <over>
                                <color code="#'.$params->get( 'autoplay_baseover_color', 'FBFBFB').'" alpha="'.$params->get( 'autoplay_baseover_alpha', '0.6').'" />
                            </over>
                            <down>
                                <color code="#'.$params->get( 'autoplay_baseover_color', 'FBFBFB').'" alpha="'.$params->get( 'autoplay_baseover_alpha', '0.6').'" />
                            </down>
                        </base>

                        <animation>
                            <up>
                                <color code="#'.$params->get( 'animation_up_color', 'FBFBFB').'" alpha="'.$params->get( 'animation_up_alpha', '1').'" />
                            </up>
                            <over>
                                <color code="#'.$params->get( 'animation_over_color', '020202').'" alpha="'.$params->get( 'animation_up_alpha', '1').'" />
                            </over>
                            <down>
                                <color code="#'.$params->get( 'animation_over_color', '020202').'" alpha="'.$params->get( 'animation_up_alpha', '1').'" />
                            </down>
                        </animation>

                        <symbol>
                            <up>
                                <color code="#'.$params->get( 'autoplay_sym_up_color', 'FBFBFB').'" alpha="'.$params->get( 'animation_up_alpha', '1').'" />
                            </up>
                            <over>
                                <color code="#'.$params->get( 'autoplay_sym_over_color', '020202').'" alpha="'.$params->get( 'animation_up_alpha', '1').'" />
                            </over>
                            <down>
                                <color code="#'.$params->get( 'autoplay_sym_over_color', '020202').'" alpha="'.$params->get( 'animation_up_alpha', '1').'" />
                            </down>
                        </symbol>

                    </autoplay>

                </controls>';
    $xml_data_data .= '</viewer>

            <thumbsPanel position="'.$params->get( 'thumbpanel_position', 'bottom').'" height="'.$params->get( 'thumbpanel_height', '76').'">

                <base>
                    <color code="#'.$params->get( 'thumbpanel_base_color', '2C2C2C').'" alpha="'.$params->get( 'thumbpanel_base_alpha', '1').'" />
                </base>

                <thumb width="'.$params->get( 'thumb_width', '86').'" height="'.$params->get( 'thumb_height', '64').'">

                    <base>
                        <up>
                            <color code="#'.$params->get( 'thumb_baseup_color', 'FEFEFE').'" alpha="'.$params->get( 'thumbpanel_base_alpha', '1').'" />
                        </up>
                        <over>
                            <color code="#'.$params->get( 'thumb_mouseover_color', '0EF44A').'" alpha="'.$params->get( 'thumbpanel_base_alpha', '1').'" />
                        </over>
                        <down>
                            <color code="#'.$params->get( 'thumb_mouseover_color', '0EF44A').'" alpha="'.$params->get( 'thumbpanel_base_alpha', '1').'" />
                        </down>
                        <selected>
                            <color code="#'.$params->get( 'thumb_mouseover_color', '0EF44A').'" alpha="'.$params->get( 'thumbpanel_base_alpha', '1').'" />
                        </selected>
                    </base>

                    <preloader>
                        <color code="#'.$params->get( 'thumb_preloader_color', '202020').'" alpha="'.$params->get( 'thumbpanel_base_alpha', '1').'" />
                    </preloader>

                </thumb>

                <tooltip>

                    <base>
                        <color code="#'.$params->get( 'tooltip_base_color', 'FBFBFB').'" alpha="'.$params->get( 'tooltip_base_alpha', '0.8').'" />
                    </base>

                    <label>
                        <color code="#'.$params->get( 'tooltip_label_color', '202020').'" alpha="'.$params->get( 'tooltip_label_alpha', '1').'" />
                    </label>

                </tooltip>

                <arrow>
                    <up>
                        <color code="#'.$params->get( 'thumbnav_arrowcolor', '0EF44A').'" alpha="'.$params->get( 'thumbnav_arrow_alpha', '1').'" />
                    </up>
                    <over>
                        <color code="#'.$params->get( 'thumbnav_arrow_mouseovercolor', '0EF44A').'" alpha="'.$params->get( 'thumbnav_arrow_mouseovercolor_alpha', '1').'" />
                    </over>
                    <down>
                        <color code="#'.$params->get( 'thumbnav_arrow_onclickcolor', '0EF44A').'" alpha="'.$params->get( 'thumbnav_arrow_Onclickcolor_alpha', '1').'" />
                    </down>
                </arrow>


            </thumbsPanel>';

    $xml_data_data .= '<category_list>
    <showOnStart>'.($params->get( 'showOnStart', 'on')=='on'?'true':'false').'</showOnStart>
                <height>'.$params->get( 'catlist_height', '385').'</height>
                <item>
                    <title_size>'.$params->get( 'catlist_titlesize', '15').'</title_size>
                    <description_size>'.$params->get( 'catlist_descsize', '12').'</description_size>
                    <title_color>'.$params->get( 'catlist_titlecolor', 'FFFFFF').'</title_color>
                    <description_color>'.$params->get( 'catlist_desccolor', 'FFFFFF').'</description_color>
                    <background_color>'.$params->get( 'catlist_bgcolor', '606060').'</background_color>
                    <color_over>'.$params->get( 'catlist_bgovercolor', '1f1f1f').'</color_over>
                    <width>'.$params->get( 'catlist_itemwidth', '330').'</width>
                    <height>'.$params->get( 'catlist_itemheight', '125').'</height>
                    <thumb_width>'.$params->get( 'catlist_thmbwidth', '89').'</thumb_width>
                    <thumb_height>'.$params->get( 'catlist_thmbheight', '97').'</thumb_height>
                    <thumb_border_size>'.$params->get( 'catlist_thmbbordersize', '4').'</thumb_border_size>
                    <thumb_border_color>'.$params->get( 'catlist_thmbbordercolor', 'FFFFFF').'</thumb_border_color>
                </item>
                <scroll>
                    <background_color>'.$params->get( 'catlist_scollerbgcolor', '77c001').'</background_color>
                    <element_color>'.$params->get( 'catlist_scollerarrowcolor', 'f5f5f5').'</element_color>
                    <element_icon_color>'.$params->get( 'catlist_scollericoncolor', '000000').'</element_icon_color>
                </scroll>
                <close>
                    <background_color>'.$params->get( 'catlist_scollerbgcolor', '77c001').'</background_color>
                    <icon_color>'.$params->get( 'catlist_scollerarrowcolor', 'f5f5f5').'</icon_color>
                    <background_color_over>'.$params->get( 'catlist_scollerarrowcolor', 'f5f5f5').'</background_color_over>
                    <icon_color_over>'.$params->get( 'catlist_scollerbgcolor', '77c001').'</icon_color_over>
                </close>
            </category_list>';
            $xml_data_data .= ' </settings><content>';
    if(count($cat_q_res) > 0){
        foreach ($cat_q_res as $curr_category)
        {	$cat_thumb_path = 'images/dreamworkgallery/galleries/';
            $ret_arr = write_galdreamwork_xml_data($curr_category->id, $curr_category->id, $params);
            if ($ret_arr['flag']) {
        $xml_data_data .= '<category>';
            $xml_data_data .= '<title><![CDATA['.$curr_category->name.']]></title>';
            $xml_data_data .= '<description><![CDATA['.$curr_category->descr.']]></description>';
            $xml_data_data .= '<thumb scale="'.$generate_catthumb_scale.'">'.JURI::root().$cat_thumb_path.$curr_category->thumb.'</thumb>';
                $xml_data_data .= $ret_arr['xml'];
                $xml_data_data .= '</category>';
            }

        }
    }
    $xml_data_data .= '</content></data>';
    $xml_categories_file = fopen($xml_categories_filename,'w');
    fwrite($xml_categories_file, $xml_data_data);
    fclose($xml_categories_file);
    ///////// set chmod 0777 for creating .xml file  if server is not windows
        $os_string = php_uname('s');
        $cnt = substr_count($os_string, 'Windows');
        if($cnt =='0'){
            @chmod($xml_categories_filename, 0777);
        }
    }
}

if (!function_exists('write_galdreamwork_xml_data')) {
function write_galdreamwork_xml_data($cat_name, $cat_id, $params)
{
	$ret_arr = array ('flag'=>false, 'xml'=>'');
	$params_comg = &JComponentHelper::getParams('com_dreamworkgallery');
	$images_path = $params_comg->get('pic_path', 'images/dreamworkgallery/gallery');
	$images_path .= '/';
	global $mosConfig_absolute_path, $sess;
	$query = "Select * FROM #__dreamworkgallery WHERE catid = " . $cat_id . " AND publish = 1 ORDER BY ordnum ASC ";

	$xml_data = '';
	$db = & JFactory::getDBO();//new ps_DB();
	//$db->query($query);
	//$rows = $db->record;
	$db->setQuery($query);
	$prod_q_res = $db->loadObjectList();

	foreach ($prod_q_res as $curr_product) {

$ret_arr['flag'] = true;
$xml_data .= ' <item interactive="'.$params->get( 'item_interactive', 'yes').'" zoom="'.$params->get( 'item_zoom', 'enabled').'">';

				$xml_data .= ' <main scale="'.$params->get( 'mainImg_scale', 'yes').'">'.trim(JURI::root().$images_path.$curr_product->image).'</main>';

				$xml_data .= ' <thumb scale="'.$params->get( 'thumbImg_scale', 'yes').'">'.trim(JURI::root().$images_path.$curr_product->thumb).'</thumb>';

				if ($params->get('show_tooltip', 'on') == 'on') {
					$xml_data .= '<label><![CDATA['.trim($curr_product->name).']]></label>';
				}else{
					$xml_data .= '<label><![CDATA[]]></label>';
				}

				if ($params->get('show_desc', 'on') == 'on') {
				$xml_data .= '<description><![CDATA['.trim($curr_product->descr).']]></description>';
				}else{
					$xml_data .= '<description><![CDATA[]]></description>';
				}

				$xml_data .= '<link window="'.$params->get( 'target', 'new').'"><![CDATA['.trim($curr_product->linkname).']]></link>';

				if ($params->get('show_price', 'yes') == 'yes') {
					$xml_data .= '<price>';
			if($curr_product->reg_price > 0){
					$xml_data .= '<regular><![CDATA['.$curr_product->reg_price.']]></regular>';
			if ($params->get('show_disprice', 'no') == 'yes') {
					if($curr_product->dis_price > 0){
						$xml_data .= '<updated><![CDATA['.$curr_product->dis_price.']]></updated>';
					}else{
						$xml_data .= '<updated><![CDATA[]]></updated>';
					}
				}else{
					$xml_data .= '<updated><![CDATA[]]></updated>';
				}
			}else{
					$xml_data .= '<regular><![CDATA[]]></regular>';
					$xml_data .= '<updated><![CDATA[]]></updated>';
			}

				$xml_data .= '</price>';
			}else{
				$xml_data .= '<price>';
				$xml_data .= '<regular><![CDATA[]]></regular>';
				$xml_data .= '<updated><![CDATA[]]></updated>';
				$xml_data .= '</price>';
			}

				$xml_data .= '</item>';
	}
		$ret_arr['xml'] = $xml_data;
		return $ret_arr;
}
}

if (!function_exists('getCatDataDreamW')) {
	function getCatDataDreamW($cat_id, $params, $params_comg)
    {
		$database = & JFactory::getDBO();
		$allitms_resp = "no";
		$pagecnt = $params->get('nrOfThumbsPerPage', '30');

		$qlimstart = 0;
		$qlimnumber = $pagecnt;

		$q_ord_items = ' ORDER BY #__dreamworkgallery.ordnum ';

		$cnt = 0;

		$where_q = ' #__dreamworkgallery.catid = ' . $cat_id . ' AND ';
		$query = 'Select COUNT(#__dreamworkgallery.id) FROM #__dreamworkgallery WHERE '.$where_q.' #__dreamworkgallery.publish = 1 ';
		$database->setQuery($query);
		$result = $database->query();
		if ($result) {
			$cnt = $database->loadResult();
		}
		if ($cnt > 0) {
			$query = 'Select #__dreamworkgallery.* FROM #__dreamworkgallery WHERE '.$where_q.' #__dreamworkgallery.publish = 1 ' . $q_ord_items;
			$database->setQuery($query,$qlimstart,$qlimnumber);
			
			$data = $database->loadObjectList();
		} else {
			$data = array();
		}
		$ret_data = array('data'=>$data, 'cnt'=>$cnt);
		return $ret_data;
    }
}

if (!function_exists('getDataDreamW')) {
    function getDataDreamW($cat_ids, $params, $params_comg)
    {
		$database = & JFactory::getDBO();
		$where_q = '';
		$data_ret = array();
		if ($cat_ids != 0) {
			$ids_arr = explode(',', $cat_ids);
			if (isset($ids_arr) && is_array($ids_arr) && count($ids_arr)>0) {
				$where_q = ' AND (id=' . implode (' OR id=', $ids_arr) . ')';
			}
			$query = 'SELECT * FROM #__dreamworkgalleryc WHERE publish = 1 '.$where_q .' ORDER BY ordnum';
			$database->setQuery($query);
			$dbdata_c = $database->loadObjectList('id');
			$cur_i = 0;
			foreach ($ids_arr as $c_id) {
				$data_ret[$cur_i++] = $dbdata_c[$c_id];
			}
		} else {
			$query = 'SELECT * FROM #__dreamworkgalleryc WHERE publish = 1 '.$where_q .' ORDER BY ordnum';
			$database->setQuery($query);
			$dbdata = $database->loadObjectList();
			$cur_i = 0;
			foreach ($dbdata as $c_dval) {
				$data_ret[$cur_i++] = $c_dval;
			}
		}
		return 	$data_ret;
    }
}

$activate_resp_tpl = $params->get( 'activate_resp_tpl', 'yes' );

if ($activate_resp_tpl == "no" || !(strstr($_SERVER['HTTP_USER_AGENT'],"iPad") || strstr($_SERVER['HTTP_USER_AGENT'],"iPhone") || stristr($_SERVER['HTTP_USER_AGENT'], 'Android'))) {

    create_galldreamwork_xml_file($params, $catppv_id, $onlyvm_flag,$modId);
?>
<div align="center" id="<?php echo $modId?>">
<script type="text/javascript">AC_FL_RunContent = 0;</script>
<script src="<?php echo JURI::root()?>modules/mod_xmlswf_gallery_dreamwork/AC_RunActiveContent.js" type="text/javascript"></script>
<script type="text/javascript">
	if (AC_FL_RunContent == 0) {
		alert("This page requires AC_RunActiveContent.js.");
	}
    else {
		AC_FL_RunContent(
			'codebase', 'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0',
			'width', '<?php echo $bannerWidth;?>',
			'height', '<?php echo $bannerHeight; ?>',
			'src', '<?php echo JURI::root()?>modules/mod_xmlswf_gallery_dreamwork/mod_xmlswf_gallery_dreamwork<?php echo $catppv_id; ?>',
			'quality', 'high',
			'pluginspage', 'http://www.macromedia.com/go/getflashplayer',
			'align', 'middle',
			'play', 'true',
			'loop', 'true',
			'scale', 'showall',
			'wmode', '<?php echo $wmode;?>',
			'devicefont', 'false',
			'id', 'gallery',
			'bgcolor', '<?php echo $backgroundColor; ?>',
			'name', 'gallery',
			'menu', 'true',
			'mobile', '<?php echo JURI::root()?>modules/mod_xmlswf_gallery_dreamwork/data<?php echo $catppv_id; ?>.xml',
			'allowFullScreen', 'true',
			'flashVars', 'data=<?php echo JURI::root()?>modules/mod_xmlswf_gallery_dreamwork/data<?php echo $catppv_id; ?>.xml',
			'allowScriptAccess','sameDomain',
			'movie', '<?php echo JURI::root()?>modules/mod_xmlswf_gallery_dreamwork/mod_xmlswf_gallery_dreamwork<?php echo $catppv_id; ?>',
			'salign', ''
			); //end AC code
	}
</script></div><?php
 } else {
		$cat_ids  = trim($params->get( 'category_id', '0' ));
		$data = getDataDreamW($cat_ids, $params, $params_comg);
		
		if (isset($data[0]->id)) {
			$catdata = array();
			$caticnt = array();
			foreach ($data as $c_dkey=>$c_d) {
				$ret_cdata = getCatDataDreamW($c_d->id, $params, $params_comg);
				if ($ret_cdata['cnt'] > 0) {
					$catdata[$c_d->id] = $ret_cdata['data'];
					$caticnt[$c_d->id] = $ret_cdata['cnt'];
				} else {
					unset($data[$c_dkey]);
				}
			}
		} else {
			$catdata = array();
		}

$ps_url = JURI::root().'components/com_dreamworkgallery/';
$sb_path = $ps_url.'sb/';
$ps_rs_url = $ps_url.'sb/responsive/';
$images_path = $params_comg->get('pic_path', 'images/dreamworkgallery/gallery');
$images_path .= '/';
$pics_url = trim(JURI::root().$images_path);
$pics_path = JPATH_SITE.'/'.$images_path;

$style_var = '<style type="text/css">
div#drmwrs_ha table input {
    margin: 0px;
}
div#drmwrs_ha table {
    margin: 0px;
}

div#picpage a:link, div#picpage a:visited {
	border: 1px solid #ccdbe4;
	margin: 2px;
	padding: 2px 5px;
}
div#picpage a:hover, div#picpage a:active {
	background-color: #3666d4;
	color: #FFFFFF;
	border: 1px solid #2855af;
	margin: 2px;
	padding: 2px 5px;
}

#picpage a {
	text-decoration: none;
}

</style>';

$document =& JFactory::getDocument();
$document->addCustomTag($style_var);

$rspskin = $params->get('rspskin', "skin_minimal_dark_square");
if ($rspskin == "skin_minimal_dark_square") {
	$rspskincss = "skin_minimal_dark_global";
} else {
	$rspskincss = "skin_minimal_dark_global";
}
$document->addScript($sb_path . 'jqmin.js');
$document->addStyleSheet($ps_rs_url.'load/'.$rspskincss.'.css');
$document->addScript($ps_rs_url.'java/' . 'FWDGrid.js');

$gridHolderId = 'drmwrs_ha';
$gridPlayListAndSkinId = 'drmwrs_pla';
$buy_link_place_rsp = 2;//$params->get('buy_link_place_rsp', "2");
$showContextMenu = $params->get('showContextMenu', "yes");
$thumbnailOverlayType = $params->get('thumbnailOverlayType', "text");
$addMargins = $params->get('addMargins', "yes");
$loadMoreThumbsButtonOffest = $params->get('loadMoreThumbsButtonOffest', "0");
$thumbnailBaseWidth = $params_comg->get('thumbnailBaseWidth', "180");
$thumbnailBaseHeight = $params_comg->get('thumbnailBaseHeight', "180");
$nrOfThumbsToShowOnSet = $params->get('nrOfThumbsToShowOnSet', "30");
$horizontalSpaceBetweenThumbnails = $params->get('horizontalSpaceBetweenThumbnails', "6");
	$vert_space_add = 0;
$verticalSpaceBetweenThumbnails = $params->get('verticalSpaceBetweenThumbnails', "6") + $vert_space_add;
$thumbBorderSize = $params->get('thumbBorderSize', "4");
$thumbnailOverlayOpacity = $params->get('thumbnailOverlayOpacity', ".85");
$backgroundColor = $params->get('backgroundColor', "CCCCCC");
$thumbnailOverlayColor = $params->get('thumbnailOverlayColor', "000000");
$thumbnailBackgroundColor = $params->get('thumbnailBackgroundColor', "333333");
$thumbnailBorderNormalColor = $params->get('thumbnailBorderNormalColor', "FFFFFF");
$thumbnailBorderSelectedColor = $params->get('thumbnailBorderSelectedColor', "FFFFFF");
$addLightBoxKeyboardSupport = $params->get('addLightBoxKeyboardSupport', "yes");
$showLightBoxNextAndPrevButtons = $params->get('showLightBoxNextAndPrevButtons', "yes");
$showLightBoxZoomButton = $params->get('showLightBoxZoomButton', "yes");
$showLightBoxInfoButton = $params->get('showLightBoxInfoButton', "yes");
$showLighBoxSlideShowButton = $params->get('showLighBoxSlideShowButton', "yes");
$slideShowAutoPlay = $params->get('slideShowAutoPlay', "no");
//$showContextMenu = $params->get('showContextMenu', "yes");
$lighBoxBackgroundColor = $params->get('lighBoxBackgroundColor', "000000");
$lightBoxInfoWindowBackgroundColor = $params->get('lightBoxInfoWindowBackgroundColor', "FFFFFF");
$lightBoxItemBorderColor = $params->get('lightBoxItemBorderColor', "FFFFFF");
$lightBoxItemBackgroundColor = $params->get('lightBoxItemBackgroundColor', "222222");
$lightBoxMainBackgroundOpacity = $params->get('lightBoxMainBackgroundOpacity', ".8");
$lightBoxInfoWindowBackgroundOpacity = $params->get('lightBoxInfoWindowBackgroundOpacity', ".9");
$lightBoxBorderSize = $params->get('lightBoxBorderSize', "4");
$lightBoxSlideShowDelay = $params->get('lightBoxSlideShowDelay', "4");
//new settings 2.js-categories
$thumbnailBorderRadius = $params->get('thumbnailBorderRadius', "0");
$showLightBoxInfoWindowByDefault = $params->get('showLightBoxInfoWindowByDefault', "no");
$lightBoxVideoAutoPlay = $params->get('lightBoxVideoAutoPlay', "no");
$lightBoxBorderRadius = $params->get('lightBoxBorderRadius', "0");
$startAtCategory = $params->get('startAtCategory', "1");
$selectLabel = $params->get('selectLabelName', "CATEGORIES");//JText::_('COM_PICSELL_RSP_SELECTLABEL');
$allCategoriesLabel = $params->get('allCategoriesLabel', "All Categories");//JText::_('COM_PICSELL_RSP_ALLCATEGORIESLABEL');
$showAllCategories = $params->get('showAllCategories', "yes");
$comboBoxPosition = $params->get('comboBoxPosition', "topleft");
$selctorBackgroundNormalColor = $params->get('selctorBackgroundNormalColor', "FFFFFF");
$selctorBackgroundSelectedColor = $params->get('selctorBackgroundSelectedColor', "000000");
$selctorTextNormalColor = $params->get('selctorTextNormalColor', "000000");
$selctorTextSelectedColor = $params->get('selctorTextSelectedColor', "FFFFFF");
$buttonBackgroundNormalColor = $params->get('buttonBackgroundNormalColor', "FFFFFF");
$buttonBackgroundSelectedColor = $params->get('buttonBackgroundSelectedColor', "000000");
$buttonTextNormalColor = $params->get('buttonTextNormalColor', "000000");
$buttonTextSelectedColor = $params->get('buttonTextSelectedColor', "FFFFFF");
$comboBoxShadowColor = $params->get('comboBoxShadowColor', "000000");
$comboBoxHorizontalMargins = $params->get('comboBoxHorizontalMargins', "12");
$comboBoxVerticalMargins = $params->get('comboBoxVerticalMargins', "12");
$comboBoxCornerRadius = $params->get('comboBoxCornerRadius', "0");
//$sizespopw = 4 + $buylnk_price_fontsz + $lightBoxBorderSize;//Height added to div to show sizes and buy link on popup(lightbox)
$sizespopw =0;
$initgridjs = 'var grid = new FWDGrid({
				//main settings
gridHolderId:"'.$gridHolderId.'",
gridPlayListAndSkinId:"'.$gridPlayListAndSkinId.'",
showContextMenu:"'.$showContextMenu.'",
				//grid settings
thumbnailOverlayType:"'.$thumbnailOverlayType.'",
addMargins:"'.$addMargins.'",
loadMoreThumbsButtonOffest:'.$loadMoreThumbsButtonOffest.',
thumbnailBaseWidth:'.$thumbnailBaseWidth.',
thumbnailBaseHeight:'.$thumbnailBaseHeight.',
nrOfThumbsToShowOnSet:'.$nrOfThumbsToShowOnSet.',
horizontalSpaceBetweenThumbnails:'.$horizontalSpaceBetweenThumbnails.',
verticalSpaceBetweenThumbnails:'.$verticalSpaceBetweenThumbnails.',
thumbnailBorderSize:'.$thumbBorderSize.',
thumbnailBorderRadius:'.$thumbnailBorderRadius.',
thumbnailOverlayOpacity:'.$thumbnailOverlayOpacity.',
backgroundColor:"#'.$backgroundColor.'",
thumbnailOverlayColor:"#'.$thumbnailOverlayColor.'",
thumbnailBackgroundColor:"#'.$thumbnailBackgroundColor.'",
thumbnailBorderNormalColor:"#'.$thumbnailBorderNormalColor.'",
thumbnailBorderSelectedColor:"#'.$thumbnailBorderSelectedColor.'",
//combobox settings
startAtCategory:startAtCatPos,
selectLabel:"'.$selectLabel.'",
allCategoriesLabel:"'.$allCategoriesLabel.'",
showAllCategories:"'.$showAllCategories.'",
comboBoxPosition:"'.$comboBoxPosition.'",
selctorBackgroundNormalColor:"#'.$selctorBackgroundNormalColor.'",
selctorBackgroundSelectedColor:"#'.$selctorBackgroundSelectedColor.'",
selctorTextNormalColor:"#'.$selctorTextNormalColor.'",
selctorTextSelectedColor:"#'.$selctorTextSelectedColor.'",
buttonBackgroundNormalColor:"#'.$buttonBackgroundNormalColor.'",
buttonBackgroundSelectedColor:"#'.$buttonBackgroundSelectedColor.'",
buttonTextNormalColor:"#'.$buttonTextNormalColor.'",
buttonTextSelectedColor:"#'.$buttonTextSelectedColor.'",
comboBoxShadowColor:"#'.$comboBoxShadowColor.'",
comboBoxHorizontalMargins:'.$comboBoxHorizontalMargins.',
comboBoxVerticalMargins:'.$comboBoxVerticalMargins.',
comboBoxCornerRadius:'.$comboBoxCornerRadius.',
//ligtbox settings
addLightBoxKeyboardSupport:"'.$addLightBoxKeyboardSupport.'",
showLightBoxNextAndPrevButtons:"'.$showLightBoxNextAndPrevButtons.'",
showLightBoxZoomButton:"'.$showLightBoxZoomButton.'",
showLightBoxInfoButton:"'.$showLightBoxInfoButton.'",
showLighBoxSlideShowButton:"'.$showLighBoxSlideShowButton.'",
showLightBoxInfoWindowByDefault:"'.$showLightBoxInfoWindowByDefault.'",
slideShowAutoPlay:"'.$slideShowAutoPlay.'",
lightBoxVideoAutoPlay:"'.$lightBoxVideoAutoPlay.'",
lighBoxBackgroundColor:"#'.$lighBoxBackgroundColor.'",
lightBoxInfoWindowBackgroundColor:"#'.$lightBoxInfoWindowBackgroundColor.'",
lightBoxItemBorderColor:"#'.$lightBoxItemBorderColor.'",
lightBoxItemBackgroundColor:"#'.$lightBoxItemBackgroundColor.'",
lightBoxMainBackgroundOpacity:'.$lightBoxMainBackgroundOpacity.',
lightBoxInfoWindowBackgroundOpacity:'.$lightBoxInfoWindowBackgroundOpacity.',
lightBoxBorderSize:'.$lightBoxBorderSize.',
lightBoxBorderRadius:'.$lightBoxBorderRadius.',
lightBoxSlideShowDelay:'.$lightBoxSlideShowDelay.'
			});';
$ci = 0;
$rsp_cat_i_to_id = 'var rsp_cat_i_to_id = new Array();
';
if ($showAllCategories == "yes") {
$rsp_cat_i_to_id .= 'rsp_cat_i_to_id['.$ci.'] = '.$data[0]->id.';
';
$ci++;
}
foreach ($data as $c_dta) {
	$rsp_cat_i_to_id .= 'rsp_cat_i_to_id['.$ci.'] = '.$c_dta->id.';
';
$ci++;
}
//var rspLBoxSzTopMad -px added to my div margin
$rspLBoxSzTopMad_par = $params->get('rspLBoxSzTopMad_par', "0");
$rspLBoxSzTopMad = - $rspLBoxSzTopMad_par;
if ($rspLBoxSzTopMad_par < -$lightBoxBorderSize) {
	$rspblinkoverfflag = 1;
} else {
	$rspblinkoverfflag = 0;
}
$picsperpage = $params->get('nrOfThumbsPerPage', '30');
$rs_scr1 = '<script type="text/javascript">
var buylinkprsp = 0;
var rspLBoxBrSz = '.$lightBoxBorderSize.';
var rspLBoxSzTopMad = '.$rspLBoxSzTopMad.';
var rspblinkoverfflag = '.$rspblinkoverfflag.';
'.$rsp_cat_i_to_id.'
var startAtCatPos='. $startAtCategory .';
function ComboChPage(oldi, newi)
{
	startAtCatPos = newi+1;
	jQuery("#picpage_"+rsp_cat_i_to_id[oldi]).hide();
	jQuery("#picpage_"+rsp_cat_i_to_id[newi]).show();
}
function getSzAddW()
{
	return '.(($buy_link_place_rsp == 2) ? $sizespopw : '0').';
}
function DoInitG(exfunc){
		exfunc();
}
function InitGridTm() {
	jQuery("#'.$gridHolderId.'").html("");
	DoInitG (function(){
	'.$initgridjs.'
	});
}
FWDUtils.onReady(function(){
	'.$initgridjs.'
	});
	
	function ShowCat(currcid, curpn)
	{
		jQuery("#'.$gridHolderId.'").html("<table align=\\"center\\"  width=\\"100%\\"><tr align=\\"center\\"><td align=\\"center\\"><img src=\\"'.$sb_path.'misc/preload_cat.gif\\" /></td></tr></table>");
		jQuery.get("'.JURI::root().'", { option: "com_dreamworkgallery", controller: "dreamworkgallery", task: "catpics", gcatid: currcid, pagenum: curpn, cat_ids: "'.$cat_ids.'",picsperpage: "'.$picsperpage.'"},
	   function(data){
		var ret_arr = data.split("_sss_sss_sss_");

	jQuery("#cid" + currcid).html(ret_arr[0]);
	if (ret_arr[1] && ret_arr[1] != "") {
		jQuery("#picpage_" + currcid).html(ret_arr[1]);
	}
	if (ret_arr[2] && ret_arr[2] != "") {
		jQuery("#rspsztwocat_" + currcid).html(ret_arr[2]);
	}
});
		jQuery("#cid" + currcid).ready(function(){
		var tt1=setTimeout("InitGridTm()", 1500);
		});
	}
</script>';
$document->addCustomTag($rs_scr1);


$html_out = '<table id="picsellctable" width="100%" border="" style="border-collapse:collapse;border:0;" cellspacing="0" cellpadding="0"><tr style="border:0;"><td align="center" style="border:0;"><div id="picsellcont" >';
$html_out .='<div id="glpicss" class="glpicss">';

		$page_index_num = 10;		
		$page_num = 1;

		

		$page_href = 'javascript:scrollTo(0,0);';
		
		$toolt_js_add1 = '';
		$toolt_divs1 = '';
		$toolt_divs1dsc = '';
		$r_cnt = 1;
		$curr_picr = 1;
		$ifor_cnt = 0;

	$html_resp_out = '
	<div id="'.$gridHolderId.'" style="width:100%;"></div>
	<div id="'.$gridPlayListAndSkinId.'" style="display: none;">
		<ul data-skin="">
			<li data-preloader-path="'.$ps_rs_url.'load/'.$rspskin.'/rotite-30-29.png"></li>
			<li data-show-more-thumbnails-button-normal-path="'.$ps_rs_url.'load/'.$rspskin.'/showMoreThumbsNormalState.png"></li>
			<li data-show-more-thumbnails-button-selectsed-path="'.$ps_rs_url.'load/'.$rspskin.'/showMoreThumbsSelectedState.png"></li>
			<li data-image-icon-path="'.$ps_rs_url.'load/'.$rspskin.'/photoIcon.png"></li>
			<li data-video-icon-path="'.$ps_rs_url.'load/'.$rspskin.'/videoIcon.png"></li>
			<li data-link-icon-path="'.$ps_rs_url.'load/'.$rspskin.'/linkIcon.png"></li>
			<li data-iframe-icon-path="'.$ps_rs_url.'load/'.$rspskin.'/iframeIcon.png"></li>
			<li data-hand-move-icon-path="'.$ps_rs_url.'load/'.$rspskin.'/handnmove.cur"></li>
			<li data-hand-drag-icon-path="'.$ps_rs_url.'load/'.$rspskin.'/handgrab.cur"></li>
			<li data-combobox-down-arrow-icon-normal-path="'.$ps_rs_url.'load/'.$rspskin.'/combobox-down-arrow.png"></li>
			<li data-combobox-down-arrow-icon-selected-path="'.$ps_rs_url.'load/'.$rspskin.'/combobox-down-arrow-rollover.png"></li>
			<li data-lightbox-slideshow-preloader-path="'.$ps_rs_url.'load/'.$rspskin.'/slideShowPreloader.png"></li>
			<li data-lightbox-close-button-normal-path="'.$ps_rs_url.'load/'.$rspskin.'/galleryCloseButtonNormalState.png"></li>
			<li data-lightbox-close-button-selected-path="'.$ps_rs_url.'load/'.$rspskin.'/galleryCloseButtonSelectedState.png"></li>
			<li data-lightbox-next-button-normal-path="'.$ps_rs_url.'load/'.$rspskin.'/nextIconNormalState.png"></li>
			<li data-lightbox-next-button-selected-path="'.$ps_rs_url.'load/'.$rspskin.'/nextIconSelectedState.png"></li>
			<li data-lightbox-prev-button-normal-path="'.$ps_rs_url.'load/'.$rspskin.'/prevIconNormalState.png"></li>
			<li data-lightbox-prev-button-selected-path="'.$ps_rs_url.'load/'.$rspskin.'/prevIconSelectedState.png"></li>
			<li data-lightbox-play-button-normal-path="'.$ps_rs_url.'load/'.$rspskin.'/playButtonNormalState.png"></li>
			<li data-lightbox-play-button-selected-path="'.$ps_rs_url.'load/'.$rspskin.'/playButtonSelectedState.png"></li>
			<li data-lightbox-pause-button-normal-path="'.$ps_rs_url.'load/'.$rspskin.'/pauseButtonNormalState.png"></li>
			<li data-lightbox-pause-button-selected-path="'.$ps_rs_url.'load/'.$rspskin.'/pauseButtonSelectedState.png"></li>
			<li data-lightbox-maximize-button-normal-path="'.$ps_rs_url.'load/'.$rspskin.'/maximizeButtonNormalState.png"></li>
			<li data-lightbox-maximize-button-selected-path="'.$ps_rs_url.'load/'.$rspskin.'/maximizeButtonSelectedState.png"></li>
			<li data-lightbox-minimize-button-normal-path="'.$ps_rs_url.'load/'.$rspskin.'/minimizeButtonNormalState.png"></li>
			<li data-lightbox-minimize-button-selected-path="'.$ps_rs_url.'load/'.$rspskin.'/minimizeButtonSelectedState.png"></li>
			<li data-lightbox-info-button-open-normal-path="'.$ps_rs_url.'load/'.$rspskin.'/infoButtonOpenNormalState.png"></li>
			<li data-lightbox-info-button-open-selected-path="'.$ps_rs_url.'load/'.$rspskin.'/infoButtonOpenSelectedState.png"></li>
			<li data-lightbox-info-button-close-normal-path="'.$ps_rs_url.'load/'.$rspskin.'/infoButtonCloseNormalPath.png"></li>
			<li data-lightbox-info-button-close-selected-path="'.$ps_rs_url.'load/'.$rspskin.'/infoButtonCloseSelectedPath.png"></li>
		</ul>
		';
		$ret_str_p = '';
		$i_cat = 1;
		if ($showAllCategories == "yes") {
			$i_cat++;
		}
	foreach ($data as $c_cat) {
		$pic_numb_rsp = $caticnt[$c_cat->id];
		if ($pic_numb_rsp > $picsperpage) {
			$pages_all_rsp = ceil($pic_numb_rsp / ($picsperpage));
			$last_page_rsp = ceil($page_num / $page_index_num) * $page_index_num;
			if ($last_page_rsp > $pages_all_rsp) {
				$last_page_rsp = $pages_all_rsp;
			}
		}
		$first_page = 1;
		
		$cat_id = $c_cat->id;
		if ($i_cat == $startAtCategory || count($data)==1) {
			$pdiv_hide_s = 'style="display: block;"';
		} else {
			$pdiv_hide_s = 'style="display: none;"';
		}
		$ret_str_p .= '<div id="picpage_'.$c_cat->id.'" class="picpclsdsp" '.$pdiv_hide_s.'>';
		if ($pic_numb_rsp > $picsperpage) {
			if ($first_page > 1) {
				$ret_str_p .= '<a href="'.$page_href.'" onclick="ShowCat('.$cat_id.', 1);" >&lt;&lt; ' . JText::_('FIRST') . '</a>';
				$ret_str_p .= '<a href="'.$page_href.'" onclick="ShowCat('.$cat_id.', '.($page_num - 1).');" >&lt; ' . JText::_('PREV') . '</a>';
			}
			for ($ip = $first_page; $ip <= $last_page_rsp; $ip++) {
				if ($ip != $page_num) {
					$ret_str_p .= '<a href="'.$page_href.'" onclick="ShowCat('.$cat_id.', '.$ip.');" >'.$ip.'</a>';
				} else {
					$ret_str_p .= '<span style="margin: 2px; padding: 2px;"><b>'.$page_num.'</b></span>';
				}
			}
			if($page_num < $pages_all_rsp) {
				$ret_str_p .= '<a href="'.$page_href.'" onclick="ShowCat('.$cat_id.', '.($page_num + 1).');" >' . JText::_('NEXT') . ' &gt;</a>';
			}
			if ($last_page_rsp < $pages_all_rsp) {
				$ret_str_p .= '<a href="'.$page_href.'" onclick="ShowCat('.$cat_id.', '.$pages_all_rsp.');" >' . JText::_('LAST') . ' &gt;&gt;</a>';
			}
		}
		$ret_str_p .= '</div>';
		
		$html_resp_out .= '<ul data-cat="'.$c_cat->name.'" id="cid'.$c_cat->id.'">
		';

	foreach ($catdata[$c_cat->id] as $c_itm) {
		$tex = strtolower(strrchr($c_itm->thumb, "."));
		$thumb_img = $pics_url .'rsp/'. $c_itm->thumb;
		if (!file_exists($pics_path.'rsp/'.$c_itm->thumb) || !($tex == ".jpeg" || $tex == ".jpg" || $tex == ".png")) {
			$thumb_img = $ps_rs_url.'load/noimage.png';
		}
		$ext = strtolower(strrchr($c_itm->image, "."));
		$demo_img = $pics_url . $c_itm->image;
		if (!file_exists($pics_path.$c_itm->image) || !($ext == ".jpeg" || $ext == ".jpg" || $ext == ".png" || $ext == ".gif" || $ext == ".bmp" || $ext == ".tiff")) {
			$demo_img = $thumb_img;
		}

		$html_resp_out .= '
			<ul>
				<li data-type="media" data-url="'.$demo_img.'"></li>
				<li data-thumbnail-path="'.$thumb_img.'"></li>
				<li data-thumbnail-text>
					<p class="largeLabel">'.$c_itm->name.'</p>
					<p class="smallLabel">'.$c_itm->descr.'</p>
				</li>
				<li data-info>
					<p class="mediaDescriptionHeader">'.$c_itm->name.'</p>
					<p class="mediaDescriptionText">'.$c_itm->descr.'</p>
				</li>
			</ul>
			';
	}
	$html_resp_out .= '
		</ul>
		';
	$i_cat++;
	}
	$html_resp_out .= '</div>
	';
	$html_out .= $html_resp_out;
	$ret_str = '<div id="picpage" style="margin: 10px 0px; text-align: right;">'.$ret_str_p.'</div>';

	//after if responsive
	$html_out .= $ret_str;
	$html_out .= '</div>';
	$html_out .= '</div></td></tr></table>';
	echo $html_out;
	
 } ?>
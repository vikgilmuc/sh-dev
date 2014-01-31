<?php
/**
* @Copyright Copyright (C) 2010- xml/swf
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

/**
 * Item Model for Gallery XML Component
 */
 
// No direct access
 
defined( '_JEXEC' ) or die( 'Restricted access' );

if (!function_exists('PrepareThumbRsp')) {

function iinsta_pear() {
	$ds = DS;
	$inc_paths_arr = explode(PATH_SEPARATOR, get_include_path());
	foreach ($inc_paths_arr as $c_path) {
		if (file_exists($c_path.$ds.'PEAR.php')) {
			return true;
		}
	}
	return false;
}

function PrepareThumbRsp($c_itm, $preptrsp_params)
{
	$ret_arr = array('maden'=>0);
	$rsp_thumb_fname = $preptrsp_params['rsp_path'] . DS . $c_itm->thumb;
	$rspW = (int)$preptrsp_params['thumbnailBaseWidth'];
	$rspH = (int)$preptrsp_params['thumbnailBaseHeight'];
	$resize_type = (int)$preptrsp_params['resize_type'];
	if (file_exists($rsp_thumb_fname)) {
		@$img_inf = getimagesize($rsp_thumb_fname);
		if ($img_inf) {
			$tw = (int)$img_inf[0];
			$th = (int)$img_inf[1];
			if (($rspW < $tw && $tw < 2*$rspW) && ($rspH < $th && $th < 2*$rspH) && ($resize_type == 1 || ($resize_type == 2 && abs($rspH/$rspW - $th/$tw)<0.04))) {
				$ret_arr['maden'] = 1;
				return $ret_arr;
			}
		}
		unlink($rsp_thumb_fname);
	}
	$img_ext_list = array('jpg','png','gif','jpeg');
	$gallery_path = JPATH_SITE . DS . $preptrsp_params['images_folder'];

	$big_imgfn = $gallery_path. DS .$c_itm->image;
	if (!isset($big_imgfn) || !file_exists($big_imgfn)) {
		$big_imgfn = $gallery_path . DS . $c_itm->thumb;
	}
	$par_arr = array('big_imgfn'=>$big_imgfn, 'rsp_thumb_fname'=>$rsp_thumb_fname, 'rspW'=>$rspW, 'rspH'=>$rspH, 'resize_type'=>$resize_type);
	$gtbool = GenerateRspThumb($par_arr);
	if ($gtbool) {
		$ret_arr['maden'] = 2;
	} else {
		$par_arr['big_imgfn'] = JPATH_COMPONENT_SITE . DS . $preptrsp_params['no_image_image'];
		if(GenerateRspThumb($par_arr)) {
			$ret_arr['maden'] = 3;
		} else {
			if(copy($par_arr['big_imgfn'], $rsp_thumb_fname)) {
				$ret_arr['maden'] = 4;
			} else {
				$ret_arr['maden'] = -1;
			}
		}
	}
	return $ret_arr;
}


function UseImgLib($uil_par)
{
	$ret_par = array('succ'=>false);
	$t_w = $uil_par['t_w'];
	$t_h = $uil_par['t_h'];
	$bigi_name = $uil_par['bigi_name'];
	$thumb_name = $uil_par['thumb_name'];
	$big_w = $uil_par['big_w'];
	$big_h = $uil_par['big_h'];
	$ext = $uil_par['ext'];
	$text = $uil_par['text'];
	$resize_type = $uil_par['resize_type'];
	$t_h_a = $uil_par['t_h_a'];
	$t_w_a = $uil_par['t_w_a'];
	$rspW = $uil_par['rspW'];
	$rspH = $uil_par['rspH'];
	//$fperm_old = $uil_par['fperm_old'];
	$img_libs_used = array();
	$img_libs_used[] = 'imagick';
	$img_libs_used[] = 'NetPBM';
	$img_libs_used[] = 'Imlib';
	$img_libs_used[] = 'IM';
	$img_libs_used[] = 'Cairowrapper';
	$img_libs_used[] = 'GD';

	$lib_pear_dir = JPATH_SITE . DS . 'components'. DS .'com_dreamworkgallery'. DS .'sb'. DS .'PEAR';
	if (iinsta_pear()) {
		set_include_path($lib_pear_dir . DS .'image_tranform'. PATH_SEPARATOR .  get_include_path());
	} else {
		set_include_path($lib_pear_dir . PATH_SEPARATOR . $lib_pear_dir . DS .'image_tranform'. PATH_SEPARATOR . get_include_path());
	}

	require_once($lib_pear_dir .DS.'image_tranform'.DS.'Image'.DS.'Transform.php');
	
	$use_Image_Transform = false;
	foreach ($img_libs_used as $c_ilib) {
		$a = Image_Transform::factory($c_ilib);
		if (PEAR::isError($a)) {
		   // die('err1='.$a->getMessage());
		} else {
			$a->load($bigi_name);
			if (PEAR::isError($a)) {
				$ret_par['image_transform'] = 'err2='.$a->getMessage();
			} else {
				$a->resize($t_w,$t_h);
				if (PEAR::isError($a)) {
					$ret_par['image_transform'] = 'err3='.$a->getMessage();
				} else {
					if ($t_h_a) {
						$a->crop($rspW, $rspH, 0, $t_h_a);
					}
					if ($t_w_a) {
						$a->crop($rspW, $rspH, $t_w_a, 0);
					}
					if (PEAR::isError($a)) {
						$ret_par['image_transform'] = 'err3.5='.$a->getMessage();
					} else {
						$a->save($thumb_name);
						if (PEAR::isError($a)) {
							$ret_par['image_transform'] = 'err4='.$a->getMessage();
						} else {
							if (file_exists($thumb_name)) {
								$ret_par['succ'] = true;
								break;
							}
						}
					}
				}
			}
		}
	}

	if (!$ret_par['succ']) {
		$is_png = false;
		$jpg_imgr = false;
		switch ($ext) {
			case 'jpg':
			case 'jpeg':
				if (function_exists('imagecreatefromjpeg')) {
					$jpg_imgr = imagecreatefromjpeg($bigi_name);
				}
			break;
			case 'png':
				if (function_exists('imagecreatefrompng')) {
					$jpg_imgr = imagecreatefrompng($bigi_name);
					//$is_png = true;
					imagesavealpha($jpg_imgr, true);
				}
			break;
			case 'gif':
				if (function_exists('imagecreatefromgif')) {
					$jpg_imgr = imagecreatefromgif($bigi_name);
				}
			break;
			default:
				$ret = true;
		}
		
		if ($ret) {
			$ret_par['succ'] = false;
			return $ret_par;
		}
		if ($jpg_imgr) {
			$dst_img=ImageCreateTrueColor($t_w, $t_h);
			if ($text == "png") {
				$is_png = true;
			}
			if ($is_png) {
				$background_png = imagecolorallocatealpha($dst_img, 255, 255, 255, 127);
				imagecolortransparent($dst_img, $background_png);
				imagealphablending($dst_img, false);
				imagesavealpha($dst_img, true);
			}
			imagecopyresampled($dst_img, $jpg_imgr,0,0,0,0,$t_w,$t_h,$big_w,$big_h);
			switch ($text) {
				case 'jpg':
				case 'jpeg':
					imagejpeg($dst_img, $thumb_name);
				break;
				case 'png':
					imagepng($dst_img, $thumb_name);
				break;
				case 'gif':
					if (function_exists('imagegif')) { 
						imagegif ($dst_img, $thumb_name);
					} else {
						imagejpeg($dst_img, $thumb_name);
					}
				break;
				default:
					$ret_par['succ'] = false;
					return $ret_par;
			}
			imagedestroy($dst_img);
			imagedestroy($jpg_imgr);
			$ret_par['succ'] = true;
		} else {
			$ret_par['succ'] = false;
		}
	}
	return $ret_par;
}

function GenerateRspThumb($par_arr)
{
	$bigi_name = $par_arr['big_imgfn'];
	$thumb_name = $par_arr['rsp_thumb_fname'];
	$rspW = $par_arr['rspW'];
	$rspH = $par_arr['rspH'];
	$resize_type = $par_arr['resize_type'];
	$ext = substr(strrchr($bigi_name, '.'), 1);
	$text = substr(strrchr($thumb_name, '.'), 1);
	$is_picsell = false;
	if ($is_picsell) {
		clearstatcache();
		$fperm_old = fileperms($bigi_name);
		if (($fperm_old & 0444) == 0) {
			chmod($bigi_name, 0644);
		}
	}
	$ret = false;
	$img_inf = getimagesize($bigi_name);
	$big_w = $img_inf[0];
	$big_h = $img_inf[1];
	$t_h_a = false;
	$t_w_a = false;
	if ($resize_type == 1) {
		if (($rspW < $big_w && $big_w < 2*$rspW) && ($rspH < $big_h && $big_h < 2*$rspH)) {
			$copyres = copy($bigi_name, $thumb_name);
			if ($is_picsell) {
				if (($fperm_old & 0444) == 0) {
					chmod($bigi_name, $fperm_old);
				}
			}
			return $copyres;
		} else {
			if ($rspH/$rspW > $big_h/$big_w) {
			   $t_h = $rspH;
			   $t_w = floor(($t_h * $big_w) / $big_h);
			   if ($t_w >= 2*$rspW) {
					$t_w = 2*$rspW - 1;
			   }
			} else {
			   $t_w = $rspW;
			   $t_h = floor(($t_w * $big_h) / $big_w);
			   if ($t_h >= 2*$rspH) {
					$t_h = 2*$rspH - 1;
			   }
			}
		}
	} else {
		$ratio_sub = $rspH/$rspW - $big_h/$big_w;
		if ((abs($ratio_sub) < 0.04) && ($rspW < $big_w && $big_w < 2*$rspW) && ($rspH < $big_h && $big_h < 2*$rspH)) {
			$copyres = copy($bigi_name, $thumb_name);
			if ($is_picsell) {
				if (($fperm_old & 0444) == 0) {
					chmod($bigi_name, $fperm_old);
				}
			}
			return $copyres;
		}
		if ($ratio_sub < 0) {
			$t_w = $rspW;
			$t_h = floor(($t_w * $big_h) / $big_w);
			$t_h_a = floor(($t_h - $rspH)/2);
		} else {
			$t_h = $rspH;
			$t_w = floor(($t_h * $big_w) / $big_h);
			$t_w_a = floor(($t_w - $rspW)/2);
		}
	}
	$uil_par = array();
	$uil_par['t_w'] = $t_w;
	$uil_par['t_h'] = $t_h;
	$uil_par['bigi_name'] = $bigi_name;
	$uil_par['thumb_name'] = $thumb_name;
	$uil_par['big_w'] = $big_w;
	$uil_par['big_h'] = $big_h;
	$uil_par['ext'] = $ext;
	$uil_par['text'] = $text;
	$uil_par['resize_type'] = $resize_type;
	$uil_par['t_h_a'] = $t_h_a;
	$uil_par['t_w_a'] = $t_w_a;
	$uil_par['rspH'] = $rspH;
	$uil_par['rspW'] = $rspW;
	//$uil_par['fperm_old'] = $fperm_old;
	$ret_par = UseImgLib($uil_par);
	if ($is_picsell) {
		if (($fperm_old & 0444) == 0) {
			chmod($bigi_name, $fperm_old);
		}
	}
	return $ret_par['succ'];
}

}

jimport( 'joomla.application.component.model' );

/**
 * Templates Model
 */
if (!class_exists('JModelSST')) {
 if (!class_exists('JModel')) {
  if(function_exists('class_alias')) {
   class_alias('JModelLegacy', 'JModelSST');
  } else {
   class JModelSST extends JModelLegacy
   {
    function __construct()
    {
     parent::__construct();
    }
   }
  }
  } else {
  if(function_exists('class_alias')) {
   class_alias('JModel', 'JModelSST');
  } else {
   class JModelSST extends JModel
   {
    function __construct()
    {
     parent::__construct();
    }
   }
  }
 }
}
class GalleryModelTemplates extends JModelSST
{
    /**
     * Gallery data array
     *
     * @var array
     */
    var $_data;
	var $_id;
	var $_categories;
	var $_cats_info;
	var $_no_image_thumb;
	var $_no_image_image;
	var $_categories_folder;
	var $_images_folder;
	var $_sell_path;
	var $_link_data;
	
	var $_post_arr;
	
	var $_cmpt_name;
	var $_cmpt_cat_tbl;
	var $_cmpt_items_tbl;
	var $_cmpt_orders_tbl;
	var $_cmpt_links_tbl;
	var $_cmpt_catitm_tbl;
	var $_cmpt_tags_tbl;
	var $_cmpt_itags_tbl;
	
	var $all_images;
	var $_chmod_use;
	/**
	 * Constructor that retrieves the ID from the request
	 *
	 * @access    public
	 * @return    void
	*/
	function __construct()
	{
		parent::__construct();
		$this->_post_arr = false;
		$this->_cmpt_name = 'com_dreamworkgallery';
		$this->_cmpt_cat_tbl = 'picsellc';
		$this->_cmpt_orders_tbl = 'picsello';
		$this->_cmpt_links_tbl = 'picselll';
		$this->_cmpt_items_tbl = 'dreamworkgallery';
		$this->_cmpt_catitm_tbl = 'jos_picsellci';
		$this->_cmpt_tags_tbl = 'jos_picsellt';
		$this->_cmpt_itags_tbl = 'jos_picsellit';
		$array = JRequest::getVar('cid',  0, '', 'array');
		$this->_no_image_thumb = 'noimage_thumb.png';
		$this->_no_image_image = 'noimage.png';
		$params = &JComponentHelper::getParams($this->_cmpt_name);
		$this->_categories_folder = $params->get('cat_path', 'components/'.$this->_cmpt_name.'/galleries');
		$this->_images_folder = $params->get('pic_path', 'images/dreamworkgallery/gallery');
		$this->_sell_path = $this->_images_folder;//$params->get('sell_path', 'components/'.$this->_cmpt_name.'/selling');
		$this->_chmod_use = (int)$params->get('chmod_use', '2');
	}
	
	function PrepareThResp($litmid)
	{
		$start_time = (int)time();
		$time_limit = (int)ini_get('max_execution_time');
		if (!(is_int($time_limit) && $time_limit > 0)) {
			$time_limit = 30;
		}
		$end_time = $start_time + $time_limit - 2;
		$ret_arr = array('err'=>false, 'litmid'=>$litmid, 'errid'=>array());
		
		if(version_compare(JVERSION, '3.0.0', 'ge')) {
			$params_comg = JComponentHelper::getParams('com_dreamworkgallery', true);
		} else {
			$componentstr = &JComponentHelper::getComponent('com_dreamworkgallery');
			$params_comg = new JParameter($componentstr->params);
		}
		$rsp = 'rsp';
		$gallery_path = JPATH_SITE . DS .$this->_images_folder;
		$rsp_path = $gallery_path . DS . $rsp;
		if (!is_dir($rsp_path)) {
			mkdir($rsp_path, 0777);
			chmod($rsp_path, 0777);
		}
		$thumbnailBaseWidth = $params_comg->get('thumbnailBaseWidth', "180");
		$thumbnailBaseHeight = $params_comg->get('thumbnailBaseHeight', "180");
		$resize_type = $params_comg->get('resize_type', "2");
		$preptrsp_params = array('thumbnailBaseWidth'=>$thumbnailBaseWidth, 'thumbnailBaseHeight'=>$thumbnailBaseHeight, 'images_folder'=>$this->_images_folder, 'sell_path'=>$this->_sell_path, 'rsp_path'=>$rsp_path, 'no_image_image'=>$this->_no_image_image, 'resize_type'=>$resize_type);
		$qlimit = ($time_limit - 2) * 20;
		
		$next_q = true;
		while ($next_q) {
			$query = 'SELECT * FROM #__'.$this->_cmpt_items_tbl.' WHERE id>'.$ret_arr['litmid'].' ORDER BY id LIMIT ' . $qlimit;
			$this->_db->setQuery($query);
			$items = $this->_db->loadObjectList();
			if ($items && is_array($items) && count($items) > 0) {
				foreach ($items as $c_itm) {
					$retf_arr = PrepareThumbRsp($c_itm, $preptrsp_params);
					if ($retf_arr['maden'] < 1) {
						$ret_arr['err'] = true;
						$ret_arr['errid'][$c_itm->id] = $retf_arr['maden'];
					}
					$ret_arr['litmid'] = $c_itm->id;
					if ($end_time <= time()) {
						return $ret_arr;
					}
				}
			} else {
				$next_q = false;
			}
			if (count($items) < $qlimit) {
				$next_q = false;
			}
			$items = false;
		}
		$ret_arr['litmid'] = 0;
		return $ret_arr;
	}

}
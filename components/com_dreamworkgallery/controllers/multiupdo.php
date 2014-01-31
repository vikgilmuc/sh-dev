<?php
/**
* @Copyright Copyright (C) 2011- xml/swf
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/
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

jimport('joomla.application.component.controller');
 
if (!class_exists('JControllerSST')) {
 if (!class_exists('JController')) {
  if(function_exists('class_alias')) {
   class_alias('JControllerLegacy', 'JControllerSST');
  } else {
   class JControllerSST extends JControllerLegacy
   {
    function __construct()
    {
     parent::__construct();
    }
   }
  }
  } else {
  if(function_exists('class_alias')) {
   class_alias('JController', 'JControllerSST');
  } else {
   class JControllerSST extends JController
   {
    function __construct()
    {
     parent::__construct();
    }
   }
  }
 }
}
class GalleryControllerMultiupdo extends JControllerSST
{
	function __construct()
	{
		parent::__construct();
	}

function upfuf()
{
$version = new JVersion;
$jver = $version->getShortVersion();
if(substr($jver, 0, 3) != '1.5'){
	jimport('joomla.html.parameter');
}

$root_dirn = JPATH_ROOT . DS;

if(version_compare(JVERSION, '3.0.0', 'ge')) {
	$params_comg = JComponentHelper::getParams('com_dreamworkgallery', true);
} else {
	$componentstr = &JComponentHelper::getComponent('com_dreamworkgallery');
	$params_comg = new JParameter($componentstr->params);
}
$thumb_x = $params_comg->get('thumb_x', '100');
$thumb_y = $params_comg->get('thumb_y', '75');
$secur_w = $params_comg->get('secur_w', 'telx3eis7d');
if ($secur_w == 'telx3eis7d') {
	exit();
}
if (!isset($_POST['decret']) || $_POST['decret'] != $secur_w) {
	exit();
}
$pic_path = $params_comg->get('pic_path', 'images/dreamworkgallery/gallery');

if (DS != '/') {
	$pic_path = str_replace('/', DS, $pic_path);
}

$gallery_path = $root_dirn . $pic_path . DS;
$rsp = 'rsp';
$rsp_path = $gallery_path . $rsp;
if (!is_dir($rsp_path)) {
	mkdir($rsp_path, 0777);
	chmod($rsp_path, 0777);
}

if ($_FILES["Filedata"]["error"] > 0 || !(strtolower(substr($_FILES["Filedata"]["name"], -5)) == ".jpeg" || strtolower(substr($_FILES["Filedata"]["name"], -4)) == ".jpg" || strtolower(substr($_FILES["Filedata"]["name"], -4)) == ".gif"  || strtolower(substr($_FILES["Filedata"]["name"], -4)) == ".bmp" || strtolower(substr($_FILES["Filedata"]["name"], -4)) == ".png")) {
	exit();
} else {
	$img_ext = strtolower(strrchr($_FILES["Filedata"]["name"], '.'));
	$pic_name = substr($_FILES["Filedata"]["name"], 0, -strlen($img_ext));
	$image_name_end = preg_replace('/[^a-zA-Z0-9_]/', '_', $pic_name) . $img_ext;
	$image_name = $image_name_end;
	$noex_f_index = 0;
	while(file_exists($gallery_path . $image_name_end)) {
		$noex_f_index++;
		$image_ext = strrchr($image_name, '.');
		$image_name_end = substr($image_name, 0, -strlen($image_ext)) . '_' . $noex_f_index . $image_ext;				
	}
	if ($image_name_end != $image_name) {
		$image_name = $image_name_end;
	}
	move_uploaded_file($_FILES["Filedata"]["tmp_name"], $gallery_path . $image_name);
}

$thumb_name = 'noimage_thumb.jpg';
if (file_exists($gallery_path . $image_name)) {
	$thumb_name_end = 'thumb_' . $image_name;
	$thumb_name = $thumb_name_end;
	$noex_f_index = 0;
	while(file_exists($gallery_path . $thumb_name_end)) {
		$noex_f_index++;
		$thumb_ext = strrchr($thumb_name, '.');
		$thumb_name_end = substr($thumb_name, 0, -strlen($thumb_ext)) . '_' . $noex_f_index . $thumb_ext;				
	}
	if ($thumb_name_end != $thumb_name) {
		$thumb_name = $thumb_name_end;
	}
	$thumb_ext = substr(strrchr($thumb_name, '.'), 1);
	$cnt_ext_chr = -(strlen($thumb_ext) + 1);
	$thumb_noext = $gallery_path . substr($thumb_name, 0, $cnt_ext_chr);
	$image_noext = $gallery_path . substr($image_name, 0, $cnt_ext_chr);
	$this->CreateThumbF($image_noext, $thumb_noext, $thumb_ext, $thumb_x, $thumb_y);
	$resize_type = (int)$params_comg->get('resize_type', "2");
	$rspW = (int)$params_comg->get('thumbnailBaseWidth', "180");
	$rspH = (int)$params_comg->get('thumbnailBaseHeight', "180");	
	$par_arr = array('big_imgfn'=>$image_noext.'.'.$thumb_ext, 'rsp_thumb_fname'=>$gallery_path .$rsp.DS.$thumb_name, 'rspW'=>$rspW, 'rspH'=>$rspH, 'resize_type'=>$resize_type);
	$ret_ptrsp = GenerateRspThumb($par_arr);
	if (!$ret_ptrsp) {
		$par_arr['big_imgfn'] = $root_dirn . 'components'.DS.'com_dreamworkgallery'.DS.'noimage.png';
		if (!GenerateRspThumb($par_arr)) {
			copy($par_arr['big_imgfn'], $par_arr['rsp_thumb_fname']);
		}
	}
}

$ord_numb = 1;
if (is_numeric($_POST['catid'])) {
	$cat_id = $_POST['catid'];
} else {
	$cat_id = 1;
}
$q_maxord = ' SELECT MAX(ordnum) as maxordnum FROM #__dreamworkgallery WHERE catid = ' . $cat_id;
$database = & JFactory::getDBO();
$database->setQuery($q_maxord);
$img_info = $database->loadObject();
if (!$img_info) {
	$ord_numb = 1;
} else {
	$ord_numb = $img_info->maxordnum + 1;
}

$insert_q = "INSERT INTO #__dreamworkgallery VALUES (NULL,".$cat_id.",".$ord_numb.",1,'".$pic_name."','".$pic_name."','".'thumb_'.$pic_name."','".$pic_name."','" .$thumb_name. "','".$image_name."','','',0,0,1)";
$database->setQuery($insert_q);
$database->query();
echo "Picture successfully saved";
$mainframe = JFactory::getApplication();
$mainframe->close();
}

function CreateThumbF($bigi_name, $thumb_name, $ext, $t_x, $t_y)
{
	$jpg_imgr = false;
	switch ($ext) {
		case 'jpg':
		case 'jpeg':
			if (function_exists('imagecreatefromjpeg')) {
				$jpg_imgr = imagecreatefromjpeg($bigi_name . '.' . $ext);
			}
		break;
		case 'png':
			if (function_exists('imagecreatefrompng')) {
				$jpg_imgr = imagecreatefrompng($bigi_name . '.' . $ext);
			}
		break;
		case 'gif':
			if (function_exists('imagecreatefromgif')) {
				$jpg_imgr = imagecreatefromgif($bigi_name . '.' . $ext);
			}
		break;
		default:
			return false;
	}
	
	if ($jpg_imgr) {
		$big_x = imagesx($jpg_imgr);
		$big_y = imagesy($jpg_imgr);
		
		$ratio_orig = $big_x/$big_y;
		if ($t_x/$t_y > $ratio_orig) {
		   $t_x = $t_y * $ratio_orig;
		} else {
		   $t_y = $t_x/$ratio_orig;
		}

		$dst_img=ImageCreateTrueColor($t_x, $t_y);
		imagecopyresampled($dst_img, $jpg_imgr,0,0,0,0,$t_x,$t_y,$big_x,$big_y);
		switch ($ext) {
			case 'jpg':
			case 'jpeg':
				imagejpeg($dst_img, $thumb_name . '.' . $ext, 100);
			break;
			case 'png':
				imagepng($dst_img, $thumb_name . '.' . $ext, 0);
			break;
			case 'gif':
				if (function_exists('imagegif')) { 
					imagegif ($dst_img, $thumb_name . '.' . $ext);
				} else {
					imagejpeg($dst_img, $thumb_name . '.' . $ext, 100);
				}
			break;
			default:
				return false;
		}
		imagedestroy($jpg_imgr);
		imagedestroy($dst_img);
	} else {
		return false;
	}
}

}

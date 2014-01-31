<?php
/**
* @Copyright Copyright (C) 2011- xml/swf
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
 * Item Model
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
class GalleryModelItem extends JModelSST
{
    /**
     * Gallery data array
     *
     * @var array
     */
    var $_data;
	var $_id;
	var $_categories;
	var $_no_image_thumb;
	var $_no_image_image;
	var $_categories_folder;
	var $_images_folder;
	/**
	 * Constructor that retrieves the ID from the request
	 *
	 * @access    public
	 * @return    void
	*/
	function __construct()
	{
		parent::__construct();
	 
		$array = JRequest::getVar('cid',  0, '', 'array');
		$this->setId((int)$array[0]);
		$this->_no_image_thumb = 'noimage_thumb.jpg';
		$this->_no_image_image = 'noimage.jpg';
		$params = &JComponentHelper::getParams('com_dreamworkgallery');
		$this->_categories_folder = $params->get('cat_path', 'images/dreamworkgallery/galleries');
		$this->_images_folder = $params->get('pic_path', 'images/dreamworkgallery/gallery');
	}
	
	/**
	 * Method to set the item id
	 *
	 * @access    public
	 * @param    int item identifier
	 * @return    void
	 */
	function setId($id)
	{
		// Set id and wipe data
		$this->_id        	= $id;
		$this->_data  		= null;
	}

	/**
	 * Method to get a item data
	 * @return object with data
	 */
	 
	function &getData()
	{
		// Load the data
		if (empty( $this->_data )) {
			$query = ' SELECT * FROM #__dreamworkgallery '
					 . ' WHERE id = ' . $this->_id;
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObject();
		}
		if (!$this->_data) {
			$this->_data = new stdClass();
			$this->_data->id = 0;
			$this->_data->catid = 1;			
			$this->_data->ordnum = 1;
			$this->_data->publish = 1;
			$this->_data->name = null;
			$this->_data->descr = null;
			$this->_data->altthumb = 'no image';
			$this->_data->altlarge = 'no image';
			$this->_data->thumb = $this->_no_image_thumb;
			$this->_data->image = $this->_no_image_image;
			$this->_data->linkname = null;
			$this->_data->linkit = null;
			$this->_data->reg_price = null;
			$this->_data->dis_price = null;
			$this->_data->medfld = 1;
		}
		return $this->_data;
	}
	function &getCategories()
	{
		if (empty( $this->_categories )) {
			$query = 'SELECT id,ordnum,publish,name FROM #__dreamworkgalleryc ORDER BY ordnum';
			$this->_categories = $this->_getList( $query );
			//$this->_db->setQuery( $query );
			//$this->_categories = $this->_db->loadObject();
		}
		return 	$this->_categories;
	}
	//Return Max ordnum (order number) of the category
	function getMaxOrder($categoryId)
	{
		$query = ' SELECT MAX(ordnum) as maxordnum FROM #__dreamworkgallery WHERE catid = ' . $categoryId;
		$this->_db->setQuery( $query );
		$maxordn = $this->_db->loadObject();
//print_r($maxordn);exit();
		if (!$maxordn) {
			$ret_Maxnumber = 0;
		} else {
			$ret_Maxnumber = $maxordn->maxordnum;
		}
		return $ret_Maxnumber;
	}
	/**
	 * Method to store a record
	 *
	 * @access    public
	 * @return    boolean    True on success
	 */
	function store()
	{
		$row =& $this->getTable();
	 
		$data = JRequest::get( 'post' );
		$data['descr'] = $_POST['descr'];
		if ($_POST['medfld'] == 1) {
			if (($_FILES["file1"]["error"] > 0 || !(strtolower(substr($_FILES["file1"]["name"], -4)) == ".swf" || strtolower(substr($_FILES["file1"]["name"], -4)) == ".flv" || strtolower(substr($_FILES["file1"]["name"], -4)) == ".mp4" || strtolower(substr($_FILES["file1"]["name"], -4)) == ".mov"))) {
				$data['medfld'] = 1;
			} else {
				$data['medfld'] = 2;
			}
		}
		$this->setId((int)$data['id']);
		$old_picinfo = $this->getData();
		
		//set the order
		$max_ord_num = $this->getMaxOrder($data['catid']);
		
		$update_order_after_insert = array();
		$update_order_after_insert['insert_flag'] = false;
		$update_order_after_insert['new_ordnum'] = $data['ordnum'];
		$update_order_after_insert['new_catid'] = $data['catid'];
		if ($old_picinfo->id > 0) {
			$update_order_after_insert['old_ordnum'] = $old_picinfo->ordnum;
			if ($old_picinfo->catid == $data['catid']) {
				$update_order_after_insert['flag'] = 2;
			} else {
				$update_order_after_insert['flag']  = 3;
				$update_order_after_insert['old_catid'] = $old_picinfo->catid;
			}
		} else {
			$update_order_after_insert['flag']  = 1;
		}
		
		//$update_order_after_insert_flag = false;
		if($max_ord_num == 0) {
			$data['ordnum'] = 1;
		} else {
			if ($data['ordnum'] > 0 && $data['ordnum'] <= $max_ord_num) {
				//if picture new or old picture get different ordnumber - than make reorder
				if ($old_picinfo->id == 0 || $data['ordnum'] != $old_picinfo->ordnum || $update_order_after_insert['flag'] == 3) {
					$update_order_after_insert['insert_flag'] = true;
					$update_order_after_insert['new_ordnum'] = $data['ordnum'];
					//$data['ordnum'] = $max_ord_num + 2;
				}
			} else {
				if ($update_order_after_insert['flag']  == 2) {
					$data['ordnum'] = $max_ord_num;
					$update_order_after_insert['insert_flag'] = true;
					$update_order_after_insert['new_ordnum'] = $data['ordnum'];
				} else {
					$data['ordnum'] = $max_ord_num + 1;
				}
			}
		}
		
		
		//FILES TO UPLOAD
		$move_uploaded_file_flag = false;
		$move_uploaded_image_file_flag = false;
		$gallery_path = JPATH_SITE . DS .$this->_images_folder;//JPATH_COMPONENT_SITE . DS . 'gallery' . DS;
		$new_gallery_path = $gallery_path;//$gallery_path . 'g' . $data['catid'];
		if (!is_dir($new_gallery_path)) {
			mkdir($new_gallery_path, 0777);
		}
		$gallery_path .= DS;
		$new_gallery_path = $gallery_path;
		if (!file_exists($new_gallery_path .  $this->_no_image_image)) {
			copy(JPATH_COMPONENT_SITE . DS . $this->_no_image_image, $new_gallery_path . $this->_no_image_image);
		}
		if (!file_exists($new_gallery_path . $this->_no_image_thumb)) {
			copy(JPATH_COMPONENT_SITE . DS . $this->_no_image_thumb, $new_gallery_path . $this->_no_image_thumb);
		}
		
		
		//$g_name = 'g' . $data['catid'];
		$save_file_path = $gallery_path;
		
		if ($_FILES["file"]["error"] > 0 || !(strtolower(substr($_FILES["file"]["name"], -5)) == ".jpeg" || strtolower(substr($_FILES["file"]["name"], -4)) == ".jpg" || strtolower(substr($_FILES["file"]["name"], -4)) == ".gif"  || strtolower(substr($_FILES["file"]["name"], -4)) == ".bmp" || strtolower(substr($_FILES["file"]["name"], -4)) == ".png" || strtolower(substr($_FILES["file1"]["name"], -4)) == ".swf" || strtolower(substr($_FILES["file1"]["name"], -4)) == ".flv" || strtolower(substr($_FILES["file1"]["name"], -4)) == ".mp4" || strtolower(substr($_FILES["file1"]["name"], -4)) == ".mov")) {

		} else {
			if ($old_picinfo->thumb != $this->_no_image_thumb) {
				$old_thumb_file = $new_gallery_path . $old_picinfo->thumb;
				if (file_exists($old_thumb_file)) {
					unlink($old_thumb_file);
				}
			}
			
			$old_picinfo->thumb = $_FILES["file"]["name"];
			$thumb_name_end = $old_picinfo->thumb;
			$noex_f_index = 0;
			while(file_exists($save_file_path . $thumb_name_end)) {
				$noex_f_index++;
				$thumb_ext = strrchr($old_picinfo->thumb, '.');
				$thumb_name_end = substr($old_picinfo->thumb, 0, -strlen($thumb_ext)) . '_' . $noex_f_index . $thumb_ext;				
			}
			
			if ($thumb_name_end != $old_picinfo->thumb) {
				$old_picinfo->thumb = $thumb_name_end;
			}
			
			$move_uploaded_file_flag = true;	
		}
		
		if ($_FILES["file1"]["error"] > 0 || $_POST['medfld'] > 10 || !(strtolower(substr($_FILES["file1"]["name"], -5)) == ".jpeg" || strtolower(substr($_FILES["file1"]["name"], -4)) == ".jpg" || strtolower(substr($_FILES["file1"]["name"], -4)) == ".gif"  || strtolower(substr($_FILES["file1"]["name"], -4)) == ".bmp" || strtolower(substr($_FILES["file1"]["name"], -4)) == ".png" || strtolower(substr($_FILES["file1"]["name"], -4)) == ".swf" || strtolower(substr($_FILES["file1"]["name"], -4)) == ".flv" || strtolower(substr($_FILES["file1"]["name"], -4)) == ".mp4" || strtolower(substr($_FILES["file1"]["name"], -4)) == ".mov")) {

		} else {
			if ($old_picinfo->image != $this->_no_image_image) {
				$old_image_file = $new_gallery_path . $old_picinfo->image;
				if (file_exists($old_image_file)) {
					unlink($old_image_file);
				}
			}
			
			$old_picinfo->image = $_FILES["file1"]["name"];
			$image_name_end = $old_picinfo->image;
			$noex_f_index = 0;
			while(file_exists($save_file_path . $image_name_end)) {
				$noex_f_index++;
				$image_ext = strrchr($old_picinfo->image, '.');
				$image_name_end = substr($old_picinfo->image, 0, -strlen($image_ext)) . '_' . $noex_f_index . $image_ext;				
			}
			
			if ($image_name_end != $old_picinfo->image) {
				$old_picinfo->image = $image_name_end;
			}
			
			$move_uploaded_image_file_flag = true;	
		}

		//If thumb and Large image files are equal - rename thumb
		if($old_picinfo->thumb == $old_picinfo->image) {
			$image_ext = strrchr($old_picinfo->thumb, '.');
			$old_picinfo->thumb = substr($old_picinfo->thumb, 0, -strlen($image_ext)) . '_thumb' . $image_ext;			
		}
		if ($move_uploaded_file_flag) {
			$save_file_name = $save_file_path . $old_picinfo->thumb;
		}
		if ($move_uploaded_image_file_flag) {
			$save_image_file_name = $save_file_path . $old_picinfo->image;
		}
		
		$data['thumb'] = $old_picinfo->thumb;
		if ($data['thumb'] == $this->_no_image_thumb) {
			$data['thumb'] = md5('thumb'.rand().'r'.rand().'b'.time().'k'.rand().'d') . '.png';
			$old_picinfo->thumb = $data['thumb'];
			copy(JPATH_COMPONENT_SITE . DS . $this->_no_image_thumb, $new_gallery_path . $data['thumb']);
		}
		$data['image'] = $old_picinfo->image;
		
		// Bind the form fields to the hello table
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return $old_picinfo->id;
		}
	 
		// Make sure the item record is valid
		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return $old_picinfo->id;
		}
	 
		// Store the web link table to the database
		if (!$row->store()) {
			$this->setError($this->_db->getErrorMsg());
			return $old_picinfo->id;
		}
		
		//Reorder Pics
		if ($old_picinfo->id > 0) {
			$update_order_after_insert['id'] = $old_picinfo->id;			
		} else {
			$update_order_after_insert['id'] = $this->_db->insertid();
		}
		
		if ($update_order_after_insert['insert_flag']) {
			if ($update_order_after_insert['flag'] == 1 || $update_order_after_insert['flag'] == 3) {
				$this->ReorderPics($update_order_after_insert['new_catid'], $update_order_after_insert['new_ordnum'], $update_order_after_insert['id']);
			} else {
				if ($update_order_after_insert['flag'] == 2) {
					$this->ReorderPics($update_order_after_insert['new_catid'], $update_order_after_insert['new_ordnum'], $update_order_after_insert['id'], $update_order_after_insert['old_ordnum']);
				}
			}
		}
		if ($update_order_after_insert['flag'] == 3) {
			$this->ReorderDesc($update_order_after_insert['old_catid'], $update_order_after_insert['old_ordnum']);
		}
		//UPLOAD UPLOADED FILES
		if ($move_uploaded_image_file_flag && $_POST['medfld'] < 11 ) {
			move_uploaded_file($_FILES["file1"]["tmp_name"], $save_image_file_name);
		}
		if ($move_uploaded_file_flag) {
			move_uploaded_file($_FILES["file"]["tmp_name"], $save_file_name);
		}

		
		$rsp = 'rsp';
		$gallery_pathr = JPATH_SITE . DS .$this->_images_folder;
		$rsp_path = $gallery_pathr . DS . $rsp;
		if (!is_dir($rsp_path)) {
			mkdir($rsp_path, 0777);
			chmod($rsp_path, 0777);
		}
		$params = &JComponentHelper::getParams('com_dreamworkgallery');
		$thumbnailBaseWidth = $params->get('thumbnailBaseWidth', "180");
		$thumbnailBaseHeight = $params->get('thumbnailBaseHeight', "180");
		$resize_type = $params->get('resize_type', "2");
		$preptrsp_params = array('thumbnailBaseWidth'=>$thumbnailBaseWidth, 'thumbnailBaseHeight'=>$thumbnailBaseHeight, 'images_folder'=>$this->_images_folder, 'sell_path'=>$this->_images_folder, 'rsp_path'=>$rsp_path, 'no_image_image'=>$this->_no_image_image, 'resize_type'=>$resize_type);
		$c_itm = new stdClass();
		foreach ($data as $dkey=>$dval) {
			$c_itm->{$dkey} = $dval;
		}
		PrepareThumbRsp($c_itm, $preptrsp_params);

		
		return $update_order_after_insert['id'];
	}
	
	function ReorderPics($cat_id, $new_ordnum, $pic_id, $old_ordnum = false)
	{
		if ($old_ordnum) {
			if ($new_ordnum > $old_ordnum) {
				$add_q = '- 1 WHERE catid = ' . $cat_id . ' AND ordnum <= ' . $new_ordnum . ' AND ordnum > ' . $old_ordnum;
			} else {
				$add_q = '+ 1 WHERE catid = ' . $cat_id . ' AND ordnum < ' . $old_ordnum . ' AND ordnum >= ' . $new_ordnum;
			}
		} else {
			$add_q = '+ 1 WHERE catid = ' . $cat_id . ' AND ordnum >= ' . $new_ordnum;
		}
		$up_query = 'UPDATE #__dreamworkgallery  SET ordnum = ordnum ' . $add_q;
		$this->_db->setQuery($up_query);
		$this->_db->query();

		$up_query = 'UPDATE #__dreamworkgallery  SET ordnum = ' . $new_ordnum . ' WHERE id = ' . $pic_id;
		$this->_db->setQuery($up_query);
		$this->_db->query();
	}
	function ReorderDesc($cat_id, $deleted_ordnum)
	{
		$up_query = 'UPDATE #__dreamworkgallery  SET ordnum = ordnum - 1 WHERE catid = ' . $cat_id . ' AND ordnum > '.$deleted_ordnum;
		$this->_db->setQuery($up_query);
		$this->_db->query();
	}
	
	/**
	 * unpublish one record from link
	 * @return void
	 */	
	function unpublish()
	{
		$p_id = JRequest::getVar( 'picid', 0, 'get', 'int' );
		if ($p_id && $p_id != 0) {
			$up_query = 'UPDATE #__dreamworkgallery  SET publish = 0 WHERE id = ' . $p_id;
			$this->_db->setQuery($up_query);
			$this->_db->query();
		}
	}
	/**
	 * publish one record from link
	 * @return void
	 */	
	function publish()
	{
		$p_id = JRequest::getVar( 'picid', 0, 'get', 'int' );
		if ($p_id && $p_id > 0) {
			$up_query = 'UPDATE #__dreamworkgallery  SET publish = 1 WHERE id = ' . $p_id;
			$this->_db->setQuery($up_query);
			$this->_db->query();
		}
	}
	/**
	 * Method to delete record(s)
	 *
	 * @access    public
	 * @return    boolean    True on success
	 */
	function delete()
	{
		$result = true;
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$row =& $this->getTable();
		$gallery_path = JPATH_SITE . DS . $this->_images_folder . DS;//JPATH_COMPONENT_SITE . DS . 'gallery' . DS;
		
		foreach($cids as $cid) {
			$this->setId($cid);
			$pic_info = $this->getData();
			
			if (!$row->delete( $cid )) {
				$this->setError( $row->getErrorMsg() );
				$result = false;//return false;
			} else {
				if ($pic_info->image != $this->_no_image_image) {
					$image_file_name = $gallery_path . $pic_info->image;
					if (file_exists($image_file_name)) {
						unlink($image_file_name);
					}
				}
				if ($pic_info->thumb != $this->_no_image_thumb) {
					$thumb_file_name = $gallery_path . $pic_info->thumb;
					if (file_exists($thumb_file_name)) {
						unlink($thumb_file_name);
					}
				}
				$this->ReorderDesc($pic_info->catid, $pic_info->ordnum);
			}
		}
		return $result;
	}
	
}

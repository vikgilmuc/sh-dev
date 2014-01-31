<?php
/**
* @Copyright Copyright (C) 2011- xml/swf
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/
// No direct access
 
defined( '_JEXEC' ) or die( 'Restricted access' );

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
class GalleryControllerDreamworkgallery extends JControllerSST
{

    function display()
    {
		JRequest::setVar( 'view', 'dreamworkgallery' );
		JRequest::setVar( 'layout', 'default' );
        parent::display();
    }
	
	function catpics()
	{
		$video_ext_arr = array(1=>'.swf','.flv','.mp4','.mov','.m4v');
		$szarr = array('s'=>array('sz'=>'s', 'szsb'=>'', 'sflagp'=>128, 'sflag'=>64, 'toglfl'=>1, 'szsizelng'=>JText::_('S_SIZE')), 'm'=>array('sz'=>'m', 'sflagp'=>32, 'sflag'=>16, 'toglfl'=>2, 'szsizelng'=>JText::_('M_SIZE')), 'l'=>array('sz'=>'l', 'sflagp'=>8, 'sflag'=>4, 'toglfl'=>3, 'szsizelng'=>JText::_('L_SIZE')), 'xl'=>array('sz'=>'', 'sflagp'=>2, 'sflag'=>1, 'toglfl'=>4, 'szsizelng'=>JText::_('XL_SIZE')), 'el'=>array('sz'=>'el', 'sflagp'=>512, 'sflag'=>256, 'toglfl'=>5, 'szsizelng'=>JText::_('EL_SIZE')), 'gl'=>array('sz'=>'gl', 'sflagp'=>2048, 'sflag'=>1024, 'toglfl'=>6, 'szsizelng'=>JText::_('GL_SIZE')));
		
		$mainframe = JFactory::getApplication();
		$model = $this->getModel('gallery');//&$this->getModel();
		$cat_id = JRequest::getInt('gcatid');
		$page_num = JRequest::getInt('pagenum');
		$picsperpage = JRequest::getInt('picsperpage');
		$gCDP_params = array();
		$gCDP_params['cat_id'] = $cat_id;
		$gCDP_params['page_num'] = $page_num;
		$gCDP_params['picsperpage'] = $picsperpage;
		$data = $model->getCatDataPg($gCDP_params);
		if(version_compare(JVERSION, '1.6', 'ge')){
			jimport('joomla.html.parameter');
		}
		if(version_compare(JVERSION, '3.0.0', 'ge')) {
		   $params_comg = JComponentHelper::getParams('com_dreamworkgallery', true);
		} else {
		   $componentstr = &JComponentHelper::getComponent('com_dreamworkgallery');
		   $params_comg = new JParameter($componentstr->params);
		}

		$pic_path = $params_comg->get('pic_path', 'images/dreamworkgallery/gallery');
		$pics_url = JURI::root().$pic_path . '/';
		$picsdir = $pic_path;
		$pics_path = JPATH_SITE . DS . $pic_path . DS ;
		if (DS != '/') {
			$pics_path = str_replace('/', DS, $pics_path);
		}

		$picsdir .= '/';
		$picsdir = JURI::root() . $picsdir;
		
		$pic_numb = $data['cnt'];
		$page_index_num = 10;
		
		$sb_path = JURI::root().'components/com_dreamworkgallery/sb/';
		
		//$picsperpage = $params_comg->get('nrOfThumbsPerPage', '30');

		if ($pic_numb > $picsperpage) {
			$pages_all = ceil($pic_numb / ($picsperpage));
			$last_page = ceil($page_num / $page_index_num) * $page_index_num;
			if ($last_page > $pages_all) {
				$last_page = $pages_all;
			}
			if ($last_page < $page_index_num) {
				$first_page = 1;
			} else {
				$first_page = $last_page - $page_index_num + 1;
			}
		}

		$ret_str = '';

$ps_url = JURI::root().'components/com_dreamworkgallery/';
$ps_rs_url = $ps_url.'sb/responsive/';


	$html_resp_out = '';

	foreach ($data['data'] as $c_itm) {
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
	$ret_str .= $html_resp_out;

		$ret_str_p = '';
		//$page_href = 'javascript:void(0);';
		$page_href = 'javascript:scrollTo(0,0);';
		if ($pic_numb > $picsperpage) {
			if ($first_page > 1) {
				$ret_str_p .= '<a href="'.$page_href.'" onclick="ShowCat('.$cat_id.', 1);" >&lt;&lt; ' . JText::_('FIRST') . '</a>';
				$ret_str_p .= '<a href="'.$page_href.'" onclick="ShowCat('.$cat_id.', '.($page_num - 1).');" >&lt; ' . JText::_('PREV') . '</a>';
			}
			for ($ip = $first_page; $ip <= $last_page; $ip++) {
				if ($ip != $page_num) {
					$ret_str_p .= '<a href="'.$page_href.'" onclick="ShowCat('.$cat_id.', '.$ip.');" >'.$ip.'</a>';
				} else {
					$ret_str_p .= '<span style="margin: 2px; padding: 2px;"><b>'.$page_num.'</b></span>';
				}
			}
			if($page_num < $pages_all) {
				$ret_str_p .= '<a href="'.$page_href.'" onclick="ShowCat('.$cat_id.', '.($page_num + 1).');" >' . JText::_('NEXT') . ' &gt;</a>';
			}
			if ($last_page < $pages_all) {
				$ret_str_p .= '<a href="'.$page_href.'" onclick="ShowCat('.$cat_id.', '.$pages_all.');" >' . JText::_('LAST') . ' &gt;&gt;</a>';
			}
		}
		$ret_str .= "_sss_sss_sss_" . $ret_str_p;

		echo $ret_str;
		$mainframe->close();
	}
	
}

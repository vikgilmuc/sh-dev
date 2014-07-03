<?php
/**
* @file
* @brief    sigplus Image Gallery Plus Imagelightbox engine
* @author   Levente Hunyadi
* @version  1.4.2
* @remarks  Copyright (C) 2009-2010 Levente Hunyadi
* @remarks  Licensed under GNU/GPLv3, see http://www.gnu.org/licenses/gpl-3.0.html
* @see      http://hunyadi.info.hu/projects/sigplus
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
* Support class for Milkbox (MooTools-based).
* @see http://reghellin.com/milkbox/
*/
class SIGPlusimagelightboxEngine extends SIGPlusLightboxEngine {
	public function getIdentifier() {
		return 'imagelightbox';
	}
    
    
 
    
    protected function addCommonScripts() {
        echo("in addcomoscript image");
        $this->addJQuery();
        $document = JFactory::getDocument();
        
        $this->addScript('/plugins/content/sigplus/engines/imagelightbox/js/imagelightbox.js');
        parent::addCommonScripts();
        
    }
    
	public function addScripts($galleryid, SIGPlusGalleryParameters $params) {
		if ($params->linkage != 'inline') {
			throw new SIGPlusNotSupportedException();
            
             $script = '__jQuery__("#'.$galleryid.' a[rel|=\'imagelightbox\']").each(function(index, el) { __jQuery__(el).imagelightbox'.$this->getCustomParameters($params).'); });';
        $this->addOnReadyScript($script);
		}

		
	}
    
    
   

    
    
    
    
}

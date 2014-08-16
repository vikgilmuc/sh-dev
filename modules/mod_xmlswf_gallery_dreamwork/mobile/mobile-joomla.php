<?php
	/**
	 * Ladda script
	 */
	$document = JFactory::getDocument();
	$server_path = JURI::root();
	$document->addStyleSheet( $server_path.'modules/'. PLUGINNAME .'/mobile/css/app.min.css');
	$document->addStyleSheet( $server_path.'modules/'. PLUGINNAME .'/mobile/css/royalslider.min.css');
	$document->addStyleSheet( $server_path.'modules/'. PLUGINNAME .'/mobile/css/default/rs-default.min.css');


	$document->addScript( $server_path.'modules/'. PLUGINNAME .'/mobile/js/libs.js');
	$document->addScript( $server_path.'modules/'. PLUGINNAME .'/mobile/js/tpl.min.js');
	$document->addScript( $server_path.'modules/'. PLUGINNAME .'/mobile/js/app.min.js');

?>
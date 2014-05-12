<?php 
	$stMegaMenu = false;
	$moduleTable = JTableModule::getInstance('module');
	
	$pattern = "/\[stmegamenu[^\]]*\/]/";
	preg_match($pattern, $item->title, $match);
	
	if (count($match) && count($match[0])) 
	{
		$pattern = "/stmegamenu=[0-9]+/";
		preg_match($pattern, $match[0], $dropdowWidth);
		
		if (count($dropdowWidth)) {
			$dropdowWidth = 'span'.str_replace('stmegamenu=', '', $dropdowWidth[0]);	
		} else {
			$dropdowWidth = 'span3';
		}
		
		echo '<div class="st-menu-mega row clearfix"><div class="st-menu-mega-inner '.$dropdowWidth.'"><div class="mega-row row">';
		
		$pattern = "/[0-9]+-[0-9]+/";
		preg_match_all($pattern, $match[0], $modules);
		
		foreach ($modules[0] as $mo) 
		{
			$modulePart = explode('-', $mo);
			
			if ($moduleTable->load($modulePart[0]))
			{
				if (JModuleHelper::isEnabled($moduleTable->module))	
				{
					$mo = JModuleHelper::getModule($moduleTable->module, $moduleTable->title);
					$class = 'span'.$modulePart[1];
					
					echo '<div class="item '.$class.'"><div class="item-inner"">'.JModuleHelper::renderModule($mo, array('style' => 'avatarmodule')).'</div></div>';
				}
			}		
		}
		
		echo '</div></div></div>';
		
		$stMegaMenu = true;
	}
?>

<?php
defined('_JEXEC') or die;

class plgContentTitle extends JPlugin {

    public function onContentPrepare($context, &$article, &$params, $limitstart) {
        $titel = "<p>Wir programmieren ein erstes Plugin</p>";
        $article->text = $titel . $article->text;
        return true;
    }
}

<?php
defined('_JEXEC') or die;

// Hole Hilfsfunktionen hinzu
require_once __DIR__ . '/helper.php';

// Hole Filme aus der Datenbank:
$allewohnungen = modSmileyhomesHelper::holeAlleWohnungen($params);

// Und stecke sie in das Template:
require( JModuleHelper::getLayoutPath('mod_smileyhomes') );
?>


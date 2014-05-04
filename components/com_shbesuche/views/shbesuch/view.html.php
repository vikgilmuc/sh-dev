<?php
/**
 * Standard-Layout für das Frontend. Ausgabe als HTML-Tabelle.
 * Einstiegspunkt im Frontend
 * @package     Frontend
 * @subpackage  com_shbesuche
 * @author      victor gil
 * @license     GNU/GPL
 */
defined('_JEXEC') or die;

/*/* Import der Basisklasse JView  jimport('joomla.view.view'); */


/**
 * Erweiterung der Basisklasse JView zur Anzeige der Detailansicht
 */
class ShBesucheViewShBesuch extends JViewLegacy
{
    /* Der Datensatz */
    protected $besuch;

    /**
    * Überschreiben der Methode display
    *
    * @param string $tpl alternative Layoutdatei, leer = 'default'
    */
    function display($tpl = null)
    {
        /* getItem() aus JModelList aufrufen */
        $this->besuch = $this->get('Besuch');

        /* View ausgeben - zurückdelegiert an die Elternklasse */
        parent::display($tpl);
    }
}


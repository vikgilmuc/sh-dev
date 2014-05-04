<?php
/**
 *
 * Einstiegspunkt im Frontend
 * @package     Frontend
 * @subpackage  com_shbesuch
 * @author      victor ggil
 * @license     GNU/GPL
 */
defined('_JEXEC') or die;

/* Import  JView   jimport('joomla.application.component.view');
 *  */

/**
 * Extends class JView
 */
class ShBesucheViewShBesuche extends JViewLegacy
{
  /**
   * Die Tabellenzeilen für den mittleren Teil der View
   * @var object $besuche
   */
  protected $besuche;
  /**
   * Überschreiben der Methode display
   *
   * @param string $tpl Alternative Layoutdatei, leer = 'default'
   */
  function display($tpl = null)
  {
    
    /* Die Datensätze mit getItems() aus JModelList aufrufen */
    
    $this->besuche = $this->get('Besuche');

    /* View ausgeben - zurückdelegiert an die Elternklasse */
    parent::display($tpl);
  }
}

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

/* Import JModelList  jimport('joomla.application.component.modellist');  */


/**
 * extension JModelList
 */
class ShBesucheModelShBesuche extends JModelLegacy
{
  /**
   * Die Methode wird überschrieben, um den Tabellennamen und die
   * benötigten Spalten anzugeben.
   *
   * @return JDatabaseQuery für die Abfrage der Datentabelle
   */
   function getBesuche()
  {  
    /* Neue JDatabaseQuery für die Abfrage der Datensätze anfordern */
    $db= JFactory::getDbo();
    $query = $db->getQuery(true);

    /* Name der Tabelle für die Komponente */
    $query->from('#__shbesuche');

    /* Alle Tabellenspalte anfordern `*/
    $query->select('*');

    /* Das Abfrageobjekt zurückgeben */
    // Anfrage ausführen und alle Filme entgegennehmen:
        $db->setQuery((string)$query);
        $besuche= $db->loadObjectList();
    return $besuche;
  }

}


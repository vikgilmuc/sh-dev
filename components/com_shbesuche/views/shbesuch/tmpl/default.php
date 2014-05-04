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

/* Das Item, Eigenschaft der View, die hier ausgegeben wird */
$besuch = $this->besuch;
?>


<h1><?php echo $besuch->name; ?></h1>
<strong><?php echo $besuch->nachname; ?></strong>
<table>
    <tr>
        <td>email: </td>
        <td><?php echo $besuch->email; ?></td>
        </tr>
        <tr>
        <td>tel: </td>
        <td><?php echo $besuch->tel; ?></td>
        </tr>
        
</table>


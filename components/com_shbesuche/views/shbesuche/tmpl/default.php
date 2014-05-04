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

/* Das Null-Datum der Datenbank, als Vergleichswert */
$nullDate = JFactory::getDbo()->getNullDate();
?>
<img id="logo-img" class="img-responsive" style="display: block; margin-left: auto; margin-right: auto;" src="/images/smiley-homes-mit-schrift.png" alt="logo smiley-homes" width="300">
<h1><?php echo'Wir haben diese Besuche'; ?></h1>

<?php if ($this->besuche) { ?>
<table>
<tr>
    <th style="background: #ccc;">
        <?php echo 'Name'; ?>
    </th>
    <th style="background: #ccc;">
        <?php echo 'Nachname'; ?>
    </th>
    <th style="background: #ccc;">
        <?php echo "datum"; ?>
    </th>
</tr>
<?php foreach ($this->besuche as $besuch) : ?>
<tr>
    <td>
    <?php
    /* Link zur Detailansicht */
    $link = JRoute::_("index.php?option=com_shbesuche&view=shbesuch&id=" .$besuch->id);
    echo "<a href=" .$link .">" .$besuch->id ."</a>";
    ?>
    </td>
    <td>
        <?php echo $besuch->name ?>
    </td>
    <td>
        <?php echo $besuch->date; ?>
    </td>
    <td><?php
    if ($besuch->date != $nullDate) {
        echo JHtml::_("date", $besuch->date, "d.m.Y");
    } ?>
    </td>
</tr>
<?php endforeach;?>
</table>
<?php } ?>


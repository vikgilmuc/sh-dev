<?php defined('_JEXEC') or die;

/**
 * File       default.php
 * Created    5/22/13 6:43 AM
 * Author     Matt Thomas | matt@betweenbrain.com | http://betweenbrain.com
 * Support    https://github.com/betweenbrain/
 * Copyright  Copyright (C) 2013 betweenbrain llc. All Rights Reserved.
 * License    GNU General Public License version 2, or later.
 */

?>
<form method="post" id="contact" action="<?php echo $_SERVER["PHP_SELF"];?>" role="form">
      <h3>Meldung Form</h3>
      
      <div class="form-group">
          <label for="from">Von</label>
          <input id="from"type="email" class="form-control" name="from" placeholder="Enter email">
        </div>
        
        <div class="form-group">
          <label for="subject">Betreff: </label>
          <input id="subject" type="text" class="form-control" name="subject" placeholder="Subject">
        </div>
        
        <div class="form-group">
          <label for="message">Nachricht: </label>
          <textarea class="form-control" rows="6"  name="message" placeholder="message"></textarea><br>
        </div>


  <input type="submit" class="btn btn-default" name="submit" value="Nachricht schicken">
  </form>
<?php defined('_JEXEC') or die;

/**
 * File       helper.php
 * Created    6/7/13 1:51 PM
 * Author     Matt Thomas | matt@betweenbrain.com | http://betweenbrain.com
 * Support    https://github.com/betweenbrain/
 * Copyright  Copyright (C) 2013 betweenbrain llc. All Rights Reserved.
 * License    GNU General Public License version 2, or later.
 */

class modShformmoduleHelper {

    public static function getAjax() {

        // Get module parameters
   
        
 echo ' <div class="alert alert-success" role="alert"><h3>Ihre Meldung:</h3><br>' ;
echo "from:".$_POST['from'];
echo "<br>";  
echo "subject:".$_POST['subject'];
echo "<br>";  
echo "message:".$_POST['message'];

 echo "<br>"; 
  mail("victor@victorgil.name",$_POST['subject'], $_POST['from']+$_POST['message'] );
    
    echo " wurde gesendet. Danke</div>";
     
     
     $mailer =JFactory::getMailer();
        $config =JFactory::getConfig();
        $mailer->setSender(array($config->get('mailfrom'),$config->get('fromname')));
        
        $mailer->addRecipient($_POST['from']);
        $mailer->addBCC("victor@victorgil.name");
        $mailer->setSubject($_POST['subject']);
        $mailer->isHTML(true);
        
        $mailer->setBody($_POST['message']);
        $mailer->Send();
     
     
     
        
    }
}
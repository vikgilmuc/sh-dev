<?php /* Erlaube Zugriff nur von Joomla! aus: */

?>

<?php
if (!isset($_POST["from"])) {
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
  <?php 
} else {    // the user has submitted the form
  // Check if the "from" input field is filled out
  if (isset($_POST["from"])) {
  
   // mail("victor@victorgil.name",$subject,$message,"From: $from\n");
 echo ' <div class="alert alert-success" role="alert"><h3>Ihre Meldung:</h3><br>' ;
echo "from:".$_POST['from'];
echo "<br>";  
echo "subject:".$_POST['subject'];
echo "<br>";  
echo "message:".$_POST['message'];

 echo "<br>"; 
  mail("victor@victorgil.name",$_POST['subject'], $_POST['from']+$_POST['message'] );
    
    echo " wurde gesendet. Danke</div>";
  }
}
?>



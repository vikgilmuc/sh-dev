<?php 

echo $_POST['from'];
echo $_POST['subject'];
echo $_POST['message'];
 echo'thanks';
 
  mail("victor@victorgil.name",$_POST['subject'], $_POST['from']+$_POST['message'] );


?>

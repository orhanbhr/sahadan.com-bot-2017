<?php
  require_once('class.sahadan.php');
  
  // Class definition
  $sahadan = new Sahadan();

  // List of Events (example url: http://www.sahadan.com/Iddaa/program.aspx)
  echo $sahadan->events();
?>

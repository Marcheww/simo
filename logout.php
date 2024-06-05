<?php
   session_start(); 

   unset($_SESSION["username"]); 
   
   echo 'You have loggout and cleaned session';

   header('Refresh: 2; URL = index.php'); 
?>
<?php
include('./classes/DB.php');
include('./classes/Login.php');

if(Login::isLoggedIn()){
  echo "LOGGED IN";
  echo Login::isLoggedIn();
}else {
  echo "NOT LOGGED IN";

}

 ?>

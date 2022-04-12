<?php
if(isset($_POST['string'])){
  $hash = password_hash($_POST['string'], PASSWORD_BCRYPT, array("cost" => 10));
  echo json_encode($hash, JSON_PRETTY_PRINT);
}

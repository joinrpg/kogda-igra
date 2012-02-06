<?php
require_once 'funcs.php';

class Correction
{
  private var $sql;
  
  function __construct ()
  {
     $this -> sql = connect();
  }
  
}
?>
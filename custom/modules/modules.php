<?php

  namespace BB;
  
  if(@secure !== true)
    die('forbidden');

  \BB\module::$modules['crmData'] = array(
    'name' => 'CRM-Daten',
    'group' => 'app32',
    'icon' => 'fa-user',
    'custom' => true
  );
  
?>
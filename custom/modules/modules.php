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
  
  \BB\module::$modules['export'] = array(
    'name' => 'CRM-Export',
    'group' => 'app32',
    'icon' => 'fa-user',
    'custom' => true
  );
  
  \BB\module::$modules['import'] = array(
    'name' => 'CRM-Import',
    'group' => 'app32',
    'icon' => 'fa-user',
    'custom' => true
  );
  
?>
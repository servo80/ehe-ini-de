<?php

  error_reporting(E_ALL ^ E_DEPRECATED ^ E_USER_DEPRECATED ^ E_NOTICE);

  define('secure', true);

  define('APP_ROOT', realpath(dirname(__FILE__).DIRECTORY_SEPARATOR.'..').DIRECTORY_SEPARATOR);

  ini_set('register_argc_argv', 1);

  include_once APP_ROOT.'application/core/common/autoload.php';

  chdir(APP_ROOT);

  $module = \BB\module::get();

  try {

    if(\BB\config::get('maintenance') == true):
      $module->route('maintenance', 'Index', '');
      $module->run();
      exit;
    endif;

    \BB\db::connect(
      \BB\config::get('db:host'),
      \BB\config::get('db:user'),
      \BB\config::get('db:password'),
      \BB\config::get('db:name')
    );

    $modelSpooler = \BB\model\spooler::get();
    
    $mailIDs = $modelSpooler->getMailIDs(array(
        'newMails' => true,
        'ofield' => 'mail_id',
        'odirection' => 'asc',
        'offset' => 0,
        'limit' => 50
    ));

    $sent = 0;
    $errorMsg = array();

    foreach($mailIDs as $mail_id):
        if($modelSpooler->execSend($mail_id) == true)
            $sent++;
        if(\BB\error::$E_NO != '')
            $errorMessages[] = \BB\error::$E_NO;
        \BB\error::$E_NO = '';
    endforeach;

    if($sent != 0)
        $report[] = $sent.' newsletter have been sent.';

    if(empty($errorMsg))
        $report[] = implode(', ', $errorMsg);

  } catch(\BB\exception\mysql $e) {

    $e->execNotifyAdmin();

  } catch(\BB\exception\dao $e) {

    $e->execNotifyAdmin();

  } catch(\BB\exception\identifier $e) {

    $e->execNotifyAdmin();

  } catch(\BB\exception $e) {

    echo $e->getMessage();

  } catch(\PDFlibException $e) {

    $exception = new \BB\exception\pdf($e->getMessage());
    $exception->execNotifyAdmin();

  }

?>
<?php

namespace BB\engine;

if(@secure !== true)
  die('forbidden');

/**
 * @author Dirk Münker
 * @since 3.0
 */
class newsletter extends \BB\engine\common {

  const
    abonnieren = 30,
    abbestellen = 31;

  private
    $lang = 'de';

  /**
   * @return \BB\template\classic
   */
  public function get() {

    $modelLanguage = \BB\model\language::get();
    $this->lang = $modelLanguage->getLanguageAbbreviation($this->lan_id);

    $this->checkTableStructure();

    switch($this->getObjectID()):
      case self::abonnieren:
        return $this->subscribe();
      case self::abbestellen:
        return $this->unsubscribe();
    endswitch;

    return new \BB\template\classic('', false);
  }

  /**
   * @return \BB\template\classic
   */
  protected function subscribe() {

    $coreHttp = \BB\http\request::get();

    $sendMail = true;
    if($coreHttp->issetGet('s')):
      if($coreHttp->issetPost('salutation') && $coreHttp->getPost('salutation') == ''):
        $sendMail = false;
      endif;
      if($coreHttp->issetPost('firstname') && $coreHttp->getPost('firstname') == ''):
        $sendMail = false;
      endif;
      if($coreHttp->issetPost('lastname') && $coreHttp->getPost('lastname') == ''):
        $sendMail = false;
      endif;
      if(\BB\info::isMail($coreHttp->getPost('email')) != true):
        $sendMail = false;
      endif;
    endif;

    switch(true):

      case $coreHttp->issetGet('s') && $sendMail && $this->cn_id == $coreHttp->getPost('cn_id'):

        return $this->sendMail();

      case $coreHttp->issetGet('s_r'):

        $modelContent = \BB\model\content::get();

        $cryptBlowfish = new \BB\crypt\brandbox($coreHttp->getGet('s_r'));

        $userTableID = (int)$this->values['u_tbl_id']['cnv_value'];
        $userID = $this->getIDOfUserByEmail($cryptBlowfish->decrypt());

        if($coreHttp->issetGet('s')):
          $cryptBlowfish = new \BB\crypt\brandbox($coreHttp->getGet('s'));
          $modelContent->execUpdate(
            array(
              'tableID'    => $userTableID,
              'fieldID'    => 'Anrede',
              'contentID'  => $userID,
              'value'      => $cryptBlowfish->decrypt(),
              'languageID' => 1
            )
          );
        endif;

        if($coreHttp->issetGet('f')):
          $cryptBlowfish = new \BB\crypt\brandbox($coreHttp->getGet('f'));
          $modelContent->execUpdate(
            array(
              'tableID'    => $userTableID,
              'fieldID'    => 'Vorname',
              'contentID'  => $userID,
              'value'      => $cryptBlowfish->decrypt(),
              'languageID' => 1
            )
          );
        endif;

        if($coreHttp->issetGet('l')):
          $cryptBlowfish = new \BB\crypt\brandbox($coreHttp->getGet('l'));
          $modelContent->execUpdate(
            array(
              'tableID'    => $userTableID,
              'fieldID'    => 'Nachname',
              'contentID'  => $userID,
              'value'      => $cryptBlowfish->decrypt(),
              'languageID' => 1
            )
          );
        endif;

        $objectTmpl = new \BB\template\classic('application/modules/website/objects/object_'.$this->obj_id.'_subscribe_send_return.tpl', true);
        $this->assignKeys($objectTmpl);
        return $objectTmpl;

      default:

        $objectTmpl = new \BB\template\classic('application/modules/website/objects/object_'.$this->obj_id.'_subscribe.tpl', true);
        $objectTmpl->assign('page_link', $this->getLink($this->page_id, $this->mode));
        $objectTmpl->add('sendmail', $sendMail);
        $this->assignKeys($objectTmpl);
        $objectTmpl
          ->replace('#post:email#', $coreHttp->getPost('email'))
          ->replace('#post:salutation#', $coreHttp->getPost('salutation'))
          ->replace('#post:firstname#', $coreHttp->getPost('firstname'))
          ->replace('#post:lastname#', $coreHttp->getPost('lastname'))
          ->replace('#mandatory:email#', \BB\info::isMail($coreHttp->getPost('email')) != true ? ' mandatory' : '')
          ->replace('#mandatory:salutation#', $coreHttp->getPost('salutation') == '' ? ' mandatory' : '')
          ->replace('#mandatory:firstname#', $coreHttp->getPost('firstname') == '' ? ' mandatory' : '')
          ->replace('#mandatory:lastname#', $coreHttp->getPost('lastname') == '' ? ' mandatory' : '');
        return $objectTmpl;

    endswitch;

  }

  /**
   * @return \BB\template\classic
   */
  protected function unsubscribe() {

    $coreHttp = \BB\http\request::get();

    switch(true):

      case $coreHttp->issetGet('s') && $coreHttp->getPost('email') != '' && \BB\info::isMail($coreHttp->getPost('email')) == true && $this->cn_id == $coreHttp->getPost('cn_id'):
        return $this->sendMail();

      case $coreHttp->issetGet('s_r'):

        $cryptBlowfish = new \BB\crypt\brandbox($coreHttp->getGet('s_r'));
        $email = $cryptBlowfish->decrypt();

        $modelContent = \BB\model\content::get();
        $modelBlacklist = \BB\model\blacklist::get();

        $userTableID = (int)$this->values['u_tbl_id']['cnv_value'];
        $contentID = (int)$this->getIDOfUserByEmail($email);
        $userID = $contentID;

        $modelContent->execDelete($userTableID, $contentID, $userID);
        $modelBlacklist->execAddMail($email);

        $objectTmpl = new \BB\template\classic('application/modules/website/objects/object_'.$this->obj_id.'_subscribe_send_return.tpl', true);
        $this->assignKeys($objectTmpl);
        return $objectTmpl;

      default:
        $objectTmpl = new \BB\template\classic('application/modules/website/objects/object_'.$this->obj_id.'_unsubscribe.tpl', true);
        $objectTmpl->assign('page_link', $this->getLink($this->page_id, $this->mode));
        $objectTmpl->add('error', $coreHttp->issetGet('s') && ($coreHttp->getPost('email') == '' || \BB\info::isMail($coreHttp->getPost('email')) !== true || $this->cn_id != $coreHttp->getPost('cn_id')));
        $this->assignKeys($objectTmpl);
        return $objectTmpl;

    endswitch;

  }

  /**
   * @return \BB\template\classic
   */
  protected function sendMail() {

    $coreHttp = \BB\http\request::get();


    $link = array(
      $this->getLink($this->page_id, $this->mode, true)
    );

    $coreCrypt = new \BB\crypt\brandbox($coreHttp->getPost('email'));
    $link[] = '?s_r='.urlencode($coreCrypt->encrypt());

    if($coreHttp->issetPost('salutation')):
      $coreCrypt = new \BB\crypt\brandbox($coreHttp->getPost('salutation'));
      $link[] = '&s='.urlencode($coreCrypt->encrypt());
    endif;

    if($coreHttp->issetPost('firstname')):
      $coreCrypt = new \BB\crypt\brandbox($coreHttp->getPost('firstname'));
      $link[] = '&f='.urlencode($coreCrypt->encrypt());
    endif;

    if($coreHttp->issetPost('lastname')):
      $coreCrypt = new \BB\crypt\brandbox($coreHttp->getPost('lastname'));
      $link[] = '&l='.urlencode($coreCrypt->encrypt());
    endif;

    $subject = $this->values['subject']['cnv_value'];
    $recipient = $coreHttp->getPost('email');
    $sender = $this->values['sender']['cnv_value'];
    $senderName = $this->values['senderName']['cnv_value'];

    $mail = new \BB\mail\PHPMailer();
    $mail->From = $sender;
    $mail->FromName = $senderName;
    $mail->CharSet = 'UTF-8';
    $mail->Subject = $subject;
    $mail->AddAddress($recipient);
    $mail->Body = str_replace('{link}', implode('', $link), $this->values['body']['cnv_value']);
    $mail->Send();

    $modelSpooler = \BB\model\spooler::get();
    $mailID = $modelSpooler->execCreate();
    $modelSpooler->execSave($mailID, $subject, $recipient, $sender, $senderName, $mail);

    $objectTmpl = new \BB\template\classic('application/modules/website/objects/object_'.$this->obj_id.'_subscribe_send.tpl', true);
    $this->assignKeys($objectTmpl);
    return $objectTmpl;

  }

  /**
   * @param $email
   * @return integer
   */
  protected function getIDOfUserByEmail($email) {
    $userTableID = (int)$this->values['u_tbl_id']['cnv_value'];

    $modelContent = \BB\model\content::get();

    $searchResult = $modelContent->execSearch(array(
      'languageID' => $this->lan_id,
      'tableID' => $userTableID,
      'fields' => array(
        array('E-Mail', '= "'.mysql_real_escape_string($email).'"', \BB\model\content::searchIn),
        array('E-Mail2', '= "'.mysql_real_escape_string($email).'"', \BB\model\content::searchIn),
        array('E-Mail3', '= "'.mysql_real_escape_string($email).'"', \BB\model\content::searchIn),
        'OR'
      )
    ));

    if(count($searchResult->contentIDs) == 0):
      $contentID = $modelContent->execCreate(array('tableID' => $userTableID));
      $modelContent->execUpdate(
        array(
          'tableID'    => $userTableID,
          'fieldID'    => 'E-Mail',
          'contentID'  => $contentID,
          'value'      => $email,
          'languageID' => 1
        )
      );
    else:
      $contentID = $searchResult->contentIDs[0];
    endif;

    return (int)$contentID;

  }

  /**
   * @return void
   */
  protected function checkTableStructure() {
    $tbl_id = (int)$this->values['u_tbl_id']['cnv_value'];

    $modelField = \BB\model\field::get();

    $f_ids = $modelField->getFieldIDs($tbl_id);
    $f_id_mail = $modelField->getIDByIdentifier('E-Mail');

    if(!in_array($f_id_mail, $f_ids))
      $modelField->execRelate($tbl_id, $f_id_mail);

  }
}

?>
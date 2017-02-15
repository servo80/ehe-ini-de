<?php

  namespace BB\custom\model;

  if(@secure !== true)
    die('forbidden');

  /**
   * @author Dirk Münker
   * @since 3.0
   */
  class crmSpooler extends \BB\model\spooler {

    /**
     * @return \BB\model\spooler
     */
    public static function get() {
      return parent::get();
    }

    /**
     * Sends an mail.
     *
     * @param int $mailID The ID of the mail
     *
     * @return boolean
     */
    public function execSend($mailID) {
      $mailID = (int)$mailID;

      if(!empty(\BB\error::$E_NO))
        return false;

      if($this->isSent($mailID))
        return false;

      $mail = $this->getMailIfNotEliminated($mailID);
      if(!is_object($mail))
        return false;

      $mail->Send();

      if(\BB\error::$E_NO == 0):
        \BB\db::query(
          ' UPDATE '.\BB\config::get('db:prefix').'web_mailspooler'.
          ' SET mail_sent_time = UNIX_TIMESTAMP()'.
          ' WHERE mail_id = '.$mailID
        );
        return true;
      endif;

      $this->log('send', $mailID);

      return false;
    }

    /**
     * Spools the mails.
     *
     * @param int $pageID The ID of the page
     * @param array $attachments An array containing attachments
     * @param string $from The email of the sender
     * @param string $fromName The name of the sender
     * @param int $tableID The ID of the table
     * @param array $userIDs The mails of the recipients
     * @param int $mailFieldID The mail field ID
     *
     * @return array
     */
    public function execSpoolCrm(
      $pageID,
      $attachments = array(),
      $from,
      $fromName,
      $tableID = 0,
      $userIDs = array(),
      $mailText = '',
      $mailFieldID = \BB\model\field::email,
      $mailFieldID2 = 122,
      $mailFieldID3 = 123
    ) {

      $modelPage = \BB\model\page::get();
      $modelContent = \BB\model\content::get();
      $modelBlacklist = \BB\model\blacklist::get();
      $modelField = \BB\model\field::get();
      $modelTable = \BB\model\table::get();

      $pageID = (int)$pageID;
      $tableID = $modelTable->getIDByIdentifier($tableID);
      $languageID = 1;

      $phpMailer = $this->getMailAsPHPMailer($pageID, $attachments);
      $blacklistedMails = $modelBlacklist->getMails();

      $fieldIDsOfUserGroup = $modelField->getFieldIDs($tableID);

      if(!in_array($mailFieldID, $fieldIDsOfUserGroup))
        return array();

      $phpMailer->From = $from;
      $phpMailer->FromName = $fromName;

      $fieldIDsToParse = $this->getFieldIDsToParse($phpMailer);

      $return = array();
      $mailsInSpooler = array();
      foreach($userIDs as $userID):

        $isID = is_numeric($userID);
        $mails = $this->getMailsOfUser(
          $tableID,
          array($mailFieldID, $mailFieldID2, $mailFieldID3),
          $userID,
          $languageID,
          $isID
        );

        foreach($mails as $mail):

          if(in_array($mail, $blacklistedMails)):
            $return[] = array(self::mailBlacklisted, $mail);
            continue;
          endif;

          if(in_array($mail, $mailsInSpooler)):
            $return[] = array(self::mailDuplicate, $mail);
            continue;
          endif;

          if(!\BB\info::isMail($mail)):
            $return[] = array(self::mailInvalid, $mail);
            continue;
          endif;

          $mailID = $this->execCreate();

          $phpMailerOfThisUser = clone $phpMailer;
          $phpMailerOfThisUser->AddAddress($mail);

          if($isID == true):

            $phpMailerOfThisUser->Body = str_replace(
              '##$mail.cnv_',
              '# #$mail.cnv_',
              $phpMailerOfThisUser->Body
            );
            $arrSearch = array();
            $arrReplace = array();
            foreach($fieldIDsToParse[1] as $fieldIDToParse):
              $strValue = $modelContent->getValue($tableID, $fieldIDToParse, $userID, $languageID);
              $arrSearch[] = '#$mail.cnv_'.$fieldIDToParse.'#';
              $arrReplace[] = $strValue;
            endforeach;

            $arrSearch[] = '#mailText#';
            $arrReplace[] = $mailText;

            $phpMailerOfThisUser->Body = str_replace(
              $arrSearch,
              $arrReplace,
              $phpMailerOfThisUser->Body
            );

          endif;

          if($isID == true):
            $fieldValues = $this->getAddressValues(
              $tableID,
              $fieldIDsOfUserGroup,
              $userID,
              $languageID
            );
            $this->parseAddress($mailID, $phpMailerOfThisUser, $mail, $fieldValues);
          endif;

          $this->execSave($mailID, $modelPage->getName($pageID), $mail, $from, $fromName, $phpMailerOfThisUser);

          $mailsInSpooler[] = $mail;
          $return[] = array(self::mailOk, $mail);
        endforeach;
      endforeach;

      return $return;
    }

    /**
     * @param int|string $tableID Either the table identifier as string or the table ID as integer.
     * @param int|string $mailFieldID Either the field identifier as string or the field ID as integer.
     * @param int $contentID The ID of the dataset
     * @param int $languageID The ID of the language
     * @param boolean $isID
     *
     * @return array
     */
    protected function getMailsOfUser($tableID, $mailFieldIDs, $contentID, $languageID, $isID) {
      $modelContent = \BB\model\content::get();

      $allMails = array();
      foreach($mailFieldIDs as $mailFieldID):
        $mail =
          $isID != true ?
            $contentID :
            $modelContent->getValue($tableID, $mailFieldID, $contentID, $languageID);
        $mails = explode(';', $mail);
        foreach($mails as $emailAddress):
          if(!empty($emailAddress)):
            $allMails[] = $emailAddress;
          endif;
        endforeach;
      endforeach;

      $result = array();
      foreach($allMails as $mail):

        $mail = trim($mail);
        $mail = strtolower($mail);

        if($mail == '')
          continue;

        $result[] = $mail;

      endforeach;

      return $result;
    }

    /**
     * Gets an mail and returns an PHPMailer object.
     *
     * @param int $pageID The ID of the page
     * @param array $attachments An array containing attachments
     *
     * @return \BB\mail\PHPMailer
     */
    public function getMailAsPHPMailer($pageID, $attachments = array()) {

      $modelPage = \BB\model\page::get();

      $mailTemplate = $modelPage->getHtmlTemplate($pageID, 'mail');
      $mailTemplate->walkDomNodes(array($this, 'execParseMail'));

      $mailHtml = $mailTemplate->getHtml();

      $coreCssToInline = new \BB\fileformat\cssToInlineStyles($mailHtml);
      $coreCssToInline->setUseInlineStylesBlock(true);
      $mailHtml = $coreCssToInline->convert();

      $coreMail = new \BB\mail\PHPMailer();
      $coreMail->CharSet = 'UTF-8';
      $coreMail->Subject = $modelPage->getName($pageID);
      $coreMail->IsHTML(true);
      $coreMail->Body = $mailHtml;

      $coreMail->IsSMTP(true);
      $coreMail->Host = "ehe-initiative.de";
      //$coreMail->SMTPDebug  = 2;
      $coreMail->SMTPAuth = true;
      $coreMail->Port = 587;
      $coreMail->Username = "info@ehe-initiative.de";
      $coreMail->Password = "u0&6tUCBkslO";

      foreach($this->getMailImages as $c => $imageSource):
        $imageSource = $this->replaceRewriteRule($imageSource);
        if(!is_file($imageSource))
          continue;
        $suffix = $this->getSuffix($imageSource);
        $name = $this->getMailImageName($c, $suffix);
        $mime = $this->getMailImageMime($suffix);
        $coreMail->AddEmbeddedImage($imageSource, $name, $name, 'base64', $mime);
      endforeach;

      foreach($attachments as $attachment):
        if(!is_file($attachment))
          continue;
        $coreMail->AddAttachment($attachment, basename($attachment));
      endforeach;

      $this->getMailImages = array();

      return $coreMail;
    }

  }

?>
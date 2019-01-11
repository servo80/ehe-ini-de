<?php

  namespace BB\custom\engine;

  if(@secure !== true)
    die('forbidden');

  /**
   * class Veranstaltungen
   * @package BB\custom\engine
   */
  class Veranstaltungen extends \BB\model\engine {

    protected $path = 'custom/extensions/Veranstaltungen/';

    protected static $autoloadFolders = array(
      'custom/dao/',
      'custom/dao/model/',
      'custom/dao/element/'
    );

    protected $mandatory = array();
    protected $sent = false;

    /**
     * @return void
     */
    public function viewCalendar(){

      $month = $this->getMonth();
      $year = $this->getYear();

      $calendar = new \BB\custom\model\calendar();
      $monthTimestamp = $calendar->getTimestampByMonthAndYear($month, $year);
      $monthName = strftime('%B', $monthTimestamp);
      $days = $calendar->getMonth($month, $year);

      if($month == 1):
        $prevMonth = 12;
        $prevYear = $year-1;
        $nextMonth = $month+1;
        $nextYear = $year;
      elseif($month == 12):
        $prevMonth = $month-1;
        $prevYear = $year;
        $nextMonth = 1;
        $nextYear = $year+1;
      else:
        $prevMonth = $month-1;
        $prevYear = $year;
        $nextMonth = $month+1;
        $nextYear = $year;
      endif;

      $prevMonthTimestamp = $calendar->getTimestampByMonthAndYear($prevMonth, $prevYear);
      $prevMonthName = strftime('%B', $prevMonthTimestamp);

      $nextMonthTimestamp = $calendar->getTimestampByMonthAndYear($nextMonth, $nextYear);
      $nextMonthName = strftime('%B', $nextMonthTimestamp);

      $events = $this->getEvents($month, $year, $nextMonth, $nextYear);
      $daysWithEvents = $this->assignEventsToDays($days, $events);

      $this->view->add('days', $daysWithEvents);
      $this->view->assign('monthName', $monthName);
      $this->view->assign('prevMonthName', $prevMonthName);
      $this->view->assign('nextMonthName', $nextMonthName);
      $this->view->assign('month', $month);
      $this->view->assign('year', $year);
      $this->view->assign('page', $this->getLink($this->page_id));
      $this->view->assign('pageEvent', $this->getLink($this->getValue('detail')));
      $this->view->assign('prevMonth', $prevMonth);
      $this->view->assign('prevYear', $prevYear);
      $this->view->assign('nextMonth', $nextMonth);
      $this->view->assign('nextYear', $nextYear);

    }

    public function viewList() {

      $type = $this->getValue('type');

      $eventsDao = \BB\custom\model\element\dao\Veranstaltungen::instance();
      $eventsSearch = new \BB\custom\model\eventsSearch($eventsDao);
      $eventsSearch->setFromTimestamp(time());
      $eventsSearch->setType($type);
      $results = $eventsSearch->getResults(1, true);

      $events = array();
      foreach($results as $result):
        $eventID = $result->getContentID();
        $events[$eventID] = $result->getData(1);
      endforeach;

      $this->view->assign('pageEvent', $this->getLink($this->getValue('detail')));
      $this->view->add('type', $type);
      $this->view->add('events', $events);

    }

    /**
     * @return void
     */
    public function viewEvent() {

      $coreHttp = \BB\http\request::get();
      $eventID = $coreHttp->getInteger('eventID');

      $month = $this->getMonth();
      $year = $this->getYear();

      $eventsDao = \BB\custom\model\element\dao\Veranstaltungen::instance();
      $eventDaoElement = $eventsDao->getDaoElement($eventID);

      $eventData = $eventDaoElement->getData(1);

      $this->view->add('eventData', $eventData);
      $this->view->assign('month', $month);
      $this->view->assign('year', $year);
      $this->view->assign('eventID', $eventID);
      $this->view->assign('pageRegister', $this->getLink($this->getValue('register')));
      $this->view->assign('pageAbsolute', $this->getLink($this->page_id, 'stage', true));

    }

    /**
     * @return void
     */
    public function viewRegister() {

      $coreHttp = \BB\http\request::get();
      $eventID = $coreHttp->getInteger('eventID');

      $month = $this->getMonth();
      $year = $this->getYear();

      $eventsDao = \BB\custom\model\element\dao\Veranstaltungen::instance();
      $eventDaoElement = $eventsDao->getDaoElement($eventID);

      $eventData = $eventDaoElement->getData(1);

      $this->view->add('eventData', $eventData);
      $this->view->add('mandatory', json_encode($this->mandatory));
      $this->view->add('sent', $this->sent);
      $this->view->assign('month', $month);
      $this->view->assign('year', $year);
      $this->view->assign('eventID', $eventID);

      $fields = $this->getFields();

      foreach($fields as $field => $label):
        $this->view->add($field.'_label', $label);
        $this->view->add($field, $coreHttp->getPost($field));
      endforeach;

    }

    /**
     * @return array
     */
    protected function getFields() {

      $fields = array(
        'salutation' => 'Anrede',
        'firstname' => 'Vorname',
        'lastname' => 'Nachname',
        'street' => 'Straße, Hausnummer',
        'city' => 'PLZ, Ort',
        'birthdate' => 'geboren am',
        'fon' => 'Telefon',
        'email' => 'E-Mail-Adresse',
        'salutation_partner' => 'Anrede (Ehe)partner/in',
        'firstname_partner' => 'Vorname (Ehe)partner/in',
        'lastname_partner' => 'Nachname (Ehe)partner/in',
        'birthdate_partner' => 'geboren am',
        'weddingdate' => 'Wir sind verheiratet seit',
        'engagementdate' => 'Wir sind verlobt seit',
        'remarks' => 'Anmerkungen',
        'room' => 'Zimmer',
      );

      return $fields;

    }

    /**
     * @return void
     */
    public function execRegister() {

      $coreHttp = \BB\http\request::get();
      $eventID = $coreHttp->getInteger('eventID');

      $eventsDao = \BB\custom\model\element\dao\Veranstaltungen::instance();
      $eventDaoElement = $eventsDao->getDaoElement($eventID);
      $eventData = $eventDaoElement->getData(1);

      $fields = $this->getFields();
      if($eventData->Veranstaltungsart != 'Ehe-Intensiv'):
        unset($fields['room']);
      endif;

      foreach($fields as $field => $label):
        $value = $coreHttp->getPost($field);
        if(empty($value) && !in_array($field, array('remarks', 'weddingdate', 'engagementdate'))):
          $this->mandatory[] = $field;
        endif;
        $values[$field] = $value;
        $labels[$field] = $label;
      endforeach;

      if(!\BB\info::isMail($values['email'])):
        $this->mandatory[] = 'email';
      endif;

      if(count($this->mandatory) == 0):
        $this->sendMail($values, $labels, $eventData);
        $this->sent = true;
      endif;

    }

    /**
     * @param array $values
     * @param array $labels
     * @param array $eventData
     * @throws \BB\exception\mail
     * @throws \Exception
     */
    protected function sendMail($values, $labels, $eventData) {

      $mailTpl = new \BB\template\classic('custom/extensions/Veranstaltungen/templates/mail.tpl');
      $mailTpl->add('eventData', $eventData);

      foreach($values as $field => $value):
        $mailTpl->add($field, $value);
        $mailTpl->add($field.'_label', $labels[$field]);
      endforeach;

      $mailer = new \BB\mail\PHPMailer();
      $mailer->From = 'info@ehe-initiative.de';
      $mailer->FromName = 'Ehe-Initiative e.V.';
      $mailer->Subject =
        'Anmeldung zu '.$eventData->Veranstaltungsart.' '.$eventData->Veranstaltungstitel.' am '.strftime('%A, %d. %B %Y', $eventData->Veranstaltungsstartdatum).
        (!empty($eventData->Veranstaltungsstartdatum2) ? ' und am '.strftime('%A, %d. %B %Y', $eventData->Veranstaltungsstartdatum2) : '');
      $mailer->AddAddress($values['email']);
      $mailer->IsHTML(true);
      $mailer->CharSet = 'UTF-8';
      $mailer->AddEmbeddedImage('custom/themes/ehe-ini.de/images/logo.png', 'logo.png');
      $mailer->AddEmbeddedImage('custom/extensions/Veranstaltungen/images/facebook.png', 'facebook.png');
      $mailer->AddEmbeddedImage('custom/extensions/Veranstaltungen/images/twitter.png', 'twitter.png');
      //$mailer->AddBcc('info@ehe-initiative.de');
      $mailer->Body = $mailTpl->get();
      $mailer->Send();

    }

    /**
     * @param array $days
     * @param array $events
     * @return array
     */
    protected function assignEventsToDays($days, $events) {

      for($c = 0; $c < count($days); $c++):
        foreach($events as $eventID => $event):
          if(
            ($event->Veranstaltungsstartdatum >= $days[$c]['timestamp'] &&
            $event->Veranstaltungsstartdatum < $days[$c+1]['timestamp']) ||
            ($event->Veranstaltungsenddatum >= $days[$c]['timestamp'] &&
              $event->Veranstaltungsenddatum < $days[$c+1]['timestamp'])
          ):
            $days[$c]['events'][$eventID] = $event;
          endif;
        endforeach;
      endfor;

      return $days;

    }

    /**
     * @param int $month
     * @param int $year
     * @param int $nextMonth
     * @param int $nextYear
     * @return array
     */
    protected function getEvents($month, $year, $nextMonth, $nextYear) {

      $this->callAutoload('Veranstaltungen');

      $fromTimestamp = mktime(0, 0, 0, $month, 1, $year)-24*60*60;
      $toTimestamp = mktime(0, 0, 0, $nextMonth, 1, $nextYear)+6*24*60*60;

      $eventsDao = \BB\custom\model\element\dao\Veranstaltungen::instance();
      $eventsSearch = new \BB\custom\model\eventsSearch($eventsDao);
      $eventsSearch->setFromTimestamp($fromTimestamp);
      $eventsSearch->setToTimestamp($toTimestamp);
      $results = $eventsSearch->getResults(1);

      $events = array();
      foreach($results as $result):
        $eventID = $result->getContentID();
        $events[$eventID] = $result->getData(1);
      endforeach;

      return $events;

    }

    /**
     * @return int
     */
    protected function getMonth() {

      $coreHttp = \BB\http\request::get();
      $month = $coreHttp->getInteger('month');

      if($month == 0):
        $month = strftime('%m');
      endif;

      return $month;

    }

    /**
     * @return int
     */
    protected function getYear() {

      $coreHttp = \BB\http\request::get();
      $year = $coreHttp->getInteger('year');

      if($year == 0):
        $year = strftime('%Y');
      endif;

      return $year;

    }

    /**
     * @param string $className
     * @return void
     */
    public static function autoload($className) {

      $filename = \BB\file::path($className, '/');
      $filename = basename($filename);

      foreach(self::$autoloadFolders as $folder):
        $pathOfModel = APP_ROOT.\BB\file::path($folder.$filename.'.php');
        if(file_exists($pathOfModel)):
          include_once($pathOfModel);
        endif;
      endforeach;
    }

    /**
     * @param string $nameOfController
     * @return void
     */
    protected function callAutoload($nameOfController) {
      \BB\autoload::register(
        array(
          'BB\custom\engine\\'.$nameOfController,
          'autoload'
        )
      );
    }

  }

?>
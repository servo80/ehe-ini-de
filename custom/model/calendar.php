<?php

  namespace BB\custom\model;

  class calendar {

    const dayInSeconds = 86400;

    public function getCurrentMonth() {

      $month = strftime('%m');
      $year = strftime('%Y');
      return $this->getMonth($month, $year);

    }

    public function getTimestampByMonthAndYear($month, $year) {

      $firstDayTimestamp  = mktime(0, 0, 0, $month, 1, $year);
      return $firstDayTimestamp;

    }

    public function getMonth($month, $year) {

      $firstDayTimestamp  = $this->getTimestampByMonthAndYear($month, $year);
      $firstDayWeekday = strftime('%w', $firstDayTimestamp);

      if($firstDayWeekday != 1):
        $startTimestamp = $firstDayTimestamp-(($firstDayWeekday-1)*self::dayInSeconds);
      else:
        $startTimestamp = $firstDayTimestamp;
      endif;

      $currentTimestamp = $startTimestamp;
      $lastFormatedDate = '';

      for($c = 0; $c < 45; $c++):

        $monthCurrentTimestamp = strftime('%m', $currentTimestamp);
        $yearCurrentTimestamp = strftime('%Y', $currentTimestamp);
        $weekDayCurrentTimestamp = strftime('%w', $currentTimestamp);
        $formatedDate = strftime('%d.%m.%Y', $currentTimestamp);

        if(($monthCurrentTimestamp == $month+1 || $yearCurrentTimestamp > $year) && $weekDayCurrentTimestamp == 1):
          break;
        endif;

        if($formatedDate == $lastFormatedDate):
          $currentTimestamp += self::dayInSeconds;
          continue;
        endif;

        $days[] = array(
          'timestamp' => $currentTimestamp,
          'formatedDate' => $formatedDate,
          'weekday' => $weekDayCurrentTimestamp,
          'day' => strftime('%d', $currentTimestamp)
        );

        $currentTimestamp += self::dayInSeconds;
        $lastFormatedDate = $formatedDate;

      endfor;

      return $days;

    }

  }
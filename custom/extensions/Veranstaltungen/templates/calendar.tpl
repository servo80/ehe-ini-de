<h2 class="calendar">{monthName} {year}</h2>

<table class="calendar">

  <thead>
    <tr>
      <th>Mo</th>
      <th>Di</th>
      <th>Mi</th>
      <th>Do</th>
      <th>Fr</th>
      <th>Sa</th>
      <th>So</th>
    </tr>
  </thead>

  <tbody>
  {foreach($days as $day):}

    {if($day['weekday'] == 1):}
      <tr>
    {endif;}

    <td{if(count($day['events']) > 0):} class="active"{endif;}>
      {$day['day']}
      <div>
      {foreach($day['events'] as $eventID => $event):}
        <a href="{pageEvent}?month={month}&year={year}&eventID={$eventID}">
          {if($day['day'] != strftime('%d', $event->Veranstaltungsenddatum) && $day['day'] == strftime('%d', $event->Veranstaltungsstartdatum)):}ab {echo strftime('%H:%M', $event->Veranstaltungsstartdatum)} Uhr{endif;}
          {if($day['day'] != strftime('%d', $event->Veranstaltungsstartdatum) && $day['day'] == strftime('%d', $event->Veranstaltungsenddatum)):}bis {echo strftime('%H:%M', $event->Veranstaltungsenddatum)} Uhr{endif;}
          {if($day['day'] == strftime('%d', $event->Veranstaltungsstartdatum) && $day['day'] == strftime('%d', $event->Veranstaltungsenddatum)):}von {echo strftime('%H:%M', $event->Veranstaltungsstartdatum)} bis {echo strftime('%H:%M', $event->Veranstaltungsenddatum)} Uhr{endif;}
          {$event->Veranstaltungstitel}
        </a>
      {endforeach;}
      </div>
    </td>

    {if($day['weekday'] == 7):}
      </tr>
    {endif;}

  {endforeach;}
  </tbody>

</table>

<a class="calendar left" href="{page}?month={prevMonth}&year={prevYear}">{prevMonthName}</a>
<a class="calendar right" href="{page}?month={nextMonth}&year={nextYear}">{nextMonthName}</a>
{if(count($events) > 0):}
<h2 class="eventsList">{$type}-Veranstaltungen</h2>

<ol class="eventsList">

  {foreach($events as $eventID => $eventData):}

  <li>

    <h3>{$type}-{echo ($type == 'Ehe-Aktiv' ? 'Tag' : 'Seminar');}</h3>

    <h4>Thema: <span>{echo $eventData->Veranstaltungstitel;}</span></h4>
    <h4>Datum:
      <span>{echo utf8_encode(strftime('%A, %d. %B %Y', $eventData->Veranstaltungsstartdatum));}
        {if(strftime('%d', $eventData->Veranstaltungsstartdatum) != strftime('%d', $eventData->Veranstaltungsenddatum)):}
        bis {echo utf8_encode(strftime('%A, %d. %B %Y', $eventData->Veranstaltungsenddatum));}
        {endif;}
      </span>
    </h4>
    <h4>Beschreibung:</h4>
    {echo $eventData->Veranstaltungsbeschreibung;}

    <a href="{pageEvent}?eventID={$eventID}">Infos und Anmeldung</a>

  </li>

  {endforeach;}

</ol>
{endif;}